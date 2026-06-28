<?php

// --- Helper Functions ---

/**
 * Compares a user's response with the correct answer.
 * Handles both simple strings and JSON string comparisons.
 *
 * @param string|null $userResponse The user's submitted answer.
 * @param string|null $correctAnswer The correct answer from the test data.
 * @return bool True if they match, false otherwise.
 */
function areAnswersEqual(?string $userResponse, ?string $correctAnswer): bool
{
    if ($userResponse === null || $correctAnswer === null) {
        return false;
    }

    // Normalize strings by removing newlines and carriage returns
    $userResponse = str_replace(["\r", "\n"], '', $userResponse);
    $correctAnswer = str_replace(["\r", "\n"], '', $correctAnswer);

    if ($userResponse === $correctAnswer) {
        return true;
    }

    // Attempt to decode both as JSON to handle complex question types
    $userDecoded = @json_decode($userResponse, true);
    $correctDecoded = @json_decode($correctAnswer, true);

    // If both are valid JSON arrays, compare them
    if (is_array($userDecoded) && is_array($correctDecoded)) {
        // Sort arrays to ensure order doesn't affect comparison
        ksort($userDecoded);
        ksort($correctDecoded);
        return $userDecoded == $correctDecoded;
    }

    // Otherwise, they don't match
    return false;
}

/**
 * Formats an answer for clean HTML display.
 * If the answer is a JSON string, it pretty-prints it.
 * Otherwise, it displays it as plain text.
 *
 * @param string|null $answer The answer string to format.
 * @return string The formatted HTML string.
 */
function formatAnswerForDisplay(?string $answer): string
{
    if ($answer === null) {
        return '<span class="text-muted"><em>(No answer provided)</em></span>';
    }
    
    $decoded = @json_decode($answer);

    // Check if it's a valid JSON object or array
    if (json_last_error() === JSON_ERROR_NONE && (is_object($decoded) || is_array($decoded))) {
        return '<pre>' . htmlspecialchars(json_encode($decoded, JSON_PRETTY_PRINT)) . '</pre>';
    }

    // Otherwise, treat as plain text
    return '<pre>' . htmlspecialchars($answer) . '</pre>';
}

// --- Main Script Logic ---

// 1. Get and validate the session_id
if (!isset($_GET['session_id']) || !filter_var($_GET['session_id'], FILTER_VALIDATE_INT)) {
    http_response_code(400);
    die("Error: Please provide a valid session_id GET parameter.");
}
$sessionId = (int)$_GET['session_id'];

// 2. Fetch the JSON data
$sourceUrl = "https://zece.info/full_test_session_data_json.php?session_id=" . $sessionId;

// --- FIX START: Use cURL with Cookies ---

// A. Prepare the cookies from your current browser session
$cookieString = "";
foreach ($_COOKIE as $key => $value) {
    $cookieString .= "$key=$value; ";
}

// B. CRITICAL: Unlock the session file
// This prevents the script from hanging (deadlock) while waiting for the API to respond.
session_write_close();

// C. Initialize cURL
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $sourceUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 10); // Wait up to 10 seconds
curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT'] ?? 'Mozilla/5.0');

// D. Forward the Cookies (This fixes the 403 Forbidden error)
curl_setopt($ch, CURLOPT_COOKIE, $cookieString);

// Execute
$jsonData = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$curlError = curl_error($ch);
curl_close($ch);

// --- FIX END ---

// Validate the result
if ($jsonData === false || $httpCode !== 200) {
    http_response_code(500);
    die("Error: Could not retrieve data. <br>HTTP Code: $httpCode <br>cURL Error: $curlError <br>URL: $sourceUrl");
}

// 3. Decode the JSON data
$data = json_decode($jsonData, true);

if (json_last_error() !== JSON_ERROR_NONE) {
    http_response_code(500);
    die("Error: Invalid JSON received from the source URL. JSON Error: " . json_last_error_msg());
}

// Extract data
$userResponses = $data['responses_json'] ?? [];
$correctAnswers = $data['test_data']['response_hashes'] ?? [];
$questionCount = count($userResponses);
$correctCount = 0;

// --- PRE-CALCULATE SCORE ---
$evaluationResults = []; 

