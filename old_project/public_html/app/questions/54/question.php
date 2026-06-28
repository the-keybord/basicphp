
<div class="w-full max-w-2xl mx-auto p-4">
    <!-- The Question Card -->
    <div class='question'>
        <p></p>
        
        <pre>
You are designing a database for a university that must meet these requirements:
    - A table named STUDENT represents all the students in the university. The ID field uniquely identifies each student.
    - A table named COURSE represents all the courses offered. The ID field uniquely identifies each course.
    - Each student can be enrolled in zero or more courses.
    - Each course can have zero or more students enrolled in it.
Task: You need to design the entity relationship diagram (ERD) to meet these requirements and enforce referential integrity.

        STUDENT Table
        Student_ID {{{PRIMARY KEY|||FOREIGN KEY}}}
        Name
        Model

        COURSE Table
        Course_ID {{{PRIMARY KEY|||FOREIGN KEY}}}
        Name
        Credits

        ENROLLMENT Table
        Student_ID {{{PRIMARY KEY|||FOREIGN KEY|||UNIQUE KEY}}}
        Course_ID {{{PRIMARY KEY|||FOREIGN KEY|||UNIQUE KEY}}}
    </pre>
    </div>

    <div class='response hidden'>

    </div>
</div>
<?php require __DIR__ . '/../../drivers/down_driver.php'; ?>
