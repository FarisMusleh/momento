<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
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
            
            if ($account && password_verify($password, $account['password'])) {
				if($account['email']=="admin@momento.com"){
					$_SESSION['data']['admin'] = True;
					header("Location: ../admin/index.php");
					exit();
				}
                $hasProfile = false;
                
                if($account['account_type'] == 'user') {
                    $stmt = $this->pdo->prepare("SELECT name FROM user_profiles WHERE id = ?");
                    $stmt->execute([$account['id']]);
                    $user = $stmt->fetch(PDO::FETCH_ASSOC);
                    
           
                    if($user && !empty($user['name'])) {
                        $hasProfile = true;
                        $_SESSION['data'] = array(
                            'id' => $account['id'],
                            'username' => $account['username'],
                            'email' => $account['email'],
                            'picture' => $account['picture'],
                            'location' => $account['location']
                        );
                    }
                }
                elseif($account['account_type'] == 'business') {
                    $stmt = $this->pdo->prepare("SELECT business_name FROM business_profiles WHERE id = ?");
                    $stmt->execute([$account['id']]);
                    $business = $stmt->fetch(PDO::FETCH_ASSOC);
       
                    if($business && !empty($business['business_name'])) {
                        $hasProfile = true;
                        $_SESSION['data'] = array(
                            'id' => $account['id'],
                            'username' => $account['username'],
                            'email' => $account['email'],
                            'picture' => $account['picture'],
                            'location' => $account['location']
                        );
                    }
                }
      
                if($hasProfile) {
        
                    header("Location: ../index.php");
                    exit();
                } else {
          
                    $_SESSION['create_profile'] = [
                        'id' => $account['id'],
                        'email' => $account['email'],
                        'username' => $account['username'],
                        'type' => $account['account_type'],
                        'picture' => $account['picture']
                    ];
                    header("Location: create_profile.php");
                    exit();
                }
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

$auth = new account($pdo);

$auth->isAuthenticated();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    $password = $_POST["password"];
    $auth->login($email, $password);
}
?>