<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../entity/ClassRoom.php';

class ClassRoomDAO {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
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

    public function delete($id, $teacherId) {
        $sql = "DELETE FROM classrooms WHERE id = ? AND teacher_id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$id, $teacherId]);
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

    public function getStudents($classroomId) {
        $sql = "SELECT u.* FROM users u 
                JOIN classroom_students cs ON u.id = cs.student_id 
                WHERE cs.classroom_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$classroomId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}