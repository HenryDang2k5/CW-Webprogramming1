<?php
// Enable error reporting
ini_set('display_errors', 1);
error_reporting(E_ALL);
// Include database connection
include 'db_connection.php';
// Initialize variables for error messages and success message
$errorMessage = '';
$successMessage = '';
// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get form data
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    // Validation checks
    if (empty($username) || empty($email) || empty($password)) {
        $errorMessage = "Please fill in all fields.";
    } else {
        try {
            // Check if the username or email already exists
            $query = "SELECT * FROM users WHERE username = :username OR email = :email";
            $stmt = $pdo->prepare($query);
            $stmt->execute(['username' => $username, 'email' => $email]);
            $existingUser = $stmt->fetch();
            if ($existingUser) {
                $errorMessage = "Username or email already exists. Please try again.";
            } else {
                // Hash the password for security
                $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
                // Insert the new user into the database
                $insertQuery = "INSERT INTO users (username, email, password) VALUES (:username, :email, :password)";
                $insertStmt = $pdo->prepare($insertQuery);
                $insertStmt->execute([
                    'username' => $username,
                    'email' => $email,
                    'password' => $hashedPassword
                ]);
                // Success message
                $successMessage = "Registration successful! You can now <a href='login.php'>login</a>.";
                // Redirect to the login page after registration
                header("Location: login.php");
                exit();
            }
        } catch (PDOException $e) {
            // Catch any database errors
            $errorMessage = "Error: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="css/login.css">
</head>
<body>
    <div class="form-container">
        <form action="register.php" method="POST">
            <h1>Register</h1>

            <?php if ($successMessage): ?>
                <div class="success-message"><?php echo $successMessage; ?></div>
            <?php endif; ?>

            <?php if ($errorMessage): ?>
                <div class="error-message"><?php echo $errorMessage; ?></div>
            <?php endif; ?>

            <input type="text" name="username" placeholder="Username" required value="<?php echo htmlspecialchars($username ?? ''); ?>">
            <input type="email" name="email" placeholder="Email" required value="<?php echo htmlspecialchars($email ?? ''); ?>">
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Register</button>
            <p>Already have an account? <a href="login.php">Login here</a></p>
        </form>
    </div>
    <script src="script.js"></script>
</body>
</html>
