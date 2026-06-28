
<div class="w-full max-w-2xl mx-auto p-4">
    <!-- The Question Card -->
    <div class='question'>
        <p>?</p>
        
        <pre>Your company stores employee salary information in a column named Salary in a table named Employees. 
    New compliance laws prohibit your company from storing this information. Running the query below causes an error to occur.

        ALTER TABLE Employees  
        REMOVE Salary;  

    What changes are needed to the query above so that it removes the Salary column from the Employees table?</pre>
        </div>
        <div class='options-container'>
        <div class="option">
            <code>ALTER TABLE Employees DELETE COLUMN Salary;</code>
        </div>
        <div class="option">
            <code>ALTER TABLE Employees DROP Salary;</code>
        </div>
        <div class="option">
            <code>ALTER TABLE Employees DROP COLUMN Salary;</code>
        </div>
        <div class="option">
            <code>ALTER TABLE Employees DELETE Salary;</code>
        </div>
        </div>
        <div class='response hidden'>

        </div>
</div>
<?php require __DIR__ . '/../../drivers/radio_driver.php'; ?>
