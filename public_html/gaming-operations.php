<!--Test Oracle file for UBC CPSC304 2018 Winter Term 1
  Created by Jiemin Zhang
  Modified by Simona Radu
  Modified by Jessica Wong (2018-06-22)
  This file shows the very basics of how to execute PHP commands
  on Oracle.
  Specifically, it will drop a table, create a table, insert values
  update values, and then query for values

  IF YOU HAVE A TABLE CALLED "demoTable" IT WILL BE DESTROYED

  The script assumes you already have a server set up
  All OCI commands are commands to the Oracle libraries
  To get the file to work, you must place it somewhere where your
  Apache server can run it, and you must rename it to have a ".php"
  extension.  You must also change the username and password on the
  OCILogon below to be your ORACLE username and password -->

  <html>
    <head>
        <title>Gaming Businesses</title>
    </head>

    <body>
        <h2>Reset</h2>
        <p>If you wish to reset the table press on the reset button. If this is the first time you're running this page, you MUST use reset</p>

        <form method="POST" action="gaming-operations.php">
            <!-- if you want another page to load after the button is clicked, you have to specify that page in the action parameter -->
            <input type="hidden" id="resetTablesRequest" name="resetTablesRequest">
            <p><input type="submit" value="Reset" name="reset"></p>
        </form>

        <hr />
        <h2>Insert A New Customer</h2>
        <form method="POST" action="gaming-operations.php"> <!--refresh page when submitted-->
            <input type="hidden" id="insertCustomerRequest" name="insertCustomerRequest">
            Customer ID: <input type="text" name="customerID"> <br /><br />
            First Name: <input type="text" name="customerFirstName"> <br /><br />
            Last Name: <input type="text" name="customerLastName"> <br /><br />
            Phone Number: <input type="text" name="customerPhone"> <br /><br />
            Email: <input type="text" name="customerEmail"> <br /><br />
            Spent On Games: <input type="text" name="customerSpentGames"> <br /><br />
            Spent On Consoles: <input type="text" name="customerSpentConsoles"> <br /><br />
            <input type="submit" value="Insert" name="insertCustomer"></p>
        </form>

        <hr />

        <h2>Remove An Existing Customer</h2>
        <form method="POST" action="gaming-operations.php"> <!--refresh page when submitted-->
            <input type="hidden" id="removeCustomerRequest" name="removeCustomerRequest">
            Customer ID: <input type="text" name="customerIDRemove"> <br /><br />
            <input type="submit" value="Remove" name="removeCustomer"></p>
        </form>

        <hr />

        <h2>Update An Existing Customer</h2>
        <form method="POST" action="gaming-operations.php"> <!--refresh page when submitted-->
            <input type="hidden" id="updateCustomerRequest" name="updateCustomerRequest">
            Customer ID: <input type="text" name="customerIDUpdate"> <br /><br />
            New Email: <input type="text" name="customerEmailUpdate"> <br /><br />
            <input type="submit" value="Update" name="updateCustomer"></p>
        </form>

        <hr />

        <h2>Display the Customers</h2>
        <form method="GET" action="gaming-operations.php"> <!--refresh page when submitted-->
            <input type="hidden" id="displayCustomersRequest" name="displayCustomersRequest">
            <input type="submit" name="displayCustomers"></p>
        </form>

        <?php
		//this tells the system that it's no longer just parsing html; it's now parsing PHP

        $success = True; //keep track of errors so it redirects the page only if there are no errors
        $db_conn = NULL; // edit the login credentials in connectToDB()
        $show_debug_alert_messages = False; // set to True if you want alerts to show you which methods are being triggered (see how it is used in debugAlertMessage())

        function debugAlertMessage($message) {
            global $show_debug_alert_messages;

            if ($show_debug_alert_messages) {
                echo "<script type='text/javascript'>alert('" . $message . "');</script>";
            }
        }

        function executePlainSQL($cmdstr) { //takes a plain (no bound variables) SQL command and executes it
            //echo "<br>running ".$cmdstr."<br>";
            global $db_conn, $success;

            $statement = OCIParse($db_conn, $cmdstr);
            //There are a set of comments at the end of the file that describe some of the OCI specific functions and how they work

            if (!$statement) {
                echo "<br>Cannot parse the following command: " . $cmdstr . "<br>";
                $e = OCI_Error($db_conn); // For OCIParse errors pass the connection handle
                echo htmlentities($e['message']);
                $success = False;
            }

            $r = OCIExecute($statement, OCI_DEFAULT);
            if (!$r) {
                echo "<br>Cannot execute the following command: " . $cmdstr . "<br>";
                $e = oci_error($statement); // For OCIExecute errors pass the statementhandle
                echo htmlentities($e['message']);
                $success = False;
            }

			return $statement;
		}

        function executeBoundSQL($cmdstr, $list) {
            /* Sometimes the same statement will be executed several times with different values for the variables involved in the query.
		In this case you don't need to create the statement several times. Bound variables cause a statement to only be
		parsed once and you can reuse the statement. This is also very useful in protecting against SQL injection.
		See the sample code below for how this function is used */

			global $db_conn, $success;
			$statement = OCIParse($db_conn, $cmdstr);

            if (!$statement) {
                echo "<br>Cannot parse the following command: " . $cmdstr . "<br>";
                $e = OCI_Error($db_conn);
                echo htmlentities($e['message']);
                $success = False;
            }

            foreach ($list as $tuple) {
                foreach ($tuple as $bind => $val) {
                    //echo $val;
                    //echo "<br>".$bind."<br>";
                    OCIBindByName($statement, $bind, $val);
                    unset ($val); //make sure you do not remove this. Otherwise $val will remain in an array object wrapper which will not be recognized by Oracle as a proper datatype
				}

                $r = OCIExecute($statement, OCI_DEFAULT);
                if (!$r) {
                    echo "<br>Cannot execute the following command: " . $cmdstr . "<br>";
                    $e = OCI_Error($statement); // For OCIExecute errors, pass the statementhandle
                    echo htmlentities($e['message']);
                    echo "<br>";
                    $success = False;
                }
            }
        }

        function printResult($result) { //prints results from a select statement
            echo "<br>Retrieved data from table Customers:<br>";
            echo "<table>";
            echo "<tr><th>customerID</th><th>First Name</th><th>Last Name</th><th>Phone Number</th>
                      <th>Email</th><th>Spent On Games</th><th>Spent On Consoles</th></tr>";

            while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
                echo "<tr><td>" . $row[0] . "</td><td>" . $row[1] . "</td>" .
                     "<td>" . $row[2] . "</td><td>" . $row[3] . "</td>" .
                     "<td>" . $row[4] . "</td><td>" . $row[5] . "</td>" .
                     "<td>" . $row[6] . "</td></tr>"; //or just use "echo $row[0]"
            }

            echo "</table>";
        }

        function printConsolesBought($result) { //prints results from a select statement
            echo "<br>Retrieved data from table ConsolesBought:<br>";
            echo "<table>";
            echo "<tr><th>SIN Number</th><th>Console Name</th><th>Release Date</th><th>Customer ID</th>
                      <th>Owned Since</th><th>Price</th></tr>";

            while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
                echo "<tr><td>" . $row[0] . "</td><td>" . $row[1] . "</td>" .
                     "<td>" . $row[2] . "</td><td>" . $row[3] . "</td>" .
                     "<td>" . $row[4] . "</td><td>" . $row[5] . "</td></tr>"; //or just use "echo $row[0]"
            }

            echo "</table>";
        }

        function connectToDB() {
            global $db_conn;

            // Your username is ora_(CWL_ID) and the password is a(student number). For example,
			// ora_platypus is the username and a12345678 is the password.
            $db_conn = OCILogon("ora_deelye", "a53933157", "dbhost.students.cs.ubc.ca:1522/stu");

            if ($db_conn) {
                debugAlertMessage("Database is Connected");
                return true;
            } else {
                debugAlertMessage("Cannot connect to Database");
                $e = OCI_Error(); // For OCILogon errors pass no handle
                echo htmlentities($e['message']);
                return false;
            }
        }

        function disconnectFromDB() {
            global $db_conn;

            debugAlertMessage("Disconnect from Database");
            OCILogoff($db_conn);
        }

        function handleUpdateRequest() {
            global $db_conn;

            $customer_ID = $_POST['customerIDUpdate'];
            $new_email = $_POST['customerEmailUpdate'];
            
            // you need the wrap the old name and new name values with single quotations
            executePlainSQL("UPDATE Customer SET email='" . $new_email . "' WHERE cid='" . $customer_ID . "'");
            OCICommit($db_conn);
        }

        function handleResetRequest() {
            global $db_conn;
            // Drop old table
            executePlainSQL("DROP TABLE MembershipDetails CASCADE CONSTRAINTS");
            executePlainSQL("DROP TABLE MembershipOwned CASCADE CONSTRAINTS");
            executePlainSQL("DROP TABLE CustomerSpending CASCADE CONSTRAINTS");
            executePlainSQL("DROP TABLE Customer CASCADE CONSTRAINTS");
            executePlainSQL("DROP TABLE ConsolesBought CASCADE CONSTRAINTS");

            // Create new table
            $query = "CREATE TABLE MembershipDetails
            (
                membershipLevel  char(30),
                personalDiscount int,
                PRIMARY KEY (membershipLevel)
            )";
            executePlainSQL($query);

            $query = "CREATE TABLE MembershipOwned
            (
                totalSpent      int      DEFAULT 0,
                membershipLevel char(30),
                PRIMARY KEY (totalSpent),
                FOREIGN KEY (membershipLevel) REFERENCES MembershipDetails
            )";
            executePlainSQL($query);
            
            $query = "CREATE TABLE CustomerSpending
            (
                spentOnGames    int,
                spentOnConsoles	int,
                totalSpent		int,
                PRIMARY KEY (spentOnGames, spentOnConsoles),
                FOREIGN KEY (totalSpent) REFERENCES MembershipOwned
            )";
            executePlainSQL($query);

            $query = "CREATE TABLE Customer
            (
                cid             int,
                firstName       char(30),
                lastName        char(30),
                phoneNumber     int UNIQUE,
                email           varchar(80) UNIQUE,
                spentOnGames    int DEFAULT 0,
                spentOnConsoles int DEFAULT 0,
                PRIMARY KEY (cid),
                UNIQUE (phoneNumber, email),
                FOREIGN KEY (spentOnGames, spentOnConsoles) REFERENCES CustomerSpending
                    ON DELETE SET NULL)";
            executePlainSQL($query);

            $query = "CREATE TABLE ConsolesBought
            (
                sinNumber   int,
                consoleName varchar(30),
                releaseDate date,
                cid         int NOT NULL,
                ownedSince  date,
                price       int NOT NULL,
                PRIMARY KEY (sinNumber),
                FOREIGN KEY (cid) REFERENCES Customer ON DELETE CASCADE
            )";
            executePlainSQL($query);

            executePlainSQL("INSERT INTO MembershipDetails VALUES('bronze', 10)");
            executePlainSQL("INSERT INTO MembershipOwned VALUES(10, 'bronze')");
            executePlainSQL("INSERT INTO CustomerSpending VALUES(10, 100, 10)");
            executePlainSQL("INSERT INTO Customer VALUES(123, 'Emily', 'Lee', 6041234567, 'elee@gmail.com', 10, 100)");
            executePlainSQL("INSERT INTO Customer VALUES(124, 'Amanda', 'Lee', 6043214567, 'alee@gmail.com', 10, 100)");
            executePlainSQL("INSERT INTO ConsolesBought VALUES (10000000, 'Xbox 360', DATE'2010-12-05', 123, DATE'2012-06-27', 278)");
            echo "<br> Creating New Tables <br>";
            OCICommit($db_conn);
        }

        function handleInsertRequest() {
            global $db_conn;

            //Getting the values from user and insert data into the table
            $tuple = array (
                ":bind1" => $_POST['customerID'],
                ":bind2" => $_POST['customerFirstName'],
                ":bind3" => $_POST['customerLastName'],
                ":bind4" => $_POST['customerPhone'],
                ":bind5" => $_POST['customerEmail'],
                ":bind6" => $_POST['customerSpentGames'],
                ":bind7" => $_POST['customerSpentConsoles']
            );

            $alltuples = array (
                $tuple
            );

            executeBoundSQL("INSERT INTO Customer VALUES (:bind1, :bind2, :bind3, :bind4,
                            :bind5, :bind6, :bind7)", $alltuples);
            OCICommit($db_conn);
        }

        function handleRemoveRequest() {
            global $db_conn;

            $customerID = $_POST['customerIDRemove'];

            executePlainSQL("DELETE FROM Customer WHERE cid='" . $customerID . "'");
            OCICommit($db_conn);
        }

        // function handleCountRequest() {
        //     global $db_conn;

        //     $result = executePlainSQL("SELECT Count(*) FROM demoTable");

        //     if (($row = oci_fetch_row($result)) != false) {
        //         echo "<br> The number of tuples in demoTable: " . $row[0] . "<br>";
        //     }
        // }

        function handleDisplayRequest() {
            global $db_conn;

            $result = executePlainSQL("SELECT * FROM Customer");

            printResult($result);

            $result = executePlainSQL("SELECT * FROM ConsolesBought");

            printConsolesBought($result);
        }      

        // HANDLE ALL POST ROUTES
	// A better coding practice is to have one method that reroutes your requests accordingly. It will make it easier to add/remove functionality.
        function handlePOSTRequest() {
            if (connectToDB()) {
                if (array_key_exists('resetTablesRequest', $_POST)) {
                    handleResetRequest();
                } else if (array_key_exists('insertCustomerRequest', $_POST)) {
                    handleInsertRequest();
                } else if (array_key_exists('removeCustomerRequest', $_POST)) {
                    handleRemoveRequest();
                } else if (array_key_exists('updateCustomerRequest', $_POST)) {
                    handleUpdateRequest();
                }
                disconnectFromDB();
            }
        }

        // HANDLE ALL GET ROUTES
	// A better coding practice is to have one method that reroutes your requests accordingly. It will make it easier to add/remove functionality.
        function handleGETRequest() {
            if (connectToDB()) {
                if (array_key_exists('countTuples', $_GET)) {
                    handleCountRequest();
                } else if (array_key_exists('displayCustomers', $_GET)) {
                    handleDisplayRequest();
                }
                disconnectFromDB();
            }
        }

		if (isset($_POST['reset']) || isset($_POST['updateSubmit']) || isset($_POST['insertCustomer']) 
        || isset($_POST['removeCustomer']) || isset($POST['updateCustomer'])) {
            handlePOSTRequest();
        } else if (isset($_GET['countTupleRequest']) || isset($_GET['displayCustomersRequest'])) {
            handleGETRequest();
        }
		?>
	</body>
</html>