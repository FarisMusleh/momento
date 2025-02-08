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
		background:url(img/road.jpg) no-repeat;
		background-size: cover;
		backdrop-filter: blur(3px);
	}
	h1 {
		position: relative;
		text-align:right;
	}
	h1::before {
		background-color: #f0ad4e;
		content: "";
		position: absolute;
		width: 250px;
		height: 30px;
		right: -2px;
		bottom: 0;
		z-index: -1;
		transform: rotate(-3deg);
	}
	@media (max-width: 829px) {
		h1 {
			display: none;
		}
	}
	.unselect{
		user-select: none;
	}
	</style>
	<link href="../css/toaster.css" rel="stylesheet"/>
	<script src="../js/toaster.js"/>
</head>
<body class = 'bg-dark'>
<div class="modal">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Modal title</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
          <span aria-hidden="true"></span>
        </button>
      </div>
      <div class="modal-body">
        <p>Modal body text goes here.</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary">Save changes</button>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
<script>toastr.warning('My name is Inigo Montoya. You killed my father, prepare to die!');</script>
	<div class="d-flex align-items-center vh-100 vw-100 flex-row justify-content-around">
		<div class = 'p-5 py-4 h-auto min-w-custom bg-light' style = 'width:500px;min-width:400px;'>
			<h2 class = 'text-center unselect'>Create a user account</h2>
			<form method = "post" action = "class-register.php">
				<!--ACCOUNT TYPE-->
				<div class = "d-flex justify-content-around my-3">
					<input type="radio" class="btn-check" name="account" id="success-outlined" autocomplete="off" value = "user" checked>
					<label class="btn btn-outline-success rounded-0" for="success-outlined">User account</label>
					<input type="radio" class="btn-check" name="account" id="danger-outlined" autocomplete="off" value = "business">
					<label class="btn btn-outline-danger rounded-0" for="danger-outlined">Business account</label>
				</div>
				<!--USERNAME-->
				<div class="form-floating mb-3">
					<input type="text" class="form-control" id="username" placeholder="Username" name = "username" autocomplete="username" required>
					<label for="username">Username</label>
					<div class="" id = "username-status"></div>
				</div>
				<!--EMAIL-->
				<div class="form-floating mb-3">
					<input type="email" class="form-control" id="email" placeholder="name@example.com" name = "email" autocomplete="email" required>
					<label for="email">Email address</label>
				</div>
				<!--PASSWORD-->
				<div class="form-floating">
					<input type="password" class="form-control" id="password" placeholder="Password" autocomplete="off" name = "password" required>
					<label for="password" class = "form-label">Password</label>
				</div>
				<!--GENDER-->
				<div class = "d-flex gap-3 mt-3 unselect">
					<div class="form-check">
						<input class="form-check-input" type="radio" name="gender" id="male" value="male" checked="" value = "male">
						<label class="form-check-label" for="male">Male</label>
					</div>
					<div class="form-check">
						<input class="form-check-input" type="radio" name="gender" id="female" value="female">
						<label class="form-check-label" for="female">Female</label>
					</div>
				</div>
				<!--REGISTER-->
				<div class = "d-flex align-items-center justify-content-center">
					<button type="submit" class="btn btn-primary rounded-0 mt-4 w-100" name = "enter">REGISTER</button>
				</div>		
			</form>
			<small>Already have momento account?<a href = "login.php">login</a></small>
		</div>
		<h1 class = "unselect">JOIN OUR<br> COMMUNITY</h1>
	</div>
</body>
</html>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
	$(document).ready(function(){
		$('#username').on('input', function(){
			let temp = $(this).val();
			if(temp.length > 0){
				setTimeout(function() {
					$.ajax({
						url: 'check-username.php',
						type: 'post',
						data: {key:temp},
						success: function(response){
							if(response==true){
								if ($('#username').hasClass('is-invalid')) {
									$('#username').removeClass('is-invalid');								
								}
								$("#username").addClass("is-valid");
								$('#username-status').text("Username is available.");									
							}else{
								if ($('#username').hasClass('is-valid')) {
									$('#username').removeClass('is-valid');
								}
								$("#username").addClass("is-invalid");
								$('#username-status').text("A user with that username already exist.");								
							}
						}
					});
				});
			}else{
				$('#username-status').text('');
				if($("#username").hasClass("is-valid")){
					$("#username").removeClass("is-valid");
				}else if($("#username").hasClass("is-invalid")){
					$("#username").removeClass("is-invalid");
				}
			}
		});
	});
</script>
