<?php
	require_once 'google_client.php';
	$authUrl = $client->createAuthUrl();
	header('Location: ' . $authUrl);
	exit();
	//Install Composer libraryManagement
	//Start session
	//new Google_Client();
	//Set id,secret,uri,scoops
	//If authentication code from google is set, then exchange authentication code with accessToken and then create Google_Sevice_Oauth2().
	//Else go to login screen then select your google account to login to get the authentication code.

	/*Note: Google_Client() vs Google_Service_Oauth2(): Google_Client() to create client object and set the information
	but with Google_Service_Oauth2() you can get user information.*/
?>