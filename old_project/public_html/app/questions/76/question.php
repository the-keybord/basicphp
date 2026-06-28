
<div class="w-full max-w-2xl mx-auto p-4">
    <!-- The Question Card -->
    <div class='question'>
        <p>The Pizza table contains the following data:</p>
        
        <pre><img src='https://zece.info/app/questions/76/image_1.png'>
Which query will retrieve ItemName and Price when pepperoni appears in the ItemDescription column?</pre>
    </div>
    <div class='options-container'>
        <div class="option">
            <code>SELECT ItemName, Price <br />
FROM Pizza<br />
WHERE ItemDescription = &#039;pepperoni&#039;;</code>
        </div><div class="option">
            <code>SELECT ItemName, Price <br />
FROM Pizza<br />
WHERE ItemDescription IN &#039;%pepperoni%&#039;;</code>
        </div><div class="option">
            <code>SELECT ItemName, Price <br />
FROM Pizza<br />
WHERE ItemDescription LIKE &#039;%pepperoni%&#039;;</code>
        </div><div class="option">
            <code>SELECT ItemName, Price <br />
FROM Pizza<br />
WHERE ItemDescription LIKE &#039;pepperoni&#039;;</code>
        </div>
    </div>
    <div class='response hidden'>

    </div>
</div>
<?php require __DIR__ . '/../../drivers/radio_driver.php'; ?>
