<?php
session_start();
include 'db_connection.php'; // Include your database connection

// Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header('Location: admin_login.php');
    exit();
}

// Get the module ID from the URL
if (isset($_GET['id'])) {
    $module_id = $_GET['id'];

    // Delete the module from the database
    $stmt = $pdo->prepare('DELETE FROM modules WHERE id = ?');
    $stmt->execute([$module_id]);

    // Redirect to manage modules after deletion
    header('Location: manage_modules.php');
    exit();
} else {
    header('Location: manage_modules.php'); // Redirect if no ID is given
    exit();
}
