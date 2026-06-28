<?php
require_once __DIR__ . '/../app/lib/auth.php';
require_admin();
require_once __DIR__ . '/../app/lib/db_connect.php';

// Assuming $pdo is your database connection object

// Define action types, adding a new one for Test Access
$action_types = [
    0 => 'General Test Redirect',
    1 => 'Document Access',
    4 => 'Test Access (take_test.php)'
];

function generateRandomCode($length = 6) {
    $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[random_int(0, $charactersLength - 1)];
    }
    return $randomString;
}

$generated_code = null;
$error_message = null;
$success_message = null;
$active_tab = 'test_creator'; // Default tab

// --- FORM PROCESSING ---
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // --- TEST CREATOR FORM ---
    if (isset($_POST['create_test'])) {
        $active_tab = 'test_creator';
        $course_id = filter_input(INPUT_POST, 'course_id', FILTER_VALIDATE_INT);
        $domain_questions = $_POST['domain_questions'] ?? [];

        $pdo->beginTransaction();
        try {
            $all_question_ids = [];
            foreach ($domain_questions as $domain_id => $question_count) {
                $domain_id = (int)$domain_id;
                $question_count = (int)$question_count;

                if ($question_count > 0) {
                    $stmt = $pdo->prepare("SELECT question_id FROM question WHERE domain_id = ? ORDER BY RAND() LIMIT ?");
                    $stmt->execute([$domain_id, $question_count]);
                    $question_ids = $stmt->fetchAll(PDO::FETCH_COLUMN);
                    if (count($question_ids) < $question_count) {
                        throw new Exception("Not enough questions available in one of the selected domains.");
                    }
                    $all_question_ids = array_merge($all_question_ids, $question_ids);
                }
            }

            if (empty($all_question_ids)) {
                throw new Exception("No questions were selected. Please specify the number of questions for at least one domain.");
            }

            shuffle($all_question_ids); // Randomize the final list
            $question_list_json = json_encode($all_question_ids);

            // Insert the new test into the 'tests' table
            $stmt = $pdo->prepare("INSERT INTO tests (course_id, question_list_json) VALUES (?, ?)");
            $stmt->execute([$course_id, $question_list_json]);
            $new_test_id = $pdo->lastInsertId();

            // Now, generate a code for this test
            do {
                $newCode = generateRandomCode(6);
                $stmt = $pdo->prepare("SELECT id FROM codes WHERE code = ?");
                $stmt->execute([$newCode]);
                $exists = $stmt->fetch();
            } while ($exists);
            $generated_code = $newCode;

            $duration_value = filter_input(INPUT_POST, 'duration_value', FILTER_VALIDATE_INT);
            $duration_unit = $_POST['duration_unit'] ?? 'days';
            $expires_at = null;
            if ($duration_value && $duration_value > 0) {
                $expires_at = date('Y-m-d H:i:s', strtotime("+{$duration_value} {$duration_unit}"));
            }

            // Insert the code into the 'codes' table
            $stmt = $pdo->prepare("INSERT INTO codes (code, expires_at, object_id, type_id) VALUES (?, ?, ?, ?)");
            $stmt->execute([$generated_code, $expires_at, $new_test_id, 4]); // type_id 4 is for Test Access

            $pdo->commit();
            $success_message = "Successfully created the test and generated an access code!";

        } catch (Exception $e) {
            $pdo->rollBack();
            $error_message = "Error: " . $e->getMessage();
        }
    }
    // --- GENERIC CODE FORM ---
    elseif (isset($_POST['create_generic'])) {
        // This is the logic from your previous generator, slightly adapted
        $active_tab = 'generic_creator';
        $object_id = filter_input(INPUT_POST, 'object_id', FILTER_VALIDATE_INT);
        $type_id = filter_input(INPUT_POST, 'type_id', FILTER_VALIDATE_INT);
        $duration_value = filter_input(INPUT_POST, 'duration_value', FILTER_VALIDATE_INT);
        $duration_unit = $_POST['duration_unit'] ?? 'days';

        if ($type_id === false || !array_key_exists($type_id, $action_types)) {
            $error_message = "Invalid Action Type selected.";
        } else {
            do {
                $newCode = generateRandomCode(6);
                $stmt = $pdo->prepare("SELECT id FROM codes WHERE code = ?");
                $stmt->execute([$newCode]);
                $exists = $stmt->fetch();
            } while ($exists);
            $generated_code = $newCode;

            $expires_at = null;
            if ($duration_value && $duration_value > 0) {
                $expires_at = date('Y-m-d H:i:s', strtotime("+{$duration_value} {$duration_unit}"));
            }

            $stmt = $pdo->prepare("INSERT INTO codes (code, expires_at, object_id, type_id) VALUES (?, ?, ?, ?)");
            $stmt->execute([$generated_code, $expires_at, $object_id, $type_id]);
            $success_message = "Successfully generated a generic code!";
        }
    }
}

