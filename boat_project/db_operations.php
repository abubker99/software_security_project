<?php
$host = "localhost";
$user = "root";
$password = "";
$database = "boat_shop";

$conn = new mysqli($host, $user, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle requests
$action = $_POST['action'] ?? $_GET['action'] ?? '';

if ($action === "add") {
    $name = $_POST['product-name'];
    $description = $_POST['product-description'];
    $price = $_POST['product-price'];
    
    // Handle file upload
    $imagePath = "img/" . basename($_FILES["product-image"]["name"]);
    move_uploaded_file($_FILES["product-image"]["tmp_name"], $imagePath);

    $stmt = $conn->prepare("INSERT INTO products (name, description, price, image) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssds", $name, $description, $price, $imagePath);
    $stmt->execute();
    echo "Product added!";
}

// Fetch products
elseif ($action === "fetch") {
    $result = $conn->query("SELECT * FROM products");
    $products = [];
    while ($row = $result->fetch_assoc()) {
        $products[] = $row;
    }
    echo json_encode($products);
}

// Edit product
elseif ($action === "edit") {
    $id = $_POST['edit-product-id'];
    $name = $_POST['edit-product-name'];
    $description = $_POST['edit-product-description'];
    $price = $_POST['edit-product-price'];
    
    if (!empty($_FILES["product-image"]["name"])) {
        $imagePath = "img/" . basename($_FILES["product-image"]["name"]);
        move_uploaded_file($_FILES["product-image"]["tmp_name"], $imagePath);
        $stmt = $conn->prepare("UPDATE products SET name=?, description=?, price=?, image=? WHERE id=?");
        $stmt->bind_param("ssdsi", $name, $description, $price, $imagePath, $id);
    } else {
        $stmt = $conn->prepare("UPDATE products SET name=?, description=?, price=? WHERE id=?");
        $stmt->bind_param("ssdi", $name, $description, $price, $id);
    }

    $stmt->execute();
    echo "Product updated!";
}

// Delete product
elseif ($action === "delete") {
    $id = $_POST['delete-product-id'];
    $stmt = $conn->prepare("DELETE FROM products WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    echo "Product deleted!";
}

$conn->close();
?>
