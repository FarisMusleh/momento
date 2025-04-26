<?php
require_once '../pdo.php';
header('Content-Type: application/json');

// API-KEY-CHECK
/*
$headers = getallheaders();
$apiKey = isset($headers['X-API-Key']) ? $headers['X-API-Key'] : null;

if (!$apiKey) {
    http_response_code(401);
    echo json_encode(["error" => "API key missing"]);
    exit;
}

$stmt = $pdo->prepare("SELECT user_id FROM api_keys WHERE api_key = ? AND is_active = 1");
$stmt->execute([$apiKey]);
$keyInfo = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$keyInfo) {
    http_response_code(401);
    echo json_encode(["error" => "Invalid or inactive API key"]);
    exit;
}
*/


//SIMILAR-CATEGORIES-AND-CACHING
function fetch_similar_categories_with_cache($query) {
    $cacheDir = __DIR__ . '/cache';
    if (!is_dir($cacheDir)) {
        mkdir($cacheDir, 0755, true); // Create cache folder if it doesn't exist
    }

    $cacheFile = $cacheDir . '/category_' . md5($query) . '.json';
    $cacheDuration = 300; // Cache for 300 seconds (5 minutes)

    // Check if cache exists and is fresh
    if (file_exists($cacheFile) && (time() - filemtime($cacheFile)) < $cacheDuration) {
        $data = json_decode(file_get_contents($cacheFile), true);
        if (isset($data['similar_categories'])) {
            return $data['similar_categories'];
        }
    }

    //IF-NO-VALID-CACHE-CALL-PYTHON-API
    $url = 'http://127.0.0.1:5000/search?query=' . urlencode($query);

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $response = curl_exec($ch);

    if (curl_errno($ch)) {
        curl_close($ch);
        return [];
    }

    curl_close($ch);

    $data = json_decode($response, true);

    if (isset($data['similar_categories'])) {
        file_put_contents($cacheFile, json_encode($data));
        return $data['similar_categories'];
    }

    return [];
}

//GET-CATEGORIES
$categoryInput = isset($_GET['category']) ? $_GET['category'] : 'war';

//OPERATION-ON-THE-CATEGORIES
if (is_array($categoryInput)) {
    $categories = $categoryInput;
} else {
    //SIMILAR-CATEGORIES
    $smartCategories = fetch_similar_categories_with_cache($categoryInput);

    if (!empty($smartCategories)) {
        $categories = $smartCategories;
    } else {
        $categories = explode(',', $categoryInput);
    }
}

//PAGINATION
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$perPage = isset($_GET['per_page']) ? (int)$_GET['per_page'] : 10;
$page = max($page, 1);
$perPage = min(max($perPage, 1), 50);

$offset = ($page - 1) * $perPage;
$days = 7;//WEEK

//DYNAMIC-SQL
$whereParts = [];
$params = [
    ':days' => $days,
    ':offset' => $offset,
    ':perPage' => $perPage
];

foreach ($categories as $index => $cat) {
    $paramName = ":cat$index";
    $whereParts[] = "JSON_SEARCH(label, 'one', $paramName) IS NOT NULL";
    $params[$paramName] = $cat;
}

$whereSql = implode(' OR ', $whereParts);

//FINAL-SQL
$sql = "
    SELECT id, file_name, label, likes, created_at
    FROM images
    WHERE ($whereSql)
      AND created_at >= NOW() - INTERVAL :days DAY
    ORDER BY likes DESC, created_at DESC
    LIMIT :offset, :perPage
";

$stmt = $pdo->prepare($sql);

//BINDING
foreach ($params as $key => $value) {
    $stmt->bindValue($key, $value, is_int($value) ? PDO::PARAM_INT : PDO::PARAM_STR);
}

$stmt->execute();
$images = $stmt->fetchAll(PDO::FETCH_ASSOC);

//CONVERTING
$baseUrl = '/momento/uploads/Images/';
foreach ($images as &$image) {
    $image['url'] = $baseUrl . $image['file_name'];
    $image['label'] = json_decode($image['label']);
}
unset($image);

//JSON
echo json_encode([
    "original_query" => $categoryInput,
    "resolved_categories" => $categories,
    "page" => $page,
    "per_page" => $perPage,
    "count" => count($images),
    "images" => $images
], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
exit;
?>
