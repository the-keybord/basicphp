<?php
// This page acts as the main entry point for all generated codes.
// It validates the code and redirects the user to the correct page based on the code's type.

// Step 1: Include the database connection.
require_once __DIR__ . '/app/lib/db_connect.php';

// Step 2: Get the code from the URL and initialize an error message variable.
$code = $_GET['code'] ?? null;
$error_message = null;

// Step 3: Check if a code was provided.
if (!$code) {
    $error_message = "No access code was provided. Please use the link you were given.";
} else {
    // Step 4: Validate the provided code against the database.
    try {
        $stmt = $pdo->prepare("SELECT expires_at, object_id, type_id FROM codes WHERE code = ?");
        $stmt->execute([$code]);
        $code_data = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$code_data) {
            $error_message = "The access code you provided is invalid.";
        } elseif ($code_data['expires_at'] && strtotime($code_data['expires_at']) < time()) {
            $error_message = "This access code has expired and can no longer be used.";
        } else {
            // If the code is valid, redirect based on its type_id.
            $type_id = $code_data['type_id'];
            $redirect_url = '';

            switch ($type_id) {
    case 4: // Test Access
        // Instead of going directly to take_test.php, go through new_session.php
        $redirect_url = 'new_session.php?code=' . urlencode($code);
        break;
    case 1: // Document Access
        $redirect_url = 'view_document.php?code=' . urlencode($code);
        break;
    case 0: // General Test Redirect
        $redirect_url = 'test.php?code=' . urlencode($code);
        break;
    default:
        $error_message = "The provided code has an unknown type and cannot be processed.";
        break;
}


            if ($redirect_url) {
                header("Location: " . $redirect_url);
                exit(); // Always call exit() after a header redirect.
            }
        }
    } catch (PDOException $e) {
        $error_message = "A database error occurred. Please try again later.";
        error_log("Code Router Error: " . $e->getMessage());
    }
}

// Step 5: If any error occurred, display it.
// This part of the page will only be reached if there's an error.
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invalid Code</title>
    <style>
        body { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif; margin: 0; padding: 20px; background-color: #f4f7f9; }
        .message-box { max-width: 800px; margin: 40px auto; padding: 25px; border-radius: 8px; text-align: center; background-color: #f8d7da; color: #721c24; }
        h1 { margin-top: 0; }
    </style>
</head>
<body>
    <div class="message-box">
        <h1>Access Denied</h1>
        <p><?php echo htmlspecialchars($error_message); ?></p>
    </div>
</body>
</html>
