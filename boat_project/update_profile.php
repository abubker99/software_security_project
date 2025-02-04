<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database configuration
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "boat_shop";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Database Connection Failed: " . $conn->connect_error);
}

// Process form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = isset($_POST["username"]) ? trim($_POST["username"]) : null;
    $email = isset($_POST["email"]) ? trim($_POST["email"]) : null;
    $phone = isset($_POST["phone"]) ? trim($_POST["phone"]) : null;
    $address = isset($_POST["address"]) ? trim($_POST["address"]) : null;
    $bio = isset($_POST["bio"]) ? trim($_POST["bio"]) : null;

    // Handle Profile Picture Upload
    $profile_image = "img/default-profile.png"; // Default image
    if (!empty($_FILES["uploadProfilePicture"]["name"])) {
        $target_dir = "uploads/";
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);
        }
        $target_file = $target_dir . basename($_FILES["uploadProfilePicture"]["name"]);
        if (move_uploaded_file($_FILES["uploadProfilePicture"]["tmp_name"], $target_file)) {
            $profile_image = $target_file;
        }
    }

    // Check if user already exists (by email)
    $check_stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $check_stmt->bind_param("s", $email);
    $check_stmt->execute();
    $check_stmt->store_result();

    if ($check_stmt->num_rows > 0) {
        // User exists, update the data
        $update_stmt = $conn->prepare("
            UPDATE users 
            SET profile_image = ?, username = ?, phone = ?, address = ?, bio = ?, updated_at = CURRENT_TIMESTAMP
            WHERE email = ?
        ");
        $update_stmt->bind_param("ssssss", $profile_image, $username, $phone, $address, $bio, $email);

        if ($update_stmt->execute()) {
            header("Location: User_Profile.php");
            exit(); // Ensure the script stops execution after redirect
        } else {
            die("Error updating record: " . $update_stmt->error);
        }
        

        $update_stmt->close();
    } else {
        // User does not exist, insert new record
        $insert_stmt = $conn->prepare("
            INSERT INTO users (profile_image, username, email, phone, address, bio) 
            VALUES (?, ?, ?, ?, ?, ?)
        ");
        $insert_stmt->bind_param("ssssss", $profile_image, $username, $email, $phone, $address, $bio);

        if ($insert_stmt->execute()) {
            echo "<script>alert('Profile created successfully!');</script>";
        } else {
            die("Error inserting record: " . $insert_stmt->error);
        }

        $insert_stmt->close();
    }

    $check_stmt->close();
}

$conn->close();
?>