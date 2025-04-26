<?php
session_start();
require_once '../pdo.php';

// Check if user is an admin
$admin_id = 1; // Change this to your admin ID


// Function to format message time
function formatMessageTime($timestamp) {
    $now = time();
    $today = strtotime('today');
    $yesterday = strtotime('yesterday');
    $timestamp = strtotime($timestamp);
    
    if ($timestamp >= $today) {
        return date('g:i A', $timestamp); // Today - show time
    } else if ($timestamp >= $yesterday) {
        return 'Yesterday ' . date('g:i A', $timestamp);
    } else if ($timestamp >= strtotime('-6 days')) {
        return date('D g:i A', $timestamp); // Show day name if within last week
    } else {
        return date('M j, Y g:i A', $timestamp); // Show month and day otherwise
    }
}

// Handle message submission via AJAX
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'send_admin_reply') {
    $conversation_id = $_POST['conversation_id'] ?? 0;
    $message = trim($_POST['message'] ?? '');
    
    if ($conversation_id && !empty($message)) {
        // Insert the new message
        $stmt = $pdo->prepare("INSERT INTO messages (conversation_id, sender_id, message, sent_at, seen) 
            VALUES (?, ?, ?, NOW(), 0)");
        $stmt->execute([$conversation_id, $admin_id, $message]);
        
        echo json_encode(['success' => true]);
        exit;
    } else {
        echo json_encode(['success' => false, 'error' => 'Missing data']);
        exit;
    }
}

// Handle getting messages for a specific conversation
if (isset($_GET['action']) && $_GET['action'] === 'get_conversation_messages' && isset($_GET['conversation_id'])) {
    $conversation_id = $_GET['conversation_id'];
    
    $stmt = $pdo->prepare("SELECT m.*, a.username, a.picture 
                           FROM messages m
                           JOIN accounts a ON m.sender_id = a.id
                           WHERE m.conversation_id = ? 
                           ORDER BY m.sent_at ASC");
    $stmt->execute([$conversation_id]);
    $messages = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Format messages for display
    foreach ($messages as &$msg) {
        $msg['formatted_time'] = formatMessageTime($msg['sent_at']);
        $msg['is_admin'] = ($msg['sender_id'] == $admin_id);
    }
    
    echo json_encode($messages);
    exit;
}

// Get all conversations with admin (support chats)
$conversationsQuery = $pdo->prepare("
    SELECT 
        c.id AS conversation_id,
        c.sender_id,
        c.receiver_id,
        CASE 
            WHEN c.sender_id = :admin_id THEN c.receiver_id
            ELSE c.sender_id
        END AS user_id,
        a.username AS user_username,
        a.picture AS user_picture,
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
            AND m.sender_id != :admin_id
            AND m.seen = 0
        ) AS unread_count
    FROM 
        conversations c
    JOIN 
        accounts a ON (
            CASE 
                WHEN c.sender_id = :admin_id THEN c.receiver_id
                ELSE c.sender_id
            END = a.id
        )
    WHERE 
        c.sender_id = :admin_id OR c.receiver_id = :admin_id
    ORDER BY 
        last_message_time DESC
");

$conversationsQuery->execute([':admin_id' => $admin_id]);
$conversations = $conversationsQuery->fetchAll(PDO::FETCH_ASSOC);

// Format the time for each conversation
foreach ($conversations as &$convo) {
    if ($convo['last_message_time']) {
        $convo['formatted_time'] = formatMessageTime($convo['last_message_time']);
    } else {
        $convo['formatted_time'] = '';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Support Chat</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        body {
            background-color: #f5f5f5;
        }
        
        .admin-chat-container {
            display: flex;
            height: 100vh;
            max-width: 1200px;
            margin: 0 auto;
            background-color: white;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
        }
        
        .conversations-list {
            width: 30%;
            border-right: 1px solid #ddd;
            overflow-y: auto;
            background-color: #fafafa;
        }
        
        .conversation-item {
            padding: 15px;
            border-bottom: 1px solid #eee;
            cursor: pointer;
            transition: background-color 0.2s;
            display: flex;
            align-items: center;
        }
        
        .conversation-item:hover {
            background-color: #f0f0f0;
        }
        
        .conversation-item.active {
            background-color: #e6e6e6;
        }
        
        .conversation-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background-color: #ddd;
            margin-right: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            color: #555;
        }
        
        .conversation-details {
            flex: 1;
        }
        
        .conversation-username {
            font-weight: bold;
            margin-bottom: 5px;
        }
        
        .conversation-preview {
            color: #777;
            font-size: 13px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            max-width: 200px;
        }
        
        .conversation-time {
            font-size: 12px;
            color: #999;
        }
        
        .unread-badge {
            background-color: #0b93f6;
            color: white;
            border-radius: 50%;
            min-width: 20px;
            height: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            margin-left: 10px;
        }
        
        .chat-area {
            width: 70%;
            display: flex;
            flex-direction: column;
        }
        
        .chat-header {
            padding: 15px;
            background-color: #000;
            color: white;
            border-bottom: 1px solid #ddd;
            font-weight: bold;
        }
        
        .messages-container {
            flex: 1;
            padding: 15px;
            overflow-y: auto;
            background-color: #f5f5f5;
            display: flex;
            flex-direction: column;
        }
        
        .message {
            margin-bottom: 10px;
            max-width: 70%;
            padding: 10px 15px;
            border-radius: 18px;
            position: relative;
            word-wrap: break-word;
        }
        
        .message-user {
            background-color: #e9e9eb;
            color: #333;
            align-self: flex-start;
            border-bottom-left-radius: 5px;
        }
        
        .message-admin {
            background-color: #0b93f6;
            color: white;
            align-self: flex-end;
            border-bottom-right-radius: 5px;
        }
        
        .message-time {
            font-size: 11px;
            margin-top: 3px;
            opacity: 0.7;
        }
        
        .input-area {
            padding: 15px;
            border-top: 1px solid #ddd;
            display: flex;
        }
        
        .input-area input {
            flex: 1;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 24px;
            outline: none;
        }
        
        .input-area button {
            background-color: #000;
            color: white;
            border: none;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            margin-left: 10px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .welcome-message {
            text-align: center;
            margin-top: 40%;
            color: #999;
        }
        
        .empty-state {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100%;
            color: #999;
        }
        
        .empty-state svg {
            width: 80px;
            height: 80px;
            fill: #ddd;
            margin-bottom: 20px;
        }
        
        .empty-state p {
            font-size: 16px;
        }
    </style>
</head>
<body>
    <div class="admin-chat-container">
        <div class="conversations-list">
            <?php if (count($conversations) === 0): ?>
                <div class="empty-state">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                        <path d="M20 2H4c-1.1 0-2 .9-2 2v18l4-4h14c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zm0 14H5.17L4 17.17V4h16v12z"/>
                        <path d="M7 9h10v2H7zm0-3h10v2H7zm0 6h7v2H7z"/>
                    </svg>
                    <p>No support conversations yet</p>
                </div>
            <?php else: ?>
                <?php foreach ($conversations as $conversation): ?>
                    <div class="conversation-item" data-id="<?php echo $conversation['conversation_id']; ?>">
                        <div class="conversation-avatar">
                            <?php if ($conversation['user_picture']): ?>
                                <img src="<?php echo htmlspecialchars($conversation['user_picture']); ?>" width = "40" height = "40" alt="User">
                            <?php else: ?>
                                <?php echo htmlspecialchars(substr($conversation['user_username'], 0, 1)); ?>
                            <?php endif; ?>
                        </div>
                        <div class="conversation-details">
                            <div class="conversation-username"><?php echo htmlspecialchars($conversation['user_username']); ?></div>
                            <div class="conversation-preview"><?php echo htmlspecialchars($conversation['last_message'] ?? 'No messages yet'); ?></div>
                            <div class="conversation-time"><?php echo htmlspecialchars($conversation['formatted_time']); ?></div>
                        </div>
                        <?php if ($conversation['unread_count'] > 0): ?>
                            <div class="unread-badge"><?php echo $conversation['unread_count']; ?></div>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
        
        <div class="chat-area">
            <div class="chat-header" id="chat-header">
                Select a conversation
            </div>
            <div class="messages-container" id="messages-container">
                <div class="empty-state">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                        <path d="M20 2H4c-1.1 0-2 .9-2 2v18l4-4h14c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zm0 14H5.17L4 17.17V4h16v12z"/>
                        <path d="M7 9h10v2H7zm0-3h10v2H7zm0 6h7v2H7z"/>
                    </svg>
                    <p>Select a conversation to view messages</p>
                </div>
            </div>
            <div class="input-area" id="input-area" style="display: none;">
                <input type="text" id="admin-input" placeholder="Type your message...">
                <button id="admin-send-button">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" fill="white">
                        <path d="M2 21l21-9L2 3v7l15 2-15 2v7z"/>
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const conversationItems = document.querySelectorAll('.conversation-item');
            const messagesContainer = document.getElementById('messages-container');
            const chatHeader = document.getElementById('chat-header');
            const inputArea = document.getElementById('input-area');
            const adminInput = document.getElementById('admin-input');
            const adminSendButton = document.getElementById('admin-send-button');
            
            let currentConversationId = null;
            let currentUser = null;
            let pollingInterval = null;
            
            // Function to format message time
            function formatTime(timestamp) {
                const date = new Date(timestamp);
                return date.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
            }
            
            // Function to load messages for a conversation
            function loadConversationMessages(conversationId) {
                fetch(`admin_panel.php?action=get_conversation_messages&conversation_id=${conversationId}`)
                    .then(response => response.json())
                    .then(messages => {
                        messagesContainer.innerHTML = '';
                        
                        if (messages.length === 0) {
                            const emptyMessage = document.createElement('div');
                            emptyMessage.className = 'welcome-message';
                            emptyMessage.textContent = 'No messages in this conversation yet.';
                            messagesContainer.appendChild(emptyMessage);
                        } else {
                            messages.forEach(msg => {
                                const messageDiv = document.createElement('div');
                                messageDiv.className = msg.is_admin ? 'message message-admin' : 'message message-user';
                                
                                const messageContent = document.createElement('div');
                                messageContent.className = 'message-content';
                                messageContent.textContent = msg.message;
                                
                                const messageTime = document.createElement('div');
                                messageTime.className = 'message-time';
                                messageTime.textContent = msg.formatted_time;
                                
                                messageDiv.appendChild(messageContent);
                                messageDiv.appendChild(messageTime);
                                messagesContainer.appendChild(messageDiv);
                            });
                        }
                        
                        // Scroll to bottom
                        messagesContainer.scrollTop = messagesContainer.scrollHeight;
                        
                        // Refresh unread badge for this conversation
                        const conversationItem = document.querySelector(`.conversation-item[data-id="${conversationId}"]`);
                        const unreadBadge = conversationItem.querySelector('.unread-badge');
                        if (unreadBadge) {
                            unreadBadge.remove();
                        }
                    })
                    .catch(error => console.error('Error loading messages:', error));
            }
            
            // Function to send a message
            function sendMessage() {
                const message = adminInput.value.trim();
                if (message && currentConversationId) {
                    // Create temporary message display
                    const messageDiv = document.createElement('div');
                    messageDiv.className = 'message message-admin';
                    
                    const messageContent = document.createElement('div');
                    messageContent.className = 'message-content';
                    messageContent.textContent = message;
                    
                    const messageTime = document.createElement('div');
                    messageTime.className = 'message-time';
                    messageTime.textContent = formatTime(new Date());
                    
                    messageDiv.appendChild(messageContent);
                    messageDiv.appendChild(messageTime);
                    messagesContainer.appendChild(messageDiv);
                    
                    // Clear input and scroll to bottom
                    adminInput.value = '';
                    messagesContainer.scrollTop = messagesContainer.scrollHeight;
                    
                    // Send message to server
                    const formData = new FormData();
                    formData.append('action', 'send_admin_reply');
                    formData.append('conversation_id', currentConversationId);
                    formData.append('message', message);
                    
                    fetch('admin_panel.php', {
                        method: 'POST',
                        body: formData
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (!data.success) {
                            console.error('Error sending message:', data.error);
                        } else {
                            // Update the conversation preview
                            const conversationItem = document.querySelector(`.conversation-item[data-id="${currentConversationId}"]`);
                            const previewDiv = conversationItem.querySelector('.conversation-preview');
                            const timeDiv = conversationItem.querySelector('.conversation-time');
                            
                            if (previewDiv) {
                                previewDiv.textContent = message;
                            }
                            
                            if (timeDiv) {
                                timeDiv.textContent = 'Just now';
                            }
                            
                            // Move this conversation to the top
                            const parent = conversationItem.parentNode;
                            parent.insertBefore(conversationItem, parent.firstChild);
                        }
                    })
                    .catch(error => console.error('Error:', error));
                }
            }
            
            // Add click event listeners to conversation items
            conversationItems.forEach(item => {
                item.addEventListener('click', function() {
                    // Remove active class from all items
                    conversationItems.forEach(item => item.classList.remove('active'));
                    
                    // Add active class to clicked item
                    this.classList.add('active');
                    
                    // Get conversation details
                    currentConversationId = this.getAttribute('data-id');
                    currentUser = this.querySelector('.conversation-username').textContent;
                    
                    // Update chat header
                    chatHeader.textContent = `Chat with ${currentUser}`;
                    
                    // Show input area
                    inputArea.style.display = 'flex';
                    
                    // Load messages
                    loadConversationMessages(currentConversationId);
                    
                    // Set up polling for this conversation
                    if (pollingInterval) {
                        clearInterval(pollingInterval);
                    }
                    
                    pollingInterval = setInterval(() => {
                        if (currentConversationId) {
                            loadConversationMessages(currentConversationId);
                        }
                    }, 5000); // Poll every 5 seconds
                });
            });
            
            // Send message on button click
            adminSendButton.addEventListener('click', sendMessage);
            
            // Send message on Enter key
            adminInput.addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    sendMessage();
                }
            });
        });
    </script>
</body>
</html>