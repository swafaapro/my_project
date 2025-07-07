<?php 
include 'config.php';
session_start();

if (isset($_SESSION['reset_msg'])) {
    echo '<div style="background:#e3f2fd; padding:10px; margin:10px 0;">'.$_SESSION['reset_msg'].'</div>';
    unset($_SESSION['reset_msg']);
}

if (isset($_SESSION['reset_error'])) {
    echo '<div style="background:#ffebee; padding:10px; margin:10px 0;">'.$_SESSION['reset_error'].'</div>';
    unset($_SESSION['reset_error']);
}

if (isset($_SESSION['reset_success'])) {
    echo '<div style="background:#e8f5e9; padding:10px; margin:10px 0;">'.$_SESSION['reset_success'].'</div>';
    unset($_SESSION['reset_success']);
}
if (isset($_SESSION['reset_message'])) {
    $reset_message = $_SESSION['reset_message'];
    unset($_SESSION['reset_message']);
}

if (isset($_SESSION['reset_error'])) {
    $reset_error = $_SESSION['reset_error'];
    unset($_SESSION['reset_error']);
}

if (isset($_SESSION['customer_id'])) {
    header("Location: dashboard.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];
    
    $stmt = $conn->prepare("SELECT customer_id, full_name, password FROM customers WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();
    
    if ($stmt->num_rows > 0) {
        $stmt->bind_result($customer_id, $full_name, $hashed_password);
        $stmt->fetch();
        
        if (password_verify($password, $hashed_password)) {
            $_SESSION['customer_id'] = $customer_id;
            $_SESSION['full_name'] = $full_name;
            header("Location: dashboard.php");
            exit();
        } else {
            $error = "Invalid email or password";
        }
    } else {
        $error = "Invalid email or password";
    }

    $stmt->close();
}
?>

<?php if (isset($reset_message)): ?>
    <p class="success"><?php echo $reset_message; ?></p>
<?php endif; ?>

<?php if (isset($reset_error)): ?>
    <p class="error"><?php echo $reset_error; ?></p>
<?php endif; ?>

<?php if (isset($reset_success)): ?>
    <p class="success"><?php echo $reset_success; ?></p>
<?php endif; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - LAUNDRY MANAGEMENT SYSTEM</title>
    <style>
        * /* Header */
header {
    background-color: #007bff; /* blue background */
    color: white;
    padding: 15px 30px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    font-family: 'Poppins', sans-serif;
    font-weight: 600;
    font-size: 20px;
}

header nav a {
    color: white;
    text-decoration: none;
    margin-left: 25px;
    font-weight: 500;
    transition: color 0.3s ease;
}

header nav a:hover {
    color: #ffc107; /* yellow highlight */
}

/* Container */
.container {
    max-width: 450px;
    margin: 50px auto 100px auto;
    background-color: #f8f9fa; /* light gray */
    padding: 30px 40px;
    border-radius: 12px;
    box-shadow: 0 8px 20px rgba(0,0,0,0.1);
    font-family: 'Poppins', sans-serif;
}

/* Heading */
.container h1 {
    text-align: center;
    margin-bottom: 30px;
    color: #333;
}

/* Error message */
.error {
    color: #dc3545;
    background-color: #f8d7da;
    border-radius: 6px;
    padding: 10px 15px;
    margin-bottom: 20px;
    font-weight: 600;
    font-size: 14px;
}

/* Form Group */
.form-group {
    margin-bottom: 20px;
}

.form-group label {
    display: block;
    font-weight: 600;
    margin-bottom: 8px;
    color: #444;
}

.form-group input[type="email"],
.form-group input[type="password"] {
    width: 100%;
    padding: 12px 15px;
    border: 1.5px solid #ced4da;
    border-radius: 8px;
    font-size: 16px;
    transition: border-color 0.3s ease;
}

.form-group input[type="email"]:focus,
.form-group input[type="password"]:focus {
    border-color: #007bff;
    outline: none;
}

/* Button */
.btn {
    width: 100%;
    background-color: #007bff;
    color: white;
    padding: 12px 0;
    border: none;
    border-radius: 50px;
    font-weight: 700;
    font-size: 18px;
    cursor: pointer;
    transition: background-color 0.3s ease;
    margin-top: 10px;
}

.btn:hover {
    background-color: #0056b3;
}

/* Links */
.forgot-password,
.register-link,
.admin-login {
    text-align: center;
    margin-top: 20px;
    font-size: 14px;
}

.forgot-password a,
.register-link a,
.admin-login a {
    color: #007bff;
    text-decoration: none;
    font-weight: 600;
}

.forgot-password a:hover,
.register-link a:hover,
.admin-login a:hover {
    text-decoration: underline;
}

/* Modal Background */
.modal {
    display: none; /* hide by default */
    position: fixed;
    z-index: 1000;
    left: 0; top: 0;
    width: 100%; height: 100%;
    background: rgba(0,0,0,0.5);
    justify-content: center;
    align-items: center;
}

/* Modal Content */
.modal-content {
    background: white;
    padding: 30px 40px;
    border-radius: 12px;
    width: 400px;
    position: relative;
    font-family: 'Poppins', sans-serif;
    box-shadow: 0 10px 25px rgba(0,0,0,0.2);
}

/* Modal Header */
.modal-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
}

.modal-header h2 {
    margin: 0;
    font-weight: 700;
    color: #007bff;
}

/* Close Button */
.close-btn {
    background: none;
    border: none;
    font-size: 28px;
    font-weight: 700;
    cursor: pointer;
    color: #666;
    transition: color 0.3s ease;
}

.close-btn:hover {
    color: #dc3545;
}

/* Modal Form */
#forgotPasswordForm .form-group input[type="email"] {
    font-size: 16px;
    padding: 12px 15px;
    border-radius: 8px;
    border: 1.5px solid #ced4da;
    width: 100%;
}

