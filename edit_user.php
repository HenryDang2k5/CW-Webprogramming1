<?php
session_start();
include 'db_connection.php';

// Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header('Location: admin_login.php');
    exit();
}

// Fetch user data
if (isset($_GET['id'])) {
    $stmt = $pdo->prepare('SELECT * FROM users WHERE id = ?');
    $stmt->execute([$_GET['id']]);
    $user = $stmt->fetch();
    
    if (!$user) {
        echo "User not found.";
        exit();
    }

    // Handle form submission to update user
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $username = htmlspecialchars(trim($_POST['username']));
        $email = htmlspecialchars(trim($_POST['email']));
        $role = $_POST['role'];

        $stmt_update = $pdo->prepare('UPDATE users SET username = ?, email = ?, role = ? WHERE id = ?');
        $stmt_update->execute([$username, $email, $role, $user['id']]);

        header('Location: manage_users.php'); // Redirect to manage users page
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User</title>
</head>
<body>
    <h1>Edit User</h1>
    <form method="POST">
        <input type="text" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" required>
        <input type="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
        <select name="role" required>
            <option value="admin" <?php echo ($user['role'] === 'admin' ? 'selected' : ''); ?>>Admin</option>
            <option value="user" <?php echo ($user['role'] === 'user' ? 'selected' : ''); ?>>User</option>
        </select>
        <button type="submit">Save Changes</button>
    </form>
</body>
</html>
