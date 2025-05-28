<?php
// In your add_comment.php - store everything in UTC
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

    $ch = curl_init('https://0shiro-momentospace.hf.space/check-text');
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

    // Set timezone to UTC for consistent database storage
    date_default_timezone_set('UTC');
    
    $stmt = $pdo->prepare("INSERT INTO comments (user_id, image_id, comment) VALUES (?, ?, ?)");
    if ($stmt->execute([$userId, $imageId, $comment])) {
        $commentId = $pdo->lastInsertId();
        
        // Get the timestamp and return it as ISO format for JavaScript
        $stmt_time = $pdo->prepare("SELECT created_at FROM comments WHERE id = ?");
        $stmt_time->execute([$commentId]);
        $commentData = $stmt_time->fetch();
        
        echo json_encode([
            'success' => true,
            'comment' => [
                'id' => $commentId,
                'user' => $_SESSION['data']['username'],
                'text' => htmlspecialchars($comment),
                'timestamp' => $commentData['created_at'],
                'time' => 'Just now' 
            ]
        ]);
        exit;
    } else {
        http_response_code(500);
        echo json_encode(['error' => 'Database error']);
    }
}

function timeAgo($utc_datetime) {
    date_default_timezone_set('UTC');
    $now = new DateTime();
    $commentTime = new DateTime($utc_datetime);
    $diff = $now->diff($commentTime);

    if ($diff->y > 0) return $diff->y . ' year' . ($diff->y > 1 ? 's' : '') . ' ago';
    if ($diff->m > 0) return $diff->m . ' month' . ($diff->m > 1 ? 's' : '') . ' ago';
    if ($diff->d > 0) return $diff->d . ' day' . ($diff->d > 1 ? 's' : '') . ' ago';
    if ($diff->h > 0) return $diff->h . ' hour' . ($diff->h > 1 ? 's' : '') . ' ago';
    if ($diff->i > 0) return $diff->i . ' minute' . ($diff->i > 1 ? 's' : '') . ' ago';
    return 'just now';
}
?>