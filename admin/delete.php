<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
	if(!isset($_SESSION['data']['admin']) && $_SESSION['data']['admin']==True){
		header("location: ../index.php");
	}
include 'db_connect.php';

$table = $_GET['table'] ?? '';
$id = $_GET['id'] ?? '';

if ($table && $id) {
    $colRes = $conn->query("SHOW COLUMNS FROM `$table`");
    $primaryKey = $colRes->fetch_assoc()['Field'];
    $conn->query("DELETE FROM `$table` WHERE `$primaryKey` = '$id'");
}

header("Location: manage.php?table=$table");
exit;
?>
