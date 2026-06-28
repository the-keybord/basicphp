<?php
require_once __DIR__ . '/../app/lib/auth.php';
require_admin();
require_once __DIR__ . '/../app/lib/db_connect.php';

// Assuming your database connection object is named $pdo
// and the table name is 'tests' based on the image.
try {
    $stmt = $pdo->query("SELECT test_id, course_id, question_list_json, created_at FROM tests ORDER BY created_at DESC");
    $tests = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    // It's a good practice to handle potential database errors.
    die("Could not connect to the database or query failed: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test List</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        /* A little extra style for readability */
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>
<body class="bg-gray-100 text-gray-800">

    <div class="container mx-auto p-4 sm:p-6 lg:p-8">
        <h1 class="text-2xl sm:text-3xl font-bold mb-6 text-gray-900">Available Tests</h1>

        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <ul class="divide-y divide-gray-200">
                <?php if (count($tests) > 0): ?>
                    <?php foreach ($tests as $test): ?>
                        <li class="p-4 sm:p-6 hover:bg-gray-50 transition-colors duration-200">
                            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                                <div>
                                    <p class="text-lg font-semibold text-indigo-600">Test ID: <?php echo htmlspecialchars($test['test_id']); ?></p>
                                    <p class="text-sm text-gray-600 mt-1">Course ID: <?php echo htmlspecialchars($test['course_id']); ?></p>
                                </div>
                                <div class="mt-4 sm:mt-0 sm:text-right">
                                     <p class="text-sm font-medium text-gray-700">Questions:</p>
                                     <?php
                                        // Decode the JSON string into a PHP array
                                        $question_ids = json_decode($test['question_list_json'], true);
                                     ?>
                                     <p class="text-sm text-gray-500">
                                        <?php
                                            if (json_last_error() === JSON_ERROR_NONE && is_array($question_ids)) {
                                                echo htmlspecialchars(implode(', ', $question_ids));
                                            } else {
                                                // Display the raw string if it's not valid JSON
                                                echo 'Invalid question format: ' . htmlspecialchars($test['question_list_json']);
                                            }
                                        ?>
                                     </p>
                                     <a href="https://zece.info/test_preview.php?test_id=<?php echo urlencode($test['test_id']); ?>"
               class="inline-block bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium py-2 px-4 rounded-lg transition-colors duration-200">
                Preview
            </a>
                                </div>
                            </div>
                            <div class="mt-4 text-xs text-gray-400 text-right">
                                Created on: <?php echo htmlspecialchars(date('F j, Y, g:i a', strtotime($test['created_at']))); ?>
                            </div>
                        </li>
                    <?php endforeach; ?>
                <?php else: ?>
                    <li class="p-6 text-center text-gray-500">
                        No tests found in the database.
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>

</body>
</html>
