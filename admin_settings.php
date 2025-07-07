<?php
session_start();
include 'db_connection.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit;
}

$admin_id = $_SESSION['admin_id'];

// Fetch current admin data
$stmt = $conn->prepare("SELECT username, email FROM admins WHERE admin_id = ?");
$stmt->bind_param("i", $admin_id);
$stmt->execute();
$result = $stmt->get_result();
$admin = $result->fetch_assoc();

$username = $admin['username'] ?? '';
$email = $admin['email'] ?? '';

$update_message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_profile'])) {
    $new_username = trim($_POST['username']);
    $new_email = trim($_POST['email']);

    if (!empty($new_username) && !empty($new_email)) {
        $update_stmt = $conn->prepare("UPDATE admins SET username = ?, email = ? WHERE admin_id = ?");
        $update_stmt->bind_param("ssi", $new_username, $new_email, $admin_id);
        if ($update_stmt->execute()) {
            $update_message = "Profile updated successfully.";
            $_SESSION['admin_username'] = $new_username;
            $username = $new_username;
            $email = $new_email;
        } else {
            $update_message = "Failed to update profile.";
        }
        $update_stmt->close();
    } else {
        $update_message = "Please fill in all fields.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Admin Settings</title>
  <style>
    body { font-family: Arial; padding: 20px; }
    .success { color: green; }
    .error { color: red; }
    form { max-width: 400px; margin: auto; }
    input[type=text], input[type=email] {
      width: 100%; padding: 8px; margin: 8px 0;
    }
    button { padding: 10px 20px; background: #333; color: white; border: none; }
  </style>
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

<h2>Update Admin Profile</h2>

<?php if (!empty($update_message)): ?>
  <p class="<?= strpos($update_message, 'successfully') !== false ? 'success' : 'error' ?>">
    <?= htmlspecialchars($update_message) ?>
  </p>
<?php endif; ?>

<form method="POST">
  <label>Username:</label>
  <input type="text" name="username" value="<?= htmlspecialchars($username) ?>" required>

  <label>Email:</label>
  <input type="email" name="email" value="<?= htmlspecialchars($email) ?>" required>

  <button type="submit" name="update_profile">Update Profile</button>
</form>

</body>
</html>
