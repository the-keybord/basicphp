<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class QuestionSeeder extends Seeder
{
    public function run(): void
    {
        $subcategories = [
            'design' => DB::table('subcategories')->where('name', 'Database design')->value('id'),
            'retrieval' => DB::table('subcategories')->where('name', 'Data retrieval')->value('id'),
            'management' => DB::table('subcategories')->where('name', 'Database object management')->value('id'),
            'manipulation' => DB::table('subcategories')->where('name', 'Data manipulation')->value('id'),
            'trouble' => DB::table('subcategories')->where('name', 'Troubleshooting')->value('id'),
        ];

        $questionsData = [
            [
                'type' => 'singleselect',
                'sub' => $subcategories['retrieval'],
                'answer' => 'WHERE publisher LIKE \'%stone%\'',
                'xml' => '<question>
<text> Evaluate the following code: SELECT bktitle, price FROM books WHERE publisher = \'stone\' The query writer wants to ensure that any book title with the word "stone" in it is returned. What should the WHERE clause be to have the correct criteria for the writer\'s need? </text>
<image>url</image>
<options>
<option>WHERE publisher IN \'%stone%\'</option>
<option>WHERE publisher =\'%stone%\'</option>
<option>WHERE publisher LIKE \'stone\'</option>
<option>WHERE publisher LIKE \'%stone%\'</option>
</options>
</question>'
            ],
            [
                'type' => 'singleselect',
                'sub' => $subcategories['retrieval'],
                'answer' => 'MAX',
                'xml' => '<question>
<text> An analyst needs to know the highest amount of sales in a list of daily sales amounts. Which keyword should the analyst use to obtain that amount? </text>
<image>url</image>
<options>
<option>VALUES</option>
<option>MOST</option>
<option>MAX</option>
<option>HIGH</option>
</options>
</question>'
            ],
            [
                'type' => 'singleselect',
                'sub' => $subcategories['management'],
                'answer' => 'DROP',
                'xml' => '<question>
<text> Evaluate the following SQL Statement: DELETE view UtahCustomers; A developer runs this statement and gets an error message that this is unable to be deleted. What is the most likely cause of the error? </text>
<image>url</image>
<options>
<option>The developer has insufficient permissions to delete the view.</option>
<option>The TRUNCATE keyword should be used instead of DELETE.</option>
<option>The DROP keyword should be used instead of DELETE.</option>
<option>The REVOKE keyword should be used instead of DELETE.</option>
</options>
</question>'
            ],
            [
                'type' => 'truefalse',
                'sub' => $subcategories['manipulation'],
                'answer' => 'Yes, No, Yes',
                'xml' => '<question>
<text> Evaluate each SQL statement. Select Yes if the statement has a syntax is correct and No if there is syntax error within the statement. </text>
<image>url</image>
<subjects>
<subject>SELECT * INTO Customers FROM CustomersNew</subject>
<subject>INSERT * INTO Customers FROM CustomersNew</subject>
<subject>INSERT INTO Customers (LastName, FirstName) SELECT LastName, FirstName FROM CustomersNew</subject>
</subjects>
</question>'
            ],
            [
                'type' => 'singleselect',
                'sub' => $subcategories['retrieval'],
                'answer' => 'SELECT',
                'xml' => '<question>
<text> What statement is used to retrieve data from a database? </text>
<image>url</image>
<options>
<option>READ</option>
<option>SELECT</option>
<option>EXTRACT</option>
<option>GET</option>
</options>
</question>'
            ],
            [
                'type' => 'singleselect',
                'sub' => $subcategories['retrieval'],
                'answer' => 'SELECT lastname, firstname FROM customers WHERE city &lt;&gt; \'Los Angeles\' AND state NOT IN (\'Oregon\', \'Washington\')',
                'xml' => '<question>
<text> A sales manager wants to see a list of customers that are not in Los Angeles nor the states of Washington and Oregon. Which SQL statement fulfills this request? </text>
<image>url</image>
<options>
<option>SELECT lastname, firstname FROM customers WHERE city != \'Los Angeles\' AND state NOT IN (\'Oregon\', \'Washington\')</option>
<option>SELECT lastname, firstname FROM customers WHERE city &lt;&gt; \'Los Angeles\' AND state &lt;&gt; (\'Oregon\', \'Washington\')</option>
<option>SELECT lastname, firstname FROM customers WHERE city &lt;&gt; \'Los Angeles\' AND state NOT IN (\'Oregon\', \'Washington\')</option>
<option>SELECT lastname, firstname FROM customers WHERE city != \'Los Angeles\' AND state != (\'Oregon\', \'Washington\')</option>
</options>
</question>'
            ],
            [
                'type' => 'singleselect',
                'sub' => $subcategories['manipulation'],
                'answer' => '1',
                'xml' => '<question>
<text> This query is run on the table. UPDATE Products SET Price = 49.99 WHERE (Clothing = \'Shirt\' AND Color = \'Blue\') How many items will update? </text>
<image>tests/db2x/q12.png</image>
<options>
<option>1</option>
<option>2</option>
<option>0</option>
<option>3</option>
</options>
</question>'
            ],
            [
                'type' => 'singleselect',
                'sub' => $subcategories['retrieval'],
                'answer' => 'SELECT state, city, SUM(salesamount) FROM sales GROUP BY state, city HAVING SUM(salesamount) &gt;= 50000',
                'xml' => '<question>
<text> A report writer is trying to retrieve all cities that had total sales of at least $50000. The state, city, and amount fields should be returned. Which statement fulfills that needed? </text>
<image></image>
<options>
<option>SELECT state, city, SUM(salesamount) FROM sales GROUP BY state, city WHERE SUM(salesamount) &gt;= 50000</option>
<option>SELECT state, city, SUM(salesamount) FROM sales WHERE salesamount &gt; = 50000 GROUP BY state, city</option>
<option>SELECT state, city, SUM(salesamount) FROM sales GROUP BY state, city HAVING SUM(salesamount) &gt;= 50000</option>
<option>SELECT state, city, SUM(salesamount) FROM sales HAVING salesamount &gt; = 50000 GROUP BY state, city</option>
</options>
</question>'
            ],
            [
                'type' => 'singleselect',
                'sub' => $subcategories['design'],
                'answer' => 'Birthdate',
                'xml' => '<question>
<text> Evaluate the following table: Which column needs to be moved to another table for this table to be in 3NF? </text>
<image>tests/db2x/q16.png</image>
<options>
<option>Winner</option>
<option>EventID</option>
<option>Birthdate</option>
<option>Event</option>
</options>
</question>'
            ],
            [
                'type' => 'singleselect',
                'sub' => $subcategories['management'],
                'answer' => 'Queries that return large result sets.',
                'xml' => '<question>
<text> A clustered index improves the performance of which types of queries? </text>
<image></image>
<options>
<option>Queries that return large result sets.</option>
<option>Queries that return a range of values by using the = operator.</option>
<option>Queries that do not use ORDER BY or GROUP BY clauses.</option>
<option>Queries that are accessed randomly.</option>
</options>
</question>'
            ],
            [
                'type' => 'dropdown',
                'sub' => $subcategories['design'],
                'answer' => 'Student ID, 1234567',
                'xml' => '<question>
<text>Refer to the table below to evaluate the statements. Use the dropdown menus to select the answer choice that completes each statement. The primary key in this table should be __. The best example of valid data for the first field would be __. </text>
<image>tests/db2x/image2.png</image>
<subjects>
<subject>
<option>Lastname</option>
<option>Address</option>
<option>Student ID</option>
</subject>
<subject>
<option>1234567 </option>
<option>12345abc </option>
<option>1234567890</option>
</subject>
</subjects>
</question>'
            ],
            [
                'type' => 'singleselect',
                'sub' => $subcategories['retrieval'],
                'answer' => 'SELECT region, salesperson, SUM(sales), AVG(sales) FROM totalsales GROUP BY region, salesperson',
                'xml' => '<question>
<text> You are working on a query on a table named totalsales that will total the sales and display an average sale by region and salesperson. Which statement fulfills this need? </text>
<image></image>
<options>
<option>SELECT region, salesperson, SUM(sales), AVG(sales) FROM totalsales</option>
<option>SELECT region, salesperson, SUM(sales), AVERAGE(sales) FROM totalsales</option>
<option>SELECT region, salesperson, SUM(sales), AVG(sales) FROM totalsales GROUP BY region, salesperson</option>
<option>SELECT region, salesperson, SUM(sales), AVERAGE(sales) FROM totalsales GROUP BY region, salesperson, sales</option>
</options>
</question>'
            ],
            [
                'type' => 'singleselect',
                'sub' => $subcategories['management'],
                'answer' => 'Improved Performance',
                'xml' => '<question>
<text> What is the benefit of using Stored Procedures? </text>
<image></image>
<options>
<option>Improved Performance</option>
<option>Storage Saving</option>
<option>Accessibility</option>
<option>Compatibility</option>
</options>
</question>'
            ],
            [
                'type' => 'singleselect',
                'sub' => $subcategories['manipulation'],
                'answer' => 'INSERT INTO customers SELECT * from potential_customers',
                'xml' => '<question>
<text> A database needs to have all potential customers entered into the customers table as the potential customers are now current customers. Which SQL statement accomplishes this task? </text>
<image></image>
<options>
<option>INSERT INTO customers SELECT * from potential_customers</option>
<option>INSERT INTO customers VALUES SELECT * from potential_customers</option>
<option>INSERT INTO customers WHERE * from potential_customers</option>
<option>SELECT * INTO customers from potential_customers</option>
</options>
</question>'
            ],
            [
                'type' => 'multiselect',
                'sub' => $subcategories['retrieval'],
                'answer' => '1, 4',
                'xml' => '<question>
<text> You are trying to build a query that will return the last name, first name, and the number of classes to which each student has enrolled. Only students that have enrolled in at least one class should show in the query results. The classID column needs show as "Classes." Which queries will show the correct results? (Choose two) </text>
<image></image>
<options>
<option>SELECT lastname, firstname, classID Classes FROM students INNER JOIN classes on students.studentid = classes.studentID</option>
<option>SELECT lastname, firstname, classID NAME Classes FROM students INNER JOIN classes on students.studentid = classes.studentID</option>
<option>SELECT lastname, firstname, classID ALIAS Classes FROM students INNER JOIN classes on students.studentid = classes.studentID</option>
<option>SELECT lastname, firstname, classID as Classes FROM students INNER JOIN classes on students.studentid = classes.studentID</option>
</options>
</question>'
            ],
            [
                'type' => 'singleselect',
                'sub' => $subcategories['retrieval'],
                'answer' => '308',
                'xml' => '<question>
<text> In a database, a customers table has 300 rows. A potential_customers table has 10 rows. 2 rows are identical in both tables. The following query is run: SELECT lastname, firstname FROM customers UNION SELECT lastname, firstname FROM potential_customers How many records does the query return? </text>
<image></image>
<options>
<option>2</option>
<option>310</option>
<option>308</option>
<option>302</option>
</options>
</question>'
            ],
            [
                'type' => 'singleselect',
                'sub' => $subcategories['trouble'],
                'answer' => 'Incremental',
                'xml' => '<question>
<text> Of the backup types available for databases, which backup is the fastest? </text>
<image></image>
<options>
<option>Incremental</option>
<option>Differential</option>
<option>Full</option>
</options>
</question>'
            ],
            [
                'type' => 'singleselect',
                'sub' => $subcategories['retrieval'],
                'answer' => 'SELECT lastname, firstname from studentlist INTERSECT SELECT lastname, firstname from archivestudentlist',
                'xml' => '<question>
<text> A report writer for a school wants to show students who appear in both a current student list and an archived student list to identify potential duplicate records between lists. Which query will return the needed records for the writer, including the duplicate records between the two tables? </text>
<image></image>
<options>
<option>SELECT lastname, firstname from studentlist INTERSECT ALL SELECT lastname, firstname from archivestudentlist</option>
<option>SELECT lastname, firstname from studentlist UNION SELECT lastname, firstname from archivestudentlist</option>
<option>SELECT lastname, firstname from studentlist UNION ALL SELECT lastname, firstname from archivestudentlist</option>
<option>SELECT lastname, firstname from studentlist INTERSECT SELECT lastname, firstname from archivestudentlist</option>
</options>
</question>'
            ],
            [
                'type' => 'drag_and_drop',
                'sub' => $subcategories['management'],
                'answer' => 'CREATE TABLE Campers (, ID INT, Name VARCHAR(25), Cabin INT )',
                'xml' => '<question>
<text> You volunteer at a private campground. You are creating a database object named Campers to store the following data: ID Name Cabin 1 Chipmunks 10 2 Squirrels 11 3 Bears 12 Which syntax should you use to create the object? To answer, drag the appropriate SQL statement from the column on the left to its place in the SQL query on the right. Each data type may be used once, more than once, or not at all. step1 __ step2 __, step3 __, step4 __ </text>
<image>url</image>
<options>
<option>Cabin INT )</option>
<option>ProductID INT</option>
<option>Name VARCHAR(25)</option>
<option>CREATE Campers (</option>
<option>CREATE TABLE (</option>
<option>ID INT</option>
<option>CREATE TABLE Campers (</option>
<option>Cabin VARCHAR(25))</option>
</options>
</question>'
            ],
            [
                'type' => 'singleselect',
                'sub' => $subcategories['retrieval'],
                'answer' => 'SELECT TOP 5 Date, Salesperson, SUM (Amount) FROM sales_summary WHERE Date BETWEEN GETDATE() and GETDATE() -7 GROUP BY Date, Salesperson ORDER BY SUM (Amount) DESC',
                'xml' => '<question>
<text> Evaluate the sample of records from the following sales_summary table: For a weekly sales report, a sales manager needs to see the top five salespersons by amount from the current day and the seven previous days. Which SQL statement will fulfill these requirements? </text>
<image>tests/db2x/q29.png</image>
<options>
<option>SELECT TOP 5 Date, Salesperson, SUM (Amount) FROM sales_summary WHERE Date BETWEEN GETDATE() and GETDATE() -7 GROUP BY Date, Salesperson ORDER BY SUM (Amount) DESC</option>
<option>SELECT TOP 5 Date, Salesperson, SUM (Amount) FROM sales_summary WHERE Date BETWEEN GETDATE() and GETDATE() -7 ORDER BY SUM (Amount) DESC</option>
<option>SELECT TOP 5 Date, Salesperson, SUM (Amount) FROM sales_summary WHERE Date BETWEEN GETDATE() and GETDATE() -7 GROUP BY Date, Salesperson</option>
<option>SELECT TOP 5 Date, Salesperson, SUM (Amount) FROM sales_summary HAVING Date BETWEEN GETDATE() and GETDATE() -7 GROUP BY Date, Salesperson ORDER BY SUM (Amount) DESC</option>
</options>
</question>'
            ],
            [
                'type' => 'singleselect',
                'sub' => $subcategories['design'],
                'answer' => 'Record',
                'xml' => '<question>
<text> In a database table, a group of fields across one row is called a what? </text>
<image></image>
<options>
<option>Record</option>
<option>Relationship</option>
<option>Table</option>
<option>Column</option>
</options>
</question>'
            ],
            [
                'type' => 'singleselect',
                'sub' => $subcategories['design'],
                'answer' => 'CREATE TABLE OrderDetails ( OrderDetailID int NOT NULL PRIMARY KEY OrderID int FOREIGN KEY (OrderID) REFERENCES Orders(OrderID) )',
                'xml' => '<question>
<text> A database developer is creating an orderdetails table. As part of the table, orderids should come from the orders table. Which SQL statement satisfies this requirement for creating the table? </text>
<image></image>
<options>
<option>CREATE TABLE OrderDetails ( OrderDetailID int NOT NULL PRIMARY KEY OrderID int FOREIGN KEY (OrderID) REFERENCES Orders(OrderID) )</option>
<option>CREATE TABLE OrderDetails ( OrderDetailID int NOT NULL PRIMARY KEY OrderID int PRIMARY KEY (OrderDetailID) REFERENCES Orders(OrderID) )</option>
<option>CREATE TABLE OrderDetails ( OrderDetailID int NOT NULL PRIMARY KEY OrderID int CONSTRAINT FOREIGN KEY (OrderID) REFERENCES Orders(OrderID) )</option>
<option>CREATE TABLE OrderDetails ( OrderDetailID int NOT NULL PRIMARY KEY OrderID int FOREIGN KEY REFERENCES Orders(OrderID) )</option>
</options>
</question>'
            ],
            [
                'type' => 'singleselect',
                'sub' => $subcategories['design'],
                'answer' => 'Composite Key',
                'xml' => '<question>
<text> Evaluate the following SQL statement, used to create a signups table: CREATE TABLE SIGNUPS ( studentID INT NOT NULL, classID INT NOT NULL, signupdate DATETIME PRIMARY KEY (studentID, classID) ) The studentID is the primary key for the students table. The classID is the primary key for the classes table. Which type of primary key has been created using these two fields for this table? </text>
<image></image>
<options>
<option>Foreign Key</option>
<option>Composite Key</option>
<option>Unique Key</option>
<option>Absolute Key</option>
</options>
</question>'
            ],
            [
                'type' => 'singleselect',
                'sub' => $subcategories['manipulation'],
                'answer' => 'DML',
                'xml' => '<question>
<text> The UPDATE keyword falls under which category of data languages in SQL? </text>
<image></image>
<options>
<option>DQL</option>
<option>DCL</option>
<option>DDL</option>
<option>DML</option>
</options>
</question>'
            ],
            [
                'type' => 'truefalse',
                'sub' => $subcategories['design'],
                'answer' => 'Yes, Yes, Yes, No',
                'xml' => '<question>
<text> For each of the following statements, select Yes if the statement is true and No if the statement is false. </text>
<image></image>
<subjects>
<subject>A foreign key in one table points to a primary key in another table.</subject>
<subject>Foreign keys are used to lookup items in another table.</subject>
<subject>Primary keys must have unique values.</subject>
<subject>Foreign keys must have unique values.</subject>
</subjects>
</question>'
            ],
            [
                'type' => 'singleselect',
                'sub' => $subcategories['design'],
                'answer' => 'CREATE TABLE OrderDetails ( OrderID INT ProductID varchar(10) Qty INT CHECK (Qty &gt;=1) )',
                'xml' => '<question>
<text> A developer is adding a table to a database for line items on orders and wants to ensure all order quantities are 1 or higher. Which SQL statement fulfills this requirement? </text>
<image></image>
<options>
<option>CREATE TABLE OrderDetails ( OrderID INT ProductID varchar(10) Qty INT HAVING (Qty &gt;=1) )</option>
<option>CREATE TABLE OrderDetails ( OrderID INT ProductID varchar(10) Qty INT UNIQUE (Qty &gt;=1) )</option>
<option>CREATE TABLE OrderDetails ( OrderID INT ProductID varchar(10) Qty INT CONSTRAINT (Qty &gt;=1) )</option>
<option>CREATE TABLE OrderDetails ( OrderID INT ProductID varchar(10) Qty INT CHECK (Qty &gt;=1) )</option>
</options>
</question>'
            ],
            [
                'type' => 'truefalse',
                'sub' => $subcategories['management'],
                'answer' => 'No, Yes, Yes',
                'xml' => '<question>
<text> For each of the following statements, select Yes if the statement is true and No if the statement is false. </text>
<image></image>
<subjects>
<subject>DROP TABLE removes only rows from a table.</subject>
<subject>The DELETE statement removes records in a table.</subject>
<subject>TRUNCATE TABLE removes all data from a table without logging the actual deletion of the data.</subject>
</subjects>
</question>'
            ],
            [
                'type' => 'singleselect',
                'sub' => $subcategories['manipulation'],
                'answer' => 'UPDATE StudentDebts SET Debt = 0 WHERE (Name = \'Ion\' AND City = \'Balti\');',
                'xml' => '<question>
<text> You manage a database for the Technical University dorms. You create the following table named StudentDebts: ID | Name | City | Debt (MDL) 1 | Maria | Cahul | 500 2 | Ion | Chisinau | 200 3 | Elena | Balti | 150 4 | Ion | Balti | 800 Ion from Balti pays his debt completely. Which statement will correctly update the table? </text>
<image></image>
<options>
<option>INSERT INTO StudentDebts SET Debt = 0 WHERE ID = 4;</option>
<option>UPDATE StudentDebts SET Debt = 0 WHERE ID = 2;</option>
<option>UPDATE StudentDebts SET Debt = 0 WHERE (Name = \'Ion\' OR City = \'Balti\');</option>
<option>UPDATE StudentDebts SET Debt = 0 WHERE (Name = \'Ion\' AND City = \'Balti\');</option>
</options>
</question>'
            ],
            [
                'type' => 'singleselect',
                'sub' => $subcategories['retrieval'],
                'answer' => 'LIKE',
                'xml' => '<question>
<text> You are writing a SELECT statement to find every product whose name contains a specific character. Which keyword should you use in your WHERE clause? </text>
<image></image>
<options>
<option>IN</option>
<option>FETCH</option>
<option>BETWEEN</option>
<option>LIKE</option>
</options>
</question>'
            ],
            [
                'type' => 'singleselect',
                'sub' => $subcategories['design'],
                'answer' => 'a foreign key',
                'xml' => '<question>
<text> You have a Department table and an Employee table in your database. You need to ensure that an employee can be assigned to only an existing department. What should you apply to the Employee table? </text>
<image></image>
<options>
<option>an index</option>
<option>a foreign key</option>
<option>a data type</option>
<option>a primary key</option>
</options>
</question>'
            ],
            [
                'type' => 'singleselect',
                'sub' => $subcategories['design'],
                'answer' => 'Birthday',
                'xml' => '<question>
<text> You create the following table, which displays orders from the company. Which column prevents the table from being in third normal form? </text>
<image>https://zece.info/app/questions/82/image_1.png</image>
<options>
<option>Birthday</option>
<option>TotalAmount</option>
<option>OrderId</option>
<option>CustomerId</option>
</options>
</question>'
            ],
            [
                'type' => 'singleselect',
                'sub' => $subcategories['management'],
                'answer' => 'ALTER TABLE Garaj ADD Tractor_name Varchar;',
                'xml' => '<question>
<text>Which SQL statement is a data definition language (DDL) statement?</text>
<image></image>
<options>
<option>INSERT INTO Employee VALUES (\'Matvei Feraru\');</option>
<option>SELECT * INTO Boierii FROM Saracii;</option>
<option>ALTER TABLE Garaj ADD Tractor_name Varchar;</option>
<option>SELECT EmployeeName FROM Employee WHERE EmployeeName = \'Mr Beast\';</option>
</options>
</question>'
            ],
            [
                'type' => 'singleselect',
                'sub' => $subcategories['retrieval'],
                'answer' => 'SELECT ItemName, Price FROM Pizza WHERE ItemDescription LIKE \'%pepperoni%\';',
                'xml' => '<question>
<text> The Pizza table contains the following data: Which query will retrieve ItemName and Price when pepperoni appears in the ItemDescription column? </text>
<image>https://zece.info/app/questions/76/image_1.png</image>
<options>
<option>SELECT ItemName, Price FROM Pizza WHERE ItemDescription LIKE \'pepperoni\';</option>
<option>SELECT ItemName, Price FROM Pizza WHERE ItemDescription = \'pepperoni\';</option>
<option>SELECT ItemName, Price FROM Pizza WHERE ItemDescription IN \'%pepperoni%\';</option>
<option>SELECT ItemName, Price FROM Pizza WHERE ItemDescription LIKE \'%pepperoni%\';</option>
</options>
</question>'
            ],
            [
                'type' => 'singleselect',
                'sub' => $subcategories['design'],
                'answer' => 'CREATE TABLE DriverAssignments (DriverID INTEGER, CarID INTEGER, PRIMARY KEY(DriverID, CarID));',
                'xml' => '<question>
<text>Which statement correctly creates a composite primary key for the driver assignment table?</text>
<image></image>
<options>
<option>CREATE TABLE DriverAssignments (DriverID INTEGER, CarID INTEGER, PRIMARY KEY(DriverID, CarID));</option>
<option>CREATE TABLE DriverAssignments (DriverID INTEGER PRIMARY KEY, CarID INTEGER PRIMARY KEY);</option>
<option>CREATE TABLE DriverAssignments (DriverID INTEGER, CarID INTEGER, PRIMARY KEY DriverID, PRIMARY KEY CarID);</option>
<option>CREATE TABLE DriverAssignments (DriverID INTEGER, CarID INTEGER, PRIMARY KEY);</option>
</options>
</question>'
            ],
            [
                'type' => 'singleselect',
                'sub' => $subcategories['management'],
                'answer' => 'CREATE TABLE Employee (EmployeeID INTEGER INDEX);',
                'xml' => '<question>
<text>Which statement creates an index?</text>
<image></image>
<options>
<option>CREATE TABLE Employee (EmployeeID INTEGER PRIMARY KEY);</option>
<option>CREATE TABLE Employee (EmployeeID INTEGER NULL);</option>
<option>CREATE TABLE Employee (EmployeeID INTEGER INDEX);</option>
<option>CREATE TABLE Employee (EmployeeID INTEGER DISTINCT);</option>
</options>
</question>'
            ],
            [
                'type' => 'singleselect',
                'sub' => $subcategories['retrieval'],
                'answer' => 'INTERSECT',
                'xml' => '<question>
<text>Which keyword combines the results of two queries and returns only rows that appear in both result sets?</text>
<image></image>
<options>
<option>JOIN</option>
<option>UNION</option>
<option>INTERSECT</option>
<option>ALL</option>
</options>
</question>'
            ],
            [
                'type' => 'singleselect',
                'sub' => $subcategories['management'],
                'answer' => 'DROP',
                'xml' => '<question>
<text> You need to delete a database table. Which data definition language (DDL) keyword should you use? </text>
<image></image>
<options>
<option>DROP</option>
<option>ALTER</option>
<option>TRUNCATE</option>
<option>DELETE</option>
</options>
</question>'
            ],
            [
                'type' => 'singleselect',
                'sub' => $subcategories['design'],
                'answer' => 'foreign key',
                'xml' => '<question>
<text> You have two tables named Garage and Cars. You need to ensure that each record in the Cars table has a valid associated garage record in the Garage table. Which database object should you add to the Cars table? </text>
<image></image>
<options>
<option>clustered index</option>
<option>foreign key</option>
<option>nonclustered index</option>
<option>primary key</option>
</options>
</question>'
            ],
            [
                'type' => 'dropdown',
                'sub' => $subcategories['retrieval'],
                'answer' => 'Extension, IS NOT NULL, ORDER BY',
                'xml' => '<question>
<text> The Customers table includes the following data: You need to create a query that returns a result set containing the LastName, PhoneNumber, and Extension for customers that have extensions. The result set should be sorted by the customer\'s last name. Complete the code by selecting the correct option from each drop-down list. Note: You will receive partial credit for each correct selection. SELECT LastName, PhoneNumber, Extension FROM Customers WHERE __ __ __ LastName; </text>
<image>https://zece.info/app/questions/87/image_1.png</image>
<subjects>
<subject>
<option>Extension</option>
<option>LastName</option>
<option>PhoneNumber</option>
</subject>
<subject>
<option>IS NOT NULL</option>
<option>IS NULL</option>
<option>= NULL</option>
</subject>
<subject>
<option>ORDER BY</option>
<option>GROUP BY</option>
</subject>
</subjects>
</question>'
            ],
            [
                'type' => 'singleselect',
                'sub' => $subcategories['trouble'],
                'answer' => 'The column lastname is misspelled or does not exist in the table.',
                'xml' => '<question>
<text> You run the query below and get the error: “Invalid column name ‘lastname’.” SELECT firstname, lastname FROM Employees What is the most likely cause? </text>
<image></image>
<options>
<option>The query must include an ORDER BY clause.</option>
<option>The table Employees does not exist.</option>
<option>The column lastname is misspelled or does not exist in the table.</option>
<option>SQL Server needs to be restarted.</option>
</options>
</question>'
            ],
        ];

        foreach ($questionsData as $data) {
            DB::table('questions')->insert([
                'primary_subcategory_id' => $data['sub'],
                'question_type' => $data['type'],
                'correct_answer_string' => $data['answer'],
                'xml_content' => $data['xml'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
