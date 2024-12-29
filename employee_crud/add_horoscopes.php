<?php
session_start();
include 'config/db.php';
include 'templates/header.php';

// Redirect if not logged in or not an admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.php");
    exit();
}

// Handle form submission for adding a new horoscope
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $zodiac_id = $_POST['zodiac_id'];
    $daily_horoscope = $_POST['daily_horoscope'];
    $monthly_horoscope = $_POST['monthly_horoscope'];
    $created_at = date('Y-m-d'); // Use today's date for created_at

    // Insert new horoscope into the database
    $stmt = $conn->prepare("INSERT INTO horoscopes (zodiac_id, daily_horoscope, monthly_horoscope, created_at) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("isss", $zodiac_id, $daily_horoscope, $monthly_horoscope, $created_at);

    if ($stmt->execute()) {
        $success_message = "New horoscope added successfully!";
    } else {
        $error_message = "Failed to add horoscope: " . $conn->error;
    }
}

// Fetch all zodiac signs for the dropdown
$stmt = $conn->prepare("SELECT id, name FROM zodiac_signs");
$stmt->execute();
$zodiac_signs = $stmt->get_result();
?>

<div class="container mt-5">
    <h1>Add New Horoscope</h1>
    <div class="text-end mb-3">
        <?= date('Y-m-d H:i:s'); ?> <!-- Display current date and time -->
    </div>

    <?php if (isset($success_message)): ?>
        <div class="alert alert-success"><?= htmlspecialchars($success_message); ?></div>
    <?php elseif (isset($error_message)): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error_message); ?></div>
    <?php endif; ?>

    <form method="POST">
        <div class="mb-3">
            <label for="zodiac_id" class="form-label">Zodiac Sign</label>
            <select id="zodiac_id" name="zodiac_id" class="form-select" required>
                <option value="" disabled selected>Select a zodiac sign</option>
                <?php while ($zodiac = $zodiac_signs->fetch_assoc()): ?>
                    <option value="<?= $zodiac['id']; ?>"><?= htmlspecialchars($zodiac['name']); ?></option>
                <?php endwhile; ?>
            </select>
        </div>
        <div class="mb-3">
            <label for="daily_horoscope" class="form-label">Daily Horoscope</label>
            <textarea id="daily_horoscope" name="daily_horoscope" class="form-control" rows="3" required></textarea>
        </div>
        <div class="mb-3">
            <label for="monthly_horoscope" class="form-label">Monthly Horoscope</label>
            <textarea id="monthly_horoscope" name="monthly_horoscope" class="form-control" rows="5" required></textarea>
        </div>
        <div class="d-flex justify-content-between">
            <a href="index.php" class="btn btn-primary">Back to Dashboard</a>
            <button type="submit" class="btn btn-success">Add Horoscope</button>
        </div>
    </form>
</div>

<?php include 'templates/footer.php'; ?>
