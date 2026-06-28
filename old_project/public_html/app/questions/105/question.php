<div class="w-full max-w-2xl mx-auto p-4">
    <div class='question'>
        <p>You have two tables named Devices and OperatingSystems as defined below. The two tables are related by OS_ID.</p>
        
        <pre><img src='https://zece.info/app/questions/105/2026-04-14 17_57_25.png'>
You run the following SQL statement:

        SELECT * FROM Devices  
        LEFT OUTER JOIN OperatingSystems  
        ON Devices.OS_ID = OperatingSystems.OS_ID;  

How many rows does the SQL statement return?</pre>
    </div>
    <div class='options-container'>
        <div class="option">
            <code>0</code>
        </div><div class="option">
            <code>3</code>
        </div><div class="option">
            <code>4</code>
        </div><div class="option">
            <code>7</code>
        </div>
    </div>
    <div class='response hidden'>

    </div>
</div>
<?php require __DIR__ . '/../../drivers/radio_driver.php'; ?>