<div class="w-full max-w-2xl mx-auto p-4">
    <div class='question'>
        <p></p>
        
        <pre>You manage a database for the Technical University dorms. 
You create the following table named <b>StudentDebts</b>:

<table border="1" cellpadding="5" style="border-collapse: collapse; width: 100%; font-family: monospace; font-size: 0.9em;">
  <tr><td>ID</td><td>Name</td><td>City</td><td>Debt (MDL)</td></tr>
  <tr><td>1</td><td>Maria</td><td>Cahul</td><td>500</td></tr>
  <tr><td>2</td><td>Ion</td><td>Chisinau</td><td>200</td></tr>
  <tr><td>3</td><td>Elena</td><td>Balti</td><td>150</td></tr>
  <tr><td>4</td><td>Ion</td><td>Balti</td><td>800</td></tr>
</table>
<b>Ion</b> from <b>Balti</b> pays his debt completely.
Which statement will correctly update the table?</pre>
    </div>
    <div class='options-container'>
        <div class="option">
            <code>UPDATE StudentDebts SET Debt = 0 <br />
WHERE (Name = &#039;Ion&#039; AND City = &#039;Balti&#039;);</code>
        </div>
        
        
        <div class="option">
            <code>INSERT INTO StudentDebts SET Debt = 0 <br />
WHERE ID = 4;</code>
        </div>

        <div class="option">
            <code>UPDATE StudentDebts SET Debt = 0 <br />
WHERE (Name = &#039;Ion&#039; OR City = &#039;Balti&#039;);</code>
        </div>

        <div class="option">
            <code>UPDATE StudentDebts SET Debt = 0 <br />
WHERE ID = 2;</code>
        </div>
    </div>
    <div class='response hidden'>

    </div>
</div>
<?php require __DIR__ . '/../../drivers/radio_driver.php'; ?>