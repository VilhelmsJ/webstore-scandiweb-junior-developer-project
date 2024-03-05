<?php
require_once 'classes/ProductManager.php';

// Instantiate the ProductManager class
$productManager = new ProductManager();

// // If a form is submitted to add a product
// if ($_SERVER["REQUEST_METHOD"] == "POST") {
//     // Validate and sanitize input (you should implement validation)
//     $sku = $_POST["sku"];
//     $name = $_POST["name"];
//     $price = $_POST["price"];
//     $attribute_name = $_POST["attribute_name"];
//     $attribute_value = $_POST["attribute_value"];

//     // Add the product
//     $result = $productManager->addProduct($sku, $name, $price, $product_type, $attribute_value);
//     if ($result) {
//         echo "Product added successfully.";
//     } else {
//         echo "Failed to add product.";
//     }
// }

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
    <title>Product List</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <div class="container">
        <h1>Product List</h1>
        <!-- "ADD" button to navigate to the Product Add page -->
        <a href="product_add.php" class="add-button">ADD PRODUCT</a>
        <form action="delete.php" method="POST">
            <div class="product-grid">
                <?php foreach ($products as $product): ?>
                    <div class="product-item">
                        <div class="product-sku">
                            <strong><?php echo htmlspecialchars($product['sku']); ?></strong>
                        </div>
                        <div class="product-name">
                            <?php echo htmlspecialchars($product['name']); ?>
                        </div>
                        <div class="product-price">
                            Price: $<?php echo htmlspecialchars($product['price']); ?>
                        </div>
                        <div class="product-attribute_name">
                            <?php echo htmlspecialchars($product['product_type']); ?>
                        </div>
                        <div class="product-attribute_value">
                            <?php 
                                // Fetch attribute name and value
                                $attribute_name = $product['product_type'];
                                $attribute_value = $product['attribute_value'];

                                // Define $unit even if not found in $units
                                $unit = isset($units[strtolower($attribute_name)]) ? $units[strtolower($attribute_name)] : "";
                                
                                // Display attribute value with unit
                                echo htmlspecialchars($attribute_value) . " " . $unit;
                            ?>
                        </div>
                        <div class="delete-checkbox">
                            <input type="checkbox" name="selected_products[]" value="<?php echo htmlspecialchars($product['sku']); ?>" class="delete-checkbox">
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            <button type="submit" name="mass_delete" class="mass-delete-button">MASS DELETE</button>
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
