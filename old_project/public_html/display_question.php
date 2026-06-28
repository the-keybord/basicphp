<?php
require_once __DIR__ . '/app/lib/auth.php';
require_once __DIR__ . '/app/lib/db_connect.php';

// --- NEW: Check which mode we are in ---
$is_component_only_mode = isset($_GET['component_only']) && $_GET['component_only'] === 'true';

$question_id = $_GET['id'] ?? null;
if (!$question_id) { die("No question ID provided."); }

// Permission Layer
if (!is_user_logged_in()) {
    // In a real app, redirect to a login page
    die("You do not have permission to view this question.");
}

$question_file_to_load = __DIR__ . "/app/questions/{$question_id}/question.php";
if (!file_exists($question_file_to_load)) {
    die("Question component not found.");
}

// --- NEW: Conditionally render the page wrapper ---
if (!$is_component_only_mode):
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>View Question</title>
    <link rel="stylesheet" href="/admin/style.css"> 
</head>
<body style="padding: 2rem;">
<?php endif; ?>

<?php
// This part runs in BOTH modes. It includes the container, which includes the question.
include __DIR__ . '/app/lib/question_container.php';
?>

<?php
// --- NEW: Conditionally render the closing tags ---
if (!$is_component_only_mode):
?>
</body>
</html>
<?php endif; ?>