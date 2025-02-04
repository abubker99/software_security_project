<?php
session_start();  // Start the session
include 'config.php';  // Include database connection

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    // Secure query using prepared statements
    $stmt = $conn->prepare("SELECT username, password FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        if (password_verify($password, $user['password'])) {
            session_regenerate_id(true); // Secure session handling
            $_SESSION['username'] = $user['username']; // Store username in session

            header("Location: index.php"); // Redirect to homepage
            exit();
        } else {
            echo "Invalid credentials!";
        }
    }
}

$stmt->close();
$conn->close();
?>
