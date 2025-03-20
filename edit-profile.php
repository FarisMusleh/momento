<?php 
	session_start(); 
	require('pdo.php');
	if(isset($_SESSION['data'])){
		$data = $_SESSION['data'];
	}else{
		exit();
	}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
    <style>
        body {
            background-color: #EDEDED;
            color: #212529;
        }
        .navbar {
            background-color: white;
            padding: 10px 20px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            position: relative;
        }
        .nav-links a, .nav-item .nav-link {
            color: #777 !important;
            text-decoration: none;
            font-weight: 400;
            font-size: 16px;
            margin-right: 20px;
            transition: color 0.3s ease;
        }
        .nav-links a:hover, .nav-item .nav-link:hover {
            color: #212529 !important;
        }
        .btn-action {
            position: absolute;
            bottom: 20px;
            right: 10px;
            padding: 10px 15px;
            width: auto;
        }
        .btn-action:hover {
            opacity: 0.8;
        }
        .search-bar {
            display: flex;
            align-items: center;
            width: 300px;
            position: relative;
        }
        .search-bar input {
            border: 1px solid #ddd;
            border-radius: 20px;
            padding: 8px 35px 8px 15px;
            width: 100%;
            outline: none;
            font-size: 16px;
            transition: all 0.3s ease;
        }
        .search-bar input:focus {
            border-color: rgb(64, 65, 66);
            box-shadow: 0 2px 10px rgba(0, 123, 255, 0.25);
        }
        .search-icon {
            position: absolute;
            right: 10px;
            cursor: pointer;
            color: #777;
        }
        .profile-img {
            width: 40px;
            height: 40px;
            border-radius: 50%;
        }
        .content-section {
            background-color: white;
            border-radius: 5px;
            color: #000;
            position: relative;
            padding-bottom: 60px;
        }
        .nav-item .nav-link.active-link {
            color: #343a40 !important;
            font-weight: bold;
        }
        .search-container {
            position: relative;
            max-width: 600px;
            margin: 0 auto;
        }
        .search-input {
            width: 100%;
            padding: 12px 20px;
            border: 2px solid #ddd;
            border-radius: 30px;
            font-size: 16px;
            transition: all 0.3s ease;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
        .search-input:focus {
            border-color: rgb(64, 65, 66);
            box-shadow: 0 2px 10px rgba(0, 123, 255, 0.25);
            outline: none;
        }
        input[type="text"], input[type="email"], input[type="password"], .search-bar input, textarea {
            width: 100%;
            padding: 12px 20px;
            border: 2px solid #ddd;
            border-radius: 30px;
            font-size: 16px;
            transition: all 0.3s ease;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
        input[type="text"]:focus, input[type="email"]:focus, input[type="password"]:focus, .search-bar input:focus, textarea:focus {
            border-color: rgb(64, 65, 66);
            box-shadow: 0 2px 10px rgba(0, 123, 255, 0.25);
            outline: none;
        }
        /* Hover effect for inputs */
        input[type="url"]:hover, input[type="text"]:hover, input[type="email"]:hover, input[type="password"]:hover, textarea:hover {
            border-color: rgb(64, 65, 66);
            box-shadow: 0 2px 10px rgba(0, 123, 255, 0.25);
        }
        /* Custom Modal Styles */
        .modal-content {
            border-radius: 10px;
            border: none;
        }
        .modal-header {
            background-color: #dc3545;
            color: white;
            border-top-left-radius: 10px;
            border-top-right-radius: 10px;
        }
        .modal-footer {
			
            border-bottom-left-radius: 10px;
            border-bottom-right-radius: 10px;
        }
        .modal-footer .btn-danger {
            background-color: #dc3545;
            border: none;
        }
        .modal-footer .btn-secondary {
            background-color: #6c757d;
            border: none;
        }
        /* Profile Picture in Navbar */
        .navbar-profile-img {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            position: absolute;
            right: 20px;
            top: 50%;
            transform: translateY(-50%);
        }
        /* Enhanced Navbar */
        .navbar-brand {
            font-size: 24px;
            font-weight: bold;
            color: #212529 !important;
        }
        .nav-links {
            display: flex;
            align-items: center;
        }
        .nav-links a {
            padding: 8px 12px;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }
        .nav-links a:hover {
            background-color: #f8f9fa;
        }
		
	  .hover-dark:hover {
		background-color: black !important;
		color: white !important;
		border-color: black !important;
		transition: all 0.3s ease !important;
  }
  .hover-dark{
	  font-size: 14px !important;
	  font-weight: 500 !important;
	  text-align: center !important;
	  transition: all 0.3s ease !important;
	  
  }	
    </style>
    <script>
        function showSection(sectionId, title, element) {
            document.querySelectorAll(".content-section").forEach(section => {
                section.style.display = "none";
            });
            document.getElementById(sectionId).style.display = "block";
            document.getElementById("section-title").innerText = title;
            document.querySelectorAll(".nav-item .nav-link").forEach(link => {
                link.classList.remove("active-link");
            });
            element.classList.add("active-link");
        }

        function saveChanges(section) {
            const sectionDiv = document.getElementById(section.toLowerCase().replace(" ", "-"));
            const message = sectionDiv.querySelector(".success-message");
            message.style.display = "block";
            setTimeout(() => {
                message.style.display = "none";
            }, 3000);
        }

        function changePassword() {
            const newPassword = document.getElementById("new-password").value;
            const confirmPassword = document.getElementById("confirm-password").value;
            const successMessage = document.getElementById("password-success");
            const errorMessage = document.createElement('p'); // Create error message element

            // Clear any previous error message
            document.querySelectorAll('.error-message').forEach((msg) => msg.remove());

            if (newPassword === "" || confirmPassword === "") {
                // Display message if any field is empty
                errorMessage.textContent = "Both password fields must be filled.";
                errorMessage.classList.add('error-message');
                errorMessage.style.color = 'red';
                document.getElementById("password").appendChild(errorMessage);
            } else if (newPassword !== confirmPassword) {
                // Display message if passwords do not match
                errorMessage.textContent = "Password and confirm password must be the same.";
                errorMessage.classList.add('error-message');
                errorMessage.style.color = 'red';
                document.getElementById("password").appendChild(errorMessage);
            } else {
                // Show success message if passwords match
                successMessage.style.display = "block";
                setTimeout(() => {
                    successMessage.style.display = "none";
                }, 4000);
            }
        }

        function confirmDelete() {
            const deleteModal = new bootstrap.Modal(document.getElementById('deleteAccountModal'));
            deleteModal.show();
        }

        function deleteAccount() {
            // Logic for account deletion, e.g., making an API call to delete the account
            const deleteModal = bootstrap.Modal.getInstance(document.getElementById('deleteAccountModal'));
            deleteModal.hide();
        }
    </script>
</head>
<body>

    <!-- Navigation Bar -->
    <?php
		require("header.php");
	?>
	
    <!-- Main Content -->
    <div class="container mt-5 col-md-6">
		<div style = "margin-left:10px;margin-bottom:20px;display:flex;flex-direction:row;align-items: center;font-size:21px;">
		<div style = "position:relative;">
		<img src = "<?=$data['picture']?>" width = "55" height = "55" style = "object-fit: cover;border-radius:100%;height:55px;width:55px;margin-right:20px;">
		<form id="profileUploadForm" action="upload-profile-img.php" method="POST" enctype="multipart/form-data">
			<input type="file" name="profileFile" id="profileFile" class="d-none" accept="image/*" onchange="previewImage(this)" data-type="profile">
			<button type="button" style = "position:absolute;font-size:14px;border-radius:50%;top:35px;right:20px;width:25px;height:25px;padding: 0;" class="btn btn-dark" onclick="triggerFileInput('profileFile');">+</button>
			
			<!-- Modal -->
			<div class="modal fade" id="profileImageModal" tabindex="-1" aria-labelledby="profileImageModalLabel" aria-hidden="true">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header">
							<h5 class="modal-title" id="profileImageModalLabel">Profile Picture Preview</h5>
							<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
						</div>
						<div class="modal-body text-center"   style = "margin: auto;">
							<img id="profilePreviewImg" src="#" class="card-img-top border-0" style="display: none;border-radius:100%;width:140px;height:140;object-fit: cover;" height = "140" width = "140">
						</div>
						<div class="modal-footer">
							<button id = "profile_picture_upload_button" type="submit" name="uploadProfile" class="btn btn-light text-dark border-dark hover-dark" onclick="disableButtonProfilePicture(this)">Save Profile Picture</button>
						</div>
					</div>
				</div>
			</div>
		</form>
		
		</div>
		<div style = "text-transform: capitalize;">
		<?php
		$stmt = $pdo->prepare('select account_type from accounts where id = ?');
		$stmt->execute(array($_SESSION['data']['id']));
		$account_type = $stmt->fetch(PDO::FETCH_ASSOC);
		$name = null;
		if($account_type['account_type']=='user'){
			$name = $_SESSION['data']['name'];
			echo $_SESSION['data']['name'];
		}elseif($account_type['account_type']=='business'){
			$name = $_SESSION['data']['business_name'];
			echo $_SESSION['data']['business_name'];
		}
		?>
		</div>
		<div style = "margin:0 10px;opacity:0.3;">/</div>
		<div id="section-title">General</div>

		</div>
		
        <div class="row">
            <div class="col-md-3">
                <ul class="nav flex-column">
                    <li class="nav-item"><a href="#" class="nav-link active-link" onclick="showSection('general', 'General', this)">General</a></li>
                    <li class="nav-item"><a href="#" class="nav-link " onclick="showSection('edit-profile', 'Edit Profile', this)">Edit Profile</a></li>
                    <li class="nav-item"><a href="#" class="nav-link " onclick="showSection('password', 'Password', this)">Password</a></li>
                    <li class="nav-item"><a href="#" class="nav-link " onclick="showSection('social-profiles', 'Social Profiles', this)">Social Profiles</a></li>
					<!-- Delete Account Button -->
					<li class="nav-item"><button class="nav-link text-danger" style="background: none; border: none;white-space: nowrap;overflow: hidden;text-overflow: ellipsis;color:red !important;" onclick="confirmDelete()">Delete Account</button></li>
				</ul>

                

                
            </div>
            <div class="col-md-8" style = "font-weight:500;font-size:17px;">
                
				<form action = "update-profile.php" method = "POST">
					<?php
						$stmt = $pdo->prepare('select username,email,location,account_type from accounts where id = ?');
						$stmt->execute([$data['id']]);
						$result = $stmt->fetch(PDO::FETCH_ASSOC);
						
						if($result['account_type']=="user"){
							$sql = $pdo->prepare('select name,social_links,bio from user_profiles where id = ?');
							$sql->execute([$data['id']]);
							$info = $sql->fetch(PDO::FETCH_ASSOC);
							$name = $info['name'];
							$bio = $info['bio'];
							$social_links = json_decode($info['social_links'], true);
							
						}elseif($result['account_type']=="business"){
							$sql = $pdo->prepare('select business_name,social_links,bio from business_profiles where id = ?');
							$sql->execute([$data['id']]);
							$info = $sql->fetch(PDO::FETCH_ASSOC);
							$name = $info['business_name'];
							$bio = $info['bio'];
							$social_links = json_decode($info['social_links'], true);
						}
					?>
					<input type="hidden" name="section" value="general">
					<div id="general" class="content-section" >
						<div class="mb-3" >
							<label class="form-label">Username</label>
							<input name = "username" type="text" class="form-control" value = "<?=$result['username']??""?>">
						</div>
						<div class="mb-3">
							<label class="form-label">Email</label>
							<input name = "email" type="email" class="form-control" value = "<?=$result['email']??""?>">
						</div>
						<p class="success-message" style="color: lightgreen; display: none;">Changes successfully saved!</p>
						<button type = "submit" class="btn btn-action btn-light text-dark border-dark hover-dark" onclick="saveChanges('General')">Save Changes</button>
					</div>
				</form>	
				
				<form action = "update-profile.php" method = "POST">
					<input type="hidden" name="section" value="edit">
					<div id="edit-profile" class="content-section" style="display: none;">
						<div class="mb-3">
							<label class="form-label">Name</label>
							<input name = "name" type="text" class="form-control" value="<?=$name?>">
						</div>
						<div class="mb-3">
							<label class="form-label">Location</label>
							<input name = "location" type="text" class="form-control" value="<?=$result['location']??""?>">
						</div>
						<div class="mb-3">
							<label class="form-label">Bio</label>
							<textarea name = "bio" class="form-control" rows="3" value = "<?=$bio?>"></textarea>
						</div>
						<p class="success-message" style="color: lightgreen; display: none;">Changes successfully saved!</p>
						<button type = "submit" class="btn btn-action btn-light text-dark border-dark  hover-dark" onclick="saveChanges('Edit Profile')">Save Changes</button>
					</div>
				</form>
				
				<form action = "update-profile.php" method = "POST">
					<input type="hidden" name="section" value="password">
					<div id="password" class="content-section" style="display: none;">
						<div class="mb-3">
							<label class="form-label">Old Password</label>
							<input name = "old_password" type="password" class="form-control">
						</div>
						<div class="mb-3">
							<label class="form-label">New Password</label>
							<input name = "new_password" type="password" class="form-control" id="new-password">
						</div>
						<div class="mb-3">
							<label class="form-label">Confirm Password</label>
							<input type="password" class="form-control" id="confirm-password">
						</div>
						<button type = "submit" class="btn btn-action" onclick="changePassword()">Change Password</button>
						<p id="password-success" style="color: lightgreen; display: none;">Password changed successfully!</p>
					</div>
				</form>
				
                <!-- Social Profiles Section -->
				<form action = "update-profile.php" method = "POST">
					<input type="hidden" name="section" value="socials">
					<div id="social-profiles" class="content-section" style="display: none;">
						<div class="mb-3">
							<label class="form-label">Facebook</label>
							<input name = "facebook" type="url" class="form-control" value = "<?=$social_links['facebook']??""?>">
						</div>
						<div class="mb-3">
							<label class="form-label">Twitter</label>
							<input name = "twitter" type="url" class="form-control" value = "<?=$social_links['twitter']??""?>">
						</div>
						<div class="mb-3">
							<label class="form-label">LinkedIn</label>
							<input name = "linked-in" type="url" class="form-control" value = "<?=$social_links['linked-in']??""?>">
						</div>
						<div class="mb-3">
							<label class="form-label">Instagram</label>
							<input name = "instagram" type="url" class="form-control" value = "<?=$social_links['instagram']??""?>">
						</div>
						<p class="success-message" style="color: lightgreen; display: none;">Changes successfully saved!</p>
						<button type = "submit" class="btn btn-action" style = "" onclick="saveChanges('Social Profiles')">Save Changes</button>
					</div>
				</form>
            </div>
        </div>
    </div>

    <!-- Delete Account Modal -->
    <div class="modal fade" id="deleteAccountModal" tabindex="-1" aria-labelledby="deleteAccountModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteAccountModalLabel">Delete Account</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to delete your account? This action cannot be undone.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
					<form action = "delete-account.php" method = "POST">
                    <button type="submit" class="btn btn-danger" onclick="deleteAccount()">Delete Account</button>
					</form>
                </div>
            </div>
        </div>
    </div>

</body>
<script>
function triggerFileInput(inputId) {
    document.getElementById(inputId).click();
}

function previewImage(input) {
    var file = input.files[0];
    if (!file) return;

    var reader = new FileReader();
    reader.onload = function (e) {
        var fileType = input.getAttribute("data-type");

        if (fileType === "profile") {
            // Get the profile image element
            var profileImg = document.getElementById('profilePreviewImg');

            // Reset the previous image and hide it
            profileImg.style.display = 'none'; // Hide the image initially
            profileImg.src = ''; // Reset the image source

            // Set the new image source and show it
            profileImg.src = e.target.result;
            profileImg.style.display = 'block'; // Show the new image

            // Show the modal
            var profileModal = new bootstrap.Modal(document.getElementById('profileImageModal'), {
                backdrop: 'static',
                keyboard: false
            });
            profileModal.show();

            // Clear the image and hide it when the modal is closed
            $('#profileImageModal').on('hidden.bs.modal', function () {
                profileImg.src = ''; // Clear the image source
                profileImg.style.display = 'none'; // Hide the image
                // Manually reset aria-hidden for the modal (fixes some issues)
                $('#profileImageModal').attr('aria-hidden', 'true');
            });

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
</script>

</html>