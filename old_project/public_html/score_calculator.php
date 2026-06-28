<?php
// score_calculator.php

require_once __DIR__ . '/app/lib/db_connect.php';
session_start();

// Step 1: Validate input
$session_id = $_GET['session_id'] ?? null;
if (!$session_id) {
    die("Error: session_id is required.");
}

try {
    // Step 2: Fetch test session data
    $stmt = $pdo->prepare("SELECT responses_json, test_id FROM test_sessions WHERE id = ?");
    $stmt->execute([$session_id]);
    $sessionData = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$sessionData) {
        die("Error: Invalid session_id.");
    }

    $responses = json_decode($sessionData['responses_json'], true);
    $test_id   = $sessionData['test_id'];

    // Step 3: Fetch question list from tests table
    $stmt = $pdo->prepare("SELECT question_list_json FROM tests WHERE test_id = ?");
    $stmt->execute([$test_id]);
    $testData = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$testData) {
        die("Error: Test not found.");
    }

    $question_list = json_decode($testData['question_list_json'], true);

    if (!is_array($question_list)) {
        die("Error: Invalid question list.");
    }

    // Step 4: Calculate score
    $score = 0;
    $total = count($question_list);

    $stmt = $pdo->prepare("SELECT response_hash FROM question WHERE question_id = ?");

    foreach ($question_list as $question_id) {
        $stmt->execute([$question_id]);
        $qData = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($qData) {
            $correct_hash = $qData['response_hash'];
            echo "Correct Hash for QID $question_id: $correct_hash\n"; // Debug line
            $student_response = $responses[$question_id] ?? null;
            echo "Student Response for QID $question_id: " . ($student_response ?? 'NULL') . "\n"; // Debug line
            // Compare student's response with correct hash
            if ($student_response && hash('sha256', $student_response) === $correct_hash) {
                $score++;
            }
        }
    }

    // Step 5: Output result
    $result = [
        "session_id" => $session_id,
        "test_id"    => $test_id,
        "score"      => $score,
        "total"      => $total,
        "percent"    => $total > 0 ? round(($score / $total) * 100, 2) : 0
    ];

    header('Content-Type: application/json');
    echo json_encode($result);

} catch (Exception $e) {
    die("Error: " . $e->getMessage());
}
