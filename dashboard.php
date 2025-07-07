<?php 
include 'config.php';
session_start();

if (!isset($_SESSION['customer_id'])) {
    header("Location: login.php");
    exit();
}

$customer_id = $_SESSION['customer_id'];
$full_name = $_SESSION['full_name'];

// Get customer orders
$orders_query = "SELECT * FROM orders WHERE customer_id = ? ORDER BY order_date DESC";
$stmt = $conn->prepare($orders_query);
$stmt->bind_param("i", $customer_id);
$stmt->execute();
$orders_result = $stmt->get_result();

// Get services for order form
$services_query = "SELECT * FROM services";
$services_result = $conn->query($services_query);




// Process new order
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['place_order'])) {

    $services = $_POST['services'];
    $quantities = $_POST['quantities'];
    
    // Calculate total amount
    $total_amount = 0;
    $order_items = [];
    
    foreach ($services as $index => $service_id) {
        $quantity = $quantities[$index];
        
        if ($quantity > 0) {
            $service_stmt = $conn->prepare("SELECT price_per_item FROM services WHERE service_id = ?");
            $service_stmt->bind_param("i", $service_id);
            $service_stmt->execute();
            $service_stmt->bind_result($price);
            $service_stmt->fetch();
            $service_stmt->close();
            
            $item_total = $price * $quantity;
            $total_amount += $item_total;
            
            $order_items[] = [
                'service_id' => $service_id,
                'quantity' => $quantity,
                'price' => $price
            ];
        }
    }
    
    if ($total_amount > 0) {
// Get customer's phone number
$phone_stmt = $conn->prepare("SELECT phone FROM customers WHERE customer_id = ?");

$phone_stmt->bind_param("i", $customer_id);
$phone_stmt->execute();
$phone_stmt->bind_result($customer_phone);
$phone_stmt->fetch();
$phone_stmt->close();

// Insert order with customer phone
$order_stmt = $conn->prepare("INSERT INTO orders (customer_id, customer_phone, total_amount, payment_status) VALUES (?, ?, ?, 'Unpaid')");
$order_stmt->bind_param("isd", $customer_id, $customer_phone, $total_amount);
$order_stmt->execute();
$order_id = $conn->insert_id;
$order_stmt->close();

        
        // Insert order items
        foreach ($order_items as $item) {
            $item_stmt = $conn->prepare("INSERT INTO order_items (order_id, service_id, quantity, item_price) VALUES (?, ?, ?, ?)");
            $item_stmt->bind_param("iiid", $order_id, $item['service_id'], $item['quantity'], $item['price']);
            $item_stmt->execute();
            $item_stmt->close();
        }
        
        // Initialize clothes progress
        $progress_stmt = $conn->prepare("INSERT INTO clothes_progress (order_id, customer_id) VALUES (?, ?)");
        $progress_stmt->bind_param("ii", $order_id, $customer_id);
        $progress_stmt->execute();
        $progress_stmt->close();
        
        $success = "Order placed successfully!";
    } else {
        $error = "Please select at least one service with quantity greater than 0";
    }
}

// Process profile update
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_profile'])) {
    $full_name = $_POST['full_name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    
    $update_stmt = $conn->prepare("UPDATE customers SET full_name = ?, email = ?, phone = ?, address = ? WHERE customer_id = ?");
    $update_stmt->bind_param("ssssi", $full_name, $email, $phone, $address, $customer_id);
    
    if ($update_stmt->execute()) {
        $_SESSION['full_name'] = $full_name;
        $profile_success = "Profile updated successfully!";
    } else {
        $profile_error = "Error updating profile: " . $conn->error;
    }
    
    $update_stmt->close();
}

