
<div class="w-full max-w-2xl mx-auto p-4">
    <!-- The Question Card -->
    <div class='question'>
        <p>The Customers table includes the following data:</p>
        <img src='https://zece.info/app/questions/87/image_1.png'>
        <pre>
You need to create a query that returns a result set containing the LastName, PhoneNumber, and Extension 
for customers that have extensions. The result set should be sorted by the customer's last name.

Complete the code by selecting the correct option from each drop-down list.
Note: You will receive partial credit for each correct selection.

SELECT LastName, PhoneNumber, Extension  
FROM Customers  
WHERE {{{Extension|||LastName|||PhoneNumber}}} {{{IS NOT NULL|||IS NULL|||= NULL}}}
{{{ORDER BY|||GROUP BY}}} LastName;  </pre>
    </div>

    <div class='response hidden'>

    </div>
</div>
<?php require __DIR__ . '/../../drivers/down_driver.php'; ?>
