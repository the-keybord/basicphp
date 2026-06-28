<?php
/**
 * get_domains.php
 *
 * Fetches a list of domains associated with a specific course_id.
 * This is a protected API endpoint for the question creator tool.
 */

// Use the correct paths to our shared libraries
require_once __DIR__ . '/../../app/lib/auth.php';
require_once __DIR__ . '/../../app/lib/db_connect.php';

// 1. SECURITY: Ensure the user is an admin before proceeding.
require_admin();

// 2. INPUT VALIDATION: Get the course_id from the URL and validate it.
$course_id = $_GET['course_id'] ?? null;

// Exit gracefully if the course_id is missing or not a number.
if (empty($course_id) || !ctype_digit($course_id)) {
    header('Content-Type: application/json');
    echo json_encode([]); // Return an empty JSON array
    exit;
}

// 3. DATABASE QUERY: Fetch the domains safely.
try {
    // Use a prepared statement to prevent SQL injection.
    $stmt = $pdo->prepare("
        SELECT domain_id, name, default_count
        FROM domain 
        WHERE course_id = ? 
        ORDER BY name ASC
    ");
    $stmt->execute([$course_id]);
    $domains = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // 4. RESPOND WITH JSON: Send the results back to the JavaScript.
    header('Content-Type: application/json');
    echo json_encode($domains);

} catch (PDOException $e) {
    // 5. ERROR HANDLING: If the database query fails, send a server error.
    http_response_code(500);
    header('Content-Type: application/json');
    // In a production environment, you would log the detailed error instead of sending it.
    echo json_encode(['success' => false, 'message' => 'Database query failed.']);
}