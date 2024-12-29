<?php
session_start();
include 'config/db.php';
include 'templates/header.php';

// Redirect to login page if not logged in
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}

// Fetch user details
$email = $_SESSION['email'];
$query = "SELECT name, date_of_birth FROM users WHERE email = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $email);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();

// Function to calculate zodiac sign
function getZodiacSign($date_of_birth) {
    $zodiac_signs = [
        ['Capricorn', '12-22', '01-19'],
        ['Aquarius', '01-20', '02-18'],
        ['Pisces', '02-19', '03-20'],
        ['Aries', '03-21', '04-19'],
        ['Taurus', '04-20', '05-20'],
        ['Gemini', '05-21', '06-20'],
        ['Cancer', '06-21', '07-22'],
        ['Leo', '07-23', '08-22'],
        ['Virgo', '08-23', '09-22'],
        ['Libra', '09-23', '10-22'],
        ['Scorpio', '10-23', '11-21'],
        ['Sagittarius', '11-22', '12-21']
    ];

    $birth_date = new DateTime($date_of_birth);

    foreach ($zodiac_signs as $sign) {
        $start = new DateTime($birth_date->format('Y') . '-' . $sign[1]);
        $end = new DateTime($birth_date->format('Y') . '-' . $sign[2]);

        // Handle year-end overlap for Capricorn
        if ($sign[0] === 'Capricorn' && $birth_date->format('m-d') >= '12-22') {
            $start->setDate($birth_date->format('Y'), 12, 22);
            $end->setDate($birth_date->format('Y') + 1, 1, 19);
        }

        if ($birth_date >= $start && $birth_date <= $end) {
            return $sign[0];
        }
    }

    return null;
}

// Determine zodiac sign
$zodiac_sign = getZodiacSign($user['date_of_birth']);

// Fetch zodiac sign details and horoscope
$query = "
    SELECT zodiac_signs.description, zodiac_signs.image, horoscopes.daily_horoscope, horoscopes.monthly_horoscope
    FROM zodiac_signs
    JOIN horoscopes ON zodiac_signs.id = horoscopes.zodiac_id
    WHERE zodiac_signs.name = ?
";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $zodiac_sign);
$stmt->execute();
$zodiac_details = $stmt->get_result()->fetch_assoc();
?>

<div class="custom-container">
    <h1>Welcome, <?= htmlspecialchars($user['name']); ?>!</h1>
</div>

<div class="parent-container mt-5">
    <div class="zodiac-container mt-5">
        <h2>Your Zodiac Sign: <?= $zodiac_sign; ?></h2>
        <?php if ($zodiac_details): ?>
            <div class="zodiac-details mt-4">
                <img src="<?= htmlspecialchars($zodiac_details['image']); ?>" alt="<?= htmlspecialchars($zodiac_sign); ?>" class="img-fluid" style="max-width: 200px;">
                <p><strong>Description:</strong> <?= htmlspecialchars($zodiac_details['description']); ?></p>
                <p><strong>Today's Horoscope:</strong> <?= htmlspecialchars($zodiac_details['daily_horoscope']); ?></p>
                <p><strong>Monthly Horoscope:</strong> <?= htmlspecialchars($zodiac_details['monthly_horoscope']); ?></p>
            </div>
        <?php else: ?>
            <p>Details for your zodiac sign are not available at the moment.</p>
        <?php endif; ?>
    </div>
</div>
<div class="d-flex justify-content-between">
                    <?php if ($_SESSION['role'] === 'admin'): ?>
                        <a href="viewuser.php" class="btn btn-primary">Manage Users</a>
                        <a href="add.php" class="btn btn-success">Add New User</a>
                    <?php else: ?>
                        <a href="viewuser.php" class="btn btn-primary">View Users</a>
                    <?php endif; ?>
                </div>
<?php include 'templates/footer.php'; ?>
