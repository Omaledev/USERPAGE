<?php
include("db.config.php");

$product_name = $price = $description = $quantity = "";
$product_nameErr = $priceErr = $quantityErr = "";
$productSuccess = '';

function sanitize_input($input){
    $input = trim($input);
    $input = stripslashes($input);
    $input = htmlspecialchars($input);
    return $input;
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // Validate product name
    if (empty($_POST['product_name'])) {
        $product_nameErr = "Product name is required!";
    } else {
        $product_name = sanitize_input($_POST['product_name']);
    }

    // Validate price
    if (empty($_POST['price'])) {
        $priceErr = "Price of product is required!";
    } else {
        $price = sanitize_input($_POST['price']);
    }

    // Validate quantity
    if (empty($_POST['quantity'])) {
        $quantityErr = "Quantity of product is required!";
    } else {
        $quantity = sanitize_input($_POST['quantity']);
    }

    $description = sanitize_input($_POST['description']);

    // Insert input into the database if no errors
    if (empty($product_nameErr) && empty($priceErr) && empty($quantityErr)) {
        try {
            $stmt = $dbconnection->prepare("INSERT INTO products (product_name, price, description, quantity) 
                VALUES (:product_name, :price, :description, :quantity)");
            $stmt->bindParam(':product_name', $product_name);
            $stmt->bindParam(':price', $price);
            $stmt->bindParam(':description', $description);
            $stmt->bindParam(':quantity', $quantity);

            if ($stmt->execute()) {
                // Store success message in the session
                $_SESSION['productSuccess'] = "New product added successfully";
                
                // Redirect back to the dashboard page
                header("Location: dashboard.php");
                exit();
            } else {
                echo "Error: Could not add the product";
            }
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }

        $dbconnection = null;
        exit();
    }
}
