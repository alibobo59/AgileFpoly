<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../entity/ClassRoom.php';

class ClassRoomDAO {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
        
        // Create tables if they don't exist
        $this->createTable();
    }
    
    private function createTable() {
        // Create classrooms table
        $query = "CREATE TABLE IF NOT EXISTS classrooms (
            id INT NOT NULL AUTO_INCREMENT,
            name VARCHAR(100) NOT NULL,
            description TEXT,
            teacher_id INT NOT NULL,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            updated_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (id),
            FOREIGN KEY (teacher_id) REFERENCES users(id)
        )";
        $this->db->exec($query);
        
        // Create classroom_students table for many-to-many relationship
        $query = "CREATE TABLE IF NOT EXISTS classroom_students (
            classroom_id INT NOT NULL,
            student_id INT NOT NULL,
            joined_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (classroom_id, student_id),
            FOREIGN KEY (classroom_id) REFERENCES classrooms(id) ON DELETE CASCADE,
            FOREIGN KEY (student_id) REFERENCES users(id) ON DELETE CASCADE
        )";
        $this->db->exec($query);
    }

    public function create($classroom) {
        $sql = "INSERT INTO classrooms (name, description, teacher_id, created_at, updated_at) 
                VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            $classroom->getName(),
            $classroom->getDescription(),
            $classroom->getTeacherId(),
            $classroom->getCreatedAt(),
            $classroom->getUpdatedAt()
        ]);
    }

    public function getById($id) {
        $sql = "SELECT * FROM classrooms WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($result) {
            $classroom = new ClassRoom();
            $classroom->setId($result['id']);
            $classroom->setName($result['name']);
            $classroom->setDescription($result['description']);
            $classroom->setTeacherId($result['teacher_id']);
            $classroom->setCreatedAt($result['created_at']);
            $classroom->setUpdatedAt($result['updated_at']);
            return $classroom;
        }
        return null;
    }

    public function getByTeacherId($teacherId) {
        $sql = "SELECT * FROM classrooms WHERE teacher_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$teacherId]);
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        $classrooms = [];
        foreach ($results as $result) {
            $classroom = new ClassRoom();
            $classroom->setId($result['id']);
            $classroom->setName($result['name']);
            $classroom->setDescription($result['description']);
            $classroom->setTeacherId($result['teacher_id']);
            $classroom->setCreatedAt($result['created_at']);
            $classroom->setUpdatedAt($result['updated_at']);
            $classrooms[] = $classroom;
        }
        return $classrooms;
    }

    public function update($classroom) {
        $sql = "UPDATE classrooms 
                SET name = ?, description = ?, updated_at = ? 
                WHERE id = ? AND teacher_id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            $classroom->getName(),
            $classroom->getDescription(),
            date('Y-m-d H:i:s'),
            $classroom->getId(),
            $classroom->getTeacherId()
        ]);
    }

    public function delete($id) {
        $sql = "DELETE FROM classrooms WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$id]);
    }

    public function addStudent($classroomId, $studentId) {
        $sql = "INSERT INTO classroom_students (classroom_id, student_id, joined_at) 
                VALUES (?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            $classroomId,
            $studentId,
            date('Y-m-d H:i:s')
        ]);
    }

    public function removeStudent($classroomId, $studentId) {
        $sql = "DELETE FROM classroom_students 
                WHERE classroom_id = ? AND student_id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$classroomId, $studentId]);
    }

    public function getStudentsInClassroom($classroomId) {
        $sql = "SELECT u.* FROM users u 
                JOIN classroom_students cs ON u.id = cs.student_id 
                WHERE cs.classroom_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$classroomId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getByStudentId($studentId) {
        $sql = "SELECT c.*, u.username as teacher_name, cs.joined_at as enrollment_date 
                FROM classrooms c
                JOIN classroom_students cs ON c.id = cs.classroom_id
                JOIN users u ON c.teacher_id = u.id
                WHERE cs.student_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$studentId]);
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        $classrooms = [];
        foreach ($results as $result) {
            $classroom = new ClassRoom();
            $classroom->setId($result['id']);
            $classroom->setName($result['name']);
            $classroom->setDescription($result['description']);
            $classroom->setTeacherId($result['teacher_id']);
            $classroom->setCreatedAt($result['created_at']);
            $classroom->setUpdatedAt($result['updated_at']);
            $classroom->setTeacherName($result['teacher_name']);
            $classroom->setEnrollmentDate($result['enrollment_date']);
            $classrooms[] = $classroom;
        }
        return $classrooms;
    }

    public function getTeacherName($teacherId) {
        $sql = "SELECT username FROM users WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$teacherId]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['username'] ?? '';
    }
    
    public function getAll() {
        $sql = "SELECT c.*, u.username as teacher_name 
                FROM classrooms c
                JOIN users u ON c.teacher_id = u.id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        $classrooms = [];
        foreach ($results as $result) {
            $classroom = new ClassRoom();
            $classroom->setId($result['id']);
            $classroom->setName($result['name']);
            $classroom->setDescription($result['description']);
            $classroom->setTeacherId($result['teacher_id']);
            $classroom->setCreatedAt($result['created_at']);
            $classroom->setUpdatedAt($result['updated_at']);
            $classroom->setTeacherName($result['teacher_name']);
            $classrooms[] = $classroom;
        }
        return $classrooms;
    }

    public function isStudentInClassroom($classroomId, $studentId) {
        $sql = "SELECT * FROM classroom_students 
                WHERE classroom_id = ? AND student_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$classroomId, $studentId]);
        return $stmt->rowCount() > 0;
    }
}