<?php
// db.php
$host = 'localhost';
$user = 'root';
$pass = '';
$dbname = 'projectdb';

try {
    // Create PDO connection
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    
    // For backward compatibility, create a mysqli connection as well
    $conn = new mysqli($host, $user, $pass, $dbname);
    
    if ($conn->connect_error) {
        die('Database connection failed: ' . $conn->connect_error);
    }
    
} catch (PDOException $e) {
    die('Database connection failed: ' . $e->getMessage());
}
?>