#forgotPasswordForm button.btn {
    margin-top: 15px;
}

    </style>
</head>

<body>

    <!-- Navigation Header -->
    <header>
        <div>LAUNDRY MANAGEMENT SYSTEM</div>
        <nav>
            <a href="index.php">Home</a>
            <a href="services.php">Services</a>
            <a href="about.php">About Us</a>
            <a href="contact.php">Contact</a>
            <a href="logout.php">Logout</a>
        </nav>
    </header>

    <div class="container">
        <h1>Customer Login</h1>

        <?php if (isset($error)): ?>
            <p class="error"><?php echo $error; ?></p>
        <?php endif; ?>
        
        <form action="login.php" method="POST">
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required>
            </div>
            
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
            </div>
            
            <button type="submit" class="btn">Login</button><br><br>

            <div class="forgot-password">
                <a href="customer_enter_email.php">Forgot password?</a>

            </div>
        </form>
        
        <div class="register-link">
            Don't have an account? <a href="register.php">Register here</a>
        </div>
        
        <div class="admin-login">
            Are you an admin? <a href="admin_dashboard.php">Admin login</a>
        </div>
    </div>

    <div class="modal" id="forgotPasswordModal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Reset Password</h2>
                <button class="close-btn" id="closeModal">&times;</button>
            </div>
            <form id="forgotPasswordForm" action="forgot_password.php" method="POST">
                <div class="form-group">
                    <label for="resetEmail">Enter your email address</label>
                    <input type="email" id="resetEmail" name="email" required>
                </div>
                <button type="submit" class="btn">Send Reset Link</button>
            </form>
        </div>
    </div>

    <script>
        const forgotPasswordLink = document.getElementById('forgotPasswordLink');
        const forgotPasswordModal = document.getElementById('forgotPasswordModal');
        const closeModal = document.getElementById('closeModal');
        
        if (forgotPasswordLink) {
            forgotPasswordLink.addEventListener('click', function(e) {
                e.preventDefault();
                forgotPasswordModal.style.display = 'flex';
            });
        }
        
        if (closeModal) {
            closeModal.addEventListener('click', function() {
                forgotPasswordModal.style.display = 'none';
            });
        }

        window.addEventListener('click', function(e) {
            if (e.target === forgotPasswordModal) {
                forgotPasswordModal.style.display = 'none';
            }
        });
    </script>
</body>
</html>
