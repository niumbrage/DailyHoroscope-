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
$query = "SELECT name, date_of_birth, role, address FROM users WHERE email = ?";
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

// Fetch zodiac details
$zodiac_query = "SELECT description, image FROM zodiac_signs WHERE name = ?";
$zodiac_stmt = $conn->prepare($zodiac_query);
$zodiac_stmt->bind_param("s", $zodiac_sign);
$zodiac_stmt->execute();
$zodiac_details = $zodiac_stmt->get_result()->fetch_assoc();

// Fetch today's horoscope
$current_date = (new DateTime())->format('Y-m-d');

// First, try to fetch today's horoscope
$daily_query = "
    SELECT horoscope 
    FROM daily_horoscopes 
    WHERE zodiac_id = (SELECT id FROM zodiac_signs WHERE name = ?) 
    AND date = ?
    ORDER BY date DESC LIMIT 1
";
$daily_stmt = $conn->prepare($daily_query);
$daily_stmt->bind_param("ss", $zodiac_sign, $current_date);
$daily_stmt->execute();
$daily_result = $daily_stmt->get_result();
$daily_horoscope = $daily_result->fetch_assoc()['horoscope'] ?? null;

// If no horoscope for today, fetch the most recent one available
if (!$daily_horoscope) {
    $fallback_query = "
        SELECT horoscope 
        FROM daily_horoscopes 
        WHERE zodiac_id = (SELECT id FROM zodiac_signs WHERE name = ?) 
        ORDER BY date DESC LIMIT 1
    ";
    $fallback_stmt = $conn->prepare($fallback_query);
    $fallback_stmt->bind_param("s", $zodiac_sign);
    $fallback_stmt->execute();
    $fallback_result = $fallback_stmt->get_result();
    $daily_horoscope = $fallback_result->fetch_assoc()['horoscope'] ?? 'No horoscope available at this time.';
}

// Debugging log to check the fallback mechanism
error_log("Final Daily Horoscope: " . $daily_horoscope);

// Debugging statement
error_log("Daily Horoscope Query: " . $daily_stmt->error);
error_log("Daily Horoscope Result: " . print_r($daily_horoscope, true));

// Fetch this month's horoscope
$current_month = (new DateTime())->format('Y-m-01'); // Format as YYYY-MM-DD for compatibility

// First, try to fetch the horoscope for the current month
$monthly_query = "
    SELECT horoscope 
    FROM monthly_horoscopes 
    WHERE zodiac_id = (SELECT id FROM zodiac_signs WHERE name = ?) 
    AND DATE_FORMAT(month, '%Y-%m-01') = ?
    LIMIT 1
";
$monthly_stmt = $conn->prepare($monthly_query);
$monthly_stmt->bind_param("ss", $zodiac_sign, $current_month);
$monthly_stmt->execute();
$monthly_result = $monthly_stmt->get_result();
$monthly_horoscope = $monthly_result->fetch_assoc()['horoscope'] ?? null;

// If no horoscope for the current month, fetch the most recent one available
if (!$monthly_horoscope) {
    $fallback_monthly_query = "
        SELECT horoscope 
        FROM monthly_horoscopes 
        WHERE zodiac_id = (SELECT id FROM zodiac_signs WHERE name = ?) 
        ORDER BY month DESC 
        LIMIT 1
    ";
    $fallback_monthly_stmt = $conn->prepare($fallback_monthly_query);
    $fallback_monthly_stmt->bind_param("s", $zodiac_sign);
    $fallback_monthly_stmt->execute();
    $fallback_monthly_result = $fallback_monthly_stmt->get_result();
    $monthly_horoscope = $fallback_monthly_result->fetch_assoc()['horoscope'] ?? 'No monthly horoscope available at this time.';
}

// Debugging log to check the fallback mechanism
error_log("Final Monthly Horoscope: " . $monthly_horoscope);

?>

<div class="custom-container">
    <h1>Welcome, <?= htmlspecialchars($user['name']); ?>!</h1>
</div>

<div class="parent-container mt-5">
    <div class="zodiac-container mt-5">
        <h2>Your Zodiac Sign: <?= htmlspecialchars($zodiac_sign); ?></h2>
        <div class="zodiac-details mt-4">
            <img src="<?= htmlspecialchars($zodiac_details['image']); ?>" alt="<?= htmlspecialchars($zodiac_sign); ?>" class="img-fluid" style="max-width: 200px;">
            <p><strong>Description:</strong> <?= htmlspecialchars($zodiac_details['description']); ?></p>
            <p><strong>Today's Horoscope:</strong> <?= htmlspecialchars($daily_horoscope); ?></p>
            <p><strong>Monthly Horoscope:</strong> <?= htmlspecialchars($monthly_horoscope); ?></p>
        </div>
    </div>
</div>

<div id="map" style="height: 400px; margin-top: 20px;"></div>

<iframe id="calendar-iframe" style="border: 0" width="100%" height="600" frameborder="0" scrolling="no"></iframe>

<?php if ($_SESSION['role'] === 'admin'): ?>
    <div class="d-flex justify-content-between">
        <a href="viewuser.php" class="btn btn-primary">Manage Users</a>
        <a href="add.php" class="btn btn-success">Add New User</a>
        <a href="add_horoscope.php" class="btn btn-warning">Manage Horoscopes</a>
    </div>
<?php endif; ?>

<?php include 'templates/footer.php'; ?>

<script>
    function initMap() {
        var geocoder = new google.maps.Geocoder();
        var address = "<?= htmlspecialchars($user['address']); ?>";

        geocoder.geocode({'address': address}, function(results, status) {
            if (status === 'OK') {
                var map = new google.maps.Map(document.getElementById('map'), {
                    zoom: 10,
                    center: results[0].geometry.location
                });
                var marker = new google.maps.Marker({
                    map: map,
                    position: results[0].geometry.location
                });
            } else {
                alert('Geocode was not successful for the following reason: ' + status);
            }
        });
    }

    function setCalendarIframe() {
        var timezone = Intl.DateTimeFormat().resolvedOptions().timeZone;
        console.log("Detected Timezone: " + timezone); // Log the detected timezone
        var calendarUrl = "https://calendar.google.com/calendar/embed?src=your_calendar_id@group.calendar.google.com&ctz=" + timezone; //add actual calendar id
        console.log("Generated Calendar URL: " + calendarUrl); // Log the URL
        document.getElementById('calendar-iframe').src = calendarUrl;
    }

    window.onload = function() {
        setCalendarIframe();
    };
</script>

<script src="https://maps.googleapis.com/maps/api/js?key=google_api_key&loading=async&callback=initMap" async defer></script> //add actual api key
