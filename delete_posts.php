<?php
session_start();
include 'db_connection.php'; // Include your database connection

// Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header('Location: admin_login.php');
    exit();
}

// Get the post ID from the URL
if (isset($_GET['id'])) {
    $post_id = $_GET['id'];

    // Delete the post from the database
    $stmt = $pdo->prepare('DELETE FROM posts WHERE id = ?');
    $stmt->execute([$post_id]);

    // Redirect to manage posts after deletion
    header('Location: manage_posts.php');
    exit();
} else {
    header('Location: manage_posts.php'); // Redirect if no ID is given
    exit();
}
?>
