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
	<style>
		:root {
        --warning-bg: rgba(0, 0, 0, 0.85);
        --text-light: #f8fafc;
        --text-secondary: #e2e8f0;
        --primary-color: #3b82f6;
        --primary-hover: #2563eb;
        --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        --blur-intensity: 20px;
        --card-radius: 8px;
    }
	      .gallery-item {
        position: relative;
        overflow: hidden;
        border-radius: var(--card-radius);
        transition: var(--transition);
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }
    
    .sensitive-content {
        position: relative;
        isolation: isolate; /* Creates new stacking context */
    }
    
    .sensitive-image {
        filter: blur(var(--blur-intensity));
        transition: var(--transition);
        width: 100%;
        height: 100%;
        object-fit: cover;
        transform: scale(1.05);
        border-radius: var(--card-radius);
    }
    
    .content-warning {
        position: absolute;
        inset: 0;
        background: var(--warning-bg);
        color: var(--text-light);
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        text-align: center;
        padding: 1.5rem;
        border-radius: var(--card-radius);
        backdrop-filter: blur(2px);
        transition: var(--transition);
        z-index: 10; /* Higher than image but lower than hover overlay */
        opacity: 1;
        pointer-events: auto;
    }
    
    .warning-icon {
        font-size: 2rem;
        margin-bottom: 1rem;
        color: #f59e0b;
    }
    
    .warning-heading {
        font-size: 1.3rem;
        font-weight: 600;
        margin-bottom: 0.6rem;
        line-height: 1.4;
    }
    
    .warning-description {
        color: var(--text-secondary);
        max-width: 28ch;
        margin-bottom: 1.5rem;
        line-height: 1.5;
        font-size: 0.95rem;
    }
    
    .reveal-button {
        padding: 0.7rem 1.5rem;
        background-color: var(--primary-color);
        color: black;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        font-size: 0.95rem;
        font-weight: 500;
        transition: var(--transition);
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
    }
    
    .reveal-button:hover {
        background-color: var(--primary-hover);
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    }
    
    .no-blur {
        filter: blur(0);
        transform: scale(1);
    }
    
    .hide-warning {
        opacity: 0;
        pointer-events: none;
    }
    
    /* Improved hover overlay positioning */
    .hover-overlay {
        z-index: 20; /* Highest z-index to ensure it's always on top */
        background: linear-gradient(0deg, rgba(0,0,0,0.7) 0%, rgba(0,0,0,0.3) 50%, transparent 100%);
        opacity: 0;
        transition: var(--transition);
    }
    
    .gallery-item:hover .hover-overlay {
        opacity: 1;
    }
    
    /* Ensure the image link doesn't interfere with warning */
    .gallery-item > a {
        z-index: 5; /* Between image and warning */
    }
	</style>
</head>

<body class = "fade-in">
     <?php require("header.php"); ?> 
    <div class="hero-section">
	
        <div class="container ">
            <h1 class="display-10 slide-in">Discover the world's top Photos</h1>
            <?php
				require('include/search-bar.php');
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
    <div class="image-gallery image-container">
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
				images.is_sensitive,
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
				echo '<div class="gallery-item position-relative sensitive-content" style="overflow: hidden;">';
                
                // Check if image is marked as sensitive
                if ($image['is_sensitive'] == 1) {
                    // Show blurred version with warning
                    echo '<img loading="lazy" src="' . htmlspecialchars($image['url']) . '" class="w-100 sensitive-image blur" alt="' . htmlspecialchars($image['label']) . '">';
					echo '<div class="content-warning">';

                    echo '<div class="warning-icon">⚠️</div>';
                    echo '<h3 class="warning-heading">Sensitive Content</h3>';
                    echo '<p class="warning-description">This image contains content that may be disturbing to some viewers.</p>';
                    echo '<button class="reveal-button" onclick="revealImage(this)">';
                    echo '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">';
                    echo '<path d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8zM1.173 8a13.133 13.133 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5c2.12 0 3.879 1.168 5.168 2.457A13.133 13.133 0 0 1 14.828 8c-.058.087-.122.183-.195.288-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.119 12.5 8 12.5c-2.12 0-3.879-1.168-5.168-2.457A13.134 13.134 0 0 1 1.172 8z"/>';
                    echo '<path d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5zM4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0z"/>';
                    echo '</svg>';
                    echo ' View Content';
                    echo '</button>';
                    echo '</div>';
                } else {
                    // Show normal image
                    echo '<img loading="lazy" src="' . htmlspecialchars($image['url']) . '" class="w-100" alt="' . htmlspecialchars($image['label']) . '">';
                }
                
                echo '<a href="view_image.php?id=' . $image['id'] . '" class="position-absolute top-0 start-0 w-100 h-100" style="z-index: 1;"></a>';
                echo '<div class="hover-overlay position-absolute top-0 start-0 w-100 h-100 d-flex flex-column justify-content-end p-3" style="z-index: 3; pointer-events: none;">';
                echo '<div class="mt-2 d-flex align-items-center" style="pointer-events: auto;">';
                echo '<img src="' . htmlspecialchars($image['picture']) . '" alt="Profile" class="me-2 pfp" style="width:30px;height:30px;">';
                echo '<span class="text-white text-capitalize">';
                echo '<a href="/momento/profile.php?username=' . htmlspecialchars($image['username']) . '" style="color:white;" onclick="event.stopPropagation();">';
                echo htmlspecialchars($image['username']);
                echo '</a></span>';
                echo '</div>';
                echo '<div class="d-flex align-items-center justify-content-between text-white" style="pointer-events: auto;">';
                echo '<button class="btn like-btn ' . $btnClass . '" data-image-id="' . $image['id'] . '" onclick="event.stopPropagation();">';
                echo '<i class="bi bi-suit-heart-fill" style="margin:auto;"></i>';
                echo '</button>';
                echo '<span><i class="fas fa-eye"></i> ' . $image['views'] . '</span>';
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

    <script>
        function revealImage(button) {
			const container = button.closest('.gallery-item');
            const image = container.querySelector('.sensitive-image');
			const warning = container.querySelector('.content-warning');
            
            image.classList.add('no-blur');
            warning.classList.add('hide-warning');
            
            // Optional: Prevent right-click/saving of sensitive images
            image.oncontextmenu = function() {
                return false;
            };
        }
    </script>


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