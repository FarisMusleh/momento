<?php
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

// Check if required POST data is received
if (isset($_POST['username'], $_POST['acc-type'])) {
    $username  = $_POST['username'];
    $type      = $_POST['acc-type'];
    $location  = $_POST['location'] ?? null;
    $phone     = $_POST['phone'] ?? null;
    $dob       = $_POST['dob'] ?? null;

    // Get Google data from session
    $googleData  = $_SESSION['google'];
    $email       = $googleData['email'];
    $name        = $googleData['name'];
    $provider    = $googleData['provider'];
    $providerId  = $googleData['providerId'];
    $picture     = $googleData['picture'];

    // Insert into accounts table
    $stmtAccount = $pdo->prepare("
        INSERT INTO accounts (username, email, provider_id, provider, account_type, picture, location)
        VALUES (?, ?, ?, ?, ?, ?, ?)
    ");
    $stmtAccount->execute([$username, $email, $providerId, $provider, $type, $picture, $location]);

    $lastId = $pdo->lastInsertId();

    if ($type === 'user') {
		$stmtProfile = $pdo->prepare("
			INSERT INTO user_profiles (id, name)
			VALUES (?, ?)
		");
		$stmtProfile->execute([$lastId, $name]);
		$_SESSION['data'] = [
			'id' => $lastId,
			'email'    => $email,
			'name'     => $name,
			'username' => $username,
			'type'     => $type,
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
			'email'    => $email,
			'business_name'     => $name,
			'username' => $username,
			'type'     => $type,
			'picture' => $picture,
			'location' => $location
		];
    }

    // Clean up and redirect
    unset($_SESSION['google']);
    header('Location: ../../index.php');
    exit();
}
?>





<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Momento - Information Form</title>
  <style>
    body {
      margin: 0;
      padding: 0;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background: linear-gradient(135deg, #e0eafc, #cfdef3);
      display: flex;
      align-items: center;
      justify-content: center;
      min-height: 100vh;
    }

    .container {
      background: rgba(255, 255, 255, 0.85);
      backdrop-filter: blur(10px);
      border-radius: 16px;
      box-shadow: 0 8px 32px rgba(0, 0, 0, 0.2);
      max-width: 500px;
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
      background-color: #4a90e2;
      color: white;
      border: none;
      border-radius: 8px;
      font-size: 16px;
      font-weight: bold;
      cursor: pointer;
      transition: background-color 0.3s ease;
    }

    button:hover {
      background-color: #357ab7;
    }
  </style>
</head>
<body>
  <div class="container">
    <div class="logo">Momento</div>
    <h1>Register Your Account</h1>
    <form id="registrationForm" method="post">
      <div class="form-group">
        <label for="username">Username</label>
        <input type="text" id="username" name="username" required />
        <span id="username-status"></span>
      </div>
      <div class="form-group">
        <label for="dob">Date of Birth</label>
        <input type="date" id="dob" name="dob" required />
      </div>
      <div class="form-group">
        <label for="phone">Phone Number</label>
        <input type="tel" id="phone" name="phone" pattern="\+?[0-9]{10,15}" placeholder="+1234567890" required />
      </div>
      <div class="form-group">
        <label for="acc-type">Account Type</label>
        <select id="acc-type" name="acc-type" required>
          <option value="">Select Account Type</option>
          <option value="user">Personal</option>
          <option value="business">Business</option>
        </select>
      </div>
      <?php require('locations-select.html') ?>
      <button type="submit">Confirm</button>
    </form>
  </div>

  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script>
    $(document).ready(function () {
      $('#username').on('input', function () {
        let temp = $(this).val();

        if (temp.length > 0) {
          $('#username-status').text("Checking...");
          setTimeout(function () {
            $.ajax({
              url: '../check-username.php',
              type: 'post',
              data: { key: temp },
              success: function (response) {
                $('#username-status').text(response);
              }
            });
          }, 800);
        } else {
          $('#username-status').text('');
        }
      });
    });
  </script>
</body>
</html>
