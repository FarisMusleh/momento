<?php
require('pdo.php');

$category = $_GET['category'];

if (!$category) {
    echo "Category not specified.";
    exit;
}



?>

<!DOCTYPE html>
<html>
<head>
    <title><?= htmlspecialchars(ucfirst($category)) ?> Photographers</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
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
        <?php require('include/search-bar.php'); ?>   
    </div>
</div>

<!-- Category Buttons -->
<div class=" bg-light py-5">
    <div class="d-flex justify-content-center text-center">
		<div class="cat-dark-btns d-flex flex-wrap justify-content-center gap-2">
			<button class="btn btn-dark fw-bold" onclick="location.href='photographer_by_category.php?category=wars';">Wars</button>
			<button class="btn btn-dark fw-bold" onclick="location.href='photographer_by_category.php?category=graduation';">Graduation</button>
			<button class="btn btn-dark fw-bold" onclick="location.href='photographer_by_category.php?category=wedding';">Wedding</button>
			<button class="btn btn-dark fw-bold" onclick="location.href='photographer_by_category.php?category=nature';">Nature</button>
			<button class="btn btn-dark fw-bold" onclick="location.href='photographer_by_category.php?category=tourism';">Tourism</button>
			<button class="btn btn-dark fw-bold" onclick="location.href='photographer_by_category.php?category=architecture';">Architecture</button>
		</div>
	</div>


    <!-- Photographer Cards -->
<div class="row justify-center">
    <?php
  
    $stmt = $pdo->prepare("
        SELECT 
            bp.business_name, 
            bp.bio, 
            a.username, 
            a.location, 
            a.picture, 
            bp.total_rate, 
            bp.total_likes, 
            bp.total_reviews,
            COALESCE(pvl_count.views, 0) AS total_views,
            pcs.score AS category_score
        FROM photographer_category_scores pcs
        JOIN business_profiles bp ON pcs.photographer_id = bp.id
        JOIN accounts a ON bp.id = a.id
        LEFT JOIN (
            SELECT photographer_id, COUNT(*) AS views
            FROM photographer_view_logs
            GROUP BY photographer_id
        ) AS pvl_count ON pvl_count.photographer_id = bp.id
        WHERE pcs.category = :category
        ORDER BY (pcs.score + COALESCE(pvl_count.views, 0)) DESC
        LIMIT 20
    ");
    $stmt->execute(['category' => $category]);
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($result as $row) {
        echo '
        <div class="photographer-card">
            <div class="card-header">
                <img src="' . $row['picture'] . '" alt="' . htmlspecialchars($row['business_name']) . '" class="profile-image-cards">
                <div class="favorite-btn">
                    <svg viewBox="0 0 24 24"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path></svg>
                </div>
            </div>
            <div class="card-content">
                <a href="/momento/profile.php?username=' . urlencode($row['username']) . '"><h3 class="username">' . htmlspecialchars($row["business_name"]) . '</h3></a>
                <div class="location">
                    <svg viewBox="0 0 24 24"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg>
                    ' . htmlspecialchars($row["location"]) . '
                </div>
                <div class="rate">$120 / hour</div>
                <div class="rating">
                    <svg viewBox="0 0 24 24"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon></svg>
                    ' . $row['total_rate'] . '&nbsp;(' . $row['total_reviews'] . ' reviews)
                </div>
                <div class="divider"></div>
                <div class="tags">
                    <span class="tag">Category Score: ' . $row['category_score'] . '</span>
                    <span class="tag">Views: ' . $row['total_views'] . '</span>
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
