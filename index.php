<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require 'pdo.php';
$data = $_SESSION['data'] ?? null;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Momento</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- GOOGLE-FONTS -->
	<link href="https://fonts.googleapis.com/css2?family=Dancing+Script&family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css2?family=Pacifico&family=Roboto:wght@300;500;700&family=Dancing+Script&display=swap" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet">
	<!--CSS-->
    <link rel="stylesheet" href="css/header.css">
    <link rel="stylesheet" href="css/home.css">
	<!--STYLE-FOR-NAVBAR-TRANSPARENT-ONLY-ON-THIS-PAGE-->
	<style>
		.nav-sticky {
			position: fixed;
			background-color: transparent !important;
			transition: background-color 0.3s ease !important;
		}
		.navbar.scrolled {
		    background-color: white !important;
		}
	</style>
</head>

<body class="fade-in" style = "position:relative;">
<?php require 'header.php'; ?>
<div class="hero-section">
    <div class="container">
        <h1 class="display-10">Discover the world's top photographers</h1>
        <p class="text-xl scale-up">Capture, Share & Discover Timeless Moments</p>
        <?php require 'search-bar.php'; ?>
    </div>
</div>

<div class="container-fluid trending-searches mt-10 p-5">
    <h2 class="text-center mb-4" style="font-family: Pacifico;">Popular Tags</h2>
    <div class="text-center">
        <?php
        $tags = ['Wars', 'Architecture', 'Nature', 'Wedding', 'Graduation', 'Cars', 'Art'];
        foreach ($tags as $tag) {
            echo "<button class='btn btn-dark m-1'>{$tag}</button>";
        }
        ?>
    </div>
</div>

<div class="container-md mt-1 shadow-lg p-5">
    <div class="d-flex flex-row gap-4">
        <?php
        $categories = [
            ['img' => 'img/war.jpg', 'title' => 'Wars'],
            ['img' => 'img/Arch.jpg', 'title' => 'Architecture'],
            ['img' => 'img/wedding5.jpg', 'title' => 'Wedding'],
            ['img' => 'img/BMW.png', 'title' => 'Cars'],
            ['img' => 'img/Nature.webp', 'title' => 'Nature'],
            ['img' => 'img/Graduate.jpg', 'title' => 'Graduation'],
        ];

        foreach ($categories as $category) {
        ?>
            <div style="width: 450px">
                <div class="card shadow-sm h-100">
                    <div class="card-img-container position-relative">
                        <img class="card-img-top img-fluid rounded-top" src="<?= $category['img'] ?>" alt="<?= $category['title'] ?>" style="height: 300px; object-fit: cover;">
                        <div class="card-img-overlay d-flex align-items-end">
                            <h5 class="card-title text-white fs-5 fw-bold"><?= $category['title'] ?></h5>
                        </div>
                    </div>
                </div>
            </div>
        <?php } ?>
    </div>
</div>
<div class="container-md mt-5 p-5 mb-5 shadow-lg" style="background-image: url('./img/Bg.jpg'); background-size: cover; background-position: center; border-radius: 15px; border: 1px solid #333; outline: 6px solid rgba(0,0,0,0.1);">
    <div class="row g-4">
        <div class="col-md-6">
            <div class="card shadow-lg position-relative overflow-hidden rounded-4 transition-all duration-300 hover:shadow-xl">
                <img src="img/pg.jpg" class="card-img-top" alt="Photographers" height="300px" style="object-fit: cover;">
                <div class="card-body bg-dark bg-opacity-50 p-4">
                    <h5 class="card-title text-center text-light mb-3 fs-4">Photographers</h5>
                    <div class="text-center">
                        <a href="photographers.php" class="btn btn-outline-light px-5 py-3 text-uppercase fw-bold transition-all duration-200 hover:bg-white hover-text-dark">Explore More</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card shadow-lg position-relative overflow-hidden rounded-4 transition-all duration-300 hover:shadow-xl">
                <img src="img/Photos.jpg" class="card-img-top" alt="Photos" height="300px" style="object-fit: cover;">
                <div class="card-body bg-dark bg-opacity-50 p-4">
                    <h5 class="card-title text-center text-light mb-3 fs-4">Photos</h5>
                    <div class="text-center">
                        <a href="gallery.php" class="btn btn-outline-light px-5 py-3 text-uppercase fw-bold transition-all duration-200 hover:bg-white hover-text-dark">Explore More</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
$stmt = $pdo->prepare("
    SELECT a.id, a.username, a.picture, a.location, b.business_name,b.bio, b.created_at
    FROM accounts a
    INNER JOIN business_profiles b ON a.id = b.id
    WHERE a.account_type = 'business'
    ORDER BY b.created_at DESC
    LIMIT 6
");
$stmt->execute();
$photographers = $stmt->fetchAll();
?>

<section class="photographers-section">
  <div class="container">
    <h2>Recommended New Photographers</h2>
    <div class="row">
      <?php if ($photographers): ?>
        <?php foreach ($photographers as $p): ?>
          <div class="col-md-4 mb-4">
            <div class="card photographer-card">
              <img src="<?= htmlspecialchars($p['picture']) ?>" height = 70 weight = 70 alt="Photographer Image">
              <div class="card-body">
                <h5 class="card-title"><?= htmlspecialchars($p['business_name']) ?></h5>
                <p class="card-text"><?= htmlspecialchars($p['bio']) ?></p>
                <a href="profile.php?id=<?= $p['id'] ?>" class="btn btn-outline-dark">View Profile</a>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      <?php else: ?>
        <p class="text-center w-100">No new photographers found.</p>
      <?php endif; ?>
    </div>
  </div>
</section>



<footer class="footer shadow-lg bg-dark text-white">
    <div class="container-fluid">
        <div class="fw-bold display-6 text-center">MOMENTO</div>
        <div class="row">
            <div class="col-md-6 text-center">
                <h5 class="fw-bold">For Photographers</h5>
                <div class="footer-links fw-bold">
                    <a href="#">Inspiration</a>
                    <a href="#">Advertising</a>
                    <a href="#">Blog</a>
                    <a href="#">About</a>
                    <a href="support.html">Support</a>
                </div>
            </div>
            <div class="col-md-6 text-center">
                <h5 class="fw-bold">Resources</h5>
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
<?php require('chat_ai.php'); ?>
</body>
</html>
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
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
 integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>