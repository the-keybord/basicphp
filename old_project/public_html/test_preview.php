<?php
require_once __DIR__ . '/app/lib/auth.php';
require_admin();
require_once __DIR__ . '/app/lib/db_connect.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
                <div id="questions-container">
                    <?php
                    /**
                     * Main PHP logic to fetch and display questions.
                     */
                    if (isset($_GET['test_id']) && !empty($_GET['test_id'])) {
                        // Connection is now handled by '/../app/lib/db_connect.php'
                        // which is assumed to provide the $pdo object.
                        $dbTable = 'tests'; // The table shown in your image

                        // --- 2. Sanitize Input ---
                        $testId = filter_input(INPUT_GET, 'test_id', FILTER_VALIDATE_INT);

                        if ($testId === false) {
                            echo '<div class="p-4 bg-red-100 text-red-700 border border-red-200 rounded-md"><strong>Error:</strong> Invalid Test ID. Please enter a number.</div>';
                        } else {
                            try {
                                // --- 3. Database Connection is now handled by the require_once above ---

                                // --- 4. Fetch the Question List ---
                                $stmt = $pdo->prepare("SELECT question_list_json FROM {$dbTable} WHERE test_id = :test_id");
                                $stmt->execute([':test_id' => $testId]);
                                $result = $stmt->fetch();

                                if ($result) {
                                    // --- 5. Decode JSON and Get Question IDs ---
                                    $questionListJson = $result['question_list_json'];
                                    $questionIds = json_decode($questionListJson, true);

                                    if (json_last_error() !== JSON_ERROR_NONE || !is_array($questionIds)) {
                                        echo '<div class="p-4 bg-red-100 text-red-700 border border-red-200 rounded-md"><strong>Error:</strong> Could not parse the question list. The JSON data might be malformed.</div>';
                                    } elseif (empty($questionIds)) {
                                        echo '<div class="p-4 bg-yellow-100 text-yellow-800 border border-yellow-200 rounded-md">This test has no questions assigned to it.</div>';
                                    } else {
                                        echo "<h2 class='text-xl font-semibold mb-4'>Displaying Questions for Test ID: {$testId}</h2>";
                                        
                                        // --- 6. Loop and Include Each Question File ---
                                        foreach ($questionIds as $questionId) {
                                            $questionId = (int)$questionId; // Ensure it's an integer
                                            $contentFile = "app/questions/{$questionId}/question.php";

                                            echo '<div class="mb-6 border border-gray-200 rounded-lg p-6 bg-gray-50">';
                                            echo "<p class='text-sm text-gray-500 mb-2'><em>Loading from: <code>{$contentFile}</code></em></p>";
                                            
                                            // This is the core logic from your example
                                            if (file_exists($contentFile)) {
                                                // In a real application, the content of the question file would appear here.
                                                // For this example, we'll just confirm it was found.
                                                include $contentFile;

                                            } else {
                                                echo '<div class="text-red-700"><strong>Error:</strong> Question file not found at the specified path.</div>';
                                            }
                                            echo '</div>';
                                        }
                                    }
                                } else {
                                    echo "<div class='p-4 bg-yellow-100 text-yellow-800 border border-yellow-200 rounded-md'><strong>Notice:</strong> No test found with ID: {$testId}.</div>";
                                }

                            } catch (PDOException $e) {
                                // Don't show detailed errors in a production environment
                                // error_log($e->getMessage());
                                echo '<div class="p-4 bg-red-100 text-red-700 border border-red-200 rounded-md"><strong>Database Error:</strong> Could not connect to the database. Please check the configuration.</div>';
                            }
                        }
                    } else {
                         echo '<div class="p-4 bg-blue-100 text-blue-800 border border-blue-200 rounded-md">Please enter a Test ID to begin.</div>';
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
</body>
</html>

