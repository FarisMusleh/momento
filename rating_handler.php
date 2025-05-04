<?php
	if (session_status() === PHP_SESSION_NONE) {
		session_start();
	}
	if(isset($_POST['comment'])&&isset($_SESSION['data'])){
		require "pdo.php";
		if(isset($_POST['update'])&&isset($_POST['rate_id'])){
			$sql = $pdo->prepare('update reviews set rate = ?,comment = ? where id = ?');
		    $sql->execute([$_POST['rating'],$_POST['comment'],$_POST['rate_id']]);
		}else{
		$sql = $pdo->prepare('insert into reviews(user_id,photographer_id,rate,comment) values(?,?,?,?)');
		$sql->execute([$_SESSION['data']['id'],$_POST['photographer_id'],$_POST['rating'],$_POST['comment']]);
		$total_select = $pdo->prepare('select total_reviews,total_rate from business_profiles where id = ?');
		$total_select->execute([$_POST['photographer_id']]);
		$result = $total_select->fetch();
		$total_update = $pdo->prepare('update business_profiles set total_reviews = ?, total_rate = ? where id = ?');
		$total_update->execute([$result['total_reviews']+1,$result['total_rate']+$_POST['rating'],$_POST['photographer_id']]);
		}
		header("Location: " . $_SERVER['HTTP_REFERER']);
		exit();
	}
?>