<?php

if (!isset($_GET['id'])) {
    die("Image ID not provided.");
}

$imageId = intval($_GET['id']);
$today = date('Y-m-d');

try {
    require('../pdo.php');

    // Only count the view if user is logged in
    if (isset($_SESSION['data']['id'])) {
        $userId = $_SESSION['data']['id'];
		$stmt = $pdo->prepare('SELECT user_id FROM images WHERE id = ?');
		$stmt->execute([$imageId]);
		$photographer = $stmt->fetch(PDO::FETCH_ASSOC);

		if (!$photographer) {
			exit('error: image not found');
		}

		$photographer_id = $photographer['user_id'];
		
        // Check if the user already viewed this image today
        $check = $pdo->prepare("SELECT 1 FROM image_view_logs WHERE image_id = ? AND user_id = ? AND view_date = ?");
        $check->execute([$imageId, $userId, $today]);

        if ($check->rowCount() === 0) {
            // Insert view log
            $insert = $pdo->prepare("INSERT INTO image_view_logs (image_id, user_id, view_date) VALUES (?, ?, ?)");
            $insert->execute([$imageId, $userId, $today]);

            // Increment view counter
            $update = $pdo->prepare("UPDATE images SET views = views + 1 WHERE id = ?");
            $update->execute([$imageId]);
			
			$update_business_profiles = $pdo->prepare("UPDATE business_profiles SET total_views = total_views + 1 WHERE id = ?");
            $update_business_profiles->execute([$photographer_id]);
        }
    }

} catch (PDOException $e) {
    echo "Database error: " . $e->getMessage();
}
?>
