<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $entered_code = $_POST['code'];

    if ($_SESSION['reset_code'] == $entered_code) {
        $_SESSION['code_verified'] = true;
        header("Location: customer_reset_password.php");
        exit();
    } else {
        $_SESSION['reset_error'] = "Invalid verification code.";
        header("Location: customer_enter_code.php");
        exit();
    }
}
?>

<!-- HTML -->
<!DOCTYPE html>
<html>
<head><title>Enter Code</title></head>
<body>
<h2>Enter Verification Code</h2>
<?php if (isset($_SESSION['reset_error'])) echo $_SESSION['reset_error']; ?>
<form method="POST">
    <input type="text" name="code" required placeholder="Enter code"><br>
    <button type="submit">Verify</button>
</form>
</body>
</html>
