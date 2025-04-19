<?php
session_start();
require_once '../pdo.php';
$sender_id = $_SESSION['data']['id'] ?? 0;
$receiver_id = $_POST['receiver_id'] ?? 0;
$message = trim($_POST['message'] ?? '');

if ($sender_id && $receiver_id && $message) {
    // Get or create conversation
    $stmt = $pdo->prepare("SELECT id FROM conversations 
        WHERE (sender_id = :sender AND receiver_id = :receiver) 
        OR (sender_id = :receiver AND receiver_id = :sender)
        LIMIT 1");
    $stmt->execute([
        ':sender' => $sender_id,
        ':receiver' => $receiver_id
    ]);
    $conversation = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$conversation) {
        // Create new conversation
        $pdo->prepare("INSERT INTO conversations (sender_id, receiver_id) VALUES (?, ?)")
            ->execute([$sender_id, $receiver_id]);
        $conversation_id = $pdo->lastInsertId();
    } else {
        $conversation_id = $conversation['id'];
    }
    
    // Insert the new message - make sure column names match your database
    $stmt = $pdo->prepare("INSERT INTO messages (conversation_id, sender_id, message, sent_at, seen) 
        VALUES (?, ?, ?, NOW(), 0)");
    $stmt->execute([$conversation_id, $sender_id, $message]);
    
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false]);
}
?>