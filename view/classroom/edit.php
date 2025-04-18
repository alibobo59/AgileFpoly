<?php
ob_start();
?>
<div class="card">
    <div class="card-header">
        <h2>Chỉnh sửa lớp học</h2>
    </div>
    <div class="card-body">
        <form action="index.php?controller=classroom&action=edit&id=<?php echo $classroom->getId(); ?>" method="POST">
            <div class="form-group">
                <label for="name">Tên lớp học</label>
                <input type="text" class="form-control" id="name" name="name" value="<?php echo htmlspecialchars($classroom->getName()); ?>" required>
            </div>
            
            <div class="form-group">
                <label for="description">Mô tả</label>
                <textarea class="form-control" id="description" name="description" rows="3"><?php echo htmlspecialchars($classroom->getDescription()); ?></textarea>
            </div>
            
            <div class="mt-3">
                <button type="submit" class="btn btn-primary">Cập nhật</button>
                <a href="index.php?controller=classroom&action=index" class="btn btn-secondary">Hủy</a>
            </div>
        </form>
    </div>
</div>
<?php
$content = ob_get_clean();
include __DIR__ . '/../../view/layout/main.php';
?>