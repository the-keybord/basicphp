
<div class="w-full max-w-2xl mx-auto p-4">
    <!-- The Question Card -->
    <div class='question'>
        <p>A table named TEAM is defined as follows:</p>
        
        <pre><img src='https://zece.info/app/questions/120/image_1.png'>
You want to create a database object that retrieves the team names (Name) and rankings (Rank) for all teams ranked between one and twelve.
The object that you create should allow you to insert and update teams and rankings.

You should be able to use the following queries:
SELECT * FROM [Playoff Contenders];
INSERT INTO [Playoff Contenders] SELECT 'My Team', 1;

Complete the code by selecting the correct option from each drop-down list.

  CREATE {{{FUNCTION|||PROCEDURE|||VIEW}}} [Playoff Contenders]
  {{{AS RETURN|||AS SELECT}}} Name, Rank
  {{{FROM|||SELECT}}} Teams WHERE Rank <= 12;</pre>
    </div>

    <div class='response hidden'>

    </div>
</div>
<?php require __DIR__ . '/../../drivers/down_driver.php'; ?>
