<?php
session_start();
include 'db_connection.php'; // Include your database connection

// Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header('Location: admin_login.php');
    exit();
}

// Define a hardcoded list of modules (map module ID to module name)
$modules = [
    1 => 'CSS',
    2 => 'HTML',
    3 => 'Python',
    4 => 'JavaScript'
];

// Fetch all posts from the database
$stmt = $pdo->prepare('SELECT * FROM posts');
$stmt->execute();
$posts = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Posts</title>
    <link rel="stylesheet" href="css/home_admin.css">
</head>
<body>
    <div class="container">
        <header>
            <h1>Manage Posts</h1>
            <nav>
                <a href="admin_dashboard.php" class="btn">Back to Admin Dashboard</a>
                <a href="add_posts.php" class="btn">Add New Post</a>
            </nav>
        </header>

        <main>
            <table class="post-table">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Content</th>
                        <th>Module</th>
                        <th>Image</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($posts as $post): ?>
                        <tr class="post-row">
                            <td><?php echo htmlspecialchars($post['title']); ?></td>
                            <td><?php echo htmlspecialchars($post['content']); ?></td>
                            <td>
                                <?php
                                // Display the module name instead of the ID
                                echo isset($modules[$post['module']]) ? htmlspecialchars($modules[$post['module']]) : 'Unknown Module';
                                ?>
                            </td>
                            <td><img src="<?php echo htmlspecialchars($post['image']); ?>" class="post-image" alt="Post Image"></td>
                            <td>
                                <a href="edit_posts.php?id=<?php echo $post['id']; ?>" class="btn edit-btn">Edit</a>
                                <a href="delete_posts.php?id=<?php echo $post['id']; ?>" class="btn delete-btn" onclick="return confirm('Are you sure you want to delete this post?');">Delete</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </main>
    </div>
</body>
</html>
