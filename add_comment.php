<?php
header('Content-Type: application/json');
ini_set('display_errors', 0);
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

    $ch = curl_init('http://localhost:5000/check-text');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode(['text' => $comment]));
    $response = curl_exec($ch);
    curl_close($ch);

    $result = json_decode($response, true);

    if (!isset($result['censored'])) {
        http_response_code(500);
        echo json_encode(['error' => 'Profanity check failed']);
        exit;
    }

    $comment = $result['censored'];

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
