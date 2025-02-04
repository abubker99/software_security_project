<?php
session_start();  // Start the session
include 'config.php';  // Include database connection

// Function to log login attempts
function log_login_attempt($email, $status) {
    $log_file = 'login_logs.txt';  // Log file path
    $timestamp = date('Y-m-d H:i:s');  // Current timestamp
    $log_entry = "[$timestamp] - Email: $email - Status: $status\n";

    // Open the log file in append mode
    file_put_contents($log_file, $log_entry, FILE_APPEND);
}

// Function to check if the user is blocked based on failed attempts
function is_user_blocked($last_failed_attempt, $failed_attempts) {
    $current_time = new DateTime();
    $last_failed_time = new DateTime($last_failed_attempt);

    // Check if the user is blocked for 1 minute or 1 day
    if ($failed_attempts >= 3 && $failed_attempts < 6) {
        // Block for 1 minute
        $block_duration = new DateInterval('PT1M');
        if ($current_time < $last_failed_time->add($block_duration)) {
            return true; // Blocked for 1 minute
        }
    } elseif ($failed_attempts >= 6) {
        // Block for 1 day
        $block_duration = new DateInterval('P1D');
        if ($current_time < $last_failed_time->add($block_duration)) {
            return true; // Blocked for 1 day
        }
    }
    return false; // Not blocked
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    // Secure query using prepared statements to get user details
    $stmt = $conn->prepare("SELECT username, password, failed_attempts, last_failed_attempt FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        // Check if the user is blocked based on failed attempts
        if (is_user_blocked($user['last_failed_attempt'], $user['failed_attempts'])) {
            header("Location: login.php?blocked=true");
            exit();
        }

        // Verify the password
        if (password_verify($password, $user['password'])) {
            // Reset failed attempts on successful login
            $stmt = $conn->prepare("UPDATE users SET failed_attempts = 0, last_failed_attempt = NULL WHERE email = ?");
            $stmt->bind_param("s", $email);
            $stmt->execute();

            session_regenerate_id(true); // Secure session handling
            $_SESSION['username'] = $user['username']; // Store username in session

            // Log successful login attempt
            log_login_attempt($email, 'Success');

            header("Location: index.php"); // Redirect to homepage
            exit();
        } else {
            // Increment failed attempts on failed login
            $new_failed_attempts = $user['failed_attempts'] + 1;
            $stmt = $conn->prepare("UPDATE users SET failed_attempts = ?, last_failed_attempt = NOW() WHERE email = ?");
            $stmt->bind_param("is", $new_failed_attempts, $email);
            $stmt->execute();

            // Log failed login attempt
            log_login_attempt($email, 'Failed: Incorrect password');

            // Calculate remaining attempts
            $remaining_attempts = 3 - $new_failed_attempts;
            if ($new_failed_attempts >= 6) {
                header("Location: login.php?error=blocked_for_1_day&attempts=0");
            } elseif ($new_failed_attempts >= 3) {
                header("Location: login.php?error=blocked_for_1_minute&attempts=$remaining_attempts");
            } else {
                header("Location: login.php?error=invalid_credentials&attempts=$remaining_attempts");
            }
            exit();
        }
    } else {
        // Log failed login attempt (email not found)
        log_login_attempt($email, 'Failed: Email not found');
        header("Location: login.php?error=email_not_found&attempts=3");
        exit();
    }
}

$stmt->close();
$conn->close();
?>
