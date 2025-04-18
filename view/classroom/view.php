<?php
ob_start();
?>
<div class="card">
    <div class="card-header">
        <h2><?php echo htmlspecialchars($classroom->getName()); ?></h2>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-12">
                <h4>Thông tin lớp học</h4>
                <p><strong>Mô tả:</strong> <?php echo htmlspecialchars($classroom->getDescription()); ?></p>
                <p><strong>Ngày tạo:</strong> <?php echo htmlspecialchars($classroom->getCreatedAt()); ?></p>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-md-12">
                <h4>Danh sách sinh viên</h4>
                <div class="mb-3">
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addStudentModal">
                        Thêm sinh viên
                    </button>
                </div>

                <?php if (empty($students)): ?>
                    <div class="alert alert-info">Chưa có sinh viên nào trong lớp học này.</div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Họ tên</th>
                                    <th>Email</th>
                                    <th>Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($students as $student): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($student['username']); ?></td>
                                        <td><?php echo htmlspecialchars($student['email']); ?></td>
                                        <td>
                                            <a href="index.php?controller=classroom&action=removeStudent&classroom_id=<?php echo $classroom->getId(); ?>&student_id=<?php echo $student['id']; ?>" 
                                               class="btn btn-danger btn-sm"
                                               onclick="return confirm('Bạn có chắc chắn muốn xóa sinh viên này khỏi lớp học?')">
                                                Xóa khỏi lớp
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Modal thêm sinh viên -->
<div class="modal fade" id="addStudentModal" tabindex="-1" aria-labelledby="addStudentModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addStudentModalLabel">Thêm sinh viên vào lớp</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="index.php?controller=classroom&action=addStudent" method="POST">
                <div class="modal-body">
                    <input type="hidden" name="classroom_id" value="<?php echo $classroom->getId(); ?>">
                    <div class="form-group">
                        <label for="student_id">Chọn sinh viên</label>
                        <select class="form-control" id="student_id" name="student_id" required>
                            <option value="">Chọn sinh viên...</option>
                            <!-- Thêm options cho sinh viên ở đây -->
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                    <button type="submit" class="btn btn-primary">Thêm sinh viên</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
include __DIR__ . '/../../view/layout/main.php';
?>