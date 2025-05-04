<?php
session_start();
require('pdo.php'); // Database connection

// Ensure the user is logged in
if (!isset($_SESSION['data'])) {
    header('location:index.php');
    exit();
}
$userId = $_SESSION['data']['id'];
$imageId = $_POST['image_id'];
$action = $_POST['action'];
$stmt = $pdo->prepare('SELECT user_id FROM images WHERE id = ?');
$stmt->execute([$imageId]);
$photographer = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$photographer) {
    exit('error: image not found');
}

$photographer_id = $photographer['user_id'];
if ($action === 'like') {
    $pdo->prepare("INSERT IGNORE INTO likes (user_id, image_id, created_at) VALUES (?, ?, NOW())")
        ->execute([$userId, $imageId]);
	
    $pdo->prepare("UPDATE images SET likes = likes + 1 WHERE id = ?")->execute([$imageId]);
	$pdo->prepare("UPDATE business_profiles SET total_likes = total_likes + 1 WHERE id = ?")->execute([$photographer_id]);
} elseif ($action === 'unlike') {
    $pdo->prepare("DELETE FROM likes WHERE user_id = ? AND image_id = ?")
        ->execute([$userId, $imageId]);

    $pdo->prepare("UPDATE images SET likes = likes - 1 WHERE id = ? AND likes > 0")->execute([$imageId]);
	$pdo->prepare("UPDATE business_profiles SET total_likes = total_likes - 1 WHERE id = ? AND total_likes > 0")->execute([$photographer_id]);
}

echo 'success';
?>
