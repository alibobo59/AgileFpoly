<?php
ob_start();
?>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h2>Thông tin cá nhân</h2>
        <?php if ($_SESSION['user_role'] === 'admin'): ?>
            <a href="index.php?action=dashboard" class="btn btn-secondary">Quay lại Dashboard</a>
        <?php elseif ($_SESSION['user_role'] === 'teacher'): ?>
            <a href="index.php?action=dashboard" class="btn btn-secondary">Quay lại Dashboard</a>
        <?php elseif ($_SESSION['user_role'] === 'student'): ?>
            <a href="index.php?action=dashboard" class="btn btn-secondary">Quay lại Dashboard</a>
        <?php endif; ?>
    </div>
    <div class="card-body">
        <?php if (isset($success)): ?>
            <div class="alert alert-success"><?php echo $success; ?></div>
        <?php endif; ?>
        
        <?php if (isset($error)): ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <form action="index.php?action=profile" method="POST">
            <div class="form-group">
                <label for="username">Tên người dùng</label>
                <input type="text" class="form-control" id="username" name="username" value="<?php echo htmlspecialchars($user->getUsername()); ?>" required>
            </div>
            
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($user->getEmail()); ?>" required>
            </div>
            
            <div class="form-group">
                <label for="role">Vai trò</label>
                <input type="text" class="form-control" value="<?php echo htmlspecialchars($user->getRole()); ?>" readonly>
            </div>
            
            <hr>
            
            <h4>Đổi mật khẩu</h4>
            <p class="text-muted">Để trống nếu bạn không muốn thay đổi mật khẩu</p>
            
            <div class="form-group">
                <label for="current_password">Mật khẩu hiện tại</label>
                <input type="password" class="form-control" id="current_password" name="current_password">
            </div>
            
            <div class="form-group">
                <label for="new_password">Mật khẩu mới</label>
                <input type="password" class="form-control" id="new_password" name="new_password">
            </div>
            
            <div class="form-group">
                <label for="confirm_password">Xác nhận mật khẩu mới</label>
                <input type="password" class="form-control" id="confirm_password" name="confirm_password">
            </div>
            
            <button type="submit" class="btn btn-primary">Cập nhật thông tin</button>
        </form>
    </div>
</div>
<?php
$content = ob_get_clean();
include __DIR__ . '/../../view/layout/main.php';
?>