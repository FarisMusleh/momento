<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
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
	<link href="https://fonts.googleapis.com/css2?family=Dancing+Script&family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <title>Momento - Information Form</title>
  <style>
    body {
      margin: 0;
      padding: 0;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background: linear-gradient(135deg,rgb(132, 133, 134),rgb(17, 17, 17));
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
.divider {
            display: flex;
            align-items: center;
            margin: 1.2rem 0;
            color: #999;
            font-size: 0.85rem;
        }

        .divider::before,
        .divider::after {
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
  
  #acc-type {
    -webkit-appearance: none;
    -moz-appearance: none;
    appearance: none;
    padding-right: 35px;
  }
 #location {
    -webkit-appearance: none;
    -moz-appearance: none;
    appearance: none;
    padding-right: 35px;
  }

  </style>
</head>
<body>
  <div class="container">
    <div class="logo" style="font-family:Dancing Script">Momento</div>
    <div class="divider">
          <span>Complete Your Profile</span>
    </div>
    <form id="registrationForm" method="post">
     <div class="form-group">
    <label for="username">
      <i class="fas fa-user" style="margin-right: 8px;"></i>Username
    </label>
    <div class="input-with-icon">
      <i class="fas fa-user input-icon"></i>
      <input type="text" id="username" name="username" required placeholder="John"/>
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
      <select  id="location" name="location" required>
        <option value="">Select Location</option>
        <option value="">Select Your Country</option>
    <option value="Afghanistan">Afghanistan</option>
    <option value="Albania">Albania</option>
    <option value="Algeria">Algeria</option>
    <option value="American Samoa">American Samoa</option>
    <option value="Andorra">Andorra</option>
    <option value="Angola">Angola</option>
    <option value="Anguilla">Anguilla</option>
    <option value="Antarctica">Antarctica</option>
    <option value="Antigua and Barbuda">Antigua and Barbuda</option>
    <option value="Argentina">Argentina</option>
    <option value="Armenia">Armenia</option>
    <option value="Aruba">Aruba</option>
    <option value="Australia">Australia</option>
    <option value="Austria">Austria</option>
    <option value="Azerbaijan">Azerbaijan</option>
    <option value="Bahamas">Bahamas</option>
    <option value="Bahrain">Bahrain</option>
    <option value="Bangladesh">Bangladesh</option>
    <option value="Barbados">Barbados</option>
    <option value="Belarus">Belarus</option>
    <option value="Belgium">Belgium</option>
    <option value="Belize">Belize</option>
    <option value="Benin">Benin</option>
    <option value="Bermuda">Bermuda</option>
    <option value="Bhutan">Bhutan</option>
    <option value="Bolivia, Plurinational State of">Bolivia, Plurinational State of</option>
    <option value="Bonaire, Sint Eustatius and Saba">Bonaire, Sint Eustatius and Saba</option>
    <option value="Bosnia and Herzegovina">Bosnia and Herzegovina</option>
    <option value="Botswana">Botswana</option>
    <option value="Bouvet Island">Bouvet Island</option>
    <option value="Brazil">Brazil</option>
    <option value="British Indian Ocean Territory">British Indian Ocean Territory</option>
    <option value="Brunei Darussalam">Brunei Darussalam</option>
    <option value="Bulgaria">Bulgaria</option>
    <option value="Burkina Faso">Burkina Faso</option>
    <option value="Burundi">Burundi</option>
    <option value="Cabo Verde">Cabo Verde</option>
    <option value="Cambodia">Cambodia</option>
    <option value="Cameroon">Cameroon</option>
    <option value="Canada">Canada</option>
    <option value="Cayman Islands">Cayman Islands</option>
    <option value="Central African Republic">Central African Republic</option>
    <option value="Chad">Chad</option>
    <option value="Chile">Chile</option>
    <option value="China">China</option>
    <option value="Christmas Island">Christmas Island</option>
    <option value="Cocos (Keeling) Islands">Cocos (Keeling) Islands</option>
    <option value="Colombia">Colombia</option>
    <option value="Comoros">Comoros</option>
    <option value="Congo">Congo</option>
    <option value="Congo, The Democratic Republic of the">Congo, The Democratic Republic of the</option>
    <option value="Cook Islands">Cook Islands</option>
    <option value="Costa Rica">Costa Rica</option>
    <option value="Croatia">Croatia</option>
    <option value="Cuba">Cuba</option>
    <option value="Curaçao">Curaçao</option>
    <option value="Cyprus">Cyprus</option>
    <option value="Czechia">Czechia</option>
    <option value="Côte d'Ivoire">Côte d'Ivoire</option>
    <option value="Denmark">Denmark</option>
    <option value="Djibouti">Djibouti</option>
    <option value="Dominica">Dominica</option>
    <option value="Dominican Republic">Dominican Republic</option>
    <option value="Ecuador">Ecuador</option>
    <option value="Egypt">Egypt</option>
    <option value="El Salvador">El Salvador</option>
    <option value="Equatorial Guinea">Equatorial Guinea</option>
    <option value="Eritrea">Eritrea</option>
    <option value="Estonia">Estonia</option>
    <option value="Eswatini">Eswatini</option>
    <option value="Ethiopia">Ethiopia</option>
    <option value="Falkland Islands (Malvinas)">Falkland Islands (Malvinas)</option>
    <option value="Faroe Islands">Faroe Islands</option>
    <option value="Fiji">Fiji</option>
    <option value="Finland">Finland</option>
    <option value="France">France</option>
    <option value="French Guiana">French Guiana</option>
    <option value="French Polynesia">French Polynesia</option>
    <option value="French Southern Territories">French Southern Territories</option>
    <option value="Gabon">Gabon</option>
    <option value="Gambia">Gambia</option>
    <option value="Georgia">Georgia</option>
    <option value="Germany">Germany</option>
    <option value="Ghana">Ghana</option>
    <option value="Gibraltar">Gibraltar</option>
    <option value="Greece">Greece</option>
    <option value="Greenland">Greenland</option>
    <option value="Grenada">Grenada</option>
    <option value="Guadeloupe">Guadeloupe</option>
    <option value="Guam">Guam</option>
    <option value="Guatemala">Guatemala</option>
    <option value="Guernsey">Guernsey</option>
    <option value="Guinea">Guinea</option>
    <option value="Guinea-Bissau">Guinea-Bissau</option>
    <option value="Guyana">Guyana</option>
    <option value="Haiti">Haiti</option>
    <option value="Heard Island and McDonald Islands">Heard Island and McDonald Islands</option>
    <option value="Holy See (Vatican City State)">Holy See (Vatican City State)</option>
    <option value="Honduras">Honduras</option>
    <option value="Hong Kong">Hong Kong</option>
    <option value="Hungary">Hungary</option>
    <option value="Iceland">Iceland</option>
    <option value="India">India</option>
    <option value="Indonesia">Indonesia</option>
    <option value="Iran, Islamic Republic of">Iran, Islamic Republic of</option>
    <option value="Iraq">Iraq</option>
    <option value="Ireland">Ireland</option>
    <option value="Isle of Man">Isle of Man</option>
    <option value="Israel">Israel</option>
    <option value="Italy">Italy</option>
    <option value="Jamaica">Jamaica</option>
    <option value="Japan">Japan</option>
    <option value="Jersey">Jersey</option>
    <option value="Jordan">Jordan</option>
    <option value="Kazakhstan">Kazakhstan</option>
    <option value="Kenya">Kenya</option>
    <option value="Kiribati">Kiribati</option>
    <option value="Korea, Democratic People's Republic of">Korea, Democratic People's Republic of</option>
    <option value="Korea, Republic of">Korea, Republic of</option>
    <option value="Kuwait">Kuwait</option>
    <option value="Kyrgyzstan">Kyrgyzstan</option>
    <option value="Lao People's Democratic Republic">Lao People's Democratic Republic</option>
    <option value="Latvia">Latvia</option>
    <option value="Lebanon">Lebanon</option>
    <option value="Lesotho">Lesotho</option>
    <option value="Liberia">Liberia</option>
    <option value="Libya">Libya</option>
    <option value="Liechtenstein">Liechtenstein</option>
    <option value="Lithuania">Lithuania</option>
    <option value="Luxembourg">Luxembourg</option>
    <option value="Macao">Macao</option>
    <option value="Madagascar">Madagascar</option>
    <option value="Malawi">Malawi</option>
    <option value="Malaysia">Malaysia</option>
    <option value="Maldives">Maldives</option>
    <option value="Mali">Mali</option>
    <option value="Malta">Malta</option>
    <option value="Marshall Islands">Marshall Islands</option>
    <option value="Martinique">Martinique</option>
    <option value="Mauritania">Mauritania</option>
    <option value="Mauritius">Mauritius</option>
    <option value="Mayotte">Mayotte</option>
    <option value="Mexico">Mexico</option>
    <option value="Micronesia, Federated States of">Micronesia, Federated States of</option>
    <option value="Moldova, Republic of">Moldova, Republic of</option>
    <option value="Monaco">Monaco</option>
    <option value="Mongolia">Mongolia</option>
    <option value="Montenegro">Montenegro</option>
    <option value="Montserrat">Montserrat</option>
    <option value="Morocco">Morocco</option>
    <option value="Mozambique">Mozambique</option>
    <option value="Myanmar">Myanmar</option>
    <option value="Namibia">Namibia</option>
    <option value="Nauru">Nauru</option>
    <option value="Nepal">Nepal</option>
    <option value="Netherlands">Netherlands</option>
    <option value="New Caledonia">New Caledonia</option>
    <option value="New Zealand">New Zealand</option>
    <option value="Nicaragua">Nicaragua</option>
    <option value="Niger">Niger</option>
    <option value="Nigeria">Nigeria</option>
    <option value="Niue">Niue</option>
    <option value="Norfolk Island">Norfolk Island</option>
    <option value="North Macedonia">North Macedonia</option>
    <option value="Northern Mariana Islands">Northern Mariana Islands</option>
    <option value="Norway">Norway</option>
    <option value="Oman">Oman</option>
    <option value="Pakistan">Pakistan</option>
    <option value="Palau">Palau</option>
    <option value="Palestine, State of">Palestine, State of</option>
    <option value="Panama">Panama</option>
    <option value="Papua New Guinea">Papua New Guinea</option>
    <option value="Paraguay">Paraguay</option>
    <option value="Peru">Peru</option>
    <option value="Philippines">Philippines</option>
    <option value="Pitcairn">Pitcairn</option>
    <option value="Poland">Poland</option>
    <option value="Portugal">Portugal</option>
    <option value="Puerto Rico">Puerto Rico</option>
    <option value="Qatar">Qatar</option>
    <option value="Romania">Romania</option>
    <option value="Russian Federation">Russian Federation</option>
    <option value="Rwanda">Rwanda</option>
    <option value="Réunion">Réunion</option>
    <option value="Saint Barthélemy">Saint Barthélemy</option>
    <option value="Saint Helena, Ascension and Tristan da Cunha">Saint Helena, Ascension and Tristan da Cunha</option>
    <option value="Saint Kitts and Nevis">Saint Kitts and Nevis</option>
    <option value="Saint Lucia">Saint Lucia</option>
    <option value="Saint Martin (French part)">Saint Martin (French part)</option>
    <option value="Saint Pierre and Miquelon">Saint Pierre and Miquelon</option>
    <option value="Saint Vincent and the Grenadines">Saint Vincent and the Grenadines</option>
    <option value="Samoa">Samoa</option>
    <option value="San Marino">San Marino</option>
    <option value="Sao Tome and Principe">Sao Tome and Principe</option>
    <option value="Saudi Arabia">Saudi Arabia</option>
    <option value="Senegal">Senegal</option>
    <option value="Serbia">Serbia</option>
    <option value="Seychelles">Seychelles</option>
    <option value="Sierra Leone">Sierra Leone</option>
    <option value="Singapore">Singapore</option>
    <option value="Sint Maarten (Dutch part)">Sint Maarten (Dutch part)</option>
    <option value="Slovakia">Slovakia</option>
    <option value="Slovenia">Slovenia</option>
    <option value="Solomon Islands">Solomon Islands</option>
    <option value="Somalia">Somalia</option>
    <option value="South Africa">South Africa</option>
    <option value="South Georgia and the South Sandwich Islands">South Georgia and the South Sandwich Islands</option>
    <option value="South Sudan">South Sudan</option>
    <option value="Spain">Spain</option>
    <option value="Sri Lanka">Sri Lanka</option>
    <option value="Sudan">Sudan</option>
    <option value="Suriname">Suriname</option>
    <option value="Svalbard and Jan Mayen">Svalbard and Jan Mayen</option>
    <option value="Sweden">Sweden</option>
    <option value="Switzerland">Switzerland</option>
    <option value="Syrian Arab Republic">Syrian Arab Republic</option>
    <option value="Taiwan, Province of China">Taiwan, Province of China</option>
    <option value="Tajikistan">Tajikistan</option>
    <option value="Tanzania, United Republic of">Tanzania, United Republic of</option>
    <option value="Thailand">Thailand</option>
    <option value="Timor-Leste">Timor-Leste</option>
    <option value="Togo">Togo</option>
    <option value="Tokelau">Tokelau</option>
    <option value="Tonga">Tonga</option>
    <option value="Trinidad and Tobago">Trinidad and Tobago</option>
    <option value="Tunisia">Tunisia</option>
    <option value="Turkey">Turkey</option>
    <option value="Turkmenistan">Turkmenistan</option>
    <option value="Turks and Caicos Islands">Turks and Caicos Islands</option>
    <option value="Tuvalu">Tuvalu</option>
    <option value="Uganda">Uganda</option>
    <option value="Ukraine">Ukraine</option>
    <option value="United Arab Emirates">United Arab Emirates</option>
    <option value="United Kingdom">United Kingdom</option>
    <option value="United States">United States</option>
    <option value="United States Minor Outlying Islands">United States Minor Outlying Islands</option>
    <option value="Uruguay">Uruguay</option>
    <option value="Uzbekistan">Uzbekistan</option>
    <option value="Vanuatu">Vanuatu</option>
    <option value="Venezuela, Bolivarian Republic of">Venezuela, Bolivarian Republic of</option>
    <option value="Viet Nam">Viet Nam</option>
    <option value="Virgin Islands, British">Virgin Islands, British</option>
    <option value="Virgin Islands, U.S.">Virgin Islands, U.S.</option>
    <option value="Wallis and Futuna">Wallis and Futuna</option>
    <option value="Western Sahara">Western Sahara</option>
    <option value="Yemen">Yemen</option>
    <option value="Zambia">Zambia</option>
    <option value="Zimbabwe">Zimbabwe</option>
    <option value="Åland Islands">Åland Islands</option>
      </select>
    </div>
  </div>
   
  
  <div class="mt-3 flex">
			<input type = "checkbox" style = "width:15px;height:15px;" id="agree">
			<lable><a id="showTermsBtn">Show Terms</a></label>
		</div>
  
  <button id="submit" type="submit" class="btn btn-dark text-light" disabled>
    <i class="fas fa-check-circle" style="margin-right: 8px;"></i>Confirm
  </button>
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
