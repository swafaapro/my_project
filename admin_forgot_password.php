<?php
session_start();
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = "Invalid email format.";
    } else {
        $_SESSION['reset_email'] = $email;
        header("Location: admin_reset_mail.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Forgot Password</title>
    <style>
        body { font-family: Arial; background: #f0f0f0; display: flex; justify-content: center; align-items: center; height: 100vh; }
        .box { background: white; padding: 30px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); width: 300px; }
        .error { color: red; text-align: center; }
    </style>
</head>
<body>
    <div class="box">
        <h3>Forgot Password</h3>
        <?php if ($message): ?><p class="error"><?= htmlspecialchars($message) ?></p><?php endif; ?>
       <form method="POST" action="admin_verify_email.php">
    <input type="email" name="email" required placeholder="Enter your email">
    <button type="submit">Send Code</button>
</form>

    </div>
</body>
</html>
