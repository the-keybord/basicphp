<?php
// Use the correct relative path to the auth library
require_once __DIR__ . '/../app/lib/auth.php';
// Use the gatekeeper function to ensure only admins can access this page
require_admin();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Question Creator - Admin Panel</title>
    <link rel="stylesheet" href="/admin/style.css">
</head>
<body>
    <div class="container" style="max-width: 800px;">
        <div class="dashboard-header">
            <h1>Question Creator</h1>
            <a href="/admin/dashboard.php" class="btn btn-secondary">Dashboard</a>
        </div>

        <div id="stage-1" class="card">
            <h2>Stage 1: Select Course and Domain</h2>
            <p>Select a course, then a domain to generate a new Question ID.</p>
            <form id="create-record-form">
                <div class="input-group">
  <label>1. Select a Course</label>
  <div id="course-radios" class="radio-group">
    <p>Loading courses...</p>
  </div>
</div>

<div class="input-group">
  <label>2. Select a Domain</label>
  <div id="domain-radios" class="radio-group">
    <p>First, select a course</p>
  </div>
</div>

<div class="input-group">
  <label>Secondary Domain</label>
  <div id="domain-radios2" class="radio-group">
    <p>First, select a course</p>
  </div>
</div>

                <button type="submit" id="create-btn" class="btn" disabled>Create Record & Proceed</button>
            </form>
        </div>

        <div id="stage-2" class="card">
            <h2>Stage 2: Build the Question File</h2>
            <p>Database record created successfully! Your new <strong>Question ID is <span id="new-question-id"></span></strong>.</p>
            <p>Now, choose a builder to create the actual <code>question.php</code> component for this ID.</p>
            <div id="builder-links">
                </div>
        </div>
        
        <div id="stage-3" class="card">
            <h2>Stage 3: Test and Finalize Answer</h2>
            <p>The <code>question.php</code> file has been created. Now, view the question and submit the correct answer to finalize it.</p>
    <div id="content-container">
        <?php
            $id = isset($_GET['id']) ? (int)$_GET['id'] : 0; // cast to int for safety
            $contentFile = __DIR__. "/../app/questions/{$id}/question.php";
            echo $id;
            if (file_exists($contentFile)) {
                include $contentFile;
            } else {
                echo '<p class="error"><strong>Error:</strong> Could not load content from the specified path.</p>';
            }
        ?>
    </div>
            <form id="finalize-form">
                <div class="input-group">
                    <label for="correct-answer">Enter the Correct Answer</label>
                </div>
                <button type="submit" class="btn">Hash and Save Answer</button>
            </form>
        </div>

        <div id="final-message" class="card hidden" style="text-align:center;">
            <h2>✅ Success!</h2>
            <p>Question <strong id="final-question-id"></strong> has been created and finalized.</p>
            <a href="/admin/question_creator.php" class="btn">Create Another Question</a>
        </div>
    </div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    // --- ELEMENT SELECTIONS ---
    const stage1 = document.getElementById('stage-1');
    const stage2 = document.getElementById('stage-2');
    const stage3 = document.getElementById('stage-3');
    const finalMessage = document.getElementById('final-message');
    const createRecordForm = document.getElementById('create-record-form');
    const courseRadios = document.getElementById('course-radios');
    const domainRadios = document.getElementById('domain-radios');
    const domainRadios2 = document.getElementById('domain-radios2');
    const finalizeForm = document.getElementById('finalize-form');
    const domainSelect = document.getElementById('domain-select');
    const createBtn = document.getElementById('create-btn');
    
    let currentQuestionId = null;

    // --- BUILDER CONFIGURATION ---
    const availableBuilders = [
        { name: 'Multiple Choice Builder', path: '/app/builders/multiple_choice_builder.php' },
        { name: 'Radio Button Builder', path: '/app/builders/radio_builder.php' },
        { name: 'True/False Builder', path: '/app/builders/true_builder.php' },
        { name: 'Dropdown Builder', path: '/app/builders/down_builder.php' },
        { name: 'Drag & Drop Builder', path: '/app/builders/drag_builder.php' }
        // To add a new builder in the future, just add a new line here.
    ];

    // --- STAGE 1: SELECTION LOGIC ---

    // Fetches all courses and populates the first dropdown
    async function populateCourses() {
  try {
    const response = await fetch('/api/admin/get_courses.php');
    if (!response.ok) throw new Error(`Server Error: ${response.status}`);
    const courses = await response.json();

    courseRadios.innerHTML = ""; // clear

    courses.forEach(course => {
  const btn = document.createElement("button");
  btn.type = "button"; // avoid form submission
  btn.classList.add("select-btn");
  btn.textContent = course.name;
  btn.dataset.value = course.course_id;

  btn.addEventListener("click", () => {
    // remove "active" from all siblings
    [...courseRadios.querySelectorAll(".select-btn")].forEach(b => b.classList.remove("active"));
    btn.classList.add("active");

    // load domains for this course
    loadDomains(course.course_id);
  });

  courseRadios.appendChild(btn);
});
  } catch (error) {
    console.error("Failed to populate courses:", error);
    courseRadios.innerHTML = "<p>Error loading courses</p>";
  }
}

    async function loadDomains(courseId) {
  domainRadios.innerHTML = "<p>Loading domains...</p>";
  domainRadios2.innerHTML = "<p>Loading domains...</p>";
  createBtn.disabled = true;

  try {
    const response = await fetch(`/../api/admin/get_domains.php?course_id=${courseId}`);
    if (!response.ok) throw new Error(`Server Error: ${response.status}`);
    const domains = await response.json();
    const domains2 = domains;  // For simplicity, using the same domains for the second selector

    domainRadios.innerHTML = "";
    domainRadios2.innerHTML = "";

  domains.forEach(domain => {
  const btn = document.createElement("button");
  btn.type = "button";
  btn.classList.add("select-btn");
  btn.textContent = domain.name;
  btn.dataset.value = domain.domain_id;

  btn.addEventListener("click", () => {
    [...domainRadios.querySelectorAll(".select-btn")].forEach(b => b.classList.remove("active"));
    btn.classList.add("active");
    createBtn.disabled = false;
    // save selected domain id for submission
    domainRadios.dataset.selected = domain.domain_id;
  });

  domainRadios.appendChild(btn);
});

    domains2.forEach(domain => {        
    const btn = document.createElement("button");
    btn.type = "button";
    btn.classList.add("select-btn");
    btn.textContent = domain.name;
    btn.dataset.value = domain.domain_id;
    btn.addEventListener("click", () => {
      [...domainRadios2.querySelectorAll(".select-btn")].forEach(b => b.classList.remove("active"));
      btn.classList.add("active");
      // save selected domain id for submission
      domainRadios2.dataset.selected = domain.domain_id;
    });
    domainRadios2.appendChild(btn);
    });

  } catch (error) {
    console.error("Failed to populate domains:", error);
    domainRadios.innerHTML = "<p>Error loading domains</p>";
  }
}

    // Handle the submission of Stage 1 to create the database record
    createRecordForm.addEventListener('submit', async (e) => {
        e.preventDefault();
         const domainId = domainRadios.dataset.selected;
         const domainId2 = domainRadios2.dataset.selected;
         console.log(domainId2);
  if (!domainId) return alert("Please select a domain");
        const formData = new FormData();
        formData.append('domain_id', domainId);
        formData.append('domain_id2', domainId2);
        
        
        try {
            const response = await fetch('/api/admin/create_question_record.php', { method: 'POST', body: formData });
            if (!response.ok) throw new Error(`Server Error: ${response.status}`);
            const result = await response.json();

            if (result && result.success) {
                // --- TRANSITION TO STAGE 2 ---
                currentQuestionId = result.question_id;
                document.getElementById('new-question-id').textContent = currentQuestionId;
                
                const builderLinks = document.getElementById('builder-links');
                builderLinks.innerHTML = '';
                availableBuilders.forEach(builder => {
                    const link = document.createElement('a');
                    link.href = `${builder.path}?id=${currentQuestionId}`;
                    link.className = 'btn';
                    link.textContent = builder.name;
                    link.style.display = 'block';
                    link.style.marginBottom = '1rem';
                    builderLinks.appendChild(link);
                });

                //stage1.classList.add('hidden');
                //stage2.classList.remove('hidden');
            } else {
                alert(`Failed to create record: ${result.message || 'Unknown error.'}`);
            }
        } catch (error) {
            console.error("Critical Error:", error);
            alert("A critical error occurred. Please check the browser console (F12) for more details.");
        }
    });

    // --- STAGE 3: FINALIZATION LOGIC ---

    // Check if the page was loaded after a builder finished
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.get('build_success') === 'true' && urlParams.has('id')) {
        currentQuestionId = urlParams.get('id');
    }
    
    
    // Handle the final submission of the correct answer
    finalizeForm.addEventListener('submit', async (e) => {
        e.preventDefault();
        const answer = document.querySelector('.response').innerText.trim();
        const formData = new FormData();
        formData.append('question_id', currentQuestionId);
        formData.append('answer', answer);

        const response = await fetch('/../../api/admin/save_response_hash.php', { method: 'POST', body: formData });
        const result = await response.json();

        if (result.success) {
            // --- SHOW SUCCESS MESSAGE ---
            document.getElementById('final-question-id').textContent = currentQuestionId;
            stage1.classList.add('hidden');
            stage2.classList.add('hidden');
            stage3.classList.add('hidden');
            finalMessage.classList.remove('hidden');
        } else {
            alert(`Error: ${result.message}`);
        }
    });

    // --- INITIALIZATION ---
    populateCourses();
});
</script>
</body>
</html>