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
    <title>Profile Settings</title>
    <style>
        :root {
            --primary-color: #3498db;
            --secondary-color: #2c3e50;
            --accent-color: #e74c3c;
            --light-gray: #f8f9fa;
            --medium-gray: #e9ecef;
            --dark-gray: #6c757d;
            --text-color: #212529;
            --border-radius: 8px;
            --box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
            --transition: all 0.3s ease;
        }
        
        body {
            background-color: #f5f7fa;
            color: var(--text-color);
            font-family: 'Roboto', sans-serif;
            line-height: 1.6;
        }
        
        .container-main {
            max-width: 1200px;
            margin: 40px auto;
            padding: 0 20px;
        }
        
        .profile-header {
            display: flex;
            align-items: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 1px solid var(--medium-gray);
        }
        
        .profile-avatar-container {
            position: relative;
            margin-right: 25px;
        }
        
        .profile-avatar {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid white;
            box-shadow: var(--box-shadow);
        }
        
        .avatar-upload-btn {
            position: absolute;
            bottom: 0;
            right: 0;
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: var(--primary-color);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            border: 2px solid white;
            transition: var(--transition);
        }
        
        .avatar-upload-btn:hover {
            background: var(--secondary-color);
            transform: scale(1.1);
        }
        
        .profile-title {
            font-size: 24px;
            font-weight: 600;
            margin: 0;
            color: var(--secondary-color);
        }
        
        .profile-breadcrumb {
            display: flex;
            align-items: center;
            font-size: 16px;
            color: var(--dark-gray);
        }
        
        .breadcrumb-separator {
            margin: 0 10px;
            color: var(--medium-gray);
        }
        
        .settings-container {
            display: flex;
            background: white;
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
            overflow: hidden;
        }
        
        .settings-sidebar {
            width: 250px;
            background: var(--secondary-color);
            color: white;
            padding: 25px 0;
        }
        
        .settings-nav {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        
        .settings-nav-item {
            margin-bottom: 5px;
        }
        
        .settings-nav-link {
            display: block;
            padding: 12px 25px;
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
            font-weight: 500;
            transition: var(--transition);
            border-left: 3px solid transparent;
        }
        
        .settings-nav-link:hover, 
        .settings-nav-link.active {
            background: rgba(255, 255, 255, 0.1);
            color: white;
            border-left: 3px solid var(--primary-color);
        }
        
        .settings-nav-link.active {
            font-weight: 600;
        }
        
        .settings-content {
            flex: 1;
            padding: 30px;
        }
        
        .section-title {
            font-size: 20px;
            font-weight: 600;
            margin-bottom: 25px;
            color: var(--secondary-color);
            padding-bottom: 10px;
            border-bottom: 1px solid var(--medium-gray);
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: var(--secondary-color);
        }
        
        .form-control {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid var(--medium-gray);
            border-radius: var(--border-radius);
            font-size: 15px;
            transition: var(--transition);
            background-color: var(--light-gray);
        }
        
        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.2);
            outline: none;
            background-color: white;
        }
        
        textarea.form-control {
            min-height: 120px;
            resize: vertical;
        }
        
        .btn {
            padding: 10px 20px;
            border-radius: var(--border-radius);
            font-weight: 500;
            cursor: pointer;
            transition: var(--transition);
            border: none;
        }
        
        .btn-primary {
            background-color: var(--primary-color);
            color: white;
        }
        
        .btn-primary:hover {
            background-color: #2980b9;
            transform: translateY(-1px);
        }
        
        .btn-danger {
            background-color: var(--accent-color);
            color: white;
        }
        
        .btn-danger:hover {
            background-color: #c0392b;
        }
        
        .btn-outline {
            background: transparent;
            border: 1px solid var(--medium-gray);
            color: var(--dark-gray);
        }
        
        .btn-outline:hover {
            background: var(--light-gray);
        }
        
        .alert {
            padding: 12px 15px;
            border-radius: var(--border-radius);
            margin-bottom: 20px;
            font-size: 14px;
        }
        
        .alert-success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        
        .alert-danger {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        
        .delete-account {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid var(--medium-gray);
        }
        
        .delete-account-title {
            color: var(--accent-color);
            margin-bottom: 15px;
        }
        
        .delete-account-text {
            color: var(--dark-gray);
            margin-bottom: 20px;
        }
        
        /* Modal styles */
        .modal-content {
            border-radius: var(--border-radius);
            border: none;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.15);
        }
        
        .modal-header {
            background-color: var(--secondary-color);
            color: white;
            border-top-left-radius: var(--border-radius);
            border-top-right-radius: var(--border-radius);
            padding: 15px 20px;
        }
        
        .modal-title {
            font-weight: 600;
        }
        
        .modal-body {
            padding: 25px;
        }
        
        .modal-footer {
            border-top: 1px solid var(--medium-gray);
            padding: 15px 20px;
        }
        
        /* Responsive adjustments */
        @media (max-width: 768px) {
            .settings-container {
                flex-direction: column;
            }
            
            .settings-sidebar {
                width: 100%;
                padding: 15px 0;
            }
            
            .settings-nav {
                display: flex;
                overflow-x: auto;
                padding: 0 15px;
            }
            
            .settings-nav-item {
                margin-bottom: 0;
                margin-right: 10px;
                white-space: nowrap;
            }
            
            .settings-nav-link {
                border-left: none;
                border-bottom: 3px solid transparent;
                padding: 10px 15px;
            }
            
            .settings-nav-link.active {
                border-left: none;
                border-bottom: 3px solid var(--primary-color);
            }
            
            .profile-header {
                flex-direction: column;
                text-align: center;
            }
            
            .profile-avatar-container {
                margin-right: 0;
                margin-bottom: 15px;
            }
        }

 .form-card {
        background-color: #f8f9fa;
        transition: all 0.3s ease;
    }
    .form-card:hover {
        background-color: #fff;
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.05);
    }
    .section-header {
        border-bottom: 1px solid #eaeaea;
        padding-bottom: 1rem;
    }
    .section-title {
        font-weight: 600;
        color: #2c3e50;
    }
    .section-subtitle {
        font-size: 0.9rem;
    }
    .form-control, .form-select, .form-check-input {
        border-radius: 0.375rem;
    }
    .form-floating>label {
        color: #6c757d;
    }
    .btn {
        border-radius: 0.375rem;
        font-weight: 500;
    }
    .alert {
        border-radius: 0.5rem;
    }
    </style>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="css/header.css">
    
    <!-- GOOGLE FONTS -->
    <link href="https://fonts.googleapis.com/css2?family=Dancing+Script&family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Pacifico&family=Roboto:wght@300;500;700&family=Dancing+Script&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet">