// Get customer details for profile
$customer_stmt = $conn->prepare("SELECT full_name, email, phone, address FROM customers WHERE customer_id = ?");
$customer_stmt->bind_param("i", $customer_id);
$customer_stmt->execute();
$customer_stmt->bind_result($db_full_name, $db_email, $db_phone, $db_address);
$customer_stmt->fetch();
$customer_stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - LaundryPro</title>
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
            background-color:rgb(72, 80, 88);
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
            display: flex;
            min-height: calc(100vh - 70px);
        }
        
        .sidebar {
            width: 250px;
            background-color: white;
            box-shadow: 2px 0 5px rgba(0,0,0,0.1);
            padding: 1.5rem;
        }
        
        .sidebar h3 {
            color: #1e88e5;
            margin-bottom: 1.5rem;
            padding-bottom: 0.5rem;
            border-bottom: 1px solid #eee;
        }
        
        .sidebar ul {
            list-style: none;
        }
        
        .sidebar ul li {
            margin-bottom: 0.8rem;
        }
        
        .sidebar ul li a {
            color: #555;
            text-decoration: none;
            display: block;
            padding: 0.5rem;
            border-radius: 4px;
            transition: all 0.3s;
        }
        
        .sidebar ul li a:hover, .sidebar ul li a.active {
            background-color: #e3f2fd;
            color: #1e88e5;
        }
        
        .main-content {
            flex: 1;
            padding: 2rem;
        }
        
        .welcome {
            background-color: white;
            border-radius: 8px;
            padding: 1.5rem;
            margin-bottom: 2rem;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }
        
        .welcome h2 {
            color: #1e88e5;
            margin-bottom: 0.5rem;
        }
        
        .card {
            background-color: white;
            border-radius: 8px;
            padding: 1.5rem;
            margin-bottom: 2rem;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }
        
        .card h3 {
            color: #1e88e5;
            margin-bottom: 1rem;
            padding-bottom: 0.5rem;
            border-bottom: 1px solid #eee;
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
        
        .status {
            display: inline-block;
            padding: 0.3rem 0.6rem;
            border-radius: 20px;
            font-size: 0.8rem;
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
            padding: 0.3rem 0.6rem;
            border-radius: 20px;
            font-size: 0.8rem;
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
        
        .btn {
            display: inline-block;
            padding: 0.5rem 1rem;
            background-color: #1e88e5;
            color: white;
            border: none;
            border-radius: 4px;
            text-decoration: none;
            font-weight: 500;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        
        .btn:hover {
            background-color: #1565c0;
        }
        
        .btn-outline {
            background-color: transparent;
            border: 1px solid #1e88e5;
            color: #1e88e5;
        }
        
        .btn-outline:hover {
            background-color: #e3f2fd;
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
        
        input, select {
            width: 100%;
            padding: 0.8rem;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 1rem;
        }
        
        input:focus, select:focus {
            outline: none;
            border-color: #1e88e5;
        }
        
        .service-row {
            display: flex;
            gap: 1rem;
            margin-bottom: 1rem;
            align-items: center;
        }
        
        .service-row select {
            flex: 2;
        }
        
        .service-row input {
            flex: 1;
        }
        
        .add-service {
            margin-bottom: 1.5rem;
        }
        
        .error {
            color: #e53935;
            margin-bottom: 1rem;
        }
        
        .success {
            color: #43a047;
            margin-bottom: 1rem;
        }
        
        .tab-container {
            margin-bottom: 2rem;
        }
        
        .tabs {
            display: flex;
            border-bottom: 1px solid #ddd;
            margin-bottom: 1.5rem;
        }
        
        .tab {
            padding: 0.8rem 1.5rem;
            cursor: pointer;
            border-bottom: 3px solid transparent;
            font-weight: 500;
            color: #555;
        }
        
        .tab.active {
            border-bottom-color: #1e88e5;
            color: #1e88e5;
        }
        
        .tab-content {
            display: none;
        }
        
        .tab-content.active {
            display: block;
        }
        
        .progress-steps {
            margin-top: 1.5rem;
        }
        
        .progress-step {
            display: flex;
            align-items: center;
            margin-bottom: 1rem;
            padding-bottom: 1rem;
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
            margin-right: 1rem;
            color: #1e88e5;
            font-weight: bold;
        }
        
        .progress-info {
            flex: 1;
        }
        
        .progress-title {
            font-weight: 500;
            margin-bottom: 0.3rem;
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
                flex-direction: column;
            }
            
            .sidebar {
                width: 100%;
            }
            
            .service-row {
                flex-direction: column;
                align-items: flex-start;
            }
            
            .service-row select, .service-row input {
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <header>
        <div class="logo">LAUNDRY MANAGEMENT SYSTEM</div>
        <nav>
            <ul>
                <li><a href="dashboard.php">Dashboard</a></li>
                <li><a href="services.php">Services</a></li>
                <li><a href="about.php">About Us</a></li>
                <li><a href="contact.php">Contact</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </nav>
    </header>
    
    <div class="container">
        <div class="sidebar">
            <h3>Menu</h3>
            <ul>
			 <li><div class="tab active" data-tab="dashboard">Dashboard</div>
			 <li><div class="tab" data-tab="new-order">New Order</div>
			 <li><div class="tab" data-tab="order-history">Order History</div>
			 <li><div class="tab" data-tab="profile">My Profile</div>
              
            </ul>
        </div>
        
        <div class="main-content">
            <div class="welcome">
                <h2>Welcome, <?php echo htmlspecialchars($full_name); ?>!</h2>
                <p>Manage your laundry services, track orders, and update your profile.</p>
            </div>
            
            <div class="tab-container">
                
                
                <div id="dashboard" class="tab-content active">
                    <div class="card">
                        <h3>Recent Orders</h3>
                        <?php if ($orders_result->num_rows > 0): ?>
                            <table>
                                <thead>
                                    <tr>
                                        <th>Order ID</th>
                                        <th>Date</th>
                                        <th>Amount</th>
                                        <th>Status</th>
                                        <th>Payment</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while ($order = $orders_result->fetch_assoc()): ?>
                                        <tr>
                                            <td>no:<?php echo $order['order_id']; ?></td>
                                            <td><?php echo date('M d, Y', strtotime($order['order_date'])); ?></td>
                                            <td>Tsh<?php echo number_format($order['total_amount'], 2); ?></td>
                                            <td>
                                                <span class="status status-<?php echo strtolower($order['status']); ?>">
                                                    <?php echo ucfirst($order['status']); ?>
                                                </span>
                                            </td>
                                            <td>
                                                <span class="payment-status payment-<?php echo strtolower($order['payment_status']); ?>">
                                                    <?php echo ucfirst($order['payment_status']); ?>
                                                </span>
                                            </td>
                                            <td>
												<a href="order_details.php?order_id=<?php echo $order['order_id']; ?>" class="btn btn-outline">View</a>

												
											</td>

                                        </tr>
                                    <?php endwhile; ?>
                                </tbody>
                            </table>
                        <?php else: ?>
                            <p>You haven't placed any orders yet.</p>
                        <?php endif; ?>
                    </div>
                </div>
                
                <div id="new-order" class="tab-content">
                    <div class="card">
                        <h3>New Laundry Order</h3>
                        
                        <?php if (isset($success)): ?>
                            <p class="success"><?php echo $success; ?></p>
                        <?php endif; ?>
                        
                        <?php if (isset($error)): ?>
                            <p class="error"><?php echo $error; ?></p>
                        <?php endif; ?>
                        
                        <form action="dashboard.php" method="POST">
                            <div id="services-container">
                                <div class="service-row">
                                    <select name="services[]" required>
                                        <option value="">Select Service</option>
                                        <?php while ($service = $services_result->fetch_assoc()): ?>
                                            <option value="<?php echo $service['service_id']; ?>">
                                                <?php echo htmlspecialchars($service['service_name']); ?> - Tsh <?php echo number_format($service['price_per_item'], 2); ?>
                                            </option>
                                        <?php endwhile; ?>
                                    </select>
                                    <input type="number" name="quantities[]" min="1" value="1" required>
                                </div>
                            </div>
                            
                            <button type="button" id="add-service" class="btn btn-outline add-service">+ Add Another Service</button>
                            
                            <div class="form-group">
                                <button type="submit" name="place_order" class="btn">Place Order</button>
                            </div>
                        </form>
                    </div>
                </div>
                
                <div id="order-history" class="tab-content">
                    <div class="card">
                        <h3>Order History</h3>
                        <?php 
                        // Reset pointer for orders result
                        $orders_result->data_seek(0);
                        
                        if ($orders_result->num_rows > 0): ?>
                            <table>
                                <thead>
                                    <tr>
                                        <th>Order ID</th>
                                        <th>Date</th>
                                        <th>Amount</th>
                                        <th>Status</th>
                                        <th>Payment</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while ($order = $orders_result->fetch_assoc()): ?>
                                        <tr>
                                            <td>No <?php echo $order['order_id']; ?></td>
                                            <td><?php echo date('M d, Y', strtotime($order['order_date'])); ?></td>
                                            <td>Tsh <?php echo number_format($order['total_amount'], 2); ?></td>
                                            <td>
                                                <span class="status status-<?php echo strtolower($order['status']); ?>">
                                                    <?php echo ucfirst($order['status']); ?>
                                                </span>
                                            </td>
                                            <td>
                                                <span class="payment-status payment-<?php echo strtolower($order['payment_status']); ?>">
                                                    <?php echo ucfirst($order['payment_status']); ?>
                                                </span>
                                            </td>
                                            <td>
                                                <a href="order_details.php?order_id=<?php echo $order['order_id']; ?>" class="btn btn-outline">View Details</a>
                                            </td>
                                        </tr>
                                    <?php endwhile; ?>
                                </tbody>
                            </table>
                        <?php else: ?>
                            <p>You haven't placed any orders yet.</p>
                        <?php endif; ?>
                    </div>
                </div>
                
                <div id="profile" class="tab-content">
                    <div class="card">
                        <h3>My Profile</h3>
                        
                        <?php if (isset($profile_success)): ?>
                            <p class="success"><?php echo $profile_success; ?></p>
                        <?php endif; ?>
                        
                        <?php if (isset($profile_error)): ?>
                            <p class="error"><?php echo $profile_error; ?></p>
                        <?php endif; ?>
                        
                        <form action="dashboard.php" method="POST">
                            <div class="form-group">
                                <label for="full_name">Full Name</label>
                                <input type="text" id="full_name" name="full_name" value="<?php echo htmlspecialchars($db_full_name); ?>" required>
                            </div>
                            
                            <div class="form-group">
                                <label for="email">Email</label>
                                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($db_email); ?>" required>
                            </div>
                            
                            <div class="form-group">
                                <label for="phone">Phone Number</label>
                                <input type="tel" id="phone" name="phone" value="<?php echo htmlspecialchars($db_phone); ?>" required>
                            </div>
                            
                            <div class="form-group">
                                <label for="address">Address</label>
                                <input type="text" id="address" name="address" value="<?php echo htmlspecialchars($db_address); ?>">
                            </div>
                            
                            <div class="form-group">
                                <button type="submit" name="update_profile" class="btn">Update Profile</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script>
        // Tab functionality
        const tabs = document.querySelectorAll('.tab');
        const tabContents = document.querySelectorAll('.tab-content');
        
        tabs.forEach(tab => {
            tab.addEventListener('click', () => {
                // Remove active class from all tabs and contents
                tabs.forEach(t => t.classList.remove('active'));
                tabContents.forEach(c => c.classList.remove('active'));
                
                // Add active class to clicked tab and corresponding content
                tab.classList.add('active');
                const tabId = tab.getAttribute('data-tab');
                document.getElementById(tabId).classList.add('active');
            });
        });
        
        // Add service row functionality
        document.getElementById('add-service').addEventListener('click', () => {
            const servicesContainer = document.getElementById('services-container');
            const newRow = document.createElement('div');
            newRow.className = 'service-row';
            newRow.innerHTML = `
                <select name="services[]" required>
                    <option value="">Select Service</option>
                    <?php 
                    $services_result->data_seek(0);
                    while ($service = $services_result->fetch_assoc()): ?>
                        <option value="<?php echo $service['service_id']; ?>">
                            <?php echo htmlspecialchars($service['service_name']); ?> - $<?php echo number_format($service['price_per_item'], 2); ?>
                        </option>
                    <?php endwhile; ?>
                </select>
                <input type="number" name="quantities[]" min="1" value="1" required>
                <button type="button" class="btn btn-outline remove-service">Remove</button>
            `;
            servicesContainer.appendChild(newRow);
            
            // Add remove functionality to new button
            newRow.querySelector('.remove-service').addEventListener('click', () => {
                servicesContainer.removeChild(newRow);
            });
        });
        
        // Add remove functionality to existing remove buttons
        document.querySelectorAll('.remove-service').forEach(button => {
            button.addEventListener('click', function() {
                this.parentElement.remove();
            });
        });
    </script>
</body>
</html>