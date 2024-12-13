<?php
session_start();
include 'db_connection.php'; // Database connection file
// Handle login submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    // Query the database for the user
    $stmt = $pdo->prepare('SELECT * FROM users WHERE username = ?');
    $stmt->execute([$username]);
    $user = $stmt->fetch();
    if ($user && password_verify($password, $user['password'])) {
        // Set session variables
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['role'] = $user['role'];
        // Redirect based on role
        if ($user['role'] === 'admin') {
            header('Location: admin_dashboard.php');
        } else {
            header('Location: index.php');
        }
        exit();
    } else {
        $error_message = "Invalid username or password.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="css/login.css">
</head>
<body>
    <div class="form-container">
        <form action="login.php" method="POST">
            <h1>Login</h1>
            <?php if (isset($error_message)): ?>
                <p class="error"><?php echo $error_message; ?></p>
            <?php endif; ?>
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>
            <!-- Button Text can be dynamic -->
            <button type="submit"><?php echo isset($user) && $user['role'] === 'admin' ? 'Admin Login' : 'Login'; ?></button>
            <p>Forgot your password? <a href="forgot_password.php">Reset here</a></p>
            <p>Don't have an account? <a href="register.php">Register here</a></p>
            <p>Change to <a href="admin_login.php">Admin Login</a></p>
        </form>
    </div>
    <script src="script.js"></script>
</body>
</html>
