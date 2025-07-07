<?php
include 'db_connection.php';

$sql = "SELECT COUNT(*) AS new_orders FROM orders WHERE status = 'Pending'";
$result = $conn->query($sql);

if ($result) {
    $row = $result->fetch_assoc();
    echo $row['new_orders'];
} else {
    echo 0;
}
?>
