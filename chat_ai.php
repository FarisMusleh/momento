<!-- chatbox.php -->
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

    .pulse {
        position: absolute;
        width: 60px;
        height: 60px;
        border-radius: 50%;
        background-color: rgba(0, 0, 0, 0.4);
        animation: pulse 2s infinite;
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

    @media (max-width: 600px) {
        .chat-container {
            width: 85%;
            height: 70%;
            bottom: 80px;
            right: 10px;
        }
    }
</style>

<!-- Floating Chat Icon -->
<div class="chat-icon-container">
    <div class="chat-icon" id="chat-icon">
        <div class="chat-pulse"></div>
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
            <path d="M20 2H4c-1.1 0-2 .9-2 2v18l4-4h14c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zm0 14H5.17L4 17.17V4h16v12z"/>
            <path d="M7 9h10v2H7zm0-3h10v2H7zm0 6h7v2H7z"/>
        </svg>
        <span class="tooltip">Chat with your AI assistant</span>
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
            <span>Momento</span>
        </div>
        <button class="close-button" id="close-button">&times;</button>
    </div>
    <div class="chat-messages" id="chat-messages">
        <!-- Messages will go here -->
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
    const chatIcon = document.getElementById('chat-icon');
    const chatContainer = document.getElementById('chat-container');
    const closeButton = document.getElementById('close-button');

    chatIcon.addEventListener('click', () => {
        chatContainer.style.display = 'flex';
    });

    closeButton.addEventListener('click', () => {
        chatContainer.style.display = 'none';
    });
</script>