// Fetch courses for the dropdown
try {
    $courses = $pdo->query("SELECT course_id, name FROM course ORDER BY name")->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $courses = [];
    $error_message = "Database Error: Could not fetch courses. " . $e->getMessage();
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Code & Test Generator</title>
    <style>
        body { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif; max-width: 800px; margin: 40px auto; padding: 20px; background-color: #f4f7f9; }
        .container { background: #fff; padding: 30px; border-radius: 12px; box-shadow: 0 6px 20px rgba(0,0,0,0.08); }
        .tabs { display: flex; border-bottom: 2px solid #dee2e6; margin-bottom: 25px; }
        .tab-link { padding: 12px 20px; cursor: pointer; border: none; background: none; font-size: 16px; font-weight: 600; color: #6c757d; border-bottom: 2px solid transparent; }
        .tab-link.active { color: #007bff; border-bottom-color: #007bff; }
        .tab-content { display: none; }
        .tab-content.active { display: block; }
        .form-group { margin-bottom: 20px; }
        label { display: block; margin-bottom: 8px; color: #555; font-weight: bold; }
        input[type="number"], select { width: 100%; padding: 12px; box-sizing: border-box; border: 1px solid #ccc; border-radius: 6px; font-size: 16px; }
        .duration-group, .domain-item { display: flex; gap: 10px; align-items: center; }
        .duration-group input { flex: 2; }
        .duration-group select { flex: 3; }
        button { display: block; width: 100%; padding: 15px; background-color: #007bff; color: white; border: none; border-radius: 6px; cursor: pointer; font-size: 18px; font-weight: bold; }
        button:hover { background-color: #0056b3; }
        .message-box { text-align: center; padding: 30px; margin-top: 25px; border-radius: 8px; }
        .message-box p { margin: 0; font-size: 1.2rem; }
        .generated-code-display { font-size: 6rem; font-weight: bold; font-family: monospace; letter-spacing: 5px; color: #333; margin-top: 15px; }
        #domain-list .domain-item { margin-bottom: 15px; background: #f8f9fa; padding: 10px; border-radius: 5px; }
        #domain-list label { flex: 1; }
        #domain-list input { max-width: 100px; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Generator Tool</h1>
        <div class="tabs">
            <button class="tab-link <?= $active_tab == 'test_creator' ? 'active' : '' ?>" onclick="openTab(event, 'test_creator')">Create Test</button>
            <button class="tab-link <?= $active_tab == 'generic_creator' ? 'active' : '' ?>" onclick="openTab(event, 'generic_creator')">Generic Code</button>
        </div>

        <?php if ($success_message): ?>
        <div class="message-box">
            <p>✅ <strong><?php echo htmlspecialchars($success_message); ?></strong></p>
            <p>Your new code is:</p>
            <div class="generated-code-display"><?php echo htmlspecialchars($generated_code); ?></div>
            <p style="margin-top:20px;">Test link: <a href="zece.info/code_router.php?code=<?php echo urlencode($generated_code); ?>" target="_blank">redirect.php?code=<?php echo htmlspecialchars($generated_code); ?></a></p>
        </div>
        <?php elseif ($error_message): ?>
        <div class="message-box" style="background-color: #f8d7da; color: #721c24;">
            <p>❌ <?php echo htmlspecialchars($error_message); ?></p>
        </div>
        <?php endif; ?>

        <!-- TEST CREATOR TAB -->
        <div id="test_creator" class="tab-content <?= $active_tab == 'test_creator' ? 'active' : '' ?>">
            <form action="code_generator.php" method="post">
                <div class="form-group">
                    <label for="course_id">1. Select a Course</label>
                    <select id="course_id" name="course_id" required>
                        <option value="">-- Choose a course --</option>
                        <?php foreach ($courses as $course): ?>
                            <option value="<?= $course['course_id'] ?>"><?= htmlspecialchars($course['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label>2. Set Question Counts per Domain</label>
                    <div id="domain-list"><p style="color:#6c757d;">Please select a course to see its domains.</p></div>
                </div>
                 <div class="form-group">
                    <label for="duration_value_test">3. Set Expiration (optional)</label>
                    <div class="duration-group">
                        <input type="number" id="duration_value_test" name="duration_value" min="1" placeholder="e.g., 7">
                        <select name="duration_unit">
                            <option value="minutes">Minutes</option>
                            <option value="hours">Hours</option>
                            <option value="days" selected>Days</option>
                        </select>
                    </div>
                </div>
                <button type="submit" name="create_test">Create Test & Generate Code</button>
            </form>
        </div>

        <!-- GENERIC CODE CREATOR TAB -->
        <div id="generic_creator" class="tab-content <?= $active_tab == 'generic_creator' ? 'active' : '' ?>">
            <form action="code_generator.php" method="post">
                <div class="form-group">
                    <label for="type_id">Action Type</label>
                    <select name="type_id" required>
                        <option value="">-- Select an Action --</option>
                        <?php foreach ($action_types as $id => $desc): ?>
                            <option value="<?= $id ?>"><?= htmlspecialchars($desc) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="object_id">Object ID</label>
                    <input type="number" name="object_id">
                </div>
                <div class="form-group">
                    <label for="duration_value_generic">Expires in (optional)</label>
                     <div class="duration-group">
                        <input type="number" id="duration_value_generic" name="duration_value" min="1" placeholder="e.g., 30">
                        <select name="duration_unit">
                            <option value="minutes">Minutes</option>
                            <option value="hours">Hours</option>
                            <option value="days" selected>Days</option>
                        </select>
                    </div>
                </div>
                <button type="submit" name="create_generic">Generate Generic Code</button>
            </form>
        </div>
    </div>

<script>
    function openTab(evt, tabName) {
        document.querySelectorAll('.tab-content').forEach(tab => tab.style.display = 'none');
        document.querySelectorAll('.tab-link').forEach(link => link.classList.remove('active'));
        document.getElementById(tabName).style.display = 'block';
        evt.currentTarget.classList.add('active');
    }

    // Initialize the default active tab
    document.addEventListener('DOMContentLoaded', function() {
        const activeTabButton = document.querySelector('.tab-link.active');
        if (activeTabButton) {
            activeTabButton.click();
        }
    });

    document.getElementById('course_id').addEventListener('change', function() {
        const courseId = this.value;
        const domainListDiv = document.getElementById('domain-list');
        domainListDiv.innerHTML = '<p style="color:#6c757d;">Loading domains...</p>';

        if (!courseId) {
            domainListDiv.innerHTML = '<p style="color:#6c757d;">Please select a course to see its domains.</p>';
            return;
        }

        fetch(`/../api/admin/get_domains.php?course_id=${courseId}`)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(domains => {
                if (domains.error) {
                    domainListDiv.innerHTML = `<p style="color:red;">Error: ${domains.error}</p>`;
                    return;
                }
                if (domains.length === 0) {
                     domainListDiv.innerHTML = '<p style="color:#6c757d;">No domains found for this course.</p>';
                     return;
                }
                let html = '';
                domains.forEach(domain => {
                    html += `
                        <div class="domain-item">
                            <label for="domain_${domain.domain_id}">${domain.name}</label>
                            <input type="number" name="domain_questions[${domain.domain_id}]" id="domain_${domain.domain_id}" min="0" max="${domain.question_count}" placeholder="0" value="${domain.default_count}">
                        </div>
                    `;
                });
                domainListDiv.innerHTML = html;
            })
            .catch(error => {
                domainListDiv.innerHTML = `<p style="color:red;">Failed to load domains. See console for details.</p>`;
                console.error('Error fetching domains:', error);
            });
    });
</script>

</body>
</html>

