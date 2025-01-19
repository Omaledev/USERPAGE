<?php
include 'db.config.php';
session_start();

// Ensure we have a product ID to edit
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if (!$id) {
    header("Location: dashboard.php");
    exit();
}

// Fetch product details
try {
    $stmt = $dbconnection->prepare("SELECT * FROM products WHERE id = :id");
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    $product = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$product) {
        echo "Product not found!";
        exit();
    }

    $product_name = $product['product_name'];
    $price = $product['price'];
    $description = $product['description'];
    $quantity = $product['quantity'];
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Validation and updating logic
    $product_name = !empty($_POST['product_name']) ? htmlspecialchars($_POST['product_name']) : '';
    $price = !empty($_POST['price']) ? htmlspecialchars($_POST['price']) : '';
    $description = htmlspecialchars($_POST['description']);
    $quantity = !empty($_POST['quantity']) ? htmlspecialchars($_POST['quantity']) : '';

    if ($product_name && $price && $quantity) {
        try {
            $stmt = $dbconnection->prepare("UPDATE products SET product_name = :product_name, price = :price, description = :description, quantity = :quantity WHERE id = :id");
            $stmt->bindParam(':product_name', $product_name);
            $stmt->bindParam(':price', $price);
            $stmt->bindParam(':description', $description);
            $stmt->bindParam(':quantity', $quantity);
            $stmt->bindParam(':id', $id);

            if ($stmt->execute()) {
                $_SESSION['productSuccess'] = "Product updated successfully";
                header("Location: dashboard.php");
                exit();
            } else {
                echo "Error: Could not update product";
            }
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="style.css">
    <title>Edit Product</title>
</head>
<body>
    <div class="edit_container">
        <h2>Edit Product</h2>
        <form method="POST">
           <label for="product_name">Product Name:</label>
           <input type="text" id="product_name" name="product_name" value="<?php echo ($product_name); ?>" required>
           <label for="price">Price:</label>
           <input type="text" id="price" name="price" value="<?php echo ($price); ?>" required>
           <label for="description">Description:</label>
           <textarea id="description" name="description"><?php echo ($description); ?></textarea>
           <label for="quantity">Quantity:</label>
           <input type="text" id="quantity" name="quantity" value="<?php echo ($quantity); ?>" required>
          <button type="submit">Update Product</button>
        </form>
        <p class="back_dashboard"><a href="dashboard.php">Back to Dashboard</a></p>
    </div>
</body>
</html>
