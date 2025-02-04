<?php
// Database connection
require 'config.php';

// Function to check password strength
function is_valid_password($password) {
    // Check if the password contains at least 8 characters, including at least one number, one uppercase letter, and one symbol
    return preg_match('/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[!@#$%^&*])[A-Za-z\d!@#$%^&*]{8,}$/', $password);
}

// Check if data is submitted via the form
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize and validate inputs
    $username = htmlspecialchars(trim($_POST["username"]));  // Prevent XSS attacks
    $email = filter_var(trim($_POST["email"]), FILTER_SANITIZE_EMAIL);  // Sanitize email
    $password = trim($_POST["password"]);
    $confirm_password = trim($_POST["confirm-password"]);

    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die("Invalid email format.");
    }

    // Check if passwords match
    if ($password !== $confirm_password) {
        die("Error: Passwords do not match.");
    }

    // Check password strength
    if (!is_valid_password($password)) {
        die("Password must be at least 8 characters long and include at least one uppercase letter, one number, and one special character.");
    }

    // Encrypt the password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Check if the email already exists in the `users` table
    $check_email = "SELECT id FROM users WHERE email = ?";
    $stmt = $conn->prepare($check_email);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();
    
    if ($stmt->num_rows > 0) {
        die("This email is already registered.");
    }
    $stmt->close();

    // Insert data into the `users` table
    $sql = "INSERT INTO users (username, email, password, created_at) VALUES (?, ?, ?, CURRENT_TIMESTAMP)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $username, $email, $hashed_password);

    if ($stmt->execute()) {
        // Redirect to the login page after successful registration
        header("Location: login.php");  // Adjust the URL if your login page is different
        exit();
    } else {
        echo "An error occurred during registration: " . $conn->error;
    }

    $stmt->close();
}

// Close the database connection
$conn->close();
?>
