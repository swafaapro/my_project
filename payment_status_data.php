<?php
include 'db_connection.php';

$sql = "SELECT 
            SUM(CASE WHEN payment_status = 'Paid' THEN 1 ELSE 0 END) AS paid,
            SUM(CASE WHEN payment_status != 'Paid' OR payment_status IS NULL THEN 1 ELSE 0 END) AS not_paid
        FROM orders";

$result = $conn->query($sql);
$data = ['Paid' => 0, 'Not Paid' => 0];

if ($result) {
    $row = $result->fetch_assoc();
    $data['Paid'] = (int)$row['paid'];
    $data['Not Paid'] = (int)$row['not_paid'];
}

echo json_encode($data);
?>
