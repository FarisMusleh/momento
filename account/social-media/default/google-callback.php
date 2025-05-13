<?php
	if (session_status() === PHP_SESSION_NONE) {
    session_start();
} 

	require 'vendor/autoload.php';
	require '../../pdo.php';

	use Hybridauth\Hybridauth;
	use Hybridauth\HttpClient;

	try {
		require 'config.php';
		$hybridauth = new Hybridauth($config);

		// Authentication
		$adapter = $hybridauth->authenticate('Google');
		$userProfile = $adapter->getUserProfile();

		// User Info
		$name = $userProfile->displayName;
		$email = $userProfile->email;
		$provider = 'google';
		$providerId = $userProfile->identifier;
		$picture = null;

		// Check if user exists
		$query = "SELECT * FROM accounts WHERE provider_id = ? AND provider = 'google'";
		$stmt = $pdo->prepare($query);
		$stmt->execute([$providerId]);
		$user = $stmt->fetch(PDO::FETCH_ASSOC);

		// Function to download profile picture
		function downloadProfilePic($url, $savePath) {
			$imageData = file_get_contents($url);
			if ($imageData !== false) {
				file_put_contents($savePath, $imageData);
				return true;
			}
			return false;
		}

		if ($user) {
			$_SESSION['data'] = array(
				'id' => $user['id'],
				'username' => $user['username'],
				'email' => $user['email'],
				'picture' => $user['picture'],
				'location' => $user['location']
			);
			$adapter->disconnect();
			header('Location: ../../index.php');
			exit();
		} else {
			// Save raw picture URL
			$profilePicUrl = $userProfile->photoURL;

			// Define folder
			$folder = '../../uploads/ProfilePicture/';
			if (!is_dir($folder)) {
				mkdir($folder, 0777, true);
			}

			// File name using providerId to avoid duplicates
			$filename = $folder . 'google_' . $providerId . '.jpg';

			// Download image
			if (downloadProfilePic($profilePicUrl, $filename)) {
				$picture = '/momento/uploads/ProfilePicture/google_' . $providerId . '.jpg';
			} else {
				$picture = $profilePicUrl; // fallback
			}

			$_SESSION['google'] = array(
				'email' => $email,
				'name' => $name,
				'providerId' => $providerId,
				'provider' => $provider,
				'picture' => $picture
			);

			header('Location: google-create.php');
		}

	} catch (Exception $e) {
		echo 'Oops! ' . $e->getMessage();
		header('Location: ../../logout.php');
		exit();
	}
?>
