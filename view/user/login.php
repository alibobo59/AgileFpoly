<?php
ob_start();
?>
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h2>Login</h2>
        <a href="index.php?action=register" class="btn btn-secondary">Register</a>
    </div>
    <div class="card-body">
        <?php if (isset($error)): ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <form action="index.php?action=login" method="POST">
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
            
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            
            <button type="submit" class="btn btn-primary">Login</button>
        </form>
        
        <div class="mt-3">
            <p>Don't have an account? <a href="index.php?action=register">Register here</a></p>
        </div>
    </div>
</div>
<?php
$content = ob_get_clean();
include __DIR__ . '/../../view/layout/main.php';
?>