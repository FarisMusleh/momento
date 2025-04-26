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
	<link rel="stylesheet" href="css/navbar-scrolled.css">
<!-- Add Bootstrap Icons CDN in your <head> if not already included -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

<!-- Include Bootstrap Icons if not already -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
</head>

<body class="fade-in" style = "position:relative;">
 <?php require("header.php"); ?>  
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
			$location = "/momento/searching_photos.php?type=photos&query={$tag}";
            echo "<a class='btn btn-dark m-1' href = {$location}>{$tag}</a>";
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
    SELECT a.id, a.username, a.picture, a.location, b.business_name,b.bio, b.created_at,b.rate
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
    <h2 class="section-title text-center display-5 p-5" style="font-family:Dancing script;">PREMIUM PHOTOGRAPHERS</h2>

    <?php if ($photographers): ?>
      <div id="photographerCarousel" class="carousel slide" data-bs-ride="carousel" data-bs-interval="6000">
        <div class="carousel-inner">
          <?php
            $chunks = array_chunk($photographers, 3);
            foreach ($chunks as $slideIndex => $slidePhotographers):
          ?>
            <div class="carousel-item <?= $slideIndex === 0 ? 'active' : '' ?>">
              <div class="row justify-content-center">
                <?php foreach ($slidePhotographers as $p): ?>
                  <?php
                    $rating = isset($p['rate']) ? floatval($p['rate']) : 0;
                    $fullStars = floor($rating);
                    $halfStar = ($rating - $fullStars) >= 0.5;
                    $emptyStars = 5 - $fullStars - ($halfStar ? 1 : 0);
                  ?>
                  <div class="col-md-4 d-flex align-items-stretch">
                    <div class="card photographer-card text-center w-100 p-3">
                      <div class="card-body pt-5 px-4">
                        <div class="photographer-img-container">
                          <img src="<?= htmlspecialchars($p['picture']) ?>" alt="<?= htmlspecialchars($p['business_name']) ?>" class="photographer-img mx-auto">
                        </div>
                        <h5 class="photographer-name"><?= htmlspecialchars($p['business_name']) ?></h5>

                        <div class="rating-stars">
                          <?php for ($i = 0; $i < $fullStars; $i++): ?>
                            <span class="star filled"></span>
                          <?php endfor; ?>
                          <?php if ($halfStar): ?>
                            <span class="star half"></span>
                          <?php endif; ?>
                          <?php for ($i = 0; $i < $emptyStars; $i++): ?>
                            <span class="star"></span>
                          <?php endfor; ?>
                        </div>

                        
                        <a href="profile.php?username=<?= $p['username'] ?>" class="btn btn-view-profile">View Portfolio</a>
                      </div>
                    </div>
                  </div>
                <?php endforeach; ?>
              </div>
            </div>
          <?php endforeach; ?>
        </div>

        <!-- Controls -->
        <button class="carousel-control-prev" type="button" data-bs-target="#photographerCarousel" data-bs-slide="prev">
          <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#photographerCarousel" data-bs-slide="next">
          <span class="carousel-control-next-icon" aria-hidden="true"></span>
        </button>
      </div>
    <?php else: ?>
      <p class="text-center text-muted fs-5 py-5">Currently curating our premium photographers collection</p>
    <?php endif; ?>
  </div>
</section>
<?php require('footer.php')?>
<?php require('chat_admin.php'); ?>
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