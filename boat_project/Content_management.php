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
    <title>Data Management</title>
    <!-- Link TO CSS -->
    <link rel="stylesheet" href="style.css">
    <!-- Box Icons -->
    <link rel="stylesheet" href="https://unpkg.com/boxicons@latest/css/boxicons.min.css">
    <style>
        /* General Styles */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
            color: #333;
        }

        header {
            background-color: #000;
            color: #fff;
            padding: 10px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo img {
            width: 60px;
        }

        .navbar {
            list-style: none;
            display: flex;
            gap: 15px;
        }

        .navbar li a {
            color: white;
            text-decoration: none;
        }

        .navbar li a:hover {
            text-decoration: underline;
        }

        .header-icon i {
            color: white;
            margin-left: 20px;
            font-size: 24px;
        }

        .header-icon i:hover {
            cursor: pointer;
        }

        .search-box input {
            padding: 5px 10px;
            margin-top: 10px;
            width: 200px;
        }

        /* Data Management Section */
        .data-management {
            padding: 40px;
        }

        .heading {
            text-align: center;
            margin-bottom: 40px;
        }

        .heading h2 {
            font-size: 2rem;
            color: #000;
        }

        .add-product-container,
        .edit-product-container,
        .delete-product-container {
            background-color: white;
            padding: 20px;
            margin: 20px 0;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .add-product-container h3,
        .edit-product-container h3,
        .delete-product-container h3 {
            margin-bottom: 20px;
            font-size: 1.5rem;
        }

        label {
            font-weight: bold;
            display: block;
            margin-bottom: 5px;
        }

        input[type="text"],
        input[type="number"],
        input[type="url"],
        textarea {
            width: 100%;
            padding: 8px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 1rem;
        }

        textarea {
            height: 100px;
        }

        button {
            background-color: #333;
            color: white;
            padding: 10px 20px;
            border: none;
            cursor: pointer;
            border-radius: 4px;
            font-size: 1rem;
        }

        button:hover {
            background-color: #444;
        }

        /* Product List Section */
        .product-list-container {
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .product-list-container table {
            width: 100%;
            border-collapse: collapse;
        }

        .product-list-container table th,
        .product-list-container table td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        .product-list-container table th {
            background-color: #f0f0f0;
        }

        .product-list-container table img {
            width: 50px;
        }

        /* Footer */
        .footer {
            background-color: #333;
            color: white;
            padding: 30px;
            text-align: center;
        }

        .footer .social a {
            margin: 0 10px;
            color: white;
        }

        .footer .social a:hover {
            color: #f4f4f4;
        }

        /* Copyright */
        .copyright {
            background-color: #000;
            color: #fff;
            padding: 10px;
            text-align: center;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <header>
        <a href="#" class="logo">
            <img src="img/logo.png" alt="Boat Logo">
        </a>
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
                <li><a href="login.html">Login/Sign Up</a></li>
            <?php endif; ?>
        </ul>

        <div class="header-icon">
            <i class='bx bx-cart-alt'></i>
            <i class='bx bx-search' id="search-icon"></i>
        </div>
        <div class="search-box">
            <input type="search" placeholder="Search Here...">
        </div>
    </header><br><br><br>

    <!-- Data Management Section -->
    <section class="data-management">
    <div class="heading">
        <h2>Manage Products</h2>
    </div>

    <!-- Add Product Form -->
    <div class="add-product-container">
        <h3>Add New Product</h3>
        <form id="add-product-form" enctype="multipart/form-data">
            <label for="product-name">Product Name:</label>
            <input type="text" id="product-name" name="product-name" required>

            <label for="product-description">Description:</label>
            <textarea id="product-description" name="product-description" required></textarea>

            <label for="product-price">Price:</label>
            <input type="number" id="product-price" name="product-price" required>

           
            <label for="product-image">Product Image:</label>
            <input type="file" id="product-image" name="product-image" accept="image/*" required>
            <button type="submit">Add Product</button>
        </form>
    </div>

    <!-- Edit Product Form -->
    <div class="edit-product-container">
        <h3>Edit Product</h3>
        <form id="edit-product-form">
            <label for="edit-product-id">Product ID:</label>
            <input type="number" id="edit-product-id" name="edit-product-id" required>

            <label for="edit-product-name">New Product Name:</label>
            <input type="text" id="edit-product-name" name="edit-product-name">

            <label for="edit-product-description">New Description:</label>
            <textarea id="edit-product-description" name="edit-product-description"></textarea>

            <label for="edit-product-price">New Price:</label>
            <input type="number" id="edit-product-price" name="edit-product-price">

            
            <label for="product-image">Product Image:</label>
            <input type="file" id="product-image" name="product-image" accept="image/*" required>

            <button type="submit">Update Product</button>
        </form>
    </div>

    <!-- Delete Product Form -->
    <div class="delete-product-container">
        <h3>Delete Product</h3>
        <form id="delete-product-form">
            <label for="delete-product-id">Product ID to Delete:</label>
            <input type="number" id="delete-product-id" name="delete-product-id" required>

            <button type="submit">Delete Product</button>
        </form>
    </div>

    <!-- Product List (View Products) -->
    <div class="product-list-container">
        <h3>Product List</h3>
        <table id="product-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Description</th>
                    <th>Price</th>
                    <th>Image</th>
                </tr>
            </thead>
            <tbody>
                <!-- Products will be listed here dynamically -->
            </tbody>
        </table>
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

    <!-- Link to JS -->
    <script src="main.js"></script>

    <script>
 document.addEventListener("DOMContentLoaded", function () {
    const addForm = document.getElementById("add-product-form");
    const editForm = document.getElementById("edit-product-form");
    const deleteForm = document.getElementById("delete-product-form");
    const productTable = document.getElementById("product-table").querySelector("tbody");

    // Function to fetch and display products
    function fetchProducts() {
        fetch("db_operations.php?action=fetch")
            .then(response => response.json())
            .then(data => {
                productTable.innerHTML = "";
                data.forEach(product => {
                    const row = document.createElement("tr");
                    row.innerHTML = `
                        <td>${product.id}</td>
                        <td>${product.name}</td>
                        <td>${product.description}</td>
                        <td>${product.price}</td>
                        <td><img src="${product.image}" width="50"></td>
                    `;
                    productTable.appendChild(row);
                });
            });
    }

    // Add Product
    addForm.addEventListener("submit", function (e) {
        e.preventDefault();
        const formData = new FormData(addForm);
        formData.append("action", "add");

        fetch("db_operations.php", { method: "POST", body: formData })
            .then(response => response.text())
            .then(() => {
                addForm.reset();
                fetchProducts();
            });
    });

    // Edit Product
    editForm.addEventListener("submit", function (e) {
        e.preventDefault();
        const formData = new FormData(editForm);
        formData.append("action", "edit");

        fetch("db_operations.php", { method: "POST", body: formData })
            .then(response => response.text())
            .then(() => {
                editForm.reset();
                fetchProducts();
            });
    });

    // Delete Product
    deleteForm.addEventListener("submit", function (e) {
        e.preventDefault();
        const formData = new FormData(deleteForm);
        formData.append("action", "delete");

        fetch("db_operations.php", { method: "POST", body: formData })
            .then(response => response.text())
            .then(() => {
                deleteForm.reset();
                fetchProducts();
            });
    });

    fetchProducts();
});

    </script>
</body>
</html>
