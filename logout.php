<?php
	require 'account/social-media/vendor/autoload.php';
	use Hybridauth\Hybridauth;
	try {
    require 'account/social-media/config.php';
    $hybridauth = new Hybridauth($config);
    $isHybridAuthLoggedIn = !empty($hybridauth->getConnectedAdapters());

    if ($isHybridAuthLoggedIn) {
        foreach ($hybridauth->getConnectedAdapters() as $provider => $adapter) {
            $adapter->disconnect();
        }
    }
	session_start();
	session_unset();
	session_destroy(); 
	setcookie('token', "", time() - 3600, "/", "", true, true);
	header("Location: account/login.php");
	exit();
	}catch(Exception $e){
		echo 'Logout error: ' . $e->getMessage();
		exit();
	}
?>