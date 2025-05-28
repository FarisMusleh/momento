<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require('pdo.php');
if (isset($_GET['username'])) {
    $profileUsername = $_GET['username'];
} else {
	if (!isset($_SESSION['data'])) {
		header('location:index.php');
		exit();
	}
    $profileUsername = $_SESSION['data']['username'];
	
}
$sql_check_user_profiles = null;
if(isset($_SESSION['data']['username'])){
		$sql_check_user_profiles = $pdo->prepare('select account_type from accounts where id = ?');
$sql_check_user_profiles->execute([$_SESSION['data']['id']]);
$user_account_type = $sql_check_user_profiles->fetch()['account_type'];
if($user_account_type=='user' && !isset($_GET['username'])){

	header('location:index.php');
	exit();
}

}



//TO-CHECK-IF-IT-IS-MY-PF-OR-NOT
if(isset($_SESSION['data']['username'])){
	$isMyProfile = ($profileUsername === $_SESSION['data']['username']);
}else{
	$isMyProfile = "";
}
$stmt = $pdo->prepare('SELECT account_type,id FROM accounts WHERE username = ?');
$stmt->execute([$profileUsername]);
$result = $stmt->fetch(PDO::FETCH_ASSOC);
$account_type = $result['account_type'];
$id = intval($result['id']);
if(!$account_type=='business'){
	header('index.php');
	exit();
}
 if ($account_type == 'business') {
    $profileData = $pdo->prepare('SELECT location,picture,business_name,job,bio FROM accounts,business_profiles WHERE accounts.id = ? and accounts.id = business_profiles.id');
    $profileData->execute([$id]);
    $profile = $profileData->fetch(PDO::FETCH_ASSOC);
	$displayName = $profile['business_name'];
}
//TO-REMEMBER-VARIABLES($profile,$id,$account_type) 
// Get social links
$sql = $pdo->prepare("SELECT social_links FROM business_profiles WHERE id = ?");
$sql->execute([$id]);
$json = $sql->fetch(PDO::FETCH_ASSOC);
$social_links = !empty($json['social_links']) ? json_decode($json['social_links'], true) : [];

// Get user images
function is_valid_image($url) {
    if (empty($url)) {
        return false;
    }
    if (!filter_var($url, FILTER_VALIDATE_URL)) {
        return file_exists($url);
    }
    $headers = @get_headers($url);
    return $headers && strpos($headers[0], '200') !== false;
}

$stmt = $pdo->prepare('SELECT url,id,is_sensitive,label FROM images WHERE user_id = ?');
$stmt->execute([$id]);
$images = $stmt->fetchAll(PDO::FETCH_ASSOC);

if(!$isMyProfile){
	require('queries/view_counter_profiles.php');
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Dancing+Script&family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Pacifico&family=Roboto:wght@300;500;700&family=Dancing+Script&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet">
    <!-- CSS -->
    <link rel="stylesheet" href="css/header.css">
	<link rel="stylesheet" href="css/profile.css">
	<!-- Toastify -->
	<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
	
	
	
<style>
  #reviewModal .modal-content {
    border-radius: 0.5rem;
    overflow: hidden;
    border: none;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
  }
  
  #reviewModal .form-control:focus {
    border-color: #212529;
    box-shadow: none;
  }
  
  #reviewModal .btn {
    border-radius: 0.25rem;
    font-weight: 500;
    transition: all 0.3s ease;
  }
  
  #reviewModal .btn-dark:hover {
    background-color: #000;
  }
  
  #reviewModal .btn-outline-dark:hover {
    background-color: #f8f9fa;
    color: #212529;
  }
  
  #starRating .fa-star-o:hover ~ .fa-star-o {
    opacity: 0.5;
  }
  
  #reviewModal textarea {
    resize: none;
  }
   .empty-profile-box {
    transition: all 0.3s ease;
  }
  
  .empty-profile-link {
    display: block;
    text-decoration: none;
    color: #212529;
    border: 2px dashed rgba(0, 0, 0, 0.15);
    border-radius: 8px;
    padding: 40px 20px;
    transition: all 0.3s ease;
    background-color: rgba(248, 249, 250, 0.7);
  }
  
  .empty-profile-link:hover {
    background-color: #f8f9fa;
    border-color: rgba(0, 0, 0, 0.3);
    transform: translateY(-3px);
    box-shadow: 0 10px 20px rgba(0, 0, 0, 0.08);
    color: #000;
    text-decoration: none;
  }
  
  .empty-profile-container {
    max-width: 300px;
    margin: 0 auto;
  }
  
  .empty-profile-link i {
    font-size: 32px;
    color: #000;
    opacity: 0.7;
  }
  
  .empty-profile-link:hover i {
    opacity: 1;
  } 
  
  textarea:focus,
