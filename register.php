<?php
	require('pdo.php');
	session_start();
	if($_SERVER["REQUEST_METHOD"] == "POST"){
		if(isset($_POST['user'])){
			if($_POST['user_pass']===$_POST['user_confirm']){
				$user_fn = $_POST['user_fn'];
				$user_ln = $_POST['user_ln'];
				$user_pass = $_POST['user_pass'];
				$hashed_pass = password_hash($user_pass,PASSWORD_DEFAULT);
				$user_email = $_POST['user_email'];
				$user_num = $_POST['user_num'];
				$user_gender = $_POST['user_gender'];
				$sql = $pdo->prepare('insert into users(first_name,last_name,email,password,phone,gender)
				values(?,?,?,?,?,?)');
				$sql->execute(array($user_fn,$user_ln,$user_email,$hashed_pass,$user_num,$user_gender));
				$_SESSION['create_success'] = "Account Created!";
				header("location:login.php");
			}else{
				$_SESSION['password_error'] = "Passwords do not match. Please try again.";
				header("Refresh:0");
				exit;
			}
		}
		if(isset($_POST['photographer'])){
			$photographer_fn = $_POST['photographer_fn'];
			$photographer_ln = $_POST['photographer_ln'];
			$photographer_pass = $_POST['photographer_pass'];
			$hashed_pass = password_hash($photographer_pass,PASSWORD_DEFAULT);
			$photographer_email = $_POST['photographer_email'];
			$photographer_num = $_POST['photographer_num'];
			$photographer_gender = $_POST['photographer_gender'];
			$sql = $pdo->prepare('insert into photographers(first_name,last_name,email,password,phone,gender)
			values(?,?,?,?,?,?)');
			$sql->execute(array($photographer_fn,$photographer_ln,$photographer_email,
			$hashed_pass,$photographer_num,$photographer_gender));
			$_SESSION['create_success'] = "Account Created!";
			header("location:login.php");
		}
		
	}
?>

<html>
	<head>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.3.1/css/bootstrap.min.css">
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.3.1/js/bootstrap.min.js"></script>
	<link rel="stylesheet" href = "css/register.css">
	</head>
	<body class="vsc-initialized">
		<div class="register">
			<div class="row">
			<div class="col-md-3 register-left">
				<img src="img/vertical-logo.webp" alt="">
				<h3>Capture your moment!</h3>
				<input type="submit" name="" value="Login" onclick="window.location.href='login.php'" class = "test"><br>
			</div>
			<div class="col-md-9 register-right">
				<ul class="nav nav-tabs nav-justified" id="myTab" role="tablist">
				<li class="nav-item">
				<a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">User</a>
				</li>
				<li class="nav-item">
				<a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false">Photographer</a>
				</li>
				</ul>
				<div class="tab-content" id="myTabContent">
				<div style = "text-align: center;font-size:18px;font-weight:bold;margin-left:150px;margin-top:20px;">
				<?php
					if(isset($_SESSION['password_error'])):
						echo "<div style = 'color:pink;'>{$_SESSION['password_error']}</div>";
						unset($_SESSION['password_error']);
					endif;
				?>
				</div>
					<div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
						<h3 class="register-heading">Apply as a User</h3>
						<form method = "post">
						<div class="row register-form">
							<div class="col-md-6">
								<div class="form-group">
									<input name = "user_fn" type="text" class="form-control" placeholder="First Name *" value="" required>
								</div>
								<div class="form-group">
									<input name = "user_ln" type="text" class="form-control" placeholder="Last Name *" value="" required>
								</div>
								<div class="form-group">
									<input name = "user_pass" type="password" class="form-control" placeholder="Password *" value="" required>
								</div>
								<div class="form-group">
									<input name = "user_confirm" type="password" class="form-control" placeholder="Confirm Password *" value="" required>
								</div>
								<div class="form-group">
									<div class="maxl">
									<label class="radio inline">
										<input name = "user_gender" type="radio" name="gender" checked="" value = "0">
										<span> Male </span>
									</label>
									<label class="radio inline">
										<input name = "user_gender" type="radio" name="gender" value = "1">
										<span>Female </span>
									</label>
									</div>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<input name = "user_email" type="email" class="form-control" placeholder="Your Email *" value="" required>
								</div>
								<div class="form-group">
									<input name = "user_num" type="text" minlength="10" maxlength="10" name="txtEmpPhone" class="form-control" placeholder="Your Phone *" value="" required>
									<!-------------------------FIRST BUTTON---------------------------->
									<input name = "user" type="submit" class="btnRegister" value="Register">
								</div>
							</div>
						</div>
						</form>
					</div>
					<div class="tab-pane fade show" id="profile" role="tabpanel" aria-labelledby="profile-tab">
						<h3 class="register-heading">Apply as a Photographer</h3>
						<form method = "post">
							<div class="row register-form">
								<div class="col-md-6">
									<div class="form-group">
										<input name = "photographer_fn" type="text" class="form-control" placeholder="First Name *" value="" required>
									</div>
									<div class="form-group">
										<input name = "photographer_ln" type="text" class="form-control" placeholder="Last Name *" value="" required>
									</div>
									<div class="form-group">
										<input name = "photographer_email" type="email" class="form-control" placeholder="Email *" value="" required>
									</div>
									<div class="form-group">
										<input name = "photographer_num" type="text" maxlength="10" minlength="10" class="form-control" placeholder="Phone *" value="" required>
									</div>
									<div class="maxl">
										<label class="radio inline">
											<input name = "photographer_gender" type="radio" name="gender" checked="" value = "0">
											<span> Male </span>
										</label>
										<label class="radio inline">
											<input name = "photographer_gender" type="radio" name="gender" value = "1">
											<span>Female </span>
										</label>
									</div>								
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<input name = "photographer_pass" type="password" class="form-control" placeholder="Password *" value="" required>
									</div>
									<div class="form-group">
										<input type="password" class="form-control" placeholder="Confirm Password *" value="" required>
									</div>
									<input name = "photographer" type="submit" class="btnRegister" value="Register">
								</div>
							</div>
						</form>
					</div>
				</div>
			</div>
			</div>
		</div> 
		<script type="text/javascript"></script>
	</body>
</html>