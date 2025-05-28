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
    <title>Momento | Member Access</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background-color: #f5f5f5;
            color: #333;
            line-height: 1.6;
            width: 100%;
            height: 100vh;
            overflow: hidden;
        }

        .auth-container {
            display: flex;
            height: 100vh;
        }

        .auth-hero {
            flex: 1;
            background: linear-gradient(rgba(0, 0, 0, 0.7), rgba(0, 0, 0, 0.8)),
                url('img/photography.webp') no-repeat center center;
            background-size: cover;
            background-position: center;
            color: white;
            padding: 2rem;
            display: flex;
            flex-direction: column;
            justify-content: center;
            position: relative;
            overflow: hidden;
        }

        .auth-hero-content {
            max-width: 500px;
            margin: 0 auto;
        }

        .auth-hero h1 {
            font-size: 2.2rem;
            margin-bottom: 1rem;
            font-weight: 700;
        }

        .auth-hero p {
            font-size: 1rem;
            margin-bottom: 1.5rem;
            opacity: 0.9;
        }

        .auth-hero .logo {
            font-size: 1.8rem;
            font-weight: bold;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
        }

        .auth-hero .logo i {
            color: azure;
            margin-right: 10px;
        }

        .auth-forms {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1.5rem;
            overflow: auto;
        }

        .form-container {
            background: white;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
            overflow: hidden;
            border: 1px solid #e0e0e0;
        }

        .form-header {
            padding: 1.2rem;
            background: #000;
            color: white;
            text-align: center;
        }

        .form-header h2 {
            font-size: 1.3rem;
        }

        .form-body {
            padding: 1.5rem;
        }

        .form-group {
            margin-bottom: 1.2rem;
            position: relative;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 500;
            color: #333;
        }

        .form-control {
            width: 100%;
            padding: 10px 12px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 0.95rem;
            transition: all 0.3s;
            background-color: #f9f9f9;
        }

        .form-control:focus {
            border-color: #888;
            box-shadow: 0 0 0 3px rgba(0, 0, 0, 0.1);
            outline: none;
            background-color: #fff;
        }

        .input-icon {
            position: absolute;
            right: 12px;
            top: 35px;
            color: #888;
            font-size: 0.9rem;
        }

        .btn {
            display: inline-block;
            background: #000;
            color: white;
            border: none;
            padding: 10px 15px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 0.95rem;
            font-weight: 500;
            width: 100%;
            transition: all 0.3s;
            margin-bottom: 0.8rem;
        }

        .btn:hover {
            background: #333;
        }

        .btn-google {
            background: #fff;
            color: #333;
            border: 1px solid #ddd;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .btn-google:hover {
            background: #f5f5f5;
            border-color: #ccc;
        }

        .btn-google i {
            margin-right: 8px;
            color: #4285F4;
            font-size: 1.1rem;
        }

        .form-footer {
            text-align: center;
            margin-top: 1.2rem;
            padding-top: 1.2rem;
            border-top: 1px solid #eee;
            color: #666;
            font-size: 0.9rem;
        }

        .form-footer a {
            color: #000;
            text-decoration: none;
            font-weight: 500;
        }

        .form-footer a:hover {
            text-decoration: underline;
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

        .divider-text {
            padding: 0 0.8rem;
        }

        @media (max-width: 768px) {
            body {
                overflow: auto;
            }
            
            .auth-container {
                flex-direction: column;
                height: auto;
                min-height: 100vh;
            }

            .auth-hero {
                padding: 1.5rem 1rem;
                text-align: center;
            }

            .auth-hero-content {
                max-width: 100%;
            }
            
            .auth-forms {
                padding: 1.5rem;
            }
        }
    </style>
	<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
</head>

<body>

    <div class="auth-container">
        <div class="auth-hero">
            <div class="auth-hero-content">
                <div class="logo">
               <i class="fas fa-camera"></i>

                    <span>Momento</span>
                </div>
                <h1>Welcome Back!</h1>
                <p>Sign in to share your photos, book sessions, and save your favorite moments.</p>
<p>New to Momento? Join now to connect with photographers and explore beautiful work.</p>

            </div>
        </div>

        <div class="auth-forms">
            <div class="form-container">
                <!-- Sign In Form -->
                <div>
                    <div class="form-header">
                        <h2>
                 <i class="fas fa-camera"></i>
                    Sign In to Momento</h2>
                    </div>
                    <div class="form-body">
                        <div id="signin-message" class="form-message"></div>
                        <form id="signin" method = "post" action = "class-login.php">
                            <div class="form-group">
                                <label for="signin-email">Email Address</label>
                                <input type="email" id="signin-email" class="form-control" name = "email"
                                    placeholder="Enter your email" required>
                                <i class="fas fa-envelope input-icon"></i>
                            </div>
                            <div class="form-group">
                                <label for="signin-password">Password</label>
                                <input type="password" id="signin-password" class="form-control" name = "password"
                                    placeholder="Enter your password" required>
                                <i class="fas fa-lock input-icon"></i>
                            </div>
                            <div class="form-group">
                                <button type="submit" class="btn">Sign In</button>
                            </div>
                        </form>

                        <div class="divider">
                            <span class="divider-text">OR</span>
                        </div>

                        <div class="form-group">
                            <button class="btn btn-google" onclick="location.href='social-media/authentication.php/?auth=google'">
                                <i class="fab fa-google"></i>
                                Sign in with Google
                            </button>
                        </div>

                        <div class="form-footer">
                            <p>Don't have an account? <a href="register.php">Register</a></p>
                        
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
<?php
// At the end of register.php, replace the current toast script with this:

if(isset($_SESSION['login_create'])){
    $message = $_SESSION['login_create'];
    echo '<script>
    document.addEventListener("DOMContentLoaded", function() {
        Toastify({
            text: "' . addslashes($message) . '",
            duration: 3000,
            gravity: "top",
            position: "right",
            backgroundColor: "#00FF00", 
            stopOnFocus: true
        }).showToast();
    });
    </script>';
    
    // Clear the session messages
    unset($_SESSION['login_create']);
}
?>
<?php
// At the end of register.php, replace the current toast script with this:

if(isset($_SESSION['invalid_info'])){
    $message = $_SESSION['invalid_info'];
    echo '<script>
    document.addEventListener("DOMContentLoaded", function() {
        Toastify({
            text: "' . addslashes($message) . '",
            duration: 3000,
            gravity: "top",
            position: "right",
            backgroundColor: "#dc3545", 
            stopOnFocus: true
        }).showToast();
    });
    </script>';
    
    // Clear the session messages
    unset($_SESSION['invalid_info']);
}
?>
</html>