<?php
session_start();
include 'db_connection.php'; // Database connection file
// Initialize error messages
$error_message = "";
// Handle admin registration submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Sanitize user input to prevent XSS
    $adminkey = htmlspecialchars(trim($_POST['adminkey']));
    $username = htmlspecialchars(trim($_POST['username']));
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    // Admin key verification (it should be predefined, for example, "admin123")
    define('ADMIN_KEY', '1210'); // Define a static admin key or get it from an environment variable
    // Check for valid admin key
    if ($adminkey !== ADMIN_KEY) {
        $error_message = "Invalid admin key.";
    } elseif ($password !== $confirm_password) {
        $error_message = "Passwords do not match.";
    } else {
        // Check if username already exists in the database
        $stmt = $pdo->prepare('SELECT * FROM admins WHERE username = ?');
        $stmt->execute([$username]);
        $existing_user = $stmt->fetch();
        if ($existing_user) {
            $error_message = "Username already exists.";
        } else {
            // Hash the password securely
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            // Insert the new admin user into the database
            try {
                $stmt = $pdo->prepare('INSERT INTO admins (adminkey, username, password) VALUES (?, ?, ?)');
                $stmt->execute([$adminkey, $username, $hashed_password]);
                
                // Redirect to the login page after successful registration
                header('Location: admin_login.php');
                exit();
            } catch (PDOException $e) {
                // Handle any errors during database insertion
                $error_message = "Error registering admin: " . $e->getMessage();
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Registration</title>
    <link rel="stylesheet" href="css/login.css">
</head>
<body>
    <div class="form-container">
        <form action="admin_register.php" method="POST">
            <h1>Admin Register</h1>
            <?php if (!empty($error_message)): ?>
                <p class="error"><?php echo $error_message; ?></p>
            <?php endif; ?>
            <input type="text" name="adminkey" placeholder="Admin Key" required>
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required autocomplete="off">
            <input type="password" name="confirm_password" placeholder="Confirm Password" required autocomplete="off">
            <button type="submit">Register</button>
            <p>Already have an account? <a href="admin_login.php">Login here</a></p>
        </form>
    </div>
    <script src="script.js"></script>
</body>
</html>
