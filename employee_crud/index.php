<?php
session_start();
include 'config/db.php';
include 'templates/header.php';

// Redirect to login page if not logged in
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}
?>

<div class="container mt-5">
    <h1>Welcome to the Horoscope Website</h1>
    <p>Explore your horoscope and zodiac sign details.</p>
</div>

<?php include 'templates/footer.php'; ?>
