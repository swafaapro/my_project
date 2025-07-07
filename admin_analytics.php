<?php 
include 'db_connection.php'; // hakikisha una include connection yako hapa

// 1. Orders per Month
$monthlyOrders = [];
$months = [];
$sql1 = "SELECT DATE_FORMAT(order_date, '%b') AS month, COUNT(*) AS count FROM orders GROUP BY month ORDER BY order_date ASC";
$res1 = $conn->query($sql1);
while ($row = $res1->fetch_assoc()) {
    $months[] = $row['month'];
    $monthlyOrders[] = $row['count'];
}

// 2. Order Status Distribution
$statuses = ['Pending' => 0, 'In Progress' => 0, 'Completed' => 0];
$sql2 = "SELECT status, COUNT(*) as count FROM orders GROUP BY status";
$res2 = $conn->query($sql2);
while ($row = $res2->fetch_assoc()) {
    $statuses[$row['status']] = $row['count'];
}

// 3. Payment Status Overview
$payments = ['Paid' => 0, 'Not Paid' => 0];
$sql3 = "SELECT payment_status, COUNT(*) as count FROM orders GROUP BY payment_status";
$res3 = $conn->query($sql3);
while ($row = $res3->fetch_assoc()) {
    $payments[$row['payment_status']] = $row['count'];
}

// 4. Revenue Over Time
$revenue = [];
$revMonths = [];
$sql4 = "SELECT DATE_FORMAT(order_date, '%b') AS month, SUM(total_amount) AS total FROM orders GROUP BY month ORDER BY order_date ASC";
$res4 = $conn->query($sql4);
while ($row = $res4->fetch_assoc()) {
    $revMonths[] = $row['month'];
    $revenue[] = $row['total'];
}

// 5. Top Customers
$topCustomers = [];
$customerSpending = [];
$sql5 = "SELECT c.full_name, SUM(o.total_amount) as total FROM customers c JOIN orders o ON c.customer_id = o.customer_id GROUP BY c.customer_id ORDER BY total DESC LIMIT 5";
$res5 = $conn->query($sql5);
while ($row = $res5->fetch_assoc()) {
    $topCustomers[] = $row['full_name'];
    $customerSpending[] = $row['total'];
}

// 6. Frequent Order Days
$dayLabels = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
$frequentDaysCounts = array_fill(0, 7, 0);

$sql6 = "SELECT DAYNAME(order_date) as day, COUNT(*) as total FROM orders GROUP BY day";
$res6 = $conn->query($sql6);

while ($row = $res6->fetch_assoc()) {
    $dayIndex = array_search($row['day'], $dayLabels);
    if ($dayIndex !== false) {
        $frequentDaysCounts[$dayIndex] = $row['total'];
    }
}
?>
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
<head>
  <meta charset="UTF-8">
  <title>Admin Dashboard - Analytics</title>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <style>
  body {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    background-color: #f4f6f9;
    margin: 0;
    padding: 20px;
  }
  h2 {
    text-align: center;
    margin-bottom: 30px;
  }
  .dashboard-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 20px;
  }
  .chart-box {
    background-color: white;
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.05);
    margin-bottom: 20px; /* consistent spacing below each chart */
  }
  .chart-box:last-of-type {
    margin-top: 0; /* remove extra top margin if any */
  }
  .chart-box h4 {
    text-align: center;
    margin-bottom: 15px;
    font-size: 16px;
    color: #333;
  }
  canvas {
    max-width: 100%;
  }
</style>

