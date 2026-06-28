<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PHP Content Loader</title>
    <style>
        /* Basic styling for the content area */
        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f9f9f9;
        }
        #content-container {
            padding: 25px;
            border: 1px solid #ddd;
            border-radius: 8px;
            background-color: #fff;
            min-height: 100px;
        }
        .error {
            color: #d9534f; /* A reddish color for error messages */
        }
    </style>
</head>
<body>

    <!-- This is the target element where the included content will be displayed. -->
    <div id="content-container">
        <?php
            // The path to the PHP file to include.
            // This path is relative to index.php. You'll need an 'app' directory in the same folder.
            $contentFile = 'app/questions/1/question.php';

            // Check if the file exists before trying to include it.
            if (file_exists($contentFile)) {
                // The include() statement will execute the PHP file and embed its output here.
                // The browser receives the final, combined HTML.
                include $contentFile;
            } else {
                // If the file can't be found, display a user-friendly error message.
                echo '<p class="error"><strong>Error:</strong> Could not load content from the specified path.</p>';
            }
        ?>
    </div>

    <div id="content-container">
        <?php
            $contentFile = 'app/questions/1/question.php';
            if (file_exists($contentFile)) {
                include $contentFile;
            } else {
                echo '<p class="error"><strong>Error:</strong> Could not load content from the specified path.</p>';
            }
        ?>
    </div>

    <div id="content-container">
        <?php
            $contentFile = 'app/questions/11/question.php';
            if (file_exists($contentFile)) {
                include $contentFile;
            } else {
                echo '<p class="error"><strong>Error:</strong> Could not load content from the specified path.</p>';
            }
        ?>
    </div>

    <div id="content-container">
        <?php
            $contentFile = 'app/questions/28/question.php';
            if (file_exists($contentFile)) {
                include $contentFile;
            } else {
                echo '<p class="error"><strong>Error:</strong> Could not load content from the specified path.</p>';
            }
        ?>
    </div>

     <button id="submit-btn">Check Answers</button>

  <script>
    document.getElementById("submit-btn").addEventListener("click", () => {
      // Collect all .response divs
      const responses = Array.from(document.querySelectorAll(".response"))
        .map(div => div.innerText.trim());

      // Send via fetch to PHP
      fetch("check_responses.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ responses })
      })
      .then(res => res.json())
      .then(data => {
        console.log("Server response:", data);
        alert(JSON.stringify(data));
      })
      .catch(err => console.error("Error:", err));
    });
  </script>

</body>
</html>

