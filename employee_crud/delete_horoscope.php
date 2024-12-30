<?php
session_start();
include 'config/db.php';
include 'templates/header.php';

// Ensure the user is an admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.php");
    exit();
}

// Get type and id from the URL
$type = $_GET['type'] ?? '';
$id = $_GET['id'] ?? '';

if (!in_array($type, ['daily', 'monthly']) || !$id) {
    header("Location: index.php");
    exit();
}

// Set table name based on type
$table = $type === 'daily' ? 'daily_horoscopes' : 'monthly_horoscopes';

// Delete the horoscope
$stmt = $conn->prepare("DELETE FROM $table WHERE id = ?");
$stmt->bind_param('i', $id);

if ($stmt->execute()) {
    header("Location: add_horoscope.php");
    exit();
} else {
    echo "<p>Failed to delete horoscope.</p>";
}
?>
