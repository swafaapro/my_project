<?php 
include '../config.php';
session_start();

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

$order_id = $_GET['order_id'] ?? 0;

// Get order details
$order_stmt = $conn->prepare("
    SELECT o.*, c.full_name, c.email, c.phone, c.address 
    FROM orders o 
    JOIN customers c ON o.customer_id = c.customer_id 
    WHERE o.order_id = ?
");
$order_stmt->bind_param("i", $order_id);
$order_stmt->execute();
$order_result = $order_stmt->get_result();

if ($order_result->num_rows == 0) {
    header("Location: orders.php");
    exit();
}

$order = $order_result->fetch_assoc();
$order_stmt->close();

// Get order items
$items_stmt = $conn->prepare("
    SELECT i.*, s.service_name 
    FROM order_items i 
    JOIN services s ON i.service_id = s.service_id 
    WHERE i.order_id = ?
");
$items_stmt->bind_param("i", $order_id);
$items_stmt->execute();
$items_result = $items_stmt->get_result();

// Get clothes progress
$progress_stmt = $conn->prepare("SELECT * FROM clothes_progress WHERE order_id = ?");
$progress_stmt->bind_param("i", $order_id);
$progress_stmt->execute();
$progress_result = $progress_stmt->get_result();
$progress = $progress_result->fetch_assoc();
$progress_stmt->close();

// Update order status
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_status'])) {
    $status = $_POST['status'];
    $notes = $_POST['notes'];
    
    // Update order status
    $update_stmt = $conn->prepare("UPDATE orders SET status = ? WHERE order_id = ?");
    $update_stmt->bind_param("si", $status, $order_id);
    $update_stmt->execute();
    $update_stmt->close();
    
    // Update progress based on status
    $progress_update = $conn->prepare("UPDATE clothes_progress SET 
        washing_status = ?,
        drying_status = ?,
        ironing_status = ?,
        packaging_status = ?,
        notes = ?
        WHERE order_id = ?
    ");
    
    // Set progress based on status
    $washing = $progress['washing_status'];
    $drying = $progress['drying_status'];
    $ironing = $progress['ironing_status'];
    $packaging = $progress['packaging_status'];
    
    $now = date('Y-m-d H:i:s');
    
    if ($status == 'processing' && !$washing) {
        $washing = "Started washing at $now";
    } elseif ($status == 'processing' && !$drying) {
        $drying = "Started drying at $now";
    } elseif ($status == 'ready' && !$ironing) {
        $ironing = "Completed ironing at $now";
    } elseif ($status == 'delivered' && !$packaging) {
        $packaging = "Packaged and delivered at $now";
    }
    
    $progress_update->bind_param("sssssi", $washing, $drying, $ironing, $packaging, $notes, $order_id);
    $progress_update->execute();
    $progress_update->close();
    
    // Refresh progress data
    $progress_stmt = $conn->prepare("SELECT * FROM clothes_progress WHERE order_id = ?");
    $progress_stmt->bind_param("i", $order_id);
    $progress_stmt->execute();
    $progress_result = $progress_stmt->get_result();
    $progress = $progress_result->fetch_assoc();
    $progress_stmt->close();
    
    $success = "Order status updated successfully!";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Details - LaundryPro Admin</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Arial', sans-serif;
        }
        
        body {
            background-color: #f5f5f5;
        }
        
        header {
            background-color: #1e88e5;
            color: white;
            padding: 1rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .logo {
            font-size: 1.5rem;
            font-weight: bold;
        }
        
        nav ul {
            display: flex;
            list-style: none;
        }
        
        nav ul li {
            margin-left: 1.5rem;
        }
        
        nav ul li a {
            color: white;
            text-decoration: none;
            font-weight: 500;
        }
        
        .container {
            max-width: 1200px;
            margin: 2rem auto;
            padding: 0 2rem;
        }
        
        .back-link {
            display: inline-block;
            margin-bottom: 1rem;
            color: #1e88e5;
            text-decoration: none;
            font-weight: 500;
        }
        
        .card {
            background-color: white;
            border-radius: 8px;
            padding: 1.5rem;
            margin-bottom: 2rem;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }
        
        .card h2 {
            color: #1e88e5;
            margin-bottom: 1rem;
            padding-bottom: 0.5rem;
            border-bottom: 1px solid #eee;
        }
        
        .order-info {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1.5rem;
            margin-bottom: 1.5rem;
        }
        
        .info-group h3 {
            color: #555;
            font-size: 1rem;
            margin-bottom: 0.5rem;
        }
        
        .info-group p {
            font-size: 1.1rem;
            font-weight: 500;
        }
        
        .status {
            display: inline-block;
            padding: 0.3rem 0.8rem;
            border-radius: 20px;
            font-weight: 500;
        }
        
        .status-pending {
            background-color: #fff3e0;
            color: #e65100;
        }
        
        .status-processing {
            background-color: #e3f2fd;
            color: #1565c0;
        }
        
        .status-ready {
            background-color: #e8f5e9;
            color: #2e7d32;
        }
        
        .status-delivered {
            background-color: #f1f8e9;
            color: #558b2f;
        }
        
        .payment-status {
            display: inline-block;
            padding: 0.3rem 0.8rem;
            border-radius: 20px;
            font-weight: 500;
        }
        
        .payment-unpaid {
            background-color: #ffebee;
            color: #c62828;
        }
        
        .payment-paid {
            background-color: #e8f5e9;
            color: #2e7d32;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 1rem;
        }
        
        th, td {
            padding: 0.8rem;
            text-align: left;
            border-bottom: 1px solid #eee;
        }
        
        th {
            background-color: #f9f9f9;
            font-weight: 500;
            color: #555;
        }
        
        .total-row {
            font-weight: bold;
            font-size: 1.1rem;
        }
        
        .progress-steps {
            margin-top: 2rem;
        }
        
        .progress-step {
            display: flex;
            align-items: center;
            margin-bottom: 1.5rem;
            padding-bottom: 1.5rem;
            border-bottom: 1px solid #eee;
        }
        
        .progress-icon {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background-color: #e3f2fd;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 1.5rem;
            color: #1e88e5;
            font-size: 1.2rem;
            font-weight: bold;
            flex-shrink: 0;
        }
        
        .progress-info {
            flex: 1;
        }
        
        .progress-title {
            font-weight: 500;
            margin-bottom: 0.5rem;
            font-size: 1.1rem;
        }
        
        .progress-status {
            font-size: 0.9rem;
            color: #666;
        }
        
        .completed .progress-icon {
            background-color: #e8f5e9;
            color: #2e7d32;
        }
        
        .status-form {
            margin-top: 2rem;
        }
        
        .form-group {
            margin-bottom: 1.5rem;
        }
        
        label {
            display: block;
            margin-bottom: 0.5rem;
            color: #555;
            font-weight: 500;
        }
        
        select, textarea {
            width: 100%;
            padding: 0.8rem;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 1rem;
        }
        
        select:focus, textarea:focus {
            outline: none;
            border-color: #1e88e5;
        }
        
        textarea {
            min-height: 100px;
            resize: vertical;
        }
        
        .btn {
            display: inline-block;
            padding: 0.8rem 1.5rem;
            background-color: #1e88e5;
            color: white;
            border: none;
            border-radius: 4px;
            font-size: 1rem;
            font-weight: bold;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        
        .btn:hover {
            background-color: #1565c0;
        }
        
        .error {
            color: #e53935;
            margin-bottom: 1rem;
        }
        
        .success {
            color: #43a047;
            margin-bottom: 1rem;
        }
        
        @media (max-width: 768px) {
            .order-info {
                grid-template-columns: 1fr;
            }
            
            .progress-step {
                flex-direction: column;
                align-items: flex-start;
            }
            
            .progress-icon {
                margin-right: 0;
                margin-bottom: 1rem;
            }
        }
    </style>
</head>
<body>
    <header>
        <div class="logo">LaundryPro Admin</div>
        <nav>
            <ul>
                <li><a href="dashboard.php">Dashboard</a></li>
                <li><a href="orders.php">Orders</a></li>
                <li><a href="customers.php">Customers</a></li>
                <li><a href="services.php">Services</a></li>
                <li><a href="../logout.php">Logout</a></li>
            </ul>
        </nav>
    </header>
    
    <div class="container">
        <a href="orders.php" class="back-link">&larr; Back to Orders</a>
        
        <div class="card">
            <h2>Order #<?php echo $order['order_id']; ?></h2>
            
            <div class="order-info">
                <div class="info-group">
                    <h3>Customer Name</h3>
                    <p><?php echo htmlspecialchars($order['full_name']); ?></p>
                </div>
                
                <div class="info-group">
                    <h3>Order Date</h3>
                    <p><?php echo date('F j, Y, g:i a', strtotime($order['order_date'])); ?></p>
                </div>
                
                <div class="info-group">
                    <h3>Order Status</h3>
                    <p>
                        <span class="status status-<?php echo strtolower($order['status']); ?>">
                            <?php echo ucfirst($order['status']); ?>
                        </span>
                    </p>
                </div>
                
                <div class="info-group">
                    <h3>Payment Status</h3>
                    <p>
                        <span class="payment-status payment-<?php echo strtolower($order['payment_status']); ?>">
                            <?php echo ucfirst($order['payment_status']); ?>
                        </span>
                    </p>
                </div>
                
                <div class="info-group">
                    <h3>Total Amount</h3>
                    <p>$<?php echo number_format($order['total_amount'], 2); ?></p>
                </div>
                
                <div class="info-group">
                    <h3>Customer Phone</h3>
                    <p><?php echo htmlspecialchars($order['phone']); ?></p>
                </div>
            </div>
            
            <h3>Customer Information</h3>
            <div class="order-info">
                <div class="info-group">
                    <h3>Email</h3>
                    <p><?php echo htmlspecialchars($order['email']); ?></p>
                </div>
                
                <div class="info-group">
                    <h3>Address</h3>
                    <p><?php echo htmlspecialchars($order['address']); ?></p>
                </div>
            </div>
            
            <h3>Order Items</h3>
            <table>
                <thead>
                    <tr>
                        <th>Service</th>
                        <th>Quantity</th>
                        <th>Price</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($item = $items_result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($item['service_name']); ?></td>
                            <td><?php echo $item['quantity']; ?></td>
                            <td>$<?php echo number_format($item['item_price'], 2); ?></td>
                            <td>$<?php echo number_format($item['item_price'] * $item['quantity'], 2); ?></td>
                        </tr>
                    <?php endwhile; ?>
                    <tr class="total-row">
                        <td colspan="3">Total</td>
                        <td>$<?php echo number_format($order['total_amount'], 2); ?></td>
                    </tr>
                </tbody>
            </table>
            
            <div class="progress-steps">
                <h3>Clothes Progress</h3>
                
                <div class="progress-step <?php echo $progress['washing_status'] ? 'completed' : ''; ?>">
                    <div class="progress-icon">1</div>
                    <div class="progress-info">
                        <div class="progress-title">Washing</div>
                        <div class="progress-status">
                            <?php echo $progress['washing_status'] ? $progress['washing_status'] : "Pending"; ?>
                        </div>
                    </div>
                </div>
                
                <div class="progress-step <?php echo $progress['drying_status'] ? 'completed' : ''; ?>">
                    <div class="progress-icon">2</div>
                    <div class="progress-info">
                        <div class="progress-title">Drying</div>
                        <div class="progress-status">
                            <?php echo $progress['drying_status'] ? $progress['drying_status'] : "Pending"; ?>
                        </div>
                    </div>
                </div>
                
                <div class="progress-step <?php echo $progress['ironing_status'] ? 'completed' : ''; ?>">
                    <div class="progress-icon">3</div>
                    <div class="progress-info">
                        <div class="progress-title">Ironing</div>
                        <div class="progress-status">
                            <?php echo $progress['ironing_status'] ? $progress['ironing_status'] : "Pending"; ?>
                        </div>
                    </div>
                </div>
                
                <div class="progress-step <?php echo $progress['packaging_status'] ? 'completed' : ''; ?>">
                    <div class="progress-icon">4</div>
                    <div class="progress-info">
                        <div class="progress-title">Packaging</div>
                        <div class="progress-status">
                            <?php echo $progress['packaging_status'] ? $progress['packaging_status'] : "Pending"; ?>
                        </div>
                    </div>
                </div>
            </div>
            
            <?php if (isset($success)): ?>
                <p class="success"><?php echo $success; ?></p>
            <?php endif; ?>
            
            <div class="status-form">
                <h3>Update Order Status</h3>
                <form action="order_details.php?order_id=<?php echo $order_id; ?>" method="POST">
                    <div class="form-group">
                        <label for="status">Status</label>
                        <select id="status" name="status" required>
                            <option value="pending" <?php echo $order['status'] == 'pending' ? 'selected' : ''; ?>>Pending</option>
                            <option value="processing" <?php echo $order['status'] == 'processing' ? 'selected' : ''; ?>>Processing</option>
                            <option value="ready" <?php echo $order['status'] == 'ready' ? 'selected' : ''; ?>>Ready for Pickup</option>
                            <option value="delivered" <?php echo $order['status'] == 'delivered' ? 'selected' : ''; ?>>Delivered</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="notes">Notes</label>
                        <textarea id="notes" name="notes" placeholder="Add any notes about the order..."><?php echo htmlspecialchars($progress['notes'] ?? ''); ?></textarea>
                    </div>
                    
                    <button type="submit" name="update_status" class="btn">Update Status</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>