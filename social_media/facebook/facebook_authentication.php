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
		$profile = $userProfile->photoURL;
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
	} catch (\Exception $e) {
		echo 'Oops, we ran into an issue: ' . $e->getMessage();
	}
?>