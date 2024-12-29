<?php
session_start();
include 'config/db.php';
include 'templates/header.php';

// Check if user is an admin
if ($_SESSION['role'] !== 'admin') {
    header("Location: index.php");
    exit();
}

// Fetch zodiac signs
$stmt = $conn->prepare("SELECT id, name FROM zodiac_signs");
$stmt->execute();
$zodiac_signs = $stmt->get_result();

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $zodiac_id = $_POST['zodiac_id'];
    $horoscope = $_POST['horoscope'];

    if (isset($_POST['type']) && $_POST['type'] === 'daily') {
        $date = $_POST['date'];
        $stmt = $conn->prepare("INSERT INTO daily_horoscopes (zodiac_id, horoscope, date) VALUES (?, ?, ?)");
        $stmt->bind_param("iss", $zodiac_id, $horoscope, $date);
    } elseif (isset($_POST['type']) && $_POST['type'] === 'monthly') {
        $month = $_POST['month'];
        $stmt = $conn->prepare("INSERT INTO monthly_horoscopes (zodiac_id, horoscope, month) VALUES (?, ?, ?)");
        $stmt->bind_param("iss", $zodiac_id, $horoscope, $month);
    }

    if ($stmt->execute()) {
        $message = "Horoscope added successfully!";
    } else {
        $message = "Failed to add horoscope: " . $stmt->error;
    }
}
?>

<div class="container mt-5">
    <h1>Add Horoscope</h1>
    <?php if (!empty($message)): ?>
        <div class="alert alert-info"><?= $message; ?></div>
    <?php endif; ?>

    <form method="POST">
        <div class="mb-3">
            <label for="zodiac_id" class="form-label">Zodiac Sign</label>
            <select name="zodiac_id" id="zodiac_id" class="form-control" required>
                <?php while ($row = $zodiac_signs->fetch_assoc()): ?>
                    <option value="<?= $row['id']; ?>"><?= htmlspecialchars($row['name']); ?></option>
                <?php endwhile; ?>
            </select>
        </div>
        <div class="mb-3">
            <label for="horoscope" class="form-label">Horoscope</label>
            <textarea name="horoscope" id="horoscope" class="form-control" rows="4" required></textarea>
        </div>
        <div class="mb-3">
            <label class="form-label">Type</label>
            <div>
                <input type="radio" name="type" value="daily" required> Daily
                <input type="radio" name="type" value="monthly" required> Monthly
            </div>
        </div>
        <div id="daily-fields" class="mb-3" style="display: none;">
            <label for="date" class="form-label">Date</label>
            <input type="date" name="date" id="date" class="form-control">
        </div>
        <div id="monthly-fields" class="mb-3" style="display: none;">
            <label for="month" class="form-label">Month</label>
            <input type="month" name="month" id="month" class="form-control">
        </div>
        <button type="submit" class="btn btn-primary">Add Horoscope</button>
    </form>
    <div class="d-flex justify-content-between">
        <a href="index.php" class="btn btn-secondary mt-3">Back to Dashboard</a>
        <a href="edit_horoscope.php" class="btn btn-warning mt-3">Edit Horoscope</a>
        <a href="delete_horoscope.php" class="btn btn-danger mt-3">Delete Horoscope</a>
    </div>
</div>

<script>
document.querySelectorAll('input[name="type"]').forEach(input => {
    input.addEventListener('change', () => {
        document.getElementById('daily-fields').style.display = input.value === 'daily' ? 'block' : 'none';
        document.getElementById('monthly-fields').style.display = input.value === 'monthly' ? 'block' : 'none';
    });
});
</script>

<?php include 'templates/footer.php'; ?>
