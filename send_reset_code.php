<?php
session_start();
$conn = new mysqli("localhost", "root", "", "laundry_management_system");

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['email'])) {
    $email = $_POST['email'];

    $stmt = $conn->prepare("SELECT * FROM admins WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $code = strtoupper(bin2hex(random_bytes(4))); // e.g. "A1B2C3D4"
        $_SESSION['reset_code'] = $code;
        $_SESSION['reset_email'] = $email;

        // Email sending (example uses mail())
        $subject = "Your Admin Password Reset Code";
        $message = "Your verification code is: $code";
        $headers = "From: no-reply@laundrysystem.com";

        if (mail($email, $subject, $message, $headers)) {
            header("Location: verify_code.php");
            exit();
        } else {
            echo "Failed to send email.";
        }
    } else {
        echo "No admin found with that email.";
    }
}
?>
