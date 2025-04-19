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

// Debugging: Check which controller and action are being called


// If not logged in and trying to access protected routes, redirect to login
// Update the authorization check to allow admin access
if (!isset($_SESSION['user_id']) && 
    !in_array($action, ['login', 'register']) &&
    ($controller !== 'user' || $action !== 'admin_dashboard')) {
    header('Location: index.php?controller=user&action=login');
    exit;
}

// Add special admin route handling
switch ($controller) {
    case 'user':
    default:
        $userController = new UserController();
        switch ($action) {
            case 'create':
                $userController->create();
                break;
            case 'edit':
                if ($id) {
                    $userController->edit($id);
                } else {
                    header('Location: index.php?controller=user&action=index');
                }
                break;
            case 'delete':
                if ($id) {
                    $userController->delete($id);
                } else {
                    header('Location: index.php?controller=user&action=index');
                }
                break;
            case 'view':
                if ($id) {
                    $userController->view($id);
                } else {
                    header('Location: index.php?controller=user&action=index');
                }
                break;
            case 'login':
                $userController->login();
                break;
            case 'register':
                $userController->register();
                break;
            case 'logout':
                $userController->logout();
                break;
            case 'admin_dashboard':
                if ($_SESSION['user_role'] === 'admin') {
                    $userController->admin_dashboard();
                } else {
                    header('Location: index.php?controller=user&action=login');
                    exit;
                }
                break;
            case 'teacher_dashboard':
                $userController->teacher_dashboard();
                break;
            case 'student_dashboard':
                $userController->student_dashboard();
                break;
            case 'profile':
                $userController->profile();
                break;
            case 'index':
            default:
                $userController->index();
                break;
        }
        break;
}
?>
