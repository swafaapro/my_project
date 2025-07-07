<?php
$conn = new mysqli("localhost", "root", "", "laundry_management_system");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $transaction = $_POST['transaction'];
    $amount = $_POST['amount'];

    // Tafuta transaction kwenye payments na changanya na order ili kupata expected amount
    $stmt = $conn->prepare("
        SELECT p.*, o.total_amount 
        FROM payments p 
        JOIN orders o ON p.order_id = o.order_id 
        WHERE p.transaction_number = ?
    ");
    $stmt->bind_param("s", $transaction);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 0) {
        echo "Invalid transaction number.";
    } else {
        $row = $result->fetch_assoc();

        if ($amount != $row['amount']) {
            echo "The amount you entered does not match what the admin recorded.";
        } elseif ($amount != $row['total_amount']) {
            echo "You have not paid the full required amount.";
        } else {
            // Thibitisha payment
            $update1 = $conn->prepare("UPDATE payments SET status = 'Paid' WHERE id = ?");
$update1->bind_param("i", $row['id']);
$update1->execute();

$update2 = $conn->prepare("UPDATE orders SET payment_status = 'Paid' WHERE order_id = ?");
$update2->bind_param("i", $row['order_id']);
$update2->execute();


            echo "Payment confirmed. Thank you!";
        }
    }
}
?>

<form method="post">
    <input type="text" name="transaction" placeholder="Enter Transaction Number" required><br>
    <input type="number" step="0.01" name="amount" placeholder="Amount You Paid" required><br>
    <button type="submit">Confirm Payment</button>
</form>
