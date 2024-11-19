<?php
	try{
		$pdo = new PDO('mysql:host=localhost;port=3306;dbname=projectdb','root','');
	}catch(PDOException $ex){
		echo $ex;
		echo "Not Working now :( 404";
	}
?>
              