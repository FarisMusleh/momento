<?php
	if (session_status() === PHP_SESSION_NONE) {
	session_start();
	}
	require('pdo.php');
	if(isset($_SESSION['data'])){
		$data = $_SESSION['data'];
	}else{
		exit();
	}
	$stmt = $pdo->prepare('delete from accounts where id = ?');
	$stmt->execute([$data['id']]);

?>