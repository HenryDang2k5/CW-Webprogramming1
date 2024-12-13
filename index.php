<?php
include 'db_connection.php'; // Include database connection

// Fetch posts from the database
$query = "SELECT * FROM posts ORDER BY created_at DESC";
$stmt = $pdo->query($query);
$posts = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <link rel="stylesheet" href="css/home.css">
</head>
<body>
    <header>
        <h1>Welcome to the Post Page</h1>
        <nav>
            <a href="logout.php">Logout</a>
        </nav>
    </header>
    <main>
        <div class="post-container">
            <!-- Display Posts -->
            <div class="posts">
                <h2>Posts</h2>
                <!-- Button to Add Post -->
                <div class="add-post-button">
                    <a href="add_post.php" class="btn">Add New Post</a>
                </div>
                <br>
                <!-- Contact Admin Section -->
                <div class="contact-admin">
                    <a href="mailto:dungmanh1210@gmail.com" class="btn">Contact Admin</a>
                </div>
                <br>
                <?php if (count($posts) > 0): ?>
                    <?php foreach ($posts as $post): ?>
                        <div class="post">
                            <h3><?php echo htmlspecialchars($post['title']); ?></h3>
                            <p><?php echo nl2br(htmlspecialchars($post['content'])); ?></p>
                            <p><strong>Module:</strong> <?php echo htmlspecialchars($post['module']); ?></p>

                            <!-- Display image if available -->
                            <?php if ($post['image']): ?>
                                <img src="<?php echo htmlspecialchars($post['image']); ?>" alt="Post Image" width="300">
                            <?php endif; ?>

                            <a href="edit_post.php?id=<?php echo $post['id']; ?>" class="btn">Edit</a>
                            <a href="delete_post.php?id=<?php echo $post['id']; ?>" 
                               class="btn" 
                               onclick="return confirm('Are you sure you want to delete this post?');">Delete</a>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>No posts available.</p>
                <?php endif; ?>

                
            </div>
        </div>
    </main>
</body>
</html>
