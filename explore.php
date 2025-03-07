<?php 
	session_start(); 
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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">    <link href="Pacifico/Pacifico-Regular">
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
            padding:   10px 20px ;
            border-radius: 30px;
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
                  <button class="search-button" type="submit">Search</button>
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
<button class="btn btn-outline-dark fw-bold">Architicture</button>
</div>

</div>

    <div class="row">
<!--*************** new card ********************-->
     <div class="col-md-3 mt-3">
  <!-- Photographer Card -->
  <div class="card photographer-card position-relative bg-light bg-opacity-50 border-0 rounded-4 shadow-lg"
       style="backdrop-filter: blur(10px);">
    
    <!-- Image Container -->
    <div class="position-relative">
      <!-- Background Image -->
      <img src="img/test.jpg" alt="Photographer Profile" class="card-img-top rounded-4" style="opacity:0.7">
      
      <!-- Centered Profile Image -->
      <div class="position-absolute top-50 start-50 translate-middle">
        <img src="img/test.jpg" alt="Profile Picture" 
             class="rounded-circle border border-white shadow-lg" 
             style="width: 100px; height: 100px; object-fit: cover;">
      </div>
    </div>

    <!-- Card Body -->
    <div class="card-body text-center">
      <h5 class="card-title">John Doe</h5>
      <p class="card-text">
        Professional photographer specializing in portrait, wedding, and landscape photography. 
        Based in New York City.
      </p>
      <!-- Buttons -->
      <div class="d-grid gap-2 d-md-block">
        <a href="#" class="btn btn-primary">Portfolio</a>
        <a href="#" class="btn btn-outline-primary">Contact</a>
      </div>
    </div>
  </div>
</div>
     <div class="col-md-3 mt-3">
  <!-- Photographer Card -->
  <div class="card photographer-card position-relative bg-light bg-opacity-50 border-0 rounded-4 shadow-lg"
       style="backdrop-filter: blur(10px);">
    
    <!-- Image Container -->
    <div class="position-relative">
      <!-- Background Image -->
      <img src="img/war.jpg" alt="Photographer Profile" class="card-img-top rounded-4" style="opacity:0.7">
      
      <!-- Centered Profile Image -->
      <div class="position-absolute top-50 start-50 translate-middle">
        <img src="img/test.jpg" alt="Profile Picture" 
             class="rounded-circle border border-white shadow-lg" 
             style="width: 100px; height: 100px; object-fit: cover;">
      </div>
    </div>

    <!-- Card Body -->
    <div class="card-body text-center">
      <h5 class="card-title">John Doe</h5>
      <p class="card-text">
        Professional photographer specializing in portrait, wedding, and landscape photography. 
        Based in New York City.
      </p>
      <!-- Buttons -->
      <div class="d-grid gap-2 d-md-block">
        <a href="#" class="btn btn-primary">Portfolio</a>
        <a href="#" class="btn btn-outline-primary">Contact</a>
      </div>
    </div>
  </div>
</div>
   


  <!-- Font Awesome for Icons -->
<script src="https://kit.fontawesome.com/a076d05399.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>