<?php
// admin/upload_image.php
header('Content-Type: application/json');
require_once __DIR__ . '/../app/lib/auth.php';
require_admin();

$question_id = $_POST['question_id'] ?? null;

if (!$question_id) {
    echo json_encode(['success' => false, 'error' => 'Missing question_id']);
    exit;
}

if (!isset($_FILES['image'])) {
    echo json_encode(['success' => false, 'error' => 'No image uploaded']);
    exit;
}

$image = $_FILES['image'];
$allowed_types = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];

if (!in_array($image['type'], $allowed_types)) {
    echo json_encode(['success' => false, 'error' => 'Unsupported file type']);
    exit;
}

// Destination directory
$question_dir = __DIR__ . '/../app/questions/' . $question_id;
if (!is_dir($question_dir)) {
    mkdir($question_dir, 0755, true);
}

// Find the next available number for naming
$existing_files = glob($question_dir . "/image_*.*");
$next_number = count($existing_files) + 1;

// Use consistent naming scheme
$ext = strtolower(pathinfo($image['name'], PATHINFO_EXTENSION));
$filename = "image_" . $next_number . "." . $ext;
$filepath = $question_dir . '/' . $filename;

if (move_uploaded_file($image['tmp_name'], $filepath)) {
    $relative_path = "https://zece.info/app/questions/".$question_id."/" . $filename; // path relative to question.php
    echo json_encode([
        'success' => true,
        'filename' => $filename,
        'path' => $relative_path
    ]);
} else {
    echo json_encode(['success' => false, 'error' => 'Failed to save file']);
}
