<?php
// --- List of valid codes and their target pages ---
$validCodes = [
    "abc123" => "clasatest1.php",
    "xyz789" => "documents/manual.pdf",
    "test456" => "info/about.html",
    "promo2025" => "offers/discount.html"
];

// --- Get code from URL ---
$code = isset($_GET['code']) ? trim($_GET['code']) : null;

// --- Check if code exists ---
if ($code && array_key_exists($code, $validCodes)) {
    // Redirect to the corresponding page
    header("Location: " . $validCodes[$code]);
    exit();
} else {
    // Invalid or missing code
    http_response_code(403);
    echo "<h2>Invalid or missing access code.</h2>";
    echo "<p>Please check your link or contact the administrator.</p>";
}
?>
