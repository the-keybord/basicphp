
<div class="w-full max-w-2xl mx-auto p-4">
    <!-- The Question Card -->
    <div class='question'>
    <p>You have a table named Employee that contains one million records. You frequently run the following query to generate a staff list:</p>
    <pre>SELECT FirstName, LastName FROM Employee WHERE Department = 'Engineering';

Which of the following would best improve the performance of this specific query?</pre>
</div>
<div class='options-container'>
    <div class="option">
        <code>a clustered index on the FirstName column</code>
    </div>
    <div class="option">
        <code>a clustered index on the LastName column</code>
    </div>
    <div class="option">
        <code>a non-clustered index on the Department column</code>
    </div>
    <div class="option">
        <code>a non-clustered index on the LastName column</code>
    </div>
</div>
    <div class='response hidden'>

    </div>
</div>
<?php require __DIR__ . '/../../drivers/radio_driver.php'; ?>
