<?php
$content = __FILE__;
include __DIR__ . '/../../view/layout/main.php';
?>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h2>Create New User</h2>
        <a href="index.php?action=index" class="btn btn-secondary">Back to List</a>
    </div>
    <div class="card-body">
        <form action="index.php?action=create" method="POST">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" class="form-control" id="username" name="username" required>
            </div>
            
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
            
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            
            <div class="form-group">
                <label for="role">Role</label>
                <select class="form-control" id="role" name="role" required>
                    <option value="student">Sinh viên</option>
                    <option value="teacher">Giảng viên</option>

                </select>
            </div>
            
            <button type="submit" class="btn btn-primary">Create User</button>
        </form>
    </div>
</div>