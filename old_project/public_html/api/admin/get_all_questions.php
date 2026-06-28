<?php
require_once __DIR__ . '/../../app/lib/auth.php';
require_admin();
require_once __DIR__ . '/../../app/lib/db_connect.php';

// Set a higher memory limit and longer execution time for this heavy script.
//ini_set('memory_limit', '512M');
//set_time_limit(300); // 5 minutes

// 1. Get all question metadata from the database
$stmt = $pdo->query("SELECT question_id, domain_id FROM question");
$questions_metadata = $stmt->fetchAll(PDO::FETCH_ASSOC);

$all_questions_data = [];

// 2. Loop through each question to get its rendered HTML content
foreach ($questions_metadata as $meta) {
    $question_id = $meta['question_id'];
    $file_path = __DIR__ . "/../../app/questions/{$question_id}/question.php";
    
    $content = ''; // Default to empty string
    
    if (file_exists($file_path)) {
        // Use output buffering to capture the included file's HTML
        ob_start();
        include $file_path;
        $content = ob_get_clean();
    }
    
    // 3. Combine database metadata with the rendered content
    $all_questions_data[] = [
        'question_id' => $question_id,
        'domain_id' => $meta['domain_id'],
        'content' => $content // The full HTML is now part of the data
    ];
}

// 4. Send the complete package back as JSON
header('Content-Type: application/json');
echo json_encode($all_questions_data);