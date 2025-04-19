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
/*Get user name based on account type
if ($account_type['account_type'] == 'user') {
    $profileData = $pdo->prepare('SELECT name FROM user_profiles WHERE id = ?');
    $profileData->execute([$id]);
    $name = $profileData->fetch(PDO::FETCH_ASSOC);
    $displayName = $name['name'];
} else*/ if ($account_type == 'business') {
    $profileData = $pdo->prepare('SELECT location,picture,business_name,bio FROM accounts,business_profiles WHERE accounts.id = ? and accounts.id = business_profiles.id');
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

$stmt = $pdo->prepare('SELECT url FROM images WHERE user_id = ?');
$stmt->execute([$id]);
$images = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Dancing+Script&family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Pacifico&family=Roboto:wght@300;500;700&family=Dancing+Script&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet">
    <!-- CSS -->
    <link rel="stylesheet" href="css/header.css">
    <style>
        :root {
            --primary-color: rgb(4, 18, 27);
            --secondary-color: rgb(82, 89, 95);
            --light-color: #ecf0f1;
            --dark-color: #34495e;
            --success-color: #2ecc71;
            --text-color: #333;
            --text-light: #7f8c8d;
            --shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            --border-radius: 8px;
        }

        /* Base Styles */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Roboto', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: var(--text-color);
            background-color: #f5f7fa;
        }

        .profile-container {
            max-width: 1200px;
            margin: 20px auto;
            background: white;
            border-radius: var(--border-radius);
            box-shadow: var(--shadow);
            overflow: hidden;
        }

        /* Profile Header with Integrated About Me */
        .profile-header {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            padding: 40px;
            border-radius: var(--border-radius) var(--border-radius) 0 0;
            position: relative;
        }

        .profile-header-content {
            display: flex;
            align-items: center;
            max-width: 1200px;
            margin: 0 auto;
            position: relative;
            gap: 40px;
        }

        .profile-image-section {
            display: flex;
            flex-direction: column;
            align-items: center;
            z-index: 2;
            min-width: 300px;
        }

        .profile-image-container {
            width: 200px;
            height: 200px;
            position: relative;
            margin-bottom: 20px;
        }

        .profile-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 50%;
            border: 5px solid rgba(255, 255, 255, 0.2);
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
        }

        .edit-profile-image {
            position: absolute;
            bottom: 15px;
            right: 15px;
            width: 40px;
            height: 40px;
            background-color: var(--light-color);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: black;
            cursor: pointer;
            font-size: 20px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
            border: none;
        }

        .profile-basic-info {
            text-align: center;
        }

        .profile-name {
            font-size: 2rem;
            margin-bottom: 5px;
            font-weight: 700;
        }

        .profile-title {
            font-size: 1.1rem;
            margin-bottom: 10px;
            opacity: 0.9;
        }

        .profile-location {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            font-size: 0.95rem;
            margin-bottom: 15px;
        }

        .profile-about-section {
            flex: 1;
            background: rgba(255, 255, 255, 0.1);
            padding: 30px;
            border-radius: var(--border-radius);
            backdrop-filter: blur(5px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .profile-about-section h2 {
            font-size: 1.5rem;
            margin-bottom: 15px;
            color: white;
            position: relative;
            padding-bottom: 10px;
        }

        .profile-about-section h2::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 50px;
            height: 3px;
            background-color: white;
        }

        .about-content {
            line-height: 1.7;
            margin-bottom: 20px;
        }

        .profile-social {
            display: flex;
            gap: 15px;
        }

        .social-link {
            color: white;
            font-size: 1.5rem;
            transition: transform 0.3s;
        }

        .social-link:hover {
            transform: translateY(-3px);
            color: #f8f9fa;
        }

        /* Experience Timeline Section */
        .profile-content {
            padding: 2rem;
            max-width: 1200px;
            margin: 0 auto;
        }

        .profile-columns {
            display: grid;
            grid-template-columns: 1fr;
            gap: 2rem;
        }

        .section-title {
            font-size: 1.75rem;
            color: var(--secondary-color);
            margin-bottom: 1.5rem;
            position: relative;
            padding-bottom: 0.5rem;
        }

        .section-title::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 60px;
            height: 4px;
            background: linear-gradient(90deg, var(--primary-color), #2ecc71);
            border-radius: 2px;
        }

        .timeline {
            position: relative;
            padding-left: 2.5rem;
        }

        .timeline::before {
            content: '';
            position: absolute;
            top: 0;
            left: 1rem;
            height: 100%;
            width: 3px;
            background: linear-gradient(to bottom, var(--primary-color), rgb(68, 70, 69));
            border-radius: 3px;
        }

        .timeline-item {
            position: relative;
            margin-bottom: 2.5rem;
            padding-bottom: 1.5rem;
            border-bottom: 1px solid #eee;
        }

        .timeline-item:last-child {
            margin-bottom: 0;
            padding-bottom: 0;
            border-bottom: none;
        }

        .timeline-item::before {
            content: '';
            position: absolute;
            top: 0.5rem;
            left: -2.5rem;
            width: 1.25rem;
            height: 1.25rem;
            border-radius: 50%;
            background: var(--primary-color);
            border: 3px solid white;
            box-shadow: 0 0 0 2px var(--primary-color);
            z-index: 1;
        }

        .timeline-date {
            font-size: 0.9rem;
            color: var(--text-light);
            margin-bottom: 0.5rem;
            font-weight: 500;
        }

        .timeline-content h3 {
            font-size: 1.25rem;
            color: var(--secondary-color);
            margin-bottom: 0.5rem;
        }

        .timeline-position {
            color: var(--primary-color);
            font-weight: 600;
            margin-bottom: 0.75rem;
            display: inline-block;
            background: rgba(52, 152, 219, 0.1);
            padding: 0.25rem 0.75rem;
            border-radius: 1rem;
        }

        .timeline-content p {
            color: #555;
            line-height: 1.6;
        }

        /* Image Gallery */
        .gallery-container {
			column-count: 3;
			column-gap: 10px;
		}

		.image-container {
			position: relative;
			margin-bottom: 10px;
			break-inside: avoid;
			overflow: hidden;
		}

		.gallery-image {
			width: 100%;
			height: auto;
			display: block;
			transition: all 0.3s ease;
		}

		.hover-overlay {
			position: absolute;
			top: 0;
			left: 0;
			width: 100%;
			height: 100%;
			background-color: rgba(0, 0, 0, 0.3); /* Darker overlay (0.7 opacity) */
			opacity: 0;
			transition: opacity 0.3s ease;
			display: flex;
			justify-content: center;
			align-items: center;
		}

		.image-container:hover .hover-overlay {
			opacity: 1;
		}

		.image-container:hover .gallery-image {
			filter: blur(3px); /* Add blur effect on hover */
			transform: scale(1.02); /* Optional: slight zoom effect on hover */
		}



        /* Responsive Design */
        @media (max-width: 900px) {
            .profile-header-content {
                flex-direction: column;
                text-align: center;
            }
            
            .profile-about-section {
                text-align: center;
            }
            
            .profile-about-section h2::after {
                left: 50%;
                transform: translateX(-50%);
            }
            
            .profile-social {
                justify-content: center;
            }

            .gallery-container {
                column-count: 2;
            }
        }

        @media (max-width: 768px) {
            .profile-content {
                padding: 1.5rem;
            }
            
            .profile-header {
                padding: 30px;
            }
            
            .timeline {
                padding-left: 2rem;
            }
            
            .timeline::before {
                left: 0.75rem;
            }
            
            .timeline-item::before {
                left: -2rem;
                width: 1rem;
                height: 1rem;
            }
        }

        @media (max-width: 480px) {
            .profile-header {
                padding: 20px;
            }
            
            .profile-name {
                font-size: 1.7rem;
            }
            
            .section-title {
                font-size: 1.5rem;
            }
            
            .timeline-date {
                font-size: 0.85rem;
            }
            
            .timeline-content h3 {
                font-size: 1.1rem;
            }
            
            .timeline-position {
                font-size: 0.9rem;
            }

            .gallery-container {
                column-count: 1;
				
            }
			
        }
/* Slider Container Styles */
.controls {
  margin: 20px 0;
  padding: 15px;
  background-color: #f8f9fa;
  border-radius: 8px;
  box-shadow: 0 2px 6px rgba(0,0,0,0.05);
}

/* Slider Group Styles */
.slider-group {
  margin-bottom: 15px;
  position: relative;
}

.slider-group:last-child {
  margin-bottom: 0;
}

.slider-group label {
  display: flex;
  justify-content: space-between;
  font-weight: 500;
  color: #444;
  margin-bottom: 8px;
  font-size: 14px;
}

/* Value Display */
.slider-group label span {
  background-color: #e9ecef;
  padding: 2px 8px;
  border-radius: 12px;
  font-size: 12px;
  color: #495057;
  min-width: 36px;
  text-align: center;
}

/* Custom Range Slider Styling */
.slider-group input[type=range] {
  width: 100%;
  height: 8px;
  border-radius: 5px;
  background: #ddd;
  outline: none;
  -webkit-appearance: none;
}

/* Chrome/Safari */
.slider-group input[type=range]::-webkit-slider-thumb {
  -webkit-appearance: none;
  width: 18px;
  height: 18px;
  border-radius: 50%;
  background: #3b71ca;
  cursor: pointer;
  border: 2px solid white;
  box-shadow: 0 0 2px rgba(0,0,0,0.2);
  transition: all 0.2s;
}

.slider-group input[type=range]::-webkit-slider-thumb:hover {
  background: #285192;
  transform: scale(1.1);
}

/* Firefox */
.slider-group input[type=range]::-moz-range-thumb {
  width: 18px;
  height: 18px;
  border-radius: 50%;
  background: #3b71ca;
  cursor: pointer;
  border: 2px solid white;
  box-shadow: 0 0 2px rgba(0,0,0,0.2);
  transition: all 0.2s;
}

.slider-group input[type=range]::-moz-range-thumb:hover {
  background: #285192;
  transform: scale(1.1);
}

/* Custom colors for different sliders */
#brightness::-webkit-slider-thumb {
  background: #ffa41b;
}
#brightness::-moz-range-thumb {
  background: #ffa41b;
}

