<?php
	session_start();
	require_once 'google_client.php';
	require '../../jwt/vendor/autoload.php';
	use Firebase\JWT\JWT;
	$privateKey = file_get_contents('../../keys/private_key.pem');
	if(isset($_GET['code'])) {
		$token = $client->fetchAccessTokenWithAuthCode($_GET['code']);
		if (!isset($token['error'])) {
			$client->setAccessToken($token['access_token']);
			$google_oauth = new Google_Service_Oauth2($client);
			$google_account_info = $google_oauth->userinfo->get();
			$email = $google_account_info->email;
			$name = $google_account_info->name;
			$profile = $google_account_info->picture;
			if($profile===null){
				
			}
			$token = JWT::encode(
					array(
						'iat'	=> time(),
						'nbf'	=> time(),
						'exp'	=> time() + 7200,
						'data'	=> array(
							'name'	=> $name,
							'email'	=> $email,
							'picture' => $profile
						)
					),
					$privateKey,'RS256'
				);
				setcookie("token", $token, time()+7200, "/", "");
			header('Location: ../../index.php');
			exit();
		} else {
			echo "Error during authentication.";
		}
	}
?>