input:focus {
  outline: none;
  box-shadow: none;
  border-color: black;
}

.letter-spacing-1 {
  letter-spacing: 1px;
}

.fa-star-o {
  transition: color 0.3s;
}

.fa-star-o:hover {
  color: #888;
}
.modal-content {
  border-radius: 0 !important;
  box-shadow: none !important;
}

textarea:focus,
input:focus {
  outline: none;
  box-shadow: none;
  border-color: black;
}

.letter-spacing-1 {
  letter-spacing: 1px;
}


.slider-group input[type=range] {
  background-color: #222;
  padding:4px 0px;
  color: black;
  margin-bottom: 12px;
  border: 1px solid black;
  border-radius: 10px;
}
.action-btn {
      display: flex;
      align-items: center;
      padding: 8px 16px;
      border-radius: 50px;
      font-weight: 500;
      border: none;
      background: white;
      box-shadow: 0 2px 4px rgba(0,0,0,0.1);
      cursor: pointer;
      transition: all 0.2s;
    }
    
    .action-btn:hover {
      transform: translateY(-2px);
      box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }
	.image-container .overlay-buttons {
    opacity: 0;
    transition: opacity 0.3s ease;
    pointer-events: none;
}

.image-container:hover .overlay-buttons {
    opacity: 1;
    pointer-events: auto;
}

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
<body>
    <?php require('header.php'); ?>
    
    <div class="profile-container">
        <div class="profile-header">
			<?php if(!$isMyProfile){?>
			<div style="display: flex; flex-direction: row; justify-content: end; gap: 10px;">
			  <!-- Circle Comment Icon -->
			  <a href="<?php if(isset($_SESSION['data'])){ echo "chat/chat.php?id={$id}";} else{echo "/momento/account/login.php";}?>" 
				 style="width: 35px; height: 35px; border-radius: 50%; background-color: grey;
						display: flex; align-items: center; justify-content: center; 
						color: #212529; border: 1px solid #212529; text-decoration: none;">
				<i class="fas fa-comment-dots" style = "font-size:15px;"></i>
			  </a>
				<?php if(isset($_SESSION['data']) && $user_account_type == "user"){?>
			  <button type="button" class="btn btn-dark" data-bs-toggle="modal" data-bs-target="#reviewModal" onclick="handleButtonClick()">
				<?php 
					$sql = $pdo->prepare('select * from reviews where user_id = ? and photographer_id = ?');
					$sql->execute([$_SESSION['data']['id'],$id]);
					$result = $sql->fetch();
					if($result){
						echo "Update the Review";
						$rate_id = $result['id'];
						$stars = $result['rate'];
						$comment = $result['comment'];
						$flag = true;
						
					}else{
						echo "Leave a Review";
					}
					
				?>
				</button>
				<a href = "appointment/index.php?photographer_id=<?=$id?>" class="btn btn-dark">
					Book an Appointment
				</a>
				<?php }?>

					<!-- Review Modal -->
					<div class="modal fade" id="reviewModal" tabindex="-1" aria-labelledby="reviewModalLabel" aria-hidden="true">
					  <div class="modal-dialog modal-dialog-centered">
						<form method="post" action="rating_handler.php" class="modal-content border-0" id="reviewForm" style="border-radius: 0; box-shadow: none;">
						  
						  <!-- Header -->
						  <div class="modal-header bg-black text-white border-0" style="border-radius: 0;">
							<h5 class="modal-title text-uppercase letter-spacing-1" id="reviewModalLabel">
							  <i class="fa fa-comment me-2"></i>Share Your Experience
							</h5>
							<button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
						  </div>

						  <!-- Body -->
						  <div class="modal-body p-4 bg-white text-dark" style="border-radius: 0;">
							
							<!-- Star Rating -->
							<div class="mb-4 text-center">
							  <label class="form-label fw-bold mb-3">How would you rate your experience?</label>
							  <div id="starRating" class="fs-2 d-flex justify-content-center" style="cursor: pointer; color: black;">
								<i class="fa fa-star-o mx-1" data-index="0"></i>
								<i class="fa fa-star-o mx-1" data-index="1"></i>
								<i class="fa fa-star-o mx-1" data-index="2"></i>
								<i class="fa fa-star-o mx-1" data-index="3"></i>
								<i class="fa fa-star-o mx-1" data-index="4"></i>
							  </div>
							  <input type="hidden" name="rating" id="ratingValue" value="<?=$stars??""?>">
							  <?php if(isset($flag)==true){?>
							  <input type="hidden" name="update">
							  <input type="hidden" name="rate_id" value="<?=$rate_id?>">
							  <?php }?>
							  <input type="hidden" name="photographer_id" value="<?=$id?>">
							  <div id="ratingText" class="mt-2 text-muted fst-italic"></div>
							</div>

							<!-- Comment -->
							<div class="mb-3">
							  <label for="comment" class="form-label fw-bold">Tell us about your experience</label>
							  <textarea 
								class="form-control border-dark bg-white text-dark shadow-none" 
								id="comment" 
								name="comment" 
								rows="4" 
								placeholder="Share your thoughts here..." 
								required
								style="border-radius: 0;"
							  ><?=$comment??""?></textarea>
							</div>
						  </div>

						  <!-- Footer -->
						  <div class="modal-footer bg-white border-0" style="border-radius: 0;">
							<button type="button" class="btn btn-outline-dark" data-bs-dismiss="modal">Cancel</button>
							<button type="submit" class="btn btn-dark px-4">
							  <i class="fa fa-paper-plane me-2"></i>Submit Review
							</button>
						  </div>

						</form>
					  </div>
					</div>

			</div>
			<?php }?>
			<div class="profile-header-content">
                <!-- Profile Picture Section -->
                <div class="profile-image-section">
                    <div class="profile-image-container">
                        <img src="<?= htmlspecialchars($profile['picture']) ?>" class="profile-image" alt="Profile Picture">
                        
						<?php if($isMyProfile){?>
                        <!-- Profile Picture Upload -->
                        <form id="profileUploadForm" action="upload-profile-img.php" method="POST" enctype="multipart/form-data">
                            <input type="file" name="profileFile" id="profileFile" class="d-none" accept="image/*" onchange="handleImagePreview(this, 'profile')">
                            <button id = "profile-btn-upload" type="button" class="edit-profile-image" onclick="document.getElementById('profileFile').click()">+</button>
                        </form>
						<?php }?>
                    </div>
                    <div class="profile-basic-info">
                        <h1 class="profile-name"><?= htmlspecialchars($displayName) ?></h1>
                        <p class="profile-title"><?=$profile['job']??""?></p>
                        <div class="profile-location">
                            <i class="fas fa-map-marker-alt"></i>
                            <span><?=$profile['location']??""?></span>
                        </div>
						<?php if($isMyProfile){?>
                        <a class="btn btn-light text-dark border-dark hover-dark" style="width: 150px;" href="edit-profile.php">
						<i class="bi bi-pencil-square"></i>	Edit Profile
						</a>
						<a class="btn btn-light text-dark border-dark hover-dark" style="width: 150px;" href="dashboard.php">
							<i class="bi bi-bar-chart-line"></i> Dashboard
						</a>
						
						<?php }?>
                    </div>
                </div>

                <!-- About Me Section Integrated in Header -->
                <div class="profile-about-section">
                    <h2>About Me</h2>
                    <p class="about-content">
                        <?= isset($profile['bio']) ? htmlspecialchars($profile['bio']) : '' ?>
                    </p>
                    <div class="profile-social">
                        <?php if(!empty($social_links['linkedIn'])) : ?>
                            <a href="<?= htmlspecialchars($social_links['linkedIn']) ?>" class="social-link" target="_blank" rel="noopener noreferrer">
                                <i class="fab fa-linkedin"></i>
                            </a>
                        <?php endif; ?>
                        
                        <?php if(!empty($social_links['instagram'])) : ?>
                            <a href="<?= htmlspecialchars($social_links['instagram']) ?>" class="social-link" target="_blank" rel="noopener noreferrer">
                                <i class="fab fa-instagram"></i>
                            </a>
                        <?php endif; ?>
                        
                        <?php if(!empty($social_links['twitter'])) : ?>
                            <a href="<?= htmlspecialchars($social_links['twitter']) ?>" class="social-link" target="_blank" rel="noopener noreferrer">
                                <i class="fab fa-twitter"></i>
                            </a>
                        <?php endif; ?>
                        
                        <?php if(!empty($social_links['facebook'])) : ?>
                            <a href="<?= htmlspecialchars($social_links['facebook']) ?>" class="social-link" target="_blank" rel="noopener noreferrer">
                                <i class="fab fa-facebook"></i>
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
        <?php
			$sql = $pdo->prepare('select * from experience where user_id = ?');
			$sql->execute([$id]);
			$result = $sql->fetchAll();
		?>
        <main class="profile-content">
            <div class="profile-columns">
			
                <section class="profile-section experience-section">
					<h2 class="section-title">Experience</h2>
					<?php if(!$result && $isMyProfile){
						echo "
					<div class='text-center py-5 my-4 empty-profile-box'>
					  <a href='edit-profile.php' class='empty-profile-link'>
								<div class='empty-profile-container'>
								  <i class='fa fa-plus-circle mb-3'></i>
								  <h5 class='fw-bold mb-2'>Add Your First Experience</h5>
								  <p class='text-muted small'>Share your professional journey with others</p>
								</div>
							  </a>
					  </div>";
					}?>
					<div class="timeline">
						<?php foreach($result as $row){ ?>
                        <div class="timeline-item">
							<div class="timeline-date"><?=$row['start_date']?> - <?=$row['end_date']?></div>
                            <div class="timeline-content">
                                <h3><?=$row['company']?></h3>
                                <p class="timeline-position"><?=$row['job']?></p>
                                <p><?=$row['achievements']?></p>
                            </div>
							<?php if($isMyProfile){
								echo "<form action = 'delete_experience.php' method = 'post'>";
								echo "<input type = 'hidden' name = 'del_exp' value = '".$row['id']."'>";
								echo "<input type = 'submit' class = 'btn btn-danger btn-sm' value = 'delete'>";
								echo "</form>";
								}?>
                        </div>
						<?php }?>
                    </div>
                </section>
				
                <section class="profile-section gallery-section">
                    <hr>
					
					<!--UPLOAD-IMAGE-->
					<form id="uploadForm" action="upload.php" method="POST" enctype="multipart/form-data">
					  <div style="display:flex;flex-direction:row;justify-content: space-between;align-items:center;">
						<h2 class="section-title">Gallery</h2>
						<input type="file" id="file" class="d-none" accept="image/*" onchange="previewImage(this)" data-type="image" required>
						<?php if($isMyProfile){?>

						  <button id="image-upload-btn" type="button" onclick="triggerFileInput('file');" class=" btn btn-dark">
							<i class="bi bi-arrow-up-circle"></i>
								upload
						  </button>
						<?php }?>
						<canvas id="imageCanvas" style="display:none;"></canvas>

						<!-- Modal -->
						<div class="modal fade" id="imageModal" tabindex="-1" aria-labelledby="imageModalLabel" aria-hidden="true" style = "border-radius: 0 !important;">
						  <div class="modal-dialog modal-dialog-centered modal-lg">
							<div class="modal-content border-0" style="border-radius: 0; box-shadow: none;">

							  <!-- Header -->
							  <div class="modal-header bg-black text-white border-0" style = "border-radius: 0 !important;">
								<h5 class="modal-title text-uppercase letter-spacing-1" id="imageModalLabel">Image Preview</h5>
								<button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
							  </div>

							  <!-- Body: Image and Controls -->
							  <div class="modal-body bg-white d-flex flex-column flex-md-row justify-content-center align-items-center gap-4">
								<img id="previewImg"
									 src="#"
									 class="img-fluid"
									 style="max-height: 300px; display: none; border: 2px solid black; border-radius: 0;">

								<!-- CONTROL-IMAGE -->
								<div class="controls w-100">
								  <div class="slider-group mb-2">
									<label for="brightness" class="form-label">Brightness <span id="val-brightness">100</span></label>
									<input type="range" class="form-range" id="brightness" min="0" max="200" value="100">
								  </div>
								  <div class="slider-group mb-2">
									<label for="contrast" class="form-label">Contrast <span id="val-contrast">100</span></label>
									<input type="range" class="form-range" id="contrast" min="0" max="200" value="100">
								  </div>
								  <div class="slider-group mb-2">
									<label for="grayscale" class="form-label">Grayscale <span id="val-grayscale">0</span></label>
									<input type="range" class="form-range" id="grayscale" min="0" max="100" value="0">
								  </div>
								  <div class="slider-group mb-2">
									<label for="saturate" class="form-label">Saturation <span id="val-saturate">100</span></label>
									<input type="range" class="form-range" id="saturate" min="0" max="200" value="100">
								  </div>
								</div>
							  </div>
								<!-- TITLE -->
							  <div class="modal-body bg-white">
								<input type="text"
									   id="myText"
									   name="title"
									   placeholder="Title"
									   class="form-control border-dark shadow-none"
									   style="border-radius: 0;">
							  </div>
							  <!-- Description -->
							  <div class="modal-body bg-white">
								<input type="text"
									   id="myText"
									   name="description"
									   placeholder="Description"
									   class="form-control border-dark shadow-none"
									   style="border-radius: 0;">
							  </div>

							  <!-- Footer -->
							  <div class="modal-footer bg-white border-0">
								<button id="image_upload_button" type="button" class="btn btn-dark px-4" onclick="processAndUploadImage()">
								  Upload
								</button>
							  </div>

							</div>
						  </div>
						</div>
					  </div>
					</form>

					<div class="gallery-container">
