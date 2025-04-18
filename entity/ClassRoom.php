<?php
class ClassRoom {
    private $id;
    private $name;
    private $description;
    private $teacher_id;
    private $created_at;
    private $updated_at;

    public function __construct($id = null, $name = null, $description = null, $teacher_id = null) {
        $this->id = $id;
        $this->name = $name;
        $this->description = $description;
        $this->teacher_id = $teacher_id;
        $this->created_at = date('Y-m-d H:i:s');
        $this->updated_at = date('Y-m-d H:i:s');
    }

    // Getters
    public function getId() { return $this->id; }
    public function getName() { return $this->name; }
    public function getDescription() { return $this->description; }
    public function getTeacherId() { return $this->teacher_id; }
    public function getCreatedAt() { return $this->created_at; }
    public function getUpdatedAt() { return $this->updated_at; }

    // Setters
    public function setId($id) { $this->id = $id; }
    public function setName($name) { $this->name = $name; }
    public function setDescription($description) { $this->description = $description; }
    public function setTeacherId($teacher_id) { $this->teacher_id = $teacher_id; }
    public function setCreatedAt($created_at) { $this->created_at = $created_at; }
    public function setUpdatedAt($updated_at) { $this->updated_at = $updated_at; }
}