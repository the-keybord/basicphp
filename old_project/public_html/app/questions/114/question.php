
<div class="w-full max-w-2xl mx-auto p-4">
    <!-- The Question Card -->
    <div class='question'>
    <p>A table named Sales has these columns:</p>
    <ul>
        <li>StoreID (INT, No Nulls)</li>
        <li>RegionID (INT, No Nulls)</li>
        <li>SaleDate (DATETIME, Allows Nulls)</li>
        <li>Amount (INT, Allows Nulls)</li>
    </ul>

    <pre>
        You need to show the total Amount for each StoreID in region 5. Which SQL statement should you use?

SELECT StoreID, ___
FROM Sales
___ RegionID = 5
___ ___

    {{{StoreID|||RegionID|||SUM(Amount)|||COUNT|||WHERE|||HAVING|||GROUP BY}}}</pre>
</div>

    <div class='response hidden'>

    </div>
</div>
<?php require __DIR__ . '/../../drivers/drag_driver.php'; ?>
