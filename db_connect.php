<?php
$host = 'localhost';
$db   = 'coffeebliss';
$user = 'root';
$pass = '';          // change if you have password

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>