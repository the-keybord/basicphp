<div class="w-full max-w-2xl mx-auto p-4">
    <div class='question'>
        <p></p>
        
        <pre>You have the following entity relationship diagram (ERD) for a clinic with referential integrity enforced:
<img src='https://zece.info/app/questions/94/2026-04-14 16_59_14.png'>
You run the following query:

        INSERT INTO Appointment(DoctorID, PatientID) VALUES (12, 45);

You receive the following error:

Msg 547, Level 16, State 0, Line 1  
The INSERT statement conflicted with the FOREIGN KEY constraint "FK_Appointment_Doctor".  
The conflict occurred in database "HealthSync", table "dbo.Doctor", column "ID".  
The statement has been terminated.

Completion time: 2026-04-14T17:56:13.5781753-04:00  

What is the cause of this problem?</pre>
    </div>
    <div class='options-container'>
        <div class="option">
            <code>The Patient table has no rows that have an ID value of 45.</code>
        </div><div class="option">
            <code>The Appointment table has an existing row that has a DoctorID value of 12.</code>
        </div><div class="option">
            <code>The Appointment table has an existing row that has a PatientID value of 45.</code>
        </div><div class="option">
            <code>The Doctor table has no rows that have an ID value of 12.</code>
        </div>
    </div>
    <div class='response hidden'>

    </div>
</div>
<?php require __DIR__ . '/../../drivers/radio_driver.php'; ?>