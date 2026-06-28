
<div class="w-full max-w-2xl mx-auto p-4">
    <!-- The Question Card -->
    <div class='question'>
        <p>A table named Flight contains the following fields:</p>
        <img src='https://zece.info/app/questions/84/image_1.png'>
        <pre>
You need to display the flight numbers of all flights that will arrive at Chisinau Airport (KIV) later than now.
The results should be sorted from the latest expected arrival time to the earliest expected arrival time.
        
Which query should you use? Complete the code by selecting the correct option from each drop-down list.
        
Note: You will receive partial credit for each correct selection.

SELECT FlightNumber FROM Flight
WHERE DestinationAirport = 'KIV' {{{AND|||OR}}}
ArrivalTime {{{<|||>|||=}}} GETDATE()
{{{GROUP BY|||ORDER BY}}}
{{{ArrivalTime ASC;|||ArrivalTime DESC;}}}
</pre>
    </div>

    <div class='response hidden'>

    </div>
</div>
<?php require __DIR__ . '/../../drivers/down_driver.php'; ?>
