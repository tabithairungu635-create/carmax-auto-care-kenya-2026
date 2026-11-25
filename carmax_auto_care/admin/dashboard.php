<?php
declare(strict_types=1);

session_start();

if (empty($_SESSION['carmax_staff_id'])) {
    header('Location: login.php');
    exit;
}

require_once __DIR__ . '/../config/database.php';

$counts = [
    'total' => 0,
    'pending' => 0,
    'confirmed' => 0,
    'completed' => 0,
    'cancelled' => 0
];

$stmt = $pdo->query("
    SELECT status, COUNT(*) AS total
    FROM bookings
    GROUP BY status
");

foreach ($stmt->fetchAll() as $row) {
    $counts[$row['status']] = (int)$row['total'];
    $counts['total'] += (int)$row['total'];
}

$bookings = $pdo->query("
    SELECT booking_reference, customer_name, customer_phone,
           vehicle_make, vehicle_model, service_requested,
           preferred_date, status, created_at
    FROM bookings
    ORDER BY created_at DESC
    LIMIT 10
")->fetchAll();

function e(string $value): string {
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff Dashboard | CARMAX AUTO CARE</title>
    <link rel="stylesheet" href="../assets/css/style.css">

    <style>
        * { box-sizing: border-box; }

        body {
            margin: 0;
            background: #101010;
            color: #eee;
            font-family: Arial, sans-serif;
        }

        .admin-header {
            background: #191919;
            border-bottom: 1px solid #403625;
            padding: 20px 5%;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 15px;
        }

        .admin-header h2 {
            color: #d4af69;
            margin: 0;
        }

        .admin-header a,
        .logout {
            color: #d4af69;
            text-decoration: none;
        }

        .dashboard {
            max-width: 1250px;
            margin: 35px auto;
            padding: 0 20px;
        }

        .welcome h1 {
            margin-bottom: 8px;
        }

        .welcome p { color: #aaa; }

        .stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(170px, 1fr));
            gap: 18px;
            margin: 30px 0;
        }

        .stat-card {
            background: #1c1c1c;
            border: 1px solid #403625;
            border-radius: 9px;
            padding: 22px;
        }

        .stat-card span {
            color: #aaa;
            font-size: 14px;
        }

        .stat-card h2 {
            color: #d4af69;
            font-size: 32px;
            margin: 12px 0 0;
        }

        .table-container {
            overflow-x: auto;
            background: #1b1b1b;
            border: 1px solid #403625;
            border-radius: 9px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            min-width: 950px;
        }

        th, td {
            padding: 14px;
            text-align: left;
            border-bottom: 1px solid #333;
            font-size: 13px;
        }

        th {
            color: #d4af69;
            background: #242424;
        }

        .status {
            display: inline-block;
            padding: 5px 9px;
            border-radius: 20px;
            background: #333;
            font-size: 12px;
            text-transform: capitalize;
        }

        .manage-link {
            color: #d4af69;
            text-decoration: none;
            font-weight: bold;
        }

        .empty {
            padding: 25px;
            color: #aaa;
            text-align: center;
        }

        @media(max-width: 600px) {
            .admin-header {
                align-items: flex-start;
                flex-direction: column;
            }
        }
    </style>
</head>
<body>

<header class="admin-header">
    <h2>CARMAX AUTO CARE</h2>
    <div>
        Welcome, <?= e((string)$_SESSION['carmax_staff_name']) ?>
        &nbsp; | &nbsp;
        <a href="logout.php" class="logout">Logout</a>
    </div>
</header>

<main class="dashboard">

    <section class="welcome">
        <h1>Staff Dashboard</h1>
        <p>Overview of customer service bookings.</p>
    </section>

    <section class="stats">
        <div class="stat-card">
            <span>Total Bookings</span>
            <h2><?= $counts['total'] ?></h2>
        </div>

        <div class="stat-card">
            <span>Pending Requests</span>
            <h2><?= $counts['pending'] ?></h2>
        </div>

        <div class="stat-card">
            <span>Confirmed</span>
            <h2><?= $counts['confirmed'] ?></h2>
        </div>

        <div class="stat-card">
            <span>Completed</span>
            <h2><?= $counts['completed'] ?></h2>
        </div>

        <div class="stat-card">
            <span>Cancelled</span>
            <h2><?= $counts['cancelled'] ?></h2>
        </div>
    </section>

    <h2>Recent Bookings</h2>

    <div class="table-container">
        <?php if ($bookings): ?>
            <table>
                <thead>
                    <tr>
                        <th>Reference</th>
                        <th>Customer</th>
                        <th>Phone</th>
                        <th>Vehicle</th>
                        <th>Service</th>
                        <th>Date</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($bookings as $booking): ?>
                        <tr>
                            <td><?= e($booking['booking_reference']) ?></td>
                            <td><?= e($booking['customer_name']) ?></td>
                            <td><?= e($booking['customer_phone']) ?></td>
                            <td><?= e($booking['vehicle_make'] . ' ' . $booking['vehicle_model']) ?></td>
                            <td><?= e($booking['service_requested']) ?></td>
                            <td><?= e($booking['preferred_date']) ?></td>
                            <td>
                                <span class="status">
                                    <?= e($booking['status']) ?>
                                </span>
                            </td>
                            <td>
                                <a class="manage-link"
                                   href="booking-view.php?reference=<?= urlencode($booking['booking_reference']) ?>">
                                    Manage
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <div class="empty">No customer bookings have been received yet.</div>
        <?php endif; ?>
    </div>

</main>

</body>
</html>
