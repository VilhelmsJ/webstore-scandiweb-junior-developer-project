<?php
require_once 'classes/ProductManager.php';

// Instantiate the ProductManager class
$productManager = new ProductManager();

// Validate and sanitize input (you should implement validation)

// Validate SKU (Without Using If Statements)
$errors = ($_POST['sku']) ? [] : ["SKU cannot be empty."];

// Display errors if any
echo implode("<br>", $errors);

// Proceed with adding the product if there are no errors
if (!$errors) {
    $productManager->addProduct($_POST['name'], $_POST['price'], $_POST['product_type'], $_POST['attribute_value']);
}

$sku = $_POST["sku"];
$name = $_POST["name"];
$price = $_POST["price"];
$product_type = $_POST["product_type"];
$attribute_value = $_POST["attribute_value"];

// Define a mapping of product types to their respective attribute names
$attribute_names = [
    'Size' => 'size',
    'Weight' => 'weight',
    'Dimensions' => ['height', 'width', 'length']
];

// Extract attribute value based on the product type
$attribute_value = array_reduce((array)($attribute_names[$product_type] ?? ''), function ($carry, $attribute) {
    return $carry . ($_POST[$attribute] ?? '');
}, '');

// Add the product
$result = $productManager->addProduct($name, $price, $product_type, $attribute_value);

if ($result) {
    echo "Product added successfully.";
} else {
    echo "Failed to add product.";
}
