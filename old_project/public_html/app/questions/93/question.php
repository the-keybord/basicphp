<div class="w-full max-w-2xl mx-auto p-4">
    <div class='question'>
        <p></p>
        
        <pre>The RaceResults table contains the following data:
<img src='https://zece.info/app/questions/93/2026-04-14 16_53_48.png'>
You need to create a query that displays the total number of participants, the average finish time, the slowest (highest) finish time, and the combined sum of all finish times recorded.

FinishTime represents the time in seconds.

Which query should you use?</pre>
    </div>
    <div class='options-container'>
        <div class="option">
            <code>SELECT SUM(ResultID), AVG(FinishTime), MAX(FinishTime), SUM(FinishTime) <br />
FROM RaceResults;</code>
        </div><div class="option">
            <code>SELECT COUNT(ResultID), AVG(FinishTime), MAX(FinishTime), SUM(FinishTime) <br />
FROM RaceResults <br />
GROUP BY StudentName, FinishTime;</code>
        </div><div class="option">
            <code>SELECT COUNT(ResultID), AVG(FinishTime), MAX(FinishTime), SUM(FinishTime) <br />
FROM RaceResults;</code>
        </div><div class="option">
            <code>SELECT COUNT(ResultID), AVG(FinishTime), MAX(FinishTime), SUM(FinishTime) <br />
FROM RaceResults <br />
HAVING EventType, StudentName;</code>
        </div>
    </div>
    <div class='response hidden'>

    </div>
</div>
<?php require __DIR__ . '/../../drivers/radio_driver.php'; ?>