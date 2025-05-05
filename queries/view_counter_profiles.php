<?php
if (!isset($_GET['username'])) {
    die("Photographer username not provided.");
}

$today = date('Y-m-d');

try {
   

    if (isset($_SESSION['data']['id'])) {
        $userId = $_SESSION['data']['id'];
        $username = $_GET['username'];

        // Get photographer's user ID by username
        $stmt = $pdo->prepare('SELECT id FROM accounts WHERE username = ? AND account_type = "business"');
        $stmt->execute([$username]);
        $photographer = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$photographer) {
            exit('error: photographer not found');
        }

        $photographer_id = $photographer['id'];

        // Check if the user already viewed this photographer's profile today
        $check = $pdo->prepare("SELECT 1 FROM photographer_view_logs WHERE photographer_id = ? AND user_id = ? AND view_date = ?");
        $check->execute([$photographer_id, $userId, $today]);

        if ($check->rowCount() === 0) {
            // Insert view log
            $insert = $pdo->prepare("INSERT INTO photographer_view_logs (photographer_id, user_id, view_date) VALUES (?, ?, ?)");
            $insert->execute([$photographer_id, $userId, $today]);
        }
    }

} catch (PDOException $e) {
    echo "Database error: " . $e->getMessage();
}
?>
