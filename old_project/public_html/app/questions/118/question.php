
<div class="w-full max-w-2xl mx-auto p-4">
    <!-- The Question Card -->
    <div class='question'>
        <p></p>
        <img src='https://zece.info/app/questions/118/image_1.png'>
        <pre>You are modifying a fitness database and need to create a UserMeals table.
The table has the following requirements:

Complete the code by selecting the correct option from each drop-down list.

{{{ADD TABLE|||ALTER TABLE|||CREATE TABLE|||INSERT TABLE}}} UserMeals (
    MealId INT {{{KEY,|||PRIMARY KEY AUTO_INCREMENT,|||AUTO,|||PRIMARY KEY AUTO,}}}
    UserId {{{INT FOREIGN KEY,|||INT NOT NULL,|||INT NOT NULL FOREIGN KEY,|||INT CHECK,}}}
    MealName VARCHAR(100) NOT NULL,
    MealDate DATE NOT NULL,
    Calories INT NOT NULL CHECK (Calories > 0),
    Notes TEXT,
    {{{FOREIGN KEY (UserId) REFERENCES|||FOREIGN KEY|||CONSTRAINT (UserId) FOREIGN KEY|||UserId FOREIGN KEY TO}}} Users(UserId) ON DELETE CASCADE
);




</pre>
    </div>

    <div class='response hidden'>

    </div>
</div>
<?php require __DIR__ . '/../../drivers/down_driver.php'; ?>
