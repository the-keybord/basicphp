<?php
require_once __DIR__ . '/../app/lib/auth.php';
require_admin();
require_once __DIR__ . '/../app/lib/db_connect.php';

$sessions = [];
$error_message = '';

// Handle the delete request
if (isset($_GET['delete_id'])) {
    $delete_id = filter_input(INPUT_GET, 'delete_id', FILTER_VALIDATE_INT);
    if ($delete_id) {
        try {
            $stmt = $pdo->prepare("DELETE FROM test_sessions WHERE id = ?");
            $stmt->execute([$delete_id]);
            // Redirect to the same page to remove the delete_id from the URL and prevent re-deleting on refresh
            header('Location: result_explorer.php');
            exit;
        } catch (PDOException $e) {
            $error_message = "Database error while deleting: " . $e->getMessage();
        }
    }
}

// Handle the hide request
if (isset($_GET['hide_id'])) {
    $hide_id = filter_input(INPUT_GET, 'hide_id', FILTER_VALIDATE_INT);
    if ($hide_id) {
        try {
            $stmt = $pdo->prepare("UPDATE test_sessions SET status = 'hide' WHERE id = ?");
            $stmt->execute([$hide_id]);
            // Redirect to the same page to remove the hide_id from the URL
            header('Location: result_explorer.php');
            exit;
        } catch (PDOException $e) {
            $error_message = "Database error while hiding: " . $e->getMessage();
        }
    }
}


try {
    // 1. IS NULL returns 1 for NULL and 0 for NOT NULL. 
    //    We sort DESC to put the 1s (NULLs) at the top.
    // 2. Then we sort by end_time DESC for the remaining records.
    $sql = "SELECT id, test_id, firstname, lastname, begin_time, end_time, status 
            FROM test_sessions 
            ORDER BY (end_time IS NULL) DESC, end_time DESC, id ASC LIMIT 50";
            
    $stmt = $pdo->query($sql);
    $sessions = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $error_message = "Database error: " . $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test Sessions</title>
    <!-- Tailwind CSS for styling -->
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        /* Simple style adjustments */
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>
<body class="bg-gray-100 text-gray-800">

    <div class="container mx-auto p-4 sm:p-6 lg:p-8">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl sm:text-3xl font-bold text-gray-700">Test Sessions</h1>
            <a href="https://www.zece.info/admin/dashboard.php" class="inline-block bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded-lg transition duration-300 ease-in-out transform hover:scale-105">
                Dashboard
            </a>
        </div>

        <?php if ($error_message): ?>
            <!-- Display error message if the database query fails -->
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg relative" role="alert">
                <strong class="font-bold">Error!</strong>
                <span class="block sm:inline"><?php echo htmlspecialchars($error_message); ?></span>
            </div>
        <?php elseif (empty($sessions)): ?>
            <!-- Display message if no sessions are found -->
            <div class="bg-blue-100 border border-blue-400 text-blue-700 px-4 py-3 rounded-lg relative" role="alert">
                <p>No test sessions found in the database.</p>
            </div>
        <?php else: ?>
            <!-- Display the sessions table -->
            <div class="bg-white shadow-md rounded-lg overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full leading-normal">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-2 border-b-2 border-gray-200 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Session ID</th>
                                <th class="px-4 py-2 border-b-2 border-gray-200 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Test ID</th>
                                <th class="px-4 py-2 border-b-2 border-gray-200 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Name</th>
                                <th class="px-4 py-2 border-b-2 border-gray-200 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Begin Time</th>
                                <th class="px-4 py-2 border-b-2 border-gray-200 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">End Time</th>
                                <th class="px-4 py-2 border-b-2 border-gray-200 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                                <th class="px-4 py-2 border-b-2 border-gray-200 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($sessions as $session): ?>
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-3 border-b border-gray-200 bg-white text-sm"><?php echo htmlspecialchars($session['id']); ?></td>
                                    <td class="px-4 py-3 border-b border-gray-200 bg-white text-sm"><?php echo htmlspecialchars($session['test_id']); ?></td>
                                    <td class="px-4 py-3 border-b border-gray-200 bg-white text-sm"><?php echo htmlspecialchars($session['firstname'] . ' ' . $session['lastname']); ?></td>
                                    <td class="px-4 py-3 border-b border-gray-200 bg-white text-sm"><?php echo htmlspecialchars($session['begin_time'] ?? 'N/A'); ?></td>
                                    <td class="px-4 py-3 border-b border-gray-200 bg-white text-sm"><?php echo htmlspecialchars($session['end_time'] ?? 'N/A'); ?></td>
                                    <td class="px-4 py-3 border-b border-gray-200 bg-white text-sm">
                                        <span class="relative inline-block px-3 py-1 font-semibold leading-tight rounded-full
                                            <?php
                                                switch ($session['status']) {
                                                    case 'submitted':
                                                        echo 'text-green-900 bg-green-200';
                                                        break;
                                                    case 'in_progress':
                                                        echo 'text-yellow-900 bg-yellow-200';
                                                        break;
                                                    case 'hide':
                                                        echo 'text-indigo-900 bg-indigo-200'; // Added style for 'hide'
                                                        break;
                                                    default:
                                                        echo 'text-gray-900 bg-gray-200';
                                                }
                                            ?>
                                        ">
                                            <?php echo htmlspecialchars($session['status']); ?>
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 border-b border-gray-200 bg-white text-sm flex items-center space-x-2">
                                        <a href="https://zece.info/test_session_evaluation.php?session_id=<?php echo htmlspecialchars($session['id']); ?>" class="inline-block bg-blue-500 hover:bg-blue-700 text-white font-bold py-1 px-3 rounded-lg transition duration-300 ease-in-out transform hover:scale-105">
                                            Evaluate
                                        </a>
                                        <!-- NEW HIDE BUTTON -->
                                        <a href="result_explorer.php?hide_id=<?php echo htmlspecialchars($session['id']); ?>" class="inline-block bg-orange-500 hover:bg-orange-700 text-white font-bold py-1 px-3 rounded-lg transition duration-300 ease-in-out transform hover:scale-105" onclick="return confirm('Are you sure you want to hide this session?');">
                                            Hide
                                        </a>
                                        <a href="result_explorer.php?delete_id=<?php echo htmlspecialchars($session['id']); ?>" class="inline-block bg-red-500 hover:bg-red-700 text-white font-bold py-1 px-3 rounded-lg transition duration-300 ease-in-out transform hover:scale-105" onclick="return confirm('Are you sure you want to delete this session? This action cannot be undone.');">
                                            Delete
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        <?php endif; ?>
    </div>

</body>
</html>