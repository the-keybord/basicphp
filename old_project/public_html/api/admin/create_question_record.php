<?php
/**
 * api_create_question_record.php
 *
 * Handles Stage 1 of the Question Creator.
 * Receives a domain_id via POST, creates a new record in the 'question' table,
 * and returns the new question_id as JSON.
 */

// Use the correct paths to our shared libraries
require_once __DIR__ . '/../../app/lib/auth.php';
require_once __DIR__ . '/../../app/lib/db_connect.php';

// Set the header to JSON early to ensure all responses are correctly formatted
header('Content-Type: application/json');

// 1. SECURITY: Ensure the user is an admin before allowing record creation.
require_admin();

// 2. INPUT VALIDATION: Get the domain_id from the POST request and validate it.
$domain_id = $_POST['domain_id'] ?? null;


// Exit if the domain_id is missing or is not a number.
if (empty($domain_id) || !ctype_digit($domain_id)) {
    // Send a 400 Bad Request status code for invalid input
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'A valid Domain ID is required.']);
    exit;
}

// 3. DATABASE QUERY: Insert the new record safely.
try {
    // Use a prepared statement to prevent SQL injection.
    $sql = "INSERT INTO question (domain_id) VALUES (?)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$domain_id]);
    
    // Get the ID of the new row we just inserted.
    $new_id = $pdo->lastInsertId();
    
    // 4. RESPOND WITH JSON: Send a success message and the new ID back.
    echo json_encode(['success' => true, 'question_id' => $new_id]);
    
} catch (PDOException $e) {
    // 5. ERROR HANDLING: If the database insert fails, send a server error.
    http_response_code(500);
    // In a production environment, you would log the detailed error ($e->getMessage()) instead of sending it.
    echo json_encode(['success' => false, 'message' => 'A database error occurred while creating the question record.']);
}