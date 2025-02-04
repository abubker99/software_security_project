<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet"
    href="https://unpkg.com/boxicons@latest/css/boxicons.min.css">
</head>
<body>
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
            <li><a href="products.php">products</a></li>
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
    </header>

    <div class="login-container">
        <h1>Login</h1>
        <?php 
            if (isset($_GET['error'])) {
                echo "<p class='error-message'>";
                if ($_GET['error'] == 'invalid_credentials') {
                    echo "Invalid credentials! You have " . $_GET['attempts'] . " attempts remaining.";
                } elseif ($_GET['error'] == 'email_not_found') {
                    echo "Email not found. You have 3 attempts remaining.";
                } elseif ($_GET['error'] == 'blocked_for_1_minute') {
                    echo "You have been blocked for 1 minute due to multiple failed attempts.";
                } elseif ($_GET['error'] == 'blocked_for_1_day') {
                    echo "You have been blocked for 1 day due to multiple failed attempts.";
                }
                echo "</p>";
            }
        ?>
        <form action="login_back.php" method="POST">
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Login</button>
        </form>
        <div class="additional-links">
            <p><a href="Password_recovery.html">Forgot Password?</a></p>
            <p>Don't have an account? <a href="signup.html">Sign Up</a></p>
        </div>
    </div>

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

    <script src="main.js"></script>

    <style>
        .error-message {
            color: red;
            margin-bottom: 10px;
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
</body>
</html>

