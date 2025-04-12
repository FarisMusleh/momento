<?php
require('pdo.php');
if (isset($_POST['query'])) {
    $search = "%" . $_POST['query'] . "%";  // Using wildcards for LIKE query

    $sql = "SELECT username,account_type,picture FROM accounts WHERE account_type = 'business' AND username LIKE :search LIMIT 5";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':search', $search, PDO::PARAM_STR);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        while ($row = $stmt->fetch()) {
            $username = htmlspecialchars($row['username'], ENT_QUOTES);
			$pfp = htmlspecialchars($row['picture'], ENT_QUOTES);
            echo "<li style='padding:10px; cursor:pointer; border-bottom:1px solid #eee;'>
					<img src = '$pfp' height = 27 width = 27>
                    <a href='profile.php?username=$username' style='text-decoration:none; color:#333; display:block;'>
                      $username
                    </a>
                  </li>";
        }
    } else {
        echo "<li style='padding:10px;'>No match found</li>";
    }
}
?>

