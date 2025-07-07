<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/Exception.php';
require 'PHPMailer/PHPMailer.php';
require 'PHPMailer/SMTP.php';

session_start();
require 'db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die("⚠️ Email si halali.");
    }

    // Angalia kama email iko kwenye database
    $stmt = $conn->prepare("SELECT * FROM admins WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        die("⚠️ Email haijasajiliwa!");
    }

    // Tuma verification code
    $verification_code = rand(100000, 999999);
    $_SESSION['reset_code'] = $verification_code;
    
   // $_SESSION['reset_email'] = $email;

// require '../vendor/autoload.php'; // Hakikisha umetumia Composer




    // ======= DUPLICATE SECTION (You can keep it if needed, just fixed syntax) =======

    // Hifadhi tena code na email kwenye session (already done above)
    $_SESSION['verification_code'] = $verification_code; // changed from $code
    $_SESSION['reset_email'] = $email;

    // Tuma email tena kwa PHPMailer (if intentional)
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
        $mail->Body    = "<p>Hello,</p><p>Your verification code is: <strong>$verification_code</strong></p>";

        $mail->send();

        echo "Verification code sent to $email";
        header("Refresh:3; url=admin_enter_code.php");
        exit();

    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }

} else {
    header("Location: admin_forgot_password.php");
    exit();
}
?>
