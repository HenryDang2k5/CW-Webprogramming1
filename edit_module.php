<?php
session_start();
include 'db_connection.php'; // Include your database connection

// Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header('Location: admin_login.php');
    exit();
}

// Get the module ID from the URL
if (isset($_GET['id'])) {
    $module_id = $_GET['id'];

    // Fetch the module from the database
    $stmt = $pdo->prepare('SELECT * FROM modules WHERE id = ?');
    $stmt->execute([$module_id]);
    $module = $stmt->fetch();

    if (!$module) {
        header('Location: manage_modules.php'); // Redirect if module not found
        exit();
    }
} else {
    header('Location: manage_modules.php'); // Redirect if no ID is given
    exit();
}

// Handle form submission for editing the module
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $new_module_name = $_POST['module_name'];

    // Update the module in the database (update column 'name' instead of 'module_name')
    $stmt = $pdo->prepare('UPDATE modules SET name = ? WHERE id = ?');
    $stmt->execute([$new_module_name, $module_id]);

    // Redirect to manage modules after the update
    header('Location: manage_modules.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Module</title>
    <link rel="stylesheet" href="css/edit_post.css">
</head>
<body>
    <h1>Edit Module</h1>
    <form action="edit_module.php?id=<?php echo $module['id']; ?>" method="POST">
        <label for="module_name">Module Name:</label>
        <input type="text" name="module_name" id="module_name" value="<?php echo htmlspecialchars($module['name']); ?>" required><br>

        <button type="submit">Update Module</button>
    </form>
</body>
</html>
