<?php
require('../pdo.php');
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

class register {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function create($data) {
        try {
            $username = !empty($data['username']) ? $data['username'] : null;
            $email = !empty($data['email']) ? $data['email'] : null;
            $password = !empty($data['password']) ? $data['password'] : null;
            $hashed = password_hash($password, PASSWORD_DEFAULT);
            $account = $data['account'] ?? null;
            
            // First check if the email already exists
            $checkUser = $this->pdo->prepare("SELECT id, username, email, account_type, picture FROM accounts WHERE email = ?");
            $checkUser->execute([$email]);
            $existingUser = $checkUser->fetch(PDO::FETCH_ASSOC);
            
            // If user exists, check if they have a profile
            if ($existingUser) {
                // Verify password
                $checkPassword = $this->pdo->prepare("SELECT password FROM accounts WHERE id = ?");
                $checkPassword->execute([$existingUser['id']]);
                $storedPassword = $checkPassword->fetchColumn();
                
                if (password_verify($password, $storedPassword)) {
                    // Check if user has a complete profile
                    if ($existingUser['account_type'] == 'user') {
                        $checkProfile = $this->pdo->prepare("SELECT name FROM user_profiles WHERE id = ?");
                    } else {
                        $checkProfile = $this->pdo->prepare("SELECT business_name FROM business_profiles WHERE id = ?");
                    }
                    $checkProfile->execute([$existingUser['id']]);
                    $hasProfile = $checkProfile->fetch();
                    
                    if (!$hasProfile || empty($hasProfile[0])) {
                        // User exists but has no complete profile, redirect to create profile
                        $_SESSION['create_profile'] = [
                            'id' => $existingUser['id'],
                            'email' => $existingUser['email'],
                            'username' => $existingUser['username'],
                            'type' => $existingUser['account_type'],
                            'picture' => $existingUser['picture']
                        ];
                        header("Location: create_profile.php");
                        exit();
                    } else {
                        // User already has a profile, redirect to login
                        $_SESSION['login_message'] = "You already have an account. Please login.";
                        header("Location: login.php");
                        exit();
                    }
                } else {
                    // Password doesn't match
                    $_SESSION['duplicate_error'] = "Email already registered with different password!";
                    header("Location: register.php");
                    exit();
                }
            }
            
            // Create new user if email doesn't exist
            // Insert into accounts table
            $sql = $this->pdo->prepare("INSERT INTO accounts(username, email, password, account_type, provider) VALUES(?, ?, ?, ?, 'local')");
            $sql->execute([$username, $email, $hashed, $account]);

            $last_id = $this->pdo->lastInsertId();

            // Insert into profile table - FIXED PARAMETER COUNT
            if ($account === "user") {
                $sql = $this->pdo->prepare("INSERT INTO user_profiles(id) VALUES(?)");
                $sql->execute([$last_id]);
            } elseif ($account === "business") {
                $sql = $this->pdo->prepare("INSERT INTO business_profiles(id) VALUES(?)");
                $sql->execute([$last_id]);
            }
            
            $_SESSION['create_profile'] = [
                            'id' => $last_id,
                            'email' => $email,
                            'username' => $username,
                            'type' => $account
                        ];

           
            header("Location: create_profile.php");
            exit();
            
        } catch (PDOException $e) {
            if ($e->getCode() == 23000) {
                $_SESSION['duplicate_error'] = "Error creating account: " . $e->getMessage();
                header("Location: register.php");
            } else {
                echo "Database error: " . $e->getMessage();
            }
            exit();
        }
    }
}

// handle the form...
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    //instantiate register...
    $account = new register($pdo);

    if (isset($_POST['enter'])) {
        $account->create($_POST);
    }
}
?>