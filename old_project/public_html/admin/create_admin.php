<?php
// A secure password for your first admin account
$passwordToHash = 'admin123';

// Hash the password using modern, secure standards
$hashedPassword = password_hash($passwordToHash, PASSWORD_DEFAULT);

// The SQL query to insert the new admin user
$sql = "INSERT INTO users (username, email, password, role) VALUES ('admin', 'admin@zece.info', '{$hashedPassword}', 1);";

echo "<h3>Run this SQL query in your database (e.g., via phpMyAdmin) to create your admin account:</h3>";
echo "<pre style='background:#f4f4f4; padding:15px; border-radius:5px;'>{$sql}</pre>";
?>