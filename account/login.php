<?php
	session_start();
	if(isset($_SESSION['data'])){
		header('location: ../index.php');
		exit();
	}
?>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
	<link href = "../css/bootstrap.css" rel = "stylesheet">
	<style>
	body{
		background:url(img/photography.webp) no-repeat;
		background-size: cover;
	}
	h1 {
		position: relative;
		text-align:right;
		z-index:9999;
	}
	h1::before {
		background-color: #f0ad4e;
		content: "";
		position: absolute;
		width: 190px;
		height: 30px;
		bottom: 0;
		z-index: -1;
		transform: rotate(-3deg);
	}
	@media (max-width: 829px) {
		h1::before{
			width: 170px;
		}
	}
	@media (max-width: 891px){
		.social-media{
			display: none;
		}
	}
	.social-media{
		margin-right:10px;
	}
	.unselect{
		user-select: none;
	}	
	</style>
</head>
<body class = 'bg-dark'>
	<div class="d-flex align-items-center vh-100" style = "z-index:-2;">
		<div class = 'p-4 h-auto w-50 min-w-custom bg-light mx-auto border-animation' style = 'min-width:400px;max-width:600px;'>
			<h1 class = 'text-center unselect'>MOMENTO</h1>
			<form method = "post" action = "class-login.php">
				<!--EMAIL-->
				<div class="form-floating mb-3 mt-4">
					<input type="email" class="form-control border-bottom border-primary mx-auto w-100 w-md-50" id="email" placeholder="name@example.com" name = "email">
					<label for="email">Email address</label>
				</div>
				<!--PASSWORD-->
				<div class="form-floating mt-4">
					<input type="password" class="form-control border-bottom border-primary mx-auto w-100 w-md-50" id="password" placeholder="Password" autocomplete="off" name = "password">
					<label for="password">Password</label>
				</div>
				<!--REMEMBER ME-->
				<div class="form-check form-switch rounded-6 mt-4">
					<input class="form-check-input" name="remember" value="1" type="checkbox" id="remember" checked="">
					<label class="form-check-label unselect" for="remember">Remember me</label>
				</div>

			
			<!--SOCIALS-->
			<div class = "d-flex justify-content-between mt-4">
				<button type="submit" class="btn btn-primary rounded-0 w-50" name = "enter">LOGIN</button>
			</form>	
				<button type="button" class="btn btn-outline-secondary rounded-0" style="width: 48%;" onclick="location.href='social-media/authentication.php/?auth=google'"><img src = "img/google.svg" width = "20" height = "18" class = "social-media">GOOGLE</button>
			</div>
			<small>Don't have momento account?<a href = "register.php">create</a></small>
		</div>
	</div>
</body>
</html>