</head>
<body>
    <!-- Navigation Bar -->
    
    <?php require('header.php'); ?>
    <!-- Main Content -->
    <div class="container-main p-3">
       
        <div class="profile-header">
            <div class="profile-avatar-container">
                <img src="<?=$data['picture']?>" class="profile-avatar" alt="Profile Picture">
                <form id="profileUploadForm" action="upload-profile-img.php" method="POST" enctype="multipart/form-data">
                    <input type="file" name="profileFile" id="profileFile" class="d-none" accept="image/*" onchange="previewImage(this)" data-type="profile">
                    <div class="avatar-upload-btn" onclick="triggerFileInput('profileFile');">
                        <i class="fas fa-camera"></i>
                    </div>
                    
                    <!-- Modal -->
                    <div class="modal fade" id="profileImageModal" tabindex="-1" aria-labelledby="profileImageModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="profileImageModalLabel">Profile Picture Preview</h5>
                                    <button id = "profile-upload-btn" type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body text-center">
                                    <img id="profilePreviewImg" src="#" class="img-fluid rounded-circle" style="display: none;width:140px; height:140px; object-fit:cover; margin:auto;">
                                </div>
                                <div class="modal-footer">
                                    <button id = "profile-upload-btn-cancel" type="button" class="btn btn-outline" data-bs-dismiss="modal">Cancel</button>
                                    <button id="profile_picture_upload_button" type="submit" name="uploadProfile" class="btn btn-primary" onclick="disableButtonProfilePicture(this)">
                                        <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                                        Save Changes
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            
            <div>
                <h1 class="profile-title">
                    <?php
                        $stmt = $pdo->prepare('SELECT account_type FROM accounts WHERE id = ?');
                        $stmt->execute(array($_SESSION['data']['id']));
                        $account_type = $stmt->fetch(PDO::FETCH_ASSOC);
                        if($account_type['account_type']=='user'){
                            $profileData = $pdo->prepare('SELECT name FROM user_profiles WHERE id = ?');
                            $profileData->execute([$_SESSION['data']['id']]);
                            $name = $profileData->fetch(PDO::FETCH_ASSOC);
                            echo htmlspecialchars($name['name']??null);
                        }elseif($account_type['account_type']=='business'){
                            $profileData = $pdo->prepare('SELECT business_name FROM business_profiles WHERE id = ?');
                            $profileData->execute([$_SESSION['data']['id']]);
                            $name = $profileData->fetch(PDO::FETCH_ASSOC);
                            echo htmlspecialchars($name['business_name']);
                        }
                    ?>
                </h1>
                <div class="profile-breadcrumb">
                    <span>Account</span>
                    <span class="breadcrumb-separator">/</span>
                    <span id="section-title">General Settings</span>
                </div>
            </div>
        </div>
        
        <div class="settings-container">
            <div class="settings-sidebar">
                <ul class="settings-nav">
                    <li class="settings-nav-item">
                        <a href="#" class="settings-nav-link active" onclick="showSection('general', 'General Settings', this)">
                            <i class="fas fa-cog me-2"></i>General
                        </a>
                    </li>
                    <li class="settings-nav-item">
                        <a href="#" class="settings-nav-link" onclick="showSection('edit-profile', 'Profile Settings', this)">
                            <i class="fas fa-user-edit me-2"></i>Edit Profile
                        </a>
                    </li>
                    
                    <?php 
                    $stmt = $pdo->prepare('SELECT provider FROM accounts WHERE id = ?');
                    $stmt->execute([$data['id']]);
                    $result = $stmt->fetch();
                    if($result['provider']=="local"){?>
                        <li class="settings-nav-item">
                            <a href="#" class="settings-nav-link" onclick="showSection('password', 'Password Settings', this)">
                                <i class="fas fa-lock me-2"></i>Password
                            </a>
                        </li>
                    <?php } ?>
                    
                    <li class="settings-nav-item">
                        <a href="#" class="settings-nav-link" onclick="showSection('social-profiles', 'Social Profiles', this)">
                            <i class="fas fa-share-alt me-2"></i>Social Profiles
                        </a>
                    </li>
                    <li class="settings-nav-item">
                        <a href="#" class="settings-nav-link" onclick="showSection('Experience', 'Experience', this)">
                            <i class="fas fa-briefcase me-2"></i>Experience
                        </a>
                    </li>
                </ul>
            </div>
            
            <div class="settings-content">
                <?php
                    $stmt = $pdo->prepare('SELECT username, email, location, account_type FROM accounts WHERE id = ?');
                    $stmt->execute([$data['id']]);
                    $result = $stmt->fetch(PDO::FETCH_ASSOC);
                    
                    if($result['account_type']=="user"){
                        $sql = $pdo->prepare('SELECT name, social_links, bio FROM user_profiles WHERE id = ?');
                        $sql->execute([$data['id']]);
                        $info = $sql->fetch(PDO::FETCH_ASSOC);
                        $name = $info['name']??null;
                        $bio = $info['bio']??null;
                        $social_links = json_decode($info['social_links']??null, true);
                    }elseif($result['account_type']=="business"){
                        $sql = $pdo->prepare('SELECT business_name, social_links, bio FROM business_profiles WHERE id = ?');
                        $sql->execute([$data['id']]);
                        $info = $sql->fetch(PDO::FETCH_ASSOC);
                        $name = $info['business_name'];
                        $bio = $info['bio'];
                        $social_links = json_decode($info['social_links']??null, true);
                    }
                ?>
                
                <!-- General Settings Section -->
                <form action="update-profile.php" method="POST">
                    <input type="hidden" name="section" value="general">
                    <div id="general" class="content-section">
                        <h2 class="section-title">General Settings</h2>
                        
                        <div class="form-group">
                            <label class="form-label">Username</label>
                            <input name="username" type="text" class="form-control" value="<?=htmlspecialchars($result['username']??'')?>">
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">Email Address</label>
                            <input name="email" type="email" class="form-control" value="<?=htmlspecialchars($result['email']??'')?>">
                        </div>
                        
                        <div class="d-flex justify-content-end mt-4">
                            <button type="submit" class="btn btn-primary" onclick="saveChanges('General Settings')">
                                Save Changes
                            </button>
                        </div>
                        
                        <div id="general-success" class="alert alert-success mt-3" style="display: none;">
                            <i class="fas fa-check-circle me-2"></i> Your changes have been saved successfully.
                        </div>
                    </div>
                </form>
                
                <!-- Edit Profile Section -->
                <form action="update-profile.php" method="POST">
                    <input type="hidden" name="section" value="edit">
                    <div id="edit-profile" class="content-section" style="display: none;">
                        <h2 class="section-title">Profile Settings</h2>
                        
                        <div class="form-group">
                            <label class="form-label"><?=$result['account_type']=='user'?'Full Name':'Business Name'?></label>
                            <input name="name" type="text" class="form-control" value="<?=htmlspecialchars($name)?>">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Job</label>
                            <input name="Job" type="text" class="form-control">
                        </div>
                        
                        <?php require('locations-select.html') ?>
                        
                        <div class="form-group">
                            <label class="form-label">Bio</label>
                            <textarea name="bio" class="form-control" rows="4"><?=htmlspecialchars($bio)?></textarea>
                            <small class="text-muted">Tell us a little about yourself</small>
                        </div>
                        
                        <div class="d-flex justify-content-end mt-4">
                            <button type="submit" class="btn btn-primary" onclick="saveChanges('Profile Settings')">
                                Update Profile
                            </button>
                        </div>
                        
                        <div id="profile-success" class="alert alert-success mt-3" style="display: none;">
                            <i class="fas fa-check-circle me-2"></i> Your profile has been updated successfully.
                        </div>
                    </div>
                </form>
                
                <!-- Password Section -->
                <form action="update-profile.php" method="POST">
                    <input type="hidden" name="section" value="password">
                    <div id="password" class="content-section" style="display: none;">
                        <h2 class="section-title">Password Settings</h2>
                        
                        <div class="form-group">
                            <label class="form-label">Current Password</label>
                            <input name="old_password" type="password" class="form-control">
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">New Password</label>
                            <input name="new_password" type="password" class="form-control" id="new-password">
                            <small class="text-muted">Minimum 8 characters with at least one number</small>
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">Confirm New Password</label>
                            <input type="password" class="form-control" id="confirm-password">
                        </div>
                        
                        <div class="d-flex justify-content-end mt-4">
                            <button type="submit" class="btn btn-primary" onclick="changePassword()">
                                Change Password
                            </button>
                        </div>
                        
                        <div id="password-success" class="alert alert-success mt-3" style="display: none;">
                            <i class="fas fa-check-circle me-2"></i> Your password has been updated successfully.
                        </div>
                        
                        <div id="password-error" class="alert alert-danger mt-3" style="display: none;">
                            <i class="fas fa-exclamation-circle me-2"></i> <span id="error-message"></span>
                        </div>
                    </div>
                </form>
             <!-- Experience Section -->
