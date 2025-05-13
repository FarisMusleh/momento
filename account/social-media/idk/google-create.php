<?php
/**
 * Google Account Creation
 * This file handles the completion of user profile after Google authentication
 */

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
session_unset();
session_destroy();
session_start();
require '../../pdo.php';

// Redirect if already logged in
if (isset($_SESSION['data'])) {
    header('Location: ../../index.php');
    exit();
}

// Redirect if Google session is missing
if (!isset($_SESSION['google'])) {
    header('Location: ../login.php');
    exit();
}

// Process form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['username'], $_POST['acc-type'])) {
    // Validate and sanitize inputs
    $username = trim(htmlspecialchars($_POST['username']));
    $type     = in_array($_POST['acc-type'], ['user', 'business']) ? $_POST['acc-type'] : 'user';
    $location = isset($_POST['location']) ? trim(htmlspecialchars($_POST['location'])) : null;
    $phone    = isset($_POST['phone']) ? trim(htmlspecialchars($_POST['phone'])) : null;
    
    // Basic validation
    if (empty($username)) {
        $_SESSION['form_error'] = 'Username cannot be empty';
        header('Location: ' . $_SERVER['PHP_SELF']);
        exit();
    }
    
    // Check username availability
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM accounts WHERE username = ?");
    $stmt->execute([$username]);
    if ($stmt->fetchColumn() > 0) {
        $_SESSION['form_error'] = 'Username already taken';
        header('Location: ' . $_SERVER['PHP_SELF']);
        exit();
    }

    // Get Google data from session
    $googleData  = $_SESSION['google'];
    $email       = $googleData['email'];
    $name        = $googleData['name'];
    $provider    = $googleData['provider'];
    $providerId  = $googleData['providerId'];
    $picture     = $googleData['picture'];
    
    try {
        // Start transaction
        $pdo->beginTransaction();
        
        // Insert into accounts table
        $stmtAccount = $pdo->prepare("
            INSERT INTO accounts (username, email, provider_id, provider, account_type, picture, location)
            VALUES (?, ?, ?, ?, ?, ?, ?)
        ");
        $stmtAccount->execute([$username, $email, $providerId, $provider, $type, $picture, $location]);

        $lastId = $pdo->lastInsertId();

        // Insert into appropriate profile table based on account type
        if ($type === 'user') {
            $stmtProfile = $pdo->prepare("
                INSERT INTO user_profiles (id, name)
                VALUES (?, ?)
            ");
            $stmtProfile->execute([$lastId, $name]);
            
            $_SESSION['data'] = [
                'id' => $lastId,
                'email' => $email,
                'name' => $name,
                'username' => $username,
                'type' => $type,
                'picture' => $picture,
                'location' => $location
            ];
        } elseif ($type === 'business') {
            $stmtProfile = $pdo->prepare("
                INSERT INTO business_profiles (id, business_name)
                VALUES (?, ?)
            ");
            $stmtProfile->execute([$lastId, $name]);
            
            $_SESSION['data'] = [
                'id' => $lastId,
                'email' => $email,
                'business_name' => $name,
                'username' => $username,
                'type' => $type,
                'picture' => $picture,
                'location' => $location
            ];
        }
        
        // Commit transaction
        $pdo->commit();

        // Clean up and redirect
        unset($_SESSION['google']);
        header('Location: ../../index.php');
        exit();
        
    } catch (Exception $e) {
        // Rollback on error
        $pdo->rollBack();
        error_log('Account creation error: ' . $e->getMessage());
        $_SESSION['form_error'] = 'Error creating your account. Please try again.';
        header('Location: ' . $_SERVER['PHP_SELF']);
        exit();
    }
}

// Get any form errors
$formError = isset($_SESSION['form_error']) ? $_SESSION['form_error'] : '';
unset($_SESSION['form_error']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  <link href="https://fonts.googleapis.com/css2?family=Dancing+Script&family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <title>Momento - Complete Your Profile</title>
  <style>
    body {
      margin: 0;
      padding: 0;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background: linear-gradient(135deg, rgb(132, 133, 134), rgb(17, 17, 17));
      display: flex;
      align-items: center;
      justify-content: center;
      min-height: 100vh;
    }

    .container {
      background: rgba(255, 255, 255, 0.85);
      backdrop-filter: blur(10px);
      border-radius: 16px;
      box-shadow: 0 8px 32px rgba(202, 195, 195, 0.2);
      max-width: 450px;
      width: 90%;
      padding: 40px;
      box-sizing: border-box;
      animation: fadeIn 0.6s ease-in-out;
    }

    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(10px); }
      to { opacity: 1; transform: translateY(0); }
    }

    .logo {
      text-align: center;
      font-size: 32px;
      font-weight: bold;
      color: #1a1a1a;
      margin-bottom: 20px;
    }

    h1 {
      text-align: center;
      color: #333;
      font-size: 24px;
      margin-bottom: 25px;
    }

    .form-group {
      margin-bottom: 20px;
    }

    label {
      display: block;
      margin-bottom: 6px;
      font-weight: 600;
      color: #222;
    }

    input, select {
      width: 100%;
      padding: 12px 14px;
      border-radius: 8px;
      border: 1px solid #ccc;
      font-size: 15px;
      transition: all 0.3s;
    }

    input:focus, select:focus {
      border-color: #4a90e2;
      box-shadow: 0 0 6px rgba(74, 144, 226, 0.3);
      outline: none;
    }

    #username-status {
      display: inline-block;
      margin-top: 5px;
      font-size: 13px;
      color: #555;
    }

    button {
      width: 100%;
      padding: 14px;
    }
    
    .error-message {
      color: #dc3545;
      font-size: 14px;
      margin-top: 5px;
    }

    #showTermsBtn {
      text-decoration: underline;
      color: black;
      border: none;
      border-radius: 2px;
      font-size: 16px;
      cursor: pointer;
      background: none;
      padding: 0;
      transition: all 0.3s;
    }

    #showTermsBtn:hover {
      text-shadow: 1px 1px 10px #55555555;
    }

    .modal {
      display: none;
      position: fixed;
      z-index: 10;
      left: 0;
      top: 0;
      width: 100%;
      height: 100%;
      background-color: rgba(0, 0, 0, 0.4);
      animation: fadeIn 0.3s ease-in-out;
    }

    .modal-content {
      background-color: #ffffff;
      margin: 5% auto;
      padding: 30px;
      width: 90%;
      max-width: 700px;
      height: 80%;
      overflow-y: auto;
      border-radius: 16px;
      box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
      animation: slideUp 0.4s ease-out;
    }

    .close {
      float: right;
      font-size: 22px;
      color: #888;
      cursor: pointer;
    }

    .close:hover {
      color: #000;
    }

    h2 {
      font-size: 24px;
      font-weight: 600;
      margin-bottom: 16px;
      color: #111827;
    }

    h3 {
      font-size: 18px;
      margin-top: 20px;
      color: #1f2937;
    }

    p, li {
      margin: 10px 0;
      color: #374151;
      line-height: 1.6;
    }

    ul {
      padding-left: 20px;
    }

    .divider {
      display: flex;
      align-items: center;
      margin: 1.2rem 0;
      color: #999;
      font-size: 0.85rem;
    }

    .divider::before, .divider::after {
      content: "";
      flex: 1;
      border-bottom: 1px solid #ddd;
    }

    .input-with-icon {
      position: relative;
    }
    
    .input-icon {
      position: absolute;
      left: 10px;
      top: 50%;
      transform: translateY(-50%);
      color: #6c757d;
    }
    
    .select-icon {
      right: 10px;
      left: auto;
      pointer-events: none;
    }
    
    #username, #dob, #phone, #acc-type, #location {
      padding-left: 35px;
      width: 100%;
    }
    
    #acc-type, #location {
      -webkit-appearance: none;
      -moz-appearance: none;
      appearance: none;
      padding-right: 35px;
    }

    @keyframes slideUp {
      from { transform: translateY(30px); opacity: 0; }
      to { transform: translateY(0); opacity: 1; }
    }
  </style>
