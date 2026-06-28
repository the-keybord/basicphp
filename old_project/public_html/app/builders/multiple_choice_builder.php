<?php
// Note the paths are now relative to the /app/builders directory
require_once __DIR__ . '/../lib/auth.php';
require_admin(); // Ensure only an admin can access this builder

$question_id = $_GET['id'] ?? null;

if (!$question_id) {
    die("Error: No Question ID provided to the builder.");
}

// Initialize the message variable
$message = null;

// Check if the form has been submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize and retrieve form data
    $question = htmlspecialchars($_POST['question'] ?? '', ENT_QUOTES, 'UTF-8');
    $code_block = $_POST['code_block'] ?? '';
    $options_post = $_POST['options'] ?? [];
    $options_html = '';

    // Loop through the submitted options and build the HTML for them
    foreach ($options_post as $key => $value) {
        // We use nl2br to convert newlines in the textarea to <br> tags for proper display
        $option_text = nl2br(htmlspecialchars(trim($value), ENT_QUOTES, 'UTF-8'));
        $options_html .= htmlspecialchars($key) ."
        <div class=\"option border p-3 cursor-pointer rounded\">
            <code>" . $option_text . "</code>
        </div>";
    }

    // Prepare the code block HTML, only if it's not empty
    $code_block_html = '';
    if (!empty(trim($code_block))) {
        $code_block_html = "
        <div class=\"bg-gray-100 p-3 rounded mt-2 text-sm leading-6\">" . $code_block . "</div>";
    }

    // Store the PHP driver tag in a variable to be inserted into the template
    $php_driver_tag = "<?php require __DIR__ . '/../../drivers/multiple_choice_driver.php'; ?>";

    // This is the simplified HTML template for the question page that will be generated
    $template = <<<HTML
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Interactive Question</title>
<script src="https://cdn.tailwindcss.com"></script>
<style>
.selected { background-color: #dbeafe; }
pre, code { font-family: monospace; white-space: pre-wrap; }
</style>
</head>
<body>
<div class="w-full max-w-2xl mx-auto p-4">
    <!-- The Question Card -->
    <div class='question'>
        <p>$question</p>
        $code_block_html
    </div>
    <!-- The Options -->
    <div class="mt-4 space-y-2" id="options-container">
        $options_html
    </div>
    <!-- The Response Area -->
    <div class='response border p-2 mt-4 hidden'>
        <!-- Response will be populated by JavaScript -->
    </div>
</div>
$php_driver_tag
</body>
</html>
HTML;

    // Define the directory and file path based on the question ID
    $question_dir = __DIR__ . '/../questions/' . $question_id;
    $file_path = $question_dir . '/question.php';

    // Ensure the directory exists
    if (!is_dir($question_dir)) {
        // Try to create it recursively
        if (!mkdir($question_dir, 0755, true)) {
            $message = "Error: Could not create the directory. Please check folder permissions.";
        }
    }

    // If there is no message yet (i.e., directory exists or was created successfully), proceed to save the file
    if (is_null($message)) {
        if (file_put_contents($file_path, $template)) {
            // Redirect to the question creator with success
            header("Location: /admin/question_creator.php?build_success=true&id=" . urlencode($question_id));
            exit;
        } else {
            $message = "Error: Could not save the file. Please check folder permissions.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Multiple Choice Question Builder</title>
<script src="https://cdn.tailwindcss.com"></script>
<style>
body { font-family: 'Inter', sans-serif; }
@import url('https://rsms.me/inter/inter.css');
</style>
</head>
<body class="bg-gray-100 p-4 sm:p-6 md:p-8">
<div class="max-w-3xl mx-auto">
    <?php if (isset($message)): ?>
    <!-- Display Success or Error Message -->
    <div class="mb-6 p-4 rounded-lg shadow-md <?php echo strpos($message, 'Error') !== false ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800'; ?>" role="alert">
        <?php echo $message; ?>
    </div>
    <?php endif; ?>

    <div class="bg-white p-8 rounded-xl shadow-lg">
        <h1 class="text-3xl font-bold mb-2 text-gray-800">Question Builder</h1>
        <p class="text-gray-600 mb-6">Fill out the form below to create a new interactive question page.</p>
        <form method="POST" action="multiple_choice_builder.php?id=<?php echo htmlspecialchars($question_id); ?>" class="space-y-6">
            <!-- Question Text -->
            <div>
                <label for="question" class="block text-sm font-medium text-gray-700 mb-1">Question Text</label>
                <textarea id="question" name="question" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" rows="3" required placeholder="e.g., Which syntax should you use to create the object?"></textarea>
            </div>
            <!-- Optional Code Block -->
            <div>
                <label for="code_block" class="block text-sm font-medium text-gray-700 mb-1">Code Block (Optional)</label>
                <textarea id="code_block" name="code_block" class="w-full border-gray-300 rounded-md shadow-sm font-mono text-sm focus:ring-blue-500 focus:border-blue-500" rows="6" placeholder="e.g., ID | Name | Age..."></textarea>
            </div>
            <!-- Options -->
            <fieldset>
                <legend class="text-lg font-semibold text-gray-900 mb-2">Options</legend>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="option_a" class="block text-sm font-medium text-gray-700">Option A</label>
                        <textarea id="option_a" name="options[A]" class="mt-1 w-full border-gray-300 rounded-md shadow-sm font-mono text-sm focus:ring-blue-500 focus:border-blue-500" required></textarea>
                    </div>
                    <div>
                        <label for="option_b" class="block text-sm font-medium text-gray-700">Option B</label>
                        <textarea id="option_b" name="options[B]" class="mt-1 w-full border-gray-300 rounded-md shadow-sm font-mono text-sm focus:ring-blue-500 focus:border-blue-500" required></textarea>
                    </div>
                    <div>
                        <label for="option_c" class="block text-sm font-medium text-gray-700">Option C</label>
                        <textarea id="option_c" name="options[C]" class="mt-1 w-full border-gray-300 rounded-md shadow-sm font-mono text-sm focus:ring-blue-500 focus:border-blue-500" required></textarea>
                    </div>
                    <div>
                        <label for="option_d" class="block text-sm font-medium text-gray-700">Option D</label>
                        <textarea id="option_d" name="options[D]" class="mt-1 w-full border-gray-300 rounded-md shadow-sm font-mono text-sm focus:ring-blue-500 focus:border-blue-500" required></textarea>
                    </div>
                </div>
            </fieldset>
            <?php include __DIR__ . '/image_picker_component.php'; ?>
            <!-- Submit Button -->
            <div class="flex justify-end">
                <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Generate Question Page
                </button>
            </div>
        </form>
    </div>
</div>
</body>
</html>
