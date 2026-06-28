
<div class="w-full max-w-2xl mx-auto p-4">
    <!-- The Question Card -->
    <div class='question'>
        <p>Which query correctly returns a result set for all employees where the department excludes Sales and Marketing?</p>
        
        </div>
        <div class='options-container'>
        <div class="option">
            <code>SELECT * FROM Employees WHERE NOT department = &#039;Sales&#039; AND NOT department = &#039;Marketing&#039;;</code>
        </div><div class="option">
            <code>SELECT * FROM Employees WHERE NOT department = &#039;Sales&#039; OR NOT department = &#039;Marketing&#039;;</code>
        </div><div class="option">
            <code>SELECT * FROM Employees WHERE department NOT = &#039;Sales&#039; AND department NOT = &#039;Marketing&#039;;</code>
        </div><div class="option">
            <code>SELECT * FROM Employees WHERE department NOT = &#039;Sales&#039; OR department NOT = &#039;Marketing&#039;;</code>
        </div>
        </div>
    <div class='response hidden'>

    </div>
</div>
<?php require __DIR__ . '/../../drivers/radio_driver.php'; ?>
