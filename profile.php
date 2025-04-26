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

$stmt = $pdo->prepare('SELECT url,id FROM images WHERE user_id = ?');
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
	<link rel="stylesheet" href="css/profile.css">
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
								  <input type="text" id = "myText" placeholder="Description" name="description" class="form-control form-control-sm" style="border:solid black 1px;">
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
							?>  
							<div class="image-container">
								<img src="<?= htmlspecialchars($image_url) ?>" class="gallery-image" alt="Gallery Image">
								<div class="hover-overlay">
									<a href = "download_handler.php/?imageId=<?=$image['id']?>" class="dots-menu btn btn-light"><i class = " fas fa-download"></i></a>
									<?php if($isMyProfile): ?>
										<button type="button" class="dots-menu btn btn-danger" onclick="confirmDelete(<?=$image['id']?>)">
											<i class="fas fa-trash"></i>
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