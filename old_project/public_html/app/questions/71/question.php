<div class="w-full max-w-2xl mx-auto p-4">
    <div class='question'>
    <p></p>
    <pre>You have a table named Students that contains the following data:

Student_ID   Last_Name      Club_ID
101                  Miller               10
102                  Davis                20

The Student_ID column is the primary key. The Club_ID column is a foreign key to a separate table named Clubs.
You execute the following statement:

INSERT INTO Students VALUES (101, 'Smith', 20);

What is the result?</pre>
</div>
<div class='options-container'>
    <div class="option">
        <code>A new row in the Students table</code>
    </div>
    <div class="option">
        <code>A foreign key constraint violation</code>
    </div>
    <div class="option">
        <code>A new row in the Clubs table</code>
    </div>
    <div class="option">
        <code>A primary key constraint violation</code>
    </div>
</div>
    <div class='response hidden'>

    </div>
</div>
<?php require __DIR__ . '/../../drivers/radio_driver.php'; ?>