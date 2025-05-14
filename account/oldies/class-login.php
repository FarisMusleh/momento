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
            $stmt = $this->pdo->prepare("SELECT id,username,email,password,account_type,picture,location FROM accounts WHERE email = :email AND provider = 'local'");
            $stmt->execute(['email' => $email]);
            $account = $stmt->fetch(PDO::FETCH_ASSOC);
			$picture = null;
			$business = null;
            if ($account && password_verify($password, $account['password'])) {
				if($account['account_type']=='user'){
					$stmt = $this->pdo->prepare("SELECT name from user_profiles where id = ?");
					$stmt->execute(array($account['id']));
					$user = $stmt->fetch(PDO::FETCH_ASSOC);
					$_SESSION['data'] = array('id'=>$account['id'],'username'=>$account['username'],'email'=>$account['email'],
					'picture'=>$account['picture'],'location'=>$account['location']);
				}
				elseif($account['account_type']=='business'){
					$stmt = $this->pdo->prepare("SELECT business_name from business_profiles where id = ?");
					$stmt->execute(array($account['id']));
					$business = $stmt->fetch(PDO::FETCH_ASSOC);
					$_SESSION['data'] = array('id'=>$account['id'],'username'=>$account['username'],'email'=>$account['email'],'picture'=>$account['picture'],
					'location'=>$account['location']);
				}
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

$auth->isAuthenticated();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    $password = $_POST["password"];
    $auth->login($email, $password);
}
?>