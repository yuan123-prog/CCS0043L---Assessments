<?php
// db.php - Database connection for all modules
$host = 'localhost';
$db = 'registration_db';
$user = 'root';
$pass = '';

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die('Database connection failed: ' . $conn->connect_error);
}
?>