<form action="update-profile.php" method="POST" id="experience-form" class="needs-validation" novalidate>
    <input type="hidden" name="section" value="experience">
    <div id="Experience" class="content-section" style="display: none;">
        <div class="section-header mb-4">
            <h2 class="section-title"><i class="fas fa-briefcase me-2"></i>Work Experience</h2>
            <p class="section-subtitle text-muted">Detail your professional journey to showcase your expertise</p>
        </div>
        
        <div class="form-card mb-4 p-4 border rounded">
            <div class="row g-3">
                <div class="col-md-6">
                    <div class="form-floating">
                        <input name="job_title" type="text" class="form-control" id="jobTitleInput" 
                               placeholder="Senior Software Engineer" required>
                        <label for="jobTitleInput">Job Title <span class="text-danger">*</span></label>
                        <div class="invalid-feedback">Please provide a valid job title</div>
                        <small class="form-text text-muted">E.g., Senior Software Engineer, Project Manager</small>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-floating">
                        <input name="company_name" type="text" class="form-control" id="companyInput" 
                               placeholder="Tech Solutions Inc." required>
                        <label for="companyInput">Company Name <span class="text-danger">*</span></label>
                        <div class="invalid-feedback">Please provide a company name</div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="form-card mb-4 p-4 border rounded">
            <div class="row g-3">
                <div class="col-md-6">
                    <div class="form-floating">
                        <input name="start_date" type="date" class="form-control" id="startDateInput" 
                               max="<?= date('Y-m-d') ?>" required>
                        <label for="startDateInput">Start Date <span class="text-danger">*</span></label>
                        <div class="invalid-feedback">Please select a valid start date</div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-floating" id="end-date-group">
                        <input name="end_date" type="date" class="form-control" id="endDateInput"
                               min="" max="<?= date('Y-m-d') ?>">
                        <label for="endDateInput">End Date</label>
                        <div class="invalid-feedback">End date must be after start date</div>
                    </div>
                </div>
            </div>
            
            <div class="form-check mt-3">
                <input name="current_job" type="checkbox" class="form-check-input" id="current-job">
                <label class="form-check-label" for="current-job">I currently work here</label>
            </div>
        </div>
        
        <div class="form-card mb-4 p-4 border rounded">
            <div class="form-floating">
                <textarea name="description" class="form-control" id="responsibilitiesInput" 
                          placeholder="Describe your role and achievements" style="height: 150px"></textarea>
                <label for="responsibilitiesInput">Responsibilities & Achievements</label>
                <div class="form-text">
                    <ul class="list-unstyled small text-muted mb-0">
                        <li><i class="fas fa-info-circle me-1"></i> Describe your key responsibilities</li>
                        <li><i class="fas fa-trophy me-1"></i> Highlight measurable achievements</li>
                        <li><i class="fas fa-bullseye me-1"></i> Use bullet points (•) for clarity</li>
                    </ul>
                </div>
            </div>
        </div>
        
        <div class="d-flex justify-content-between align-items-center mt-4 pt-3 border-top">
            <div class="form-check form-switch">
                <input class="form-check-input" type="checkbox" id="add-another" name="add_another" role="switch">
                <label class="form-check-label" for="add-another">
                    Add another experience after saving
                </label>
            </div>
            <div class="btn-group">
                <button type="button" class="btn btn-outline-secondary px-4" onclick="resetExperienceForm()">
                    <i class="fas fa-undo me-2"></i> Reset
                </button>
                <button type="submit" class="btn btn-primary px-4">
                    <i class="fas fa-save me-2"></i> Save Experience
                </button>
            </div>
        </div>
        
        <div id="experience-success" class="alert alert-success alert-dismissible fade show mt-4" style="display: none;">
            <div class="d-flex align-items-center">
                <i class="fas fa-check-circle me-3 fs-4"></i> 
                <div>
                    <h5 class="alert-heading mb-1">Success!</h5>
                    <p class="mb-0" id="success-message">Experience added successfully.</p>
                </div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        
        <div id="experience-error" class="alert alert-danger alert-dismissible fade show mt-4" style="display: none;">
            <div class="d-flex align-items-center">
                <i class="fas fa-exclamation-circle me-3 fs-4"></i> 
                <div>
                    <h5 class="alert-heading mb-1">Error</h5>
                    <p class="mb-0" id="experience-error-message"></p>
                </div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    </div>
