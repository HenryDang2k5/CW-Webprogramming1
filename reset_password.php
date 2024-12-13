<?php
session_start();
include 'db_connection.php'; // Include your database connection

if (isset($_GET['token'])) {
    $token = $_GET['token'];
    $stmt = $pdo->prepare('SELECT * FROM password_resets WHERE token = ? AND expires >= ?');
    $stmt->execute([$token, date('U')]);
    $resetRequest = $stmt->fetch();

    if ($resetRequest) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $newPassword = password_hash($_POST['new_password'], PASSWORD_DEFAULT);

            // Update user's password
            $stmt = $pdo->prepare('UPDATE users SET password = ? WHERE email = ?');
            $stmt->execute([$newPassword, $resetRequest['email']]);

            // Delete the password reset record
            $stmt = $pdo->prepare('DELETE FROM password_resets WHERE email = ?');
            $stmt->execute([$resetRequest['email']]);

            $success_message = "Password has been reset successfully.";
        }
    } else {
        $error_message = "Invalid or expired token.";
    }
} else {
    $error_message = "No token provided.";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <link rel="stylesheet" href="css/login.css">
</head>
<body>
    <div class="form-container">
        <?php if (isset($error_message)): ?>
            <p class="error"><?php echo $error_message; ?></p>
        <?php endif; ?>
        <?php if (isset($success_message)): ?>
            <p class="success"><?php echo $success_message; ?></p>
        <?php endif; ?>
        <?php if (!isset($success_message) && !isset($error_message)): ?>
            <form action="reset_password.php?token=<?php echo htmlspecialchars($token); ?>" method="POST">
                <h1>Reset Password</h1>
                <input type="password" name="new_password" placeholder="Enter your new password" required>
                <button type="submit">Reset Password</button>
            </form>
        <?php endif; ?>
    </div>
    <script src="script.js"></script>
</body>
</html>
