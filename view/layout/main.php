<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Management System</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body { padding-top: 20px; }
        .container { max-width: 960px; }
        .action-buttons { white-space: nowrap; }
    </style>
</head>
<body>
    <div class="container">
        <header class="mb-4">
            <nav class="navbar navbar-expand-lg navbar-light bg-light">
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav mr-auto">
                        <li class="nav-item">
                            <a class="nav-link" href="index.php?action=index">Home</a>
                        </li>
                    </ul>
                    <ul class="navbar-nav">
                        <?php if (isset($_SESSION['user_id'])): ?>
                            <li class="nav-item">
                                <span class="nav-link">Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?> (<?php echo $_SESSION['user_role']; ?>)</span>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="index.php?action=logout">Logout</a>
                            </li>
                        <?php else: ?>
                            <li class="nav-item">
                                <a class="nav-link" href="index.php?action=login">Login</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="index.php?action=register">Register</a>
                            </li>
                        <?php endif; ?>
                    </ul>
                </div>
            </nav>
            <hr>
        </header>
        
        <main>
            <?php if (isset($error)): ?>
                <div class="alert alert-danger"><?php echo $error; ?></div>
            <?php endif; ?>
            
            <?php
if (is_string($content)) {
    // If content is a file path, include it
    if (file_exists($content)) {
        ob_start();
        include $content;
        echo ob_get_clean();
    } else {
        echo $content;
    }
} else {
    echo $content;
}
?>
        </main>
        
        <footer class="mt-5 text-center text-muted">
            <hr>
            <p>&copy; <?php echo date('Y'); ?> User Management System</p>
        </footer>
    </div>
    
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>