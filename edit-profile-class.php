<?php
	session_start();
	require('pdo.php');
	if($_SERVER['REQUEST_METHOD']==='POST'){
		$data = $_POST;
		$desc = $data['description'];
		$title = $data['title'];
		$number = $data['number'];
		$pass = $data['pass'];
		$npass = $data['npass'];
		$email = $data['email'];
		if($pass === $npass){
			$sql = $pdo->prepare('update ? ');
		}
	}

?>


