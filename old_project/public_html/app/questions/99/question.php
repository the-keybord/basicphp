
<div class="w-full max-w-2xl mx-auto p-4">
    <!-- The Question Card -->
    <div class='question'>
        <p></p>
        
        <pre>Which statement deletes the rows where the customer’s email is not entered?</pre>
        </div>
        <div class='options-container'>
        <div class="option">
            <code>DELETE FROM Customer WHERE Email = NULLABLE;</code>
        </div><div class="option">
            <code>DELETE FROM Customer WHERE Email = NULL;</code>
        </div><div class="option">
            <code>DELETE FROM Customer WHERE Email IS NULL;</code>
        </div><div class="option">
            <code>DELETE FROM Customer WHERE Email IS NOT NULL;</code>
        </div>
        </div>
    <div class='response hidden'>

    </div>
</div>
<?php require __DIR__ . '/../../drivers/radio_driver.php'; ?>
