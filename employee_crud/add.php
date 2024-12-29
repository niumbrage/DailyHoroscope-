<?php
session_start();
include 'config/db.php';
include 'templates/header.php';

// Check if the user is an admin
if ($_SESSION['role'] !== 'admin') {
    header("Location: index.php");
    exit;
}

// Handle form submission
if (isset($_POST['add'])) {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $gender = mysqli_real_escape_string($conn, $_POST['gender']);
    $date_of_birth = mysqli_real_escape_string($conn, $_POST['date_of_birth']);
    $role = mysqli_real_escape_string($conn, $_POST['role']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Securely hash the password

    // Insert data into database
    $query = "INSERT INTO users (name, email, gender, date_of_birth, role, password) 
              VALUES ('$name', '$email', '$gender', '$date_of_birth', '$role', '$password')";
    
    if ($conn->query($query)) {
        echo "<script>alert('User added successfully!'); window.location='viewuser.php';</script>";
    } else {
        echo "<script>alert('Error: " . htmlspecialchars($conn->error) . "');</script>";
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
            <label for="gender" class="form-label">Gender</label>
            <select id="gender" name="gender" class="form-select" required>
                <option value="Male">Male</option>
                <option value="Female">Female</option>
                <option value="Other">Other</option>
            </select>
        </div>
        <div class="mb-3">
            <label for="date_of_birth" class="form-label">Date of Birth</label>
            <input type="date" id="date_of_birth" name="date_of_birth" class="form-control" required>
        </div>
        <div class="mb-3">
    <label for="role" class="form-label">Role</label>
    <select name="role" id="role" class="form-select" required>
        <option value="admin">Admin</option>
        <option value="user">User</option>
    </select>
</div>

        <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input type="password" name="password" id="password" class="form-control" required>
        </div>
        <div class="d-flex justify-content-between">
            <a href="index.php" class="btn btn-primary">Back to Dashboard</a>
            <button type="submit" name="add" class="btn btn-success">Add User</button>
        </div>
    </form>
</div>

<?php include 'templates/footer.php'; ?>
