<?php
session_start(); // Start the session

// Unset all session variables to log the user out
session_unset();

// Destroy the session to completely log out the user
session_destroy();

// Redirect to the login page or home page after logout
header('Location: login.php');
exit();
?>
