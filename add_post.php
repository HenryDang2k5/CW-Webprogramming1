<?php
include 'db_connection.php'; // Include database connection

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the post data
    $title = $_POST['title'];
    $content = $_POST['content'];
    $module = $_POST['module'];

    // Handle image upload
    $image = NULL;
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        // Define the target directory for the uploaded image
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($_FILES['image']['name']);
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Check if the file is a valid image type (optional security check)
        $allowed_types = ['jpg', 'png', 'jpeg', 'gif'];
        if (in_array($imageFileType, $allowed_types)) {
            // Move the uploaded file to the target directory
            if (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
                $image = $target_file; // Save the file path to the database
            } else {
                echo "Sorry, there was an error uploading your file.";
            }
        } else {
            echo "Sorry, only JPG, JPEG, PNG, and GIF files are allowed.";
        }
    }

    // Insert post data into the database, including image path if available
    $query = "INSERT INTO posts (title, content, module, image, created_at) 
              VALUES (:title, :content, :module, :image, NOW())";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':title', $title);
    $stmt->bindParam(':content', $content);
    $stmt->bindParam(':module', $module);
    $stmt->bindParam(':image', $image); // Bind image path (NULL if no image)
    
    if ($stmt->execute()) {
        // Redirect to home after adding a post
        header("Location: index.php");
        exit();
    } else {
        echo "Error: Unable to add the post.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Post</title>
    <link rel="stylesheet" href="css/home.css">
</head>
<body>
    <header>
        <h1>Add a New Post</h1>
        <nav>
            <a href="index.php">Home</a>
        </nav>
    </header>
    <main>
        <div class="add-post-container">
            <form action="add_post.php" method="POST" enctype="multipart/form-data">
                <input type="text" name="title" placeholder="Title" required>
                <textarea name="content" placeholder="Content..." required></textarea>
                <label for="module">Module:</label>
                <select name="module" id="module" required>
                    <option value="CSS">CSS</option>
                    <option value="HTML">HTML</option>
                    <option value="Python">Python</option>
                    <option value="NodeJS">NodeJS</option>
                </select>
                <input type="file" name="image">
                <button type="submit">Add Post</button>
            </form>
        </div>
    </main>
</body>
</html>
