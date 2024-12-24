<?php
session_start();
include 'config/db.php';
include 'templates/header.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hash the password
    $gender = mysqli_real_escape_string($conn, $_POST['gender']);
    $date_of_birth = mysqli_real_escape_string($conn, $_POST['date_of_birth']);
    $role = 'user'; // Default role for all users is 'user' so no one can accidentally become admin

    $query = "INSERT INTO users (name, email, password, gender, date_of_birth, role) VALUES ('$name', '$email', '$password', '$gender', '$date_of_birth', '$role')";
    if ($conn->query($query)) {
        echo "<script>alert('Registration successful!'); window.location='login.php';</script>";
    } else {
        echo "<script>alert('Error: " . $conn->error . "');</script>";
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
        <button type="submit" class="btn btn-primary">Register</button>
        <a href="register.php" class="btn btn-link">Not a New User?</a>
    </form>
</div>

<?php include 'templates/footer.php'; ?>
