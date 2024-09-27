<?php
	require_once 'vendor/autoload.php';
	$clientID = '76995771584-eqncjusmhvvu6mtdin3ro8iudgcnqf12.apps.googleusercontent.com';
	$clientSecret = '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';
	$redirectUri = 'http://localhost/momento/social_media/google/callback.php';
	$client = new Google_Client();
	$client->setClientId($clientID);
	$client->setClientSecret($clientSecret);
	$client->setRedirectUri($redirectUri);
	$client->addScope("email");
	$client->addScope("profile");
?>