</head>
<body>

  <h2>📊 Admin Analytics Dashboard</h2>

  <div class="dashboard-grid">
    <div class="chart-box">
      <h4>Orders per Month</h4>
      <canvas id="ordersPerMonthChart"></canvas>
    </div>
    <div class="chart-box">
      <h4>Order Status Distribution</h4>
      <canvas id="orderStatusChart"></canvas>
    </div>
    <div class="chart-box">
      <h4>Payment Status Overview</h4>
      <canvas id="paymentStatusChart"></canvas>
    </div>
    <div class="chart-box">
      <h4>Revenue Over Time</h4>
      <canvas id="revenueChart"></canvas>
    </div>
    <div class="chart-box">
      <h4>Top Customers</h4>
      <canvas id="topCustomersChart"></canvas>
    </div>
    <div class="chart-box">
      <h4>Frequent Order Days</h4>
      <canvas id="frequentOrderDaysChart"></canvas>
    </div>
  </div>

  <div class="chart-box">
    <h4>Most Ordered Services</h4>
    <canvas id="mostOrderedServicesChart"></canvas>
  </div>

  <!-- Chart scripts -->
  <script>
    const months = <?= json_encode($months) ?>;
    const orderCounts = <?= json_encode($monthlyOrders) ?>;
    const statusData = <?= json_encode(array_values($statuses)) ?>;
    const paymentData = <?= json_encode(array_values($payments)) ?>;
    const revenueLabels = <?= json_encode($revMonths) ?>;
    const revenueData = <?= json_encode($revenue) ?>;
    const topCustomers = <?= json_encode($topCustomers) ?>;
    const customerAmounts = <?= json_encode($customerSpending) ?>;
    const frequentDayLabels = <?= json_encode($dayLabels) ?>;
    const frequentDayCounts = <?= json_encode($frequentDaysCounts) ?>;

    // Orders per Month PIE chart (changed from bar)
    new Chart(document.getElementById('ordersPerMonthChart'), {
      type: 'pie',
      data: {
        labels: months,
        datasets: [{
          label: 'Orders',
          data: orderCounts,
          backgroundColor: [
            '#4e73df', '#1cc88a', '#36b9cc', '#f6c23e', '#e74a3b', '#858796', '#5a5c69',
            '#2e59d9', '#17a673', '#2c9faf', '#f4b619', '#d33f49'
          ]
        }]
      },
      options: {
        responsive: true,
        plugins: {
          legend: { position: 'right' }
        }
      }
    });

    new Chart(document.getElementById('orderStatusChart'), {
      type: 'pie',
      data: {
        labels: ['Pending', 'In Progress', 'Completed'],
        datasets: [{
          data: statusData,
          backgroundColor: ['#f6c23e', '#36b9cc', '#1cc88a']
        }]
      }
    });

    new Chart(document.getElementById('paymentStatusChart'), {
      type: 'pie',
      data: {
        labels: ['Paid', 'Not Paid'],
        datasets: [{
          data: paymentData,
          backgroundColor: ['#1cc88a', '#e74a3b']
        }]
      }
    });

    new Chart(document.getElementById('revenueChart'), {
      type: 'line',
      data: {
        labels: revenueLabels,
        datasets: [{
          label: 'Revenue (TZS)',
          data: revenueData,
          borderColor: '#4e73df',
          backgroundColor: 'rgba(78, 115, 223, 0.1)',
          fill: true
        }]
      }
    });

    new Chart(document.getElementById('topCustomersChart'), {
      type: 'bar',
      data: {
        labels: topCustomers,
        datasets: [{
          label: 'Total Spent (TZS)',
          data: customerAmounts,
          backgroundColor: '#36b9cc'
        }]
      }
    });

    new Chart(document.getElementById('frequentOrderDaysChart'), {
      type: 'bar',
      data: {
        labels: frequentDayLabels,
        datasets: [{
          label: 'Number of Orders',
          data: frequentDayCounts,
          backgroundColor: '#17a2b8'
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
            title: {
              display: true,
              text: 'Orders'
            }
          }
        }
      }
    });
  </script>

<?php
$sql = "
    SELECT s.service_name, COUNT(oi.service_id) AS total_orders
    FROM order_items oi
    JOIN services s ON oi.service_id = s.service_id
    GROUP BY s.service_name
    ORDER BY total_orders DESC
    LIMIT 5
";
$result = $conn->query($sql);

$serviceNames = [];
$serviceCounts = [];

while ($row = $result->fetch_assoc()) {
    $serviceNames[] = $row['service_name'];
    $serviceCounts[] = $row['total_orders'];
}

$jsServiceNames = json_encode($serviceNames);
$jsServiceCounts = json_encode($serviceCounts);
?>

<script>
  const serviceLabels = <?= $jsServiceNames ?>;
  const serviceData = <?= $jsServiceCounts ?>;

  new Chart(document.getElementById('mostOrderedServicesChart'), {
    type: 'bar',
    data: {
      labels: serviceLabels,
      datasets: [{
        label: 'Orders',
        data: serviceData,
        backgroundColor: '#f6c23e'
      }]
    }
  });
</script>

</body>
</html>
