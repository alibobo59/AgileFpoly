<?php
require_once __DIR__ . '/controller/UserController.php';
require_once __DIR__ . '/controller/ClassRoomController.php';

// Start session if not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Simple router
$controller = isset($_GET['controller']) ? $_GET['controller'] : 'user';
$action = isset($_GET['action']) ? $_GET['action'] : 'index';
$id = isset($_GET['id']) ? $_GET['id'] : null;

// If not logged in and trying to access protected routes, redirect to login
if (!isset($_SESSION['user_id']) && !in_array($action, ['login', 'register'])) {
    header('Location: index.php?controller=user&action=login');
    exit;
}

// If logged in and accessing login/register pages, redirect to appropriate dashboard
if (isset($_SESSION['user_id']) && in_array($action, ['login', 'register'])) {
    header('Location: index.php?controller=user&action=index');
    exit;
}

switch ($controller) {
    case 'classroom':
        $classroomController = new ClassRoomController();
        switch ($action) {
            case 'create':
                $classroomController->create();
                break;
            case 'edit':
                $classroomController->edit();
                break;
            case 'delete':
                $classroomController->delete();
                break;
            case 'view':
                $classroomController->view();
                break;
            case 'index':
            default:
                $classroomController->index();
                break;
        }
        break;

    case 'user':
    default:
        $userController = new UserController();
        switch ($action) {
            case 'login':
                $userController->login();
                break;
            case 'register':
                $userController->register();
                break;
            case 'logout':
                $userController->logout();
                break;
            case 'index':
            default:
                $userController->index();
                break;
        }
        break;
}
?>
