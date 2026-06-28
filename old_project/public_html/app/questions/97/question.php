
<div class="w-full max-w-2xl mx-auto p-4">
    <!-- The Question Card -->
    <div class='question'>
    <p>You have a table named Employee. The Employee table has columns for Salary and JobTitle.</p>
    <pre>You need to change the Salary value for all the interns in the Employee table to 35000.

A JobTitle of 'Intern' indicates that the employee is an intern.

Which statement should you use?</pre>
</div>
<div class='options-container'>
    <div class="option">
        <code>UPDATE Employee <br />
SET Salary = 35000 <br />
WHERE JobTitle = &#039;Intern&#039;;</code>
    </div>
    <div class="option">
        <code>SET Employee <br />
WHERE JobTitle = &#039;Intern&#039; <br />
TO Salary = 35000;</code>
    </div>
    <div class="option">
        <code>UPDATE Employee <br />
WHERE JobTitle = &#039;Intern&#039; <br />
SET Salary = 35000;</code>
    </div>
    <div class="option">
        <code>SET Employee <br />
TO Salary = 35000 <br />
WHERE JobTitle = &#039;Intern&#039;;</code>
    </div>
</div>
    <div class='response hidden'>

    </div>
</div>
<?php require __DIR__ . '/../../drivers/radio_driver.php'; ?>
