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
$query = "SELECT name, date_of_birth, role FROM users WHERE email = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $email);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();

// Store user role in the session
$_SESSION['role'] = $user['role'];

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

// Fetch all horoscopes for the zodiac sign, ordered by creation date
$query = "
    SELECT horoscopes.daily_horoscope, horoscopes.monthly_horoscope, horoscopes.created_at, zodiac_signs.description, zodiac_signs.image
    FROM zodiac_signs
    JOIN horoscopes ON zodiac_signs.id = horoscopes.zodiac_id
    WHERE zodiac_signs.name = ?
    ORDER BY horoscopes.created_at ASC
";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $zodiac_sign);
$stmt->execute();
$horoscopes = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

$current_date = new DateTime();
$daily_horoscope = null;
$monthly_horoscope = null;

// Cycle through horoscopes
foreach ($horoscopes as $horoscope) {
    $horoscope_date = new DateTime($horoscope['created_at']);
    if ($current_date >= $horoscope_date) {
        $daily_horoscope = $horoscope['daily_horoscope'];
        $monthly_horoscope = $horoscope['monthly_horoscope'];
    }
}

// If no matching horoscope is found, cycle back to the first
if (!$daily_horoscope) {
    $daily_horoscope = $horoscopes[0]['daily_horoscope'];
    $monthly_horoscope = $horoscopes[0]['monthly_horoscope'];
    $zodiac_description = $horoscopes[0]['description'];
    $zodiac_image = $horoscopes[0]['image'];
} else {
    $zodiac_description = $horoscope['description'];
    $zodiac_image = $horoscope['image'];
}

?>

<div class="custom-container">
    <h1>Welcome, <?= htmlspecialchars($user['name']); ?>!</h1>
</div>

<div class="parent-container mt-5">
    <div class="zodiac-container mt-5">
        <h2>Your Zodiac Sign: <?= htmlspecialchars($zodiac_sign); ?></h2>
        <div class="zodiac-details mt-4">
            <img src="<?= htmlspecialchars($zodiac_image); ?>" alt="<?= htmlspecialchars($zodiac_sign); ?>" class="img-fluid" style="max-width: 200px;">
            <p><strong>Description:</strong> <?= htmlspecialchars($zodiac_description); ?></p>
            <p><strong>Today's Horoscope:</strong> <?= htmlspecialchars($daily_horoscope); ?></p>
            <p><strong>Monthly Horoscope:</strong> <?= htmlspecialchars($monthly_horoscope); ?></p>
        </div>
    </div>
</div>

<?php if ($_SESSION['role'] === 'admin'): ?>
    <div class="d-flex justify-content-between">
        <a href="viewuser.php" class="btn btn-primary">Manage Users</a>
        <a href="add.php" class="btn btn-success">Add New User</a>
        <a href="add_horoscope.php" class="btn btn-warning">Add New Horoscope</a>
    </div>
<?php endif; ?>

<?php include 'templates/footer.php'; ?>
