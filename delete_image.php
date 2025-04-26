<?php
	if (session_status() === PHP_SESSION_NONE) {
		session_start();
	}
	if(isset($_POST['imageId'])){
		$id = $_POST['imageId'];
	}else{
		header('location:/momento/index.php');
		exit();
	}
	if(!$_SESSION['data']['id']==$_POST['userId']){
		header('location:/momento/index.php');
		exit();
	}
	require "pdo.php";
	$sql = $pdo->prepare('SELECT url FROM images WHERE id = ?');
	$sql->execute([$id]);
	$image = $sql->fetch(PDO::FETCH_ASSOC);

	if ($image) {
		$imageUrl = $image['url'];

		if (file_exists($imageUrl)) {
			unlink($imageUrl);
		} else {
			//HANDLE TOAST
		}
		$deleteSql = $pdo->prepare('DELETE FROM images WHERE id = ?');
		$deleteSql->execute([$id]);

	} else {
		//HANDLE TOAST
	}
	header('location:/momento/profile.php');
?>