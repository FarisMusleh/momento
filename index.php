<?php
	header("Cache-Control: public, max-age=2592000");
	header("Expires: " . gmdate("D, d M Y H:i:s", time() + 2592000) . " GMT");
	require 'jwt/vendor/autoload.php';
	session_start();
	$publicKey = file_get_contents('keys/public_key.pem');
	use Firebase\JWT\JWT;
	use Firebase\JWT\Key;
	if(isset($_COOKIE['token'])){
		$decoded = JWT::decode($_COOKIE['token'], new Key($publicKey, 'RS256'));
		echo "<div class = 'navbar'>
				<div class = 'nav-profile'>
					<img src = '".$decoded->data->picture."' width = 50 height = 50 id = 'toggle'/>
					<span>".$decoded->data->name."</span>
					<div id = 'nav-list'>
						<p>Information</p>
						<div>".$decoded->data->email."</div>
						<div>User Account</div>
						<p>More Options</p>
						<div>Add Account</div>
						<div>Settings</div>
						<div>Profile</div>
						
					</div>
				</div>
				<a href = 'logout.php'>LOGOUT</a>
			</div>";
	}
	else{
		header('location:login.php');
	}
?>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home Page</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
	<link rel="stylesheet" href="css/home.css">
</head>
<body>
    <div class="container">
        <!-- First Box -->
        <div class="box1">
            <video autoplay muted loop>
                <source src="img\background.mp4" type="video/mp4">
                Your browser does not support the video tag.
            </video>
            <div class="overlay">
				<img src="img\mobile-logo.webp" alt="Description of image">
				<p>WELCOME TO MOMENTO<br>This is a piece of art</p>
			</div>
        </div>

        <!-- Second Box -->
        <div class="box2">
            <h1>MOMENTO</h1>
            <p>Fashionista • Traveller • Lifestyle Blogger • Nature</p>
            <div class="box2-child">
				<div class="image-container">
					<img src="img\p1.jpeg" class="responsive-image">
					<button class="image-button">Art</button>
				</div>
				<div class="image-container">
					<img src="img\p2.jpg" class="responsive-image">
					<button class="image-button">Tower</button>
				</div>
				<div class="image-container">
					<img src="img\p3.jpg" class="responsive-image">
					<button class="image-button">Architecture</button>
				</div>
				<div class="image-container">
					<img src="img\p4.jpeg" class="responsive-image">
					<button class="image-button">Nature</button>
				</div>
				<div class="image-container">
					<img src="img\p5.png" class="responsive-image">
					<button class="image-button">Nature</button>
				</div>
            </div>
        </div>
        <!-- Third Box -->
        <div class="box3">
            Div 3
        </div>
    </div>
	<!-- Footer -->
	<div class="footer">
		<div class="about">
			<h2>About Us</h2>
			<p>We are a lifestyle brand dedicated to fashion and travel. Join us on our journey!</p>
		</div>
		<div class="vertical-line"></div>
		<div class="social-media">
			<h2>Our Social Media</h2>
			<div>
				<a href="https://www.facebook.com">
					<i class="fab fa-facebook-f"></i>
					<span>Facebook</span>
				</a>
			</div>
			<div>
				<a href="https://www.instagram.com">
					<i class="fab fa-instagram"></i>
					<span>Instagram</span>
				</a>
			</div>
			<div>
				<a href="https://www.linkedin.com">
					<i class="fab fa-linkedin-in"></i>
					<span>LinkedIn</span>
				</a>
			</div>
			<div>
				<a href="https://www.whatsapp.com">
					<i class="fab fa-whatsapp"></i>
					<span>WhatsApp</span>
				</a>
			</div>
		</div>
	</div>
</body>
</html>
<script>
	document.getElementById("toggle").addEventListener("click", function() {
		var div = document.getElementById("nav-list");
		if (div.style.display === "none") {
			div.style.display = "flex"; 
		} else {
			div.style.display = "none"; 
		}
	});
</script>
