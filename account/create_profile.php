<?php
session_start();
require '../pdo.php';

// Check if the user is authorized to access this page
if (!isset($_SESSION['create_profile'])) {
    header('location:../index.php');
    exit();
}

// Process form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userId = $_SESSION['create_profile'];
    $name = $_POST['name'] ?? '';
    $phone = $_POST['phone'] ?? '';
    $location = $_POST['location'] ?? '';
    $type = $_SESSION['data']['type'] ?? '';
    $picture = null;
    
    // Handle profile picture upload if present
    if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] == 0) {
        $allowed = ['jpg', 'jpeg', 'png', 'gif'];
        $filename = $_FILES['profile_picture']['name'];
        $ext = pathinfo($filename, PATHINFO_EXTENSION);
        
        if (in_array(strtolower($ext), $allowed)) {
            $new_filename = uniqid('profile_', true) . '.' . $ext;
            $upload_path = '../../uploads/profile_pics/' . $new_filename;
            
            // Make sure directory exists
            if (!file_exists('/momento/uploads/ProfilePicture/')) {
                mkdir('/momento/uploads/ProfilePicture/', 0777, true);
            }
            
            // Move uploaded file
            if (move_uploaded_file($_FILES['profile_picture']['tmp_name'], $upload_path)) {
                $picture = '/momento/uploads/ProfilePicture/' . $new_filename;
            }
        }
    }
    
    try {
        // Update the accounts table with location and picture
        if ($picture) {
            $stmtAccount = $pdo->prepare("
                UPDATE accounts 
                SET location = ?, picture = ?
                WHERE id = ?
            ");
            $stmtAccount->execute([$location, $picture, $userId]);
            
            // Update session with picture path
            $_SESSION['data']['picture'] = '/momento/Uploads/ProfilePicture/img_1.jpg';
        } else {
            $stmtAccount = $pdo->prepare("
                UPDATE accounts 
                SET location = ?
                WHERE id = ?
            ");
            $stmtAccount->execute([$location, $userId]);
        }
        
        // Get the account type and update the corresponding profile
        if ($type === 'user') {
            // Update user profile - only name is needed
            $stmtProfile = $pdo->prepare("
                UPDATE user_profiles 
                SET name = ?
                WHERE id = ?
            ");
            $stmtProfile->execute([$name, $userId]);
            
            $_SESSION['data']['name'] = $name;
        } 
        elseif ($type === 'business') {
            // Update business profile - name, contact number, bio, job
            $bio = $_POST['bio'] ?? '';
            $job = $_POST['job'] ?? '';
            $social_links = $_POST['social_links'] ?? '';
            
            $stmtProfile = $pdo->prepare("
                UPDATE business_profiles 
                SET business_name = ?, contact_number = ?, bio = ?, job = ?, social_links = ?
                WHERE id = ?
            ");
            $stmtProfile->execute([$name, $phone, $bio, $job, $social_links, $userId]);
            
            $_SESSION['data']['business_name'] = $name;
        }

        // Clean up and redirect
        unset($_SESSION['create_profile']);
        header('Location: ../index.php');
        exit();
    } catch (PDOException $e) {
        echo "Error updating profile: " . $e->getMessage();
        exit();
    }
}

// Get account type from the session
$accountType = $_SESSION['data']['type'] ?? '';
?>

<!DOCTYPE html> 
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Momento - Complete Your Profile</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #ffffff;
            color: #000000;
            margin: 0;
            padding: 20px;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }
        .container {
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 600px;
            padding: 40px;
            box-sizing: border-box;
        }
        h1 {
            color: #000000;
            text-align: center;
            margin-bottom: 30px;
        }
        .form-group {
            margin-bottom: 20px;
        }
        label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #000000;
        }
        input, select, textarea {
            width: 100%;
            padding: 14px;
            border: 1px solid #000000;
            border-radius: 6px;
            font-size: 16px;
            box-sizing: border-box;
        }
        textarea {
            height: 120px;
            resize: vertical;
        }
        input:focus, select:focus, textarea:focus {
            outline: none;
            border-color: #000000;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.3);
        }
        button {
            width: 100%;
            padding: 14px;
            background-color: #000000;
            color: white;
            border: none;
            border-radius: 6px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        button:hover {
            background-color: #333333;
        }
        .logo {
            text-align: center;
            margin-bottom: 20px;
            font-size: 28px;
            font-weight: bold;
            color: #000000;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="logo">Momento</div>
        <h1>Complete Your Profile</h1>
        
        <form id="profileForm" method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label for="name"><?php echo ($accountType === 'business') ? 'Business Name' : 'Full Name'; ?></label>
                <input type="text" id="name" name="name" required>
            </div>
            
            <?php if ($accountType === 'business'): ?>
            <div class="form-group">
                <label for="phone">Contact Number</label>
                <input type="tel" id="phone" name="phone" pattern="\+?[0-9]{10,15}" placeholder="1234567890" required>
            </div>
            
            <div class="form-group">
                <label for="social_links">Social Media Links</label>
                <input type="text" id="social_links" name="social_links" placeholder="Instagram, Facebook, etc.">
            </div>
            
            <div class="form-group">
                <label for="bio">Bio</label>
                <textarea id="bio" name="bio" placeholder="Tell us about your business"></textarea>
            </div>
            
            <div class="form-group">
                <label for="job">Industry/Job</label>
                <input type="text" id="job" name="job" placeholder="e.g. Restaurant, Technology, Art, etc.">
            </div>
            <?php endif; ?>
            
            <div class="form-group">
                <label for="location">Location</label>
                <select id="location" name="location" required>
                    <option value="">Select Your Country</option>
                    <option value="United States">United States</option>
                    <option value="United Kingdom">United Kingdom</option>
                    <option value="Canada">Canada</option>
                    <option value="Australia">Australia</option>
                    <option value="Germany">Germany</option>
                    <option value="France">France</option>
                    <option value="Italy">Italy</option>
                    <option value="Spain">Spain</option>
                    <option value="Japan">Japan</option>
                    <option value="China">China</option>
                    <option value="India">India</option>
                    <option value="Brazil">Brazil</option>
                    <option value="Mexico">Mexico</option>
                    <option value="South Africa">South Africa</option>
                    <option value="Russia">Russia</option>
                    <option value="Saudi Arabia">Saudi Arabia</option>
                    <option value="United Arab Emirates">United Arab Emirates</option>
                    <option value="Turkey">Turkey</option>
                    <option value="South Korea">South Korea</option>
                    <option value="Egypt">Egypt</option>
                </select>
            </div>
            
            <button type="submit">Complete Profile</button>
        </form>
    </div>
</body>
</html>