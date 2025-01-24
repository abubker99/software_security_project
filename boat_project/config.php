<?php
$host = 'localhost'; // Hostname
$db = 'user_auth';   // Database name
$user = 'root';      // Database username (default for XAMPP/WAMP)
$pass = '';          // Database password (leave empty for default)

// Create connection
$conn = new mysqli($host, $user, $pass, $db);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
