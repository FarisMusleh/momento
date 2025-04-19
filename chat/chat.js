// Global variables
let isScrolledToBottom = true;
const messageContainer = document.getElementById('messageContainer');

// Initialize chat functionality
document.addEventListener('DOMContentLoaded', function() {
    initializeChat();
});

// Main initialization function
function initializeChat() {
    // Auto-scroll to the bottom of the message container
    if (messageContainer) {
        messageContainer.scrollTop = messageContainer.scrollHeight;
    }
    
    // Make user items clickable to open conversations
    document.querySelectorAll('.user-item').forEach(function(item) {
        item.addEventListener('click', function() {
            const userId = this.getAttribute('data-user-id');
            window.location.href = 'chat.php?id=' + userId;
        });
    });

    // Track if user is scrolled to bottom
    if (messageContainer) {
        messageContainer.addEventListener('scroll', () => {
            const scrollPosition = messageContainer.scrollHeight - messageContainer.scrollTop - messageContainer.clientHeight;
            isScrolledToBottom = scrollPosition < 10; // Consider "almost at bottom" as "at bottom"
        });
    }

    // Add send button event listener
    const sendButton = document.getElementById('sendButton');
    if (sendButton) {
        sendButton.addEventListener('click', sendMessage);
    }

    // Add Enter key support
    const messageInput = document.getElementById('messageInput');
    if (messageInput) {
        messageInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                sendMessage();
            }
        });
    }

    // If we're in a conversation, load messages and start polling
    if (receiverId > 0) {
        loadMessages();
        // Auto refresh every 2 seconds
        setInterval(loadMessages, 2000);
    }
}

/**
 * Load messages from the server
 */
function loadMessages() {
    if (!messageContainer) return;
    
    // Remember scroll position
    const wasAtBottom = isScrolledToBottom;
    const prevScrollHeight = messageContainer.scrollHeight;
    const prevScrollTop = messageContainer.scrollTop;
    
    fetch(`getMessages.php?receiver_id=${receiverId}`)
        .then(res => res.json())
        .then(data => {
            // Check if there are new messages
            const currentMessageCount = messageContainer.querySelectorAll('.message').length;
            const hasNewMessages = data.length > currentMessageCount;
            
            // Update the messages
            messageContainer.innerHTML = '';
            
            data.forEach(msg => {
                const div = document.createElement('div');
                div.classList.add('message');
                div.classList.add(msg.sender_id == senderId ? 'sent' : 'received');
                
                const sentTime = new Date(msg.sent_at).toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'});
                
                div.innerHTML = `
                    <div class="message-content">${msg.message}</div>
                    <div class="message-time">${sentTime}</div>
                    ${msg.sender_id == senderId ? 
                        (msg.seen == 1 ? 
                            '<div class="message-status">Seen <i class="fas fa-check-double"></i></div>' : 
                            '<div class="message-status">Delivered <i class="fas fa-check"></i></div>'
                        ) : ''}
                `;
                messageContainer.appendChild(div);
            });
            
            // Only auto-scroll if we were at the bottom before loading new messages,
            // or if there are actually new messages
            if (wasAtBottom || hasNewMessages) {
                messageContainer.scrollTop = messageContainer.scrollHeight;
            } else {
                // Maintain the same scroll position
                const newScrollHeight = messageContainer.scrollHeight;
                messageContainer.scrollTop = prevScrollTop + (newScrollHeight - prevScrollHeight);
            }
        })
        .catch(error => {
            console.error('Error loading messages:', error);
        });
}

/**
 * Send a message to the server
 */
function sendMessage() {
    const msgInput = document.getElementById('messageInput');
    if (!msgInput) return;
    
    const message = msgInput.value.trim();

    if (message !== '') {
        fetch('sendMessage.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: `receiver_id=${receiverId}&message=${encodeURIComponent(message)}`
        })
        .then(res => res.json())
        .then(response => {
            if (response.success) {
                msgInput.value = '';
                loadMessages();
            }
        })
        .catch(error => {
            console.error('Error sending message:', error);
        });
    }
}