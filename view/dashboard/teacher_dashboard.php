<?php
ob_start();


?>

<div class="card">
    <div class="card-header">
        <h2>Giảng viên Dashboard</h2>
    </div>
    <div class="card-body">
        <div class="alert alert-info">
            <h4>Chào mừng, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h4>
            <p>Bạn đã đăng nhập với quyền Giảng viên.</p>
        </div>
        
        <div class="row mt-4">
            <div class="col-md-6 mb-3">
                <div class="card bg-primary text-white">
                    <div class="card-body">
                        <h5 class="card-title">Quản lý lớp học</h5>
                        <p class="card-text">Xem và quản lý các lớp học của bạn</p>
                        <a href="index.php?controller=classroom&action=index" class="btn btn-light">Truy cập</a>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6 mb-3">
                <div class="card bg-info text-white">
                    <div class="card-body">
                        <h5 class="card-title">Thông tin cá nhân</h5>
                        <p class="card-text">Cập nhật thông tin tài khoản của bạn</p>
                        <a href="index.php?action=profile" class="btn btn-light">Truy cập</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
$content = ob_get_clean();
include __DIR__ . '/../../view/layout/main.php';
?>
