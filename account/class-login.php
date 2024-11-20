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
            $stmt = $this->pdo->prepare("SELECT id,username,email,password,`account-type` FROM accounts WHERE email = :email AND provider = 'local'");
            $stmt->execute(['email' => $email]);
            $acc = $stmt->fetch(PDO::FETCH_ASSOC);
			$pic = null;
			$business = null;
            if ($acc && password_verify($password, $acc['password'])) {
				if($acc['account-type']=='user'){
					$stmtu = $this->pdo->prepare("SELECT picture from `user-profiles` where id = ?");
					$stmtu->execute(array($acc['id']));
					$user = $stmtu->fetch(PDO::FETCH_ASSOC);
					$pic = $user['picture'];
				}elseif($acc['account-type']=='business'){
					$stmtb = $this->pdo->prepare("SELECT picture,`first-name`,`last-name`,title,description from `business-profiles` where id = ?");
					$stmtb->execute(array($acc['id']));
					$business = $stmtb->fetch(PDO::FETCH_ASSOC);
					$pic = $business['picture'];
				}
				$_SESSION['data'] = array('id'=>$acc['id'],'username'=>$acc['username'],'email'=>$acc['email'],'picture'=>$pic,'title'=>$business['title'],'desc'=>$business['description'],'first-name'=>$business['first-name'],'last-name'=>$business['last-name']);
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
//execute function if acc authenticated...
$auth->isAuthenticated();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    $password = $_POST["password"];
    $auth->login($email, $password);
}
?>