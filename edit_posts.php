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

    // Fetch the post details from the database
    $stmt = $pdo->prepare('SELECT * FROM posts WHERE id = ?');
    $stmt->execute([$post_id]);
    $post = $stmt->fetch();

    if (!$post) {
        header('Location: manage_posts.php'); // Redirect if post not found
        exit();
    }
} else {
    header('Location: manage_posts.php'); // Redirect if no ID is given
    exit();
}

// Define a hardcoded list of modules
$modules = [
    1 => 'CSS',
    2 => 'HTML',
    3 => 'Python',
    4 => 'JavaScript'
];

// Handle form submission for editing the post
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $new_title = $_POST['title'];
    $new_content = $_POST['content'];
    $new_module = $_POST['module'];

    // Handle the image upload
    $new_image = $post['image']; // Default to current image if not uploaded

    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        // Set the upload directory
        $upload_dir = 'uploads/';

        // Ensure the directory exists
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0755, true);
        }

        // Get the file extension
        $image_ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);

        // Generate a unique file name
        $image_name = uniqid('post_image_') . '.' . $image_ext;

        // Full path to store the uploaded image
        $image_path = $upload_dir . $image_name;

        // Move the uploaded file to the target directory
        if (move_uploaded_file($_FILES['image']['tmp_name'], $image_path)) {
            $new_image = $image_path; // Update image path
        }
    }

    // Update the post in the database
    $stmt = $pdo->prepare('UPDATE posts SET title = ?, content = ?, module = ?, image = ? WHERE id = ?');
    $stmt->execute([$new_title, $new_content, $new_module, $new_image, $post_id]);

    // Redirect to manage posts after the update
    header('Location: manage_posts.php');
    exit();
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
    <h1>Edit Post</h1>
    <form action="edit_posts.php?id=<?php echo $post['id']; ?>" method="POST" enctype="multipart/form-data">
        <label for="title">Post Title:</label>
        <input type="text" name="title" id="title" value="<?php echo htmlspecialchars($post['title']); ?>" required><br>

        <label for="content">Post Content:</label>
        <textarea name="content" id="content" required><?php echo htmlspecialchars($post['content']); ?></textarea><br>

        <label for="module">Module:</label>
        <select name="module" id="module" required>
            <option value="">Select a Module</option>
            <?php foreach ($modules as $id => $module): ?>
                <option value="<?php echo $id; ?>" <?php echo ($id == $post['module']) ? 'selected' : ''; ?>>
                    <?php echo htmlspecialchars($module); ?>
                </option>
            <?php endforeach; ?>
        </select><br>

        <label for="image">Image (current: <?php echo basename($post['image']); ?>):</label>
        <input type="file" name="image" id="image"><br>

        <button type="submit">Update Post</button>
    </form>

    <br>
    <!-- Link to go back to manage posts page -->
    <a href="manage_posts.php">Back to Manage Posts</a>
</body>
</html>
