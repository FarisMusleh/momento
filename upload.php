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

$allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];

if (isset($_POST['upload']) && isset($_FILES['file'])) {
    $fileName = basename($_FILES['file']['name']);
    $fileExt = pathinfo($fileName, PATHINFO_EXTENSION);

    // Check if the file extension is allowed
    if (!in_array($fileExt, $allowedExtensions)) {
        echo "Invalid file type.";
        exit;
    }

    // Prepare the file for classification
    $filePath = $_FILES['file']['tmp_name'];
    $cFile = curl_file_create($filePath, mime_content_type($filePath), $fileName);

    // Prepare cURL multi handle
    $multiCurl = curl_multi_init();
    $curlHandles = [];

    // NSFW classification request
    $chNsfw = curl_init();
    curl_setopt($chNsfw, CURLOPT_URL, "http://127.0.0.1:5000/classify/nsfw");
    curl_setopt($chNsfw, CURLOPT_POST, true);
    curl_setopt($chNsfw, CURLOPT_POSTFIELDS, ['file' => $cFile]);
    curl_setopt($chNsfw, CURLOPT_RETURNTRANSFER, true);
    curl_multi_add_handle($multiCurl, $chNsfw);
    $curlHandles['nsfw'] = $chNsfw;

    // CLIP classification request
    $chClip = curl_init();
    curl_setopt($chClip, CURLOPT_URL, "http://127.0.0.1:5000/classify/clip");
    curl_setopt($chClip, CURLOPT_POST, true);
    curl_setopt($chClip, CURLOPT_POSTFIELDS, ['file' => $cFile]);
    curl_setopt($chClip, CURLOPT_RETURNTRANSFER, true);
    curl_multi_add_handle($multiCurl, $chClip);
    $curlHandles['clip'] = $chClip;

    // Execute multi cURL
    $running = null;
    do {
        curl_multi_exec($multiCurl, $running);
        usleep(100); // small delay to avoid 100% CPU usage
    } while ($running > 0);

    // Get the responses for both requests
    $responseNsfw = curl_multi_getcontent($curlHandles['nsfw']);
    $responseClip = curl_multi_getcontent($curlHandles['clip']);

    // Close all cURL handles
    curl_multi_remove_handle($multiCurl, $chNsfw);
    curl_multi_remove_handle($multiCurl, $chClip);
    curl_multi_close($multiCurl);

    // Decode the JSON responses
    $resultNsfw = json_decode($responseNsfw, true);
    $resultClip = json_decode($responseClip, true);

    $classification = $resultNsfw['label'] ?? 'Unknown';
    $topLabels = $resultClip['labels'] ?? [];

    // If classified as NSFW, do not upload
    if (strtolower($classification) === "nsfw") {
        echo "Image not allowed: NSFW content detected.";
        exit;
    }

    // Proceed with saving the image if it's safe
    $uniqueId = uniqid('img_', true);
    $targetDir = "uploads/Images/";
    $newFileName = $uniqueId . '.' . $fileExt;
    $targetFilePath = $targetDir . $newFileName;

    if (!is_dir($targetDir)) {
        mkdir($targetDir, 0755, true);
    }

    // Move uploaded file to the target directory
    if (move_uploaded_file($filePath, $targetFilePath)) {
        // Save the image details to the database
        try {
            $sql = $pdo->prepare('INSERT INTO images (`user-id`, url, label) VALUES (?, ?, ?)');
            $sql->execute([$data['id'], $targetFilePath, implode(', ', $topLabels)]);
        } catch (Exception $e) {
            echo "Database Error: " . $e->getMessage();
            exit;
        }

        // Output success message with classification results
        echo "File uploaded successfully. Classification: NSFW - " . $classification . ". Top Labels: " . implode(', ', $topLabels);
        header('Location: profile.php');
        exit;
    } else {
        echo "File upload failed.";
    }
}
?>
