<?php
session_start();
$config = [
    'callback' => 'http://localhost/momento/account/social-media/'. $_SESSION['provider'] .'-callback.php',
    'providers' => [
        'Google' => [
            'enabled' => true,
            'keys' => [
                'id' => '76995771584-eqncjusmhvvu6mtdin3ro8iudgcnqf12.apps.googleusercontent.com',
                'secret' => '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',
            ],
            'scope' => 'https://www.googleapis.com/auth/userinfo.profile https://www.googleapis.com/auth/userinfo.email',
        ],
		'Facebook' => [
				'enabled' => true,
				'keys' => [
					'id' => '527129296503622',
					'secret' => 'bf905652b93421546d766f4f5377a816',
				],
				'scope'   => 'email , public_profile',
				'trustForwarded' => false,
				'fields' => ['id', 'name', 'email', 'picture.type(large)'],
			],
    ],
];
?>