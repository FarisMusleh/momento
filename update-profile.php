<?php
	if (session_status() === PHP_SESSION_NONE) {
    session_start();
	}
	if(!isset($_SESSION['data'])){
		header('location:index.php');
		exit();
	}
	$data = $_SESSION['data'];
	require('pdo.php');
	////
	
	if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Update general info in DB
    if ($_POST["section"] == "general") {
        $username = $_POST["username"];
        $email = $_POST["email"];
        $stmt = $pdo->prepare("UPDATE accounts SET username = ?, email = ? WHERE id = ?");
        $stmt->execute([$username, $email, $data['id']]);

        // Update session
        $_SESSION['data']['username'] = $username;
        $_SESSION['data']['email'] = $email;
        
    } elseif ($_POST["section"] == "edit") {
        $name = $_POST['name'] ?? "";
		$job = $_POST['job'] ?? "";
        $location = $_POST['location'] ?? "";
        $bio = $_POST['bio'] ?? "";

        // Fetch the actual account_type as a string
        $chk = $pdo->prepare('SELECT account_type FROM accounts WHERE id = ?');
        $chk->execute([$data['id']]);
        $account_type = $chk->fetch(PDO::FETCH_ASSOC)['account_type']; // Fix here
		
		$stmt = $pdo->prepare('UPDATE accounts set location = ? where id = ?');
		$stmt->execute([$location,$data['id']]);
        if ($account_type === "user") {
            $stmt = $pdo->prepare("UPDATE user_profiles SET name = ?, bio = ? WHERE id = ?");
            $stmt->execute([$name, $bio, $data['id']]);
            $_SESSION['name'] = $name;
        } elseif ($account_type === "business") {
            $stmt = $pdo->prepare("UPDATE business_profiles SET business_name = ?, job = ?,  bio = ? WHERE id = ?");
            $stmt->execute([$name, $job,$bio, $data['id']]);
            $_SESSION['business_name'] = $name;
            $_SESSION['location'] = $location;
        }

    } elseif ($_POST["section"] == "socials") {
        // Update social media links
        $facebook = $_POST["facebook"] ?? "";
        $twitter = $_POST["twitter"] ?? "";
        $linked_in = $_POST['linked-in'] ?? "";
        $instagram = $_POST['instagram'] ?? "";

        $social_media_json = json_encode([
            "facebook" => $facebook,
            "twitter" => $twitter,
            "linked-in" => $linked_in,
            "instagram" => $instagram
        ]);

        // Fetch the actual account_type as a string
        $chk = $pdo->prepare('SELECT account_type FROM accounts WHERE id = ?');
        $chk->execute([$data['id']]);
        $account_type = $chk->fetch(PDO::FETCH_ASSOC)['account_type']; // Fix here

        if ($account_type == "user") {
            $stmt = $pdo->prepare("UPDATE user_profiles SET social_links = ? WHERE id = ?");
            $stmt->execute([$social_media_json, $data['id']]);
        } elseif ($account_type == "business") {
            $stmt = $pdo->prepare("UPDATE business_profiles SET social_links = ? WHERE id = ?");
            $stmt->execute([$social_media_json, $data['id']]);
        }

    } elseif ($_POST["section"] == "password") {
        $old_password = $_POST["old_password"];
        $new_password = $_POST["new_password"];

        // Check the old password
        $chk = $pdo->prepare('SELECT password FROM accounts WHERE id = ?');
        $chk->execute([$data['id']]);
        $result = $chk->fetch(PDO::FETCH_ASSOC);

        // Verify password
        if (!password_verify($old_password, $result["password"])) {
            echo "Old password is incorrect!";
            exit;
        }

        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("UPDATE accounts SET password = ? WHERE id = ?");
        $stmt->execute([$hashed_password, $data['id']]);

        header("Location: logout.php");
        exit;
    }elseif ($_POST["section"] == "experience") {
        $job = $_POST['job_title'] ?? "";
        $company = $_POST['company_name'] ?? "";
        $start = $_POST['start_date'] ?? "";
		if(isset($_POST['current_job'])){
			$end = "present";
		}else{
			$end = $_POST['end_date'] ?? "";			
		}
		$description = $_POST['description'] ?? "";
		$sql = $pdo->prepare('insert into experience(user_id, start_date,
		end_date, company, job, achievements) values(?,?,?,?,?,?)');
		$sql->execute([$data['id'], $start, $end, $company, $job, $description]);
	}

    // Redirect only if no password change (prevents exit)
    header("Location: edit-profile.php");
    exit;
}



?>