<?php foreach($images as $image): ?>
    <?php 
        $image_url = $image["url"];  
        $btnClass = '';
        if (isset($_SESSION['data'])) {
            $stmt = $pdo->prepare("SELECT 1 FROM likes WHERE user_id = ? AND image_id = ?");
            $stmt->execute([$data['id'], $image['id']]);
            $liked = $stmt->fetch();
            $btnClass = $liked ? 'liked-button' : '';
        }
    ?>  

    <div class="image-container position-relative sensitive-content" style="overflow: hidden; border-radius: 8px;">
        
        <!-- Sensitive Image Handling -->
        <?php if ($image['is_sensitive'] == 1): ?>
            <img loading="lazy" src="<?= htmlspecialchars($image_url) ?>" 
                 class="gallery-image w-100 sensitive-image blur" 
                 alt="<?= htmlspecialchars($image['label']) ?>" 
                 style="display: block;">
            
            <div class="content-warning">
                <div class="warning-icon">⚠️</div>
                <h3 class="warning-heading">Sensitive Content</h3>
                <p class="warning-description">This image contains content that may be disturbing to some viewers.</p>
                <button class="reveal-button" onclick="revealImage(this)">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                        <path d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8zM1.173 8a13.133 13.133 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5c2.12 0 3.879 1.168 5.168 2.457A13.133 13.133 0 0 1 14.828 8c-.058.087-.122.183-.195.288-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.119 12.5 8 12.5c-2.12 0-3.879-1.168-5.168-2.457A13.134 13.134 0 0 1 1.172 8z"/>
                        <path d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5zM4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0z"/>
                    </svg>
                    View Content
                </button>
            </div>
        <?php else: ?>
            <img src="<?= htmlspecialchars($image_url) ?>" class="gallery-image w-100" alt="Gallery Image" style="display: block;">
        <?php endif; ?>

        <!-- Clickable overlay link -->
        <a href="view_image.php?id=<?= $image['id'] ?>" class="position-absolute top-0 start-0 w-100 h-100" style="z-index: 1;"></a>

        <!-- Overlay buttons (download, delete, like) -->
        <div class="overlay-buttons position-absolute top-0 end-0 p-2 d-flex gap-2" style="z-index: 3;">
            <!-- Download -->
            <a href="download_handler.php/?imageId=<?= $image['id'] ?>" class="btn btn-sm btn-light" onclick="event.stopPropagation();">
                <i class="fas fa-download"></i>
            </a>

            <!-- Like Button SOOOOOOOOOOOOON
            <?php if(isset($_SESSION['data'])): ?>
                <button class="btn btn-sm like-btn <?= $btnClass ?>" data-image-id="<?= $image['id'] ?>" onclick="event.stopPropagation();">
                    <i class="bi bi-suit-heart-fill"></i>
                </button>
            <?php endif; ?>-->

            <!-- Delete (Only for my profile) -->
            <?php if($isMyProfile): ?>
                <button type="button" class="btn btn-sm btn-danger" onclick="event.stopPropagation(); confirmDelete(<?= $image['id'] ?>);">
                    <i class="fas fa-trash-alt"></i>
                </button>
            <?php endif; ?>
        </div>
    </div>
