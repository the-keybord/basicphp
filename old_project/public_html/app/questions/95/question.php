<div class="w-full max-w-2xl mx-auto p-4">
    <div class='question'>
        <p></p>
        
        <pre>You are managing a digital music library. You need to maintain a database table named Playlist to keep track of user songs. 
The table has the following columns and rows:
<img src='https://zece.info/app/questions/95/2026-04-14 17_02_21.png'>
You must update the table when a user decides to remove specific tracks from their collection.
You need to delete all records with the SongTitle "Midnight Sky".

Which SQL statement should you use?</pre>
    </div>
    <div class='options-container'>
        <div class="option">
            <code>DELETE FROM Playlist WHERE SongTitle IS &#039;Midnight Sky&#039;;</code>
        </div><div class="option">
            <code>DELETE FROM Playlist WHERE SongTitle == &#039;Midnight Sky&#039;;</code>
        </div><div class="option">
            <code>DELETE FROM Playlist WHERE SongTitle EQUALS &#039;Midnight Sky&#039;;</code>
        </div><div class="option">
            <code>DELETE FROM Playlist WHERE SongTitle = &#039;Midnight Sky&#039;;</code>
        </div>
    </div>
    <div class='response hidden'>

    </div>
</div>
<?php require __DIR__ . '/../../drivers/radio_driver.php'; ?>