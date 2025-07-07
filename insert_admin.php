<?php
include 'db_connection.php'; // tumia connection yako ileile

$username = "admin";
$plain_password = "admin123";
$email = "admin@example.com";

// Check if admin already exists
$stmt = $conn->prepare("SELECT * FROM admin WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    echo "✅ Admin tayari yupo kwenye database.";
} else {
    $hashed_password = password_hash($plain_password, PASSWORD_DEFAULT);

    $insert = $conn->prepare("INSERT INTO admin (username, password, email) VALUES (?, ?, ?)");
    $insert->bind_param("sss", $username, $hashed_password, $email);

    if ($insert->execute()) {
        echo "✅ Admin amewekwa kwenye database.";
    } else {
        echo "❌ Error inserting: " . $insert->error;
    }
    $insert->close();
}
$stmt->close();
$conn->close();
?>
