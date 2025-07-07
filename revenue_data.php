<?php
include 'db_connection.php';

// Query kuchukua mapato ya kila mwezi kwa mwaka huu kwa orders zilizo lipwa
$sql = "SELECT MONTH(order_date) AS month, 
               IFNULL(SUM(total_amount), 0) AS revenue
        FROM orders
        WHERE payment_status = 'Paid' AND YEAR(order_date) = YEAR(CURDATE())
        GROUP BY MONTH(order_date)";

// Run query
$result = $conn->query($sql);

// Initialize array ya mapato kwa miezi 1-12
$monthly_revenue = array_fill(1, 12, 0);

if ($result) {
    while ($row = $result->fetch_assoc()) {
        $month = (int)$row['month'];
        $monthly_revenue[$month] = (float)$row['revenue'];
    }
} 

// Return JSON array ya mapato kwa miezi kwa mpangilio wa Jan hadi Dec
echo json_encode(array_values($monthly_revenue));
