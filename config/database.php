<?php
class Database {
    private $host = "localhost";
    private $db_name = "agile";
    private $username = "root";
    private $password = "";
    private $conn;
    private static $instance = null;

    private function __construct() {
        // Initialize connection as null
        $this->conn = null;
    }

    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function getConnection() {
        if ($this->conn === null) {
            try {
                $this->conn = new PDO(
                    "mysql:host=" . $this->host . ";dbname=" . $this->db_name,
                    $this->username,
                    $this->password
                );
                $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $this->conn->exec("set names utf8");
            } catch(PDOException $e) {
                die("Database connection failed: " . $e->getMessage());
            }
        }
        return $this->conn;
    }

    // Prevent cloning and unserialization
    private function __clone() {}
    public function __wakeup() {
        throw new Exception("Cannot unserialize singleton");
    }
}
?>