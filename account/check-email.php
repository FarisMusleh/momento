<?php
	require '../pdo.php';
	
	if(isset($_POST['key'])){
		$username = $_POST['key'];
		$stmt = $pdo->prepare("select email,provider from accounts where email = :email and provider = 'local'");
		$stmt->execute(array('email'=>$email));
		$data = $stmt->fetch();
		if ($data) {
			echo 'Email already taken';
		} else {
			echo "Email available";
		}
	}
	
?>