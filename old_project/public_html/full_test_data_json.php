<?php

// Set the content type header to indicate JSON response
header('Content-Type: application/json');

// Require the database connection file
require_once 'app/lib/db_connect.php';
require_once 'app/lib/auth.php';
require_admin();

// Check if the PDO connection object exists
if (!isset($pdo)) {
    // If not, output a JSON error and stop execution
    http_response_code(500);
    echo json_encode(['error' => 'Database connection object not found.']);
    exit;
}

try {
    // 1. Check for and validate the 'test_id' GET parameter
    if (!isset($_GET['test_id']) || !filter_var($_GET['test_id'], FILTER_VALIDATE_INT)) {
        http_response_code(400); // Bad Request
        echo json_encode(['error' => 'A valid integer test_id must be provided via GET parameter.']);
        exit;
    }
    $testId = (int)$_GET['test_id'];

    // 2. Fetch the specific test record from the 'tests' table
    $sqlTest = "SELECT test_id, course_id, question_list_json, created_at FROM tests WHERE test_id = ?";
    $stmtTest = $pdo->prepare($sqlTest);
    $stmtTest->execute([$testId]);
    $test = $stmtTest->fetch(PDO::FETCH_ASSOC);

    // 3. Handle case where the test is not found
    if (!$test) {
        http_response_code(404); // Not Found
        echo json_encode(['error' => 'Test with the specified ID not found.']);
        exit;
    }

    // 4. Decode the question list from the test data
    $questionIds = json_decode($test['question_list_json'], true);
    
    // Initialize response hashes array
    $orderedHashes = [];
    
    // 5. Proceed only if the question list is a valid JSON array and is not empty
    if (json_last_error() === JSON_ERROR_NONE && is_array($questionIds) && !empty($questionIds)) {
        // Use array_unique to make the database query more efficient
        $uniqueQuestionIds = array_values(array_unique($questionIds));
        
        // 6. Create placeholders for the IN clause of the SQL query (e.g., ?,?,?)
        $placeholders = implode(',', array_fill(0, count($uniqueQuestionIds), '?'));
        
        // 7. Fetch all relevant questions and their response hashes in a single query
        $sqlQuestions = "SELECT question_id, response_hash FROM question WHERE question_id IN ($placeholders)";
        $stmtQuestions = $pdo->prepare($sqlQuestions);
        $stmtQuestions->execute($uniqueQuestionIds);
        
        // 8. Create an associative array (map) of question_id => response_hash for quick lookups
        $questionHashesMap = [];
        while ($question = $stmtQuestions->fetch(PDO::FETCH_ASSOC)) {
            $questionHashesMap[$question['question_id']] = $question['response_hash'];
        }

        // 9. For each question ID in its original order, find its corresponding hash in the map
        foreach ($questionIds as $id) {
            // If a hash exists for the ID, add it; otherwise, add null
            $orderedHashes[] = isset($questionHashesMap[$id]) ? $questionHashesMap[$id] : null;
        }
    } elseif (json_last_error() !== JSON_ERROR_NONE || !is_array($questionIds)) {
        // If JSON is invalid, ensure questionIds is an empty array for consistent output
        $questionIds = [];
    }

    // 10. Build the final result object for the single test
    $result = [
        'test_id' => $test['test_id'],
        'course_id' => $test['course_id'],
        'created_at' => $test['created_at'],
        'question_ids' => $questionIds, // The list of question IDs
        'response_hashes' => $orderedHashes, // The new list of hashes in the correct order
    ];

    // 11. Encode the final object into JSON and output it
    echo json_encode($result);

} catch (PDOException $e) {
    // Handle any potential database errors during the process
    http_response_code(500); // Internal Server Error
    echo json_encode(['error' => "Could not execute query. " . $e->getMessage()]);
}

// Close the connection
unset($pdo);

?>

