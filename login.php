<?php
	if(isset($_COOKIE['token'])){
			header('location:index.php');
	}
	session_start();
	require_once('pdo.php');
	require 'jwt/vendor/autoload.php';
	use Firebase\JWT\JWT;
	$privateKey = file_get_contents('keys/private_key.pem');
	if ($_SERVER["REQUEST_METHOD"] == "POST") {
		$email = $_POST["email"];
		$password = $_POST["password"];

		// Prepare the statement
		$stmt = $pdo->prepare('SELECT * FROM users WHERE email = :email');
		$stmt->execute(['email' => $email]);
		$user = $stmt->fetch(PDO::FETCH_ASSOC);

		if ($user) {
			if (password_verify($password,$user['password'])) { 
				$token = JWT::encode(
					array(
						'iat'	=> time(),
						'nbf'	=> time(),
						'exp'	=> time() + 7200,
						'data'	=> array(
							'name'	=> $user['first_name'],
							'email'	=> $user['email'],
							'picture' => null
						)
					),
					$privateKey,'RS256'
				);
				setcookie("token", $token, time()+7200, "/", "", true, true);
				header("Location:index.php");
				exit(); 
			} else {
				$_SESSION['invalid_info'] = "Invalid email or password!";
			}
		} else {
			$_SESSION['invalid_info'] = "Invalid email or password!";
		}
	}
?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.3.1/css/bootstrap.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.3.1/js/bootstrap.min.js"></script>
	<link rel = "stylesheet" href = "css/google_button.scss">
	<link rel = "stylesheet" href = "css/facebook_button.scss">
	<link rel = "stylesheet" href = "css/login.css">
</head>
<body>
    <div class="login">
        <div class="row">
            <div class="col-md-4 login-left">
                <img src="img/vertical-logo.webp" alt="Logo"/>
                <h3>Get back your moments!</h3>
                <input type="button" value="Create an Account" onclick="location.href='register.php'"/><br/>
            </div>
            <div class="col-md-8 login-right">
			<div style = "text-align: center;font-size:18px;font-weight:bold;">
				<?php 
					if (isset($_SESSION['create_success'])): 
						echo "<div style = 'color:lightgreen;'>{$_SESSION['create_success']}</div>";
						unset($_SESSION['create_success']);
					endif;
					if (isset($_SESSION['invalid_info'])):
						echo "<div style = 'color:pink;'>{$_SESSION['invalid_info']}</div>";
						unset($_SESSION['invalid_info']);
					endif;
				?>
			</div>
                <h3 class="login-heading">Login</h3>
                <form method = "post">
                    <div class="form-group">
                        <input name = "email" type="email" class="form-control" placeholder="Your Email *" required/>
                    </div>
                    <div class="form-group">
                        <input name = "password" type="password" class="form-control" placeholder="Your Password *" required/>
                    </div>
                    <div class="form-group" style = "margin-left:20px;">
                        <input name="remember" value="1" type="checkbox" class="form-check-input" />
                        <label class="form-check-label" style = "font-weight:bold" for="rememberMe">Remember Me</label>
                    </div>
                    <input type="submit" class="btnLogin" value="Login"/>
					
                </form>
				<div class = "socials">
				<input class="login-with-google-btn element"  type="button" value="Google" onclick="location.href='social_media/google/google_authentication.php'"/>
				<input class="login-with-facebook-btn element"  type = "button" value = "facebook" onclick="location.href='social_media/facebook/facebook_authentication.php'"/>
				</div>
			</div>
        </div>
    </div>
</body>
</html>