<?php endforeach; ?>











					</div>
                </section>
            </div>
        </main>
    </div>
	<!-- Delete Confirmation Modal -->
	<div class="modal fade" id="deleteImageModal" tabindex="-1" aria-labelledby="deleteImageModalLabel" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="deleteImageModalLabel">Confirm Deletion</h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body">
					<p>Are you sure you want to delete this image? This action cannot be undone.</p>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
					<form id="deleteForm" action="delete_image.php" method="POST">
						<input type="hidden" id="deleteImageId" name="imageId" value="">
						<input type="hidden" name="userId" value="<?= $id ?>">
						<button type="submit" class="btn btn-danger">Delete</button>
					</form>
				</div>
			</div>
		</div>
	</div>
	<!-- Profile Image Modal -->
	<div class="modal fade" id="profileImageModal" tabindex="-1" aria-labelledby="profileImageModalLabel" aria-hidden="true">
	  <div class="modal-dialog modal-dialog-centered">
		<div class="modal-content border-0" style="border-radius: 0; box-shadow: none;">

		  <!-- Header -->
		  <div class="modal-header bg-black text-white border-0" style="border-radius: 0;">
			<h5 class="modal-title text-uppercase letter-spacing-1" id="profileImageModalLabel">
			  Profile Picture Preview
			</h5>
			<button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
		  </div>

		  <!-- Body -->
		  <div class="modal-body bg-white text-center" style="border-radius: 0;">
			<img id="profilePreviewImg"
				 src="#"
				 class="img-fluid mx-auto d-block"
				 style="width: 140px; height: 140px; object-fit: cover; display: none; border-radius: 50%; border: 2px solid black;">
		  </div>

		  <!-- Footer -->
		  <div class="modal-footer bg-white border-0" style="border-radius: 0;">
			<button type="button" class="btn btn-outline-dark" data-bs-dismiss="modal">Cancel</button>
			<button id="profile_picture_upload_button" type="button" class="btn btn-dark px-4" onclick="saveProfilePicture()">
			  Save Profile Picture
			</button>
		  </div>

		</div>
	  </div>
	</div>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" 
    integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
	<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"></script>

	
	<script src="js/like-handler.js"></script>
