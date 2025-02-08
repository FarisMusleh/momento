<?php
	require '../pdo.php';
	
	if(isset($_POST['key'])){
		$username = $_POST['key'];
		$stmt = $pdo->prepare('select username from accounts where username = :username');
		$stmt->execute(array('username'=>$username));
		$data = $stmt->fetch();
		if ($data) {
			echo False;
		} else {
			echo True;
		}
	}
	
?>