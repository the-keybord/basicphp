<?php 
require_once __DIR__ . '/../app/lib/auth.php';
require_admin(); 
require_once __DIR__ . '/../app/lib/db_connect.php';

define('DEMO_MODE', true);

// Fetch question metadata
$stmt = $pdo->query("SELECT question_id, domain_id FROM question ORDER BY question_id DESC");
$questions_metadata = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<script src="https://cdn.tailwindcss.com"></script>
<div class="flex justify-between items-center mb-6">
<input type="text" id="searchInput" class="w-full px-4 py-2 mb-5 text-base border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Search questions...">
<a href="https://www.zece.info/admin/dashboard.php" class="inline-block bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded-lg transition duration-300 ease-in-out transform hover:scale-105">
                Dashboard
            </a>
            </div>
<div class="questions-list" id="questionsList">
<?php foreach ($questions_metadata as $meta): 
    $question_id = $meta['question_id'];
    $domain_id = $meta['domain_id'];
    $file_path = __DIR__ . "/../app/questions/{$question_id}/question.php";
?>
    <div class="question-card border border-gray-200 rounded-xl p-4 mb-5 bg-gray-50 transition-all duration-200 ease-in-out" id="question-<?php echo htmlspecialchars($question_id); ?>">
        <div class="flex justify-between items-center mb-3">
            <h3 class="text-lg font-semibold text-gray-800">
                Question ID: <?php echo htmlspecialchars($question_id); ?> |
                Domain ID: <?php echo htmlspecialchars($domain_id); ?>
            </h3>
            <div class="flex items-center gap-2">
                <button class="bg-blue-600 text-white py-2 px-4 rounded-md text-sm font-medium hover:bg-blue-700 transition-colors" onclick="window.location.href='/admin/question_creator.php?build_success=true&id=<?php echo htmlspecialchars($question_id); ?>'">
                    Modify
                </button>
                <button class="bg-red-600 text-white py-2 px-4 rounded-md text-sm font-medium hover:bg-red-700 transition-colors" onclick="confirmDelete(<?php echo htmlspecialchars($question_id); ?>)">
                    Delete
                </button>
            </div>
        </div>

        <div class="question-content border-t border-gray-200 pt-3">
            <?php
            if (file_exists($file_path)) {
                include $file_path;
            } else {
                echo '<p class="text-red-600"><strong class="font-bold">Error:</strong> Could not load content for question ID ' . htmlspecialchars($question_id) . '.</p>';
            }
            ?>
        </div>
    </div>
<?php endforeach; ?>
</div>

<script>
function confirmDelete(id) {
    if (confirm("Are you sure you want to delete question ID " + id + "? This action cannot be undone.")) {
        window.location.href = "delete_question.php?id=" + id;
    }
}

// Live Search
document.getElementById('searchInput').addEventListener('input', function () {
    const query = this.value.toLowerCase().trim();
    const cards = document.querySelectorAll('.question-card');

    cards.forEach(card => {
        const text = card.innerText.toLowerCase();
        if (text.includes(query)) {
            // Tailwind uses a 'hidden' class for display: none
            card.classList.remove('hidden');
        } else {
            card.classList.add('hidden');
        }
    });
});
</script>