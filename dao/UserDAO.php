<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../entity/User.php';

class UserDAO {
    private $conn;
    private $table_name = "users";

    public function __construct() {
        $database = Database::getInstance();
        $this->conn = $database->getConnection();
        
        // Create table if it doesn't exist
        $this->createTable();
    }

    private function createTable() {
        $query = "CREATE TABLE IF NOT EXISTS " . $this->table_name . " (
            id INT NOT NULL AUTO_INCREMENT,
            username VARCHAR(100) NOT NULL,
            email VARCHAR(100) NOT NULL UNIQUE,
            password VARCHAR(255) NOT NULL,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            role VARCHAR(20) DEFAULT 'user',
            PRIMARY KEY (id)
        )";

        $this->conn->exec($query);
    }

    // Create a new user
    public function create(User $user) {
        $query = "INSERT INTO " . $this->table_name . " (username, email, password, role) VALUES (:username, :email, :password, :role)";

        $stmt = $this->conn->prepare($query);

        // Sanitize and bind values
        $username = htmlspecialchars(strip_tags($user->getUsername()));
        $email = htmlspecialchars(strip_tags($user->getEmail()));
        $password = password_hash($user->getPassword(), PASSWORD_DEFAULT); // Hash the password
        $role = htmlspecialchars(strip_tags($user->getRole()));

        $stmt->bindParam(":username", $username);
        $stmt->bindParam(":email", $email);
        $stmt->bindParam(":password", $password);
        $stmt->bindParam(":role", $role);

        if ($stmt->execute()) {
            $user->setId($this->conn->lastInsertId());
            return true;
        }

        return false;
    }

    // Read all users
    public function readAll() {
        $query = "SELECT * FROM " . $this->table_name . " ORDER BY created_at DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        $users = [];

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $user = new User(
                $row['id'],
                $row['username'],
                $row['email'],
                $row['password'],
                $row['created_at'],
                $row['role']
            );
            $users[] = $user;
        }

        return $users;
    }

    // Read a single user
    public function readOne($id) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE id = :id LIMIT 0,1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            return new User(
                $row['id'],
                $row['username'],
                $row['email'],
                $row['password'],
                $row['created_at'],
                $row['role']
            );
        }

        return null;
    }

    // Update a user
    public function update(User $user) {
        $query = "UPDATE " . $this->table_name . " 
                SET username = :username, 
                    email = :email,
                    role = :role
                WHERE id = :id";

        $stmt = $this->conn->prepare($query);

        // Sanitize and bind values
        $id = $user->getId();
        $username = htmlspecialchars(strip_tags($user->getUsername()));
        $email = htmlspecialchars(strip_tags($user->getEmail()));
        $role = htmlspecialchars(strip_tags($user->getRole()));

        $stmt->bindParam(":id", $id);
        $stmt->bindParam(":username", $username);
        $stmt->bindParam(":email", $email);
        $stmt->bindParam(":role", $role);

        if ($stmt->execute()) {
            return true;
        }

        return false;
    }

    // Update user password
    public function updatePassword(User $user) {
        $query = "UPDATE " . $this->table_name . " 
                SET password = :password
                WHERE id = :id";

        $stmt = $this->conn->prepare($query);

        // Hash and bind password
        $id = $user->getId();
        $password = password_hash($user->getPassword(), PASSWORD_DEFAULT);

        $stmt->bindParam(":id", $id);
        $stmt->bindParam(":password", $password);

        if ($stmt->execute()) {
            return true;
        }

        return false;
    }

    // Delete a user
    public function delete($id) {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id);

        if ($stmt->execute()) {
            return true;
        }

        return false;
    }
    
    // Login user
    public function login($email, $password) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE email = :email LIMIT 0,1";
        $stmt = $this->conn->prepare($query);
        
        // Sanitize email
        $email = htmlspecialchars(strip_tags($email));
        
        // Bind parameters
        $stmt->bindParam(":email", $email);
        
        // Execute query
        $stmt->execute();
        
        // Get row count
        if($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            
            // Verify password
            if(password_verify($password, $row['password'])) {
                return new User(
                    $row['id'],
                    $row['username'],
                    $row['email'],
                    '',  // Don't return the password
                    $row['created_at'],
                    $row['role']
                );
            }
        }
        
        return null;
    }
    
    // Check if email exists
    public function emailExists($email) {
        $query = "SELECT COUNT(*) as count FROM " . $this->table_name . " WHERE email = :email";
        $stmt = $this->conn->prepare($query);
        
        // Sanitize email
        $email = htmlspecialchars(strip_tags($email));
        
        // Bind parameters
        $stmt->bindParam(":email", $email);
        
        // Execute query
        $stmt->execute();
        
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        return $row['count'] > 0;
    }
    
    // Get user by email
    public function getByEmail($email) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE email = :email LIMIT 0,1";
        $stmt = $this->conn->prepare($query);
        
        // Sanitize email
        $email = htmlspecialchars(strip_tags($email));
        
        // Bind parameters
        $stmt->bindParam(":email", $email);
        
        // Execute query
        $stmt->execute();
        
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($row) {
            return new User(
                $row['id'],
                $row['username'],
                $row['email'],
                $row['password'],
                $row['created_at'],
                $row['role']
            );
        }
        
        return null;
    }
    
    public function getConnection() {
        return $this->conn;
    }
    
    public function getAllUsers() {
        $sql = "SELECT id, username, email, role, created_at FROM users ORDER BY created_at DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>