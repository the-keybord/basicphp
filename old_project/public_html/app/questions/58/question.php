<div class="w-full max-w-2xl mx-auto p-4">
    <div class='question'>
        <p>You have the following tables: <strong>mecanic</strong>, <strong>reparatie</strong>, and <strong>masina</strong>. You need to return the mechanic's name and the model of each car they are working on. Mechanics not assigned to any repair should not be returned.</p>
        
        <pre><img src='https://zece.info/app/questions/58/image_1.png'>

You execute the following query:

        SELECT mecanic.nume, mecanic.prenume, masina.model 
        FROM mecanic 
        OUTER JOIN masina WHERE mecanic.id_masina = masina.id_masina;

The query returns an error.
How should you correct the query?</pre>
    </div>
    <div class='options-container'>
        <div class="option">
            <code>SELECT mecanic.nume, mecanic.prenume, masina.model FROM masina <br />
INNER JOIN reparatie ON reparatie.id_masina = masina.id_masina <br />
INNER JOIN mecanic ON reparatie.id_mecanic = mecanic.id_mecanic;</code>
        </div><div class="option">
            <code>SELECT nume, prenume, model FROM mecanic <br />
INNER JOIN ON masina, reparatie <br />
WHERE reparatie.id_masina = masina.id_masina AND reparatie.id_mecanic = mecanic.id_mecanic;</code>
        </div><div class="option">
            <code>SELECT nume, prenume, model FROM masina <br />
INNER JOIN reparatie WHERE reparatie.id_masina = masina.id_masina <br />
INNER JOIN mecanic WHERE reparatie.id_mecanic = mecanic.id_mecanic;</code>
        </div><div class="option">
            <code>SELECT mecanic.nume, mecanic.prenume, masina.model FROM mecanic <br />
INNER JOIN masina, reparatie <br />
WHERE reparatie.id_masina = masina.id_masina AND reparatie.id_mecanic = mecanic.id_mecanic;</code>
        </div>
    </div>
    <div class='response hidden'>

    </div>
</div>
<?php require __DIR__ . '/../../drivers/radio_driver.php'; ?>