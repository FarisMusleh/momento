<?php
require('pdo.php');
if (isset($_POST['query'])) {
    $search = "%" . $_POST['query'] . "%";  // Using wildcards for LIKE query

    $sql = "SELECT id,username,account_type,picture FROM accounts WHERE account_type = 'business' AND username LIKE :search LIMIT 5";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':search', $search, PDO::PARAM_STR);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        while ($row = $stmt->fetch()) {
            $username = htmlspecialchars($row['username'], ENT_QUOTES);
			$pfp = htmlspecialchars($row['picture'], ENT_QUOTES);
			$id = intval($row['id'])??0;
            echo "<li style='padding:10px; border-bottom:1px solid #eee;'>
					<img src = '$pfp' height = 29 width = 29 style = 'border:solid black 1px;border-radius:5px;box-shadow:1px 1px 10px black;'>
                    <a href='profile.php?username=$username' class = 'username-edit'>
                      $username
                    </a><a href = '/momento/chat/chat.php?id=$id' class = 'btn btn-dark' style = 'border:1px solid black;color:white;font-family:Dancing script;box-shadow:1px 1px 10px black;'>chat</a>
                  </li>";
        }
    } else {
        echo "<li style='padding:10px;'>No match found</li>";
    }
}
?>

