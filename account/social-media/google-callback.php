<?php
	require 'vendor/autoload.php';
	require '../../pdo.php';
	use Hybridauth\Hybridauth;
	use Hybridauth\HttpClient;
	try{
		require 'config.php';
		$hybridauth = new Hybridauth($config);
		//authentication...
		$adapter = $hybridauth->authenticate('Google');
		$userProfile = $adapter->getUserProfile();
		//user info...
		$name = $userProfile->displayName;
		$email = $userProfile->email;
		$provider = 'google';
		$providerId = $userProfile->identifier;
		$picture = null;
		//check query if user already exist...
		$query = "select * from accounts where provider_id = ? and provider = 'google'";
		$stmt = $pdo->prepare($query);
		$stmt->execute(array($providerId));
		$user = $stmt->fetch(PDO::FETCH_ASSOC);
		//if user does exist go to home page else create acc details...
		if($user){
			$_SESSION['data'] = array('email'=>$email,'name'=>$name,'username'=>$user['username']);
			$adapter->disconnect();
			header('location: ../../index.php');
			exit();
		}else{
			$picture = htmlspecialchars($userProfile->photoURL);
			$_SESSION['google'] = array('email'=>$email,'name'=>$name,'providerId'=>$providerId,'provider'=>$provider,'picture'=>$picture);
			header('location:google-create.php');
		}
	}catch(Exception $e){
		echo 'Oops! ' . $e->getMessage();
		header('Location:../../logout.php');
		exit();
	}
?>