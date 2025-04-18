<?php
ob_start();
?>
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h2>Quản lý lớp học</h2>
        <a href="index.php?controller=classroom&action=create" class="btn btn-primary">Tạo lớp học mới</a>
    </div>
    <div class="card-body">
        <?php if (empty($classrooms)): ?>
            <div class="alert alert-info">Bạn chưa có lớp học nào.</div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Tên lớp</th>
                            <th>Mô tả</th>
                            <th>Ngày tạo</th>
                            <th>Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($classrooms as $classroom): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($classroom->getName()); ?></td>
                                <td><?php echo htmlspecialchars($classroom->getDescription()); ?></td>
                                <td><?php echo htmlspecialchars($classroom->getCreatedAt()); ?></td>
                                <td>
                                    <a href="index.php?controller=classroom&action=view&id=<?php echo $classroom->getId(); ?>" class="btn btn-info btn-sm">Xem</a>
                                    <a href="index.php?controller=classroom&action=edit&id=<?php echo $classroom->getId(); ?>" class="btn btn-warning btn-sm">Sửa</a>
                                    <a href="index.php?controller=classroom&action=delete&id=<?php echo $classroom->getId(); ?>" class="btn btn-danger btn-sm" onclick="return confirm('Bạn có chắc chắn muốn xóa lớp học này?')">Xóa</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>
<?php
$content = ob_get_clean();
include __DIR__ . '/../../view/layout/main.php';
?>