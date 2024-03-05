<?php
require_once 'Database.php';

class ProductManager {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    public function addProduct($name, $price, $product_type, $attribute_value) {
        $conn = $this->db->getConnection();
        $sql = "INSERT INTO Products (name, price, product_type, attribute_value) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssss", $name, $price, $product_type, $attribute_value);
        return $stmt->execute();
    }

    public function getProductList() {
        $conn = $this->db->getConnection();
        $sql = "SELECT * FROM Products";
        $result = $conn->query($sql);

        $productList = array();
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $productList[] = $row;
            }
        }
        return $productList;
    }

    public function getProductById($sku) {
        $conn = $this->db->getConnection();
        $sql = "SELECT * FROM Products WHERE sku = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $sku);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    public function updateProduct($sku, $name, $price, $product_type, $attribute_value) {
        $conn = $this->db->getConnection();
        $sql = "UPDATE Products SET name = ?, price = ?, product_type = ?, attribute_value = ? WHERE sku = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sdsds", $name, $price, $product_type, $attribute_value, $sku);
        return $stmt->execute();
    }

    public function deleteProduct($sku) {
        $conn = $this->db->getConnection();
        $sql = "DELETE FROM Products WHERE sku = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $sku);
        return $stmt->execute();
    }
}
