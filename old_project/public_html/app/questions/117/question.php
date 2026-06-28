
<div class="w-full max-w-2xl mx-auto p-4">
    <!-- The Question Card -->
    <div class='question'>
    <p>A table named Shipment stores data about delivery addresses and their arrival dates.</p>
    <ul>
        <li>-- The Destination field stores the delivery address.</li>
        <li>-- The ArrivalDate field stores when the package arrived.</li>
        <li>-- A NULL value in ArrivalDate means the package is still in transit.</li>
    </ul>
    <pre>You need to display the addresses of the first 10 shipments that have already arrived.

Complete the query by moving the appropriate keywords from the list on the left to the correct locations on the right. 
You may use each keyword once, more than once, or not at all.

Note: You will receive partial credit for each correct selection.

SELECT ___ Destination  
FROM Shipment  
WHERE ArrivalDate ___  
___ ArrivalDate

    {{{COUNT|||
    GROUP BY 10|||
    IS NOT NULL|||
    IS NULL|||
    ORDER BY|||
    TOP 10|||
    HAVING}}}</pre>
</div>

    <div class='response hidden'>

    </div>
</div>
<?php require __DIR__ . '/../../drivers/drag_driver.php'; ?>