</head>
<body>
  <div class="container">
    <div class="logo" style="font-family:Dancing Script">Momento</div>
    <div class="divider">
      <span>Complete Your Profile</span>
    </div>
    
    <?php if (!empty($formError)): ?>
      <div class="alert alert-danger" role="alert">
        <?php echo $formError; ?>
      </div>
    <?php endif; ?>
    
    <form id="registrationForm" method="post">
      <div class="form-group">
        <label for="username">
          <i class="fas fa-user" style="margin-right: 8px;"></i>Username
        </label>
        <div class="input-with-icon">
          <i class="fas fa-user input-icon"></i>
          <input type="text" id="username" name="username" required placeholder="Choose a username" minlength="3" maxlength="30">
        </div>
        <span id="username-status"></span>
      </div>

      <div class="form-group">
        <label for="acc-type">
          <i class="fas fa-briefcase" style="margin-right: 8px;"></i>Account Type
        </label>
        <div class="input-with-icon">
          <i class="fas fa-chevron-down input-icon select-icon"></i>
          <select id="acc-type" name="acc-type" required>
            <option value="">Select Account Type</option>
            <option value="user">Personal</option>
            <option value="business">Business</option>
          </select>
        </div>
      </div>

      <div class="form-group">
        <label for="location">
          <i class="fas fa-map-marker-alt" style="margin-right: 8px;"></i>Location
        </label>
        <div class="input-with-icon">
          <i class="fas fa-chevron-down input-icon select-icon"></i>
          <select id="location" name="location" required>
            <option value="">Select Your Country</option>
            <!-- Countries list -->
            <?php include_once 'locations-select.html'; ?>
          </select>
        </div>
      </div>
   
      <div class="mt-3 d-flex align-items-center gap-2">
        <input type="checkbox" style="width:15px;height:15px;margin-right:5px" id="agree" required>
        <label for="agree">I agree to the <button type="button" id="showTermsBtn">Terms and Conditions</button></label>
      </div>
  
      <button id="submit" type="submit" class="btn btn-dark text-light mt-3" disabled>
        <i class="fas fa-check-circle" style="margin-right: 8px;"></i>Confirm
      </button>
    </form>
  </div>

  <!-- Terms and Conditions Modal -->
  <div id="termsModal" class="modal">
    <div class="modal-content">
      <span class="close" id="closeModal">&times;</span>
      <h2>Terms & Conditions for Momento</h2>

      <p>
        Welcome to Momento. By accessing or using this website, you agree to be bound by the following terms and
        conditions. These terms are designed in accordance with the applicable laws of the Hashemite Kingdom of
        Jordan, including but not limited to the Cybercrime Law, the Copyright Law, and the Telecommunications
        Law.
      </p>

      <h3>1. Responsibility for Uploaded Images and Content</h3>
      <ul>
        <li>Users are solely and fully responsible for any images or content they upload to the platform.</li>
        <li>Momento bears no legal or civil liability for any content uploaded by users.</li>
        <li>Any image or content that violates Jordanian law (as of the year 2025) is the sole responsibility of
          the user who uploaded it. This includes, but is not limited to:</li>
        <ul>
          <li>Violation of individuals' privacy.</li>
          <li>Breach of intellectual property or copyright.</li>
          <li>Incitement to hatred, violence, racism, or discrimination.</li>
          <li>Obscene or unethical content that contradicts public morals.</li>
          <li>Use of personal photos without explicit consent.</li>
        </ul>
      </ul>

      <!-- Rest of terms content -->
      <!-- Terms sections 2-7 -->
    </div>
  </div>

  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script>
    $(document).ready(function() {
      // Terms modal functionality
      const modal = document.getElementById("termsModal");
      const btn = document.getElementById("showTermsBtn");
      const close = document.getElementById("closeModal");

      btn.onclick = (e) => {
        e.preventDefault();
        modal.style.display = "block";
      };
      
      close.onclick = () => modal.style.display = "none";
      
      window.onclick = (event) => {
        if (event.target == modal) modal.style.display = "none";
      };
      
      // Handle checkbox for enabling submit button
      const checkbox = document.getElementById('agree');
      const submitBtn = document.getElementById('submit');

      checkbox.addEventListener('change', function() {
        submitBtn.disabled = !this.checked;
      });
      
      // Username availability check with debounce
      let usernameTimer;
      $('#username').on('input', function() {
        const username = $(this).val().trim();
        
        clearTimeout(usernameTimer);
        
        if (username.length > 2) {
          $('#username-status').text("Checking...");
          
          usernameTimer = setTimeout(function() {
            $.ajax({
              url: '../check-username.php',
              type: 'post',
              data: { key: username },
              success: function(response) {
                $('#username-status').text(response);
                $('#username-status').removeClass('text-success text-danger');
                
                if (response === 'Username available') {
                  $('#username-status').addClass('text-success');
                } else {
                  $('#username-status').addClass('text-danger');
                }
              }
            });
          }, 500);
        } else {
          $('#username-status').text('');
        }
      });
      
      // Form validation before submit
      $('#registrationForm').on('submit', function(e) {
        const username = $('#username').val().trim();
        const accountType = $('#acc-type').val();
        const location = $('#location').val();
        
        if (username.length < 3) {
          e.preventDefault();
          $('#username-status').text('Username must be at least 3 characters').addClass('text-danger');
          return false;
        }
        
        if (!accountType) {
          e.preventDefault();
          return false;
        }
        
        if (!location) {
          e.preventDefault();
          return false;  
        }
        
        return true;
      });
    });
  </script>
</body>
</html>