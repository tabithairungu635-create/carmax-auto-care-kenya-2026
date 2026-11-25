<?php
declare(strict_types=1);

session_start();

if (empty($_SESSION['carmax_staff_id'])) {
    header('Location: login.php');
    exit;
}

require_once __DIR__ . '/../config/database.php';

if (empty($_SESSION['booking_manage_csrf'])) {
    $_SESSION['booking_manage_csrf'] = bin2hex(random_bytes(32));
}

function e(string $value): string
{
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}

$reference = trim((string)($_GET['reference'] ?? ''));

$stmt = $pdo->prepare("
    SELECT *
    FROM bookings
    WHERE booking_reference = ?
    LIMIT 1
");
$stmt->execute([$reference]);
$booking = $stmt->fetch();

if (!$booking) {
    http_response_code(404);
    exit('Booking not found.');
}

$message = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $token = (string)($_POST['csrf_token'] ?? '');
    $newStatus = (string)($_POST['status'] ?? '');

    $allowedStatuses = [
        'pending',
        'confirmed',
        'cancelled',
        'completed'
    ];

    if (!hash_equals($_SESSION['booking_manage_csrf'], $token)) {
        $error = 'Your session has expired. Refresh the page and try again.';
    } elseif (!in_array($newStatus, $allowedStatuses, true)) {
        $error = 'Invalid booking status.';
    } else {
        $update = $pdo->prepare("
            UPDATE bookings
            SET status = ?
            WHERE id = ?
        ");
        $update->execute([$newStatus, $booking['id']]);

        $_SESSION['booking_manage_csrf'] = bin2hex(random_bytes(32));

        header(
            'Location: booking-view.php?reference=' .
            urlencode($reference) . '&updated=1'
        );
        exit;
    }
}

if (isset($_GET['updated'])) {
    $message = 'Booking status updated successfully.';
}

$stmt = $pdo->prepare("
    SELECT *
    FROM bookings
    WHERE booking_reference = ?
    LIMIT 1
");
$stmt->execute([$reference]);
$booking = $stmt->fetch();

?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Manage Booking | CARMAX AUTO CARE</title>
<style>
*{box-sizing:border-box}
body{margin:0;background:#101010;color:#eee;font-family:Arial,sans-serif}
header{background:#191919;border-bottom:1px solid #403625;padding:20px 5%;display:flex;justify-content:space-between;align-items:center}
header h2{color:#d4af69;margin:0}
a{color:#d4af69;text-decoration:none}
main{max-width:850px;margin:40px auto;padding:0 20px}
h1{margin-bottom:8px}
.subtitle{color:#aaa;margin-bottom:30px}
.details{background:#1b1b1b;border:1px solid #403625;border-radius:10px;padding:25px}
.detail-row{display:grid;grid-template-columns:190px 1fr;gap:15px;padding:14px 0;border-bottom:1px solid #333}
.detail-row:last-child{border-bottom:0}
.detail-label{color:#d4af69;font-weight:bold}
.status-form{margin-top:25px;padding:25px;background:#1b1b1b;border:1px solid #403625;border-radius:10px}
select{width:100%;padding:13px;margin:12px 0;background:#101010;color:white;border:1px solid #514938;border-radius:6px}
button{padding:14px 22px;background:#d4af69;color:#111;border:0;border-radius:6px;font-weight:bold;cursor:pointer}
.message{padding:13px;margin-bottom:20px;background:#183622;border:1px solid #3b8c55;border-radius:6px}
.error{padding:13px;margin-bottom:20px;background:#421e1e;border:1px solid #a94c4c;border-radius:6px}
@media(max-width:550px){.detail-row{grid-template-columns:1fr;gap:6px}header{gap:15px;flex-wrap:wrap}}
</style>
</head>
<body>

<header>
    <h2>CARMAX AUTO CARE</h2>
    <a href="dashboard.php">Back to Dashboard</a>
</header>

<main>
    <h1>Booking Details</h1>
    <p class="subtitle">Review and manage this customer booking.</p>

    <?php if ($message): ?>
        <div class="message"><?= e($message) ?></div>
    <?php endif; ?>

    <?php if ($error): ?>
        <div class="error"><?= e($error) ?></div>
    <?php endif; ?>

    <section class="details">
        <div class="detail-row">
            <div class="detail-label">Booking Reference</div>
            <div><?= e($booking['booking_reference']) ?></div>
        </div>

        <div class="detail-row">
            <div class="detail-label">Customer Name</div>
            <div><?= e($booking['customer_name']) ?></div>
        </div>

        <div class="detail-row">
            <div class="detail-label">Phone Number</div>
            <div><?= e($booking['customer_phone']) ?></div>
        </div>

        <div class="detail-row">
            <div class="detail-label">Vehicle</div>
            <div><?= e($booking['vehicle_make'] . ' ' . $booking['vehicle_model']) ?></div>
        </div>

        <div class="detail-row">
            <div class="detail-label">Service Required</div>
            <div><?= e($booking['service_requested']) ?></div>
        </div>

        <div class="detail-row">
            <div class="detail-label">Preferred Date</div>
            <div><?= e($booking['preferred_date']) ?></div>
        </div>

        <div class="detail-row">
            <div class="detail-label">Current Status</div>
            <div><?= e(ucwords(str_replace('_', ' ', $booking['status']))) ?></div>
        </div>

        <div class="detail-row">
            <div class="detail-label">Submitted On</div>
            <div><?= e($booking['created_at']) ?></div>
        </div>
    </section>

    <form method="POST" class="status-form">
        <h2>Update Booking Status</h2>

        <input type="hidden" name="csrf_token"
               value="<?= e($_SESSION['booking_manage_csrf']) ?>">

        <label for="status">Choose Status</label>

        <select name="status" id="status" required>
            <?php foreach (['pending', 'confirmed', 'cancelled', 'completed'] as $status): ?>
                <option value="<?= e($status) ?>"
                    <?= $booking['status'] === $status ? 'selected' : '' ?>>
                    <?= e(ucfirst($status)) ?>
                </option>
            <?php endforeach; ?>
        </select>

        <button type="submit">SAVE STATUS</button>
    </form>

</main>
</body>
</html>
