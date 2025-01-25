<?php
// reset-password.php

// Start a session to handle flash messages (if needed)
session_start();

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "boat_shop"; // Replace with your database name

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    // Validate that passwords match
    if ($new_password !== $confirm_password) {
        $_SESSION['error'] = "Passwords do not match!";
        header("Location: Password_recovery.html");
        exit();
    }

    // Validate email existence in the database
    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        $_SESSION['error'] = "No account found with this email address.";
        header("Location: Password_recovery.html");
        exit();
    }

    // Hash the new password for security
    $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

    // Update the user's password in the database
    $stmt = $conn->prepare("UPDATE users SET password = ? WHERE email = ?");
    $stmt->bind_param("ss", $hashed_password, $email);
    if ($stmt->execute()) {
        $_SESSION['success'] = "Password has been reset successfully.";
        header("Location: login.html");
    } else {
        $_SESSION['error'] = "An error occurred. Please try again.";
        header("Location: Password_recovery.html");
    }

    $stmt->close();
}

$conn->close();
?>
