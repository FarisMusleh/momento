<?php
/**
 * Google OAuth Callback Handler
 * This file processes the callback from Google OAuth and either logs in the user
 * or redirects them to complete their profile information
 */

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require 'vendor/autoload.php';
require '../../pdo.php';

use Hybridauth\Hybridauth;
use Hybridauth\HttpClient;

try {
    // Load configuration
    require 'config.php';
    $hybridauth = new Hybridauth($config);

    // Complete authentication process
    $adapter = $hybridauth->authenticate('Google');
    $userProfile = $adapter->getUserProfile();

    // Extract user data
    $name = htmlspecialchars($userProfile->displayName);
    $email = filter_var($userProfile->email, FILTER_SANITIZE_EMAIL);
    $provider = 'google';
    $providerId = $userProfile->identifier;
    $picture = null;

    // Check if user already exists
    $query = "SELECT * FROM accounts WHERE provider_id = ? AND provider = ?";
    $stmt = $pdo->prepare($query);
    $stmt->execute([$providerId, $provider]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    /**
     * Download and save profile picture
     *
     * @param string $url URL of the image to download
     * @param string $savePath Local path to save the image
     * @return bool True if successful, false otherwise
     */
    function downloadProfilePic($url, $savePath) {
        try {
            $imageData = @file_get_contents($url);
            if ($imageData !== false) {
                return file_put_contents($savePath, $imageData) !== false;
            }
        } catch (Exception $e) {
            error_log("Error downloading profile picture: " . $e->getMessage());
        }
        return false;
    }

    if ($user) {
        // User exists, log them in
        $_SESSION['data'] = [
            'id' => $user['id'],
            'username' => $user['username'],
            'email' => $user['email'],
            'picture' => $user['picture'],
            'location' => $user['location'],
            'type' => $user['account_type']
        ];
        
        // Disconnect from adapter
        $adapter->disconnect();
        
        // Redirect to home
        header('Location: ../../index.php');
        exit();
    } else {
        // New user, process profile picture
        $profilePicUrl = $userProfile->photoURL;

        // Create uploads directory if it doesn't exist
        $folder = '../../uploads/ProfilePicture/';
        if (!is_dir($folder)) {
            mkdir($folder, 0777, true);
        }

        // Unique filename using providerId
        $filename = $folder . 'google_' . $providerId . '.jpg';
        $webPath = '/momento/uploads/ProfilePicture/google_' . $providerId . '.jpg';

        // Try to download profile picture
        $picture = downloadProfilePic($profilePicUrl, $filename) ? $webPath : $profilePicUrl;

        // Store data in session for registration completion
        $_SESSION['google'] = [
            'email' => $email,
            'name' => $name,
            'providerId' => $providerId,
            'provider' => $provider,
            'picture' => $picture
        ];

        // Redirect to complete registration
        header('Location: google-create.php');
        exit();
    }

} catch (Exception $e) {
    // Log error
    error_log('Google callback error: ' . $e->getMessage());
    
    // Redirect with error
    $_SESSION['auth_error'] = 'Authentication failed. Please try again.';
    header('Location: ../../logout.php');
    exit();
}
?>