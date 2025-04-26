<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once '../pdo.php';

// Check if user is logged in
if (!isset($_SESSION['data']['id'])) {
    // Redirect or handle not logged in state
    header('Location: login.php');
    exit;
}

// Get admin user ID - you should replace this with your actual admin user ID
$admin_id = 1; // Change this to your admin account ID
$user_id = $_SESSION['data']['id'];

// Function to get or create conversation with admin
function getAdminConversation($pdo, $user_id, $admin_id) {
    $stmt = $pdo->prepare("SELECT id FROM conversations 
        WHERE (sender_id = :user AND receiver_id = :admin) 
        OR (sender_id = :admin AND receiver_id = :user)
        LIMIT 1");
    $stmt->execute([
        ':user' => $user_id,
        ':admin' => $admin_id
    ]);
    $conversation = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$conversation) {
        // Create new conversation
        $pdo->prepare("INSERT INTO conversations (sender_id, receiver_id) VALUES (?, ?)")
            ->execute([$user_id, $admin_id]);
        return $pdo->lastInsertId();
    } else {
        return $conversation['id'];
    }
}

// Get admin details for display
$adminQuery = $pdo->prepare("SELECT username, picture FROM accounts WHERE id = ?");
$adminQuery->execute([$admin_id]);
$adminInfo = $adminQuery->fetch(PDO::FETCH_ASSOC);

// Get or create conversation ID
$conversation_id = getAdminConversation($pdo, $user_id, $admin_id);

