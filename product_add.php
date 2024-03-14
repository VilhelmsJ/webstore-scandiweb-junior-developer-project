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
        // Add the product based on product type
        switch ($product['product_type']) {
            case 'Furniture':
                $attribute_value = $_POST['height'] . 'x' . $_POST['width'] . 'x' . $_POST['length'];
                break;
            case 'Book':
                $attribute_value = $_POST['weight'] . ' KG';
                break;
            case 'DVD-disc':
                $attribute_value = $_POST['size'] . ' MB';
                break;
            default:
                $attribute_value = '';
        }
        
        // Assign the calculated attribute value to the product array
        $product['attribute_value'] = $attribute_value;

        // Add the product
        $result = $productManager->addProduct(
            $product['name'],
            $product['price'],
            $product['product_type'],
            $product['attribute_value']
        );
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product add page</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
<div class="container">
    <h1>Product add page</h1>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST" id="product_form" class="product-add-form">
        <div class="form-group">
            <label for="sku"><i>SKU will be added dinamically</i></label>
        </div>
        <div class="form-group">
            <label for="name">Product Name:</label>
            <input type="text" id="name" name="name" required>
        </div>
        <div class="form-group">
            <label for="price">Price:</label>
            <input type="number" id="price" name="price" required>
        </div>
        <div class="form-group">
            <label for="productType">Product Type:</label>
            <select id="productType" name="product_type" required>
                <option value="">Select Product Type</option>
                <option value="Furniture">Furniture</option>
                <option value="Book">Book</option>
                <option value="DVD-disc">DVD-disc</option>
            </select>
        </div>
        <div class="form-group" id="attribute_value_fields">
            <!-- Attribute value fields will be added dynamically here based on product type -->
            <!-- Hidden input field for attribute value -->
            <input type="hidden" id="attribute_value" name="attribute_value">
            <!-- Dimension fields -->
            <div class="form-group" id="Furniture">
            <p><i>Please, provide dimensions</i></p>
                <div class="form-group">
                    <label for="height">Height (CM):</label>
                    <input type="text" id="height" name="height" required>
                    <p><i>Height</i></p>
                </div>
                <div class="form-group">
                    <label for="width">Width (CM):</label>
                    <input type="text" id="width" name="width" required>
                    <p><i>Width</i></p>
                </div>
                <div class="form-group">
                    <label for="length">Length (CM):</label>
                    <input type="text" id="length" name="length" required>
                    <p><i>Lenght</i></p>
                </div>
            </div>
            <!-- Weight field -->
            <div class="form-group" id="Book">
                <label for="weight">Weight (KG):</label>
                <input type="text" id="weight" name="weight" required>
                <p><i>Please, provide weight</i></p>
            </div>
            <!-- Size field -->
            <div class="form-group" id="DVD">
                <label for="size">Size (MB):</label>
                <input type="text" id="size" name="size" required>
                <p><i>Please, provide size</i></p>
            </div>
        </div>
        <a href="index.php" id="saveButton">Save</a>
    </form>
    <a href="index.php">Back to Product List</a>
</div>

<!-- JavaScript code for adding attribute value fields dynamically based on product type -->
<script>
    const fieldFunctions = {
        Furniture: () => {
            // Show dimensions fields
            document.getElementById('Furniture').style.display = 'block';
            // Hide weight and size fields
            document.getElementById('Book').style.display = 'none';
            document.getElementById('DVD').style.display = 'none';
        },
        Book: () => {
            // Show weight field
            document.getElementById('Book').style.display = 'block';
            // Hide dimensions and size fields
            document.getElementById('Furniture').style.display = 'none';
            document.getElementById('DVD').style.display = 'none';
        },
        "DVD-disc": () => {
            // Show size field
            document.getElementById('DVD').style.display = 'block';
            // Hide weight and dimensions fields
            document.getElementById('Book').style.display = 'none';
            document.getElementById('Furniture').style.display = 'none';
        }
    };

    document.getElementById('productType').addEventListener('change', (event) => {
        const selectedProductType = event.target.value;
        // Call the corresponding function based on the selected product type
        fieldFunctions[selectedProductType]();
    });

    // Function to concatenate dimension values
    function concatenateDimensions() {
        const height = document.getElementById('height').value;
        const width = document.getElementById('width').value;
        const length = document.getElementById('length').value;
        const concatenatedDimensions = `${height}x${width}x${length}`;
        // Assign the concatenated dimensions to the hidden input field
        document.getElementById('attribute_value').value = concatenatedDimensions;
    }

    // Call the concatenateDimensions() function whenever any of the dimension input fields are changed
    document.getElementById('height').addEventListener('input', concatenateDimensions);
    document.getElementById('width').addEventListener('input', concatenateDimensions);
    document.getElementById('length').addEventListener('input', concatenateDimensions);

    // Call the concatenateDimensions() function initially to ensure that dimensions are set when the page loads
    concatenateDimensions();

    // Add an event listener to the "Save" button
    document.getElementById('saveButton').addEventListener('click', function(event) {
        // Prevent default form submission
        event.preventDefault();

        // Perform client-side validation
        if (!validateForm()) {
            return; // Exit early if validation fails
        }

        // If validation passes, submit the form
        document.getElementById('product_form').submit();
    });

     // Function to perform client-side form validation
    function validateForm() {
        // Get the selected product type
        var productType = document.getElementById('productType').value;

        // Validate based on product type
        switch (productType) {
            case 'Dimensions':
                // Validate dimensions input fields
                if (!validateDimensions()) {
                    return false;
                }
                break;
            case 'Book':
                // Validate weight input field
                if (!validateWeight()) {
                    return false;
                }
                break;
            case 'DVD-disc':
                // Validate size input field
                if (!validateSize()) {
                    return false;
                }
                break;
            default:
                // No specific validation for other product types
        }

        // All validations passed
        return true;
    } 
    
     // Function to validate dimensions input fields
function validateDimensions() {
    var height = document.getElementById('height').value.trim();
    var width = document.getElementById('width').value.trim();
    var length = document.getElementById('length').value.trim();

    // Check if any of the dimension fields are empty
    if (height === '' || width === '' || length === '') {
        alert('Please enter values for all dimensions (height, width, length).');
        return false;
    }

    // Check if the entered values are numeric
    if (isNaN(height) || isNaN(width) || isNaN(length)) {
        alert('Please enter numeric values for dimensions.');
        return false;
    }

    // Optionally, you can add more specific validation rules here,
    // such as checking if dimensions are within certain ranges.

    return true; // Validation passes
}

// Function to validate weight input field
function validateWeight() {
    var weight = document.getElementById('weight').value.trim();

    // Check if the weight field is empty
    if (weight === '') {
        alert('Please enter the weight.');
        return false;
    }

    // Check if the entered value is numeric
    if (isNaN(weight)) {
        alert('Please enter a numeric value for weight.');
        return false;
    }

    // Optionally, you can add more specific validation rules here,
    // such as checking if weight is within a certain range.

    return true; // Validation passes
}

// Function to validate size input field
function validateSize() {
    var size = document.getElementById('size').value.trim();

    // Check if the size field is empty
    if (size === '') {
        alert('Please enter the size.');
        return false;
    }

    // Check if the entered value is numeric
    if (isNaN(size)) {
        alert('Please enter a numeric value for size.');
        return false;
    }

    // Optionally, you can add more specific validation rules here,
    // such as checking if size is within a certain range.

    return true; // Validation passes
}

</script>
</body>
</html>
