<?php
/**
 * question_container.php
 *
 * This is the standard HTML shell for any question component.
 * It expects a variable named $question_file_to_load to be defined
 * before it is included. It provides the .question and .response divs.
 */
?>
<div class="question-container">
    <div class="question">
        <?php
        // The specific question component will be rendered inside this div
        if (isset($question_file_to_load) && file_exists($question_file_to_load)) {
            include $question_file_to_load;
        } else {
            echo '<p style="color:red;">Error: Question content could not be loaded into the container.</p>';
        }
        ?>
    </div>
    <div class="response">
        <!-- This div is intentionally left empty. The question's driver script will update it. -->
    </div>
</div>