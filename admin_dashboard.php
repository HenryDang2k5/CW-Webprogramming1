<?php
session_start();

// Check if the admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header('Location: admin_login.php');
    exit();
}

$username = $_SESSION['username']; // Admin's username
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="css/home.css"> <!-- Include your CSS for styling -->
</head>
<body>
    <div class="dashboard-container">
        <header>
            <h1>Welcome, <?php echo htmlspecialchars($username); ?>!</h1>
            <a href="logout.php" class="logout-btn">Logout</a>
        </header>

        <main>
            <div class="dashboard-menu">
                <h2>Admin Management Panel</h2>
                <ul>
                    <li><a href="manage_posts.php">Manage Posts</a></li>
                    <li><a href="manage_users.php">Manage Users</a></li>
                    <li><a href="manage_modules.php">Manage Modules</a></li>
                </ul>
            </div>
        </main>
    </div>
</body>
</html>
