<?php
session_start();
require('pdo.php');

if (!isset($_SESSION['data'])) {
    http_response_code(403);
    exit('Not logged in');
}

$userId = $_SESSION['data']['id'];
$commentId = $_POST['comment_id'];
$action = $_POST['action'];

if ($action === 'like') {
    $pdo->prepare("INSERT IGNORE INTO comment_likes (user_id, comment_id) VALUES (?, ?)")
        ->execute([$userId, $commentId]);

    $pdo->prepare("UPDATE comments SET likes = likes + 1 WHERE id = ?")
        ->execute([$commentId]);
} elseif ($action === 'unlike') {
    $pdo->prepare("DELETE FROM comment_likes WHERE user_id = ? AND comment_id = ?")
        ->execute([$userId, $commentId]);

    $pdo->prepare("UPDATE comments SET likes = GREATEST(likes - 1, 0) WHERE id = ?")
        ->execute([$commentId]);
}

echo 'success';
