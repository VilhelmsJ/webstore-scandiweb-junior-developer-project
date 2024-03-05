<?php
class Database {
    private $host = "localhost";
    private $username = "root";
    private $password = "";
    private $dbname = "estore";

    public function getConnection() {
        $conn = new mysqli($this->host, $this->username, $this->password, $this->dbname);
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }
        return $conn;
    }
}
?>

<?php
$db = new Database();
$conn = $db->getConnection();
// Now you can use $conn for executing SQL queries.
?>