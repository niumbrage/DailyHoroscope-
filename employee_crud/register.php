<?php
session_start();
include 'config/db.php';
include 'templates/header.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $gender = mysqli_real_escape_string($conn, $_POST['gender']);
    $date_of_birth = mysqli_real_escape_string($conn, $_POST['date_of_birth']); // Properly escaped
    $role = mysqli_real_escape_string($conn, $_POST['role']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Secure password hash

    // Insert user details into the database
    $query = "INSERT INTO users (name, email, gender, date_of_birth, role, password) 
              VALUES ('$name', '$email', '$gender', '$date_of_birth', '$role', '$password')";

    if ($conn->query($query)) {
        echo "<script>alert('Registration successful!'); window.location='login.php';</script>";
    } else {
        echo "<script>alert('Error: " . htmlspecialchars($conn->error) . "');</script>";
    }
}
?>

<div class="container mt-5">
    <h2>Register</h2>
    <form action="register.php" method="POST">
        <div class="mb-3">
            <label for="name" class="form-label">Name</label>
            <input type="text" id="name" name="name" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" id="email" name="email" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input type="password" id="password" name="password" class="form-control" required>
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
            <select name="role" id="role" class="form-select">
                <option value="admin">Admin</option>
                <option value="user">User</option>
            </select>
        </div>
        <div class="d-flex justify-content-between">
        <a href="login.php" class="btn btn-primary">Back to Login</a>
        <button type="submit" class="btn btn-success">Register</button>
        </div>
    </form>
</div>

<?php include 'templates/footer.php'; ?>
