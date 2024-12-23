<?php
session_start();
include 'templates/header.php';
?>

<div class="container mt-5">
    <?php if (!isset($_SESSION['role'])): ?>
        <h1>Welcome to My Application</h1>
        <div class="mt-3">
            <a href="login.php" class="btn btn-primary">Login</a>
            <a href="register.php" class="btn btn-success">Register</a>
        </div>
    <?php else: ?>
        <h1>Welcome, <?= $_SESSION['email']; ?></h1>
        <div class="mt-3">
            <?php if ($_SESSION['role'] === 'admin'): ?>
                <a href="viewuser.php" class="btn btn-primary">Manage Users</a>
                <a href="add.php" class="btn btn-success">Add New User</a>
            <?php else: ?>
                <a href="viewuser.php" class="btn btn-primary">View Users</a>
            <?php endif; ?>
        </div>
    <?php endif; ?>
</div>

<?php include 'templates/footer.php'; ?>