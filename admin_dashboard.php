
<?php


// Kwenye dashboard.php
include 'db_connection.php';

$statusCounts = [
    'Pending' => 0,
    'In Progress' => 0,
    'Completed' => 0
];

$sql = "SELECT status, COUNT(*) AS count FROM orders GROUP BY status";
$result = $conn->query($sql);

while ($row = $result->fetch_assoc()) {
    $status = $row['status'];
    if (isset($statusCounts[$status])) {
        $statusCounts[$status] = $row['count'];
    }
}


session_start();
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: admin_login.php");
    exit();
}

if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: admin_login.php");
    exit();
}

$conn = new mysqli("localhost", "root", "", "laundry_management_system");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);

    
}



// Update order status
// Step 1: Update order status
if (isset($_POST['update_order']) && !empty($_POST['order_id']) && !empty($_POST['status'])) {
    $order_id = intval($_POST['order_id']);
    $new_status = $_POST['status'];

    // Step 1: Update order
    $stmt = $conn->prepare("UPDATE orders SET status = ? WHERE order_id = ?");
    $stmt->bind_param("si", $new_status, $order_id);
    $stmt->execute();
    $stmt->close();

    // Step 2: Get customer_id
    $cust_stmt = $conn->prepare("SELECT customer_id FROM orders WHERE order_id = ?");
    $cust_stmt->bind_param("i", $order_id);
    $cust_stmt->execute();
    $cust_result = $cust_stmt->get_result();
    $cust_row = $cust_result->fetch_assoc();

    if ($cust_row) {
        $customer_id = $cust_row['customer_id'];
        $cust_stmt->close();

        // Set progress status values based on selected order status
        $washing = $drying = $ironing = $packaging = 'Not Started';

        switch ($new_status) {
            case 'In Progress':
                $washing = 'In Progress';
                break;
            case 'Drying':
                $washing = 'Completed';
                $drying = 'In Progress';
                break;
            case 'Ironing':
                $washing = 'Completed';
                $drying = 'Completed';
                $ironing = 'In Progress';
                break;
            case 'Packaging':
                $washing = 'Completed';
                $drying = 'Completed';
                $ironing = 'Completed';
                $packaging = 'In Progress';
                break;
            case 'Completed':
                $washing = $drying = $ironing = $packaging = 'Completed';
                break;
        }

        // Check if progress exists
        $check_stmt = $conn->prepare("SELECT * FROM clothes_progress WHERE order_id = ? AND customer_id = ?");
        $check_stmt->bind_param("ii", $order_id, $customer_id);
        $check_stmt->execute();
        $result = $check_stmt->get_result();

        if ($result->num_rows > 0) {
            // Update existing progress
            $update_stmt = $conn->prepare("UPDATE clothes_progress 
                SET washing_status = ?, drying_status = ?, ironing_status = ?, packaging_status = ? 
                WHERE order_id = ? AND customer_id = ?");
            $update_stmt->bind_param("ssssii", $washing, $drying, $ironing, $packaging, $order_id, $customer_id);
            $update_stmt->execute();
            $update_stmt->close();
        } else {
            // Insert new progress
            $insert_stmt = $conn->prepare("INSERT INTO clothes_progress 
                (order_id, customer_id, washing_status, drying_status, ironing_status, packaging_status) 
                VALUES (?, ?, ?, ?, ?, ?)");
            $insert_stmt->bind_param("iissss", $order_id, $customer_id, $washing, $drying, $ironing, $packaging);
            $insert_stmt->execute();
            $insert_stmt->close();
        }
    } else {
        echo "<p style='color:red'>Order ID $order_id haikupatikana kwenye database.</p>";
    }

    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}






// Approve payment with validation and log payment date
$approval_message = '';
if (isset($_POST['approve_payment'])) {
    $order_id = $_POST['order_id'];
    $code = $_POST['payment_code'];

    if (preg_match('/^[A-Z0-9.]{20}$/', $code)) {
        $payment_date = date('Y-m-d H:i:s');

        $stmt = $conn->prepare("UPDATE orders SET payment_status = 'Paid', payment_date = ? WHERE order_id = ?");
        $stmt->bind_param("si", $payment_date, $order_id);
        $stmt->execute();

        $stmt2 = $conn->prepare("SELECT customer_id FROM orders WHERE order_id = ?");
        $stmt2->bind_param("i", $order_id);
        $stmt2->execute();
        $result = $stmt2->get_result();
        $row = $result->fetch_assoc();
        $customer_id = $row['customer_id'];

        $stmt3 = $conn->prepare("UPDATE customers SET payment_status = 'Paid' WHERE customer_id = ?");
        $stmt3->bind_param("i", $customer_id);
        $stmt3->execute();

        $approval_message = "Payment approved successfully, marked as Paid and date recorded.";
    } else {
        $approval_message = "Not Recognized: Please enter a valid 20-character code (uppercase letters, numbers, and dots only).";
    }
}

// Send message
if (isset($_POST['send_message'])) {
    $customer_id = $_POST['customer_id'];
    $message = $_POST['message'];

    $stmt = $conn->prepare("INSERT INTO messages (customer_id, message) VALUES (?, ?)");
    $stmt->bind_param("is", $customer_id, $message);
    $stmt->execute();

    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

// ✅ Delete customer with all dependencies
if (isset($_POST['delete_customer'])) {
    $customer_id = $_POST['customer_id'];

    // 1. Get all order_ids for this customer
    $stmt_orders = $conn->prepare("SELECT order_id FROM orders WHERE customer_id = ?");
    $stmt_orders->bind_param("i", $customer_id);
    $stmt_orders->execute();
    $result_orders = $stmt_orders->get_result();

    while ($order = $result_orders->fetch_assoc()) {
        $order_id = $order['order_id'];

        // 2. Delete order_items
        $stmt_items = $conn->prepare("DELETE FROM order_items WHERE order_id = ?");
        $stmt_items->bind_param("i", $order_id);
        $stmt_items->execute();
    }

    // 3. Delete clothes_progress
    $stmt_cp = $conn->prepare("DELETE FROM clothes_progress WHERE customer_id = ?");
    $stmt_cp->bind_param("i", $customer_id);
    $stmt_cp->execute();

    // 4. Delete orders
    $stmt_del_orders = $conn->prepare("DELETE FROM orders WHERE customer_id = ?");
    $stmt_del_orders->bind_param("i", $customer_id);
    $stmt_del_orders->execute();

    // 5. Delete messages
    $stmt_msgs = $conn->prepare("DELETE FROM messages WHERE customer_id = ?");
    $stmt_msgs->bind_param("i", $customer_id);
    $stmt_msgs->execute();

    // 6. Delete customer
    $stmt_del_cust = $conn->prepare("DELETE FROM customers WHERE customer_id = ?");
    $stmt_del_cust->bind_param("i", $customer_id);
    $stmt_del_cust->execute();

    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}


// Add new admin
if (isset($_POST['add_admin'])) {
    $new_username = $_POST['new_admin_username'];
    $new_password = password_hash($_POST['new_admin_password'], PASSWORD_DEFAULT);


    $stmt = $conn->prepare("INSERT INTO admins (username, password) VALUES (?, ?)");
    $stmt->bind_param("ss", $new_username, $new_password);

    if ($stmt->execute()) {
        $admin_message = "Admin added successfully.";
    } else {
        $admin_message = "Failed to add admin: " . $stmt->error;
    }
}



// Fetch orders
$orders = $conn->query("SELECT o.*, c.full_name AS customer_name, c.phone, c.customer_id, c.payment_status AS customer_payment_status 
                        FROM orders o 
                        JOIN customers c ON o.customer_id = c.customer_id");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard - Laundry System</title>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
       /* Reset & Base */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: 'Arial', sans-serif;
}

body {
    background-color: #f5f5f5;
    color: #333;
    padding: 2rem;
}

/* Header / Logout link */
h2 a {
    float: right;
    margin-right: 20px;
    text-decoration: none;
    color: #1e88e5;
    font-weight: 600;
}

h2 a:hover {
    text-decoration: underline;
}

/* Headings */
h2, h3 {
    color: #333;
    margin-bottom: 1rem;
}

/* Messages */
.success {
    color: #2e7d32;
    background-color: #c8e6c9;
    padding: 0.5rem 1rem;
    border-radius: 4px;
    margin-bottom: 1rem;
    font-weight: 600;
}

.error {
    color: #c62828;
    background-color: #ffcdd2;
    padding: 0.5rem 1rem;
    border-radius: 4px;
    margin-bottom: 1rem;
    font-weight: 600;
}

/* Forms inside table and admin add form */
form {
    margin: 0;
}

form input[type="text"],
form input[type="password"],
form select,
form textarea,
.payment-input,
.message-box {
    padding: 0.4rem 0.6rem;
    border: 1px solid #ddd;
    border-radius: 4px;
    font-size: 0.9rem;
    font-family: inherit;
}

form select.status-select {
    min-width: 110px;
}

form input.payment-input {
    width: 140px;
    text-transform: uppercase;
}

form textarea.message-box {
    width: 160px;
    resize: vertical;
    font-family: inherit;
}

form button {
    background-color: #1e88e5;
    color: white;
    border: none;
    border-radius: 4px;
    padding: 0.4rem 0.8rem;
    cursor: pointer;
    font-weight: 600;
    font-size: 0.9rem;
    transition: background-color 0.3s ease;
    margin-top: 0.2rem;
}

form button:hover {
    background-color: #1565c0;
}

/* Table styling */
table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 1.5rem;
    box-shadow: 0 2px 5px rgba(0,0,0,0.1);
}

table thead {
    background-color: #1e88e5;
    color: white;
}

table th, table td {
    padding: 0.8rem 1rem;
    border: 1px solid #ddd;
    text-align: left;
    vertical-align: middle;
    font-size: 0.9rem;
}

table tbody tr:nth-child(even) {
    background-color: #f9f9f9;
}

/* Delete button */
.delete-button {
    background-color: #c62828;
    padding: 0.4rem 0.8rem;
}

.delete-button:hover {
    background-color: #b71c1c;
}




#orderStatusChart {
    width: 400px !important;
    height: 400px !important;
    display: block;
    margin: 0 auto;
}


 body { font-family: Arial, sans-serif; margin: 20px; background: #f9f9f9; }
        h2, h3 { text-align: center; }
        .notification { position: relative; display: inline-block; margin: 0 20px; }
        .notification .badge {
            position: absolute; top: -10px; right: -10px;
            background: red; color: white; border-radius: 50%;
            padding: 5px 10px; font-size: 12px; font-weight: bold;
        }
        table { width: 100%; border-collapse: collapse; margin: 20px auto; max-width: 900px; background: white; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: center; }
        th { background-color: #007BFF; color: white; }
        .chart-container { max-width: 700px; margin: 30px auto; background: white; padding: 20px; border-radius: 8px; box-shadow: 0 0 8px rgba(0,0,0,0.1);}


/* Container ya chart iwe na max-width na max-height ili isizidi ukubwa fulani */
.chart-container {
    max-width: 400px;    /* upo kwa ukubwa wa max width unayotaka */
    max-height: 300px;   /* max height kwa chart yako */
    margin: 20px auto;   /* kuweka katikati na padding kidogo */
    padding: 10px;
    border: 1px solid #ddd; /* line nyepesi kama boundary */
    border-radius: 8px;
    background-color: #fafafa; /* background nyepesi */
}

/* Canvas ndani ya container iwe full width na height */
.chart-container canvas {
    width: 100% !important;
    height: auto !important;
    display: block;
}


    </style>
	
	
	
	
	
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin Dashboard</title>
  <style>
    body {
      margin: 0;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background-color: #f4f6f9;
    }
    header {
      background-color: #343a40;
      color: white;
      padding: 15px 30px;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }
    .logo {
      font-size: 20px;
      font-weight: bold;
    }
    nav ul {
      list-style: none;
      margin: 0;
      padding: 0;
      display: flex;
    }
    nav ul li {
      margin-left: 20px;
    }
    nav ul li a {
      color: white;
      text-decoration: none;
      font-size: 15px;
      transition: color 0.3s ease;
    }
    nav ul li a:hover {
      color: #ffc107;
    }
    h2, h3 {
      text-align: center;
      margin-top: 30px;
      color: #333;
    }
    .notification-bar {
      display: flex;
      justify-content: flex-end;
      padding: 10px 30px;
    }
    .notification-bar i {
      font-size: 22px;
      color: #333;
      position: relative;
    }
    .notification-bar span {
      position: absolute;
      top: -6px;
      right: -10px;
      background: red;
      color: white;
      font-size: 12px;
      border-radius: 50%;
      padding: 3px 6px;
    }
    .form-section {
      width: 100%;
      max-width: 400px;
      margin: 0 auto 40px auto;
      background: #fff;
      padding: 20px;
      border-radius: 10px;
      box-shadow: 0 1px 6px rgba(0,0,0,0.1);
    }
    .form-section input,
    .form-section button {
      width: 100%;
      padding: 10px;
      margin-bottom: 10px;
      border-radius: 6px;
      border: 1px solid #ccc;
      font-size: 14px;
    }
    .form-section button {
      background-color: #4e73df;
      color: white;
      border: none;
      cursor: pointer;
      transition: background 0.3s ease;
    }
    .form-section button:hover {
      background-color: #2e59d9;
    }
    .message-box {
      width: 100%;
      padding: 8px;
      border-radius: 5px;
      border: 1px solid #ccc;
    }
    .status-select, .payment-input {
      padding: 6px;
      border-radius: 4px;
      border: 1px solid #aaa;
    }
    .delete-button {
      background-color: #e74a3b;
      color: white;
      border: none;
      padding: 6px 10px;
      border-radius: 4px;
      cursor: pointer;
    }
    .delete-button:hover {
      background-color: #c0392b;
    }
    table {
      width: 95%;
      margin: 0 auto 50px auto;
      border-collapse: collapse;
      background-color: #fff;
      box-shadow: 0 1px 5px rgba(0,0,0,0.1);
    }
    table thead {
      background-color: #4e73df;
      color: white;
    }
    table th, table td {
      padding: 10px;
      border: 1px solid #ddd;
      text-align: center;
    }
    .success {
      color: green;
      text-align: center;
      font-weight: bold;
    }
    .error {
      color: red;
      text-align: center;
      font-weight: bold;
    }
  </style>
  <!-- FontAwesome for icons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>

<header>
  <div class="logo">🧺 Laundry Admin Panel</div>
  <nav>
    <ul>
      <li><a href="admin_dashboard.php">Dashboard</a></li>
      <li><a href="admin_analytics.php">Reports</a></li>
      <li><a href="admin_settings.php">Settings</a></li>
	  <li><a href="logout.php">Logout</a></li>
    </ul>
  </nav>
</header>

<h2>Welcome, <?= htmlspecialchars($_SESSION['admin_username']) ?></h2>

<div class="notification-bar">
  <div style="position: relative;">
    <i class="fas fa-bell"></i>
    <span id="order-notification">0</span>
  </div>
</div>

<h3>Customer Orders</h3>

<?php if (!empty($admin_message)): ?>
  <p class="<?= strpos($admin_message, 'successfully') !== false ? 'success' : 'error' ?>">
    <?= htmlspecialchars($admin_message) ?>
  </p>
<?php endif; ?>

<h3>Add New Admin</h3>
<div class="form-section">
  <form method="post">
    <input type="text" name="new_admin_username" placeholder="New admin username" required>
    <input type="password" name="new_admin_password" placeholder="New admin password" required>
    <button type="submit" name="add_admin">Add Admin</button>
  </form>
</div>

<?php if ($approval_message): ?>
  <p class="<?= strpos($approval_message, 'Not Recognized') === 0 ? 'error' : 'success' ?>">
    <?= htmlspecialchars($approval_message) ?>
  </p>
<?php endif; ?>

<table>
  <thead>
    <tr>
      <th>Order ID</th>
      <th>Items</th>
      <th>Customer</th>
      <th>Phone</th>
      <th>Status</th>
      <th>Update Status</th>
      <th>Payment Status</th>
      <th>Approve Payment</th>
      <th>Send Message</th>
      <th>Actions</th>
    </tr>
  </thead>
  <tbody>
    <?php while ($row = $orders->fetch_assoc()): ?>
      <tr>
        <td><?= htmlspecialchars($row['order_id']) ?></td>
        <td><?= htmlspecialchars($row['items'] ?? 'No items listed') ?></td>
        <td><?= htmlspecialchars($row['customer_name']) ?></td>
        <td><?= htmlspecialchars($row['phone']) ?></td>
        <td><strong><?= htmlspecialchars($row['status']) ?></strong></td>
        <td>
          <form method="post">
            <input type="hidden" name="order_id" value="<?= $row['order_id'] ?>">
            <select name="status" class="status-select">
              <option value="Pending" <?= $row['status'] === 'Pending' ? 'selected' : '' ?>>Pending</option>
              <option value="In Progress" <?= $row['status'] === 'In Progress' ? 'selected' : '' ?>>In Progress</option>
              <option value="Completed" <?= $row['status'] === 'Completed' ? 'selected' : '' ?>>Completed</option>
            </select>
            <button type="submit" name="update_order">Update</button>
          </form>
        </td>
        <td><strong><?= htmlspecialchars($row['payment_status'] ?? $row['customer_payment_status'] ?? 'Not Paid') ?></strong></td>
        <td>
          <form method="post">
            <input type="hidden" name="order_id" value="<?= $row['order_id'] ?>">
            <input type="text" name="payment_code" class="payment-input" placeholder="20-char code"
              pattern="[A-Z0-9.]{20}" maxlength="20" minlength="20" required style="text-transform:uppercase;">
            <button type="submit" name="approve_payment">Approve</button>
          </form>
        </td>
        <td>
          <form method="post">
            <input type="hidden" name="customer_id" value="<?= $row['customer_id'] ?>">
            <textarea name="message" placeholder="Write message..." rows="2" class="message-box"></textarea><br>
            <button type="submit" name="send_message">Send</button>
          </form>
        </td>
        <td>
          <form method="post"
            onsubmit="return confirm('Are you sure you want to delete this customer? All their orders will also be permanently deleted.');">
            <input type="hidden" name="customer_id" value="<?= $row['customer_id'] ?>">
            <button type="submit" name="delete_customer" class="delete-button">Delete</button>
          </form>
        </td>
      </tr>
    <?php endwhile; ?>
  </tbody>
</table>

</body>
</html>


<script>
   function checkNewOrders() {
    fetch('check_new_orders.php')
        .then(response => response.text())
        .then(count => {
            document.getElementById('order-notification').textContent = count;
        })
        .catch(error => {
            console.error('Error:', error);
        });
}

// Check every 10 seconds
setInterval(checkNewOrders, 10000);
checkNewOrders(); // Check immediately

</script>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    fetch('orders_per_month.php')
        .then(res => res.json())
        .then(data => {
            const ctx = document.getElementById('ordersPerMonthChart').getContext('2d');
            new Chart(ctx, {
                type: 'bar', // Change to 'line' for line chart
                data: {
                    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun',
                             'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                    datasets: [{
                        label: 'Orders',
                        data: data,
                        backgroundColor: 'rgba(54, 162, 235, 0.7)',
                        borderColor: 'rgba(54, 162, 235, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: { display: false }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            stepSize: 1
                        }
                    }
                }
            });
        });
</script>


<h3>Orders Awaiting Payment</h3>
<?php
$conn = new mysqli("localhost", "root", "", "laundry_management_system");

$awaiting_stmt = $conn->prepare("SELECT order_id, customer_phone, total_amount FROM orders WHERE payment_status = 'Awaiting'");
$awaiting_stmt->execute();
$awaiting_result = $awaiting_stmt->get_result();

if ($awaiting_result->num_rows > 0):
    while ($row = $awaiting_result->fetch_assoc()):
?>
    <div style="border:1px solid #007bff; padding:10px; margin:10px 0; background-color:#e9f7fe;">
        <strong>Order #<?php echo $row['order_id']; ?></strong><br>
        Phone: <?php echo $row['customer_phone']; ?><br>
        Amount: Tsh <?php echo number_format($row['total_amount'], 2); ?><br>
        <em>Awaiting payment confirmation</em>
    </div>
<?php
    endwhile;
else:
    echo "<p>No orders are awaiting payment.</p>";
endif;
?>





</body>
</html>
