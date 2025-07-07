<?php include 'config.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - LAUNDRY MANAGEMENT SYSTEM</title>
    <style>
        * /* Reset & Base */
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

/* Form Container */
.container {
    max-width: 500px;
    margin: 3rem auto;
    padding: 2rem;
    background-color: white;
    border-radius: 8px;
    box-shadow: 0 2px 6px rgba(0,0,0,0.1);
}

/* Form Group */
.form-group {
    margin-bottom: 1.5rem;
}

label {
    display: block;
    margin-bottom: 0.5rem;
    color: #555;
    font-weight: 500;
}

input[type="text"],
input[type="email"],
input[type="tel"],
input[type="password"] {
    width: 100%;
    padding: 0.8rem;
    border: 1px solid #ddd;
    border-radius: 4px;
    font-size: 1rem;
    transition: border-color 0.3s ease;
}

input:focus {
    outline: none;
    border-color: #1e88e5;
}

/* Button */
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
    width: 100%;
}

.btn:hover {
    background-color: #1565c0;
}

/* Login Link */
.login-link {
    margin-top: 1rem;
    text-align: center;
    font-size: 0.9rem;
}

.login-link a {
    color: #1e88e5;
    text-decoration: none;
    font-weight: 600;
}

.login-link a:hover {
    text-decoration: underline;
}

    </style>
</head>
<body>
    <div class="container">
        <h1>Create an Account</h1>
        
        <?php
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $full_name = $_POST['full_name'];
            $email = $_POST['email'];
            $phone = $_POST['phone'];
            $password = $_POST['password'];
            $address = $_POST['address'];
            
            // Validate inputs
            $errors = [];
            
            if (empty($full_name)) {
                $errors[] = "Full name is required";
            }
            
            if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors[] = "Valid email is required";
            }
            
            if (empty($phone)) {
                $errors[] = "Phone number is required";
            }
            
            if (strlen($password) < 6) {
                $errors[] = "Password must be at least 6 characters";
            }
            
            // Check if email already exists
            $stmt = $conn->prepare("SELECT email FROM customers WHERE email = ?");
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $stmt->store_result();
            
            if ($stmt->num_rows > 0) {
                $errors[] = "Email already registered";
            }
            
            $stmt->close();
            
            if (empty($errors)) {
                // Hash password
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                
                // Insert into database
                $stmt = $conn->prepare("INSERT INTO customers (full_name, email, phone, password, address) VALUES (?, ?, ?, ?, ?)");
                $stmt->bind_param("sssss", $full_name, $email, $phone, $hashed_password, $address);
                
                if ($stmt->execute()) {
                    echo '<p class="success">Registration successful! You can now <a href="login.php">login</a>.</p>';
                } else {
                    echo '<p class="error">Error: ' . $stmt->error . '</p>';
                }
                
                $stmt->close();
            } else {
                foreach ($errors as $error) {
                    echo '<p class="error">' . $error . '</p>';
                }
            }
        }
        ?>
        
        <form action="register.php" method="POST">
            <div class="form-group">
                <label for="full_name">Full Name</label>
                <input type="text" id="full_name" name="full_name" required>
            </div>
            
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required>
            </div>
            
            <div class="form-group">
                <label for="phone">Phone Number</label>
                <input type="tel" id="phone" name="phone" required>
            </div>
            
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required minlength="6">
            </div>
            
            <div class="form-group">
                <label for="address">Address</label>
                <input type="text" id="address" name="address">
            </div>
            
            <button type="submit" class="btn">Register</button>
        </form>
        
        <div class="login-link">
            Already have an account? <a href="login.php">Login here</a>
        </div>
    </div>
</body>
</html>