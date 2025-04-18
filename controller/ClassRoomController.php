<?php
require_once __DIR__ . '/../dao/ClassRoomDAO.php';
require_once __DIR__ . '/../entity/ClassRoom.php';

class ClassRoomController {
    private $classroomDAO;

    public function __construct() {
        $this->classroomDAO = new ClassRoomDAO();
    }

    public function index() {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'teacher') {
            header('Location: index.php?controller=user&action=login');
            exit;
        }

        $classrooms = $this->classroomDAO->getByTeacherId($_SESSION['user_id']);
        include __DIR__ . '/../view/classroom/index.php';
    }

    public function create() {
        echo 'classroom create' ; 
            // die();
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'teacher') {
            
            header('Location: index.php?controller=user&action=login');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $classroom = new ClassRoom(
                null,
                $_POST['name'],
                $_POST['description'],
                $_SESSION['user_id']
            );

            if ($this->classroomDAO->create($classroom)) {
                header('Location: index.php?controller=classroom&action=index');
                exit;
            }
        }

        include __DIR__ . '/../view/classroom/create.php';
    }

    public function edit() {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'teacher') {
            header('Location: index.php?action=login');
            exit;
        }

        $id = $_GET['id'] ?? null;
        $classroom = $this->classroomDAO->getById($id);

        if (!$classroom || $classroom->getTeacherId() != $_SESSION['user_id']) {
            header('Location: index.php?controller=classroom&action=index');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $classroom->setName($_POST['name']);
            $classroom->setDescription($_POST['description']);

            if ($this->classroomDAO->update($classroom)) {
                header('Location: index.php?controller=classroom&action=index');
                exit;
            }
        }

        include __DIR__ . '/../view/classroom/edit.php';
    }

    public function delete() {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'teacher') {
            header('Location: index.php?action=login');
            exit;
        }

        $id = $_GET['id'] ?? null;
        if ($id && $this->classroomDAO->delete($id, $_SESSION['user_id'])) {
            header('Location: index.php?controller=classroom&action=index');
            exit;
        }

        header('Location: index.php?controller=classroom&action=index');
    }

    public function view() {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'teacher') {
            header('Location: index.php?action=login');
            exit;
        }

        $id = $_GET['id'] ?? null;
        $classroom = $this->classroomDAO->getById($id);

        if (!$classroom || $classroom->getTeacherId() != $_SESSION['user_id']) {
            header('Location: index.php?controller=classroom&action=index');
            exit;
        }

        $students = $this->classroomDAO->getStudents($id);
        include __DIR__ . '/../view/classroom/view.php';
    }

    public function addStudent() {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'teacher') {
            header('Location: index.php?action=login');
            exit;
        }

        $classroomId = $_POST['classroom_id'] ?? null;
        $studentId = $_POST['student_id'] ?? null;

        if ($classroomId && $studentId) {
            $classroom = $this->classroomDAO->getById($classroomId);
            if ($classroom && $classroom->getTeacherId() == $_SESSION['user_id']) {
                $this->classroomDAO->addStudent($classroomId, $studentId);
            }
        }

        header('Location: index.php?controller=classroom&action=view&id=' . $classroomId);
    }

    public function removeStudent() {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'teacher') {
            header('Location: index.php?action=login');
            exit;
        }

        $classroomId = $_GET['classroom_id'] ?? null;
        $studentId = $_GET['student_id'] ?? null;

        if ($classroomId && $studentId) {
            $classroom = $this->classroomDAO->getById($classroomId);
            if ($classroom && $classroom->getTeacherId() == $_SESSION['user_id']) {
                $this->classroomDAO->removeStudent($classroomId, $studentId);
            }
        }

        header('Location: index.php?controller=classroom&action=view&id=' . $classroomId);
    }
}
