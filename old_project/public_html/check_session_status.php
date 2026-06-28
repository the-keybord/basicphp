<?php
// Set content type to JSON
header('Content-Type: application/json');

// Step 1: Include DB connection
require_once __DIR__ . '/app/lib/db_connect.php';

// Step 2: Get session_id from the query parameter
$session_id = $_GET['session_id'] ?? null;

if (!$session_id) {
    // Send error if no session ID
    http_response_code(400); // Bad Request
    echo json_encode(['status' => 'error', 'message' => 'No session ID provided.']);
    exit;
}

try {
    // Step 3: Prepare and execute the query
    $stmt = $pdo->prepare("SELECT status FROM test_sessions WHERE id = ?");
    $stmt->execute([$session_id]);
    $session_data = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($session_data) {
        // Step 4: Send the status as JSON
        echo json_encode(['status' => $session_data['status']]);
    } else {
        // Send error if session ID is invalid
        http_response_code(404); // Not Found
        echo json_encode(['status' => 'error', 'message' => 'Invalid session ID.']);
    }

} catch (PDOException $e) {
    // Handle database errors
    error_log("Check Status Error: " . $e->getMessage());
    http_response_code(500); // Internal Server Error
    echo json_encode(['status' => 'error', 'message' => 'Database error.']);
}