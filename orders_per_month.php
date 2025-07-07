<?php
include 'db_connection.php';

$sql = "SELECT MONTH(order_date) as month, COUNT(*) as total_orders
        FROM orders
        WHERE YEAR(order_date) = YEAR(CURDATE())
        GROUP BY MONTH(order_date)";
        
$result = $conn->query($sql);

$data = array_fill(1, 12, 0); // Initialize months Jan-Dec with 0

while ($row = $result->fetch_assoc()) {
    $data[(int)$row['month']] = (int)$row['total_orders'];
}

echo json_encode(array_values($data)); // Return data as JSON indexed by month
?>
 