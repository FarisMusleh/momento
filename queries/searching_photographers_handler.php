<?php
session_start();
require('../pdo.php');

if (isset($_POST['query'])) {
    $search = "%" . $_POST['query'] . "%";

    // Determine if the user is logged in and is a "user"
    $canBook = false;
    if (isset($_SESSION['data']['id'])) {
        $currentUserId = $_SESSION['data']['id'];
        $stmtType = $pdo->prepare('SELECT account_type FROM accounts WHERE id = ?');
        $stmtType->execute([$currentUserId]);
        $type_a = $stmtType->fetch(PDO::FETCH_ASSOC);
        if ($type_a && $type_a['account_type'] === 'user') {
            $canBook = true;
        }
    }

    $sql = "SELECT id, username, account_type, picture FROM accounts WHERE account_type = 'business' AND username LIKE :search LIMIT 5";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':search', $search, PDO::PARAM_STR);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $username = htmlspecialchars($row['username'], ENT_QUOTES);
            $pfp = htmlspecialchars($row['picture'], ENT_QUOTES);
            $id = intval($row['id']);
            $encodedUsername = urlencode($username);

            echo "<li>
                    <div class='user-info'>
                      <img src='$pfp' class='profile-image' alt='$username'>
                      <a href='profile.php?username=$encodedUsername' class='username-edit'>
                        $username
                      </a>
                    </div>
                    <div class='action-buttons'>";

            // Show Book button only for logged-in "user" type
            if ($canBook) {
                echo "<a href='appointment/index.php?photographer_id=$id' class='btn-action book-btn'>
                        <i data-feather='calendar'></i> Book
                      </a>";
            }

			$chatLink = isset($_SESSION['data']['id']) 
				? "/momento/chat/chat.php?id=$id" 
				: "/momento/account/login.php";

			$chatTitle = isset($_SESSION['data']['id']) 
				? "Chat with photographer" 
				: "Please log in to start a chat";

			echo "<a href='$chatLink' class='btn-action chat-btn' title='$chatTitle'>
					<i data-feather='message-circle'></i> Chat
				  </a>
                </div>
              </li>";
        }
    } else {
        echo "<li class='empty-result'>No photographers found</li>";
    }
}
?>

<script>
// Initialize feather icons after content load
if (typeof feather !== 'undefined') {
  setTimeout(() => feather.replace(), 10);
}
</script>
