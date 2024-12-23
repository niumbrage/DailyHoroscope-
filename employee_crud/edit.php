<?php
session_start();
include 'templates/header.php';
require 'config/db.php';

// Check if the user is logged in as admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.php");
    exit();
}

// Check if an ID is provided
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Fetch user data
    $stmt = $conn->prepare("SELECT * FROM employee WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if (!$user) {
        echo "<div class='alert alert-danger'>User not found!</div>";
        exit();
    }
} else {
    header("Location: viewuser.php");
    exit();
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $emp_desc = $_POST['emp_desc'];
    $role = $_POST['role'];

    // Update user information
    $stmt = $conn->prepare("UPDATE employee SET name = ?, email = ?, emp_desc = ?, role = ? WHERE id = ?");
    $stmt->bind_param("ssssi", $name, $email, $emp_desc, $role, $id);

    if ($stmt->execute()) {
        echo "<script>alert('User updated successfully'); window.location='viewuser.php';</script>";
    } else {
        echo "<div class='alert alert-danger'>Error updating user!</div>";
    }
}
?>

<div class="container mt-5">
    <h1 class="mb-4">Edit User</h1>
    <form method="POST">
        <div class="mb-3">
            <label for="name" class="form-label">Name</label>
            <input type="text" name="name" id="name" class="form-control" value="<?= htmlspecialchars($user['name']); ?>" required>
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" name="email" id="email" class="form-control" value="<?= htmlspecialchars($user['email']); ?>" required>
        </div>
        <div class="mb-3">
            <label for="emp_desc" class="form-label">Description</label>
            <textarea name="emp_desc" id="emp_desc" class="form-control" rows="4"><?= htmlspecialchars($user['emp_desc']); ?></textarea>
        </div>
        <div class="mb-3">
            <label for="role" class="form-label">Role</label>
            <select name="role" id="role" class="form-select">
                <option value="standard" <?= $user['role'] === 'standard' ? 'selected' : ''; ?>>Standard</option>
                <option value="admin" <?= $user['role'] === 'admin' ? 'selected' : ''; ?>>Admin</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Update User</button>
        <a href="viewuser.php" class="btn btn-secondary">Cancel</a>
    </form>
</div>

<?php include 'templates/footer.php'; ?>
