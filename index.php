<?php
require_once 'classes/ProductManager.php';

// Instantiate the ProductManager class
$productManager = new ProductManager();

// Fetch product list
$products = $productManager->getProductList();

$units = [
    "Size" => "MB",
    "Weight" => "KG",
    "Dimensions" => "",
];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product List Page</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <div class="container">
        <h1>Product List Page</h1>
        <!-- "ADD" button to navigate to the Product Add page -->
        <a href="product_add.php" class="add-button">ADD</a>
        <form action="delete.php" method="POST">
            <div class="product-grid">
                <?php foreach ($products as $product): ?>
                    <div class="product-item">
                    <?php
                    // Display product SKU
                    echo "<p>$product[sku]</p>";
                    // Display product type
                    echo "<p>$product[product_type]</p>";
                    // Display product attribute name and value
                    echo "<p><strong>$product[name]</strong></p>";
                    // Display product price
                    echo "<p>Price: $$product[price]</p>";

                    // Check the product type and display the appropriate attribute
                    if ($product['product_type'] === "Book") {
                        echo "<p>Weight : $product[attribute_value]</p>";
                    } else if($product['product_type'] === "Furniture") {
                        echo "<p>Dimensions : $product[attribute_value]</p>";
                    } else {
                        echo "<p>Size : $product[attribute_value]</p>";
                    }
                    ?>
                        <div class="delete-checkbox">
                            <input type="checkbox" name="selected_products[]" value="<?php echo htmlspecialchars($product['sku']); ?>" class="delete-checkbox">
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            <button type="submit" id="delete-product-btn" name="mass_delete" class="mass-delete-button">MASS DELETE</button>
        </form>
    </div>

    <script>
        function massDelete() {
            // Get all checkboxes with class .delete-checkbox
            var checkboxes = document.querySelectorAll('.delete-checkbox input[type="checkbox"]:checked');
            var skus = [];
            // Get the value (SKU) of each checked checkbox
            checkboxes.forEach(function(checkbox) {
                skus.push(checkbox.value);
            });
            // Redirect to the delete endpoint with selected SKUs
            if (skus.length > 0) {
                window.location.href = 'delete.php?skus=' + skus.join(',');
            } else {
                alert('No products selected for deletion.');
            }
        }
    </script>
</body>
</html>
