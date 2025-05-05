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
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
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
      
    }
	

	#showTermsBtn {
            
            text-decoration:underline;
            color: black;
            border: none;
            border-radius: 2px;
            font-size: 16px;
            cursor: pointer;
            box-shadow: 0 4px 14px rgba(0, 0, 0, 0.1);
            transition: background 0.3s;
        }

        #showTermsBtn:hover {
            text-shadow:1px 1px 10px #55555555;
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

        p,
        li {
            margin: 10px 0;
            color: #374151;
            line-height: 1.6;
        }

        ul {
            padding-left: 20px;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        @keyframes slideUp {
            from {
                transform: translateY(30px);
                opacity: 0;
            }

            to {
                transform: translateY(0);
                opacity: 1;
            }
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
		<div class="mt-3 flex">
			<input type = "checkbox" class="" style = "width:15px;height:15px;" id="agree">
			<lable><a id="showTermsBtn">Show Terms</a></label>
		</div>
      <button id = "submit" type="submit" class = "btn btn-primary" disabled>Confirm</button>
    </form>
  </div>



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
                    <li>Violation of individuals’ privacy.</li>
                    <li>Breach of intellectual property or copyright.</li>
                    <li>Incitement to hatred, violence, racism, or discrimination.</li>
                    <li>Obscene or unethical content that contradicts public morals.</li>
                    <li>Use of personal photos without explicit consent.</li>
                </ul>
            </ul>

            <h3>2. Ownership and Usage Rights</h3>
            <ul>
                <li>Any image uploaded without clear prior agreement or license shall be considered proprietary content
                    of Momento, and the platform reserves the right to use, modify, or republish it at its discretion.
                </li>
                <li>By uploading content to Momento, users grant the platform a non-exclusive license to use such
                    content within the platform’s services and promotional materials.</li>
            </ul>

            <h3>3. Compliance with Jordanian Law</h3>
            <ul>
                <li>Users agree not to use the platform for any unlawful or unauthorized purposes under the laws of
                    Jordan.</li>
                <li>Uploading or sharing any content that violates Jordanian law, including the Cybercrime Law No. 27 of
                    2015, is strictly prohibited.</li>
                <li>The platform reserves the right to cooperate with law enforcement authorities and judicial entities
                    in the event of any legal violation involving a user.</li>
            </ul>

            <h3>4. Account and Content Management</h3>
            <ul>
                <li>Momento reserves the right to suspend or delete any user account that violates these terms or
                    uploads content deemed illegal or inappropriate.</li>
                <li>The platform may remove any uploaded content without prior notice if it violates internal policies
                    or national regulations.</li>
            </ul>

            <h3>5. Privacy and Data Protection</h3>
            <ul>
                <li>Momento is committed to maintaining the confidentiality of user data and will not share it with any
                    third party without explicit consent, unless required by law or judicial authorities.</li>
                <li>Users have the right to request permanent deletion of their personal data from the platform’s
                    database.</li>
            </ul>

            <h3>6. Child Protection</h3>
            <ul>
                <li>Users are strictly prohibited from uploading any images or content involving minors (under 18 years
                    of age) without verified parental or legal guardian consent.</li>
                <li>Any violation of this policy may result in account suspension and legal action in accordance with
                    child protection laws in Jordan.</li>
            </ul>

            <h3>7. Changes to Terms</h3>
            <ul>
                <li>These terms and conditions may be updated at any time without prior notice. Continued use of the
                    platform after changes are posted will be considered acceptance of those changes.</li>
            </ul>
        </div>
    </div>

    <script>
        const modal = document.getElementById("termsModal");
        const btn = document.getElementById("showTermsBtn");
        const close = document.getElementById("closeModal");

        btn.onclick = () => modal.style.display = "block";
        close.onclick = () => modal.style.display = "none";
        window.onclick = (event) => {
            if (event.target == modal) modal.style.display = "none";
        }
		  const checkbox = document.getElementById('agree');
  const submitBtn = document.getElementById('submit');

  checkbox.addEventListener('change', function () {
    submitBtn.disabled = !this.checked;
  });
    </script>


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
