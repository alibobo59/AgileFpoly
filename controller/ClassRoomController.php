<?php
require_once __DIR__ . '/../dao/ClassRoomDAO.php';
require_once __DIR__ . '/../entity/ClassRoom.php';
require_once __DIR__ . '/../dao/UserDAO.php';

class ClassRoomController {
    private $classroomDAO;
    private $userDAO;

    public function __construct() {
        $this->classroomDAO = new ClassRoomDAO();
        $this->userDAO = new UserDAO();
        
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
    }
    
    public function index() {
        // Check if user is logged in
        if (!isset($_SESSION['user_id'])) {
            header('Location: index.php?controller=user&action=login');
            exit;
        }
        
        // Get classrooms based on user role
        $classrooms = [];
        
        if ($_SESSION['user_role'] === 'teacher') {
            $classrooms = $this->classroomDAO->getByTeacherId($_SESSION['user_id']);
            include __DIR__ . '/../view/classroom/index.php';
        } elseif ($_SESSION['user_role'] === 'student') {
            $classrooms = $this->classroomDAO->getByStudentId($_SESSION['user_id']);
            include __DIR__ . '/../view/classroom/student_index.php';
        } elseif ($_SESSION['user_role'] === 'admin') {
            $classrooms = $this->classroomDAO->getAll();
            include __DIR__ . '/../view/classroom/index.php';
        }
    }
    
    public function create() {
        // Check if user is logged in and is a teacher
        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'teacher') {
            header('Location: index.php?controller=user&action=login');
            exit;
        }
        
        // Handle form submission
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = $_POST['name'] ?? '';
            $description = $_POST['description'] ?? '';
            
