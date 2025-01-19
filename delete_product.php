<?php
include("db.config.php");

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    try {
        $stmt = $dbconnection->prepare("DELETE FROM products WHERE id = :id");
        $stmt->bindParam(':id', $id);
        if ($stmt->execute()) {
            $_SESSION['productSuccess'] = "Product deleted successfully";
        } else {
            $_SESSION['productSuccess'] = "Error: Could not delete product";
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}

header("Location: dashboard.php");
exit();
?>
