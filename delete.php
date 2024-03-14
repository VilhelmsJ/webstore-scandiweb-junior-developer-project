<?php
// Include your database connection file
require_once 'classes/Database.php';

// Check if products are selected for deletion
if (isset($_POST['selected_products']) && is_array($_POST['selected_products']) && count($_POST['selected_products']) > 0) {
    $selectedProducts = $_POST['selected_products'];

    // Connect to the database
    $db = new Database();
    $conn = $db->getConnection();

    // Prepare and execute SQL query to delete selected products
    $sql = "DELETE FROM products WHERE sku IN (" . implode(',', array_fill(0, count($selectedProducts), '?')) . ")";
    $stmt = $conn->prepare($sql);
    $stmt->execute($selectedProducts);

    // Redirect back to the product list page after deletion
    header("Location: index.php");
    exit();
} else {
    // If no products are selected, redirect back to the product list page
    header("Location: index.php");
    exit();
}
