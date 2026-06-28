<?php
include __DIR__ . "/clientdb.php";

// Fetch all question IDs
$sql = "SELECT question_id FROM question";
$result = $conn->query($sql);

$questionIds = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $questionIds[] = (int)$row["question_id"];
    }
}

// Shuffle the question order
shuffle($questionIds);
?>

<!DOCTYPE html>
<html>
<head>
    <link rel="icon" type="image/png" href="/favicon-96x96.png" sizes="96x96" />
    <link rel="icon" type="image/svg+xml" href="/favicon.svg" />
    <link rel="shortcut icon" href="/favicon.ico" />
    <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png" />
    <link rel="manifest" href="/site.webmanifest" />
    <title>Home</title>
    <style>
        .question-block {
            border: 1px solid #ccc;
            padding: 10px;
            margin-bottom: 15px;
        }
    </style>
    <script>
        // Store the order of questions and the responses
        let questionOrder = <?php echo json_encode($questionIds); ?>; // shuffled list
        let responses = new Array(questionOrder.length).fill(null);

        // Save response by display index
        function saveResponse(displayIndex, value) {
            responses[displayIndex] = value;
            console.log("Saved:", displayIndex, value);
            console.log("Responses array:", responses);
        }

        function submitAll() {
            console.log("Question IDs (shuffled):", questionOrder);
            console.log("Responses:", responses);
            alert("Responses collected! Check console.");
            // You can send questionOrder + responses via AJAX here
        }

        function attachListeners() {
            document.querySelectorAll(".question-block").forEach((block, i) => {
                block.querySelectorAll("input").forEach(input => {
                    input.addEventListener("change", () => {
                        saveResponse(i, input.value);  // i = display index
                    });
                });
            });
        }

        window.addEventListener("DOMContentLoaded", attachListeners);
    </script>
</head>
<body>

<h2>Questions</h2>

<form id="questionsForm" onsubmit="event.preventDefault(); submitAll();">

<?php
foreach ($questionIds as $displayIndex => $qid) {
    $file = __DIR__ . "/questions/$qid/question.php";

    echo "<div class='question-block' data-qid='$qid'>";
    echo "<p>Question " . ($displayIndex + 1) . ":</p>"; // display number

    if (file_exists($file)) {
        include $file;  // only inputs and content
    } else {
        echo "<p>⚠️ Missing file for question ID: $qid</p>";
    }

    echo "<div class='response'>5</div>";

    echo "</div>";
}
$conn->close();
?>

<button type="submit">Submit All</button>
</form>

</body>
</html>
