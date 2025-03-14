<?php 
	if (session_status() === PHP_SESSION_NONE) {
    session_start();
	}
	require('pdo.php');
	if(isset($_SESSION['data'])){
		$data = $_SESSION['data'];
	}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Moomento</title>
	<link href="Pacifico/Pacifico-Regular">
     <style>
        .hero-section {
            padding: 100px 0;
            text-align: center;
            background-image: url('./img/test.jpg');
            background-size: cover;
            background-repeat: no-repeat;
        }
        .trending-searches {
            padding: 50px 0;
           
            
        }
        .search-item {
            margin: 10px;
            padding: 10px 20px;
            background: #e9ecef;
            border-radius: 20px;
            display: inline-block;

        }
      /* Card hover animation */
        .card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            overflow: hidden;
        }
        .card:hover {
            transform: translateY(-10px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
        }

        /* Image zoom animation on hover */
        .card-img-top {
            transition: transform 0.3s ease;
        }
        .card:hover .card-img-top {
            transform: scale(1.1);
        }

        /* Overlay animation */
        .card-img-overlay {
            background: linear-gradient(to top, rgba(0, 0, 0, 0.7), rgba(0, 0, 0, 0));
            opacity: 0;
            transition: opacity 0.3s ease;
        }
        .card:hover .card-img-overlay {
            opacity: 1;
        }
         .search-container {
            position: relative;
            max-width: 600px;
            margin: 0 auto;
            
        }
        .search-input {
            width: 100%;
            padding: 12px 20px;
            border: 2px solid #ddd;
            border-radius: 30px;
            font-size: 16px;
            transition: all 0.3s ease;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
        .search-input:focus {
            border-color:rgb(64, 65, 66);
            box-shadow: 0 2px 10px rgba(0, 123, 255, 0.25);
            outline: none;
        }
        .search-button {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            background:rgb(31, 29, 29);
            color: white;
            border: none;
            padding:   10px 15px ;
            border-radius: 100%;
            cursor: pointer;
            transition: background 0.3s ease;
        }
        .search-button:hover {
            background:rgb(49, 49, 49);
        }
 
        /* Entrance animation for cards */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        .card {
            animation: fadeInUp 0.5s ease-out;
            animation-fill-mode: both;
        }
        .card:nth-child(1) { animation-delay: 0.1s; }
        .card:nth-child(2) { animation-delay: 0.2s; }
        .card:nth-child(3) { animation-delay: 0.3s; }
        .card:nth-child(4) { animation-delay: 0.4s; }
        .card:nth-child(5) { animation-delay: 0.5s; }
        .card:nth-child(6) { animation-delay: 0.6s; }

      .login{
        
          background:rgb(31, 29, 29);
          border-radius: 20px;
          cursor: pointer; 
          margin-top:5px;
          transition: background 0.3s ease; 
          width:100px
        
      }
    .login:hover{
        background:rgb(80, 78, 78);
      }
/* ***********************   */ 
 .footer {
            background-color: #f8f9fa;
            padding: 40px 0;
            font-family: Arial, sans-serif;
        }
        .footer a {
            color: #6c757d;
            text-decoration: none;
        }
        .footer a:hover {
            color:rgb(212, 214, 216);
        }
        .footer .footer-links {
            margin-bottom: 20px;
        }
        .footer .footer-links a {
            margin-right: 15px;
        }
        .footer .footer-bottom {
            margin-top: 20px;
            font-size: 0.9rem;
            color: #6c757d;
        }  

        /* Slide-in animation for the title */
        .slide-in {
            font-size: 3rem;
            font-weight: bold;
            color: #333;
            opacity: 0; /* Start invisible */
            transform: translateX(-100%); /* Start off-screen to the left */
            animation: slideIn 1.5s ease-out forwards;
        }

        /* Slide-in keyframes */
        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateX(-100%);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        /* Scale-up animation for the subtitle */
        .scale-up {
            font-size: 1.5rem;
            margin-top: 20px;
            opacity: 0; /* Start invisible */
            transform: scale(0.5); /* Start small */
            animation: scaleUp 1.5s ease-out 1s forwards; /* Delay the animation */
        }

        /* Scale-up keyframes */
        @keyframes scaleUp {
            from {
                opacity: 0;
                transform: scale(0.5);
            }
            to {
                opacity: 1;
                transform: scale(1);
            }
        }
  .loader {
       border: 6px solid #f3f3f3; /* Light grey */
  border-top: 6px solid #3498db; /* Blue */
  border-radius: 50%;
  width: 60px;
  height: 60px;
  animation: spin 1.5s linear infinite;
  position: relative;
    }
.loader::before {
  content: '';
  position: absolute;
  top: -6px;
  left: -6px;
  right: -6px;
  bottom: -6px;
  border-radius: 50%;
  border: 6px solid transparent;
  border-top: 6px solid #e74c3c; /* Red */
  animation: spin 2s linear infinite;
}
.loader::after {
  content: '';
  position: absolute;
  top: -12px;
  left: -12px;
  right: -12px;
  bottom: -12px;
  border-radius: 50%;
  border: 6px solid transparent;
  border-top: 6px solid #2ecc71; /* Green */
  animation: spin 3s linear infinite;
}
/* Spin Animation */
@keyframes spin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}
.loader-container {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: rgba(255, 255, 255, 0.9); 
  display: flex;
  justify-content: center;
  align-items: center;
  z-index: 1000; /* Ensure it's on top of other content */
}



		.search-dropdown {
            position: absolute;
			right:60px;
			top:3px;
			background-color: transparent;
			text-align:left;
        }

        .search-dropdown-btn {
            background: white;
            padding: 10px 18px;
            font-size: 16px;
            border: 0px solid #ddd;
            border-radius: 6px;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 8px;
            transition: background 0.3s;
        }
		
        .search-dropdown-btn:hover {
            background: rgb();
        }

        .arrow {
            width: 7px;
            height: 7px;
            border-left: 2px solid #333;
            border-bottom: 2px solid #333;
            transform: rotate(-45deg);
            transition: transform 0.3s ease;
        }

        .search-dropdown.active .arrow {
            transform: rotate(135deg);
        }

        .search-dropdown-content {
            position: absolute;
            top: 110%;
            left: 0;
            background: white;
            min-width: 160px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
            border-radius: 6px;
            overflow: hidden;
            z-index: 10;
            opacity: 0;
            visibility: hidden;
            transform: translateY(-10px);
            transition: opacity 0.3s ease, transform 0.3s ease, visibility 0.3s;
        }

        .search-dropdown-content a {
            display: block;
            padding: 12px 16px;
            text-decoration: none;
            color: #333;
            transition: background 0.3s;
        }

        .search-dropdown-content a:hover {
            background: #f5f5f5;
        }

        .search-dropdown.active .search-dropdown-content {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }


 </style>
</head>
<body>
  <!--  <div class="loader-container">
  <div class="loader"></div>
</div> -->
    <!-- Navigation Bar -->
	<?php require("header.php"); ?>    
    <!-- Hero Section -->
    <div class="hero-section">
        <div class="container">
            <h1 class="display-10 slide-in"style=" font-family: Dancing Script, cursive;">Discover the world's top photographers</h1>
            <p class="text-xl scale-up ">Explore work from the most talented and accomplished photographers ready to take on your next project.</p>
            <div class="container mt-5">
        <div class="search-container">
            <input class="search-input" type="search" placeholder="What are you looking for?" aria-label="Search">
            			<div class="search-dropdown">
					<button class="search-dropdown-btn">
						Shots
						<span class="arrow"></span>
					</button>
					<div class="search-dropdown-content">
						<a href="#">Shots</a>
						<a href="#">Photographers</a>
					</div>
				</div>
            <button class="search-button" type="submit">
				<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-search" viewBox="0 0 16 16">
				<path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001q.044.06.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1 1 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0"/>
				</svg>
			</button>
        </div>
    </div>
        </div>
    </div>

    <!-- Trending Searches Section -->
    <div class="container-fluid trending-searches mt-10 p-5">
        <h2 class="text-center mb-4"style="font-family: Dancing Script, cursive;" >Trending searches</h2>
        <div class="text-center">
            <button class="btn btn-dark" style="font-family: Dancing Script, cursive; width:120px"> Wars</button>
            <button class="btn btn-dark border" style="font-family: Dancing Script, cursive; width:120px">Architecture</button>
            <button class="btn btn-dark border" style="font-family: Dancing Script, cursive; width:120px">Nature</button>
            <button class="btn btn-dark border" style="font-family: Dancing Script, cursive; width:120px">Wedding</button>
            <button class="btn btn-dark border" style="font-family: Dancing Script, cursive; width:120px">Graduation</button>
            <button class="btn btn-dark border" style="font-family: Dancing Script, cursive; width:120px">Cars</button>
            <button class="btn btn-dark border" style="font-family: Dancing Script, cursive; width:120px">Art</button>
        </div>
    </div>

<div class="container-md mt-1 shadow-lg p-5">
    <div class="d-flex flex-row gap-4"> <!-- Flex container with horizontal layout and gap -->
        <!-- Card 1 -->
        <div style="width: 500px">
            <div class="card shadow-sm h-100">
                <div class="card-img-container position-relative">
                    <img class="card-img-top img-fluid rounded-top" src="img/war.jpg" alt="Wars" style="height: 300px; object-fit: cover;">
                    <div class="card-img-overlay d-flex align-items-end">
                        <h5 class="card-title text-white fs-5 fw-bold">Wars</h5>
                    </div>
                </div>
            </div>
        </div>

        <!-- Card 2 -->
        <div style="width: 450px">
            <div class="card shadow-sm h-100">
                <div class="card-img-container position-relative">
                    <img class="card-img-top img-fluid rounded-top" src="img/Arch.jpg" alt="Architecture" style="height: 300px; object-fit: cover;">
                    <div class="card-img-overlay d-flex align-items-end">
                        <h5 class="card-title text-white fs-5 fw-bold">Architecture</h5>
                    </div>
                </div>
            </div>
        </div>

        <!-- Card 3 -->
        <div style="width: 450px">
            <div class="card shadow-sm h-100">
                <div class="card-img-container position-relative">
                    <img class="card-img-top img-fluid rounded-top" src="img/wedding5.jpg" alt="Wedding" style="height: 300px; object-fit: cover;">
                    <div class="card-img-overlay d-flex align-items-end">
                        <h5 class="card-title text-white fs-5 fw-bold">Wedding</h5>
                    </div>
                </div>
            </div>
        </div>

        <!-- Card 4 -->
        <div style="width: 450px">
            <div class="card shadow-sm h-100">
                <div class="card-img-container position-relative">
                    <img class="card-img-top img-fluid rounded-top" src="img/BMW.png" alt="Cars" style="height: 300px; object-fit: cover;">
                    <div class="card-img-overlay d-flex align-items-end">
                        <h5 class="card-title text-white fs-5 fw-bold">Cars</h5>
                    </div>
                </div>
            </div>
        </div>

        <!-- Card 5 -->
        <div style="width:450px">
            <div class="card shadow-sm h-100">
                <div class="card-img-container position-relative">
                    <img class="card-img-top img-fluid rounded-top" src="img/Nature.webp" alt="Nature" style="height: 300px; object-fit: cover;">
                    <div class="card-img-overlay d-flex align-items-end">
                        <h5 class="card-title text-white fs-5 fw-bold">Nature</h5>
                    </div>
                </div>
            </div>
        </div>

        <!-- Card 6 -->
        <div style="width: 450px">
            <div class="card shadow-sm h-100">
                <div class="card-img-container position-relative">
                    <img class="card-img-top img-fluid rounded-top" src="img/Graduate.jpg" alt="Graduation" style="height: 300px; object-fit: cover;">
                    <div class="card-img-overlay d-flex align-items-end">
                        <h5 class="card-title text-white fs-5 fw-bold">Graduation</h5>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!--  ///////////////// -->
<div class="container-md mt-5 p-5 mb-5 shadow-lg" style="background-image: url('./img/Bg.jpg'); border: 1px solid black; outline: 6px solid gray">
        <div class="row g-4"> <!-- Add gap between cards -->
            <!-- Card 1 -->
            <div class="col-md-6">
                <div class="card shadow-sm position-relative overflow-hidden ">
                    <img src="img/PPP.jpg" class="card-img-top" alt="Nature" height="350px">
                    <div class="card-body bg-dark">
                        <h5 class="card-title text-center text-light">Photographers</h5>
                        <!-- Centered Button -->
                    <div class="text-center pb-5">
                        <a href="#" class="btn btn-light">Explore More..</a>
                    </div>
                    </div>
                  
                </div>
            </div>

            <!-- Card 2 -->
            <div class="col-md-6">
                <div class="card shadow-sm position-relative overflow-hidden">
                    <img src="img/Photos.jpg" class="card-img-top" alt="City" height="350px">
                    <div class="card-body bg-dark">
                        <h5 class="card-title text-center text-light">Photos</h5>
                        <!-- Centered Button -->
                        <div class="text-center pb-5">
                        <a href="#" class="btn btn-light">Explore More..</a>
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>
    </div>
<!-- ********************************************************** -->
<footer class="footer shadow-lg bg-dark">
        <div class="container-fluid">
          <div class="fw-bold display-6 text-white text-center text-underlined"> MOOMENTO</div>
            <div class="row">
                <div class="col-md-6 text-center">
                    <h5 class="fw-bold text-white">For Photographers</h5>
                    <div class="footer-links fw-bold">
                        <a href="#">Inspiration</a>
                        <a href="#">Advertising</a>
                        <a href="#">Blog</a>
                        <a href="#">About</a>
                        <a href="support.html">Support</a>
                    </div>
                </div>
                <div class="col-md-6 text-center">
                    <h5 class="fw-bold text-white">Resources</h5>
                    <div class="footer-links fw-bold">
                        <a href="#">Jobs</a>
                        <a href="#">Photographers</a>
                        <a href="#">Freelancers</a>
                        <a href="#">Tags</a>
                        <a href="#">Places</a>
                    </div>
                </div>
            </div>
            <div class="footer-bottom text-center">
                <p>© 2025 Moomento. Terms | Privacy | Cookies</p>
            </div>
        </div>
    </footer>

<script>
	document.addEventListener("DOMContentLoaded", function () {
		const dropdown = document.querySelector(".search-dropdown");
		const btn = document.querySelector(".search-dropdown-btn");

		btn.addEventListener("click", function (event) {
			event.stopPropagation();
			dropdown.classList.toggle("active");
		});

		document.addEventListener("click", function (event) {
			if (!dropdown.contains(event.target)) {
				dropdown.classList.remove("active");
			}
		});
	});
</script>