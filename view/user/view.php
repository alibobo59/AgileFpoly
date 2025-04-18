<?php
ob_start();
?>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h2>User Details</h2>
        <div>
            <a href="index.php?action=edit&id=<?php echo $user->getId(); ?>" class="btn btn-warning">Edit</a>
            <a href="index.php?action=index" class="btn btn-secondary">Back to List</a>
        </div>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <table class="table">
                    <tr>
                        <th>ID:</th>
                        <td><?php echo $user->getId(); ?></td>
                    </tr>
                    <tr>
                        <th>Username:</th>
                        <td><?php echo htmlspecialchars($user->getUsername()); ?></td>
                    </tr>
                    <tr>
                        <th>Email:</th>
                        <td><?php echo htmlspecialchars($user->getEmail()); ?></td>
                    </tr>
                    <tr>
                        <th>Created At:</th>
                        <td><?php echo $user->getCreatedAt(); ?></td>
                    </tr>
                </table>
            </div>
        </div>
        <div class="mt-3">
            <a href="javascript:void(0);" onclick="confirmDelete(<?php echo $user->getId(); ?>)" class="btn btn-danger">Delete User</a>
        </div>
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