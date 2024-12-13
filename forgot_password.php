<?php
session_start();
include 'db_connection.php'; // Include your database connection
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Include PHPMailer autoloader
require 'vendor/autoload.php'; // Adjust the path if you're not using Composer

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];

    // Check if the email exists in the database
    $stmt = $pdo->prepare('SELECT * FROM users WHERE email = ?');
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user) {
        // Generate a password reset token and expiration time
        $token = bin2hex(random_bytes(50)); // Secure random token
        $expires = date('U') + 3600; // Token expires in 1 hour

        // Insert or update the token and expiration in the database
        $stmt = $pdo->prepare('REPLACE INTO password_resets (email, token, expires) VALUES (?, ?, ?)');
        $stmt->execute([$email, $token, $expires]);

        // Create a password reset link
        $resetLink = "http://localhost/crud_app/reset_password.php?token=$token";

        // Send the reset link to the user's email using PHPMailer
        $mail = new PHPMailer(true);

        try {
            // Server settings
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'dungmanh1210@gmail.com';
            $mail->Password = 'afvgqxdccfhqbdhd'; // App-specific password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            // Recipients
            $mail->setFrom('dungmanh1210@gmail.com', 'News');
            $mail->addAddress($email);

            // Content
            $mail->isHTML(true);
            $mail->Subject = 'Password Reset Request';
            $mail->Body = "To reset your password, click the link below:<br><br><a href='$resetLink'>$resetLink</a>";

            $mail->send();
            $success_message = "A password reset link has been sent to your email address.";
        } catch (Exception $e) {
            $error_message = "Error sending email: " . $mail->ErrorInfo;
        }
    } else {
        $error_message = "No account found with that email address.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
    <link rel="stylesheet" href="css/login.css">
</head>
<body>
    <div class="form-container">
        <form action="forgot_password.php" method="POST">
            <h1>Forgot Password</h1>
            <?php if (isset($error_message)): ?>
                <p class="error"><?php echo $error_message; ?></p>
            <?php endif; ?>
            <?php if (isset($success_message)): ?>
                <p class="success"><?php echo $success_message; ?></p>
            <?php endif; ?>
            <input type="email" name="email" placeholder="Enter your email address" required>
            <button type="submit">Send Password Reset Link</button>
            <p>Remembered your password? <a href="login.php">Login here</a></p>
        </form>
    </div>
    <script src="script.js"></script>
</body>
</html>
