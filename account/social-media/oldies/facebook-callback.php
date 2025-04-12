<?php
	require_once 'vendor/autoload.php';
	require '../../pdo.php';
	use Hybridauth\Hybridauth;
	use Hybridauth\HttpClient;
	session_start();
	try {
		require 'config.php';
		$hybridauth = new Hybridauth($config);
		$adapter = $hybridauth->authenticate('Facebook');
		$userProfile = $adapter->getUserProfile();
		
		$name = $userProfile->displayName;
		$email = $userProfile->email;
		$dob = $userProfile->birthYear;
		$gender = $userProfile->gender;
		$picture = null;
		if (!empty($userProfile->photoURL)) {
			$picture = htmlspecialchars($userProfile->photoURL);
		} else {
			echo 'No profile picture available.';
		}
		$provider = 'facebook';
		$providerId = $userProfile->identifier;
		//check query if user already exist...
		$query = "select * from accounts where provider_id = ? and provider = 'facebook'";
		$stmt = $pdo->prepare($query);
		$stmt->execute(array($providerId));
		$user = $stmt->fetch(PDO::FETCH_ASSOC);
		if(!$user){
			$stmt_1 = $pdo->prepare('insert into accounts(username,email,provider_id,provider) values(?,?,?,?)');
			$stmt_1->execute(array($name,$email,$providerId,$provider));
			$lastId = $pdo->lastInsertId();
			$stmt_2 = $pdo->prepare('insert into user_profiles(id,first_name,gender,dob,picture) values(?,?,?,?,?)');
			$stmt_2->execute(array($lastId,$name,$gender,$dob,$picture));
		}
		
		$_SESSION['data'] = array('username'=>$name,'email'=>$email);
		
		header('Location: ../../index.php');
		exit();
	} catch (Exception $e) {
		echo 'Oops, we ran into an issue: ' . $e->getMessage();
		header('Location: ../login.php');
		exit();
	}
?>