<?php
/**
 * OAuth Configuration
 * This file contains configuration for OAuth providers
 * IMPORTANT: Move client ID and secret to environment variables in production
 */

// Base URL for callbacks
$baseUrl = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https://' : 'http://';
$baseUrl .= $_SERVER['HTTP_HOST'];
$baseUrl .= '/momento/account/social-media/';

// Get provider from session
$provider = isset($_SESSION['provider']) ? $_SESSION['provider'] : 'google';

// Configuration for Hybridauth
$config = [
    'callback' => $baseUrl . $provider . '-callback.php',
    'providers' => [
        'Google' => [
            'enabled' => true,
            'keys' => [
                // In production, use environment variables:
                // 'id' => getenv('GOOGLE_CLIENT_ID'),
                // 'secret' => getenv('GOOGLE_CLIENT_SECRET'),
                'id' => '76995771584-eqncjusmhvvu6mtdin3ro8iudgcnqf12.apps.googleusercontent.com',
                'secret' => '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',
            ],
            'scope' => 'https://www.googleapis.com/auth/userinfo.profile https://www.googleapis.com/auth/userinfo.email',
        ],
    ],
];
?>