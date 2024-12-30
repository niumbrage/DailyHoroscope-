<?php
session_start();
include 'config/db.php';
include 'templates/header.php';

// Check if user is an admin
if ($_SESSION['role'] !== 'admin') {
    header("Location: index.php");
    exit();
}

// Fetch all daily and monthly horoscopes
$daily_horoscopes = $conn->query("SELECT dh.id, zs.name AS zodiac_name, dh.horoscope, dh.date 
                                  FROM daily_horoscopes dh 
                                  JOIN zodiac_signs zs ON dh.zodiac_id = zs.id");
$monthly_horoscopes = $conn->query("SELECT mh.id, zs.name AS zodiac_name, mh.horoscope, mh.month 
                                    FROM monthly_horoscopes mh 
                                    JOIN zodiac_signs zs ON mh.zodiac_id = zs.id");

// Handle form submissions (for adding new horoscopes)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $zodiac_id = $_POST['zodiac_id'];
    $horoscope = $_POST['horoscope'];

    if ($_POST['type'] === 'daily') {
        $date = $_POST['date'];
        $stmt = $conn->prepare("INSERT INTO daily_horoscopes (zodiac_id, horoscope, date) VALUES (?, ?, ?)");
        $stmt->bind_param("iss", $zodiac_id, $horoscope, $date);
    } elseif ($_POST['type'] === 'monthly') {
        $month = $_POST['month'] . '-01'; // Convert YYYY-MM to YYYY-MM-DD
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
    <h1>Manage Horoscopes</h1>
    <?php if (!empty($message)): ?>
        <div class="alert alert-info"><?= $message; ?></div>
    <?php endif; ?>

    <!-- Form for Adding Horoscope -->
    <form method="POST" class="mb-5">
        <div class="mb-3">
            <label for="zodiac_id" class="form-label">Zodiac Sign</label>
            <select name="zodiac_id" id="zodiac_id" class="form-control" required>
                <?php
                $zodiac_stmt = $conn->prepare("SELECT id, name FROM zodiac_signs");
                $zodiac_stmt->execute();
                $zodiac_signs = $zodiac_stmt->get_result();

                while ($row = $zodiac_signs->fetch_assoc()):
                ?>
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
            <input type="month" name="month" id="month" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary">Add Horoscope</button>
    </form>

    <!-- List of Daily Horoscopes -->
    <h2>Daily Horoscopes</h2>
    <ul class="list-group mb-5">
        <?php while ($row = $daily_horoscopes->fetch_assoc()): ?>
            <li class="list-group-item d-flex justify-content-between align-items-center">
                <?= htmlspecialchars($row['zodiac_name'] . " - " . $row['date'] . ": " . $row['horoscope']); ?>
                <div>
                    <a href="edit_horoscope.php?type=daily&id=<?= $row['id']; ?>" class="btn btn-warning btn-sm">Edit</a>
                    <a href="delete_horoscope.php?type=daily&id=<?= $row['id']; ?>" class="btn btn-danger btn-sm">Delete</a>
                </div>
            </li>
        <?php endwhile; ?>
    </ul>

    <!-- List of Monthly Horoscopes -->
    <h2>Monthly Horoscopes</h2>
    <ul class="list-group">
        <?php while ($row = $monthly_horoscopes->fetch_assoc()): ?>
            <li class="list-group-item d-flex justify-content-between align-items-center">
                <?= htmlspecialchars($row['zodiac_name'] . " - " . date('Y-m', strtotime($row['month'])) . ": " . $row['horoscope']); ?>
                <div>
                    <a href="edit_horoscope.php?type=monthly&id=<?= $row['id']; ?>" class="btn btn-warning btn-sm">Edit</a>
                    <a href="delete_horoscope.php?type=monthly&id=<?= $row['id']; ?>" class="btn btn-danger btn-sm">Delete</a>
                </div>
            </li>
        <?php endwhile; ?>
    </ul>
</div>

<script>
document.querySelectorAll('input[name="type"]').forEach(input => {
    input.addEventListener('change', () => {
        const dailyFields = document.getElementById('daily-fields');
        const monthlyFields = document.getElementById('monthly-fields');
        
        if (input.value === 'daily') {
            dailyFields.style.display = 'block';
            monthlyFields.style.display = 'none';
            document.getElementById('month').disabled = true; // Disable month input when not shown
        } else {
            dailyFields.style.display = 'none';
            monthlyFields.style.display = 'block';
            document.getElementById('month').disabled = false; // Enable month input when shown
        }
    });
});
</script>

<?php include 'templates/footer.php'; ?>
