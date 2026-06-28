<?php
require_once __DIR__ . '/app/lib/db_connect.php';

$code = $_GET['code'] ?? null;
$error_message = null;
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $firstname = trim($_POST['firstname'] ?? '');
    $lastname = trim($_POST['lastname'] ?? '');
    $code = $_POST['code'] ?? null;

    if (!$firstname || !$lastname || !$code) {
        $error_message = "Please provide both first name and last name.";
    } else {
        try {
            // Fetch test info using code
            // MODIFIED: Select the 'expires_at' column as well
            $stmt = $pdo->prepare("SELECT object_id, parameters, expires_at FROM codes WHERE code = ?");
            $stmt->execute([$code]);
            $code_data = $stmt->fetch(PDO::FETCH_ASSOC);

            // MODIFIED: Added check for expiration
            if (!$code_data) {
                $error_message = "Invalid code."; // More specific error
            } else if ($code_data['expires_at'] !== null && strtotime($code_data['expires_at']) < time()) {
                $error_message = "This code has expired."; // Specific expiration error
            } else {
                // Code is valid and not expired, proceed
                $test_id = $code_data['object_id'];

                // Insert new test session
                $stmt = $pdo->prepare("INSERT INTO test_sessions (firstname, lastname, begin_time, test_id) 
                                        VALUES (?, ?, NOW(), ?)");
                $stmt->execute([$firstname, $lastname, $test_id]);

                $session_id = $pdo->lastInsertId();

                // Redirect to take_test.php with session_id
                header("Location: take_test_old.php?sid=" . urlencode($session_id));
                exit();
            }
        } catch (PDOException $e) {
            $error_message = "Database error: please try again later.";
            error_log("New Session Error: " . $e->getMessage());
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Start Test</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f4f7f9; padding: 20px; }
        .form-box { max-width: 500px; margin: 50px auto; background: #fff; padding: 25px; border-radius: 8px; box-shadow: 0 2px 6px rgba(0,0,0,.1); }
        label { display: block; margin-bottom: 8px; font-weight: bold; }
        input { width: 100%; padding: 10px; margin-bottom: 15px; border-radius: 4px; border: 1px solid #ccc; }
        button { padding: 12px 20px; background: #007bff; color: #fff; border: none; border-radius: 4px; cursor: pointer; }
        button:hover { background: #0056b3; }
        .error { color: #b00020; margin-bottom: 15px; }
    </style>
</head>
<body>
    <div class="form-box">
        <h1>Start Your Test</h1>
        <?php if ($error_message): ?>
            <div class="error"><?= htmlspecialchars($error_message) ?></div>
        <?php endif; ?>
        <form method="POST">
            <input type="hidden" name="code" value="<?= htmlspecialchars($code) ?>">
            <label for="firstname">First Name</label>
            <input type="text" name="firstname" required>
            
            <label for="lastname">Last Name</label>
            <input type="text" name="lastname" required>
            
            <button type="submit">Begin Test</button>
        </form>
    </div>
</body>
</html>