</form>
                <!-- Social Profiles Section -->
                <form action="update-profile.php" method="POST">
                    <input type="hidden" name="section" value="socials">
                    <div id="social-profiles" class="content-section" style="display: none;">
                        <h2 class="section-title">Social Profiles</h2>
                        <p class="text-muted mb-4">Add links to your social media profiles to help others connect with you.</p>
                        
                        <div class="form-group">
                            <label class="form-label">
                                <i class="fab fa-facebook me-2 text-primary"></i>Facebook
                            </label>
                            <input name="facebook" type="url" class="form-control" value="<?=htmlspecialchars($social_links['facebook']??'')?>">
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">
                                <i class="fab fa-twitter me-2 text-info"></i>Twitter
                            </label>
                            <input name="twitter" type="url" class="form-control" value="<?=htmlspecialchars($social_links['twitter']??'')?>">
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">
                                <i class="fab fa-linkedin me-2 text-primary"></i>LinkedIn
                            </label>
                            <input name="linked-in" type="url" class="form-control" value="<?=htmlspecialchars($social_links['linked-in']??'')?>">
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">
                                <i class="fab fa-instagram me-2 text-danger"></i>Instagram
                            </label>
                            <input name="instagram" type="url" class="form-control" value="<?=htmlspecialchars($social_links['instagram']??'')?>">
                        </div>
                        
                        <div class="d-flex justify-content-end mt-4">
                            <button type="submit" class="btn btn-primary" onclick="saveChanges('Social Profiles')">
                                Save Social Links
                            </button>
                        </div>
                        
                        <div id="social-success" class="alert alert-success mt-3" style="display: none;">
                            <i class="fas fa-check-circle me-2"></i> Your social profiles have been updated.
                        </div>
                    </div>
                </form>
                
                <!-- Delete Account Section -->
                <div class="delete-account">
                    <h3 class="delete-account-title">
                        <i class="fas fa-exclamation-triangle me-2"></i>Delete Account
                    </h3>
                    <p class="delete-account-text">
                        Once you delete your account, there is no going back. Please be certain.
                    </p>
                    <button class="btn btn-danger" onclick="confirmDelete()">
                        <i class="fas fa-trash-alt me-2"></i>Delete Account
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Account Modal -->
    <div class="modal fade" id="deleteAccountModal" tabindex="-1" aria-labelledby="deleteAccountModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteAccountModalLabel">
                        <i class="fas fa-exclamation-triangle me-2"></i>Confirm Account Deletion
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to delete your account? This action cannot be undone. All your data will be permanently removed from our systems.</p>
                    <div class="alert alert-danger mt-3">
                        <i class="fas fa-exclamation-circle me-2"></i> Warning: This will permanently delete all your account information.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline" data-bs-dismiss="modal">Cancel</button>
                    <form action="delete-account.php" method="POST">
                        <button type="submit" class="btn btn-danger">
                            <i class="fas fa-trash-alt me-2"></i>Delete Account
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
		//TO-RESET-THE-MODAL-FOR-PROFILE-PICTURE
		document.getElementById('profile-upload-btn').addEventListener('click', function () {
		  document.getElementById('profileUploadForm').reset();
		});
		//TO-RESET-THE-MODAL-FOR-PROFILE-PICTURE
		document.getElementById('profile-upload-btn-cancel').addEventListener('click', function () {
		  document.getElementById('profileUploadForm').reset();
		});
		
        function showSection(sectionId, title, element) {
            // Hide all sections
            document.querySelectorAll(".content-section").forEach(section => {
                section.style.display = "none";
            });
            
            // Show the selected section
            document.getElementById(sectionId).style.display = "block";
            
            // Update the section title in breadcrumb
            document.getElementById("section-title").innerText = title;
            
            // Update active nav link
            document.querySelectorAll(".settings-nav-link").forEach(link => {
                link.classList.remove("active");
            });
            element.classList.add("active");
        }

        function saveChanges(section) {
            const sectionName = section.toLowerCase().replace(" ", "-");
            const message = document.getElementById(`${sectionName}-success`);
            if(message) {
                message.style.display = "block";
                setTimeout(() => {
                    message.style.display = "none";
                }, 5000);
            }
        }

        function changePassword() {
            const newPassword = document.getElementById("new-password").value;
            const confirmPassword = document.getElementById("confirm-password").value;
            const successMessage = document.getElementById("password-success");
            const errorMessage = document.getElementById("password-error");
            const errorText = document.getElementById("error-message");

            // Hide messages initially
            successMessage.style.display = "none";
            errorMessage.style.display = "none";

            if (newPassword === "" || confirmPassword === "") {
                errorText.textContent = "Both password fields must be filled.";
                errorMessage.style.display = "block";
                return false;
            } else if (newPassword !== confirmPassword) {
                errorText.textContent = "Passwords do not match. Please try again.";
                errorMessage.style.display = "block";
                return false;
            } else if (newPassword.length < 8) {
                errorText.textContent = "Password must be at least 8 characters long.";
                errorMessage.style.display = "block";
                return false;
            } else {
                successMessage.style.display = "block";
                setTimeout(() => {
                    successMessage.style.display = "none";
                }, 5000);
                return true;
            }
        }

        function confirmDelete() {
            const deleteModal = new bootstrap.Modal(document.getElementById('deleteAccountModal'));
            deleteModal.show();
        }

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
                    var profileImg = document.getElementById('profilePreviewImg');
                    profileImg.style.display = 'none';
                    profileImg.src = '';
                    profileImg.src = e.target.result;
                    profileImg.style.display = 'block';

                    var profileModal = new bootstrap.Modal(document.getElementById('profileImageModal'), {
                        backdrop: 'static',
                        keyboard: false
                    });
                    profileModal.show();

                    $('#profileImageModal').on('hidden.bs.modal', function () {
                        profileImg.src = '';
                        profileImg.style.display = 'none';
                        $('#profileImageModal').attr('aria-hidden', 'true');
                    });
                }
            };
            reader.readAsDataURL(file);
        }
        
         function disableButtonProfilePicture(button) {
            const spinner = button.querySelector('.spinner-border');
            spinner.classList.remove('d-none');
            /* button.innerHTML = 'Uploading...'; */
            button.submit().setTimeout(() => {
                this.disabled = true;
            }, 3000);
            
           

        } 

