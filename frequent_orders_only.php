<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Frequent Order Days</title>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <style>
    body {
      font-family: Arial, sans-serif;
      background-color: #f4f6f9;
      margin: 0;
      padding: 20px;
    }
    h2 {
      text-align: center;
      margin-bottom: 30px;
    }
    .chart-container {
      max-width: 700px;
      margin: auto;
      background: #fff;
      padding: 20px;
      border-radius: 10px;
      box-shadow: 0 2px 6px rgba(0,0,0,0.1);
    }
  </style>
</head>
<body>

  <h2>📊 Frequent Order Days</h2>

  <div class="chart-container">
    <canvas id="frequentOrderDaysChart"></canvas>
  </div>

  <?php
    include 'db_connection.php'; // hakikisha hii path ni sahihi

    $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
    $orderCounts = array_fill(0, 7, 0);

    $sql = "SELECT DAYOFWEEK(order_date) AS weekday, COUNT(*) AS total FROM orders GROUP BY weekday";
    $result = $conn->query($sql);

    if ($result) {
      while ($row = $result->fetch_assoc()) {
        $weekday = (int)$row['weekday']; // 1 (Sunday) to 7 (Saturday)
        $index = $weekday == 1 ? 6 : $weekday - 2; // Adjust to start from Monday
        $orderCounts[$index] = (int)$row['total'];
      }
    }
  ?>

  <script>
    const weekDays = <?= json_encode($days); ?>;
    const orderData = <?= json_encode($orderCounts); ?>;

    new Chart(document.getElementById('frequentOrderDaysChart'), {
      type: 'bar',
      data: {
        labels: weekDays,
        datasets: [{
          label: 'Number of Orders',
          data: orderData,
          backgroundColor: '#17a2b8'
        }]
      },
      options: {
        scales: {
          y: {
            beginAtZero: true,
            title: {
              display: true,
              text: 'Total Orders'
            }
          }
        }
      }
    });
  </script>

</body>
</html>
