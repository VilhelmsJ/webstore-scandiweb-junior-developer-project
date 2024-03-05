<?php
require_once 'classes/ProductManager.php';

// Instantiate the ProductManager class
$productManager = new ProductManager();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Fetch product data from $_POST
    $product = [
        'name' => $_POST['name'],
        'price' => $_POST['price'],
        'product_type' => $_POST['product_type'],
        'attribute_value' => $_POST['attribute_value']
    ];

    // Validate required fields
    $required_keys = ['name', 'price', 'product_type', 'attribute_value'];
    $missing_keys = array_diff($required_keys, array_keys($product));

    if (!empty($missing_keys)) {
        echo "Not all required fields are present.";
    } else {
        // Add the product
        $result = $productManager->addProduct(
            $product['name'],
            $product['price'],
            $product['product_type'],
            $product['attribute_value']
        );
        
        
        if ($result) {
            echo "Product added successfully.";
        } else {
            echo "Failed to add product.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Product</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
<div class="container">
        <h1>Add Product</h1>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST" class="product-add-form">
            <div class="form-group">
                <label for="name">Product Name:</label>
                <input type="text" id="name" name="name" required>
            </div>
            <div class="form-group">
                <label for="price">Price:</label>
                <input type="number" id="price" name="price" required>
            </div>
            <div class="form-group">
                <label for="product_type">Product Type:</label>
                <select id="product_type" name="product_type" required>
                    <option value="">Select Product Type</option>
                    <option value="Size">Size</option>
                    <option value="Weight">Weight</option>
                    <option value="Dimensions">Dimensions</option>
                </select>
            </div>
            <div class="form-group" id="attribute_value_fields">
                <!-- Attribute value fields will be added dynamically here based on product type -->
                <!-- Include all necessary fields here initially -->
                <div class="form-group">
                    <label for="size">Size (MB):</label>
                    <input type="text" id="size" name="attribute_value">
                </div>
                <div class="form-group">
                    <label for="weight">Weight (Kg):</label>
                    <input type="text" id="weight" name="attribute_value">
                </div>
                <div class="form-group">
                    <label for="height">Height (CM):</label>
                    <input type="text" id="height" name="height">
                </div>
                <div class="form-group">
                    <label for="width">Width (CM):</label>
                    <input type="text" id="width" name="width">
                </div>
                <div class="form-group">
                    <label for="length">Length (CM):</label>
                    <input type="text" id="length" name="length">
                </div>
            </div>
            <button type="submit">Save</button>
        </form>
        <a href="index.php">Back to Product List</a>
    </div>

    <!-- JavaScript code for adding attribute value fields dynamically based on product type -->
    <script>
        const attributeValueFieldsContainer = document.getElementById('attribute_value_fields');
        const fieldFunctions = {
            Size: () => {
                // Hide unnecessary fields
                document.getElementById('weight').style.display = 'none';
                document.getElementById('height').style.display = 'none';
                document.getElementById('width').style.display = 'none';
                document.getElementById('length').style.display = 'none';

                // Show necessary field
                document.getElementById('size').style.display = 'block';
            },
            Weight: () => {
                // Hide unnecessary fields
                document.getElementById('size').style.display = 'none';
                document.getElementById('height').style.display = 'none';
                document.getElementById('width').style.display = 'none';
                document.getElementById('length').style.display = 'none';

                // Show necessary field
                document.getElementById('weight').style.display = 'block';
            },
            Dimensions: () => {
                // Hide unnecessary fields
                document.getElementById('size').style.display = 'none';
                document.getElementById('weight').style.display = 'none';

                // Show necessary fields
                document.getElementById('height').style.display = 'block';
                document.getElementById('width').style.display = 'block';
                document.getElementById('length').style.display = 'block';
            }
        };

        document.getElementById('product_type').addEventListener('change', (event) => {
            const selectedProductType = event.target.value;
            fieldFunctions[selectedProductType]();
        });
    </script>
</body>
</html>
