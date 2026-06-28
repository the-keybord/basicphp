<div class="w-full max-w-2xl mx-auto p-4">
    <div class='question'>
        <p>A portion of the <strong>Wine</strong> table is shown as follows:</p>
        
        <pre><img src='https://zece.info/app/questions/60/image_1.png'>
A developer is trying to add a column named <strong>BottlingYear</strong> to the Wine table by using the following query:

        ALTER TABLE Wine ADD BottlingYear int NOT NULL;

The developer receives an error message after running the query because the table already contains data.
How can they resolve the problem without data loss?
Change the query as follows:</pre>
    </div>
    <div class='options-container'>
        <div class="option">
            <code>ALTER TABLE Wine ADD BottlingYear varchar(4) NOT NULL;</code>
        </div><div class="option">
            <code>DROP CONSTRAINT; ALTER TABLE Wine ADD BottlingYear int NOT NULL;</code>
        </div><div class="option">
            <code>DELETE FROM Wine; ALTER TABLE Wine ADD BottlingYear int NOT NULL;</code>
        </div><div class="option">
            <code>ALTER TABLE Wine ADD BottlingYear int NOT NULL DEFAULT 2025;</code>
        </div>
    </div>
    <div class='response hidden'>

    </div>
</div>
<?php require __DIR__ . '/../../drivers/radio_driver.php'; ?>