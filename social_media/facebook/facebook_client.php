<?php
	require_once 'vendor/autoload.php';

	use Hybridauth\Hybridauth;
	use Hybridauth\HttpClient;
	
	$config = [
		'callback' => 'http://localhost/momento/social_media/facebook/callback.php',

		'providers' => [
			'Facebook' => [
				'enabled' => true,
				'keys' => [
					'id' => '527129296503622',
					'secret' => 'bf905652b93421546d766f4f5377a816',
				],
				'scope'   => 'email,public_profile',
				'trustForwarded' => false,
			]
		]
	];
?>