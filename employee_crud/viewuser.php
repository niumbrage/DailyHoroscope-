<?php
session_start();
include 'config/db.php';
include 'templates/header.php';

if (!isset($_SESSION['role'])) {
    header("Location: index.php");
    exit();
}

// Fetch all users
$stmt = $conn->prepare("SELECT id, name, email, emp_desc, role FROM employee");
$stmt->execute();
$result = $stmt->get_result();
?>

<div class="container mt-5">
    <h1>User Management</h1>
    <div class="text-end mb-3">
        <?= date('Y-m-d H:i:s'); ?> <!-- Display current date and time -->
    </div>
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Description</th>
                <th>Role</th>
                <?php if ($_SESSION['role'] === 'admin'): ?>
                    <th>Actions</th>
                <?php endif; ?>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= $row['id']; ?></td>
                    <td><?= htmlspecialchars($row['name']); ?></td>
                    <td><?= htmlspecialchars($row['email']); ?></td>
                    <td><?= htmlspecialchars($row['emp_desc']); ?></td>
                    <td><?= $row['role']; ?></td>
                    <?php if ($_SESSION['role'] === 'admin'): ?>
                        <td>
                            <a href="edit.php?id=<?= $row['id']; ?>" class="btn btn-sm btn-warning">Edit</a>
                            <a href="delete.php?id=<?= $row['id']; ?>" class="btn btn-sm btn-danger" 
                               onclick="return confirm('Are you sure you want to delete this user?');">Delete</a>
                        </td>
                    <?php endif; ?>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
    <a href="index.php" class="btn btn-primary">Back to Dashboard</a>
</div>

<?php include 'templates/footer.php'; ?>