#contrast::-webkit-slider-thumb {
  background: #7209b7;
}
#contrast::-moz-range-thumb {
  background: #7209b7;
}

#grayscale::-webkit-slider-thumb {
  background: #4a4e69;
}
#grayscale::-moz-range-thumb {
  background: #4a4e69;
}

#saturate::-webkit-slider-thumb {
  background: #4cc9f0;
}
#saturate::-moz-range-thumb {
  background: #4cc9f0;
}
.modal-dialog {
  width: fit-content !important;
  max-width: 100%;
  margin: 1.75rem auto; /* optional, centers vertically */
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
				 style="width: 35px; height: 35px; border-radius: 50%; background-color: #f8f9fa;
						display: flex; align-items: center; justify-content: center; 
						color: #212529; border: 1px solid #212529; text-decoration: none;">
				<i class="fas fa-comment-dots" style = "font-size:15px;"></i>
			  </a>
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
                        <p class="profile-title">Senior Software Engineer</p>
                        <div class="profile-location">
                            <i class="fas fa-map-marker-alt"></i>
                            <span><?=$profile['location']??""?></span>
                        </div>
						<?php if($isMyProfile){?>
                        <a class="btn btn-light text-dark border-dark hover-dark" style="width: 150px;" href="edit-profile.php">
							Edit Profile
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
        
        <main class="profile-content">
            <div class="profile-columns">
                <section class="profile-section experience-section">
                    <h2 class="section-title">Experience</h2>
                    <div class="timeline">
                        <div class="timeline-item">
                            <div class="timeline-date">2020 - Present</div>
                            <div class="timeline-content">
                                <h3>Tech Solutions Inc.</h3>
                                <p class="timeline-position">Senior Software Engineer</p>
                                <p>Lead development of customer portal and internal tools.</p>
                            </div>
                        </div>
                        <div class="timeline-item">
                            <div class="timeline-date">2016 - 2020</div>
                            <div class="timeline-content">
                                <h3>Digital Innovations</h3>
                                <p class="timeline-position">Software Engineer</p>
                                <p>Full-stack development of web applications.</p>
                            </div>
                        </div>
                    </div>
                </section>
                <section class="profile-section gallery-section">
                    <hr>
					

					<!--UPLOAD-IMAGE-->
					<form id="uploadForm" action="upload.php" method="POST" enctype="multipart/form-data">
					<div style = "display:flex;flex-direction:row;justify-content: space-between;">
					  <h2 class="section-title">Gallery</h2>
					  <input type="file" id="file" class="d-none" accept="image/*" onchange="previewImage(this)" data-type="image" required>
					  <?php if($isMyProfile){?>
					  <button id = "image-upload-btn" type="button" class="btn btn-light text-dark border-dark hover-dark" onclick="triggerFileInput('file');" style="width:50px;height:40px;"><i class="fas fa-upload"></i></button>
					  <?php }?>
					  <canvas id="imageCanvas" style="display:none;"></canvas>
						  <!-- Modal -->
						  <div class="modal fade" id="imageModal" tabindex="-1" aria-labelledby="imageModalLabel" aria-hidden="true">
							<div class="modal-dialog">
							  <div class="modal-content">
								<div class="modal-header">
								  <h5 class="modal-title" id="imageModalLabel">Image Preview</h5>
								  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
								</div>
								<div class="modal-body text-center" style="margin: auto;display:flex;flex-direction:row;align-items:center;">
								  <img id="previewImg" src="#" class="img-fluid rounded" style="max-height: 300px; display: none;">
								  <!--CONTROL-IMAGE-->
								  <div class="controls" style = "">
									<div class="slider-group">
									  <label for="brightness">Brightness <span id="val-brightness">100</span></label>
									  <input type="range" id="brightness" min="0" max="200" value="100">
									</div>
									<div class="slider-group">
									  <label for="contrast">Contrast <span id="val-contrast">100</span></label>
									  <input type="range" id="contrast" min="0" max="200" value="100">
									</div>
									<div class="slider-group">
									  <label for="grayscale">Grayscale <span id="val-grayscale">0</span></label>
									  <input type="range" id="grayscale" min="0" max="100" value="0">
									</div>
									<div class="slider-group">
									  <label for="saturate">Saturation <span id="val-saturate">100</span></label>
									  <input type="range" id="saturate" min="0" max="200" value="100">
									</div>
								  </div>
								</div>
								<div class="modal-body text-center">
								  <input type="text" placeholder="Description" name="description" class="form-control form-control-sm" style="border:solid black 1px;">
								</div>  
								<div class="modal-footer">
								  <button id="image_upload_button" type="button" class="btn btn-light text-dark border-dark hover-dark" onclick="processAndUploadImage()">Upload</button>
								</div>
							  </div>
							</div>
						  </div>
						</form>
					</div>
					<div class="gallery-container">
						<?php foreach($images as $image): ?>
							<?php 
							$image_url = $image["url"];
							if (is_valid_image($image_url)): 
							?>  
							<div class="image-container">
								<img src="<?= htmlspecialchars($image_url) ?>" class="gallery-image" alt="Gallery Image">
								<div class="hover-overlay"></div>
							</div>
							<?php endif; ?>
						<?php endforeach; ?>
					</div>
                </section>
            </div>
        </main>
    </div>

    <!-- Profile Image Modal -->
    <div class="modal fade" id="profileImageModal" tabindex="-1" aria-labelledby="profileImageModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="profileImageModalLabel">Profile Picture Preview</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center" style = "margin:auto;">
                    <img id="profilePreviewImg" src="#" class="img-fluid centered rounded-circle" style="width:140px; height:140px; object-fit:cover; display:none;">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button id="profile_picture_upload_button" type="button" class="btn btn-primary" onclick="saveProfilePicture()">Save Profile Picture</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" 
    integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    
    <script>
	
	
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
			document.getElementById('image_upload_button').disabled = true;
			
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
				
				// Use fetch API to upload the image
				fetch('upload.php', {
					method: 'POST',
					body: formData
				})
				.then(response => {
					// Redirect to profile page after successful upload
					window.location.href = '/momento/profile.php?success=1';
				})
				.catch(error => {
					console.error('Error uploading image:', error);
					alert('Failed to upload image. Please try again.');
					document.getElementById('image_upload_button').disabled = false;
				});
			}, 'image/jpeg', 0.9);
		}

		// Set up event listeners when the DOM is loaded
		document.addEventListener('DOMContentLoaded', function() {
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