if ($questionCount > 0) {
    for ($i = 0; $i < $questionCount; $i++) {
        $userResponse = $userResponses[$i] ?? null;
        $correctAnswer = $correctAnswers[$i] ?? null;
        
        $isCorrect = areAnswersEqual($userResponse, $correctAnswer);
        
        if ($isCorrect) {
            $correctCount++;
        }
        
        // Store the results for building the table later
        $evaluationResults[] = [
            'userResponse' => $userResponse,
            'correctAnswer' => $correctAnswer,
            'isCorrect' => $isCorrect
        ];
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test Session Evaluation - Session #<?php echo $sessionId; ?></title>
    <style>
        html {
            scroll-behavior: smooth; /* Smooth scrolling when clicking the top bubbles */
        }
        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f8f9fa;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 900px;
            margin: auto;
            background: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        h1, h2 {
            color: #0056b3;
            border-bottom: 2px solid #dee2e6;
            padding-bottom: 10px;
        }
        .session-info {
            background: #e9ecef;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 25px;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 10px;
        }
        .session-info div {
            padding: 5px;
        }
        .session-info strong {
            color: #495057;
        }
        
        /* --- NEW CSS FOR SUMMARY BAR --- */
        .summary-bar {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            justify-content: center;
            margin-bottom: 25px;
            padding: 15px;
            background: #fff;
            border: 1px solid #dee2e6;
            border-radius: 8px;
        }
        .summary-badge {
            width: 35px;
            height: 35px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            color: #fff;
            font-weight: bold;
            text-decoration: none;
            font-size: 0.9em;
            transition: transform 0.2s;
        }
        .summary-badge:hover {
            transform: scale(1.1);
            opacity: 0.9;
        }
        .summary-badge.correct {
            background-color: #28a745;
        }
        .summary-badge.incorrect {
            background-color: #dc3545;
        }
        /* ------------------------------- */

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            padding: 12px;
            border: 1px solid #dee2e6;
            text-align: left;
            vertical-align: top;
        }
        th {
            background-color: #f2f2f2;
            font-weight: 600;
        }
        .evaluation-row.correct {
            background-color: #d4edda; /* Light green */
        }
        .evaluation-row.incorrect {
            background-color: #f8d7da; /* Light red */
        }
        pre {
            white-space: pre-wrap;
            word-wrap: break-word;
            margin: 0;
            background: #fff;
            padding: 5px;
            border-radius: 4px;
            font-family: "SFMono-Regular", Consolas, "Liberation Mono", Menlo, Courier, monospace;
            font-size: 0.9em;
        }
        .text-muted {
            color: #6c757d;
        }
        .status-correct {
            color: #155724;
            font-weight: bold;
        }
        .status-incorrect {
            color: #721c24;
            font-weight: bold;
        }
        .final-score {
            font-size: 1.5em;
            font-weight: bold;
            text-align: center;
            margin-bottom: 25px; 
            padding: 20px;
            background: #0056b3;
            color: #fff;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Test Session Evaluation</h1>

        <div class="final-score">
            Final Score: <?php echo $correctCount; ?> / <?php echo $questionCount; ?>
        </div>

        <?php if ($questionCount > 0): ?>
            <div class="summary-bar">
                <?php foreach ($evaluationResults as $index => $result): ?>
                    <a href="#question-<?php echo $index; ?>" 
                       class="summary-badge <?php echo $result['isCorrect'] ? 'correct' : 'incorrect'; ?>"
                       title="<?php echo $result['isCorrect'] ? 'Correct' : 'Incorrect'; ?>">
                        <?php echo $index + 1; ?>
                    </a>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <div class="session-info">
            <div><strong>Student:</strong> <?php echo htmlspecialchars($data['firstname'] . ' ' . $data['lastname']); ?></div>
            <div><strong>Session ID:</strong> <?php echo htmlspecialchars($data['id']); ?></div>
            <div><strong>Test ID:</strong> <?php echo htmlspecialchars($data['test_id']); ?></div>
            <div><strong>Status:</strong> <?php echo htmlspecialchars(ucfirst($data['status'])); ?></div>
            <div><strong>Begin Time:</strong> <?php echo htmlspecialchars($data['begin_time']); ?></div>
            <div><strong>End Time:</strong> <?php echo htmlspecialchars($data['end_time']); ?></div>
        </div>

        <h2>Evaluation Details</h2>

        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Your Response</th>
                    <th>Correct Answer</th>
                    <th>Result</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($questionCount > 0): ?>
                    <?php foreach ($evaluationResults as $index => $result): ?>
                        <tr id="question-<?php echo $index; ?>" class="evaluation-row <?php echo $result['isCorrect'] ? 'correct' : 'incorrect'; ?>">
                            <td><?php echo $index + 1; ?></td>
                            <td><?php echo formatAnswerForDisplay($result['userResponse']); ?></td>
                            <td><?php echo formatAnswerForDisplay($result['correctAnswer']); ?></td>
                            <td>
                                <?php if ($result['isCorrect']): ?>
                                    <span class="status-correct">Correct</span>
                                <?php else: ?>
                                    <span class="status-incorrect">Incorrect</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4" style="text-align: center;">No responses found for this session.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

    </div>
</body>
</html>