<?php
$conn = new mysqli("localhost", "root", "", "laundry_management_system");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$orderOptions = "";
$selected_phone = "";
$payment_success = "";
$error = "";

// Step 1: Fetch orders by customer phone
if (isset($_POST['fetch_orders'])) {
    $selected_phone = $_POST['customer_phone'];

    $stmt = $conn->prepare("SELECT order_id, total_amount, payment_status FROM orders WHERE customer_phone = ?");
    $stmt->bind_param("s", $selected_phone);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $orderOptions .= "<option value=''>Select Order</option>";
        while ($row = $result->fetch_assoc()) {
            $orderOptions .= "<option value='" . $row['order_id'] . "'>
                Order No" . $row['order_id'] . " - Tsh " . number_format($row['total_amount'], 2) . " (" . $row['payment_status'] . ")
            </option>";
        }
    } else {
        $error = "No orders found for this phone number.";
    }
}

// Step 2: Submit payment
if (isset($_POST['submit_payment'])) {
    $selected_order_id = $_POST['order_id'];
    $paid_amount = $_POST['paid_amount'];
    $transaction_number = $_POST['transaction_number'];

    // ✅ Get customer_id from order
    $getCustomer = $conn->prepare("SELECT customer_id FROM orders WHERE order_id = ?");
    $getCustomer->bind_param("i", $selected_order_id);
    $getCustomer->execute();
    $getCustomer->bind_result($customer_id);
    $getCustomer->fetch();
    $getCustomer->close();

    if (!$customer_id) {
        $error = "Failed to get customer ID for the order.";
    } else {
        // ✅ Insert payment
        $stmt = $conn->prepare("INSERT INTO payments (order_id, customer_id, amount, transaction_number, status) VALUES (?, ?, ?, ?, 'Unpaid')");
$stmt->bind_param("iisd", $selected_order_id, $customer_id, $paid_amount, $transaction_number);


        if ($stmt->execute()) {
            // ✅ Update order payment status
            $update = $conn->prepare("UPDATE orders SET payment_status = 'Paid' WHERE order_id = ?");
            $update->bind_param("i", $selected_order_id);
            $update->execute();
            $update->close();

            $payment_success = "Payment recorded successfully!";
        } else {
            $error = "Failed to record payment. Error: " . $stmt->error;
        }

        $stmt->close();
    }
}
?>


<!DOCTYPE html>
<html>
<head>
    <title>Admin Add Payment</title>
    <style>
        body { font-family: Arial; padding: 20px; }
        input, select, button { padding: 8px; margin-top: 10px; width: 100%; }
        .success { color: green; margin-top: 10px; }
        .error { color: red; margin-top: 10px; }
        .card { max-width: 500px; margin: auto; border: 1px solid #ddd; padding: 20px; border-radius: 8px; background: #f9f9f9; }
    </style>
</head>
<body>
<div class="card">
    <h2>Admin - Add Payment</h2>

    <?php if ($payment_success): ?><div class="success"><?= $payment_success ?></div><?php endif; ?>
    <?php if ($error): ?><div class="error"><?= $error ?></div><?php endif; ?>

    <!-- Step 1: Fetch Orders -->
    <form method="POST">
        <label>Customer Phone:</label>
        <input type="text" name="customer_phone" value="<?= htmlspecialchars($selected_phone) ?>" required>
        <button type="submit" name="fetch_orders">Fetch Orders</button>
    </form>

    <?php if (!empty($orderOptions)): ?>
        <!-- Step 2: Submit Payment -->
        <form method="POST">
            <label>Select Order:</label>
            <select name="order_id" required>
                <?= $orderOptions ?>
            </select>

            <label>Amount Paid:</label>
            <input type="number" name="paid_amount" step="0.01" required>

            <label>Transaction Number:</label>
            <input type="text" name="transaction_number" required>

            <button type="submit" name="submit_payment">Submit Payment</button>
        </form>
    <?php endif; ?>
</div>
</body>
</html>
