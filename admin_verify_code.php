<?php
session_start();
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $entered_code = $_POST['code'] ?? '';
    if ($entered_code == $_SESSION['reset_code']) {
       // Hifadhi kwamba code imethibitishwa
$_SESSION['code_verified'] = true;
header("Location: change_admin_password.php");
exit();

    } else {
        $error = "Invalid code.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Verify Code</title>
    <style>
        body { font-family: Arial; background: #eef2f3; display: flex; justify-content: center; align-items: center; height: 100vh; }
        .box { background: white; padding: 30px; border-radius: 8px; width: 300px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .error { color: red; text-align: center; }
    </style>
</head>
<body>
    <div class="box">
        <h3>Verify Code</h3>
        <?php if ($error): ?><p class="error"><?= htmlspecialchars($error) ?></p><?php endif; ?>
        <form method="post">
            <input type="text" name="code" placeholder="Enter code" required style="width:100%;padding:10px;margin:10px 0;">
            <button type="submit" style="width:100%;padding:10px;">Verify</button>
        </form>
    </div>
</body>
</html>
