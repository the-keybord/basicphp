<?php
/**
 * dashboard.php
 *
 * This is the main landing page for the admin panel.
 */

// CHANGE: The path must go up one level from /admin to the root, then into /app/lib.
require_once __DIR__ . '/../app/lib/auth.php';

// CHANGE: Use the new gatekeeper function from the auth library.
// This single line replaces the old 'require_once "auth_check.php"' and the old if-statement.
require_admin();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Admin Panel</title>
    <link rel="stylesheet" href="/admin/style.css">
</head>
<body>

    <div id="dashboard-container" class="container">
        <div class="dashboard-header">
            <h1>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h1>
            <a href="/admin/logout.php" class="btn btn-secondary">Logout</a>
        </div>
        <div class="dashboard-grid">
            <div class="dashboard-card">
                <h3>Question Explorer</h3>
                <p>Browse, search, and preview all question components.</p>
                <a href="/admin/question_explorer.php" class="card-link">Open Explorer &rarr;</a>
            </div>
            <div class="dashboard-card">
                <h3>Question Creator</h3>
                <p>Use guided builders to create new question components.</p>
                <a href="/admin/question_creator.php" class="card-link">Open Creator &rarr;</a>
            </div>
            <div class="dashboard-card">
                <h3>Result Explorer</h3>
                <p>View, edit, or delete user accounts.</p>
                <a href="/admin/result_explorer.php" class="card-link">Manage Users &rarr;</a>
            </div>
            <div class="dashboard-card">
                <h3>Test Explorer</h3>
                <p>See existing tests</p>
                <a href="/admin/test_explorer.php" class="card-link">Manage Tests &rarr;</a>
            </div>
            <!-- <
            <div class="dashboard-card">
                <h3>Settings</h3>
                <p>Configure general website settings and options.</p>
                <a href="#" class="card-link">Go to Settings &rarr;</a>
            </div> -->
            <!-- NEW: Code Generator -->
            <div class="dashboard-card">
                <h3>Code Generator</h3>
                <p>Generate reusable code snippets or scaffolds for faster development.</p>
                <a href="/admin/code_generator.php" class="card-link">Open Generator &rarr;</a>
            </div>
        </div>
    </div>

</body>
</html>
