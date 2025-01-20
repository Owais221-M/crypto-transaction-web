<?php
$host = 'localhost'; // or your server IP
$user = 'root'; // your MySQL username
$password = 'Ansari_221'; // your MySQL password
$dbname = 'crypto_transaction'; // your database name

// Create connection
$conn = new mysqli($host, $user, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
