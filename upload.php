<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['data'])) {
    header('location:index.php');
    exit();
}
$data = $_SESSION['data'];
require('pdo.php');

$allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];

if (isset($_POST['upload']) && isset($_FILES['file'])) {
    $fileName = basename($_FILES['file']['name']);
    $fileExt = pathinfo($fileName, PATHINFO_EXTENSION);

    if (!in_array($fileExt, $allowedExtensions)) {
        $_SESSION['message'] = "Image not allowed: Invalid file type.";
        exit;
    }

    $filePath = $_FILES['file']['tmp_name'];
    $imageInfo = getimagesize($filePath);
    $cFile = curl_file_create($filePath, mime_content_type($filePath), $fileName);

    if ($imageInfo) {
        $width = $imageInfo[0];
        $height = $imageInfo[1];
        if ($width < 600 || $height < 600) {
			$_SESSION['message'] = "Image not allowed : Image size too small.";
            header('Location: ' . $_SERVER['HTTP_REFERER']);
            exit();
        }
    }

    // STEP 1: NSFW check
    $chNsfw = curl_init("http://127.0.0.1:5000/classify/nsfw");
    curl_setopt_array($chNsfw, [
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => ['file' => $cFile],
        CURLOPT_RETURNTRANSFER => true
    ]);
    $responseNsfw = curl_exec($chNsfw);
    curl_close($chNsfw);
    $resultNsfw = json_decode($responseNsfw, true);
    $classification = $resultNsfw['label'] ?? 'Unknown';

    if (strtolower($classification) === 'nsfw') {
        $_SESSION['message'] = "Image not allowed: NSFW content detected.";
        exit;
    }

	// STEP 2: CLIP classification + violence detection (combined)
	$chClip = curl_init("http://127.0.0.1:5000/classify/clip");
	curl_setopt_array($chClip, [
		CURLOPT_POST => true,
		CURLOPT_POSTFIELDS => ['file' => $cFile],
		CURLOPT_RETURNTRANSFER => true
	]);
	$responseClip = curl_exec($chClip);
	curl_close($chClip);

	// Decode response
	$resultClip = json_decode($responseClip, true);
	$topLabels = $resultClip['labels'] ?? [];
	$isViolent = $resultClip['flag_violence'] ?? false; // Will be true/false
	if($isViolent){
		exit();
	}
	// Save to disk
	$uniqueId = uniqid('img_', true);
	$targetDir = "uploads/Images/";
	$newFileName = $uniqueId . '.' . $fileExt;
	$targetFilePath = $targetDir . $newFileName;

	if (!is_dir($targetDir)) {
		mkdir($targetDir, 0755, true);
	}


    if (move_uploaded_file($filePath, $targetFilePath)) {
        // Save to DB
        try {
            $sql = $pdo->prepare('INSERT INTO images (user_id, url, title, file_name, description, label) VALUES (?, ?, ?, ?, ?, ?)');
            $sql->execute([
                $data['id'],
                $targetFilePath,
                $_POST['title'],
                $newFileName,
                $_POST['description'],
                json_encode($topLabels)
            ]);
			$_SESSION['message'] = "Image Uploaded Successfully";
			$_SESSION['color'] = "green";
        } catch (Exception $e) {
            $_SESSION['message'] = "File upload failed.";
            exit;
        }

        header('Location: profile.php');
        exit;
    } else {
        $_SESSION['message'] = "File upload failed.";
    }
	
}
?>
