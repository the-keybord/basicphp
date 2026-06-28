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
    $options_post = $_POST['sentences'] ?? [];
    $options_html = '';

    // Loop through the submitted options and build the HTML for them
    foreach ($options_post as $key => $value) {
        $option_text = nl2br(htmlspecialchars(trim($value), ENT_QUOTES, 'UTF-8'));
        $options_html .= 
        "<div class=\"option\">
            <code>" . $option_text . "</code>
        </div>";
    }

    // Prepare the code block HTML, only if it's not empty
    $code_block_html = '';
    if (!empty(trim($code_block))) {
        $code_block_html = "
        <pre>" . $code_block . "</pre>";
    }

    // Store the PHP driver tag in a variable to be inserted into the template
    $php_driver_tag = "<?php require __DIR__ . '/../../drivers/true_driver.php'; ?>";

    // This is the simplified HTML template for the question page that will be generated
    $template = <<<HTML

<div class="w-full max-w-2xl mx-auto p-4">
    <!-- The Question Card -->
    <div class='question'>
        <p>$question</p>
        $code_block_html
    </div>
    <div class='options-container'>
        $options_html
    </div>
    <div class='response hidden'>

    </div>
</div>
$php_driver_tag

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
<title>True/False Question Builder</title>
<script src="https://cdn.tailwindcss.com"></script>
<style>
body { font-family: 'Inter', sans-serif; }
@import url('https://rsms.me/inter/inter.css');
</style>
</head>
<body class="bg-gray-100 p-4 sm:p-6 md:p-8">
<div class="max-w-3xl mx-auto">
    <?php if (isset($message)): ?>
    <div class="mb-6 p-4 rounded-lg shadow-md <?php echo strpos($message, 'Error') !== false ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800'; ?>" role="alert">
        <?php echo $message; ?>
    </div>
    <?php endif; ?>

    <div class="bg-white p-8 rounded-xl shadow-lg">
        <h1 class="text-3xl font-bold mb-2 text-gray-800">True/False Builder</h1>
        <p class="text-gray-600 mb-6">Create a question where the user must evaluate one or more sentences as true or false.</p>
        <form method="POST" action="true_builder.php?id=<?php echo htmlspecialchars($question_id); ?>" class="space-y-6">
            <div>
                <label for="question" class="block text-sm font-medium text-gray-700 mb-1">Main Question Text</label>
                <textarea id="question" name="question" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" rows="3" placeholder="e.g., Based on the code, determine if the following statements are true or false."></textarea>
            </div>
            <div>
                <label for="code_block" class="block text-sm font-medium text-gray-700 mb-1">Code Block (Optional)</label>
                <textarea id="code_block" name="code_block" class="w-full border-gray-300 rounded-md shadow-sm font-mono text-sm focus:ring-blue-500 focus:border-blue-500" rows="6" placeholder="e.g., let user = { name: 'Alex', premium: true };"></textarea>
            </div>
            <fieldset>
                <legend class="text-lg font-semibold text-gray-900 mb-2">Sentences to Evaluate</legend>
                <div id="sentences-container" class="space-y-4">
                    <div class="flex items-start space-x-2">
                        <textarea name="sentences[]" class="w-full border-gray-300 rounded-md shadow-sm font-mono text-sm focus:ring-blue-500 focus:border-blue-500" rows="2" required placeholder="e.g., The user object has a 'name' property."></textarea>
                        </div>
                </div>
                <div class="mt-4">
                    <button type="button" id="add-sentence" class="py-2 px-3 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Add Another Sentence
                    </button>
                </div>
            </fieldset>

            <?php include __DIR__ . '/image_picker_component.php'; ?>

            <div class="flex justify-end pt-4">
                <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Generate Question Page
                </button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const addButton = document.getElementById('add-sentence');
    const container = document.getElementById('sentences-container');
    const maxSentences = 10;

    const updateButtonState = () => {
        const currentCount = container.querySelectorAll('textarea').length;
        addButton.disabled = currentCount >= maxSentences;
        if (addButton.disabled) {
            addButton.textContent = 'Maximum sentences reached';
        } else {
            addButton.textContent = 'Add Another Sentence';
        }
    };

    addButton.addEventListener('click', () => {
        const currentCount = container.querySelectorAll('textarea').length;
        if (currentCount < maxSentences) {
            const newSentenceDiv = document.createElement('div');
            newSentenceDiv.className = 'flex items-start space-x-2';
            newSentenceDiv.innerHTML = `
                <textarea name="sentences[]" class="w-full border-gray-300 rounded-md shadow-sm font-mono text-sm focus:ring-blue-500 focus:border-blue-500" rows="2" placeholder="Enter another sentence..."></textarea>
                <button type="button" class="remove-sentence mt-1.5 py-1 px-2 text-sm font-medium text-red-600 hover:text-red-800" title="Remove sentence">&times;</button>
            `;
            container.appendChild(newSentenceDiv);
            updateButtonState();
        }
    });

    container.addEventListener('click', function(e) {
        if (e.target && e.target.classList.contains('remove-sentence')) {
            e.target.closest('.flex').remove();
            updateButtonState();
        }
    });

    // Initial check in case you start with more than one for some reason
    updateButtonState();
});
</script>

</body>
</html>