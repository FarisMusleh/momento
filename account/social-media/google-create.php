<?php
	session_start();
	require '../../pdo.php';
	if(isset($_SESSION['data'])){
		header('location:../../index.php');
		exit();
	}elseif(!isset($_SESSION['google'])){
		header('location:../login.php');
		exit();
	}elseif(isset($_POST['username'])&&isset($_POST['acc-type'])){
		$username = $_POST['username'];
		$type = $_POST['acc-type'];
		$data = $_SESSION['google'];
		$email = $data['email'];
		$name = $data['name'];
		$provider = $data['provider'];
		$providerId = $data['providerId'];
		$picture = $data['picture'];
		$stmt_1 = $pdo->prepare('insert into accounts(username,email,provider_id,provider,account_type) values(?,?,?,?,?)');
		$stmt_1->execute(array($username,$email,$providerId,$provider,$type));
		$lastId = $pdo->lastInsertId();
		$stmt_2 = $pdo->prepare('insert into ' . $type . '_profiles(id,first_name,picture) values(?,?,?)');
		$stmt_2->execute(array($lastId,$name,$picture));
		$_SESSION['data'] = array('email'=>$email,'name'=>$name,'username'=>$username);
		unset($_SESSION['google']);
		header('Location: ../../index.php');
	}
?>

<html>
	<body>
		<form method = 'post'>
			<input type = 'text' placeholder = "Username *" name = 'username' id = 'username'>
			<input type = "submit">
			<span id = 'username-status'></span>
			<br>
			<p>Account type</p>
			<label>User<input type = 'radio' name = 'acc-type' value = 'user'>
			<label>Business<input type = 'radio' name = 'acc-type' value = 'business'>
			
		</form>
	</body>
</html>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
	$(document).ready(function(){
		$('#username').on('input', function(){
			let temp = $(this).val();
			
			if(temp.length > 0){
				$('#username-status').text("...");
				setTimeout(function() {
					$.ajax({
						url: '../check-username.php',
						type: 'post',
						data: {key:temp},
						success: function(response){
							$('#username-status').text(response);
						}
					});
				},900);
			}else{
				$('#username-status').text('');
			}
		});
	});
</script>