<?php
session_start();
include 'config.php';
//require 'vendor/autoload.php'; // Make sure you've used Composer

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/Exception.php';
require 'PHPMailer/PHPMailer.php';
require 'PHPMailer/SMTP.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];

    $stmt = $conn->prepare("SELECT customer_id FROM customers WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows == 1) {
        $code = rand(100000, 999999); // 6-digit code
        $_SESSION['reset_email'] = $email;
        $_SESSION['reset_code'] = $code;

        // Send email
        $mail = new PHPMailer(true);
        try {
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'swafaahassan21@gmail.com'; // BADILISHA
        $mail->Password   = 'yoev qhew sopl jtpq';               // BADILISHA
        $mail->SMTPSecure = 'tls';
        $mail->Port       = 587;

        $mail->setFrom('swafaahassan21@gmail.com', 'Laundry');
        $mail->addAddress($email);

        // Usirudie addAddress na subject mara mbili
        $mail->isHTML(true);
        $mail->Subject = 'Verification Code to Reset Password';
        $mail->Body    = "<p>Hello,</p><p>Your verification code is: <strong>$code</strong></p>";

        $mail->send();

        echo "Verification code sent to $email";
        header("Refresh:3; url=customer_enter_code.php");
        exit();

    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }

} else {
    header("Location: customer_reset_password.php");
    exit();
}
}
?>

<!-- HTML -->
<!DOCTYPE html>
<html>
<head><title>Forgot Password</title></head>
<body>
<h2>Enter your Email</h2>
<?php if (isset($_SESSION['reset_error'])) echo $_SESSION['reset_error']; ?>
<form method="POST">
    <input type="email" name="email" required placeholder="Your email"><br>
    <button type="submit">Send Code</button>
</form>
</body>
</html>
