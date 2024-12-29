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
    $id = intval($_GET['id']); // Ensure the ID is an integer

    // Fetch user data
    $stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
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
    $gender = $_POST['gender'];
    $date_of_birth = $_POST['date_of_birth'];
    $role = $_POST['role'];

    // Update user information
    $stmt = $conn->prepare("UPDATE users SET name = ?, email = ?, gender = ?, date_of_birth = ?, role = ? WHERE id = ?");
    $stmt->bind_param("sssssi", $name, $email, $gender, $date_of_birth, $role, $id);

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
            <label for="gender" class="form-label">Gender</label>
            <select id="gender" name="gender" class="form-select" required>
                <option value="Male" <?= $user['gender'] === 'Male' ? 'selected' : ''; ?>>Male</option>
                <option value="Female" <?= $user['gender'] === 'Female' ? 'selected' : ''; ?>>Female</option>
                <option value="Other" <?= $user['gender'] === 'Other' ? 'selected' : ''; ?>>Other</option>
            </select>
        </div>
        <div class="mb-3">
            <label for="date_of_birth" class="form-label">Date of Birth</label>
            <input type="date" id="date_of_birth" name="date_of_birth" class="form-control" value="<?= htmlspecialchars($user['date_of_birth']); ?>" required>
        </div>
        <div class="mb-3">
            <label for="role" class="form-label">Role</label>
            <select name="role" id="role" class="form-select">
                <option value="standard" <?= $user['role'] === 'user' ? 'selected' : ''; ?>>User</option>
                <option value="admin" <?= $user['role'] === 'admin' ? 'selected' : ''; ?>>Admin</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Update User</button>
        <a href="viewuser.php" class="btn btn-secondary">Cancel</a>
    </form>
</div>

<?php include 'templates/footer.php'; ?>
