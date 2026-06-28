<?php
require_once __DIR__ . '/app/lib/db_connect.php';

// Read raw JSON body
$input = file_get_contents("php://input");
$data = json_decode($input, true);

if (!$data || empty($data['session_id']) || empty($data['answers'])) {
    http_response_code(400);
    echo json_encode(["error" => "Invalid request. Session ID and answers are required."]);
    exit();
}

$session_id = $data['session_id'];
$answers    = $data['answers'];

try {
    // Check if session exists
    $stmt = $pdo->prepare("SELECT id, status FROM test_sessions WHERE id = ?");
    $stmt->execute([$session_id]);
    $session = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$session) {
        http_response_code(404);
        echo json_encode(["error" => "Session not found."]);
        exit();
    }

    // Prevent double submission
    if ($session['status'] === 'submitted') {
        http_response_code(409);
        echo json_encode(["error" => "This session has already been submitted."]);
        exit();
    }

    // Save responses and mark session as submitted
    $stmt = $pdo->prepare("UPDATE test_sessions 
                           SET responses_json = ?, end_time = NOW(), status = 'submitted'
                           WHERE id = ?");
    $stmt->execute([json_encode($answers), $session_id]);

    echo json_encode([
        "success" => true,
        "message" => "Answers saved successfully.",
        "session_id" => $session_id
    ]);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(["error" => "Database error: " . $e->getMessage()]);
    error_log("Submit Answers Error: " . $e->getMessage());
}
