<div class="w-full max-w-2xl mx-auto p-4">
    <div class='question'>
        <p></p>
        
        <pre>The ShelterCats table contains data about various cats currently in the facility.
<img src='https://zece.info/app/questions/106/2026-04-14 18_00_48.png'>
The veterinarian has recommended increasing Luna's daily calorie intake by 10 percent.

Which query will increase the DailyCalories for CatID 1 by 10 percent?</pre>
    </div>
    <div class='options-container'>
        <div class="option">
            <code>SET DailyCalories = DailyCalories * 1.10 
                FROM ShelterCats WHERE CatID = 1;</code>
        </div><div class="option">
            <code>USE ShelterCats 
                SET DailyCalories = DailyCalories * 1.10 WHERE CatID = 1;</code>
        </div><div class="option">
            <code>UPDATE ShelterCats 
                SET DailyCalories = DailyCalories * 1.10 WHERE CatID = 1;</code>
        </div><div class="option">
            <code>ALTER ShelterCats 
                SET DailyCalories = DailyCalories * 1.10 WHERE CatID = 1;</code>
        </div>
    </div>
    <div class='response hidden'>

    </div>
</div>
<?php require __DIR__ . '/../../drivers/radio_driver.php'; ?>