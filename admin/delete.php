<?php
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
