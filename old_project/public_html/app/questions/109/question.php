
<div class="w-full max-w-2xl mx-auto p-4">
    <!-- The Question Card -->
    <div class='question'>
        <p></p>
        
        <pre>You create a table named Games that contains the review scores of recently released video games.

You need to create a view that returns a list of game names.

Name represents the game name.

Which query should you use?</pre>
    </div>
    <div class='options-container'>
        <div class="option">
            <code>CREATE VIEW MyGames AS SELECT Name FROM Games;</code>
        </div><div class="option">
            <code>CREATE VIEW MyGames AS SELECT * FROM Games;</code>
        </div><div class="option">
            <code>CREATE VIEW MyGames SELECT Name FROM Games ORDER BY Name;</code>
        </div><div class="option">
            <code>CREATE VIEW MyGames AS SELECT * from Games WHERE Name BETWEEN &#039;A&#039; AND &#039;Z&#039;;</code>
        </div>
    </div>
    <div class='response hidden'>

    </div>
</div>
<?php require __DIR__ . '/../../drivers/radio_driver.php'; ?>
