<?php
// Database connection parameters
$host = 'localhost';        // Database host (localhost for local)
$dbname = 'crud_website';  // Your database name
$username = 'root';// Your MySQL username
$password = '';// Your MySQL password

try {
    // Create a PDO instance (connect to the database)
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    
    // Set the PDO error mode to exception
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    
} catch (PDOException $e) {
    // If there is an error in connection, display the message
    echo "Connection failed: " . $e->getMessage();
}
?>
