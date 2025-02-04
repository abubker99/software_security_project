<?php
session_start();
include 'config.php'; // Database connection

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.php"); // Redirect if not logged in
    exit();
}

$username = $_SESSION['username'];

// Fetch user details from the database
$stmt = $conn->prepare("SELECT username, email, profile_image, phone, address, bio FROM users WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    $user = $result->fetch_assoc();
} else {
    echo "<script>alert('User not found!'); window.location.href='index.php';</script>";
    exit();
}

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="img/Boat.ico" type="image/x-icon">
    <title>User Profile - Boat</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://unpkg.com/boxicons@latest/css/boxicons.min.css">
</head>
<body>
    <!-- Navbar -->
    <header>
        <a href="#" class="logo">
            <img src="img/logo.png" alt="Boat Logo">
        </a>
        <!-- Menu-Icon -->
        <i class='bx bx-menu' id="menu-icon"></i>
        <!-- Links -->
        <ul class="navbar">
            <li><a href="index.php">Home</a></li>
            <li><a href="Aboutus.php">About Us</a></li>
            <li><a href="products.php">Products</a></li>
            <?php if ($username === "admin"): ?>
                <li><a href="Content_management.php">Manage Products</a></li>
            <?php endif; ?>
            <li><a href="logout.php">Logout</a></li>
            <li><span>Welcome, <?php echo htmlspecialchars($username); ?>!</span></li>
        </ul>
        <!-- Header-Icon (Cart + Search) -->
        <div class="header-icon">
            <i class='bx bx-cart-alt'></i>
            <i class='bx bx-search' id="search-icon"></i>
        </div>
        <!-- Search Box -->
        <div class="search-box">
            <input type="search" name="" id="" placeholder="Search Here...">
        </div>
    </header><br><br><br>

    
    <!-- User Profile Section -->
    <main class="Main">
        <div class="container">
            <div class="apply_box">
                <h1>User Profile <span class="title_small">(Boat)</span></h1>
                <form action="update_profile.php" method="POST" enctype="multipart/form-data">
                    <div class="profile_image">
                        <img src="<?php echo htmlspecialchars($user['profile_image']); ?>" alt="Profile Picture" id="profilePicture">
                        <input type="file" id="uploadProfilePicture" name="uploadProfilePicture">
                    </div>
                    <div class="form_container">
                        <div class="form_control">
                            <label for="username">Username</label>
                            <input id="username" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" readonly>
                        </div>
                        <div class="form_control">
                            <label for="email">Email</label>
                            <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" readonly>
                        </div>
                        <div class="form_control">
                            <label for="phone">Phone Number</label>
                            <input type="tel" id="phone" name="phone" value="<?php echo htmlspecialchars($user['phone']); ?>" placeholder="Enter Phone Number...">
                        </div>
                        <div class="form_control">
                            <label for="address">Address</label>
                            <input id="address" name="address" value="<?php echo htmlspecialchars($user['address']); ?>" placeholder="Enter Address...">
                        </div>
                        <div class="form_control">
                            <label for="bio">Bio</label>
                            <textarea id="bio" name="bio" rows="4" placeholder="Write a short bio..."><?php echo htmlspecialchars($user['bio']); ?></textarea>
                        </div>
                    </div>
                    <div class="button_container">
                        <button type="submit">Update Profile</button>
                    </div>
                </form>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <section class="footer">
        <div class="footer-box">
            <h2>The Boat Shop</h2>
            <p>The company and its members believe that cooperation is the path to success.</p>
        </div>
        <div class="footer-box">
            <h3>Contact</h3>
            <span><i class='bx bxs-map'></i> KSA</span>
            <span><i class='bx bxs-phone'></i> +966 111 1111</span>
            <span><i class='bx bxs-envelope'></i> Boat@shop.com</span>
        </div>
    </section>

    <div class="copyright">
        <p>&#169; BoatTeam All Rights Reserved</p>
    </div>

    <script src="main.js"></script>
</body>
</html>
