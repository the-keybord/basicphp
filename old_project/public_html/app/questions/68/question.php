
<div class="w-full max-w-2xl mx-auto p-4">
    <!-- The Question Card -->
    <div class='question'>
        <p>You execute the following query:</p>
        
        <pre>SELECT LandmarkID, Name, DistrictName FROM Landmarks, Districts;

Which type of operation was performed?</pre>
    </div>
    <div class='options-container'>
        <div class="option">
            <code>Cartesian product</code>
        </div><div class="option">
            <code>intersection</code>
        </div><div class="option">
            <code>outer join</code>
        </div><div class="option">
            <code>equi-join</code>
        </div>
    </div>
    <div class='response hidden'>

    </div>
</div>
<?php require __DIR__ . '/../../drivers/radio_driver.php'; ?>
