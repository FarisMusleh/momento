<?php
/**
 * Social Media Authentication Initialization
 * This file handles the initial authentication redirect for social media logins
 */

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
session_unset();
session_destroy();
session_start();
// Redirect logged in users to home page
if (isset($_SESSION['data'])) {
    header('location: ../../index.php');
    exit();
}

require 'vendor/autoload.php';
use Hybridauth\Hybridauth;

// Handle authentication requests
if (isset($_GET['auth'])) {
    $provider = strtolower(trim($_GET['auth']));
    
    // Whitelist of allowed providers
    $allowedProviders = ['google'];
    
    if (in_array($provider, $allowedProviders)) {
        try {
            // Set provider in session for callback reference
            $_SESSION['provider'] = $provider;
            
            // Load configuration
            require 'config.php';
            
            // Initialize Hybridauth with config
            $hybridauth = new Hybridauth($config);
            
            // Start authentication process with specified provider
            $providerName = ucfirst($provider); // Convert to proper case for Hybridauth
            $hybridauth->authenticate($providerName);
            
            // Clear session provider after authentication
            unset($_SESSION['provider']);
            
        } catch (Exception $e) {
            // Log the error
            error_log('Social authentication error: ' . $e->getMessage());
            
            // Display user-friendly error
            $_SESSION['auth_error'] = 'Authentication failed. Please try again later.';
            header('location: ../login.php');
            exit();
        }
    } else {
        // Invalid provider requested
        $_SESSION['auth_error'] = 'Invalid authentication method.';
        header('location: ../login.php');
        exit();
    }
}
?>