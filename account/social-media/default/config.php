<?php
$config = [
    'callback' => 'http://localhost/momento/account/social-media/'. 'google' .'-callback.php',
    'providers' => [
        'Google' => [
            'enabled' => true,
            'keys' => [
                'id' => '76995771584-eqncjusmhvvu6mtdin3ro8iudgcnqf12.apps.googleusercontent.com',
                'secret' => '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',
            ],
            'scope' => 'https://www.googleapis.com/auth/userinfo.profile https://www.googleapis.com/auth/userinfo.email',
        ],
    ],
];
?>