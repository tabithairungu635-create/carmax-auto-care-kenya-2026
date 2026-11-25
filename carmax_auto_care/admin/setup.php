<?php
declare(strict_types=1);

session_start();
require_once __DIR__ . '/../config/database.php';

$stmt = $pdo->query("
    SELECT COUNT(*) FROM staff_users WHERE email <> ''
");
$accountExists = (int)$stmt->fetchColumn() > 0;

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !$accountExists) {
    $name = trim((string)($_POST['full_name'] ?? ''));
    $email = strtolower(trim((string)($_POST['email'] ?? '')));
    $password = (string)($_POST['password'] ?? '');
    $confirm = (string)($_POST['confirm_password'] ?? '');

    if ($name === '' || strlen($name) > 120) {
        $error = 'Enter your full name.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Enter a valid email address.';
    } elseif (strlen($password) < 12) {
        $error = 'Your password must contain at least 12 characters.';
    } elseif ($password !== $confirm) {
        $error = 'Passwords do not match.';
    } else {
        try {
            $hash = password_hash($password, PASSWORD_DEFAULT);

            $existing = $pdo->query("
                SELECT id FROM staff_users ORDER BY id ASC LIMIT 1
            ")->fetch();

            if ($existing) {
                $stmt = $pdo->prepare("
                    UPDATE staff_users
                    SET email = ?, full_name = ?, password_hash = ?, is_active = 1
                    WHERE id = ?
                ");
                $stmt->execute([$email, $name, $hash, $existing['id']]);
            } else {
                $stmt = $pdo->prepare("
                    INSERT INTO staff_users
                    (username, email, full_name, password_hash, is_active)
                    VALUES (?, ?, ?, ?, 1)
                ");
                $stmt->execute([$email, $email, $name, $hash]);
            }

            $accountExists = true;
            $success = 'Your staff account has been created. You can now sign in.';
        } catch (PDOException $e) {
            error_log('CARMAX account setup error: ' . $e->getMessage());
            $error = 'Account creation failed. Check whether this email is already registered.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Create Staff Account | CARMAX AUTO CARE</title>
<style>
*{box-sizing:border-box}
body{margin:0;background:#101010;color:white;font-family:Arial;min-height:100vh;display:grid;place-items:center;padding:20px}
.container{width:100%;max-width:450px;background:#1b1b1b;padding:32px;border:1px solid #d4af69;border-radius:12px}
h1{text-align:center;color:#d4af69}
p{text-align:center;color:#aaa}
label{display:block;margin:17px 0 8px;color:#e5d2a9}
input{width:100%;padding:13px;background:#101010;color:white;border:1px solid #514938;border-radius:6px}
button{width:100%;padding:14px;margin-top:24px;background:#d4af69;border:0;border-radius:6px;font-weight:bold;cursor:pointer}
.message{padding:12px;margin:15px 0;border-radius:6px;background:#183622}
.error{padding:12px;margin:15px 0;border-radius:6px;background:#421e1e}
a{color:#d4af69}
</style>
</head>
<body>
<div class="container">
<h1>CARMAX AUTO CARE</h1>
<p>Create your administrator account</p>

<?php if ($accountExists): ?>
    <div class="message">
        Account setup is closed. A staff account has already been configured.
    </div>
    <p><a href="login.php">Go to staff login</a></p>
<?php else: ?>

    <?php if ($error): ?>
        <div class="error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <?php if ($success): ?>
        <div class="message"><?= htmlspecialchars($success) ?></div>
        <p><a href="login.php">Sign in now</a></p>
    <?php else: ?>
    <form method="POST">
        <label for="full_name">Full Name</label>
        <input id="full_name" name="full_name" required maxlength="120">

        <label for="email">Your Email Address</label>
        <input id="email" type="email" name="email" required maxlength="150">

        <label for="password">Create Password</label>
        <input id="password" type="password" name="password" required minlength="12">

        <label for="confirm_password">Confirm Password</label>
        <input id="confirm_password" type="password" name="confirm_password" required minlength="12">

        <button type="submit">CREATE MY ACCOUNT</button>
    </form>
    <?php endif; ?>

<?php endif; ?>
</div>
</body>
</html>
