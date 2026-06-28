<?php
/**
 * get_courses.php
 *
 * Fetches a list of all courses from the database.
 * This is a protected API endpoint for admin use.
 */

// Use __DIR__ and relative paths to securely locate the library files.
// From /api/admin/, ../.. goes up to the project root.
require_once __DIR__ . '/../../app/lib/auth.php';
require_once __DIR__ . '/../../app/lib/db_connect.php';

// Ensure the user is an admin before proceeding.
require_admin();

try {
    // Select all courses to populate the first dropdown in the creator tool.
    $stmt = $pdo->query("SELECT course_id, name FROM course ORDER BY name ASC");
    $courses = $stmt->fetchAll(PDO::FETCH_ASSOC);

    header('Content-Type: application/json');
    echo json_encode($courses);

} catch (PDOException $e) {
    // Return a server error if the database query fails.
    http_response_code(500);
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Database query failed.']);
}