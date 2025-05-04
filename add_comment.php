<?php
header('Content-Type: application/json'); // Force JSON response
ini_set('display_errors', 0);              // Don't show warnings in response
error_reporting(E_ALL);  
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require 'pdo.php'; 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userId = $_SESSION['data']['id'] ?? null;
    $imageId = $_POST['image_id'] ?? null;
    $comment = trim($_POST['comment'] ?? '');

    if (!$userId || !$imageId || $comment === '') {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid input']);
        exit;
    }
    $stmt = $pdo->prepare("INSERT INTO comments (user_id, image_id, comment) VALUES (?, ?, ?)");
    if ($stmt->execute([$userId, $imageId, $comment])) {
        echo json_encode([
            'success' => true,
            'comment' => [
                'user' => $_SESSION['data']['username'],
                'text' => htmlspecialchars($comment),
                'time' => 'Just now'
            ]
        ]);
		exit;
    } else {
        http_response_code(500);
        echo json_encode(['error' => 'Database error']);
    }
}
?>