<script>
    function revealImage(button) {
        const container = button.closest('.image-container');
        const image = container.querySelector('.sensitive-image');
        const warning = container.querySelector('.content-warning');

        image.classList.add('no-blur');
        warning.classList.add('hide-warning');

        image.oncontextmenu = function() {
            return false;
        };
    }
</script>

	<script>

const stars = document.querySelectorAll('#starRating i');
  const ratingInput = document.getElementById('ratingValue');
  const ratingText = document.getElementById('ratingText');

  function updateStars(rating) {
    stars.forEach((star, i) => {
      const index = i + 1;
      if (rating >= index) {
        star.className = 'fa fa-star';
      } else if (rating >= index - 0.5) {
        star.className = 'fa fa-star-half-o';
      } else {
        star.className = 'fa fa-star-o';
      }
    });
    ratingText.innerText = `Rating: ${rating.toFixed(1)} / 5`;
  }

  stars.forEach((star, i) => {
    star.addEventListener('mousemove', (e) => {
      const rect = star.getBoundingClientRect();
      const x = e.clientX - rect.left;
      const isHalf = x < rect.width / 2;
      const tempRating = i + (isHalf ? 0.5 : 1);
      updateStars(tempRating);
    });

    star.addEventListener('mouseleave', () => {
      updateStars(parseFloat(ratingInput.value || 0));
    });

    star.addEventListener('click', (e) => {
      const rect = star.getBoundingClientRect();
      const x = e.clientX - rect.left;
      const isHalf = x < rect.width / 2;
      const finalRating = i + (isHalf ? 0.5 : 1);
      ratingInput.value = finalRating;
      updateStars(finalRating);
    });
  });

