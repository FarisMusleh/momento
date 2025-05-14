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
            
            $checkUser = $this->pdo->prepare("SELECT id FROM accounts WHERE email = ?");
            $checkUser->execute([$email]);
            $existingUser = $checkUser->fetch(PDO::FETCH_ASSOC);

            if ($existingUser) {
                $_SESSION['login_message'] = "An account with this email already exists. Please login.";
                header("Location: login.php");
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

            $_SESSION['login_message'] = "Registration successful! Please login with your credentials.";
            header("Location: login.php");
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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $account = new register($pdo);

    if (isset($_POST['enter'])) {
        $account->create($_POST);
    }
}
?>