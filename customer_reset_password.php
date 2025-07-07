<?php
session_start();
include 'config.php';

if (!isset($_SESSION['code_verified']) || !$_SESSION['code_verified']) {
    header("Location: customer_enter_email.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $password = $_POST['password'];
    $confirm = $_POST['confirm'];

    if ($password === $confirm) {
        $hashed = password_hash($password, PASSWORD_DEFAULT);
        $email = $_SESSION['reset_email'];

        $stmt = $conn->prepare("UPDATE customers SET password = ? WHERE email = ?");
        $stmt->bind_param("ss", $hashed, $email);
        $stmt->execute();

        // Auto-login
        $stmt = $conn->prepare("SELECT customer_id, full_name FROM customers WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->bind_result($id, $name);
        $stmt->fetch();

        $_SESSION['customer_id'] = $id;
        $_SESSION['full_name'] = $name;

        unset($_SESSION['reset_email'], $_SESSION['reset_code'], $_SESSION['code_verified']);

        header("Location: dashboard.php");
        exit();
    } else {
        $_SESSION['reset_error'] = "Passwords do not match.";
    }
}
?>

<!-- HTML -->
<!DOCTYPE html>
<html>
<head><title>Reset Password</title></head>
<body>
<h2>Enter New Password</h2>
<?php if (isset($_SESSION['reset_error'])) echo $_SESSION['reset_error']; ?>
<form method="POST">
    <input type="password" name="password" required placeholder="New password"><br>
    <input type="password" name="confirm" required placeholder="Confirm password"><br>
    <button type="submit">Reset Password</button>
</form>
</body>
</html>