document.addEventListener('DOMContentLoaded', function () {
  const modal = document.getElementById('reviewModal');
  
  modal.addEventListener('show.bs.modal', function (e) {
    // Replace this with your actual PHP session check
    const isLoggedIn = <?php echo isset($_SESSION['data']) ? 'true' : 'false'; ?>;

    if (!isLoggedIn) {
      e.preventDefault(); // Prevent modal from opening
      window.location.href = '/momento/account/login.php'; // Redirect to login
    }
  });

  modal.addEventListener('shown.bs.modal', function () {
    const ratingInput = document.getElementById('ratingValue');
    const rating = parseFloat(ratingInput.value || 0);
    updateStars(rating);
  });
});



	
	
		function confirmDelete(imageId) {
			// Set the delete link with the correct image ID
			document.getElementById('deleteImageId').value = imageId;
			
			// Show the modal
			const deleteModal = new bootstrap.Modal(document.getElementById('deleteImageModal'));
			deleteModal.show();
		}
		// Prevent Enter key from submitting the form
		  document.getElementById("myText").addEventListener("keydown", function(event) {
			if (event.key === "Enter") {
			  event.preventDefault();
			}
		  });
		//TO-RESET-THE-MODAL-FOR-PROFILE-PICTURE
		document.getElementById('image-upload-btn').addEventListener('click', function () {
		  document.getElementById('uploadForm').reset();
		});
		function triggerFileInput(inputId) {
			document.getElementById(inputId).click();
		}
		//FOR-IMAGE-UPLOAD
		function previewImage(input) {
			var file = input.files[0];
			if (!file) return;

			var reader = new FileReader();
			reader.onload = function (e) {
				var fileType = input.getAttribute("data-type");

				if (fileType === "profile") {
					// Profile image preview logic (not included as you requested only the image upload modal)
				} else {
					// Get the generic image element
					var genericImg = document.getElementById('previewImg');

					// Reset the previous image and hide it
					genericImg.style.display = 'none'; // Hide the image initially
					genericImg.src = ''; // Reset the image source

					// Set the new image source and show it
					genericImg.src = e.target.result;
					genericImg.style.display = 'block'; // Show the new image

					// Show the modal
					var genericModal = new bootstrap.Modal(document.getElementById('imageModal'), {
						backdrop: 'static',
						keyboard: false
					});
					genericModal.show();

					// Clear the image and hide it when the modal is closed
					$('#imageModal').on('hidden.bs.modal', function () {
						genericImg.src = ''; // Clear the image source
						genericImg.style.display = 'none'; // Hide the image
						// Manually reset aria-hidden for the modal (fixes some issues)
						$('#imageModal').attr('aria-hidden', 'true');
					});
				}
			};

			reader.readAsDataURL(file);
		}

		function disableButtonImage() {
		  document.getElementById('image_upload_button').disabled = true;
		  var ImageInput = document.createElement('input');
		  ImageInput.type = 'hidden';
		  ImageInput.name = 'upload';
		  ImageInput.value = 'true';
		  document.getElementById('uploadForm').appendChild(ImageInput);
		  document.getElementById('uploadForm').submit();
		}

		//TO-RESET-THE-MODAL-FOR-PROFILE-PICTURE
		document.getElementById('profile-btn-upload').addEventListener('click', function () {
		  document.getElementById('profileUploadForm').reset();
		});
        //FOR-PROFILE-PICTURE
        function handleImagePreview(input, type) {
			const file = input.files[0];
            if (!file) return;
            const reader = new FileReader();
            reader.onload = function(e) {
                const previewImg = document.getElementById('profilePreviewImg');
                previewImg.src = e.target.result;
                previewImg.style.display = 'block';
                
                // Show modal
                const modal = new bootstrap.Modal(document.getElementById('profileImageModal'));
                modal.show();
            };
            reader.readAsDataURL(file);
        }
        // SAVE-PROFILE-PICTURE
        function saveProfilePicture() {
            const uploadButton = document.getElementById('profile_picture_upload_button');
            uploadButton.disabled = true;
            uploadButton.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Saving...';
            
            const form = document.getElementById('profileUploadForm');
            const hiddenInput = document.createElement('input');
            hiddenInput.type = 'hidden';
            hiddenInput.name = 'uploadProfile';
            hiddenInput.value = 'true';
            form.appendChild(hiddenInput);
            form.submit();
        }

        // SMOOTH-SCROLLING
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('a[href^="#"]').forEach(anchor => {
                anchor.addEventListener('click', function(e) {
                    const target = document.querySelector(this.getAttribute('href'));
                    if (target) {
                        e.preventDefault();
                        target.scrollIntoView({
                            behavior: 'smooth'
                        });
                    }
                });
            });
        });
		
		
		//FOR-FILTERS-SLIDERS
		// Global variables
		const filters = {
			brightness: 100,
			contrast: 100,
			grayscale: 0,
			saturate: 100
		};

		let originalImage = null;

		function triggerFileInput(inputId) {
			document.getElementById(inputId).click();
		}

		function previewImage(input) {
			const file = input.files[0];
			if (!file) return;

			const reader = new FileReader();
			reader.onload = function(e) {
				const fileType = input.getAttribute("data-type");
				
				if (fileType === "image") {
					// Store the original image data
					originalImage = new Image();
					originalImage.onload = function() {
						// Set up preview
						const previewImg = document.getElementById('previewImg');
						previewImg.style.display = 'none';
						previewImg.src = '';
						previewImg.src = e.target.result;
						previewImg.style.display = 'block';
						
						// Apply default filters
						applyFilters();
						
						// Show modal
						const imageModal = new bootstrap.Modal(document.getElementById('imageModal'), {
							backdrop: 'static',
							keyboard: false
						});
						imageModal.show();
						
						// Clean up when modal closes
						$('#imageModal').on('hidden.bs.modal', function() {
							previewImg.src = '';
							previewImg.style.display = 'none';
							$('#imageModal').attr('aria-hidden', 'true');
						});
					};
					originalImage.src = e.target.result;
				}
			};
			reader.readAsDataURL(file);
		}

		function applyFilters() {
			const previewImg = document.getElementById('previewImg');
			const filterString = `brightness(${filters.brightness}%) contrast(${filters.contrast}%) grayscale(${filters.grayscale}%) saturate(${filters.saturate}%)`;
			previewImg.style.filter = filterString;
		}

		function updateSliderValue(id, value) {
			document.getElementById(`val-${id}`).textContent = value;
		}

