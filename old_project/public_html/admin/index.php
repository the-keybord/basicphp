<?php
// We need to start the session to check if a user is already logged in
session_start();

// Use our new auth library to check
require_once __DIR__ . '/../app/lib/auth.php';

// If an admin is already logged in, send them to the dashboard
if (is_user_admin()) {
    header('Location: /admin/dashboard.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Admin Login</title>
    <link rel="stylesheet" href="/admin/style.css">
</head>
<body>
    <div id="login-container" class="container">
        <form action="/admin/login_handler.php" method="POST" class="card">
            <h2>Admin Login</h2>
            <div class="input-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" required>
            </div>
            <div class="input-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
            </div>
            <button type="submit" class="btn">Login</button>
        </form>
    </div>
</body>
</html>