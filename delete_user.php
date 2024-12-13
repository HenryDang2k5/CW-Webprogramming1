<?php
session_start();
include 'db_connection.php'; // Database connection

// Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header('Location: admin_login.php');
    exit();
}

// Handle user deletion
if (isset($_GET['id'])) {
    $user_id = $_GET['id'];

    // Prepare and execute the delete query
    $stmt = $pdo->prepare('DELETE FROM users WHERE id = ?');
    $stmt->execute([$user_id]);

    // Redirect to the manage users page after deletion
    header('Location: manage_users.php');
    exit();
} else {
    echo "No user ID provided.";
}
?>
