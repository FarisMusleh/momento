<?php
	if (session_status() === PHP_SESSION_NONE) {
		session_start();
	}
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
				<div class="message-icon-container p-1">
					<div class="bg-secondary rounded-circle message-icon" style=" padding:3px " id="messageIcon" onclick="window.location.href='/momento/chat/chat.php'">
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
                <li class="nav-item"><a class="nav-link" href="/momento/index.php">HOME</a></li>
               <!-- Ultra-Professional Categories Dropdown -->
                <li class="nav-item dropdown dropdown-hover">
                    <a class="nav-link d-flex align-items-center" href="#" id="galleryDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                </i> GALLERY
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end p-2" aria-labelledby="galleryDropdown" style="min-width: 320px;">
                        <li>
                            <a class="dropdown-item categories-item" href="/momento/gallery.php">
                                <div class="d-flex align-items-center">
                                    <div class="categories-icon bg-primary bg-opacity-10 text-primary">
                                        <i class="fas fa-images"></i>
                                    </div>
                                    <div class="ms-3">
                                        <div class="fw-medium">All Collections</div>
                                        <div class="text-muted small">Browse complete gallery</div>
                                    </div>
                                </div>
                            </a>
                        </li>
                        <li><hr class="dropdown-divider my-2"></li>
                        <li>
                            <a class="dropdown-item categories-item" href="/momento/searching_photos.php?type=photos&query=war">
                                <div class="d-flex align-items-center">
                                    <div class="categories-icon bg-danger bg-opacity-10 text-danger">
                                        <i class="fas fa-fighter-jet"></i>
                                    </div>
                                    <div class="ms-3">
                                        <div class="fw-medium">Wars</div>
                                        <div class="text-muted small">Documentary and historical</div>
                                    </div>
                                </div>
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item categories-item" href="/momento/searching_photos.php?type=photos&query=wedding">
                                <div class="d-flex align-items-center">
                                    <div class="categories-icon bg-warning bg-opacity-10 text-warning">
                                        <i class="fas fa-ring"></i>
                                    </div>
                                    <div class="ms-3">
                                        <div class="fw-medium">Wedding</div>
                                        <div class="text-muted small">Celebrations and moments</div>
                                    </div>
                                </div>
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item categories-item" href="/momento/searching_photos.php?type=photos&query=nature">
                                <div class="d-flex align-items-center">
                                    <div class="categories-icon bg-success bg-opacity-10 text-success">
                                        <i class="fas fa-mountain"></i>
                                    </div>
                                    <div class="ms-3">
                                        <div class="fw-medium">Nature</div>
                                        <div class="text-muted small">Landscapes and wildlife</div>
                                    </div>
                                </div>
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item categories-item" href="/momento/searching_photos.php?type=photos&query=graduation">
                                <div class="d-flex align-items-center">
                                    <div class="categories-icon bg-info bg-opacity-10 text-info">
                                        <i class="fas fa-graduation-cap"></i>
                                    </div>
                                    <div class="ms-3">
                                        <div class="fw-medium">Graduation</div>
                                        <div class="text-muted small">Academic achievements</div>
                                    </div>
                                </div>
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item categories-item" href="/momento/searching_photos.php?type=photos&query=art">
                                <div class="d-flex align-items-center">
                                    <div class="categories-icon  bg-opacity-10 text-purple">
                                         <i class="fas fa-palette"></i>   
                                </div>
                                    <div class="ms-3">
                                        <div class="fw-medium">Art</div>
                                        <div class="text-muted small">Creative expressions</div>
                                    </div>
                                </div>
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item categories-item" href="/momento/searching_photos.php?type=photos&query=architecture">
                                <div class="d-flex align-items-center">
                                    <div class="categories-icon bg-secondary bg-opacity-10 text-secondary">
                                        <i class="fas fa-building"></i>
                                    </div>
                                    <div class="ms-3">
                                        <div class="fw-medium">Architecture</div>
                                        <div class="text-muted small">Urban and structures</div>
                                    </div>
                                </div>
                            </a>
                        </li>
                    </ul>
                </li>
                <!-- End Ultra-Professional Dropdown -->
                <li class="nav-item"><a class="nav-link" href="/momento/photographers.php">PHOTOGRAPHERS</a></li>
				



                <?php if (!isset($_SESSION['data'])): ?>
                    <li class="nav-item"><a class="nav-link" href="account/register.php">Sign up</a></li>
                    <li class="login btn"><a class="text-white text-center text-decoration-none" style = "display:block;width:100%;height:100%;" href="account/login.php">Login</a></li>                <?php else: ?>
                    <ul class="list-unstyled m-0 p-0">
                        <div class="dropdown-button">
							<?php 
								$sql = $pdo->prepare('select picture from accounts where id = ?');
								$sql->execute([$_SESSION['data']['id']]);
								$result = $sql->fetch();
							?>
                            <img src="<?= $result['picture'] ?>" class="dropdown-img">
							<?php echo "<script>console.log('{$result['picture']}')</script>"?>
                        </div>
                        <div class="dropdown-content">
                            <div class="dropdown-header"> 
							<div class="navbar-profile-container">
							  <img src="<?= $result['picture'] ?>" class="navbar-profile-image">
							  
							</div>
							<div class="navbar-user-info"> 
							  <div class="navbar-user-name"> 
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
						  </div> 
                            <div class="navbar-dropdown-body"> 
								<?php if ($account_type['account_type'] === 'business'): ?> 
								  <a href="/momento/profile.php" class="navbar-menu-item">
									<svg class="navbar-menu-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
									  <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
									  <circle cx="12" cy="7" r="4"></circle>
									</svg>
									<span>Profile</span>
									<div class="navbar-hover-indicator"></div>
								  </a> 
								  <a href="/momento/dashboard.php" class="navbar-menu-item">
								  <svg class="navbar-menu-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="24" height="24" stroke-linecap="round" stroke-linejoin="round">
										<path d="M4 3h6v6H4V3zm0 8h6v10H4V11zm10-8h6v10h-6V3zm0 12h6v6h-6v-6z"/>
								  </svg>
								  <span>Dashboard</span>
								  <div class="navbar-hover-indicator"></div>
								</a>
								  <a href="/momento/appointment/services.php" class="navbar-menu-item">
								<i class="fas fa-concierge-bell" style="color: red; margin-right: 15px;"></i>
								  <span>Services</span>
								  <div class="navbar-hover-indicator"></div>
								</a>
								  <a href="/momento/appointment/appointments_inbox.php" class="navbar-menu-item">
								  <i class="fas fa-calendar-alt" style="color: red; margin-right: 15px;"></i>
								  <span> Appointments</span>
								  <div class="navbar-hover-indicator"></div>
								</a>

								<?php endif; ?> 
								<a href="/momento/edit-profile.php" class="navbar-menu-item">
								  <svg class="navbar-menu-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
									<circle cx="12" cy="12" r="3"></circle>
									<path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"></path>
								  </svg>
								  <span>Settings</span>
								  <div class="navbar-hover-indicator"></div>
								</a>
								<a href="/momento/logout.php" class="navbar-menu-item">
								  <svg class="navbar-menu-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
									<path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
									<polyline points="16 17 21 12 16 7"></polyline>
									<line x1="21" y1="12" x2="9" y2="12"></line>
								  </svg>
								  <span>Sign Out</span>
								  <div class="navbar-hover-indicator"></div>
								</a> 
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