<div class="w-full max-w-2xl mx-auto p-4">
    <div class='question'>
        <p></p>
        <img src='https://zece.info/app/questions/96/2026-04-14 17_33_45.png'>
        <pre>You run the following two queries:

        SELECT [Lab].[RoomName] AS Lab FROM [Lab]  
        JOIN [ProjectAssignment] ON LabID = Lab.ID;

        SELECT [Researcher].[Name] AS Researcher FROM [Researcher]  
        LEFT JOIN [ProjectAssignment] ON ResearcherID = Researcher.ID;

How many rows are returned by the first query? {{{1|||2|||3|||4|||5}}}
How many rows are returned by the second query? {{{1|||2|||3|||4|||5}}}</pre>
    </div>

    <div class='response hidden'>

    </div>
</div>
<?php require __DIR__ . '/../../drivers/down_driver.php'; ?>