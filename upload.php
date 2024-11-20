<!DOCTYPE html>
<html>
<head>
    <title>Upload File</title>
</head>
<body>
    <form method="post" enctype="multipart/form-data">
        <label for="file">Choose a file:</label>
        <input type="file" name="file" id="file">
        <button type="submit" name="upload">Upload</button>
    </form>
</body>
<?php
session_start();
$data = $_SESSION['data'];
require('pdo.php');
if (isset($_POST['upload'])) {
    // Define the target directory
    $targetDir = "uploads/";

    // Get file information
    $fileName = basename($_FILES['file']['name']);
    $targetFilePath = $targetDir . $fileName;

    // Check if directory exists; if not, create it
    if (!is_dir($targetDir)) {
        mkdir($targetDir, 0755, true);
    }

    // Check if file is uploaded and move it
    if (move_uploaded_file($_FILES['file']['tmp_name'], $targetFilePath)) {
        echo "File uploaded successfully: " . $fileName;
    } else {
        echo "File upload failed.";
    }
	$sql = $pdo->prepare('insert into images(`user-id`,url) values(?,?)');
	$sql->execute(array($data['id'],$targetFilePath));
	header('location:profile.php');
}
?>
