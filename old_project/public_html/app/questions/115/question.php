
<div class="w-full max-w-2xl mx-auto p-4">
    <!-- The Question Card -->
    <div class='question'>
    <p>You need to create a database view that lists all available products in the 'Electronics' category (AvailableElectronics_View).</p>
    <pre>

___
___
___
WHERE p.Category = 'Electronics' 
AND p.IsAvailable = 1;

{{{ CREATE VIEW [dbo].[AvailableElectronics_View] |||
    INSERT VIEW [dbo].[AvailableElectronics_View] |||
    AS JOIN p.ID, p.Name |||
    AS SELECT p.ID, p.Name |||
    FROM Product p |||
    JOIN Product p }}}</pre>
</div>

    <div class='response hidden'>

    </div>
</div>
<?php require __DIR__ . '/../../drivers/drag_driver.php'; ?>
