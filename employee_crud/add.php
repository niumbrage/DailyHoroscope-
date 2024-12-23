<?php
session_start();
include 'config/db.php';
include 'templates/header.php';

if ($_SESSION['role'] !== 'admin') {
    header("Location: index.php");
    exit;
}

if (isset($_POST['add'])) {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $emp_desc = mysqli_real_escape_string($conn, $_POST['emp_desc']);
    $role = mysqli_real_escape_string($conn, $_POST['role']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $query = "INSERT INTO employee (name, email, emp_desc, role, password) 
              VALUES ('$name', '$email', '$emp_desc', '$role', '$password')";
    if ($conn->query($query)) {
        echo "<script>alert('User added successfully'); window.location='viewuser.php';</script>";
    } else {
        echo "<script>alert('Error: " . $conn->error . "');</script>";
    }
}
?>

<div class="container mt-5">
    <h1>Add New User</h1>
    <form action="add.php" method="POST">
        <div class="mb-3">
            <label for="name" class="form-label">Name</label>
            <input type="text" name="name" id="name" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" name="email" id="email" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="emp_desc" class="form-label">Description</label>
            <textarea name="emp_desc" id="emp_desc" class="form-control" required></textarea>
        </div>
        <div class="mb-3">
            <label for="role" class="form-label">Role</label>
            <select name="role" id="role" class="form-select">
                <option value="admin">Admin</option>
                <option value="standard">Standard</option>
            </select>
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input type="password" name="password" id="password" class="form-control" required>
        </div>
        <button type="submit" name="add" class="btn btn-success">Add User</button>
    </form>
</div>

<?php include 'templates/footer.php'; ?>
