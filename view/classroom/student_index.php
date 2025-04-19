<?php
ob_start();
?>
<div class="card">
    <div class="card-header">
        <h2>My Classes</h2>
    </div>
    <div class="card-body">
        <?php if (empty($classrooms)): ?>
            <div class="alert alert-info">You are not enrolled in any classes</div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Class Name</th>
                            <th>Description</th>
                            <th>Teacher</th>
                            <th>Enrollment Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($classrooms as $class): ?>
                        <tr>
                            <td>
                                <a href="index.php?controller=classroom&action=view&id=<?= $class->getId() ?>">
                                    <?= htmlspecialchars($class->getName()) ?>
                                </a>
                            </td>
                            <td><?= htmlspecialchars($class->getDescription()) ?></td>
                            <td><?= htmlspecialchars($class->getTeacherName()) ?></td>
                            <td><?= $class->getEnrollmentDate() ?></td>
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