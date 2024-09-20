<?php
	require 'jwt/vendor/autoload.php';
	session_start();
	$publicKey = file_get_contents('keys/public_key.pem');
	use Firebase\JWT\JWT;
	use Firebase\JWT\Key;
	if(isset($_COOKIE['token'])){
		$decoded = JWT::decode($_COOKIE['token'], new Key($publicKey, 'RS256'));
	}
	else{
		header('location:login.php');
	}
	echo "Welcome, " . $decoded->data->name . "! Your email is " . $decoded->data->email;
	echo '<br><a href = "logout.php">Logout</a>';
	/*if (isset($_SESSION['email']) && isset($_SESSION['name'])) {
		echo "Welcome, " . $_SESSION['name'] . "! Your email is " . $_SESSION['email'];
	} else {
		echo "Please log in first.";
		exit();
	}
	*/
?>