<?php
require('pdo.php');
if (isset($_POST['query'])) {
    $search = "%" . $_POST['query'] . "%";  // Using wildcards for LIKE query
    $sql = "SELECT id,username,account_type,picture FROM accounts WHERE account_type = 'business' AND username LIKE :search LIMIT 5";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':search', $search, PDO::PARAM_STR);
    $stmt->execute();
    if ($stmt->rowCount() > 0) {
        $index = 0;
        while ($row = $stmt->fetch()) {
            $username = htmlspecialchars($row['username'], ENT_QUOTES);
            $pfp = htmlspecialchars($row['picture'], ENT_QUOTES);
            $id = intval($row['id']) ?? 0;
            echo "<li>
                    <div class='user-info'>
                      <img src='$pfp' class='profile-image' alt='$username'>
                      <a href='profile.php?username=$username' class='username-edit'>
                        $username
                      </a>
                    </div>
                    <div class='action-buttons'>
                      <a href='appointment/index.php?photographer_id=$id' class='btn-action book-btn'>
                        <i data-feather='calendar'></i> Book
                      </a>
                      <a href='/momento/chat/chat.php?id=$id' class='btn-action chat-btn'>
                        <i data-feather='message-circle'></i> Chat
                      </a>
                    </div>
                  </li>";
            $index++;
        }
    } else {
        echo "<li class='empty-result'>No photographers found</li>";
    }
}
?>

<script>
// Make sure to initialize Feather icons after dynamic content is loaded
if (typeof feather !== 'undefined') {
  setTimeout(() => feather.replace(), 10);
}
</script>