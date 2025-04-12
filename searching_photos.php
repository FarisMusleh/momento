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
if(empty($_GET['query'])){
	header('location: gallery.php');
}
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
	<link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet">
	<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
	<!--CSS-->
    <link rel="stylesheet" href="css/header.css">
	<link rel="stylesheet" href="css/gallery.css">
	<!--JS FILE-->
	<script src="js/gallery.js"></script>
    <title>Photos</title>



</head>

<body>
    <?php require("header.php"); ?>  
    <div class="hero-section">
        <div class="container">
            <h1 class="display-10 slide-in">Discover the world's top Photos</h1>
            <?php
				require('search-bar.php');
			?>
        </div>
    </div>

    <!-- Image Gallery Section -->
    <div class="container-fluid p-5">
        <div class="image-gallery" style = "">
            <?php
				if (isset($_GET['query'])) {
					
					$search_query = $_GET['query'] ?? '';
					
					$flask_url = 'http://127.0.0.1:5000/search?query=' . urlencode($search_query);

					// Initialize cURL session
					$ch = curl_init();
					curl_setopt($ch, CURLOPT_URL, $flask_url);
					curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
					curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
					$response = curl_exec($ch);

					if($response === false) {
						echo "cURL Error: " . curl_error($ch);
						exit();
					}
					
					$response_data = json_decode($response, true);
					curl_close($ch);
					
					if (isset($response_data['similar_categories'])) {
						$sql = "SELECT images.id,url,username,views,label,likes,picture,(likes * 100.0 / NULLIF(views, 0)) AS like_percentage FROM 
						images,accounts WHERE images.label LIKE ? and images.user_id = accounts.id ORDER BY like_percentage DESC, views DESC;";
						$stmt = $pdo->prepare($sql);
						$stmt->execute(['%' .$response_data['similar_categories'][0]. '%']);
						if ($stmt->rowCount() > 0) {
							while ($image = $stmt->fetch(PDO::FETCH_ASSOC)) {
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
								echo '<span class="text-white" style="text-transform: capitalize;">' . htmlspecialchars($image['username']) . '</span>';
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
						} else {
							echo "<div>Sorry, we couldn't find any matches</div>";
						}
					}
				}
			
			?>
        </div>
    </div>
</body>
</html>
<script src="js/like-handler.js"></script>
