<?php
session_start();
include 'db_connection.php'; // Include your database connection

// Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header('Location: admin_login.php');
    exit();
}

// Handle form submission to add a new module
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['module_name'])) {
    $module_name = $_POST['module_name'];

    // Insert new module into the database
    $stmt = $pdo->prepare('INSERT INTO modules (name) VALUES (?)');
    $stmt->execute([$module_name]);

    // Redirect back to the same page to refresh the list
    header('Location: manage_modules.php');
    exit();
}

// Fetch all modules from the database
$stmt = $pdo->prepare('SELECT * FROM modules');
$stmt->execute();
$modules = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Modules</title>
    <link rel="stylesheet" href="css/edit_post.css">
</head>
<body>
    <header>
        <h1>Manage Modules</h1>
        <a href="admin_dashboard.php">Back to Dashboard</a>
    </header>
    
    <main>
        <h2>Existing Modules</h2>
        <table>
            <thead>
                <tr>
                    <th>Module Name</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($modules as $module): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($module['name']); ?></td>
                        <td>
                            <a href="edit_module.php?id=<?php echo $module['id']; ?>">Edit</a> |
                            <a href="delete_module.php?id=<?php echo $module['id']; ?>">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <h2>Add New Module</h2>
        <form action="manage_modules.php" method="POST">
            <input type="text" name="module_name" placeholder="New Module Name" required>
            <button type="submit">Add Module</button>
        </form>
    </main>
</body>
</html>