function processAndUploadImage() {
    // Disable the upload button
    const uploadButton = document.getElementById('image_upload_button');
    uploadButton.disabled = true;
    uploadButton.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Uploading...';
    
    // Get the canvas and context
    const canvas = document.getElementById('imageCanvas');
    const ctx = canvas.getContext('2d');
    
    // Set canvas dimensions
    canvas.width = originalImage.naturalWidth;
    canvas.height = originalImage.naturalHeight;
    
    // Apply filters to the image on canvas
    ctx.filter = `brightness(${filters.brightness}%) contrast(${filters.contrast}%) grayscale(${filters.grayscale}%) saturate(${filters.saturate}%)`;
    ctx.drawImage(originalImage, 0, 0);
    
    // Convert the canvas to a blob
    canvas.toBlob(function(blob) {
        // Create a File object from the blob
        const filteredFile = new File([blob], "filtered_image.jpg", {type: "image/jpeg"});
        
        // Create a FormData object and append the filtered image
        const formData = new FormData(document.getElementById('uploadForm'));
        
        // Replace the original file with the filtered one
        formData.delete('file');
        formData.append('file', filteredFile);
        
        // Add the upload flag
        formData.append('upload', 'true');
        
        fetch('upload.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            // Check if upload was successful
            if (data.status === 'success') {
                // Store success message in sessionStorage
                sessionStorage.setItem('uploadSuccess', 'true');
                
                // Close the modal
                const modal = bootstrap.Modal.getInstance(document.getElementById('imageModal'));
                if (modal) {
                    modal.hide();
                }
                
                // Reset the form
                document.getElementById('uploadForm').reset();
                
                // Reload the page immediately
                window.location.reload();
            } else {
                // Show error toast immediately for errors
                Toastify({
                    text: data.message || "Upload failed. Please try again.",
                    duration: 3000,
                    gravity: "top",
                    position: "right",
                    backgroundColor: "#dc3545", // Red color for error
                    stopOnFocus: true
                }).showToast();
                
                // Re-enable the button
                uploadButton.disabled = false;
                uploadButton.innerHTML = 'Upload';
            }
        })
        .catch(error => {
            console.error('Error uploading image:', error);
            
            // Show error toast immediately for network errors
            Toastify({
                text: "Network error. Please check your connection.",
                duration: 3000,
                gravity: "top",
                position: "right",
                backgroundColor: "#dc3545", // Red color for error
                stopOnFocus: true
            }).showToast();
            
            // Re-enable the button
            uploadButton.disabled = false;
            uploadButton.innerHTML = 'Upload';
        });
    }, 'image/jpeg', 0.9);
}

