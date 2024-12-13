<?php
include 'db_connection.php';

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $title = $_POST['title'];
    $content = $_POST['content'];
    $module = $_POST['module'];

    // Initialize variables for image handling
    $newImage = null;

    // Check if a file is uploaded
    if (!empty($_FILES['image']['name'])) {
        $image = $_FILES['image'];
        $targetDir = "uploads/";
        $targetFile = $targetDir . basename($image["name"]);
        $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

        // Validate image type and size (optional)
        $validTypes = ['jpg', 'jpeg', 'png', 'gif'];
        if (!in_array($imageFileType, $validTypes)) {
            echo "Only JPG, JPEG, PNG & GIF files are allowed.";
            exit;
        }
        if ($image['size'] > 2000000) { // 2MB limit
            echo "File size must not exceed 2MB.";
            exit;
        }

        // Move the uploaded file to the target directory
        if (move_uploaded_file($image["tmp_name"], $targetFile)) {
            $newImage = basename($image["name"]);
        } else {
            echo "Error uploading the image.";
            exit;
        }
    }

    // Update query with or without the image
    if ($newImage) {
        $query = "UPDATE posts SET title = :title, content = :content, module = :module, image = :image WHERE id = :id";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':image', $newImage);
    } else {
        $query = "UPDATE posts SET title = :title, content = :content, module = :module WHERE id = :id";
        $stmt = $pdo->prepare($query);
    }

    $stmt->bindParam(':id', $id);
    $stmt->bindParam(':title', $title);
    $stmt->bindParam(':content', $content);
    $stmt->bindParam(':module', $module);
    $stmt->execute();

    // Redirect to the homepage
    header("Location: index.php");
    exit;
}

// Check if ID is passed and fetch post details
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Fetch the post by ID
    $query = "SELECT * FROM posts WHERE id = :id";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':id', $id);
    $stmt->execute();

    $post = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$post) {
        echo "Post not found!";
        exit;
    }
} else {
    echo "Invalid request!";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Post</title>
    <link rel="stylesheet" href="css/edit_post.css">
</head>
<body>
    <header>
        <h1>Edit Post</h1>
    </header>
    <main>
        <form action="edit_post.php" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="id" value="<?php echo htmlspecialchars($post['id']); ?>">
            <h2>Edit Post Details</h2>
            <div>
                <label for="title">Title</label>
                <input type="text" id="title" name="title" value="<?php echo htmlspecialchars($post['title']); ?>" required>
            </div>
            <div>
                <label for="content">Content</label>
                <textarea id="content" name="content" required><?php echo htmlspecialchars($post['content']); ?></textarea>
            </div>
            <div>
                <label for="module">Module</label>
                <select id="module" name="module" required>
                    <option value="CSS" <?php echo $post['module'] == 'CSS' ? 'selected' : ''; ?>>CSS</option>
                    <option value="HTML" <?php echo $post['module'] == 'HTML' ? 'selected' : ''; ?>>HTML</option>
                    <option value="Python" <?php echo $post['module'] == 'Python' ? 'selected' : ''; ?>>Python</option>
                    <option value="NodeJS" <?php echo $post['module'] == 'NodeJS' ? 'selected' : ''; ?>>NodeJS</option>
                </select>
            </div>
            <div class="post-image">
                <div>
                    <label for="image">Change Image</label>
                    <input type="file" id="image" name="image">
                    <?php if ($post['image']): ?>
                        <p>Current Image:</p>
                        <img src="uploads/<?php echo htmlspecialchars($post['image']); ?>" alt="Post Image">
                    <?php endif; ?>
                </div>
            </div>
            <button type="submit">Update Post</button>
            <a href="index.php" class="cancel-btn">Cancel</a>
        </form>
    </main>
</body>
</html>
