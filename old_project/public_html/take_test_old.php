<?php
// Step 1: Include DB connection
require_once __DIR__ . '/app/lib/db_connect.php';
session_start();

// Step 2: Get session_id
$session_id = $_GET['sid'] ?? null;
$error_message = null;
$test_id = null;
$question_ids = [];
$student_firstname = null;
$student_lastname = null;

// Step 3: Validate session_id and load session details
if (!$session_id) {
    $error_message = "No session ID provided.";
} else {
    try {
        $stmt = $pdo->prepare("SELECT firstname, lastname, test_id, begin_time,status FROM test_sessions WHERE id = ?");
        $stmt->execute([$session_id]);
        $session_data = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$session_data) {
            $error_message = "Invalid session ID. Please restart the test.";
        } else {
            $student_firstname = $session_data['firstname'];
            $student_lastname  = $session_data['lastname'];
            $test_id           = $session_data['test_id'];
            if ($session_data['status'] === 'submitted') {
                // Prevent access if already submitted
                echo '<div class="message-box error"><p><strong>Error:</strong> This test session has already been submitted. You cannot access the test again.</p></div>';
                exit;
            }
        }
    } catch (PDOException $e) {
        $error_message = "Database error. Please try again later.";
        error_log("Take Test Error: " . $e->getMessage());
    }
}

