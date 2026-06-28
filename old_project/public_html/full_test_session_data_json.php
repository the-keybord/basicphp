<?php

header('Content-Type: application/json');

require_once 'app/lib/db_connect.php';
require_once 'app/lib/auth.php';
require_admin();

if (!isset($pdo)) {
    http_response_code(500);
    echo json_encode(['error' => 'Database connection object not found.']);
    exit;
}

// --- Input Validation ---
if (!isset($_GET['session_id']) || !filter_var($_GET['session_id'], FILTER_VALIDATE_INT)) {
    http_response_code(400);
    echo json_encode(['error' => 'A valid integer session_id is required.']);
    exit;
}

$sessionId = (int)$_GET['session_id'];

// --- Database Query ---
try {
    // 1. Fetch the session data
    $sql = "SELECT * FROM test_sessions WHERE id = :session_id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':session_id', $sessionId, PDO::PARAM_INT);
    $stmt->execute();
    $sessionData = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($sessionData) {
        // Decode internal JSON responses
        if ($sessionData['responses_json'] !== null) {
            $decodedResponses = json_decode($sessionData['responses_json']);
            if (json_last_error() === JSON_ERROR_NONE) {
                $sessionData['responses_json'] = $decodedResponses;
            }
        }

        // --- FETCH FULL TEST DATA ---
        if (!empty($sessionData['test_id'])) {
            $testId = $sessionData['test_id'];

            // A. Construct the absolute URL dynamically
            $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
            $domain = $_SERVER['HTTP_HOST'];
            $path = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
            $url = "$protocol$domain$path/full_test_data_json.php?test_id=" . urlencode($testId);

            // B. Prepare Cookies
            // We take the cookies from the user's current request and rebuild them string
            // so we can pass them to cURL.
            $cookieString = "";
            foreach ($_COOKIE as $key => $value) {
                $cookieString .= "$key=$value; ";
            }

            // C. CRITICAL: CLOSE SESSION WRITE PERMISSIONS
            // If we don't do this, the second script will hang waiting for this script
            // to release the session file. This prevents a "Deadlock".
            session_write_close();

            // D. Execute cURL
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 5); // 5 second timeout
            curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT'] ?? 'Mozilla/5.0'); // Pretend to be a browser
            curl_setopt($ch, CURLOPT_COOKIE, $cookieString); // FORWARD THE COOKIES

            // Optional: If you are on localhost with self-signed SSL, uncomment this:
            // curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            // curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

            $testDataJsonString = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $curlError = curl_error($ch);
            curl_close($ch);

            // E. Process Result
            if ($testDataJsonString !== false && $httpCode === 200) {
                $testData = json_decode($testDataJsonString);
                $sessionData['test_data'] = (json_last_error() === JSON_ERROR_NONE) ? $testData : ['error' => 'Invalid JSON received'];
            } else {
                $sessionData['test_data'] = [
                    'error' => 'Failed to fetch test data via cURL.',
                    'http_status' => $httpCode,
                    'curl_error' => $curlError,
                    'url' => $url
                ];
            }
        } else {
            $sessionData['test_data'] = null;
        }

        echo json_encode($sessionData, JSON_PRETTY_PRINT);

    } else {
        http_response_code(404);
        echo json_encode(['error' => 'Session not found.']);
    }

} 
catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'An internal server error occurred.']);
}
?>