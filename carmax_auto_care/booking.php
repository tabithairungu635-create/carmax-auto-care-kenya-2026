<?php
declare(strict_types=1);

session_start();

require_once __DIR__ . '/config/database.php';

if (empty($_SESSION['booking_csrf'])) {
    $_SESSION['booking_csrf'] = bin2hex(random_bytes(32));
}

$services = [
    'Engine Repair & Diagnostics',
    'General Servicing',
    'Brake Services',
    'Suspension & Steering',
    'Battery & Electrical',
    'Tyres & Wheels',
    'Other / Not Sure'
];

$errors = [];
$success = false;
$bookingReference = '';

$values = [
    'customer_name' => '',
    'customer_phone' => '',
    'vehicle_make' => '',
    'vehicle_model' => '',
    'service_requested' => '',
    'preferred_date' => ''
];

function e(string $value): string
{
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    foreach ($values as $key => $value) {
        $values[$key] = trim((string)($_POST[$key] ?? ''));
    }

    if (!hash_equals(
        $_SESSION['booking_csrf'],
        (string)($_POST['csrf_token'] ?? '')
    )) {
        $errors[] = 'Your session expired. Refresh the page and try again.';
    }

    if ($values['customer_name'] === '' || strlen($values['customer_name']) > 120) {
        $errors[] = 'Enter your full name.';
    }

    if (!preg_match('/^[0-9+\s()-]{9,20}$/', $values['customer_phone'])) {
        $errors[] = 'Enter a valid phone number.';
    }

    if ($values['vehicle_make'] === '' || strlen($values['vehicle_make']) > 80) {
        $errors[] = 'Enter your vehicle make.';
    }

    if ($values['vehicle_model'] === '' || strlen($values['vehicle_model']) > 80) {
        $errors[] = 'Enter your vehicle model.';
    }

    if (!in_array($values['service_requested'], $services, true)) {
        $errors[] = 'Select a valid service.';
    }

    $date = DateTime::createFromFormat('!Y-m-d', $values['preferred_date']);
    $dateErrors = DateTime::getLastErrors();

    if (
        !$date ||
        ($dateErrors !== false &&
            ($dateErrors['warning_count'] > 0 || $dateErrors['error_count'] > 0)) ||
        $date->format('Y-m-d') !== $values['preferred_date'] ||
        $values['preferred_date'] < date('Y-m-d')
    ) {
        $errors[] = 'Choose a valid appointment date that is not in the past.';
    }

    if (!$errors) {
        try {
            $bookingReference = 'CMX-' . date('ymd') . '-' .
                strtoupper(bin2hex(random_bytes(3)));

            $stmt = $pdo->prepare("
                INSERT INTO bookings (
                    booking_reference,
                    customer_name,
                    customer_phone,
                    vehicle_make,
                    vehicle_model,
                    service_requested,
                    preferred_date
                ) VALUES (
                    :reference,
                    :name,
                    :phone,
                    :make,
                    :model,
                    :service,
                    :preferred_date
                )
            ");

            $stmt->execute([
                ':reference' => $bookingReference,
                ':name' => $values['customer_name'],
                ':phone' => $values['customer_phone'],
                ':make' => $values['vehicle_make'],
                ':model' => $values['vehicle_model'],
                ':service' => $values['service_requested'],
                ':preferred_date' => $values['preferred_date']
            ]);

            $success = true;
            $_SESSION['booking_csrf'] = bin2hex(random_bytes(32));

            foreach ($values as $key => $value) {
                $values[$key] = '';
            }

        } catch (PDOException $e) {
            error_log('CARMAX booking error: ' . $e->getMessage());
            $errors[] = 'We could not save your request. Please try again.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Request a vehicle service appointment at CARMAX AUTO CARE in Kenol Town.">
    <title>Book a Service | CARMAX AUTO CARE</title>
    <link rel="stylesheet" href="assets/css/style.css">

    <style>
        .booking-section {
            min-height: 75vh;
            padding: 65px 20px;
            background: #101010;
            color: #fff;
        }

        .booking-container {
            max-width: 850px;
            margin: auto;
        }

        .booking-heading {
            text-align: center;
            margin-bottom: 35px;
        }

        .booking-heading h1 {
            font-size: clamp(2rem, 5vw, 3.5rem);
            color: #d4af69;
            margin-bottom: 10px;
        }

        .booking-form {
            background: #1b1b1b;
            border: 1px solid #39332a;
            border-radius: 12px;
            padding: 30px;
        }

        .booking-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 20px;
        }

        .booking-field {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .booking-field.full {
            grid-column: 1 / -1;
        }

        .booking-field label {
            color: #e5d2a9;
            font-weight: 600;
        }

        .booking-field input,
        .booking-field select {
            width: 100%;
            padding: 13px;
            border: 1px solid #514938;
            border-radius: 6px;
            background: #101010;
            color: #fff;
            font: inherit;
        }

        .booking-submit {
            width: 100%;
            margin-top: 25px;
            padding: 15px;
            border: 0;
            border-radius: 6px;
            background: #d4af69;
            color: #111;
            font-size: 1rem;
            font-weight: 700;
            cursor: pointer;
        }

        .booking-submit:hover {
            background: #ebc982;
        }

        .booking-message {
            padding: 16px;
            border-radius: 7px;
            margin-bottom: 20px;
        }

        .booking-error {
            background: #421e1e;
            border: 1px solid #a94c4c;
        }

        .booking-success {
            background: #183622;
            border: 1px solid #3b8c55;
        }

        .booking-note {
            color: #c4c4c4;
            margin-top: 18px;
            text-align: center;
            line-height: 1.6;
        }

        @media(max-width: 600px) {
            .booking-grid {
                grid-template-columns: 1fr;
            }

            .booking-form {
                padding: 20px;
            }
        }
    </style>
</head>
<body>

<main class="booking-section">
    <div class="booking-container">

        <div class="booking-heading">
            <h1>BOOK A SERVICE</h1>
            <p>Tell us about your vehicle and the service you need.</p>
        </div>

        <?php if ($success): ?>
            <div class="booking-message booking-success">
                <h2>Booking request received!</h2>
                <p>Your booking reference is:</p>
                <h3><?= e($bookingReference) ?></h3>
                <p>Your request is pending confirmation by CARMAX AUTO CARE. Please keep your reference number.</p>
            </div>
        <?php endif; ?>

        <?php if ($errors): ?>
            <div class="booking-message booking-error" role="alert">
                <strong>Please correct the following:</strong>
                <ul>
                    <?php foreach ($errors as $error): ?>
                        <li><?= e($error) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form method="POST" action="booking.php" class="booking-form">

            <input type="hidden" name="csrf_token"
                   value="<?= e($_SESSION['booking_csrf']) ?>">

            <div class="booking-grid">

                <div class="booking-field">
                    <label for="customer_name">Full Name *</label>
                    <input id="customer_name" name="customer_name"
                           required maxlength="120"
                           value="<?= e($values['customer_name']) ?>">
                </div>

                <div class="booking-field">
                    <label for="customer_phone">Phone Number *</label>
                    <input id="customer_phone" name="customer_phone"
                           type="tel" required maxlength="20"
                           value="<?= e($values['customer_phone']) ?>">
                </div>

                <div class="booking-field">
                    <label for="vehicle_make">Vehicle Make *</label>
                    <input id="vehicle_make" name="vehicle_make"
                           required maxlength="80" placeholder="e.g. Toyota"
                           value="<?= e($values['vehicle_make']) ?>">
                </div>

                <div class="booking-field">
                    <label for="vehicle_model">Vehicle Model *</label>
                    <input id="vehicle_model" name="vehicle_model"
                           required maxlength="80" placeholder="e.g. Axio"
                           value="<?= e($values['vehicle_model']) ?>">
                </div>

                <div class="booking-field full">
                    <label for="service_requested">Service Required *</label>
                    <select id="service_requested" name="service_requested" required>
                        <option value="">Select a service</option>
                        <?php foreach ($services as $service): ?>
                            <option value="<?= e($service) ?>"
                                <?= $values['service_requested'] === $service ? 'selected' : '' ?>>
                                <?= e($service) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="booking-field full">
                    <label for="preferred_date">Preferred Date *</label>
                    <input id="preferred_date" name="preferred_date"
                           type="date" min="<?= e(date('Y-m-d')) ?>"
                           required value="<?= e($values['preferred_date']) ?>">
                </div>

            </div>

            <button type="submit" class="booking-submit">
                SUBMIT BOOKING REQUEST
            </button>

            <p class="booking-note">
                Submitting this form sends an appointment request, not a confirmed booking.
                Our team will contact you to confirm availability.
            </p>

        </form>

    </div>
</main>

</body>
</html>
