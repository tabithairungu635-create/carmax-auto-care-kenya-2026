<?php
declare(strict_types=1);

session_start();
require_once __DIR__ . '/../config/database.php';

if (!empty($_SESSION['carmax_staff_id'])) {
    header('Location: dashboard.php');
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim((string)($_POST['email'] ?? ''));
    $password = (string)($_POST['password'] ?? '');

    $stmt = $pdo->prepare("
        SELECT id, email, password_hash, full_name
        FROM staff_users
        WHERE email = :email AND is_active = 1
        LIMIT 1
    ");
    $stmt->execute(['email' => $email]);
    $staff = $stmt->fetch();

    if ($staff && password_verify($password, $staff['password_hash'])) {
        session_regenerate_id(true);
        $_SESSION['carmax_staff_id'] = (int)$staff['id'];
        $_SESSION['carmax_staff_name'] = $staff['full_name'];

        header('Location: dashboard.php');
        exit;
    }

    $error = 'Incorrect email or password.';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Staff Login | CARMAX AUTO CARE</title>
<style>
*{box-sizing:border-box}
body{margin:0;background:#101010;color:#fff;font-family:Arial;min-height:100vh;display:grid;place-items:center;padding:20px}
.login-box{width:100%;max-width:430px;background:#1b1b1b;border:1px solid #d4af69;border-radius:12px;padding:35px}
h1{text-align:center;color:#d4af69}
p{text-align:center;color:#aaa}
label{display:block;margin:18px 0 8px;color:#e5d2a9}
input{width:100%;padding:14px;background:#101010;border:1px solid #514938;border-radius:6px;color:white}
button{width:100%;padding:15px;margin-top:25px;background:#d4af69;border:0;border-radius:6px;font-weight:bold;cursor:pointer}
.error{background:#421e1e;padding:12px;margin-bottom:15px;border-radius:6px}
</style>
</head>
<body>
<div class="login-box">
<h1>CARMAX AUTO CARE</h1>
<p>Staff Management Portal</p>

<?php if ($error): ?>
<div class="error"><?= htmlspecialchars($error) ?></div>
<?php endif; ?>

<form method="POST">
<label for="email">Email Address</label>
<input type="email" id="email" name="email" required>

<label for="password">Password</label>
<input type="password" id="password" name="password" required>

<button type="submit">SIGN IN</button>
</form>
</div>
</body>
</html>