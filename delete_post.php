<?php
include 'db_connection.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Validate if ID exists in the database
    $query = "SELECT * FROM posts WHERE id = :id";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':id', $id);
    $stmt->execute();

    // Check if post exists
    if ($stmt->rowCount() > 0) {
        // Delete post by ID
        $query = "DELETE FROM posts WHERE id = :id";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        // Redirect to homepage after deletion
        header("Location: index.php");
        exit;
    } else {
        echo "Post not found.";
    }
} else {
    echo "No ID provided.";
}
?>
