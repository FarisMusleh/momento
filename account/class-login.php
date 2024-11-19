<?php
session_start();
require_once('../pdo.php');

class account {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function isAuthenticated() {
        if (isset($_SESSION['data'])) {
            header('Location: index.php');
            exit();
        }
    }

    public function login($email, $password) {
        try {
            // Prepare the statement
            $stmt = $this->pdo->prepare("SELECT username,email,password FROM accounts WHERE email = :email AND provider = 'local'");
            $stmt->execute(['email' => $email]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user && password_verify($password, $user['password'])) {
				$_SESSION['data'] = array('username'=>$user['username'],'email'=>$user['email']);
                header("Location: ../index.php");
                exit();
            } else {
				$_SESSION['invalid_info'] = "Invalid email or password!";
				header("Location: login.php");
                exit();
            }
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
            exit();
        }
    }
}
//Main
$auth = new account($pdo);
//execute function if user authenticated...
$auth->isAuthenticated();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    $password = $_POST["password"];
    $auth->login($email, $password);
}
?>