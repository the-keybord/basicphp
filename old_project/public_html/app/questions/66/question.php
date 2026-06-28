<div class="w-full max-w-2xl mx-auto p-4">
    <div class='question'>
        <p>Which query returns a result set of deliveries made after January 2024 to all districts except Chișinău?</p>
        
    </div>
    <div class='options-container'>
        <div class="option">
            <code>SELECT * FROM Livrare WHERE DataLivrare &gt; &#039;2024-01-31&#039; AND Raion &lt;&gt; &#039;Chisinau&#039;;</code>
        </div><div class="option">
            <code>SELECT * FROM Livrare WHERE DataLivrare &gt; &#039;2024-01-31&#039; OR Raion &lt;&gt; &#039;Chisinau&#039;;</code>
        </div><div class="option">
            <code>SELECT * FROM Livrare WHERE DataLivrare &gt; &#039;2024-01-31&#039; AND Raion LIKE &#039;Chisinau&#039;;</code>
        </div><div class="option">
            <code>SELECT * FROM Livrare WHERE DataLivrare &gt; &#039;2024-01-31&#039; OR Raion LIKE &#039;Chisinau&#039;;</code>
        </div>
    </div>
    <div class='response hidden'>

    </div>
</div>
<?php require __DIR__ . '/../../drivers/radio_driver.php'; ?>