            if (!empty($name)) {
                $classroom = new ClassRoom(null, $name, $description, $_SESSION['user_id']);
                if ($this->classroomDAO->create($classroom)) {
                    header('Location: index.php?controller=classroom&action=index');
                    exit;
                }
            }
        }
        
        include __DIR__ . '/../view/classroom/create.php';
    }
    
    public function edit($id) {
        // Check if user is logged in and is a teacher
        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'teacher') {
            header('Location: index.php?controller=user&action=login');
            exit;
        }
        
        // Get classroom
        $classroom = $this->classroomDAO->getById($id);
        
        // Check if classroom exists and belongs to the teacher
        if (!$classroom || $classroom->getTeacherId() != $_SESSION['user_id']) {
            header('Location: index.php?controller=classroom&action=index');
            exit;
        }
        
        // Handle form submission
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = $_POST['name'] ?? '';
            $description = $_POST['description'] ?? '';
            
            if (!empty($name)) {
                $classroom->setName($name);
                $classroom->setDescription($description);
                $classroom->setUpdatedAt(date('Y-m-d H:i:s'));
                
                if ($this->classroomDAO->update($classroom)) {
                    header('Location: index.php?controller=classroom&action=index');
                    exit;
                }
            }
        }
        
        include __DIR__ . '/../view/classroom/edit.php';
    }
    
    public function delete($id) {
        // Check if user is logged in and is a teacher
        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'teacher') {
            header('Location: index.php?controller=user&action=login');
            exit;
        }
        
        // Get classroom
        $classroom = $this->classroomDAO->getById($id);
        
        // Check if classroom exists and belongs to the teacher
        if ($classroom && $classroom->getTeacherId() == $_SESSION['user_id']) {
            $this->classroomDAO->delete($id);
        }
        
        header('Location: index.php?controller=classroom&action=index');
        exit;
    }
    
    public function view($id) {
        // Check if user is logged in
        if (!isset($_SESSION['user_id'])) {
            header('Location: index.php?controller=user&action=login');
            exit;
        }
        
        // Get classroom
        $classroom = $this->classroomDAO->getById($id);
        
        // Check if classroom exists
        if (!$classroom) {
            header('Location: index.php?controller=classroom&action=index');
            exit;
        }
        
        // Check if user is teacher of this classroom or admin
        $isTeacher = ($_SESSION['user_role'] === 'teacher' && $classroom->getTeacherId() == $_SESSION['user_id']);
        $isAdmin = ($_SESSION['user_role'] === 'admin');
        
        // Get students in classroom
        $students = $this->classroomDAO->getStudentsInClassroom($id);
        
        // Get all students for dropdown
        $allStudents = [];
        if ($isTeacher || $isAdmin) {
            $query = "SELECT id, username, email FROM users WHERE role = 'student'";
            $stmt = $this->userDAO->getConnection()->prepare($query);
            $stmt->execute();
            $allStudents = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
        
        include __DIR__ . '/../view/classroom/view.php';
    }
    
    public function addStudent() {
        error_log("ADD STUDENT STARTED"); // Debug
        
        // Check if user is logged in and is a teacher
        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'teacher') {
            header('Location: index.php?controller=user&action=login');
            exit;
        }

        $classroomId = isset($_POST['classroom_id']) ? filter_var($_POST['classroom_id'], FILTER_VALIDATE_INT) : null;
        $studentEmail = $_POST['student_email'] ?? null;  // Change from student_id to email

        if (!$classroomId || !$studentEmail) {
            $_SESSION['error'] = "Please provide a valid student email";
            header('Location: index.php?controller=classroom&action=view&id=' . $classroomId);
            exit;
        }

        // Find student by email
        error_log("Classroom ID: $classroomId, Student Email: $studentEmail"); // Debug

        $student = $this->userDAO->getByEmail($studentEmail);
        error_log("Student found: " . ($student ? $student->getId() : 'null')); // Debug

        if ($student && $student->getRole() === 'student') {
            error_log("Student valid, checking enrollment...");
            if ($this->classroomDAO->isStudentInClassroom($classroomId, $student->getId())) {
                error_log("Student already enrolled");
                $_SESSION['error'] = "Student already in class";
            } else {
                error_log("Attempting to enroll student");
                if ($this->classroomDAO->addStudent($classroomId, $student->getId())) {
                    error_log("Enrollment successful");
                    $_SESSION['success'] = "Student added successfully";
                } else {
                    error_log("Enrollment failed");
                    $_SESSION['error'] = "Failed to add student to classroom";
                }
            }
        } else {
            error_log("Invalid student or role mismatch");
            $_SESSION['error'] = "Student not found or invalid student ID";
        }
        
        // Verify classroom exists and belongs to the teacher
        $classroom = $this->classroomDAO->getById($classroomId);
        if (!$classroom || $classroom->getTeacherId() != $_SESSION['user_id']) {
            $_SESSION['error'] = "You don't have permission to add students to this classroom";
            header('Location: index.php?controller=classroom&action=index');
            exit;
        }

        if ($student && $student->getRole() === 'student') {
            // Add student to class
            if ($this->classroomDAO->isStudentInClassroom($classroomId, $student->getId())) {
                $_SESSION['error'] = "Student already in class";
            } else {
                if ($this->classroomDAO->addStudent($classroomId, $student->getId())) {
                    $_SESSION['success'] = "Student added successfully";
                }
            }
        }
        
        header('Location: index.php?controller=classroom&action=view&id=' . $classroomId);
        exit;
    }
    
    public function removeStudent() {
        // Check if user is logged in and is a teacher
        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'teacher') {
            header('Location: index.php?controller=user&action=login');
            exit;
        }
        
        $classroomId = isset($_POST['classroom_id']) ? filter_var($_POST['classroom_id'], FILTER_VALIDATE_INT) : null;
        $studentId = isset($_POST['student_id']) ? filter_var($_POST['student_id'], FILTER_VALIDATE_INT) : null;
        
        if (!$classroomId || !$studentId) {
            $_SESSION['error'] = "Invalid classroom ID or student ID";
            header('Location: index.php?controller=classroom&action=index');
            exit;
        }
        
        // Verify classroom exists and belongs to the teacher
        $classroom = $this->classroomDAO->getById($classroomId);
        if (!$classroom || $classroom->getTeacherId() != $_SESSION['user_id']) {
            $_SESSION['error'] = "You don't have permission to remove students from this classroom";
            header('Location: index.php?controller=classroom&action=index');
            exit;
        }
        
        if ($this->classroomDAO->removeStudent($classroomId, $studentId)) {
            $_SESSION['success'] = "Student removed successfully";
        } else {
            $_SESSION['error'] = "Failed to remove student from classroom";
        }
        
        header('Location: index.php?controller=classroom&action=view&id=' . $classroomId);
        exit;
    }
}
