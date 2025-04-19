<?php
session_start();
header('Content-Type: application/json');
require_once '../pdo.php';
$sender_id = $_SESSION['data']['id'] ?? 0;
$receiver_id = $_GET['receiver_id'] ?? 0;

if (!$sender_id || !$receiver_id) {
    echo json_encode(['error' => 'Invalid user IDs']);
    exit;
}

function getConversationId($pdo, $user1, $user2) {
    $stmt = $pdo->prepare("SELECT id FROM conversations 
        WHERE (sender_id = :user1 AND receiver_id = :user2) 
        OR (sender_id = :user2 AND receiver_id = :user1)
        LIMIT 1");
    $stmt->execute([':user1' => $user1, ':user2' => $user2]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    return $row ? $row['id'] : null;
}

$conversation_id = getConversationId($pdo, $sender_id, $receiver_id);

if ($conversation_id) {
    // Get messages
    $stmt = $pdo->prepare("SELECT * FROM messages WHERE conversation_id = ? ORDER BY sent_at ASC");
    $stmt->execute([$conversation_id]);
    $messages = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Mark messages as seen (fix column name if needed)
    $pdo->prepare("UPDATE messages SET seen = 1, seen_at = NOW() 
        WHERE conversation_id = ? AND sender_id = ? AND seen = 0")
        ->execute([$conversation_id, $receiver_id]);
    
    echo json_encode($messages);
} else {
    echo json_encode([]);
}
?>