<?php
require_once __DIR__ . '/../../app/lib/auth.php';
require_admin();
require_once __DIR__ . '/../../app/lib/db_connect.php';

header('Content-Type: application/json');

$question_id = $_POST['question_id'] ?? null;
$answer = $_POST['answer'] ?? null;

if (empty($question_id) || empty($answer)) {
    echo json_encode(['success' => false, 'message' => 'Question ID and Answer are required.']);
    exit;
}

$hashed_answer = $answer;//password_hash($answer, PASSWORD_DEFAULT);

try {
    // CHANGE: Table name updated to 'question'
    $sql = "UPDATE question SET response_hash = ? WHERE question_id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$hashed_answer, $question_id]);
    
    echo json_encode(['success' => true]);
    
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}