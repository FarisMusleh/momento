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
        INSERT INTO accounts (username, email, provider_id, provider, account_type, picture)
        VALUES (?, ?, ?, ?, ?, ?)
    ");
    $stmtAccount->execute([$username, $email, $providerId, $provider, $type, $picture]);

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
			'picture' => $picture
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
			'picture' => $picture
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
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Momento - information form </title>
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
        input, select {
            width: 100%;
            padding: 14px;
            border: 1px solid #000000;
            border-radius: 6px;
            font-size: 16px;
            box-sizing: border-box;
        }
        input:focus, select:focus {
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
       
        <form id="registrationForm" method = "post">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" required><span id = 'username-status'></span>
            </div>
            <div class="form-group">
                <label for="dob">Date of Birth</label>
                <input type="date" id="dob" name="dob" required>
            </div>
            <div class="form-group">
                <label for="phone">Phone Number</label>
                <input type="tel" id="phone" name="phone" pattern="\+?[0-9]{10,15}" placeholder="1234567890" required>
                
            </div>
            <div class="form-group">
                <label for="acc-type">Account Type</label>
                <select id="acc-type" name="acc-type" required>
                    <option value="">Select Account Type</option>
                    <option value="user">Personal</option>
                    <option value="business">Business</option>
                </select>
            </div>
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
            <button type="submit">Confirm</button>
        </form>
    </div>
</body>
</html>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
	$(document).ready(function(){
		$('#username').on('input', function(){
			let temp = $(this).val();
			
			if(temp.length > 0){
				$('#username-status').text("...");
				setTimeout(function() {
					$.ajax({
						url: '../check-username.php',
						type: 'post',
						data: {key:temp},
						success: function(response){
							$('#username-status').text(response);
						}
					});
				},900);
			}else{
				$('#username-status').text('');
			}
		});
	});
</script>