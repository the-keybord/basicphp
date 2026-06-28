
<div class="w-full max-w-2xl mx-auto p-4">
    <!-- The Question Card -->
    <div class='question'>
        <p></p>
        
        <pre>You run the query below and get the error:
“Invalid column name ‘lastname’.”

  SELECT firstname, lastname
  FROM Employees

What is the most likely cause?
</pre>
    </div>
    <div class='options-container'>
        <div class="option">
            <code>The table Employees does not exist.</code>
        </div><div class="option">
            <code>The column lastname is misspelled or does not exist in the table.</code>
        </div><div class="option">
            <code>SQL Server needs to be restarted.</code>
        </div><div class="option">
            <code>The query must include an ORDER BY clause.</code>
        </div>
    </div>
    <div class='response hidden'>

    </div>
</div>
<?php require __DIR__ . '/../../drivers/radio_driver.php'; ?>
