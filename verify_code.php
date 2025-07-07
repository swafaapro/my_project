<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $entered_code = $_POST['code'];
    $correct_code = $_SESSION['verification_code'] ?? '';

    if ($entered_code == $correct_code) {
        // Code sahihi, elekeza kurasa ya reset
        header("Location: reset_password.php");
        exit();
    } else {
        echo "Incorrect code.";
    }
} else {
    header("Location: admin_enter_code.php");
    exit();
}
