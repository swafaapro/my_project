<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>About Us - Laundry Management System</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- CSS -->
    <link href="your-stylesheet.css" rel="stylesheet"> <!-- Replace with your actual CSS file path -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet">
    <!-- Font Awesome for navbar dropdown -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f4f7fa;
            margin: 0;
            padding: 0;
        }
        .logo {
            font-size: 22px;
            font-weight: 600;
            color: var(--bs-primary);
        }
        header {
            background-color: #fff;
            padding: 10px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }
        nav ul {
            list-style: none;
            display: flex;
            gap: 20px;
            margin: 0;
        }
        nav a {
            text-decoration: none;
            color: var(--bs-primary);
            font-weight: 500;
        }
        nav a:hover {
            color: var(--bs-secondary);
        }
        .hero {
            background: linear-gradient(rgba(0, 58, 102, 0.9), rgba(0, 58, 102, 0.8)), url('img/breadcrumb.png');
            background-position: center;
            background-size: cover;
            padding: 100px 20px 60px 20px;
            color: white;
            text-align: center;
        }
        .about-content {
            padding: 40px 20px;
            max-width: 1000px;
            margin: auto;
        }
        .about-section {
            margin-bottom: 40px;
        }
        .about-section h2 {
            color: var(--bs-primary);
            border-bottom: 2px solid var(--bs-secondary);
            padding-bottom: 10px;
        }
        .about-section li {
            margin-left: 20px;
        }
        footer {
            background-color: var(--bs-primary);
            color: white;
            padding: 20px 30px;
            text-align: center;
        }
        .footer-links {
            margin-bottom: 10px;
        }
        .footer-links a {
            color: white;
            margin: 0 10px;
            text-decoration: none;
        }
        .footer-links a:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>
    <!-- Spinner -->
    <div id="spinner" class="show position-fixed w-100 vh-100 top-0 start-0 d-flex align-items-center justify-content-center bg-white">
        <div class="spinner-border text-primary" role="status"></div>
    </div>

    <!-- Header -->
    <header>
        <div class="logo">LAUNDRY MANAGEMENT SYSTEM</div>
        <nav>
            <ul>
                <li><a href="index.php">Home</a></li>
                <li><a href="services.php">Services</a></li>
                <li><a href="about.php" class="active">About Us</a></li>
                <li><a href="contact.php">Contact</a></li>
                <li><a href="login.php">Login</a></li>
            </ul>
        </nav>
    </header>

    <!-- Hero Section -->
    <section class="hero">
        <h1>About Swafhan Laundry Services</h1>
        <p>Zanzibar’s choice for professional laundry care</p>
    </section>

    <!-- About Content -->
    <div class="about-content">
        <div class="about-section">
            <h2>Our Story</h2>
            <p>It all started with a simple goal: to make everyday life a little easier. At Swafhan Laundry, we believe that clean clothes bring comfort, confidence, and dignity. Our journey began in Zanzibar with a passion for service and a dream to create a laundry solution that combines quality, reliability, and convenience.</p>
            <p>We noticed that many people—locals and tourists alike—struggled to find professional, timely laundry care they could trust. That’s when we decided to step in. With just a few machines and a strong work ethic, we started serving our first customers one load at a time.</p>
            <p>Word quickly spread, and what began as a small idea grew into a fully equipped laundry service known for its commitment to excellence.</p>
            <p>Today, we serve individuals, families, and businesses across Zanzibar, delivering clean clothes and peace of mind right to their doorsteps. Our story is built on hard work, honesty, and a deep respect for our customers' time and trust.</p>
            <p>From our family to yours — thank you for making us a part of your everyday life.</p>
        </div>

        <div class="about-section">
            <h2>Our Mission</h2>
            <p>At Swafhan Laundry, our mission is to deliver high-quality, reliable, and affordable laundry services that simplify our customers’ lives.</p>
            <ul>
                <li>Excellence: Spotless, fresh garments using modern techniques and eco-friendly practices.</li>
                <li>Convenience: Timely pickup and delivery services.</li>
                <li>Trust: Consistent quality and honest service.</li>
                <li>Community: Creating jobs and supporting ethical business practices.</li>
            </ul> 
            <p>Our goal is not just to clean clothes—it’s to create a service experience that brings comfort, confidence, and peace of mind to every customer we serve.</p>
        </div>

        <div class="about-section">
            <h2>Why Choose Us</h2>
            <p><strong>Fast & Reliable:</strong> On-time pickup and delivery you can count on.</p>
            <p><strong>High Quality:</strong> Your clothes are cleaned, folded, and returned with care.</p>
            <p><strong>Affordable:</strong> Great service at competitive prices.</p>
            <p><strong>Eco-Friendly:</strong> Safe detergents and sustainable practices.</p>
            <p><strong>Trusted:</strong> Loved by locals, tourists, and top hotels in Zanzibar.</p>
        </div>
    </div>

    <!-- Footer -->
    <footer>
        <div class="footer-links">
            <a href="index.php">Home</a>
            <a href="services.php">Services</a>
            <a href="about.php">About Us</a>
            <a href="contact.php">Contact</a>
            <a href="privacy.php">Privacy Policy</a>
        </div>
        <p>© 2025 Swafhan Laundry Services. All rights reserved.</p>
    </footer>

    <script>
        // Spinner hide
        window.addEventListener('load', () => {
            document.getElementById('spinner').classList.remove('show');
        });
    </script>
</body>
</html>
