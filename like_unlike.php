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

if ($action === 'like') {
    $pdo->prepare("INSERT IGNORE INTO likes (user_id, image_id, created_at) VALUES (?, ?, NOW())")
        ->execute([$userId, $imageId]);

    $pdo->prepare("UPDATE images SET likes = likes + 1 WHERE id = ?")->execute([$imageId]);
} elseif ($action === 'unlike') {
    $pdo->prepare("DELETE FROM likes WHERE user_id = ? AND image_id = ?")
        ->execute([$userId, $imageId]);

    $pdo->prepare("UPDATE images SET likes = likes - 1 WHERE id = ? AND likes > 0")->execute([$imageId]);
}

echo 'success';
?>
