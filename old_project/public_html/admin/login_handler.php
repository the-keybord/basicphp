<?php
// Start the session at the very beginning
session_start();

// CHANGE: Use __DIR__ and '..' to correctly locate the files in /app/lib
require_once __DIR__ . '/../app/lib/db_connect.php';

// Check if the form was submitted
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: /admin/index.php');
    exit;
}

$username = $_POST['username'] ?? '';
$password = $_POST['password'] ?? '';

try {
    // Find the user in the database
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    // Verify user exists and the password is correct
    if ($user && password_verify($password, $user['password'])) {
        
        // Credentials are correct, regenerate session ID for security
        session_regenerate_id();
        
        // Set session variables
        $_SESSION['loggedin'] = true;
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];
        
        // CHANGE: Use an absolute path for the redirect
        header('Location: /admin/dashboard.php');
        exit;
    } else {
        // Incorrect credentials
        // CHANGE: Use an absolute path for the redirect
        header('Location: /admin/index.php?error=invalid');
        exit;
    }
} catch (PDOException $e) {
    // In a real app, you would log this error, not show it.
    // For debugging, you can uncomment the next line:
    // die("Database error: " . $e->getMessage());
    
    // Redirect on any database error
    header('Location: /admin/index.php?error=dberror');
    exit;
}