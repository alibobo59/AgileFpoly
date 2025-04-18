<?php
ob_start();


?>

<div class="card">
    <div class="card-header">
        <h2>Sinh viên Dashboard</h2>
    </div>
    <div class="card-body">
        <div class="alert alert-info">
            <h4>Chào mừng, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h4>
            <p>Bạn đã đăng nhập với quyền Sinh viên.</p>
        </div>
        
        <div class="row mt-4">
            <div class="col-md-6 mb-3">
                <div class="card bg-primary text-white">
                    <div class="card-body">
                        <h5 class="card-title">Lớp học của tôi</h5>
                        <p class="card-text">Xem các lớp học và bài tập</p>
                        <a href="#" class="btn btn-light">Truy cập</a>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6 mb-3">
                <div class="card bg-info text-white">
                    <div class="card-body">
                        <h5 class="card-title">Thông tin cá nhân</h5>
                        <p class="card-text">Cập nhật thông tin tài khoản của bạn</p>
                        <a href="index.php?action=profile&controller=user" class="btn btn-light">Truy cập</a>
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
