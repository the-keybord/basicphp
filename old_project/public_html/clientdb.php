<?php
// Prevent direct web access
if (basename($_SERVER['SCRIPT_FILENAME']) === basename(__FILE__)) {
    http_response_code(403);
    exit("Forbidden");
}

// Database credentials
$host = "localhost";
$user = "kdlsqwnb_zece_client";
$pass = "PeMlRk(JDXvi4";
$db   = "kdlsqwnb_zece";

// Create connection
$conn = new mysqli($host, $user, $pass, $db);

// Check connection
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}
?>
