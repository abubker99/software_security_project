<?php
include 'config.php';  // Include database connection

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // 🚨 Vulnerable: SQL Injection (Direct user input in SQL query)
    $sql = "SELECT * FROM users WHERE email = '$email'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        // Debugging: Display user details (Leaking hashed password)
        echo "<strong>DEBUG:</strong><br>";
        echo "Stored Hashed Password: " . $user['password'] . "<br>";
        echo "Entered Password: " . $password . "<br>";

        // 🚨 Uses password_verify() but still allows SQL injection
        if (password_verify($password, $user['password'])) {
            echo "<br><strong>Login successful!</strong>";
        } else {
            echo "<br><strong>Invalid password.</strong>";
        }
    } else {
        echo "<strong>No user found with this email.</strong>";
    }
}

// Close connection
$conn->close();
?>
