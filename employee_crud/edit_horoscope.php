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

// Fetch the current horoscope details
$stmt = $conn->prepare("SELECT * FROM $table WHERE id = ?");
$stmt->bind_param('i', $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "<p>Horoscope not found.</p>";
    exit();
}

$horoscope = $result->fetch_assoc();

// Update the horoscope
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $zodiac_id = $_POST['zodiac_id'];
    $value = $type === 'daily' ? $_POST['date'] : $_POST['month'];
    $text = $_POST['horoscope'];

    $update_stmt = $conn->prepare("UPDATE $table SET zodiac_id = ?, " . ($type === 'daily' ? 'date' : 'month') . " = ?, horoscope = ? WHERE id = ?");
    $update_stmt->bind_param('issi', $zodiac_id, $value, $text, $id);

    if ($update_stmt->execute()) {
        header("Location: index.php");
        exit();
    } else {
        echo "<p>Failed to update horoscope.</p>";
    }
}
?>

<div class="container mt-5">
    <h1>Edit <?= ucfirst($type); ?> Horoscope</h1>
    <form method="POST">
        <div class="mb-3">
            <label for="zodiac_id" class="form-label">Zodiac Sign</label>
            <select name="zodiac_id" id="zodiac_id" class="form-select" required>
                <?php
                $zodiac_stmt = $conn->prepare("SELECT id, name FROM zodiac_signs");
                $zodiac_stmt->execute();
                $zodiac_signs = $zodiac_stmt->get_result();

                while ($zodiac = $zodiac_signs->fetch_assoc()):
                ?>
                    <option value="<?= $zodiac['id']; ?>" <?= $zodiac['id'] == $horoscope['zodiac_id'] ? 'selected' : ''; ?>>
                        <?= htmlspecialchars($zodiac['name']); ?>
                    </option>
                <?php endwhile; ?>
            </select>
        </div>

        <div class="mb-3">
            <label for="<?= $type === 'daily' ? 'date' : 'month'; ?>" class="form-label"><?= $type === 'daily' ? 'Date' : 'Month'; ?></label>
            <input type="<?= $type === 'daily' ? 'date' : 'text'; ?>" name="<?= $type === 'daily' ? 'date' : 'month'; ?>" id="<?= $type === 'daily' ? 'date' : 'month'; ?>" class="form-control" value="<?= htmlspecialchars($type === 'daily' ? $horoscope['date'] : $horoscope['month']); ?>" required>
        </div>

        <div class="mb-3">
            <label for="horoscope" class="form-label">Horoscope</label>
            <textarea name="horoscope" id="horoscope" class="form-control" rows="5" required><?= htmlspecialchars($horoscope['horoscope']); ?></textarea>
        </div>

        <button type="submit" class="btn btn-primary">Update Horoscope</button>
        <a href="index.php" class="btn btn-secondary">Cancel</a>
    </form>
</div>

<?php include 'templates/footer.php'; ?>
