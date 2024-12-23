<?php
session_start();
include 'config/db.php';
include 'templates/header.php';

if ($_SESSION['role'] !== 'admin') {
    header("Location: index.php");
    exit;
}

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $query = "DELETE FROM employee WHERE id = $id";
    if ($conn->query($query)) {
        echo "<script>alert('User deleted successfully'); window.location='viewuser.php';</script>";
    } else {
        echo "<script>alert('Error: " . $conn->error . "');</script>";
    }
}
?>

<div class="container mt-5">
    <h1>User Deletion</h1>
    <p>User has been deleted. <a href="viewuser.php">Go back to user list</a>.</p>
</div>

<?php include 'templates/footer.php'; ?>
