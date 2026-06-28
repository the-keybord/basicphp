<?php
require_once __DIR__ . '/../app/lib/auth.php';
require_admin();
require_once __DIR__ . '/../app/lib/db_connect.php';

// Step 1: Validate input
$question_id = $_GET['id'] ?? null;

if (!$question_id || !is_numeric($question_id)) {
    die("Invalid question ID.");
}

// Step 2: Check if question exists
$stmt = $pdo->prepare("SELECT question_id FROM question WHERE question_id = ?");
$stmt->execute([$question_id]);
$question = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$question) {
    die("Question not found.");
}

// Step 3: Delete question from database
$delete_stmt = $pdo->prepare("DELETE FROM question WHERE question_id = ?");
$delete_stmt->execute([$question_id]);

// Step 4: Delete question folder if it exists
$question_dir = __DIR__ . "/../app/questions/{$question_id}";

if (is_dir($question_dir)) {
    deleteDirectory($question_dir);
}

// Step 5: Redirect back with a success message
header("Location: question_explorer.php?deleted=1");
exit();

/**
 * Recursively deletes a directory and its contents
 */
function deleteDirectory($dir) {
    $files = array_diff(scandir($dir), ['.', '..']);
    foreach ($files as $file) {
        $path = "$dir/$file";
        if (is_dir($path)) {
            deleteDirectory($path);
        } else {
            unlink($path);
        }
    }
    rmdir($dir);
}
?>
