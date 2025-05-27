<?php
	require('pdo.php');
	$email = 'admin@momento.com';
	$password_plain = 'abcde123456789abcdeK4321adn@';
	$password_hashed = password_hash($password_plain, PASSWORD_DEFAULT);

	// Insert into database
	$sql = "INSERT INTO accounts (email, password) VALUES (:email, :password)";
	$stmt = $pdo->prepare($sql);
	$stmt->execute([
		':email' => $email,
		':password' => $password_hashed,
	]);

	echo "Admin inserted successfully.";
	
?>