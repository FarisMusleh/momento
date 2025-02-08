<?php
require('../pdo.php');
session_start();

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
			$provider = "local";
			$gender = $data['gender'] ?? null;
			// Insert into accounts table
			$sql_1 = $this->pdo->prepare('insert into accounts(username, email, password, `account-type`, provider) values(?, ?, ?, ?, ?)');
			$sql_1->execute([$username, $email, $hashed, $account, $provider]);

			$lastId = $this->pdo->lastInsertId();

			// Insert into profile table
			if($account==="user"){
				$sql_2 = $this->pdo->prepare("insert into `user-profiles`(id,gender) values(?,?)");
				$sql_2->execute([$lastId,$gender]);
			}elseif($account==="business"){
				$sql_2 = $this->pdo->prepare("insert into `business-profiles`(id,gender) values(?,?)");
				$sql_2->execute([$lastId,$gender]);
			}
			
			$_SESSION['create_success'] = "Account Created!";
			header("Location: login.php");
			exit();
			
		} catch (PDOException $e) {
			if ($e->getCode() == 23000) {
				$_SESSION['duplicate_error'] = "Email already registered!";
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
