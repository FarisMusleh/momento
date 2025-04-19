<?php 
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$data = $_SESSION['data'] ?? null;
require_once '../pdo.php';
if (!isset($_SESSION['data']['id'])) { 
    header('location:../index.php'); 
    exit; 
}
$sender_id = $_SESSION['data']['id']; 
$receiver_id = isset($_GET['id']) ? intval($_GET['id']) : 0; 

function getConversationId($pdo, $user_id_1, $user_id_2) {
    $stmt = $pdo->prepare("
        SELECT id FROM conversations 
        WHERE 
            (sender_id = :user1 AND receiver_id = :user2) OR 
            (sender_id = :user2 AND receiver_id = :user1)
        LIMIT 1
    ");
    $stmt->execute([
        ':user1' => $user_id_1,
        ':user2' => $user_id_2
    ]);

    $conversation = $stmt->fetch(PDO::FETCH_ASSOC);
    return $conversation ? $conversation['id'] : null;
}

// Format message time
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

// Mark messages as seen when conversation is opened
if ($receiver_id > 0) {
    $conversation_id = getConversationId($pdo, $sender_id, $receiver_id);
    
    if ($conversation_id) {
        // Update all unread messages from the other user to "seen"
        $markSeenQuery = $pdo->prepare("
            UPDATE messages 
            SET seen = 1
            WHERE conversation_id = :conversation_id 
            AND sender_id = :receiver_id 
            AND seen = 0
        ");
        
        $markSeenQuery->execute([
            ':conversation_id' => $conversation_id,
            ':receiver_id' => $receiver_id
        ]);
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Momento-Chat</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Dancing+Script&family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Pacifico&family=Roboto:wght@300;500;700&family=Dancing+Script&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet">
    <!-- CSS -->
    <link rel="stylesheet" href="../css/header.css">
    <link rel="stylesheet" href="chat.css">
</head>
<body>
    <?php require('../header.php')?>
    <div class="container">
        <h2 class="mt-3">Messaging</h2>
        
        <div class="main-container">
            <!-- User list sidebar -->
            <div class="user-sidebar">
                <div class="sidebar-header">
                    <h4>Conversations</h4>
                </div>
                
                <!-- Dynamic user list -->
                <div class="user-list">
                    <?php
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
                    $conversations = $conversationsQuery->fetchAll();
                    
                    foreach($conversations as $convo):
                        $isActive = ($convo['other_user_id'] == $receiver_id);
                        $timeFormatted = formatMessageTime(strtotime($convo['last_message_time']));
                        $isOnline = false; // You would determine this dynamically
                    ?>
                        <div class="user-item <?php echo $isActive ? 'active' : ''; ?>" data-user-id="<?php echo $convo['other_user_id']; ?>">
                            <div class="user-avatar">
                                <img src="<?php echo htmlspecialchars($convo['other_user_picture']);?>">
                                <span class="status-indicator <?php echo $isOnline ? 'online' : 'offline'; ?>"></span>
                            </div>
                            <div class="user-info">
                                <h5>
                                    <?php echo htmlspecialchars($convo['other_username']); ?>
                                    <?php if($convo['unread_count'] > 0): ?>
                                        <span class="unread-indicator"></span>
                                    <?php endif; ?>
                                </h5>
                                <p class="last-message"><?php echo htmlspecialchars(substr($convo['last_message'], 0, 30)) . (strlen($convo['last_message']) > 30 ? '...' : ''); ?></p>
                            </div>
                            <div class="message-time"><?php echo $timeFormatted; ?></div>
                        </div>
                    <?php endforeach; ?>
                    
                    <?php if(count($conversations) == 0): ?>
                        <div class="p-3 text-center text-muted">
                            No conversations yet
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            
            <!-- Chat content area -->
            <div class="chat-container">
                <?php if($receiver_id > 0): ?>
                    <h4>
                        <?php 
                            // Get username of receiver
                            $userQuery = $pdo->prepare("SELECT username FROM accounts WHERE id = :user_id");
                            $userQuery->execute([':user_id' => $receiver_id]);
                            $user = $userQuery->fetch();
                            echo htmlspecialchars($user['username'] ?? 'Conversation');
                        ?>
                    </h4>
                    
                    <!-- Messages display area -->
                    <div class="message-container" id="messageContainer">
                        <?php
                            $conversation_id = getConversationId($pdo, $sender_id, $receiver_id);
                            if ($conversation_id) {
                                $sql = $pdo->prepare("
                                SELECT 
                                    m.id AS message_id,
                                    m.message,
                                    m.sender_id,
                                    a.username AS sender_name,
                                    m.sent_at,
                                    m.seen,
                                    m.seen
                                FROM 
                                    messages m
                                JOIN 
                                    accounts a ON m.sender_id = a.id
                                WHERE 
                                    m.conversation_id = :conversation_id
                                ORDER BY 
                                    m.sent_at ASC
                                ");
                                $sql->execute([':conversation_id' => $conversation_id]);
                                $result = $sql->fetchAll();
                                foreach($result as $msg):
                                    $isSent = $msg['sender_id'] == $sender_id;
                            ?>
                                <div class="message <?php echo $isSent ? 'sent' : 'received'; ?>">
                                    <div class="message-content">
                                        <?php echo htmlspecialchars($msg['message']); ?>
                                    </div>
                                    <div class="message-time">
                                        <?php echo date('M d, g:i a', strtotime($msg['sent_at'])); ?>
                                    </div>
                                    <?php if($isSent): ?>
                                        <div class="message-status">
                                            <?php if($msg['seen']): ?>
                                                Seen <?php echo date('g:i a', strtotime($msg['seen'])); ?>
                                                <i class="fas fa-check-double"></i>
                                            <?php else: ?>
                                                Delivered <i class="fas fa-check"></i>
                                            <?php endif; ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            <?php endforeach; 
                            }
                            ?>
                    </div>
                    <div class="input-group">
                        <input type="text" id="messageInput" class="form-control" placeholder="Type your message">
                        <button id="sendButton" class="btn btn-primary">Send</button>
                    </div>
                <?php else: ?>
                    <div class="text-center mt-5">
                        <h4>Select a conversation to start chatting</h4>
                        <p class="text-muted">Choose a user from the left sidebar</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" 
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script>
        // Pass PHP variables to JavaScript
        const receiverId = <?= json_encode($receiver_id) ?>;
        const senderId = <?= json_encode($sender_id) ?>;
    </script>
    <script src="chat.js"></script>
</body>
</html>