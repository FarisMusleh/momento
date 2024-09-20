<?php
	session_start();
	require_once 'facebook_Client.php';
	require '../../jwt/vendor/autoload.php';
	use Firebase\JWT\JWT;
	use Hybridauth\Hybridauth;
	use Hybridauth\HttpClient;
	$privateKey = file_get_contents('../../keys/private_key.pem');
	try {
		$hybridauth = new Hybridauth($config);
		$adapter = $hybridauth->authenticate('Facebook');
		$userProfile = $adapter->getUserProfile();
		$name = $userProfile->displayName;
		$email = $userProfile->email;
		$token = JWT::encode(
					array(
						'iat'	=> time(),
						'nbf'	=> time(),
						'exp'	=> time() + 3600,
						'data'	=> array(
							'name'	=> $name,
							'email'	=> $email
						)
					),
					$privateKey,'RS256'
				);
				setcookie("token", $token, time()+3600, "/", "", true, true);
	} catch (\Exception $e) {
		echo 'Oops, we ran into an issue: ' . $e->getMessage();
	}
?>