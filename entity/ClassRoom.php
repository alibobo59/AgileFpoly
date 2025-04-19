<?php
class ClassRoom {
    private $id;
    private $name;
    private $description;
    private $teacher_id;
    private $created_at;
    private $updated_at;
    private $teacher_name;  // Add new property
    private $enrollment_date; // Add enrollment date property

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

    // Add new methods for teacher name
    public function getTeacherName() {
        return $this->teacher_name;
    }

    public function setTeacherName($teacher_name) {
        $this->teacher_name = $teacher_name;
    }
    
    // Add methods for enrollment date
    public function getEnrollmentDate() {
        return $this->enrollment_date;
    }
    
    public function setEnrollmentDate($enrollment_date) {
        $this->enrollment_date = $enrollment_date;
    }
}