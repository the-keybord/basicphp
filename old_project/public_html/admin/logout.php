<?php
// Start the session
session_start();

// Unset all of the session variables
$_SESSION = [];

// Destroy the session
session_destroy();

// Redirect to the login page with a logout message
header('Location: index.php?logout=1');
exit;
?>