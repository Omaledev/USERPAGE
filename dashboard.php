<?php
session_start();
include 'db.config.php';
include 'add_product.php';

// Check if the 'id' session variable is set
if (isset($_SESSION['id'])) {
    $id = $_SESSION['id'];
} else {
    // Redirect to login page or show an error message
    header("Location: login.php"); // Adjust the path to your login page if necessary
    exit();
}

$name = '';

try {
    $stmt = $dbconnection->prepare("SELECT name FROM users WHERE Id=:id");
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    $name = $stmt->fetchColumn();
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}

// Check if a success message exists in the session
$productSuccess = isset($_SESSION['productSuccess']) ? $_SESSION['productSuccess'] : '';

// Clear the success message from the session after displaying it
unset($_SESSION['productSuccess']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="dashboard_products">
    <div class="dashboard_container">
        <h2>Welcome <?php echo htmlspecialchars($name); ?></h2>

        <!-- Display the success message if it exists -->
        <?php if ($productSuccess): ?>
            <p style="color: green;"><?php echo htmlspecialchars($productSuccess); ?></p>
        <?php endif; ?>

        <h2>Add New Product</h2>
        <form action="<?php echo htmlspecialchars(trim($_SERVER["PHP_SELF"])); ?>" method="POST">
          <label for="product_name">Product Name:</label><br>
          <input type="text" id="product_name" name="product_name" value="<?php echo htmlspecialchars($product_name); ?>" required><br>
          <span class="error" style="color:red;"><?php echo htmlspecialchars($product_nameErr); ?></span>

          <label for="price">Price:</label><br>
          <input type="text" step="0.01" id="price" name="price" value="<?php echo htmlspecialchars($price); ?>" placeholder="&#8358" required><br>
          <span class="error" style="color:red;"><?php echo htmlspecialchars($priceErr); ?></span>

          <label for="description">Description:</label><br>
          <textarea id="description" name="description"><?php echo htmlspecialchars($description); ?></textarea><br>

          <label for="quantity">Quantity:</label><br>
          <input type="text" id="quantity" name="quantity" value="<?php echo htmlspecialchars($quantity); ?>"><br>
          <span class="error" style="color:red;"><?php echo htmlspecialchars($quantityErr); ?></span>

          <input class="submit" type="submit" value="Add Product">
          <p><a href="logout.php">logout</a></p>
        </form>

        <h2>Product List</h2>
        <table border="1" cellpadding="10">
            <tr>
               <th>ID</th>
               <th>Product Name</th>
               <th>Description</th>
               <th>Price</th>
               <th>Quantity</th>
               <th>Actions</th>
            </tr>
            <?php
       
       try {
    // Query to select data from the products table
          $stmt = $dbconnection->query("SELECT id, product_name, description, price, quantity FROM products");
          $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
         } catch (PDOException $e) {
         echo "Error: " . $e->getMessage();
        }

    // Check if there are products to display
            if ($products) {
            foreach ($products as $product) {
            echo "<tr>";
            echo "<td>" . ($product['id']) . "</td>";
            echo "<td>" . ($product['product_name']) . "</td>";
            echo "<td>" . ($product['description']) . "</td>";
            echo "<td>₦" . ($product['price']) . "</td>";
            echo "<td>" . ($product['quantity']) . "</td>";
            echo "<td>
                <a href='edit_product.php?id=" . htmlspecialchars($product['id']) . "'>Edit</a> |
                <a href='delete_product.php?id=" . htmlspecialchars($product['id']) . "' onclick='return confirm(\"Are you sure you want to delete this product?\");'>Delete</a>
            </td>";
            echo "</tr>";
               }
           } else {
           echo "<tr><td colspan='4'>No products found.</td></tr>";
           }
           ?>
        </table>

    </div>
</div>
</body>
</html>

