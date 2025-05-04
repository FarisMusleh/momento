<?php
	require 'pdo.php';
	if($_POST['del_exp']){
		$id = $_POST['del_exp'];
		$sql = $pdo->prepare('delete from experience where id = ?');
		$sql->execute([$id]);
		header('location: profile.php');
	}else{
		header('location: index.php');
	}
?>