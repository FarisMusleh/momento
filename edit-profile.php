<?php 
	session_start(); 
	require('pdo.php');
	if(isset($_SESSION['data'])){
		$data = $_SESSION['data'];
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
            font-weight: 500;
            font-size: 16px;
            margin-right: 20px;
            transition: color 0.3s ease;
        }
        .nav-links a:hover, .nav-item .nav-link:hover {
            color: #212529 !important;
        }
        .btn-action {
            background-color: #212529 !important;
            color: white !important;
            border: none;
            position: absolute;
            bottom: 20px;
            right: 20px;
            padding: 10px 15px;
            border-radius: 5px;
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
            background-color: #212529;
            padding: 20px;
            border-radius: 5px;
            color: #FFFFFF;
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
            alert("Your account has been deleted.");
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
    <div class="container mt-5">
        <div class="row">
            <div class="col-md-3">
                <ul class="nav flex-column">
                    <li class="nav-item"><a href="#" class="nav-link active-link" onclick="showSection('general', 'General', this)">General</a></li>
                    <li class="nav-item"><a href="#" class="nav-link" onclick="showSection('edit-profile', 'Edit Profile', this)">Edit Profile</a></li>
                    <li class="nav-item"><a href="#" class="nav-link" onclick="showSection('password', 'Password', this)">Password</a></li>
                    <li class="nav-item"><a href="#" class="nav-link" onclick="showSection('social-profiles', 'Social Profiles', this)">Social Profiles</a></li>
                </ul>

                <!-- Horizontal line -->
                <hr>

                <!-- Delete Account Button -->
                <button class="btn text-danger ps-0" style="background: none; border: none;" onclick="confirmDelete()">Delete Account</button>
            </div>

            <div class="col-md-9">
                <h3 id="section-title">General</h3>

                <div id="general" class="content-section">
                    <div class="mb-3">
                        <label class="form-label">Username</label>
                        <input type="text" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" class="form-control">
                    </div>
                    <p class="success-message" style="color: lightgreen; display: none;">Changes successfully saved!</p>
                    <button class="btn btn-action" onclick="saveChanges('General')">Save Changes</button>
                </div>

                <div id="edit-profile" class="content-section" style="display: none;">
                    <div class="mb-3">
                        <label class="form-label">Name</label>
                        <input type="text" class="form-control" value="Qusai Odeh">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Location</label>
                        <input type="text" class="form-control" value="Jordan">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Bio</label>
                        <textarea class="form-control" rows="3"></textarea>
                    </div>
                    <p class="success-message" style="color: lightgreen; display: none;">Changes successfully saved!</p>
                    <button class="btn btn-action" onclick="saveChanges('Edit Profile')">Save Changes</button>
                </div>

                <div id="password" class="content-section" style="display: none;">
                    <div class="mb-3">
                        <label class="form-label">Old Password</label>
                        <input type="password" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">New Password</label>
                        <input type="password" class="form-control" id="new-password">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Confirm Password</label>
                        <input type="password" class="form-control" id="confirm-password">
                    </div>
                    <button class="btn btn-action" onclick="changePassword()">Change Password</button>
                    <p id="password-success" style="color: lightgreen; display: none;">Password changed successfully!</p>
                </div>

                <!-- Social Profiles Section -->
                <div id="social-profiles" class="content-section" style="display: none;">
                    <div class="mb-3">
                        <label class="form-label">Facebook</label>
                        <input type="url" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Twitter</label>
                        <input type="url" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">LinkedIn</label>
                        <input type="url" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Instagram</label>
                        <input type="url" class="form-control">
                    </div>
                    <p class="success-message" style="color: lightgreen; display: none;">Changes successfully saved!</p>
                    <button class="btn btn-action" onclick="saveChanges('Social Profiles')">Save Changes</button>
                </div>
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
                    <button type="button" class="btn btn-danger" onclick="deleteAccount()">Delete Account</button>
                </div>
            </div>
        </div>
    </div>

</body>
</html>