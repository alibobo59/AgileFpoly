<?php
ob_start();
?>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h2>Users List</h2>
        <a href="index.php?action=create" class="btn btn-primary">Add New User</a>
    </div>
    <div class="card-body">
        <?php if (empty($users)): ?>
            <div class="alert alert-info">No users found.</div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Username</th>
                            <th>Email</th>
                            <th>Created At</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($users as $user): ?>
                            <tr>
                                <td><?php echo $user->getId(); ?></td>
                                <td><?php echo htmlspecialchars($user->getUsername()); ?></td>
                                <td><?php echo htmlspecialchars($user->getEmail()); ?></td>
                                <td><?php echo $user->getCreatedAt(); ?></td>
                                <td class="action-buttons">
                                    <a href="index.php?action=view&id=<?php echo $user->getId(); ?>" class="btn btn-sm btn-info">View</a>
                                    <a href="index.php?action=edit&id=<?php echo $user->getId(); ?>" class="btn btn-sm btn-warning">Edit</a>
                                    <a href="javascript:void(0);" onclick="confirmDelete(<?php echo $user->getId(); ?>)" class="btn btn-sm btn-danger">Delete</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>

<script>
    function confirmDelete(id) {
        if (confirm('Are you sure you want to delete this user?')) {
            window.location.href = 'index.php?action=delete&id=' + id;
        }
    }
</script>
<?php
$content = ob_get_clean();
include __DIR__ . '/../../view/layout/main.php';
?>