// Step 4: If valid session, fetch the test questions
if ($test_id) {
    $stmt = $pdo->prepare("SELECT question_list_json FROM tests WHERE test_id = ?");
    $stmt->execute([$test_id]);
    $test_data = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($test_data && !empty($test_data['question_list_json'])) {
        $question_ids = json_decode($test_data['question_list_json'], true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            $error_message = "Error: Test data is corrupted.";
            $question_ids = [];
        }
    } else {
        $error_message = "Error: Test not found.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Take the Test</title>
    <style>
        body { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif; margin: 0; padding: 20px; background-color: #f4f7f9; }
        .main-container { max-width: 800px; margin: 20px auto; }
        /* */
        .question-container { padding: 25px; border: 1px solid #ddd; border-radius: 8px; background-color: #fff; margin-bottom: 20px; }
        .message-box { padding: 25px; border-radius: 8px; text-align: center; }
        .message-box.error { background-color: #f8d7da; color: #721c24; }
        .message-box.info { background-color: #d1ecf1; color: #0c5460; }
        button { display: inline-block; padding: 12px 25px; font-size: 16px; font-weight: bold; color: #fff; background-color: #007bff; border: none; border-radius: 5px; cursor: pointer; }
        button:hover { background-color: #0056b3; }
        button:disabled { background-color: #ccc; cursor: not-allowed; }
        
        /* */
        #pagination-controls { display: flex; justify-content: space-between; align-items: center; margin-top: 20px; }
        #question-counter { font-size: 16px; font-weight: bold; color: #555; }
    </style>
</head>
<body>

<div class="main-container">

    <h1>Test Platform</h1>

    <?php if ($error_message): ?>
        <div class="message-box error">
            <p><strong>Error:</strong> <?= htmlspecialchars($error_message) ?></p>
        </div>

    <?php elseif (!empty($question_ids)): ?>
        <div class="message-box info">
            <p>Welcome, <strong><?= htmlspecialchars($student_firstname . ' ' . $student_lastname) ?></strong>. Good luck!</p>
        </div>
        
        <h2>Test Questions</h2>

        <div id="question-wrapper">
            <?php foreach ($question_ids as $index => $question_id): ?>
                <div class="question-container" data-original-index="<?= $index ?>" style="display: none;">
                    <?php
                        // We display the *visual* question number based on the loop, but it will be shuffled
                        // echo $index + 1; // This line is now handled by the JS counter
                        $contentFile = __DIR__ . '/app/questions/' . $question_id . '/question.php';
                        if (file_exists($contentFile)) {
                            include $contentFile;
                        } else {
                            echo '<p style="color: #d9534f;"><strong>Error:</strong> Missing question with ID ' . htmlspecialchars($question_id) . '.</p>';
                        }
                    ?>
                </div>
            <?php endforeach; ?>
        </div>
        
        <div id="pagination-controls" style="display: none;"> <button id="prevBtn" type="button">&laquo; Previous</button>
            <span id="question-counter"></span>
            <button id="nextBtn" type="button">Next &raquo;</button>
        </div>

        <div id="submit-container" style="text-align: center; margin-top:20px; display: none;">
            <button id="submitBtn">Submit Your Answers</button>
        </div>

        <div id="resultMessage" class="message-box info" style="display:none; margin-top:20px;"></div>

    <?php else: ?>
        <div class="message-box error">
            <p>Unexpected error. Please contact administrator.</p>
        </div>
    <?php endif; ?>

</div>

<script>
const sessionId = "<?= htmlspecialchars($session_id ?? ''); ?>";
const submitBtn = document.getElementById("submitBtn");
const resultMessage = document.getElementById("resultMessage");

// 1. We define the submit logic as a named async function
// This allows us to call it from multiple places (the button OR the poller)
async function handleSubmit() {
    // Disable button to prevent multiple submissions
    if (submitBtn) {
        submitBtn.disabled = true;
        submitBtn.textContent = "Submitting...";
    }

    let responses = [];
    
    // --- MODIFICATION: GATHER RESPONSES IN ORIGINAL ORDER ---
    
    // 1. Select all question containers
    const containers = document.querySelectorAll('.question-container');
    
    // 2. Convert NodeList to Array so we can sort
    const containerArray = Array.from(containers);

    // 3. Sort the array based on the 'data-original-index' we added in PHP
    containerArray.sort((a, b) => {
        return parseInt(a.dataset.originalIndex, 10) - parseInt(b.dataset.originalIndex, 10);
    });

    // 4. Iterate through the *sorted* array to gather responses in the correct order
    containerArray.forEach(container => {
        // Find all '.response' elements *within* this correctly-ordered container
        container.querySelectorAll(".response").forEach(el => {
            if ((el.type === "checkbox" || el.type === "radio")) {
                if (el.checked) {
                    responses.push({ name: el.name, value: el.value });
                }
            } else if (el.tagName.toLowerCase() === "input" || el.tagName.toLowerCase() === "textarea") {
                responses.push({ name: el.name, value: el.value });
            } else {
                responses.push(el.innerHTML.trim());
            }
        });
    });
    // --- END OF MODIFICATION ---


    let payload = {
        session_id: sessionId,
        answers: responses
    };

    try {
        let res = await fetch("submit_answers.php", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify(payload)
        });

        let data = await res.text();
        if (resultMessage) {
            resultMessage.style.display = "block";
            resultMessage.innerHTML = "<p><strong>Upload successful:</strong></p><pre>" + data + "</pre>";
        }
    } catch (err) {
        if (resultMessage) {
            resultMessage.style.display = "block";
            resultMessage.innerHTML = "<p style='color:red;'><strong>Error:</strong> " + err.message + "</p>";
        }
    }
}

// 2. Attach the named function to the button's click event
submitBtn?.addEventListener("click", handleSubmit);

// --- NEW POLLING LOGIC ---

// 3. Define the function that checks the session status
async function checkSessionStatus() {
    if (!sessionId) {
        return; // Don't poll if there's no session ID
    }

    try {
        const res = await fetch(`check_session_status.php?session_id=${sessionId}`);
        if (!res.ok) {
            console.error("Status check failed:", res.statusText);
            return;
        }

        const data = await res.json();
        
        // 4. Check if the status is 'hide'
        if (data.status === 'hide') {
            console.log("Remote status is 'hide'. Forcing submission...");
            
            // Stop the poller
            clearInterval(statusPoller); 
            
            // Call and wait for the submission to complete
            await handleSubmit();
            
            // After submission is done, reload the page
            console.log("Submission complete. Reloading page.");
            location.reload();
        }
    } catch (err) {
        console.error("Error during status check:", err.message);
    }
}

// 5. Start the poller (every 60,000 ms = 1 minute)
// We only start it if the page loaded correctly (no PHP error and a valid session)
<?php if ($session_id && !$error_message): ?>
    const statusPoller = setInterval(checkSessionStatus, 10000); // Using 10s from your code
    console.log("Session status poller started. Checking every 10 seconds.");


    // --- MODIFICATION: PAGINATION & SHUFFLE LOGIC ---
    document.addEventListener("DOMContentLoaded", () => {
        const questionWrapper = document.getElementById('question-wrapper');
        const allQuestions = Array.from(document.querySelectorAll('.question-container'));
        const totalQuestions = allQuestions.length;
        
        if (totalQuestions === 0) return; // No questions to show

        // --- 1. Shuffling Logic ---
        
        // Fisher-Yates shuffle algorithm
        function shuffleArray(array) {
            for (let i = array.length - 1; i > 0; i--) {
                const j = Math.floor(Math.random() * (i + 1));
                [array[i], array[j]] = [array[j], array[i]];
            }
        }
        
        shuffleArray(allQuestions);
        
        // Re-append shuffled questions to the DOM
        // Clear wrapper first, then append shuffled items
        questionWrapper.innerHTML = ''; 
        allQuestions.forEach(question => {
            questionWrapper.appendChild(question);
        });

        // --- 2. Pagination Logic ---
        let currentQuestionIndex = 0;
        const prevBtn = document.getElementById('prevBtn');
        const nextBtn = document.getElementById('nextBtn');
        const counterEl = document.getElementById('question-counter');
        const submitContainer = document.getElementById('submit-container');
        const paginationControls = document.getElementById('pagination-controls');
        
        function showQuestion(index) {
            // Hide all questions
            allQuestions.forEach((q, i) => {
                q.style.display = (i === index) ? 'block' : 'none';
            });

            // Update counter
            counterEl.textContent = `Question ${index + 1} of ${totalQuestions}`;

            // Update button states
            prevBtn.disabled = (index === 0);
            
            // On last question: hide 'Next', show 'Submit'
            if (index === totalQuestions - 1) {
                nextBtn.style.display = 'none';
                submitContainer.style.display = 'block';
            } else {
                nextBtn.style.display = 'inline-block';
                submitContainer.style.display = 'none';
            }
        }
        
        // Event Listeners
        prevBtn.addEventListener('click', () => {
            if (currentQuestionIndex > 0) {
                currentQuestionIndex--;
                showQuestion(currentQuestionIndex);
            }
        });

        nextBtn.addEventListener('click', () => {
            if (currentQuestionIndex < totalQuestions - 1) {
                currentQuestionIndex++;
                showQuestion(currentQuestionIndex);
            }
        });

        // Initial setup
        paginationControls.style.display = 'flex'; // Show controls
        showQuestion(0); // Show the first (shuffled) question
    });
    // --- END OF PAGINATION & SHUFFLE MODIFICATION ---

<?php endif; ?>

</script>

<script>
(function () {
  // Init when DOM is ready
  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
  } else {
    init();
  }

  function init() {
    // Count containers (and log for debugging)
    const containers = document.querySelectorAll('.question-container'); // <-- MODIFIED SELECTOR
    const containerCount = containers.length;
    console.log('[timer] .question-container count =', containerCount);

    // Use at least 1 container so timer isn't zero (adjust if you prefer 0 => immediate)
    const effectiveCount = Math.max(1, containerCount);

    // 75 seconds per container
    let timeRemaining = effectiveCount * 75; // seconds

    // Create overlay (or reuse if present)
    let timerOverlay = document.getElementById('timerOverlayCustom');
    if (!timerOverlay) {
      timerOverlay = document.createElement('div');
      timerOverlay.id = 'timerOverlayCustom';
      Object.assign(timerOverlay.style, {
        position: 'fixed',
        bottom: '20px',
        right: '20px',
        background: 'rgba(0, 0, 0, 0.7)',
        color: 'white',
        padding: '10px 20px',
        borderRadius: '8px',
        fontFamily: 'Arial, sans-serif',
        fontSize: '18px',
        zIndex: '9999'
      });
      document.body.appendChild(timerOverlay);
    }

    function formatTime(s) {
      const m = Math.floor(s / 60);
      const sec = s % 60;
      return `${m}:${sec.toString().padStart(2, '0')}`;
    }

    // Start interval first so timerInterval exists inside updateTimer
    const timerInterval = setInterval(updateTimer, 1000);
    updateTimer(); // initial display (safe because timerInterval already defined)

    function updateTimer() {
      timerOverlay.textContent = formatTime(timeRemaining);

      if (timeRemaining > 0) {
        timeRemaining--;
        return;
      }

      // time ran out
      clearInterval(timerInterval);

      const submitBtn = document.getElementById('submitBtn');
      if (submitBtn) {
        submitBtn.click();
        console.log('[timer] clicked #submitBtn');
      } else {
        console.warn('[timer] #submitBtn not found — cannot click.');
      }

      timerOverlay.textContent = "Time’s up!";
      timerOverlay.style.background = 'rgba(2, 1, 1, 0.8)';
    }

    // Helpful console info
    console.log(`[timer] started: ${effectiveCount} container(s) × 75s = ${formatTime(effectiveCount * 75)}`);
  }
})();
</script>

</body>
</html>