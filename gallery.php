<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
	}
	if(isset($_SESSION['data'])){
		$data = $_SESSION['data'];
		$isLoggedIn = 'true';
	}else{
		$isLoggedIn = 'false';
	}
require('pdo.php');
?>
<script>
    const isLoggedIn = <?php echo $isLoggedIn; ?>;
</script>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- GOOGLE-FONTS -->
	<link href="https://fonts.googleapis.com/css2?family=Dancing+Script&family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css2?family=Pacifico&family=Roboto:wght@300;500;700&family=Dancing+Script&display=swap" rel="stylesheet">
	<link rel="stylesheet" href="css/header.css">
	<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Include Bootstrap Icons if not already -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

	<!--CSS FILE-->
	<link rel="stylesheet" href="css/gallery.css">
	<link rel="stylesheet" href="css/navbar-scrolled.css">
	<!--JS FILE-->
	<script src="js/gallery.js"></script>
    <title>Gallery</title>
</head>
<body class = "fade-in">
    <?php require("header.php"); ?>  
    <div class="hero-section">
        <div class="container">
            <h1 class="display-10 slide-in">Discover the world's top Photos</h1>
            <?php
				require('search-bar.php');
			?>
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
    <!-- Image Gallery Section -->
<div class="container-fluid p-5">
    <div class="image-gallery">
        <?php
        $userLocation = 'USA'; // This can be dynamic later
        $sql = "
            SELECT 
                images.id,
                images.label,
                images.description,
                images.url,
                images.views,
                images.likes,
                images.created_at,
                accounts.username,
                accounts.location,
                accounts.picture,
                (
                    (images.likes * 3 + images.views) /
                    POW(TIMESTAMPDIFF(HOUR, images.created_at, NOW()) + 2, 1.5)
                ) * IF(accounts.location = :userLocation, 1.5, 1) AS score
            FROM images
            JOIN accounts ON images.user_id = accounts.id
            ORDER BY score DESC
            LIMIT 50
        ";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':userLocation', $userLocation);
        $stmt->execute();
        $images = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach ($images as $image) {
			$btnClass = '';
			if(isset($_SESSION['data'])){
				$stmt = $pdo->prepare("SELECT 1 FROM likes WHERE user_id = ? AND image_id = ?");
				$stmt->execute([$data['id'], $image['id']]);
				$liked = $stmt->fetch();
				$btnClass = $liked ? 'liked-button' : '';
			}
			
            echo '<div>';
            echo '<div class="gallery-item">';
            echo '<img loading = "lazy" src="' . htmlspecialchars($image['url']) . '" class="" alt="' . htmlspecialchars($image['label']) . '">';
            echo '<div class="hover-overlay position-absolute top-0 start-0 w-100 h-100 d-flex flex-column justify-content-end p-3">';
            echo '<div class="mt-2 d-flex align-items-center">';
            echo '<img src="' . htmlspecialchars($image['picture']) . '" alt="Profile" class="me-2 pfp" style="width:30px;height:30px;">';
            echo '<span class="text-white" style="text-transform: capitalize;"><a style = "color:white;" href = "/momento/profile.php?username='.htmlspecialchars($image['username']).'">' . htmlspecialchars($image['username']) . '</a></span>';
            echo '</div>';
            echo '<div class="d-flex align-items-center justify-content-between text-white">';
            echo '<button class="btn like-btn '.$btnClass.'" data-image-id="' . $image['id'] . '">';
            echo '<i class="bi bi-suit-heart-fill" style="margin:auto;"></i>';
            echo '</button>';
            echo '<span><i class="fas fa-eye"></i> ' . $image['views'] . '</span>';
            echo '</div>';
            echo '</div>';
            echo '</div>';
            echo '</div>';
        }
        ?>
    </div>
</div>
</body>
</html>
<script src="js/like-handler.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
 integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
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