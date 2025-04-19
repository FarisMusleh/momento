<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once '../pdo.php';

if (!isset($_SESSION['data']['id'])) {
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Not authenticated']);
    exit;
}

$sender_id = $_SESSION['data']['id'];

// Format message time helper function
function formatMessageTime($timestamp) {
    $now = time();
    $today = strtotime('today');
    $yesterday = strtotime('yesterday');
    
    if ($timestamp >= $today) {
        return date('g:i A', $timestamp); // Today - show time
    } else if ($timestamp >= $yesterday) {
        return 'Yesterday';
    } else if ($timestamp >= strtotime('-6 days')) {
        return date('D', $timestamp); // Show day name if within last week
    } else {
        return date('M j', $timestamp); // Show month and day otherwise
    }
}

// Get all users that the current user has conversations with
$conversationsQuery = $pdo->prepare("
    SELECT 
        c.id AS conversation_id,
        c.sender_id,
        c.receiver_id,
        CASE 
            WHEN c.sender_id = :current_user THEN c.receiver_id
            ELSE c.sender_id
        END AS other_user_id,
        a.username AS other_username,
        a.picture AS other_user_picture,
        (
            SELECT m.message
            FROM messages m 
            WHERE m.conversation_id = c.id
            ORDER BY m.sent_at DESC
            LIMIT 1
        ) AS last_message,
        (
            SELECT m.sent_at
            FROM messages m 
            WHERE m.conversation_id = c.id
            ORDER BY m.sent_at DESC
            LIMIT 1
        ) AS last_message_time,
        (
            SELECT COUNT(*)
            FROM messages m
            WHERE m.conversation_id = c.id
            AND m.sender_id != :current_user
            AND m.seen = 0
        ) AS unread_count
    FROM 
        conversations c
    JOIN 
        accounts a ON (
            CASE 
                WHEN c.sender_id = :current_user THEN c.receiver_id
                ELSE c.sender_id
            END = a.id
        )
    WHERE 
        c.sender_id = :current_user OR c.receiver_id = :current_user
    ORDER BY 
        last_message_time DESC
");

$conversationsQuery->execute([':current_user' => $sender_id]);
$conversations = $conversationsQuery->fetchAll(PDO::FETCH_ASSOC);

// Format the time for each conversation for display
foreach ($conversations as &$convo) {
    $convo['formatted_time'] = formatMessageTime(strtotime($convo['last_message_time']));
}

// Return the conversations as JSON
header('Content-Type: application/json');
echo json_encode($conversations);
?>