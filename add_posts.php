<?php
session_start();
include 'db_connection.php'; // Include your database connection

// Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header('Location: admin_login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get form data
    $title = $_POST['title'];
    $content = $_POST['content'];
    $module = $_POST['module'];
    
    // Handle file upload
    $image = null;
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $imageTmpName = $_FILES['image']['tmp_name'];
        $imageName = basename($_FILES['image']['name']);
        $imageDestination = 'uploads/' . $imageName;

        // Move uploaded image to the uploads directory
        if (move_uploaded_file($imageTmpName, $imageDestination)) {
            $image = $imageName; // Store the image name in the database
        } else {
            echo "Error uploading image.";
        }
    }

    // Insert the new post into the database
    $query = "INSERT INTO posts (title, content, module, image) VALUES (:title, :content, :module, :image)";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':title', $title);
    $stmt->bindParam(':content', $content);
    $stmt->bindParam(':module', $module);
    $stmt->bindParam(':image', $image);

    if ($stmt->execute()) {
        // Redirect to the posts management page after successful insertion
        header('Location: manage_posts.php');
        exit();
    } else {
        echo "Error adding post.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Post</title>
</head>
<body>
    <h1>Add New Post</h1>

    <form action="add_post.php" method="POST" enctype="multipart/form-data">
        <label for="title">Title:</label>
        <input type="text" name="title" id="title" required><br><br>

        <label for="content">Content:</label>
        <textarea name="content" id="content" required></textarea><br><br>

        <label for="module">Module:</label>
        <select name="module" id="module" required>
            <option value="CSS">CSS</option>
            <option value="HTML">HTML</option>
            <option value="Python">Python</option>
            <option value="NodeJS">NodeJS</option>
        </select><br><br>

        <label for="image">Upload Image:</label>
        <input type="file" name="image" id="image"><br><br>

        <button type="submit">Add Post</button>
    </form>

    <br>
    <a href="manage_posts.php">Back to Manage Posts</a>
</body>
</html>