// Handle new message submission via AJAX
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'send_message') {
    $message = trim($_POST['message'] ?? '');
    
    if (!empty($message)) {
        // Insert the new message
        $stmt = $pdo->prepare("INSERT INTO messages (conversation_id, sender_id, message, sent_at, seen) 
            VALUES (?, ?, ?, NOW(), 0)");
        $stmt->execute([$conversation_id, $user_id, $message]);
        
        echo json_encode(['success' => true]);
        exit;
    } else {
        echo json_encode(['success' => false, 'error' => 'Empty message']);
        exit;
    }
}

// Handle message fetch via AJAX
if (isset($_GET['action']) && $_GET['action'] === 'get_messages') {
    $stmt = $pdo->prepare("SELECT m.*, a.username, a.picture 
                           FROM messages m
                           JOIN accounts a ON m.sender_id = a.id
                           WHERE m.conversation_id = ? 
                           ORDER BY m.sent_at ASC");
    $stmt->execute([$conversation_id]);
    $messages = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Mark messages as seen if they're from admin
    $pdo->prepare("UPDATE messages SET seen = 1, seen_at = NOW() 
        WHERE conversation_id = ? AND sender_id = ? AND seen = 0")
        ->execute([$conversation_id, $admin_id]);
    
    echo json_encode($messages);
    exit;
}
?>

<!-- chatbox.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Chat Support</title>
    <style>
        .chat-container {
            width: 350px;
            height: 500px;
            background-color: white;
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
            display: flex;
            flex-direction: column;
            overflow: hidden;
            position: fixed;
            bottom: 90px;
            right: 20px;
            z-index: 1000;
            display: none;
            transition: all 0.3s ease;
            animation: slideUp 0.3s forwards;
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .chat-header {
            background-color: #000000;
            color: white;
            padding: 15px;
            text-align: center;
            font-weight: 500;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .header-title {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .header-logo {
            width: 24px;
            height: 24px;
            background-color: white;
            border-radius: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .header-logo svg {
            width: 16px;
            height: 16px;
            fill: black;
        }

        .close-button {
            color: white;
            background: none;
            border: none;
            font-size: 20px;
            cursor: pointer;
            width: 24px;
            height: 24px;
            display: flex;
            justify-content: center;
            align-items: center;
            border-radius: 50%;
        }

        .chat-messages {
            flex: 1;
            padding: 15px;
            overflow-y: auto;
            background-color: #fafafa;
        }

        .chat-input {
            display: flex;
            padding: 15px;
            border-top: 1px solid #eee;
            background-color: white;
        }

        .chat-input input {
            flex: 1;
            padding: 12px 15px;
            border: 1px solid #ddd;
            border-radius: 24px;
            outline: none;
            font-size: 14px;
        }

        .chat-input button {
            background-color: #000;
            color: white;
            border: none;
            width: 40px;
            height: 40px;
            margin-left: 10px;
            border-radius: 50%;
            cursor: pointer;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .chat-input button svg {
            width: 20px;
            height: 20px;
            fill: white;
        }

        .chat-icon-container {
            position: fixed;
            bottom: 20px;
            right: 20px;
            z-index: 999;
        }

        .chat-icon {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background-color: #000;
            display: flex;
            justify-content: center;
            align-items: center;
            cursor: pointer;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
            position: relative;
        }

        .chat-icon svg {
            width: 28px;
            height: 28px;
            fill: white;
        }

        .tooltip {
            position: absolute;
            top: -45px;
            right: 0;
            background-color: #333;
            color: white;
            padding: 8px 12px;
            border-radius: 6px;
            font-size: 14px;
            visibility: hidden;
            opacity: 0;
            transition: all 0.3s;
            white-space: nowrap;
            transform: translateY(5px);
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.1);
        }

        .chat-icon:hover .tooltip {
            visibility: visible;
            opacity: 1;
            transform: translateY(0);
        }

        .chat-pulse {
            position: absolute;
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background-color: rgba(0, 0, 0, 0.4);
            animation: chat-pulse 2s infinite;
        }

        @keyframes chat-pulse {
            0% {
                transform: scale(1);
                opacity: 0.8;
            }
            70% {
                transform: scale(1.3);
                opacity: 0;
            }
            100% {
                transform: scale(1.3);
                opacity: 0;
            }
        }

        /* Message styles */
        .message {
            margin-bottom: 10px;
            max-width: 80%;
            padding: 10px 15px;
            border-radius: 18px;
            position: relative;
            word-wrap: break-word;
        }

        .message-admin {
            background-color: #e9e9eb;
            color: #333;
            align-self: flex-start;
            margin-right: auto;
            border-bottom-left-radius: 5px;
        }

        .message-user {
            background-color: #0b93f6;
            color: white;
            align-self: flex-end;
            margin-left: auto;
            border-bottom-right-radius: 5px;
        }

        .message-info {
            font-size: 12px;
            margin-top: 2px;
            display: block;
        }

        .message-time {
            font-size: 11px;
            opacity: 0.7;
			float:right;
        }

        .message-container {
            display: flex;
            flex-direction: column;
        }

        @media (max-width: 600px) {
            .chat-container {
                width: 85%;
                height: 70%;
                bottom: 80px;
                right: 10px;
            }
        }
    </style>
</head>
<body>

<!-- Floating Chat Icon -->
<div class="chat-icon-container">
    <div class="chat-icon" id="chat-icon">
        <div class="chat-pulse"></div>
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
            <path d="M20 2H4c-1.1 0-2 .9-2 2v18l4-4h14c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zm0 14H5.17L4 17.17V4h16v12z"/>
            <path d="M7 9h10v2H7zm0-3h10v2H7zm0 6h7v2H7z"/>
        </svg>
        <span class="tooltip">Chat with support</span>
    </div>
</div>

<!-- Chat Box -->
<div class="chat-container" id="chat-container">
    <div class="chat-header">
        <div class="header-title">
            <div class="header-logo">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                    <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                </svg>
            </div>
            <span>Support</span>
        </div>
        <button class="close-button" id="close-button">&times;</button>
    </div>
    <div class="chat-messages" id="chat-messages">
        <!-- Messages will be loaded here -->
    </div>
    <div class="chat-input">
        <input type="text" id="user-input" placeholder="Type your message...">
        <button id="send-button">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                <path d="M2 21l21-9L2 3v7l15 2-15 2v7z"/>
            </svg>
        </button>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const chatIcon = document.getElementById('chat-icon');
        const chatContainer = document.getElementById('chat-container');
        const closeButton = document.getElementById('close-button');
        const chatMessages = document.getElementById('chat-messages');
        const userInput = document.getElementById('user-input');
        const sendButton = document.getElementById('send-button');
        let messagesLoaded = false;

        // Function to format date
        function formatMessageTime(timestamp) {
            const date = new Date(timestamp);
            const now = new Date();
            const isToday = date.toDateString() === now.toDateString();
            
            if (isToday) {
                return date.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
            } else {
                return date.toLocaleDateString() + ' ' + date.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
            }
        }

        // Load messages
        function loadMessages() {
            fetch('chat_admin.php?action=get_messages')
                .then(response => response.json())
                .then(messages => {
                    chatMessages.innerHTML = ''; // Clear existing messages
                    
                    if (messages.length === 0) {
                        // Add welcome message if no messages
                        const welcomeDiv = document.createElement('div');
                        welcomeDiv.className = 'message message-admin';
                        welcomeDiv.textContent = 'Welcome to Momento support! How can we help you today?';
                        chatMessages.appendChild(welcomeDiv);
                    } else {
                        // Add all messages
                        messages.forEach(msg => {
                            const userId = <?php echo json_encode($user_id); ?>;
                            const isUser = msg.sender_id == userId;
                            const messageDiv = document.createElement('div');
                            messageDiv.className = isUser ? 'message message-user' : 'message message-admin';
                            messageDiv.textContent = msg.message;
                            
                            // Add time
                            const timeSpan = document.createElement('span');
                            timeSpan.className = 'message-time';
                            timeSpan.textContent = formatMessageTime(msg.sent_at);
                            messageDiv.appendChild(timeSpan);
                            
                            chatMessages.appendChild(messageDiv);
                        });
                    }
                    
                    // Scroll to bottom
                    chatMessages.scrollTop = chatMessages.scrollHeight;
                    messagesLoaded = true;
                })
                .catch(error => console.error('Error loading messages:', error));
        }

        // Send message
        function sendMessage() {
            const message = userInput.value.trim();
            if (message) {
                // Add message to UI immediately for better UX
                const messageDiv = document.createElement('div');
                messageDiv.className = 'message message-user';
                messageDiv.textContent = message;
                
                // Add time
                const timeSpan = document.createElement('span');
                timeSpan.className = 'message-time';
                timeSpan.textContent = formatMessageTime(new Date());
                messageDiv.appendChild(timeSpan);
                
                chatMessages.appendChild(messageDiv);
                chatMessages.scrollTop = chatMessages.scrollHeight;
                
                // Clear input
                userInput.value = '';
                
                // Send to server
                const formData = new FormData();
                formData.append('action', 'send_message');
                formData.append('message', message);
                
                fetch('chat_admin.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (!data.success) {
                        console.error('Error sending message:', data.error);
                    }
                })
                .catch(error => console.error('Error:', error));
            }
        }

        // Event listeners
        chatIcon.addEventListener('click', () => {
            chatContainer.style.display = 'flex';
            if (!messagesLoaded) {
                loadMessages();
            }
        });

        closeButton.addEventListener('click', () => {
            chatContainer.style.display = 'none';
        });

        sendButton.addEventListener('click', sendMessage);
        
        userInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                sendMessage();
            }
        });

        // Poll for new messages every 5 seconds when chat is open
        setInterval(() => {
            if (chatContainer.style.display === 'flex') {
                loadMessages();
            }
        }, 5000);
    });
</script>

</body>
</html>