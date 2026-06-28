<div class="w-full max-w-2xl mx-auto p-4">
    <div class='question'>
        <p>Which statement correctly creates a composite primary key for the driver assignment table?</p>
        
    </div>
    <div class='options-container'>
        <div class="option">
            <code>CREATE TABLE DriverAssignments <br />
(DriverID INTEGER, <br />
CarID INTEGER, <br />
PRIMARY KEY(DriverID, CarID));</code>
        </div><div class="option">
            <code>CREATE TABLE DriverAssignments <br />
(DriverID INTEGER, <br />
CarID INTEGER, <br />
PRIMARY KEY DriverID, <br />
PRIMARY KEY CarID);</code>
        </div><div class="option">
            <code>CREATE TABLE DriverAssignments <br />
(DriverID INTEGER, <br />
CarID INTEGER, <br />
PRIMARY KEY);</code>
        </div><div class="option">
            <code>CREATE TABLE DriverAssignments <br />
(DriverID INTEGER PRIMARY KEY, <br />
CarID INTEGER PRIMARY KEY);</code>
        </div>
    </div>
    <div class='response hidden'>

    </div>
</div>
<?php require __DIR__ . '/../../drivers/radio_driver.php'; ?>