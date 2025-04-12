<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require 'pdo.php';

$value = $_GET['username'];
$sql = $pdo->prepare('select username,business_name,rate,social_links,contact_number, from accounts,business_profiles');
?>