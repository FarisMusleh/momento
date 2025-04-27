<?php 
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require('pdo.php');

if (isset($_SESSION['data'])) {
    $data = $_SESSION['data'];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Momento</title>

    <!-- BOOTSTRAP -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Include Bootstrap Icons if not already -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

    <!-- GOOGLE FONTS -->
    <link href="https://fonts.googleapis.com/css2?family=Dancing+Script&family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Pacifico&family=Roboto:wght@300;500;700&family=Dancing+Script&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet">
	
    <!-- CUSTOM CSS -->
    <link rel="stylesheet" href="css/header.css">
	<link rel="stylesheet" href="css/navbar-scrolled.css">
    <link rel="stylesheet" href="css/photographers.css">
</head>
<body class = "fade-in">

<?php require("header.php"); ?>  

<!-- Hero Section -->
<div class="hero-section">
    <div class="container">
        <h1 class="display-10 slide-in">Discover the world's top photographers</h1>
        <p class="text-xl scale-up">Explore work from the most talented and accomplished photographers ready to take on your next project.</p>
        <?php require('search-bar.php'); ?>   
    </div>
</div>

<!-- Category Buttons -->
<div class="container-fluid bg-light py-5">
    <div class="d-flex  text-center">
        <div class="mx-auto d-flex">
            <button class="btn btn-dark fw-bold">Wars</button>
            <button class="btn btn-dark fw-bold">Graduation</button>
            <button class="btn btn-dark fw-bold">Wedding</button>
            <button class="btn btn-dark fw-bold">Nature</button>
            <button class="btn btn-dark fw-bold">Tourism</button>
            <button class="btn btn-dark fw-bold">Architecture</button>
        </div>
    </div>

    <!-- Photographer Cards -->
    <div class="row">
        <?php
        $stmt = $pdo->prepare("
            SELECT bp.business_name, bp.bio, a.location, a.picture, bp.rate, bp.total_likes, bp.total_views
            FROM business_profiles bp
            JOIN accounts a ON bp.id = a.id
            ORDER BY bp.rate DESC, bp.total_likes DESC, bp.total_views DESC
            LIMIT 20
        ");

        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($result as $row) {
            echo '<div class="col-md-3 mt-3 animate">
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
                            <h5 class="card-title">'.$row["business_name"].'</h5>
                            <p class="card-text">'.$row["bio"].'</p>
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
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
 integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
<!-- JavaScript Animation -->
<script>
    const animatedElements = document.querySelectorAll('.animate');

    animatedElements.forEach((el, index) => {
        el.style.setProperty('--delay', `${index * 0.1}s`);
    });

    const observer = new IntersectionObserver(entries => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('visible');
                observer.unobserve(entry.target);
            }
        });
    }, {
        threshold: 0.1
    });

    animatedElements.forEach(el => observer.observe(el));
</script>
<script>
window.onscroll = function() {
  var navbar = document.getElementById("navbar");
  if (window.scrollY > 0) {
    navbar.classList.add("scrolled");
  } else {
    navbar.classList.remove("scrolled");
  }
};
</script>
</body>
</html>
