<?php
class User {
    // Properties
    private $id;
    private $username;
    private $email;
    private $password;
    private $created_at;
    private $role;

    // Constructor
    public function __construct($id = null, $username = null, $email = null, $password = null, $created_at = null, $role = 'user') {
        $this->id = $id;
        $this->username = $username;
        $this->email = $email;
        $this->password = $password;
        $this->created_at = $created_at;
        $this->role = $role;
    }

    // Getters
    public function getId() {
        return $this->id;
    }

    public function getUsername() {
        return $this->username;
    }

    public function getEmail() {
        return $this->email;
    }

    public function getPassword() {
        return $this->password;
    }

    public function getCreatedAt() {
        return $this->created_at;
    }
    
    public function getRole() {
        return $this->role;
    }

    // Setters
    public function setId($id) {
        $this->id = $id;
    }

    public function setUsername($username) {
        $this->username = $username;
    }

    public function setEmail($email) {
        $this->email = $email;
    }
    
    public function setRole($role) {
        $this->role = $role;
    }

    public function setPassword($password) {
        $this->password = $password;
    }

    public function setCreatedAt($created_at) {
        $this->created_at = $created_at;
    }
}
?>