<?php 
include 'config.php';
session_start();

// Check if user is logged in, if not redirect to login page
if (!isset($_SESSION['customer_id'])) {
    header("Location: login.php");
    exit();
}

// Get all services from database
$services_query = "SELECT * FROM services";
$services_result = $conn->query($services_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Our Services - LAUNDRY MANAGEMENT SYSTEM</title>
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
            background-color:rgb(65, 70, 74);
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
        
        .services-header {
            text-align: center;
            margin-bottom: 2rem;
        }
        
        .services-header h1 {
            color: #1e88e5;
            margin-bottom: 1rem;
        }
        
        .services-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 2rem;
        }
        
        .service-card {
            background-color: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            transition: transform 0.3s;
        }
        
        .service-card:hover {
            transform: translateY(-5px);
        }
        
        .service-img {
            height: 200px;
            background-color: #ddd;
            background-size: cover;
            background-position: center;
        }
        
        .service-content {
            padding: 1.5rem;
        }
        
        .service-content h3 {
            color: #1e88e5;
            margin-bottom: 0.5rem;
        }
        
        .service-content p {
            color: #666;
            margin-bottom: 1rem;
            line-height: 1.5;
        }
        
        .service-price {
            font-weight: bold;
            color: #333;
            font-size: 1.2rem;
        }
        
        .back-link {
            display: inline-block;
            margin-top: 2rem;
            color: #1e88e5;
            text-decoration: none;
            font-weight: 500;
        }
        
        @media (max-width: 768px) {
            header {
                flex-direction: column;
                text-align: center;
            }
            
            nav ul {
                margin-top: 1rem;
            }
            
            .services-grid {
                grid-template-columns: 1fr;
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
        <div class="services-header">
            <h1>Our Laundry Services</h1>
            <p>We offer a wide range of professional laundry services to meet all your needs</p>
        </div>
        
        <div class="services-grid">
            <?php while ($service = $services_result->fetch_assoc()): ?>
                <div class="service-card">
                    <div class="service-img" style="background-image: url('mashine.jpg');"></div>
                    <div class="service-content">
                        <h3><?php echo htmlspecialchars($service['service_name']); ?></h3>
                        <p><?php echo htmlspecialchars($service['description']); ?></p>
                        <p class="service-price">Tsh <?php echo number_format($service['price_per_item'], 0); ?> per item</p>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
        
        <a href="dashboard.php" class="back-link">&larr; Back to Dashboard</a>
    </div>
</body>
</html>



