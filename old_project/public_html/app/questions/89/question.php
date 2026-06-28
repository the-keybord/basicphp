
<div class="w-full max-w-2xl mx-auto p-4">
    <!-- The Question Card -->
    <div class='question'>
    <p></p>
    <pre>You are writing a query to find if the user 'JohnDoe' appears exactly once in the System_Logs table.

SELECT Username  
FROM System_Logs  
WHERE Username = 'JohnDoe'  
ORDER BY Username  
GROUP BY Username  
HAVING COUNT(*) = 1;  

When you run this query, it returns a syntax error because of the clause order.
You need to fix the query so it runs successfully and returns the correct result.

What should you do?</pre>
</div>
<div class='options-container'>
    <div class="option">
        <code>Change the HAVING clause to HAVING COUNT(1) = 1</code>
    </div>
    <div class="option">
        <code>Remove the GROUP BY clause</code>
    </div>
    <div class="option">
        <code>Remove the ORDER BY clause</code>
    </div>
    <div class="option">
        <code>Change the HAVING clause to HAVING COUNT(Username) = 1</code>
    </div>
</div>
    <div class='response hidden'>

    </div>
</div>
<?php require __DIR__ . '/../../drivers/radio_driver.php'; ?>
