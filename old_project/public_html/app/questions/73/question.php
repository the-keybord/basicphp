<div class="w-full max-w-2xl mx-auto p-4">
    <div class='question'>
        <p></p>
        
        <pre>Your database contains a table named DriverAssignments. You need to delete the record from the DriverAssignments table that has an AssignmentID of 12345.
Which statement should you use?</pre>
    </div>
    <div class='options-container'>
        <div class="option">
            <code>UPDATE DriverAssignments <br />
DELETE * WHERE AssignmentID = 12345;</code>
        </div><div class="option">
            <code>DELETE FROM DriverAssignments <br />
WHERE AssignmentID = 12345;</code>
        </div><div class="option">
            <code>UPDATE AssignmentID FROM DriverAssignments <br />
DELETE * WHERE AssignmentID = 12345;</code>
        </div><div class="option">
            <code>DELETE AssignmentID FROM DriverAssignments <br />
WHERE AssignmentID = 12345;</code>
        </div>
    </div>
    <div class='response hidden'>

    </div>
</div>
<?php require __DIR__ . '/../../drivers/radio_driver.php'; ?>