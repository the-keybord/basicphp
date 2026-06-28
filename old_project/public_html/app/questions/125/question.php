<div class="w-full max-w-2xl mx-auto p-4">
    <div class='question'>
        <p></p>
        
        <pre>You are managing a database for a robotics workshop. 
You have a table named 'OldComponents' containing 50 records. 
Some records have a NULL value in the 'Manufacturer' column.

You execute the following statement:

DROP TABLE OldComponents

What is the result?</pre>
    </div>
    <div class='options-container'>
        <div class="option">
            <code>All rows in the table will be deleted.</code>
        </div><div class="option">
            <code>You will receive an error message because of the NULL values.</code>
        </div><div class="option">
            <code>Only the rows containing a NULL value in the 'Manufacturer' column will be deleted.</code>
        </div><div class="option">
            <code>All rows and the table structure itself will be deleted.</code>
        </div>
    </div>
    <div class='response hidden'>

    </div>
</div>
<?php require __DIR__ . '/../../drivers/radio_driver.php'; ?>