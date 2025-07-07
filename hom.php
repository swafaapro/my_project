<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Laundry Management System - Home</title>

  <!-- Swiper CSS (for slideshow) -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.css" />

  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    body {
      background-color: #f4f6f8;
      color: #333;
    }

    header {
      background-color: #1e3a8a;
      color: white;
      padding: 1rem 2rem;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    .logo {
      font-size: 1.8rem;
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
      transition: color 0.3s;
    }

    nav ul li a:hover {
      color: #ffcc00;
    }

    /* Swiper slider */
    .swiper {
      width: 100%;
      height: 80vh;
    }

    .swiper-slide {
      position: relative;
      background-size: cover;
      background-position: center;
    }

    .slide-content {
      position: absolute;
      top: 50%;
      left: 10%;
      transform: translateY(-50%);
      color: white;
      max-width: 500px;
    }

    .slide-content h2 {
      font-size: 3rem;
      margin-bottom: 1rem;
    }

    .slide-content p {
      font-size: 1.2rem;
      margin-bottom: 1.5rem;
    }

    .btn {
      background-color: #facc15;
      color: #000;
      padding: 0.8rem 1.5rem;
      text-decoration: none;
      border-radius: 5px;
      font-weight: bold;
    }

    .services {
      padding: 4rem 2rem;
      text-align: center;
    }

    .services h2 {
      font-size: 2.5rem;
      color: #1e40af;
      margin-bottom: 2rem;
    }

    .service-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
      gap: 2rem;
    }

    .service-card {
      background: white;
      border-radius: 10px;
      overflow: hidden;
      box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
      transition: transform 0.3s;
    }

    .service-card:hover {
      transform: translateY(-5px);
    }

    .service-img {
      height: 200px;
      background-size: cover;
      background-position: center;
    }

    .service-info {
      padding: 1.5rem;
    }

    .service-info h3 {
      margin-bottom: 0.5rem;
      color: #1d4ed8;
    }

    .price {
      font-weight: bold;
      margin-top: 0.5rem;
    }

    footer {
      background-color: #1f2937;
      color: white;
      padding: 2rem 1rem;
      text-align: center;
    }

    .footer-links a {
      color: white;
      margin: 0 1rem;
      text-decoration: none;
    }

    .footer-links a:hover {
      text-decoration: underline;
    }

    @media (max-width: 768px) {
      .slide-content h2 {
        font-size: 2rem;
      }
    }
  </style>
</head>
<body>

  <header>
    <div class="logo">Swafhan Laundry</div>
    <nav>
      <ul>
        <li><a href="index.php">Home</a></li>
        <li><a href="services.php">Services</a></li>
        <li><a href="about.php">About</a></li>
        <li><a href="contact.php">Contact</a></li>
        <li><a href="login.php">Login</a></li>
      </ul>
    </nav>
  </header>

  <!-- Swiper -->
  <div class="swiper">
    <div class="swiper-wrapper">
      <div class="swiper-slide" style="background-image:url('assets/images/slide1.jpg')">
        <div class="slide-content">
          <h2>Professional Laundry Services</h2>
          <p>Fast, clean and affordable solutions for home and business clothes.</p>
          <a href="register.php" class="btn">Start Now</a>
        </div>
      </div>
      <div class="swiper-slide" style="background-image:url('assets/images/slide2.jpg')">
        <div class="slide-content">
          <h2>Pickup & Delivery</h2>
          <p>Let us come to you! Reliable pickup and door-to-door delivery service.</p>
          <a href="services.php" class="btn">See Services</a>
        </div>
      </div>
      <div class="swiper-slide" style="background-image:url('assets/images/slide3.jpg')">
        <div class="slide-content">
          <h2>Trusted by Hundreds</h2>
          <p>Your satisfaction is our pride. Experience premium laundry care.</p>
          <a href="contact.php" class="btn">Contact Us</a>
        </div>
      </div>
    </div>
  </div>

  <section class="services">
    <h2>Our Services</h2>
    <div class="service-grid">
      <div class="service-card">
        <div class="service-img" style="background-image: url('assets/images/wash.jpg');"></div>
        <div class="service-info">
          <h3>Washing</h3>
          <p>High-quality washing with modern machines</p>
          <p class="price">@ TZS 5100/item</p>
        </div>
      </div>

      <div class="service-card">
        <div class="service-img" style="background-image: url('assets/images/dry.jpg');"></div>
        <div class="service-info">
          <h3>Dry Cleaning</h3>
          <p>Gentle care for your delicate fabrics</p>
          <p class="price">@ TZS 1500/item</p>
        </div>
      </div>

      <div class="service-card">
        <div class="service-img" style="background-image: url('assets/images/iron.jpg');"></div>
        <div class="service-info">
          <h3>Ironing</h3>
          <p>Crisp results with our professional ironing</p>
          <p class="price">@ TZS 1000/item</p>
        </div>
      </div>

      <div class="service-card">
        <div class="service-img" style="background-image: url('assets/images/pickup.jpg');"></div>
        <div class="service-info">
          <h3>Pickup Service</h3>
          <p>Book a pickup at your convenience</p>
          <p class="price">Free within 5km</p>
        </div>
      </div>

      <div class="service-card">
        <div class="service-img" style="background-image: url('assets/images/fold.jpg');"></div>
        <div class="service-info">
          <h3>Folding</h3>
          <p>Neat folding for your laundry needs</p>
          <p class="price">@ TZS 500/item</p>
        </div>
      </div>

      <div class="service-card">
        <div class="service-img" style="background-image: url('assets/images/delivery.jpg');"></div>
        <div class="service-info">
          <h3>Express Delivery</h3>
          <p>Get your clothes cleaned within 24 hours</p>
          <p class="price">Extra + TZS 2000</p>
        </div>
      </div>
    </div>
  </section>

  <footer>
    <div class="footer-links">
      <a href="index.php">Home</a>
      <a href="services.php">Services</a>
      <a href="about.php">About</a>
      <a href="contact.php">Contact</a>
      <a href="privacy.php">Privacy Policy</a>
    </div>
    <p>&copy; 2025 Swafhan Laundry Management System. All rights reserved.</p>
  </footer>

  <!-- Swiper JS -->
  <script src="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.js"></script>
  <script>
    const swiper = new Swiper('.swiper', {
      loop: true,
      autoplay: {
        delay: 5000,
        disableOnInteraction: false,
      }
    });
  </script>
</body>
</html>
