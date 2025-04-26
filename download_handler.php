<?php
require_once 'pdo.php';
if(isset($_GET['imageId'])){
	$imageId = $_GET['imageId'];
}else{
	exit();
}


$stmt = $pdo->prepare("SELECT file_name FROM images WHERE id = ?");
$stmt->execute([$imageId]);
$image = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$image) {
    http_response_code(404);
    echo json_encode(["error" => "Image not found"]);
    exit;
}

$filename = $image['file_name'];
$filepath = __DIR__."/uploads/Images/" . $filename;
echo $filepath;
if (!file_exists($filepath)) {
    http_response_code(404);
    echo json_encode(["error" => "File does not exist"]);
    exit;
}

$finfo = finfo_open(FILEINFO_MIME_TYPE);
$mimeType = finfo_file($finfo, $filepath);
finfo_close($finfo);

// Set headers
header('Content-Description: File Transfer');
header('Content-Type: ' . $mimeType);
header('Content-Disposition: attachment; filename="' . basename($filepath) . '"');
header('Content-Length: ' . filesize($filepath));

ob_clean();
flush();

readfile($filepath);
exit;
?>
