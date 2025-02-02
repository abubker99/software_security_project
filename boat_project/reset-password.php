<?php
session_start();
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Load PHPMailer
require 'PHPMailer-master/src/Exception.php';
require 'PHPMailer-master/src/PHPMailer.php';
require 'PHPMailer-master/src/SMTP.php';

// Database connection
require 'config.php';

// Step 1: Send OTP if email is provided
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['send_otp'])) {
    $email = $_POST['email'];

    // Check if email exists
    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        echo "No account found with this email.";
        exit();
    }

    // Generate 6-digit OTP
    $otp = rand(100000, 999999);
    $expiry = date('Y-m-d H:i:s', time() + (5 * 60)); // OTP expires in 5 minutes

    // Store OTP in database
    $stmt = $conn->prepare("UPDATE users SET otp_code = ?, otp_expires_at = ? WHERE email = ?");
    $stmt->bind_param("sss", $otp, $expiry, $email); // Use "sss" for string binding
    if (!$stmt->execute()) {
        echo "Error storing OTP: " . $stmt->error;
        exit();
    }

    // Send OTP via email
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'ut711234@gmail.com'; // Replace with your email
        $mail->Password = 'gcad txnk jxzt mqqv';   // Use App Password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        $mail->setFrom('ut711234@gmail.com', 'noreply@Boat.com');
        $mail->addAddress($email);
        $mail->Subject = 'Your Password Reset OTP';
        $mail->Body = "Your OTP for password reset is: $otp\n\nThis OTP is valid for 5 minutes.";

        $mail->send();
        echo "OTP sent to your email.";
        exit();
    } catch (Exception $e) {
        echo "Email sending failed: " . $mail->ErrorInfo;
        exit();
    }
}

// Step 2: Verify OTP & Reset Password
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['verify_otp'])) {
    $email = $_POST['email'];
    $otp = $_POST['otp'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    // Check if passwords match
    if ($new_password !== $confirm_password) {
        echo "Passwords do not match!";
        exit();
    }

    // Validate OTP
    $stmt = $conn->prepare("SELECT otp_code, otp_expires_at FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->bind_result($stored_otp, $otp_expiry);
    $stmt->fetch();
    $stmt->close();

    if (!$stored_otp || $stored_otp != $otp || time() > strtotime($otp_expiry)) {
        echo "Invalid or expired OTP!";
        exit();
    }

    // Hash the new password
    $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

    // Update password and clear OTP
    $stmt = $conn->prepare("UPDATE users SET password = ?, otp_code = NULL, otp_expires_at = NULL, updated_at = CURRENT_TIMESTAMP WHERE email = ?");
    $stmt->bind_param("ss", $hashed_password, $email);
    
    if ($stmt->execute()) {
        echo "Password Reset Successful! You can now log in.";
        exit();
    } else {
        echo "An error occurred. Please try again.";
        exit();
    }
}

$conn->close();
?>