// Add this script to check for success message on page load:
document.addEventListener('DOMContentLoaded', function() {
    // Check if upload was successful (from sessionStorage)
    if (sessionStorage.getItem('uploadSuccess') === 'true') {
        // Remove the flag
        sessionStorage.removeItem('uploadSuccess');
        
        // Show success toast after page loads
        Toastify({
            text: "Image Uploaded Successfully!",
            duration: 3000,
            gravity: "top",
            position: "right",
            backgroundColor: "#28a745", // Green color for success
            stopOnFocus: true
        }).showToast();
    }
    
    // Your existing DOMContentLoaded code for sliders...
    // Brightness slider
    document.getElementById('brightness').addEventListener('input', function(e) {
        filters.brightness = e.target.value;
        updateSliderValue('brightness', e.target.value);
        applyFilters();
    });
    
    // Contrast slider
    document.getElementById('contrast').addEventListener('input', function(e) {
        filters.contrast = e.target.value;
        updateSliderValue('contrast', e.target.value);
        applyFilters();
    });
    
    // Grayscale slider
    document.getElementById('grayscale').addEventListener('input', function(e) {
        filters.grayscale = e.target.value;
        updateSliderValue('grayscale', e.target.value);
        applyFilters();
    });
    
    // Saturation slider
    document.getElementById('saturate').addEventListener('input', function(e) {
        filters.saturate = e.target.value;
        updateSliderValue('saturate', e.target.value);
        applyFilters();
    });
});
    </script>
</body>
</html>