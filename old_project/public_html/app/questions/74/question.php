<div class="w-full max-w-2xl mx-auto p-4">
    <div class='question'>
    <p></p>
    <pre>You are managing a Library system and need a report for the 2024 season.
The report must:
1. Show books that were purchased on or after June 1, 2024.
2. Show books that are 'Archived' AND were moved to storage any time in 2024.
3. Sort the list by purchase date, newest first.

Which query should you use?</pre>
</div>
<div class='options-container'>
    <div class="option">
        <code>SELECT * FROM library_books 
WHERE purchase_date >= '2024-06-01' 
OR (status = 'Archived' OR archive_date LIKE '2024%') 
ORDER BY purchase_date DESC;</code>
    </div>
    <div class="option">
        <code>SELECT * FROM library_books 
WHERE purchase_date >= '2024-06-01' 
OR (status = 'Archived' AND archive_date LIKE '2024%') 
ORDER BY purchase_date DESC;</code>
    </div>
    <div class="option">
        <code>SELECT * FROM library_books 
WHERE purchase_date >= '2024-06-01' 
AND (status = 'Archived' AND archive_date LIKE '2024%') 
ORDER BY purchase_date ASC;</code>
    </div>
    <div class="option">
        <code>SELECT * FROM library_books 
WHERE purchase_date >= '2024-06-01' 
AND (status = 'Archived' OR archive_date LIKE '2024%') 
ORDER BY purchase_date DESC;</code>
    </div>
</div>
    <div class='response hidden'>

    </div>
</div>
<?php require __DIR__ . '/../../drivers/radio_driver.php'; ?>