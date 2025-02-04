<?php
session_start(); // Start the session

// Check if the user is logged in
$isLoggedIn = isset($_SESSION['username']);
$username = $isLoggedIn ? $_SESSION['username'] : "";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="img/Boat.ico" type="image/x-icon">
    <title>Boat</title>
    <!-- Link TO CSS -->
    <link rel="stylesheet" href="style.css">
    <!-- Box Icons -->
    <link rel="stylesheet"
  href="https://unpkg.com/boxicons@latest/css/boxicons.min.css">
</head>
<style> 
    .form-group {
            margin-bottom: 15px;
        }
        /* Ensure full-page layout */
* {
    box-sizing: border-box;
    margin: 0;
    padding: 0;
}

body, html {
    height: 100%;
    display: flex;
    flex-direction: column;
    font-family: Arial, sans-serif;
}

/* Center main content */
.main-content {
    flex: 1;
    display: flex;
    justify-content: center;
    align-items: center;
    text-align: center;
    padding: 20px;
}

/* General container styling for login/signup */
.container {
    background: white;
    padding: 25px;
    border-radius: 8px;
    box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
    width: 350px;
}

.container h1 {
    margin-bottom: 15px;
}

/* Input fields */
.container input {
    width: 100%;
    padding: 10px;
    margin-top: 10px;
    border: 1px solid #ccc;
    border-radius: 5px;
}

/* Button styling */
.container button {
    width: 100%;
    padding: 10px;
    background-color: #007BFF;
    color: white;
    border: none;
    border-radius: 5px;
    margin-top: 15px;
    cursor: pointer;
}

.container button:hover {
    background-color: #0056b3;
}

/* Center additional links */
.additional-links {
    margin-top: 15px;
}

/* Stick footer to the bottom */
.footer {
    background: #333;
    color: white;
    text-align: center;
    padding: 10px;
    width: 100%;
    margin-top: auto;
}
</style>
<body>
    <!-- Navbar -->
    <header>
        <a href="#" class="logo">
            <img src="img/logo.png" alt="">
        </a>
        <!-- Menu-Icon -->
        <i class='bx bx-menu' id="menu-icon"></i>
        <!-- Links -->
        <ul class="navbar">
            <li><a href="index.php">Home</a></li>
            <li><a href="Aboutus.php">About Us</a></li>
            <li><a href="products.php">Products</a></li>

            <!-- Show "Manage Products" only if the user is "admin" -->
            <?php if ($isLoggedIn && $username === "admin"): ?>
                <li><a href="Content_management.php">Manage Products</a></li>
            <?php endif; ?>

            <!-- If user is logged in, show username and Logout -->
            <?php if ($isLoggedIn): ?>
                <li><a href="User_profile.php">My Profile</a></li>
                <li><a href="logout.php">Logout</a></li>
                <li><span>Welcome, <?php echo htmlspecialchars($username); ?>!</span></li>
            <?php else: ?>
                <!-- Show Login/Sign up if not logged in -->
                <li><a href="login.php">Login/Sign Up</a></li>
            <?php endif; ?>
        </ul>

			

        </ul>
        <!-- Icon -->
        <div class="header-icon">
            <i class='bx bx-cart-alt' ></i>
            <i class='bx bx-search' id="search-icon"></i>
        </div>
        <!-- Search Box -->
        <div class="search-box">
          <input type="search" name="" id="" placeholder="Search Here...">
        </div>
    </header><br><br>

    <!-- About -->
    <section class="about" id="about">
        <div class="about-img">
            <img src="img/about.jpg" alt="pc img">
        </div>
        <div class="about-text">
            <h2>Our history</h2>
            <p>Boat is an online store about computers and everything related to computers.</p>
            <p>We have started our store in 2024.</p>
            <p>We are a group of five people Abdulrahman, Hassan, Abu Bakr, Faisal, and Nawaf.</p>
            <a href="index.php" class="btn">Learn More</a>
        </div>
    </section>

    <!-- Footer -->
    <section class="footer">
        <div class="footer-box">
            <h2>The Boat Shop</h2>
            <p>The company and its members believe that cooperation is the path to success.</p>
            <div class="social">
                <a href="#"><i class='bx bxl-facebook'></i></a>
                <a href="#"><i class='bx bxl-twitter'></i></a>
                <a href="#"><i class='bx bxl-instagram'></i></a>
                <a href="#"><i class='bx bxl-tiktok'></i></a>
            </div>
        </div>
    </section>

    <!-- Copyright -->
    <div class="copyright">
        <p>&#169; BoatTeam All Rights Reserved</p>
    </div>
</body>
</html>