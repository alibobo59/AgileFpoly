<?php
ob_start();
?>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h2>Edit User</h2>
        <a href="index.php?action=index" class="btn btn-secondary">Back to List</a>
    </div>
    <div class="card-body">
        <form action="index.php?action=edit&id=<?php echo $user->getId(); ?>" method="POST">
            <div class="form-group">
                <label for="username">Usernameád</label>
                <input type="text" class="form-control" id="username" name="username" value="<?php echo htmlspecialchars($user->getUsername()); ?>" required>
            </div>
            
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($user->getEmail()); ?>" required>
            </div>
            
            <button type="submit" class="btn btn-primary">Update User</button>
        </form>
    </div>
</div>
<?php
$content = ob_get_clean();
include __DIR__ . '/../../view/layout/main.php';
?>