<?php
// db.php - include your PDO connection here
require_once '../pdo.php';
session_start();

try {
    // Assume logged in user ID is stored in session
    $user_id = $_SESSION['data']['id'] ?? null;

    if (!$user_id && $_POST['photographer_id']) {
        header('location: ../index.php');
    }

    // Get form values
    $photographer_id = $_POST['photographer_id'];
	$full_name = $_POST['full_name'];
	$email = $_POST['email'];
	$phone_number = $_POST['phone_number'];
    $category = $_POST['category'];
    $location = $_POST['location'];
    $duration_minutes = $_POST['duration_minutes'];
    $date = $_POST['date'];
    $notes = $_POST['notes'];
    $status = "pending";

    // Insert appointment
    $stmt = $pdo->prepare("
        INSERT INTO appointments (user_id, photographer_id, full_name, email, phone_number, category, location,
		duration_minutes, date, notes, status)
        VALUES (:user_id, :photographer_id, :full_name,
		:email, :phone_number, :category, :location,
		:duration_minutes, :date, :notes, :status)
    ");

    $stmt->execute([
        ':user_id' => $user_id,
        ':photographer_id' => $photographer_id,
		':full_name' => $full_name,
		':email' => $email,
		':phone_number' => $phone_number,
        ':category' => $category,
        ':location' => $location,
        ':duration_minutes' => $duration_minutes,
        ':date' => $date,
		':notes' => $notes,
        ':status' => $status
    ]);
	$sql_username = $pdo->prepare('select username from accounts where id = ?');
	$sql_username->execute([$photographer_id]);
	$username = $sql_username->fetch();
    header('location: /momento/profile.php?username='.$username['username']);
} catch (Exception $e) {
    echo "error: " . $e->getMessage();
}
?>
