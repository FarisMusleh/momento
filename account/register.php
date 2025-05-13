<?php
	if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
	if(isset($_SESSION['data'])){
		header('location: ../index.php');
		exit();
	}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <title>Register | HotelSnap</title>
    <style>
        :root {
            --black: #000000;
            --white: #ffffff;
            --gray-dark: #333333;
            --gray-medium: #666666;
            --gray-light: #dddddd;
            --gray-very-light: #f5f5f5;
            --accent: #4285F4; /* Google blue */
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background-color: var(--gray-very-light);
            color: var(--gray-dark);
            line-height: 1.6;
        }

        .auth-container {
            display: flex;
            min-height: 100vh;
        }

        .auth-hero {
            flex: 1;
            background: linear-gradient(rgba(0, 0, 0, 0.7), rgba(0, 0, 0, 0.8)),
                url('img/road.jpg') no-repeat center center;
            background-size: cover;
            background-position: center;
            color: var(--white);
            padding: 2rem;
            display: flex;
            flex-direction: column;
            justify-content: center;
            position: relative;
        }

        .auth-hero-content {
            max-width: 500px;
            margin: 0 auto;
        }

        .auth-hero h1 {
            font-size: 2.5rem;
            margin-bottom: 1rem;
            font-weight: 700;
        }

        .auth-hero p {
            font-size: 1.1rem;
            margin-bottom: 2rem;
            opacity: 0.9;
        }

        .auth-hero .logo {
            font-size: 2rem;
            font-weight: bold;
            margin-bottom: 2rem;
            display: flex;
            align-items: center;
        }

        .auth-hero .logo i {
            color: var(--white);
            margin-right: 10px;
        }

        .auth-hero a {
            color: var(--white);
            text-decoration: underline;
            font-weight: 500;
        }

        .auth-hero a:hover {
            color: var(--gray-light);
        }

        .auth-forms {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
        }

        .form-container {
            background: var(--white);
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 450px;
            overflow: hidden;
            border: 1px solid var(--gray-light);
        }

        .form-header {
            padding: 1.5rem;
            background: var(--black);
            color: var(--white);
            text-align: center;
        }

        .form-header h2 {
            font-size: 1.5rem;
        }

        .form-body {
            padding: 2rem;
        }

        .form-group {
            margin-bottom: 1.5rem;
            position: relative;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 500;
            color: var(--gray-dark);
        }

        .form-control {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid var(--gray-light);
            border-radius: 5px;
            font-size: 1rem;
            transition: all 0.3s;
            background-color: var(--gray-very-light);
        }

        .form-control:focus {
            border-color: var(--gray-medium);
            box-shadow: 0 0 0 3px rgba(0, 0, 0, 0.1);
            outline: none;
            background-color: var(--white);
        }

        .input-icon {
            position: absolute;
            right: 15px;
            top: 40px;
            color: var(--gray-medium);
        }

        /* Radio Button Styles */
        .radio-group {
            display: flex;
            gap: 1rem;
            margin-top: 0.5rem;
        }

        .radio-option {
            flex: 1;
            position: relative;
        }

        .radio-option input[type="radio"] {
            position: absolute;
            opacity: 0;
            width: 0;
            height: 0;
        }

        .radio-label {
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 12px 15px;
            border: 1px solid var(--gray-light);
            border-radius: 5px;
            background-color: var(--gray-very-light);
            cursor: pointer;
            transition: all 0.3s;
            text-align: center;
        }

        .radio-option input[type="radio"]:checked + .radio-label {
            background-color: var(--black);
            color: var(--white);
            border-color: var(--black);
        }

        .radio-option input[type="radio"]:focus + .radio-label {
            box-shadow: 0 0 0 3px rgba(0, 0, 0, 0.1);
        }

        /* Checkbox Styles */
        .checkbox-group {
            display: flex;
            align-items: flex-start;
            margin-top: 1rem;
        }

        .checkbox-group input[type="checkbox"] {
            position: absolute;
            opacity: 0;
            width: 0;
            height: 0;
        }

        .checkmark {
            position: relative;
            display: inline-block;
            width: 20px;
            height: 20px;
            background-color: var(--gray-very-light);
            border: 1px solid var(--gray-light);
            border-radius: 4px;
            margin-right: 10px;
            flex-shrink: 0;
            cursor: pointer;
        }

        .checkbox-group input[type="checkbox"]:checked ~ .checkmark {
            background-color: var(--black);
            border-color: var(--black);
        }

        .checkmark:after {
            content: "";
            position: absolute;
            display: none;
            left: 7px;
            top: 3px;
            width: 5px;
            height: 10px;
            border: solid var(--white);
            border-width: 0 2px 2px 0;
            transform: rotate(45deg);
        }

        .checkbox-group input[type="checkbox"]:checked ~ .checkmark:after {
            display: block;
        }

        .checkbox-label {
            font-size: 0.9rem;
            color: var(--gray-medium);
            cursor: pointer;
        }

        .checkbox-label a {
            color: var(--black);
            text-decoration: underline;
        }

        /* Button Styles */
        .btn {
            display: inline-block;
            background: var(--black);
            color: var(--white);
            border: none;
            padding: 12px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1rem;
            font-weight: 500;
            width: 100%;
            transition: all 0.3s;
            margin-bottom: 1rem;
        }

        .btn:hover {
            background: var(--gray-dark);
        }

        .btn-google {
            background: var(--white);
            color: var(--gray-dark);
            border: 1px solid var(--gray-light);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .btn-google:hover {
            background: var(--gray-very-light);
            border-color: var(--gray-medium);
        }

        .btn-google i {
            margin-right: 10px;
            color: var(--accent);
            font-size: 1.2rem;
        }

        .form-footer {
            text-align: center;
            margin-top: 1.5rem;
            padding-top: 1.5rem;
            border-top: 1px solid var(--gray-light);
            color: var(--gray-medium);
        }

        .form-footer a {
            color: var(--black);
            text-decoration: none;
            font-weight: 500;
        }

        .form-footer a:hover {
            text-decoration: underline;
        }

        .divider {
            display: flex;
            align-items: center;
            margin: 1.5rem 0;
            color: var(--gray-medium);
        }

        .divider::before,
        .divider::after {
            content: "";
            flex: 1;
            border-bottom: 1px solid var(--gray-light);
        }

        .divider-text {
            padding: 0 1rem;
            font-size: 0.9rem;
        }

        @media (max-width: 768px) {
            .auth-container {
                flex-direction: column;
            }

            .auth-hero {
                padding: 2rem 1rem;
                text-align: center;
            }

            .auth-hero-content {
                max-width: 100%;
            }
            
            .radio-group {
                flex-direction: column;
                gap: 0.5rem;
            }
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
		.btn:disabled{
			opacity: 0.6 !important;
			cursor: not-allowed !important;
		}
    </style>
</head>

<body>
    <div class="auth-container">
        <div class="auth-hero">
            <div class="auth-hero-content">
                <div class="logo">
                   <i class="fas fa-camera"></i>
                    <span>Momento</span>
                </div>

                <p>Join Momento to share your photography, discover talented creators, and book sessions with ease—all in one place.</p>
<p>Already have an account? <a href="signin.html">Sign in here</a> to continue capturing and exploring moments that matter.</p>

            </div>
        </div>

        <div class="auth-forms">
            <div class="form-container">
                <div>
                    <div class="form-header">
                        <h2>Create Your Account</h2>
                    </div>
                    <div class="form-body">

                        <div id="register-message" class="form-message"></div>
                        <form id="register" method = "post" action = "class-register.php">
                            <div class="form-group">
                                <label>Account Type</label>
                                <div class="radio-group">
                                    <div class="radio-option">
                                        <input type="radio" id="user-account" name="account" value="user" checked>
                                        <label for="user-account" class="radio-label">
                                            <i class="fas fa-user" style="margin-right: 8px;"></i>
                                            User
                                        </label>
                                    </div>
                                    <div class="radio-option">
                                        <input type="radio" id="business-account" name="account" value="business">
                                        <label for="business-account" class="radio-label">
                                            <i class="fas fa-briefcase" style="margin-right: 8px;"></i>
                                            Business
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="register-name">Username</label>
                                <input type="text" id="register-name" class="form-control" name = "username"
                                    placeholder="Enter your full name" required>
                                <i class="fas fa-user input-icon"></i>
                            </div>
                            <div class="form-group">
                                <label for="register-email">Email Address</label>
                                <input type="email" id="register-email" class="form-control" name = "email"
                                    placeholder="Enter your email" required>
                                <i class="fas fa-envelope input-icon"></i>
                            </div>

                            <div class="form-group">
                                <label for="register-password">Password</label>
                                <input type="password" id="register-password" class="form-control" name = "password"
                                    placeholder="Create a password" required>
                                <i class="fas fa-lock input-icon"></i>
                            </div>  
                            <div class="checkbox-group" style="margin-bottom: 1rem;">
                                <input type="checkbox" id="terms-agreement" required>
                                <label for="terms-agreement" class="checkmark"></label>
                                <label for="terms-agreement" class="checkbox-label">
                                    I agree to the <a href="#" id = "showTermsBtn">Terms of Service</a>
                                </label>
                            </div>
                            
                            <div class="form-group">
                                <button type="submit" class="btn" id = "submit" disabled>Create Account</button>
                            </div>
							<input type = "hidden" name = "enter">
                        </form>
                        
                        <div class="divider">
                            <span class="divider-text">OR</span>
                        </div>
                        
                        <div class="form-group">
                            <button class="btn btn-google" onclick="location.href='social-media/authentication.php/?auth=google'">
                                <i class="fab fa-google"></i>
                                Sign up with Google
                            </button>
                        </div>
                        
                   <!--      <div class="form-footer">
                            <p>Already have an account? <a href="signin.html">Sign In</a></p>
                        </div> -->
                    </div>
                </div>
            </div>
        </div>
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
		  const checkbox = document.getElementById('terms-agreement');
		  const submitBtn = document.getElementById('submit');

		  checkbox.addEventListener('change', function () {
			submitBtn.disabled = !this.checked;
		  });
		  
    </script>




</body>
</html>