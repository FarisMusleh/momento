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


$categories = ['war', 'gaza', 'palestine'];//CATEGORIES

//PAGINATION
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$perPage = isset($_GET['per_page']) ? (int)$_GET['per_page'] : 10;
$page = max($page, 1);
$perPage = min(max($perPage, 1), 50);
$offset = ($page - 1) * $perPage;//CALCULATE-FOR-PAGINATION
$days = 7; //WEEK

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

//CONVERT
$baseUrl = '/momento/uploads/Images/';
foreach ($images as &$image) {
    $image['url'] = $baseUrl . $image['file_name'];
    $image['label'] = json_decode($image['label']);
}
unset($image);

// --- JSON Response ---
echo json_encode([
    "categories" => $categories,   //CATEGORIES
    "page" => $page,               //NUMBER-OF-PAGES PAGINATION
    "per_page" => $perPage,        //IMAGES-PER-PAGE  PAGINATION
    "count" => count($images),     //RESULT-NUMBER-OF-IMAGES
    "images" => $images            //ARRAY-OF-IMAGES
], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);

exit;
?>
