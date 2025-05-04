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
<div class=" bg-light py-5">
    <div class="d-flex justify-content-center text-center">
		<div class="cat-dark-btns d-flex flex-wrap justify-content-center gap-2">
			<button class="btn btn-dark fw-bold">Wars</button>
			<button class="btn btn-dark fw-bold">Graduation</button>
			<button class="btn btn-dark fw-bold">Wedding</button>
			<button class="btn btn-dark fw-bold">Nature</button>
			<button class="btn btn-dark fw-bold">Tourism</button>
			<button class="btn btn-dark fw-bold">Architecture</button>
		</div>
	</div>


    <!-- Photographer Cards -->
    <div class="row gap-5 justify-center">
        <?php
        $stmt = $pdo->prepare("
            SELECT bp.business_name, bp.bio, a.username, a.location, a.picture, bp.total_rate, bp.total_likes, bp.total_reviews, bp.total_views
            FROM business_profiles bp
            JOIN accounts a ON bp.id = a.id
            ORDER BY bp.total_rate DESC, bp.total_likes DESC, bp.total_views DESC
            LIMIT 20
        ");

        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($result as $row) {
echo '
    <div class="photographer-card">
        <div class="card-header">
            <img src="'.$row['picture'].'" alt="Julia Smith" class="profile-image-cards">
            <div class="favorite-btn">
                <svg viewBox="0 0 24 24"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path></svg>
            </div>
        </div>
        <div class="card-content">
            <a href = "/momento/profile.php?username='.$row['username'].'"><h3 class="username">'.$row["business_name"].'</h3></a>
            <div class="location">
                <svg viewBox="0 0 24 24"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg>
                '.$row["location"].'
            </div>
            <div class="rate">$120 / hour</div>
            <div class="rating">
                <svg viewBox="0 0 24 24"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon></svg>
                '.$row['total_rate'].'&nbsp;('.$row['total_reviews'].' reviews)
            </div>
            <div class="divider"></div>
            <div class="tags">
                <span class="tag">Portrait</span>
                <span class="tag">Fashion</span>
                <span class="tag">Wedding</span>
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
    <script>
        // Simple toggle for favorite buttons
        document.querySelectorAll('.favorite-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                this.classList.toggle('active');
            });
        });
    </script>
</body>
</html>
