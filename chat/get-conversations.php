<?php
// get-conversations.php
require_once '../pdo.php'; // Include your database connection

// Get current user ID
$sender_id = $_GET['user_id'] ?? 0;
$current_active = $_GET['active_user'] ?? 0;

// Function to format message time
function formatMessageTime($timestamp) {
    if (!$timestamp) return '';
    
    $now = time();
    $today = strtotime('today');
    $yesterday = strtotime('yesterday');
    $diff = $now - $timestamp;
    
    if ($timestamp >= $today) {
        // Today: show time
        return date('g:i A', $timestamp);
    } else if ($timestamp >= $yesterday) {
        // Yesterday
        return 'Yesterday';
    } else if ($diff < 604800) {
        // Less than a week: show day name
        return date('D', $timestamp);
    } else {
        // More than a week: show date
        return date('M j', $timestamp);
    }
}

// Get conversations
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

// Add formatted time
foreach ($conversations as &$convo) {
    // Make sure we have a valid timestamp before formatting
    if ($convo['last_message_time']) {
        $timestamp = strtotime($convo['last_message_time']);
        $convo['time_formatted'] = formatMessageTime($timestamp);
    } else {
        $convo['time_formatted'] = '';
    }
    
    // Default to empty string if last_message is null
    if ($convo['last_message'] === null) {
        $convo['last_message'] = 'No messages yet';
    }
    
    // You could add online status logic here
    $convo['is_online'] = false; // Replace with actual online status check
}

// Return JSON response
header('Content-Type: application/json');
echo json_encode([
    'conversations' => $conversations,
    'current_active' => $current_active,
    'success' => true
]);