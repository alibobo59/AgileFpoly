<?php
require_once __DIR__ . '/../dao/UserDAO.php';
require_once __DIR__ . '/../entity/User.php';

class UserController {
    private $userDAO;
    
    public function __construct() {
        try {
            $this->userDAO = new UserDAO();
            
            if (session_status() == PHP_SESSION_NONE) {
                session_start();
            }
        } catch (Exception $e) {
            die('Failed to initialize UserController: ' . $e->getMessage());
        }
    }
    
    public function index() {
        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
            header('Location: index.php?controller=user&action=login');
            exit;
        }
        
        $userDAO = new UserDAO();
        $users = $userDAO->getAllUsers();
        
        include __DIR__ . '/../view/user/admin_index.php';
    }
    
    public function create() {
        // Check if user is logged in and is admin
        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
            header('Location: index.php?controller=user&action=login');
            exit;
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $user = new User();
            $user->setUsername($_POST['username']);
            $user->setEmail($_POST['email']);
            $user->setPassword($_POST['password']);
            if (isset($_POST['role'])) {
                $user->setRole($_POST['role']);
            }
            
            if ($this->userDAO->create($user)) {
                header('Location: index.php?action=index');
                exit;
            } else {
                $error = "Failed to create user";
                include __DIR__ . '/../view/user/create.php';
            }
        } else {
            include __DIR__ . '/../view/user/create.php';
        }
    }
    
    public function edit($id) {
        $user = $this->userDAO->readOne($id);
        
        if (!$user) {
            header('Location: index.php?action=index');
            exit;
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $user->setUsername($_POST['username']);
            $user->setEmail($_POST['email']);
            
            if ($this->userDAO->update($user)) {
                header('Location: index.php?action=index');
                exit;
            } else {
                $error = "Failed to update user";
            }
        }
        
        include __DIR__ . '/../view/user/edit.php';
    }
    
    public function delete($id) {
        if ($this->userDAO->delete($id)) {
            header('Location: index.php?action=index');
            exit;
        } else {
            $error = "Failed to delete user";
            $this->index();
        }
    }
    
    public function view($id) {
        $user = $this->userDAO->readOne($id);
        
        if (!$user) {
            header('Location: index.php?action=index');
            exit;
        }
        
        include __DIR__ . '/../view/user/view.php';
    }

    public function admin_dashboard() {
        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
            header('Location: index.php?action=login');
            exit;
        }
        include __DIR__ . '/../view/dashboard/admin_dashboard.php';
    }

    public function teacher_dashboard() {
        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'teacher') {
            header('Location: index.php?action=login');
            exit;
        }
        include __DIR__ . '/../view/dashboard/teacher_dashboard.php';
    }

    public function student_dashboard() {
        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'student') {
            header('Location: index.php?action=login');
            exit;
        }
        include __DIR__ . '/../view/dashboard/student_dashboard.php';
    }
    // Login method
    public function login() {
        // If user is already logged in, redirect to index
        if (isset($_SESSION['user_id'])) {
            header('Location: index.php?action=index');
            exit;
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = isset($_POST['email']) ? $_POST['email'] : '';
            $password = isset($_POST['password']) ? $_POST['password'] : '';
            
            $user = $this->userDAO->login($email, $password);
            
            if ($user) {
                // Set session variables
                $_SESSION['user_id'] = $user->getId();
                $_SESSION['username'] = $user->getUsername();
                $_SESSION['user_email'] = $user->getEmail();
                $_SESSION['user_role'] = $user->getRole();
                
                // Redirect based on user role
                switch ($_SESSION['user_role']) {
                    case 'admin':
                        header('Location: index.php?controller=user&action=admin_dashboard');
                        break;
                    case 'teacher':
                        header('Location: index.php?controller=user&action=teacher_dashboard');
                        break;
                    case 'student':
                        header('Location: index.php?controller=user&action=student_dashboard');
                        break;
                    default:
                        header('Location: index.php?action=index');
                }
                exit;
            } else {
                $error = "Invalid email or password";
                include __DIR__ . '/../view/user/login.php';
            }
        } else {
            include __DIR__ . '/../view/user/login.php';
        }
    }
    
    // Register method
    public function register() {
        // If user is already logged in, redirect to index
        if (isset($_SESSION['user_id'])) {
            header('Location: index.php?action=index');
            exit;
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Check if email already exists
            if ($this->userDAO->emailExists($_POST['email'])) {
                $error = "Email already exists";
                include __DIR__ . '/../view/user/register.php';
                return;
            }
            
            // Create new user
            $user = new User();
            $user->setUsername($_POST['username']);
            $user->setEmail($_POST['email']);
            $user->setPassword($_POST['password']);
            // Set role from form selection
            if (isset($_POST['role']) && in_array($_POST['role'], ['student', 'teacher', 'admin'])) {
                $user->setRole($_POST['role']);
            } else {
                $user->setRole('student'); // Default role is student
            }
            
            if ($this->userDAO->create($user)) {
                // Auto login after registration
                $newUser = $this->userDAO->login($_POST['email'], $_POST['password']);
                
                if ($newUser) {
                    // Set session variables
                    $_SESSION['user_id'] = $newUser->getId();
                    $_SESSION['username'] = $newUser->getUsername();
                    $_SESSION['user_email'] = $newUser->getEmail();
                    $_SESSION['user_role'] = $newUser->getRole();
                    
                    header('Location: index.php?action=index');
                    exit;
                }
            } else {
                $error = "Failed to register user";
                include __DIR__ . '/../view/user/register.php';
            }
        } else {
            include __DIR__ . '/../view/user/register.php';
        }
    }
    
    // Logout method
    public function logout() {
        // Unset all session variables
        $_SESSION = array();
        
        // Destroy the session
        session_destroy();
        
        // Redirect to login page
        header('Location: index.php?action=login');
        exit;
    }
    
    // Dashboard method - redirects to appropriate dashboard based on user role
    public function dashboard() {
        // Check if user is logged in
        if (!isset($_SESSION['user_id'])) {
            header('Location: index.php?action=login');
            exit;
        }
        
        // Redirect to appropriate dashboard based on role
        switch ($_SESSION['user_role']) {
            case 'admin':
                include __DIR__ . '/../view/dashboard/admin_dashboard.php';
                break;
            case 'teacher':
                include __DIR__ . '/../view/dashboard/teacher_dashboard.php';
                break;
            case 'student':
                include __DIR__ . '/../view/dashboard/student_dashboard.php';
                break;
            default:
                // Fallback to student dashboard
                include __DIR__ . '/../view/dashboard/student_dashboard.php';
                break;
        }
    }
    
    // Profile method - allows users to update their information
    public function profile() {
        // Check if user is logged in
        if (!isset($_SESSION['user_id'])) {
            header('Location: index.php?action=login');
            exit;
        }
        
        // Get current user
        $user = $this->userDAO->readOne($_SESSION['user_id']);
        
        if (!$user) {
            header('Location: index.php?action=logout');
            exit;
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Update basic info
            $user->setUsername($_POST['username']);
            $user->setEmail($_POST['email']);
            
            $success = false;
            $error = null;
            
            // Check if password change is requested
            if (!empty($_POST['current_password']) && !empty($_POST['new_password']) && !empty($_POST['confirm_password'])) {
                // Verify current password
                $currentUser = $this->userDAO->login($user->getEmail(), $_POST['current_password']);
                
                if (!$currentUser) {
                    $error = "Mật khẩu hiện tại không đúng";
                } else if ($_POST['new_password'] !== $_POST['confirm_password']) {
                    $error = "Mật khẩu mới và xác nhận mật khẩu không khớp";
                } else {
                    // Update password
                    $user->setPassword($_POST['new_password']);
                    if ($this->userDAO->updatePassword($user)) {
                        $success = true;
                    } else {
                        $error = "Không thể cập nhật mật khẩu";
                    }
                }
            }
            
            // Update user info
            if ($this->userDAO->update($user)) {
                // Update session variables
                $_SESSION['username'] = $user->getUsername();
                $_SESSION['user_email'] = $user->getEmail();
                
                if (!$error) {
                    $success = true;
                }
            } else if (!$error) {
                $error = "Không thể cập nhật thông tin người dùng";
            }
            
            if ($success) {
                $success = "Thông tin đã được cập nhật thành công";
            }
        }
        
        include __DIR__ . '/../view/user/profile.php';
    }
}
?>