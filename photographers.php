<?php 
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require('pdo.php');

if (isset($_SESSION['data'])) {
    $data = $_SESSION['data'];
}
function safe_divide($a, $b) {
    return $b != 0 ? $a / $b : 0;
}
$canBook = false;
if (isset($_SESSION['data']['id'])) {
	$currentUserId = $_SESSION['data']['id'];
	$stmtType = $pdo->prepare('SELECT account_type FROM accounts WHERE id = ?');
	$stmtType->execute([$currentUserId]);
	$type_a = $stmtType->fetch(PDO::FETCH_ASSOC);
	if ($type_a && $type_a['account_type'] === 'user') {
		$canBook = true;
	}
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
	<style>
.photographer-card {
    font-family: 'Segoe UI', sans-serif;
    width: 300px;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 4px 20px rgba(0,0,0,0.1);
    background: white;
    transition: transform 0.3s ease;
    margin: 15px;
}

.photographer-card:hover {
    transform: translateY(-5px);
}

.card-header {
    position: relative;
    height: 120px;
}

.blur-pfp {
    width: 100%;
    height: 100%;
    object-fit: cover;
    filter: blur(3px);
}

.profile-image-cards {
    position: absolute;
    width: 80px;
    height: 80px;
    border-radius: 50%;
    border: 3px solid white;
    left: 50%;
    bottom: -40px;
    transform: translateX(-50%);
    object-fit: cover;
    box-shadow: 0 2px 10px rgba(0,0,0,0.2);
}

.favorite-btn {
    position: absolute;
    top: 15px;
    right: 25px;
}

.follow-btn {
    background: rgba(255,255,255,0.9);
    border: none;
    padding: 6px 12px;
    border-radius: 20px;
    font-weight: 600;
    cursor: pointer;
    box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    transition: all 0.2s;
}

.follow-btn:hover {
    background: #f0f0f0;
    transform: scale(1.05);
}

.card-content {
    padding: 30px 20px 20px;
    text-align: center;
}

.username {
    margin: 15px 0 5px;
    color: #333;
    font-size: 1.2rem;
}

.professional-badge {
    margin: 5px 0 15px;
}

.badge {
    background: #4a6bff;
    color: white;
    padding: 3px 8px;
    border-radius: 4px;
    font-size: 0.7rem;
    font-weight: 600;
}

.verified-badge {
    color: #4CAF50;
    margin-left: 5px;
    font-size: 0.8rem;
}

.location, .rating {
    display: flex;
    align-items: center;
    justify-content: center;
    color: #666;
    font-size: 0.9rem;
    margin: 8px 0;
}

.location svg, .rating svg {
    width: 16px;
    height: 16px;
    margin-right: 5px;
    fill: currentColor;
}

.rating {
    color: #FFA41C;
}

.divider {
    height: 1px;
    background: #eee;
    margin: 15px 0;
}

.tags {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    gap: 8px;
    margin-bottom: 15px;
}

.tag {
    background: #a0a0a0;
    color: azure;
    padding: 4px 10px;
    border-radius: 20px;
    font-size: 0.75rem;
}

.action-buttons {
    display: flex;
    gap: 10px;
    margin-top: 15px;
}

.message-btn, .book-btn {
    flex: 1;
    padding: 8px;
    border: none;
    border-radius: 6px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.2s;
}

.message-btn {
    background: #c0c0c0;
    color: #333;
}

.book-btn {
    background: #4a6bff;
    color: white;
}

.message-btn:hover {
    background: #e0e0e0;
}

.book-btn:hover {
    background: #3a5bef;
    transform: translateY(-2px);
}

	</style>
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
    <div class="row  justify-center">
	<?php
	$stmt = $pdo->prepare("
		SELECT bp.business_name, bp.bio, a.username, a.location, a.picture, bp.total_rate, bp.total_likes, bp.total_reviews, bp.total_views, bp.id as photographer_id
		FROM business_profiles bp
		JOIN accounts a ON bp.id = a.id
		ORDER BY bp.total_rate DESC, bp.total_likes DESC, bp.total_views DESC
	");
	$stmt->execute();
	$result = $stmt->fetchAll(PDO::FETCH_ASSOC);

	foreach ($result as $row) {
		$servicesStmt = $pdo->prepare("SELECT category, price FROM appointment_services WHERE photographer_id = ? LIMIT 3");
		$servicesStmt->execute([$row['photographer_id']]);
		$services = $servicesStmt->fetchAll(PDO::FETCH_ASSOC);

		echo '
		<div class="photographer-card">
    <div class="card-header">
        <img src="'.$row['picture'].'" width="296" height="120" class="blur-pfp">
        <img src="'.$row['picture'].'" alt="'.$row["business_name"].'" class="profile-image-cards">
        
    </div>
    <div class="card-content">
        <a href="/momento/profile.php?username='.$row['username'].'"><h3 class="username">'.$row["business_name"].'</h3></a>
        <div class="location">
            <svg viewBox="0 0 24 24"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg>
            '.$row["location"].'
        </div>
        <div class="rating">
            <svg viewBox="0 0 24 24"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon></svg>
            '.safe_divide($row['total_rate'], $row['total_reviews']).'&nbsp;('.$row['total_reviews'].' reviews)
        </div>
        
        <div class="tags">';
        
        foreach ($services as $service) {
            echo '<span class="tag">'.htmlspecialchars($service['category']).'</span>';
        }
        
        echo '</div>
        <div class="action-buttons">
            '; 
			if ($canBook) {echo '<button class="book-btn" onclick="location.href=\'/momento/appointment/index.php?photographer_id=' . $row["photographer_id"] . '\'">Book Now</button>';}
            $chatLink = isset($_SESSION['data']['id']) 
				? '/momento/chat/chat.php?id=' . $row["photographer_id"] 
				: '/momento/account/login.php';
			echo '<button class="message-btn" onclick="location.href=\'' . $chatLink . '\'">Message</button>
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
