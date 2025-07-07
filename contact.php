<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us - LAUNDRY MANAGEMENT SYSTEM</title>
    <style>
        */* Reset & Base */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: 'Arial', sans-serif;
}

body {
    background-color: #f5f5f5;
    color: #333;
}

/* Header */
header {
    background-color: rgb(64, 68, 71);
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

nav ul li a:hover {
    text-decoration: underline;
}

/* Hero Section */
.hero {
    /* Uncomment if you want gradient overlay on image */
    /* background-image: linear-gradient(rgba(0,0,0,0.5), rgba(0,0,0,0.5)), url('laundry-contact.jpg'); */
    background-image: url("ironing.jpg");
    background-position: center;
    background-repeat: no-repeat;
    background-size: cover;
    height: 40vh;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    text-align: center;
    color: white;
    padding: 0 2rem;
}

.hero h1 {
    font-size: 3rem;
    margin-bottom: 1rem;
    color: blue;
}

/* Contact Container */
.contact-container {
    max-width: 1200px;
    margin: 3rem auto;
    padding: 0 2rem;
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 2rem;
}

/* Contact Info and Form Boxes */
.contact-info, 
.contact-form {
    background-color: white;
    border-radius: 8px;
    padding: 2rem;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

/* Headings */
.contact-info h2, 
.contact-form h2 {
    color: #1e88e5;
    margin-bottom: 1.5rem;
    padding-bottom: 0.5rem;
    border-bottom: 1px solid #eee;
}

/* Info Items */
.info-item {
    display: flex;
    align-items: flex-start;
    margin-bottom: 1.5rem;
}

.info-icon {
    background-color: #e3f2fd;
    color: #1e88e5;
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 1rem;
    flex-shrink: 0;
}

.info-content h3 {
    color: #555;
    margin-bottom: 0.3rem;
}

.info-content p {
    color: #666;
    line-height: 1.5;
}

/* Form Elements */
.form-group {
    margin-bottom: 1.5rem;
}

label {
    display: block;
    margin-bottom: 0.5rem;
    color: #555;
    font-weight: 500;
}

input, 
textarea {
    width: 100%;
    padding: 0.8rem;
    border: 1px solid #ddd;
    border-radius: 4px;
    font-size: 1rem;
    transition: border-color 0.3s ease;
}

input:focus, 
textarea:focus {
    outline: none;
    border-color: #1e88e5;
}

textarea {
    min-height: 150px;
    resize: vertical;
}

/* Buttons */
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
    transition: background-color 0.3s ease;
}

.btn:hover {
    background-color: #1565c0;
}

/* Map Section */
.map {
    margin-top: 2rem;
    border-radius: 8px;
    overflow: hidden;
}

.map iframe {
    width: 100%;
    height: 300px;
    border: none;
}

/* Footer */
footer {
    background-color: #333;
    color: white;
    padding: 2rem;
    text-align: center;
}

.footer-links {
    display: flex;
    justify-content: center;
    margin-bottom: 1rem;
}

.footer-links a {
    color: white;
    margin: 0 1rem;
    text-decoration: none;
}

.footer-links a:hover {
    text-decoration: underline;
}

/* Responsive */
@media (max-width: 768px) {
    header {
        flex-direction: column;
        text-align: center;
    }
    
    nav ul {
        margin-top: 1rem;
    }
    
    .hero h1 {
        font-size: 2rem;
    }

    .contact-container {
        grid-template-columns: 1fr;
        padding: 1rem;
    }
}

            
            .contact-container {
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
                <li><a href="index.php">Home</a></li>
                <li><a href="services.php">Services</a></li>
                <li><a href="about.php">About Us</a></li>
                <li><a href="contact.php">Contact</a></li>
                <li><a href="login.php">Login</a></li>
            </ul>
        </nav>
    </header>
    
    <section class="hero">
        <h1>Contact Us</h1>
        <marquee behavior="scroll" direction="left">
  We're here to help with all your laundry needs
</marquee>

    </section>
    
    <div class="contact-container">
        <div class="contact-info">
            <h2>Get In Touch</h2>
            
            <div class="info-item">
                <div class="info-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                        <circle cx="12" cy="10" r="3"></circle>
                    </svg>
                </div>
                <div class="info-content">
                    <h3>Address</h3>
                    <p>mbuzini </p>
                </div>
            </div>
            
            <div class="info-item">
                <div class="info-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path>
                    </svg>
                </div>
                <div class="info-content">
                    <h3>Phone</h3>
                    <p>+255 123 456 789</p>
                    <p>+255 987 654 321</p>
                </div>
            </div>
            
            <div class="info-item">
                <div class="info-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path>
                        <polyline points="22,6 12,13 2,6"></polyline>
                    </svg>
                </div>
                <div class="info-content">
                    <h3>Email</h3>
                    <p>info@laundrypro.com</p>
                    <p>support@laundrypro.com</p>
                </div>
            </div>
            
            <div class="info-item">
                <div class="info-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                        <line x1="16" y1="2" x2="16" y2="6"></line>
                        <line x1="8" y1="2" x2="8" y2="6"></line>
                        <line x1="3" y1="10" x2="21" y2="10"></line>
                    </svg>
                </div>
                <div class="info-content">
                    <h3>Opening Hours</h3>
                    <p>Monday - Friday: 8:00 AM - 8:00 PM</p>
                    <p>Saturday: 9:00 AM - 6:00 PM</p>
                    <p>Sunday: 10:00 AM - 4:00 PM</p>
                </div>

                
            </div>
            

        </div>
        
        <div class="contact-form">
            <h2>Send Us a Message</h2>
            <form action="process_contact.php" method="POST">
                <div class="form-group">
                    <label for="name">Your Name</label>
                    <input type="text" id="name" name="name" required>
                </div>
                
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" required>
                </div>
                
                <div class="form-group">
                    <label for="phone">Phone</label>
                    <input type="tel" id="phone" name="phone">
                </div>
                
                <div class="form-group">
                    <label for="subject">Subject</label>
                    <input type="text" id="subject" name="subject" required>
                </div>
                
                <div class="form-group">
                    <label for="message">Message</label>
                    <textarea id="message" name="message" required></textarea>
                </div>
                
                <button type="submit" class="btn">Send Message</button>
            </form>
        </div>
    </div>
    
    <footer>
        <div class="footer-links">
            <a href="index.php">Home</a>
            <a href="services.php">Services</a>
            <a href="about.php">About Us</a>
            <a href="contact.php">Contact</a>
            <a href="privacy.php">Privacy Policy</a>
    </div>
             <p> Laundry Management System. All rights reserved.</p>
    </footer>
       

</body>
</html>