// Enhanced Experience Form Functionality
document.addEventListener('DOMContentLoaded', function() {
    const currentJobCheckbox = document.getElementById('current-job');
    const endDateInput = document.querySelector('input[name="end_date"]');
    const startDateInput = document.querySelector('input[name="start_date"]');
    const experienceForm = document.getElementById('experience-form');
    
    // Current job checkbox functionality
    if(currentJobCheckbox) {
        currentJobCheckbox.addEventListener('change', function() {
            endDateInput.disabled = this.checked;
            if(this.checked) {
                endDateInput.value = '';
            }
        });
    }
    
    // Set min end date based on start date
    if(startDateInput) {
        startDateInput.addEventListener('change', function() {
            const endDateInput = document.querySelector('input[name="end_date"]');
            endDateInput.min = this.value;
        });
    }
    
    // Form validation
    if(experienceForm) {
        experienceForm.addEventListener('submit', function(e) {
            if(!this.checkValidity()) {
                e.preventDefault();
                e.stopPropagation();
            }
            
            // Additional validation for end date
            if(endDateInput.value && startDateInput.value) {
                if(new Date(endDateInput.value) < new Date(startDateInput.value)) {
                    endDateInput.classList.add('is-invalid');
                    e.preventDefault();
                    e.stopPropagation();
                } else {
                    endDateInput.classList.remove('is-invalid');
                }
            }
            
            this.classList.add('was-validated');
        });
    }
});

function resetExperienceForm() {
    const form = document.getElementById('experience-form');
    form.reset();
    form.classList.remove('was-validated');
    document.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
    document.getElementById('experience-success').style.display = 'none';
    document.getElementById('experience-error').style.display = 'none';
}
 // Example of enhanced date validation
    document.getElementById('current-job').addEventListener('change', function() {
        const endDateGroup = document.getElementById('end-date-group');
        if (this.checked) {
            endDateGroup.style.opacity = '0.5';
            endDateGroup.querySelector('input').disabled = true;
            endDateGroup.querySelector('input').value = '';
        } else {
            endDateGroup.style.opacity = '1';
            endDateGroup.querySelector('input').disabled = false;
        }
    });

    // Start date change handler to set min end date
    document.querySelector('input[name="start_date"]').addEventListener('change', function() {
        document.querySelector('input[name="end_date"]').min = this.value;
    });
    </script>
</body>
</html>