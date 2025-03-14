<?php
if (session_status() === PHP_SESSION_NONE) {
session_start();
}
require('pdo.php');
if(isset($_SESSION['data'])){
	$data = $_SESSION['data'];
}

$allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];

if (isset($_POST['uploadProfile']) && isset($_FILES['profileFile'])) {
    $fileName = basename($_FILES['profileFile']['name']);
    $fileExt = pathinfo($fileName, PATHINFO_EXTENSION);

    // Check if the file extension is allowed
    if (!in_array($fileExt, $allowedExtensions)) {
        echo "Invalid file type.";
        exit;
    }

    // Prepare the file for classification
    $filePath = $_FILES['profileFile']['tmp_name'];
    $cFile = curl_file_create($filePath, mime_content_type($filePath), $fileName);

    // NSFW classification request
    $chNsfw = curl_init();
    curl_setopt($chNsfw, CURLOPT_URL, "http://127.0.0.1:5000/classify/nsfw");
    curl_setopt($chNsfw, CURLOPT_POST, true);
    curl_setopt($chNsfw, CURLOPT_POSTFIELDS, ['file' => $cFile]);
    curl_setopt($chNsfw, CURLOPT_RETURNTRANSFER, true);
    
    $responseNsfw = curl_exec($chNsfw);
    curl_close($chNsfw);

    // Decode the JSON response
    $resultNsfw = json_decode($responseNsfw, true);
    $classification = $resultNsfw['label'] ?? 'Unknown';

    // If classified as NSFW, do not upload
    if (strtolower($classification) === "nsfw") {
        echo "Image not allowed: NSFW content detected.";
        exit;
    }

    // Proceed with saving the image if it's safe
    $uniqueId = uniqid('img_', true);
    $targetDir = "uploads/ProfilePicture/";
    $newFileName = $uniqueId . '.' . $fileExt;
    $targetFilePath = $targetDir . $newFileName;

    if (!is_dir($targetDir)) {
        mkdir($targetDir, 0755, true);
    }
	if(!$data['picture']=="uploads/ProfilePicture/img_1.jpg"&&file_exists($data['picture'])){
		unlink($data['picture']);
	}
    // Move uploaded file to the target directory
    if (move_uploaded_file($filePath, $targetFilePath)) {
        // Save the image details to the database
        try {
            $sql = $pdo->prepare("UPDATE accounts SET picture = ? WHERE id = ?");
            $sql->execute([$targetFilePath,$data['id']]);
			$_SESSION['data']['picture'] = $targetFilePath;
        } catch (Exception $e) {
            echo "Database Error: " . $e->getMessage();
            exit;
        }

        // Output success message
        echo "File uploaded successfully.";
        header('Location: profile.php');
        exit;
    } else {
        echo "File upload failed.";
    }
}

?>