<?php
$host = 'localhost';
$dbname = 'laundry_management_system';
$username = 'root';
$password = ''; // default password XAMPP

$conn = new mysqli($host, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
