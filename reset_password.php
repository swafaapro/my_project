<?php
session_start();
include 'config.php';

$error = '';
$success = '';
$email = '';

// Kama mtumiaji ameshatuma email (kama hatua ya 1)
if (isset($_POST['step']) && $_POST['step'] === 'email') {
    $email = trim($_POST['email']);

    $stmt = $conn->prepare("SELECT customer_id FROM customers WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows === 1) {
        // Email ipo, tunahifadhi kwa session ili hatua ya pili
        $_SESSION['reset_email'] = $email;
        $_SESSION['reset_step'] = 2;
    } else {
        $error = "Email is not valid in system.";
    }

    $stmt->close();
}

// Kama mtumiaji anatumia form ya kuweka password mpya (hatua ya 2)
if (isset($_POST['step']) && $_POST['step'] === 'reset') {
    if (!isset($_SESSION['reset_email'])) {
        $error = "Please insert new pasword.";
    } else {
        $email = $_SESSION['reset_email'];
        $new_password = $_POST['new_password'];
        $confirm_password = $_POST['confirm_password'];

        if ($new_password !== $confirm_password) {
            $error = "Passwords do not match.";
        } else {
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

            $stmt = $conn->prepare("UPDATE customers SET password = ? WHERE email = ?");
            $stmt->bind_param("ss", $hashed_password, $email);

            if ($stmt->execute()) {
                $success = "Password has been changed successfully! You can now log in.";
                // Futa session ili kuzuia tena kutumia reset form
                unset($_SESSION['reset_email']);
                unset($_SESSION['reset_step']);
            } else {
                $error = "Failed to change the password, please try again.";
            }
            $stmt->close();
        }
    }
}

// Angalia hatua ya sasa ili kuonesha form sahihi
$step = $_SESSION['reset_step'] ?? 1;

?>

<!DOCTYPE html>
<html>
<head>
    <title>Reset Password</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f0f2f5;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .container {
            background: white;
            padding: 2rem;
            border-radius: 8px;
            width: 100%;
            max-width: 400px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        input[type="email"], input[type="password"] {
            width: 100%;
            padding: 0.8rem;
            margin: 0.5rem 0 1rem 0;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        button {
            width: 100%;
            background: #1e88e5;
            color: white;
            border: none;
            padding: 0.8rem;
            border-radius: 4px;
            cursor: pointer;
            font-weight: bold;
        }
        button:hover {
            background: #1565c0;
        }
        .error {
            background: #ffebee;
            color: #d32f2f;
            padding: 0.8rem;
            margin-bottom: 1rem;
            border-radius: 4px;
        }
        .success {
            background: #e8f5e9;
            color: #388e3c;
            padding: 0.8rem;
            margin-bottom: 1rem;
            border-radius: 4px;
        }
        h2 {
            margin-bottom: 1rem;
            color: #333;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Reset Password</h2>

        <?php if ($error): ?>
            <div class="error"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <?php if ($success): ?>
            <div class="success"><?php echo htmlspecialchars($success); ?></div>
            <p style="text-align:center;">
                <a href="login.php">Go to the login page</a>
            </p>
        <?php endif; ?>

        <?php if (!$success): ?>
            <?php if ($step === 1): ?>
                <!-- Hatua 1: Ingiza email -->
                <form method="POST" action="">
                    <input type="hidden" name="step" value="email">
                    <label>Enter your email</label>
                    <input type="email" name="email" value="<?php echo htmlspecialchars($email); ?>" required>
                    <button type="submit">Send</button>
                </form>

            <?php elseif ($step === 2): ?>
                <!-- Hatua 2: Ingiza password mpya -->
                <form method="POST" action="">
                    <input type="hidden" name="step" value="reset">
                    <label>New Password</label>
                    <input type="password" name="new_password" required>
                    <label>Confirm Password</label>
                    <input type="password" name="confirm_password" required>
                    <button type="submit">Reset Password</button>
                </form>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</body>
</html>
