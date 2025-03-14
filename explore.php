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
    <title>Momento</title>
    <link href="Pacifico/Pacifico-Regular">
    <style>
	.hero-section {
		padding: 100px 0;
		text-align: center;
		background-image: url('./img/test.jpg');
		background-size: cover;
		background-repeat: no-repeat;
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
	
	.photographer-card {
      max-width: 400px;
      margin: 0 auto;
      border: none;
      border-radius: 15px;
      overflow: hidden;
      box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
      transition: transform 0.3s ease, box-shadow 0.3s ease;
      background: #fff;
    }
    .photographer-card:hover {
      transform: translateY(-10px);
      box-shadow: 0 8px 25px rgba(0, 0, 0, 0.2);
    }
    .photographer-card img {
      width: 100%;
      height: 250px;
      object-fit: cover;
    }
    .photographer-card .card-body {
      padding: 2rem;
      text-align: center;
    }
    .photographer-card .card-title {
      font-size: 1.75rem;
      font-weight: 700;
      margin-bottom: 0.75rem;
      color: #333;
    }
    .photographer-card .card-text {
      font-size: 1rem;
      color: #666;
      margin-bottom: 1.5rem;
    }
    .photographer-card .btn {
      font-size: 0.9rem;
      font-weight: 500;
      padding: 0.75rem 1.5rem;
      border-radius: 25px;
      transition: all 0.3s ease;
    }
    .photographer-card .btn-primary {
      background-color:rgb(46, 45, 45);
      border: none;
    }
    .photographer-card .btn-primary:hover {
      background-color:rgb(62, 62, 63);
    }
    .photographer-card .btn-outline-primary {
      border-color: #007bff;
      color: #007bff;
    }
    .photographer-card .btn-outline-primary:hover {
      background-color: #007bff;
      color: #fff;
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
<!-- ******************** -->
  
<!-- ******************** -->   
      <div class="container-fluid bg-light py-5">
     
  <div class="d-flex gap-4 text-center ">
  <div class="mx-auto d-flex gap-4 fw-bold ">
<button class="btn btn-outline-dark fw-bold">Discover</button>
<button class="btn btn-outline-dark fw-bold">Wars</button>
<button class="btn btn-outline-dark fw-bold">Graduation</button>
<button class="btn btn-outline-dark fw-bold">Wedding</button>
<button class="btn btn-outline-dark fw-bold">Nature</button>
<button class="btn btn-outline-dark fw-bold">Tourism</button>
<button class="btn btn-outline-dark fw-bold">Architecture</button>
</div>

</div>

    <div class="row">
		<?php
			$stmt = $pdo->prepare('select `first-name`,`last-name`,picture,description from `business-profiles` order by rand() limit 20');
			$stmt->execute();
			$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
			foreach ($result as $row) {
			echo '<div class="col-md-3 mt-3">
				  <div class="card photographer-card position-relative bg-light bg-opacity-50 border-0 rounded-4 shadow-lg"
					   style="backdrop-filter: blur(10px);">
					<div class="position-relative">
					  <img src="img/test.jpg" alt="Photographer Profile" class="card-img-top rounded-4" style="opacity:0.7">
					  <div class="position-absolute top-50 start-50 translate-middle">
						<img src="'.$row["picture"].'" alt="Profile Picture" 
							 class="rounded-circle border border-white shadow-lg" 
							 style="width: 100px; height: 100px; object-fit: cover;">
					  </div>
					</div>
					<div class="card-body text-center">
					  <h5 class="card-title">'.$row["first-name"].$row["last-name"].'</h5>
					  <p class="card-text">
						'.$row["description"].'
					  </p>
					  <div class="d-grid gap-2 d-md-block">
						<a href="#" class="btn btn-primary">Portfolio</a>
						<a href="#" class="btn btn-outline-primary">Contact</a>
					  </div>
					</div>
				  </div>
				</div>';
			}
			?>
    </div>


  <!-- Font Awesome for Icons -->
<script src="https://kit.fontawesome.com/a076d05399.js"></script>
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
</body>
</html>