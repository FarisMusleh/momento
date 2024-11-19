<?php
	session_start();
	if(isset($_SESSION['data'])){
		header('location: ../../index.php');
		exit();
	}
	require 'vendor/autoload.php';

	use Hybridauth\Hybridauth;

	if(isset($_GET['auth'])){
		if($_GET['auth']==='google'){
			try {
				$_SESSION['provider'] = 'google';
				require 'config.php';
				$hybridauth = new Hybridauth($config);
				$hybridauth->authenticate('Google');
				unset($_SESSION['provider']);
			} catch (Exception $e) {
				echo 'Error: ' . $e->getMessage();
			}
		}
		elseif($_GET['auth']==='facebook'){
			try {
				$_SESSION['provider'] = 'facebook';
				require 'config.php';
				$hybridauth = new Hybridauth($config);
				$hybridauth->authenticate('Facebook');
				unset($_SESSION['provider']);
			} catch (Exception $e) {
				echo 'Error: ' . $e->getMessage();
			}
		}
	}
?>

