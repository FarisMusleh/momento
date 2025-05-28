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
            
            // Check if email already exists
            $checkEmail = $this->pdo->prepare("SELECT id FROM accounts WHERE email = ?");
            $checkEmail->execute([$email]);
            $existingEmail = $checkEmail->fetch(PDO::FETCH_ASSOC);

            if ($existingEmail) {
                $_SESSION['register_message'] = "An account with this email already exists.";
				$_SESSION['color'] = "#dc3545";
                header("Location: register.php");
                exit();
            }
            
            // Check if username already exists
            $checkUsername = $this->pdo->prepare("SELECT id FROM accounts WHERE username = ?");
            $checkUsername->execute([$username]);
            $existingUsername = $checkUsername->fetch(PDO::FETCH_ASSOC);

            if ($existingUsername) {
                $_SESSION['register_message'] = "This username is already taken. Please choose a different username.";
				$_SESSION['color'] = "#dc3545";
                header("Location: register.php");
                exit();
            }
            
            $sql = $this->pdo->prepare("INSERT INTO accounts(username, email, password, account_type, provider) VALUES(?, ?, ?, ?, 'local')");
            $sql->execute([$username, $email, $hashed, $account]);

            $last_id = $this->pdo->lastInsertId();

            if ($account === "user") {
                $sql = $this->pdo->prepare("INSERT INTO user_profiles(id) VALUES(?)");
                $sql->execute([$last_id]);
            } elseif ($account === "business") {
                $sql = $this->pdo->prepare("INSERT INTO business_profiles(id) VALUES(?)");
                $sql->execute([$last_id]);
            }

            $_SESSION['login_create'] = "Registration successful! Please login with your credentials.";
			$_SESSION['color'] = "#00FF00";
            header("Location: login.php");
            exit();
            
        } catch (PDOException $e) {
            if ($e->getCode() == 23000) {
                $_SESSION['register_message'] = "Error creating account: " . $e->getMessage();
				$_SESSION['color'] = "#dc3545";
                header("Location: register.php");
            } else {
                $_SESSION['register_message'] = "Something Went Wrong!";
				$_SESSION['color'] = "#dc3545";
                header("Location: register.php");
            }
            exit();
        }
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $account = new register($pdo);

    if (isset($_POST['enter'])) {
        $account->create($_POST);
    }
}
?>