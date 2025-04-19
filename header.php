<?php
    require_once('pdo.php');
	if(isset($_SESSION['data']))
		$data = $_SESSION['data'];
?>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<nav class="navbar navbar-expand-lg nav-sticky" id = "navbar">
    <div class="container-fluid">
        <a class="navbar-brand fs-3 momento-logo" href="/momento/index.php">Momento</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto gap-4 align-items-center">
				<!-- CHAT-ICON -->
				<?php if(isset($_SESSION['data'])){?>
				<?php
					$sql = $pdo->prepare("
					SELECT m.*, a.username AS sender_name
					FROM messages m
					JOIN conversations c ON m.conversation_id = c.id
					JOIN accounts a ON m.sender_id = a.id
					WHERE 
						(c.sender_id = :user_id OR c.receiver_id = :user_id)
						AND m.sender_id != :user_id
						AND m.seen = 0
						AND m.id IN (
							SELECT MAX(id)
							FROM messages
							WHERE seen = 0 AND sender_id != :user_id
							GROUP BY conversation_id
						)
					ORDER BY m.sent_at DESC
					LIMIT 5
					");
					$sql->execute([':user_id' => $data['id']]);
					$result = $sql->fetchAll();
				?>
				<div class="message-icon-container">
					<div class="message-icon" id="messageIcon" onclick="window.location.href='/momento/chat/chat.php'">
						<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
							<path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"></path>
						</svg>
						<?php if(count($result)!=0){?>
						<div class="notification-badge" id="notificationBadge"><?php echo count($result);?></div>
						<?php }?>
					</div>
					<?php if(count($result)!=0){?>
					<div class="message-tooltip" id="messageTooltip">
						<?php
							foreach($result as $row){
						?>
						<div class="message-preview-item unread">
							<div class="message-preview-sender"><?=$row['sender_name']?></div>
							<div class="message-preview-text"><?=$row['message']?></div>
						</div>
							<?php }?>
					</div>
					<?php }?>
				</div>
				<?php }?>
				<!-- END-CHAT-ICON -->
                <li class="nav-item"><a class="nav-link" href="/momento/gallery.php">GALLERY</a></li>
                <li class="nav-item"><a class="nav-link" href="/momento/photographers.php">PHOTOGRAPHERS</a></li>
                <li class="nav-item"><a class="nav-link" href="#">FIND JOBS</a></li>
				



                <?php if (!isset($_SESSION['data'])): ?>
                    <li class="nav-item"><a class="nav-link" href="account/register.php">Sign up</a></li>
                    <li class="login"><a class="btn text-white text-center" href="account/login.php">Login</a></li>
                <?php else: ?>
                    <ul class="list-unstyled m-0 p-0">
                        <div class="dropdown-button">
                            <img src="<?= $data['picture'] ?>" class="dropdown-img">
                        </div>
                        <div class="dropdown-content">
                            <div class="DropDownFlex">
                                <img src="<?= $data['picture'] ?>" class="dropdown-img">
								
                                <div class="dropdown-name">
                                    <?php
                                        $stmt = $pdo->prepare('SELECT account_type FROM accounts WHERE id = ?');
                                        $stmt->execute([$_SESSION['data']['id']]);
                                        $account_type = $stmt->fetch(PDO::FETCH_ASSOC);

                                        if ($account_type['account_type'] === 'user') {
                                            $profileData = $pdo->prepare('SELECT name FROM user_profiles WHERE id = ?');
                                            $profileData->execute([$_SESSION['data']['id']]);
                                            $name = $profileData->fetch(PDO::FETCH_ASSOC);
                                            echo $name['name']??null;
                                        } elseif ($account_type['account_type'] === 'business') {
                                            $profileData = $pdo->prepare('SELECT business_name FROM business_profiles WHERE id = ?');
                                            $profileData->execute([$_SESSION['data']['id']]);
                                            $name = $profileData->fetch(PDO::FETCH_ASSOC);
                                            echo $name['business_name'];
                                        }
                                    ?>
                                </div>
                            </div>
                            <div class="dropdown-section">
                                <?php if ($account_type['account_type'] === 'business'): ?>
                                    <a href="/momento/profile.php" class="dropdown-link">Profile</a>
                                    <hr class="dropdown-divider">
                                <?php endif; ?>
                                <a href="/momento/edit-profile.php" class="dropdown-link">Settings</a>
                                <hr class="dropdown-divider">
                                <a href="/momento/logout.php" class="dropdown-link">Sign Out</a>
                            </div>
                        </div>
                    </ul>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>



<script>
        document.addEventListener('DOMContentLoaded', function() {
            // DOM Elements
            const siteLogo = document.getElementById('siteLogo');
            const messageTooltip = document.getElementById('messageTooltip');
            const messageIcon = document.getElementById('messageIcon');
            const inboxContainer = document.getElementById('inboxContainer');
            const homeContent = document.getElementById('homeContent');
            const chatContainer = document.getElementById('chatContainer');
            const backToHome = document.getElementById('backToHome');
            const backToInbox = document.getElementById('backToInbox');
            const messageItems = document.querySelectorAll('.message-item');
            const notificationBadge = document.getElementById('notificationBadge');
            const chatMessages = document.getElementById('chatMessages');
            const messageInput = document.getElementById('messageInput');
            const sendButton = document.getElementById('sendButton');
            const chatAvatar = document.getElementById('chatAvatar');
            const chatContactName = document.getElementById('chatContactName');
            const previewItems = document.querySelectorAll('.message-preview-item');
            
            // Store conversation history
            const conversations = {
                'John Doe': [
                    { type: 'received', text: 'Hey there! Just wanted to check in about the project.' },
                    { type: 'received', text: 'Do you have any updates on the timeline?' }
                ],
                'Jane Smith': [
                    { type: 'received', text: 'Can we schedule a meeting for next week?' },
                    { type: 'sent', text: 'Sure, how about Tuesday at 2pm?' },
                    { type: 'received', text: 'That works for me. I\'ll send a calendar invite.' }
                ],
                'Robert Martin': [
                    { type: 'received', text: 'The documents you requested are attached.' },
                    { type: 'sent', text: 'Thanks, I appreciate it.' },
                    { type: 'received', text: 'No problem. Let me know if you need anything else.' }
                ]
            };
            
            let currentContact = '';
            
            // Function to load conversation for a specific contact
            function loadConversation(contact) {
                // Clear existing messages
                chatMessages.innerHTML = '';
                
                // Add messages from the conversation history
                if (conversations[contact]) {
                    conversations[contact].forEach(message => {
                        const messageElement = document.createElement('div');
                        messageElement.className = `message-bubble message-${message.type}`;
                        messageElement.textContent = message.text;
                        chatMessages.appendChild(messageElement);
                    });
                }
                
                // Scroll to the bottom of the chat
                chatMessages.scrollTop = chatMessages.scrollHeight;
            }
            
            // Function to send a message
            function sendMessage() {
                const messageText = messageInput.value.trim();
                if (!messageText) return;
                
                // Create a message object
                const message = {
                    type: 'sent',
                    text: messageText
                };
                
                // Add message to the conversation
                if (!conversations[currentContact]) {
                    conversations[currentContact] = [];
                }
                conversations[currentContact].push(message);
                
                // Add message to chat display
                const messageElement = document.createElement('div');
                messageElement.className = `message-bubble message-sent`;
                messageElement.textContent = messageText;
                chatMessages.appendChild(messageElement);
                
                // Clear input
                messageInput.value = '';
                
                // Scroll to bottom
                chatMessages.scrollTop = chatMessages.scrollHeight;
                
                // Simulate a response after a delay (for demo purposes)
                if (currentContact === 'John Doe') {
                    setTimeout(() => {
                        const response = {
                            type: 'received',
                            text: 'Thanks for the update. Looking forward to seeing the progress!'
                        };
                        conversations[currentContact].push(response);
                        
                        const responseElement = document.createElement('div');
                        responseElement.className = `message-bubble message-received`;
                        responseElement.textContent = response.text;
                        chatMessages.appendChild(responseElement);
                        
                        chatMessages.scrollTop = chatMessages.scrollHeight;
                    }, 1000);
                }
            }
            
            // Event Listeners
            
            // Show message tooltip on hover over message icon
            messageIcon.addEventListener('mouseenter', function() {
                messageTooltip.style.display = 'block';
            });
            
            messageIcon.addEventListener('mouseleave', function() {
                messageTooltip.style.display = 'none';
            });
            
            // Logo click to show home
            siteLogo.addEventListener('click', function() {
                homeContent.style.display = 'block';
                inboxContainer.style.display = 'none';
                chatContainer.style.display = 'none';
            });
            
            // Message icon click to show inbox
            messageIcon.addEventListener('click', function() {
                homeContent.style.display = 'none';
                inboxContainer.style.display = 'block';
                chatContainer.style.display = 'none';
                
                // Clear notification badge
                notificationBadge.style.display = 'none';
            });
            
            // Back to home link
            backToHome.addEventListener('click', function(e) {
                e.preventDefault();
                homeContent.style.display = 'block';
                inboxContainer.style.display = 'none';
                chatContainer.style.display = 'none';
            });
            
            // Back to inbox link
            backToInbox.addEventListener('click', function(e) {
                e.preventDefault();
                homeContent.style.display = 'none';
                inboxContainer.style.display = 'block';
                chatContainer.style.display = 'none';
            });
            
            // Message item click to open chat
            messageItems.forEach(item => {
                item.addEventListener('click', function() {
                    // Get contact info
                    currentContact = this.getAttribute('data-contact');
                    const initials = this.getAttribute('data-initials');
                    
                    // Update chat header
                    chatAvatar.textContent = initials;
                    chatContactName.textContent = currentContact;
                    
                    // Show chat container
                    homeContent.style.display = 'none';
                    inboxContainer.style.display = 'none';
                    chatContainer.style.display = 'block';
                    
                    // Remove unread class if present
                    if (this.classList.contains('unread')) {
                        this.classList.remove('unread');
                    }
                    
                    // Load conversation
                    loadConversation(currentContact);
                    
                    // Focus on input
                    messageInput.focus();
                });
            });
            
            // Message preview click to open chat
            previewItems.forEach(item => {
                item.addEventListener('click', function() {
                    // Get contact name
                    const contactName = this.querySelector('.message-preview-sender').textContent;
                    
                    // Find corresponding message item
                    const messageItem = Array.from(messageItems).find(item => 
                        item.getAttribute('data-contact') === contactName
                    );
                    
                    if (messageItem) {
                        // Trigger click on the message item
                        messageItem.click();
                        
                        // Remove unread class from message preview
                        if (this.classList.contains('unread')) {
                            this.classList.remove('unread');
                        }
                    }
                    
                    // Hide tooltip
                    messageTooltip.style.display = 'none';
                });
            });
            
            // Send button click to send message
            sendButton.addEventListener('click', sendMessage);
            
            // Enter key to send message
            messageInput.addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    sendMessage();
                }
            });
        });
    </script>