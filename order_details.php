<?php 
include 'config.php';
session_start();

if (!isset($_SESSION['customer_id'])) {
    header("Location: login.php");
    exit();
}

$customer_id = $_SESSION['customer_id'];
$order_id = $_GET['order_id'] ?? 0;

// Verify order belongs to customer
$order_stmt = $conn->prepare("SELECT o.* FROM orders o WHERE o.order_id = ? AND o.customer_id = ?");
$order_stmt->bind_param("ii", $order_id, $customer_id);
$order_stmt->execute();
$order_result = $order_stmt->get_result();

if ($order_result->num_rows == 0) {
    header("Location: dashboard.php");
    exit();
}

$order = $order_result->fetch_assoc();
$order_stmt->close();

// Get order items
$items_stmt = $conn->prepare("
    SELECT i.*, s.service_name, s.price_per_item 
    FROM order_items i 
    JOIN services s ON i.service_id = s.service_id 
    WHERE i.order_id = ?
");
$items_stmt->bind_param("i", $order_id);
$items_stmt->execute();
$items_result = $items_stmt->get_result();

// Get clothes progress
$progress_stmt = $conn->prepare("SELECT * FROM clothes_progress WHERE order_id = ? AND customer_id = ?");
$progress_stmt->bind_param("ii", $order_id, $customer_id);
$progress_stmt->execute();
$progress_result = $progress_stmt->get_result();
$progress = $progress_result->fetch_assoc();
$progress_stmt->close();

// Process payment submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit_payment'])) {
    $phone = $_POST['phone'];
    
    // Validate Tanzania phone number
    if (empty($phone) || !preg_match('/^0[67][0-9]{8}$/', $phone)) {
        $payment_error = "Tafadhali weka namba ya simu sahihi (Anza na 06 au 07, tarakimu 10)";
    } else {
        // Save payment info (status remains 'pending' until admin verifies)
        $payment_stmt = $conn->prepare("UPDATE orders SET payment_method = 'Mobile Money', payment_phone = ?, payment_status = 'pending' WHERE order_id = ?");
        $payment_stmt->bind_param("si", $phone, $order_id);
        
        if ($payment_stmt->execute()) {
            $payment_success = "Taarifa za malipo zimepokelewa! Tutakuthibitishia kwa simu baada ya kupokea malipo.";
            $order['payment_status'] = 'pending';
            $order['payment_phone'] = $phone;
            $order['payment_method'] = 'Mobile Money';
        } else {
            $payment_error = "Hitilafu ya mfumo: " . $conn->error;
        }
        
        $payment_stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Details - LaundryPro</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Arial', sans-serif;
        }
        
        body {
            background-color: #f5f7fa;
            color: #333;
            line-height: 1.6;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
        
        .back-link {
            display: inline-block;
            margin-bottom: 20px;
            color: #1e88e5;
            text-decoration: none;
            font-weight: 500;
        }
        
        .back-link:hover {
            text-decoration: underline;
        }
        
        .card {
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.05);
            padding: 25px;
            margin-bottom: 30px;
        }
        
        .card h2 {
            color: #1e88e5;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 1px solid #eee;
        }
        
        .order-info {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 25px;
        }
        
        .info-group h3 {
            color: #555;
            font-size: 0.95rem;
            margin-bottom: 5px;
        }
        
        .info-group p {
            font-size: 1.05rem;
        }
        
        /* Payment Section Styles */
        .payment-section {
            margin-top: 30px;
            padding: 25px;
            background-color: #f8fafc;
            border-radius: 8px;
            border: 1px solid #e2e8f0;
        }
        
        .payment-section h3 {
            color: #1e88e5;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 1px solid #e2e8f0;
            font-size: 1.25rem;
        }
        
        .payment-status {
            display: inline-block;
            padding: 0.4rem 1rem;
            border-radius: 20px;
            font-weight: 600;
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .payment-status.pending {
            background-color: #fffbeb;
            color: #d97706;
            border: 1px solid #fcd34d;
        }
        
        .payment-status.unpaid {
            background-color: #fee2e2;
            color: #dc2626;
            border: 1px solid #fca5a5;
        }
        
        .payment-status.paid {
            background-color: #dcfce7;
            color: #16a34a;
            border: 1px solid #86efac;
        }
        
        .alert {
            padding: 12px 15px;
            border-radius: 6px;
            margin-bottom: 20px;
            font-size: 0.95rem;
        }
        
        .alert.success {
            background-color: #dcfce7;
            color: #166534;
            border: 1px solid #bbf7d0;
        }
        
        .alert.error {
            background-color: #fee2e2;
            color: #991b1b;
            border: 1px solid #fca5a5;
        }
        
        .payment-instructions {
            background-color: #eff6ff;
            border-left: 4px solid #1e88e5;
            padding: 20px;
            margin: 20px 0;
            border-radius: 6px;
        }
        
        .payment-instructions h4 {
            color: #1e40af;
            margin-bottom: 12px;
            font-size: 1rem;
        }
        
        .payment-instructions ol {
            padding-left: 20px;
            margin-top: 10px;
        }
        
        .payment-instructions li {
            margin-bottom: 8px;
            color: #4b5563;
        }
        
        .payment-instructions strong {
            color: #1e40af;
            font-weight: 600;
        }
        
        .admin-note {
            background-color: #fffbeb;
            border-left: 4px solid #f59e0b;
            padding: 15px;
            margin: 15px 0;
            border-radius: 6px;
            font-size: 0.9rem;
            color: #78350f;
        }
        
        .admin-note strong {
            color: #92400e;
        }
        
        .payment-form .form-group {
            margin-bottom: 20px;
        }
        
        .payment-form label {
            display: block;
            margin-bottom: 8px;
            color: #4b5563;
            font-weight: 500;
            font-size: 0.95rem;
        }
        
        .payment-form input[type="tel"],
        .payment-form input[type="text"] {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid #d1d5db;
            border-radius: 6px;
            font-size: 1rem;
            transition: all 0.3s;
        }
        
        .payment-form input[type="tel"]:focus,
        .payment-form input[type="text"]:focus {
            outline: none;
            border-color: #1e88e5;
            box-shadow: 0 0 0 3px rgba(30, 136, 229, 0.1);
        }
        
        .btn {
            display: inline-block;
            background-color: #1e88e5;
            color: white;
            padding: 12px 25px;
            border: none;
            border-radius: 6px;
            font-size: 1rem;
            font-weight: 500;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        
        .btn:hover {
            background-color: #1565c0;
        }
        
        .btn-primary {
            background-color: #1e88e5;
        }
        
        .btn-primary:hover {
            background-color: #1565c0;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        
        th, td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #eee;
        }
        
        th {
            background-color: #f9f9f9;
            font-weight: 500;
            color: #555;
        }
        
        .progress-steps {
            margin-top: 30px;
        }
        
        .progress-step {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 20px;
            border-bottom: 1px solid #eee;
        }
        
        .progress-icon {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background-color: #e3f2fd;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 15px;
            color: #1e88e5;
            font-weight: bold;
            flex-shrink: 0;
        }
        
        .progress-info {
            flex: 1;
        }
        
        .progress-title {
            font-weight: 500;
            margin-bottom: 5px;
        }
        
        .progress-status {
            font-size: 0.9rem;
            color: #666;
        }
        
        .completed .progress-icon {
            background-color: #e8f5e9;
            color: #2e7d32;
        }
        
        @media (max-width: 768px) {
            .container {
                padding: 15px;
            }
            
            .card {
                padding: 20px;
            }
            
            .order-info {
                grid-template-columns: 1fr;
            }
            
            .payment-section {
                padding: 20px;
            }
            
            th, td {
                padding: 10px;
                font-size: 0.9rem;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <a href="dashboard.php" class="back-link">&larr; Back to Dashboard</a>
        
        <div class="card">
            <h2>Order no:<?php echo $order['order_id']; ?></h2>
            
            <div class="order-info">
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
                    <p>Tsh <?php echo number_format($order['total_amount'], 0); ?></p>
                </div>
            </div>
            
            <h3>Order Items</h3>
            <table>
                <thead>
                    <tr>
                        <th>Service</th>
                        <th>Quantity</th>
                        <th>Unit Price</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($item = $items_result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($item['service_name']); ?></td>
                            <td><?php echo $item['quantity']; ?></td>
                            <td>Tsh <?php echo number_format($item['price_per_item'], 0); ?></td>
                            <td>Tsh <?php echo number_format($item['price_per_item'] * $item['quantity'], 0); ?></td>
                        </tr>
                    <?php endwhile; ?>
                    <tr class="total-row">
                        <td colspan="3"><strong>Total</strong></td>
                        <td><strong>Tsh <?php echo number_format($order['total_amount'], 0); ?></strong></td>
                    </tr>
                </tbody>
            </table>
            
            <div class="progress-steps">
                <h3>Order Progress</h3>
                
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
            
            <!-- PAYMENT SECTION -->
            <div class="payment-section">
                <?php if ($order['payment_status'] == 'unpaid' || $order['payment_status'] == 'pending'): ?>
                    <h3>Make Payment</h3>
                    
                    <?php if (isset($payment_success)): ?>
                        <div class="alert success"><?php echo $payment_success; ?></div>
                    <?php endif; ?>
                    
                    <?php if (isset($payment_error)): ?>
                        <div class="alert error"><?php echo $payment_error; ?></div>
                    <?php endif; ?>
                    
                    <div class="payment-instructions">
                        <h4>Payment Instructions:</h4>
                        <ol>
                            <li>Send <strong>Tsh <?php echo number_format($order['total_amount'], 0); ?></strong> to <strong>0689746133</strong> via Airtelmoney</li>
                            <li>Enter the phone number you used to pay below</li>
                            <li>Our admin will manually verify your payment</li>
                            <li>You'll receive SMS confirmation once verified</li>
                        </ol>
                    </div>
                    
                    <div class="admin-note">
                        <strong>Note:</strong> Payment will show as <span class="payment-status pending">Pending</span> until manually verified by admin.
                    </div>
    
                    
                <?php elseif ($order['payment_status'] == 'paid'): ?>
                    <h3>Payment Information</h3>
                    
                    <div class="payment-info">
                        <p><strong>Status:</strong> <span class="payment-status paid">Paid</span></p>
                        <p><strong>Method:</strong> <?php echo $order['payment_method']; ?></p>
                        <p><strong>Phone:</strong> <?php echo htmlspecialchars($order['payment_phone']); ?></p>
                        <p><strong>Verified On:</strong> <?php echo date('j M Y H:i', strtotime($order['payment_date'])); ?></p>
                    </div>
                <?php endif; ?>
            </div>
            <!-- END PAYMENT SECTION -->
        </div>
    </div>
</body>
</html>