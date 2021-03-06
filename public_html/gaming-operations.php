<!--The script assumes you already have a server set up
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
        <p>If you wish to reset the tables, follow the instructions on the reset button.</p>

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

        <h2>Find Customers Who Spent Over a Certain Amount</h2>
        <form method="GET" action="gaming-operations.php"> <!--refresh page when submitted-->
            <input type="hidden" id="customerSpentRequest" name="customerSpentRequest">
            Find customers whose spending is more than $ <input type="text" name="totalSpentGames"> on games. <br /><br />
            Find customers whose spending is more than $ <input type="text" name="totalSpentConsoles"> on consoles. <br /><br />
            <input type="submit" value="Search" name="customerSpent"></p>
        </form>

        <hr />

        <h2>Find Customer Information</h2>
        <form method="GET" action="gaming-operations.php">
            <input type="hidden" id="findCustomerRequest" name="findCustomerRequest">
            <input type="checkbox" id="findCID" name="findCid" value="cid">
            <label for="findCID">Customer ID</label><br>
            <input type="checkbox" id="findFirstName" name="findFirstName" value="firstName">
            <label for="findFirstName">First Name</label><br>
            <input type="checkbox" id="findLastName" name="findLastName" value="lastName">
            <label for="findLastName">Last Name</label><br>
            <input type="checkbox" id="findPhone" name="findPhone" value="phoneNumber">
            <label for="findPhone">Phone Number</label><br>
            <input type="checkbox" id="findEmail" name="findEmail" value="email">
            <label for="findEmail">Email</label><br>
            <input type="checkbox" id="findSpentOnGames" name="findSpentOnGames" value="spentOnGames">
            <label for="findSpentOnGames">Spent On Games</label><br>
            <input type="checkbox" id="findSpentOnConsoles" name="findSpentOnConsoles" value="spentOnConsoles">
            <label for="findSpentOnConsoles">Spent On Consoles</label><br/><br/>
            <input type="submit" name="findCustomer"></p>
        </form>

        <hr />

        <h2>Customers Who Own this Console</h2>
        <form method="GET" action="gaming-operations.php"> <!--refresh page when submitted-->
            <label for ="consoles">Console:</label>
            <input type="hidden" id="customerConsolesRequest" name="customerConsolesRequest">
            <select name="consoleName">
                <option value="Xbox 360">Xbox 360</option>
                <option value="Nintendo Wii">Nintendo Wii</option>
                <option value="Nintendo Switch">Nintendo Switch</option>
                <option value="PS3">PS3</option>
                <option value="PS5">PS5</option>
            </select>
            <input type="submit" name="customerConsoles"></p>
        </form>

        <hr />

        <h2>Find Average Customer Spending</h2>
        <form method="GET" action="gaming-operations.php"> <!--refresh page when submitted-->
            <input type="hidden" id="averageSpendingRequest" name="averageSpendingRequest">
            <input type="submit" name="averageSpending"></p>
        </form>

        <hr />

        <h2>Find Amount of Consoles Sold</h2>
        <form method="GET" action="gaming-operations.php"> <!--refresh page when submitted-->
            <input type="hidden" id="popularConsolesRequest" name="popularConsolesRequest">
            <input type="submit" name="popularConsoles"></p>
        </form>

        <hr />

        <h2>Find Customers Who Own All The Consoles</h2>
        <form method="GET" action="gaming-operations.php">
            <input type="hidden" id="allConsolesOwnedRequest" name="allConsolesOwnedRequest">
            <input type="submit" name="allConsolesOwned"></p>
        </form>

        <hr />

        <h2>Display the Customers</h2>
        <form method="GET" action="gaming-operations.php"> <!--refresh page when submitted-->
            <input type="hidden" id="displayCustomersRequest" name="displayCustomersRequest">
            <input type="submit" name="displayCustomers"></p>
        </form>

        <hr />

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

        function printCustomerResults($result) { //prints results from a select statement
            $table_header = "<tr>";

            if (isset($_GET['findCid'])) {$table_header .= "<th>Customer ID</th>";}
            if (isset($_GET['findFirstName'])) {$table_header .= "<th>First Name</th>";}
            if (isset($_GET['findLastName'])) {$table_header .= "<th>Last Name</th>";}
            if (isset($_GET['findPhone'])) {$table_header .= "<th>Phone Number</th>";}
            if (isset($_GET['findEmail'])) {$table_header .= "<th>Email<th/>";}
            if (isset($_GET['findSpentOnGames'])) {$table_header .= "<th>Spent On Games</th>";}
            if (isset($_GET['findSpentOnConsoles'])) {$table_header .= "<th>Spent On Consoles</th>";}

            if ($table_header == "<tr>") {
                $table_header = "<th>customerID</th><th>First Name</th><th>Last Name</th><th>Phone Number</th>
                          <th>Email</th><th>Spent On Games</th><th>Spent On Consoles</th>";
            }

            $table_header .= "</tr>";
            
            echo "<br>Selected Customer Information:<br>";
            echo "<table>";
            echo " $table_header ";

            while ($row = OCI_Fetch_Array($result, OCI_BOTH + OCI_RETURN_NULLS)) {
                echo "<tr><td>" . $row[0] . "</td><td>" . $row[1] . "</td>" .
                     "<td>" . $row[2] . "</td><td>" . $row[3] . "</td>" .
                     "<td>" . $row[4] . "</td><td>" . $row[5] . "</td>" .
                     "<td>" . $row[6] . "</td></tr>"; //or just use "echo $row[0]"
            }

            echo "</table>";
        }

        function printConsolesBought($result) { //prints results from a select statement
            echo "<br>All Consoles Bought:<br>";
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

        function printCustomerSpending($result) {
            echo "<br>All Records of Customer Spending:<br>";
            echo "<table>";
            echo "<tr><th>Spent On Games</th><th>Spent On Consoles</th><th>Total Spent</th></tr>";

            while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
                echo "<tr><td>" . $row[0] . "</td><td>" . $row[1] . "</td>" .
                     "<td>" . $row[2] . "</td></tr>"; //or just use "echo $row[0]"
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

        function handleResetRequest() {
            global $db_conn;
            // Create new table
            echo "<br> To Reset, Manually Reenter 'START gamingbiz.sql' into SQLPlus<br>";
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

            $total_spent = $_POST['customerSpentGames'] + $_POST['customerSpentConsoles'];
            echo "<br> The total spent is: " . $total_spent . "<br>";
            if ($total_spent >= 1000) {
                $qualify_amount = 1000;
            } else if ($total_spent >= 500) {
                $qualify_amount = 500;
            } else if ($total_spent >= 250) {
                $qualify_amount = 250;
            } else {
                $qualify_amount = 10;
            }
            
            executePlainSQL("INSERT INTO CustomerSpending VALUES (" . $_POST['customerSpentGames'] . ",
                            " . $_POST['customerSpentConsoles'] . ", " . $qualify_amount . ")");

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

        function handleUpdateRequest() {
            global $db_conn;

            $customer_ID = $_POST['customerIDUpdate'];
            $new_email = $_POST['customerEmailUpdate'];
            
            executePlainSQL("UPDATE Customer SET email='" . $new_email . "' WHERE cid='" . $customer_ID . "'");
            OCICommit($db_conn);
        }

        function handleFindCustomerRequest() {
            global $db_conn;
            $select = "";

            if (isset($_GET['findCid'])) {$select .= ",cid";}
            if (isset($_GET['findFirstName'])) {$select .= ",firstName";}
            if (isset($_GET['findLastName'])) {$select .= ",lastName";}
            if (isset($_GET['findPhone'])) {$select .= ",phoneNumber";}
            if (isset($_GET['findEmail'])) {$select .= ",email";}
            if (isset($_GET['findSpentOnGames'])) {$select .= ",spentOnGames";}
            if (isset($_GET['findSpentOnConsoles'])) {$select .= ",spentOnConsoles";}

            $select = substr($select, 1);
            if ($select == "") {$select = "*";}
            
            $result = executePlainSQL("SELECT " . $select . " FROM Customer");
            printCustomerResults($result);
        }

        function handleSelectionRequest() {
            global $db_conn;

            $total_spent_games = $_GET['totalSpentGames'];
            if ($total_spent_games == "") {
                $total_spent_games = 0;
            }
            $total_spent_consoles = $_GET['totalSpentConsoles'];
            if ($total_spent_consoles == "") {
                $total_spent_consoles = 0;
            }

            $result = executePlainSQL("SELECT cid, firstName, lastName, spentOnGames, spentOnConsoles FROM Customer WHERE spentOnGames >'" . $total_spent_games . "' AND spentOnConsoles >'" . $total_spent_consoles . "'");
            printCustomerMoreThan($result);
        } 

        function printCustomerMoreThan($result) {
            echo "<br>Customers who have spent more than the specified amount:  <br>";
            echo "<table>";
            echo "<tr><th>Customer ID</th><th>First Name</th><th>Last Name</th><th>Spent On Games</th><th>Spent On Consoles</th></tr>";

            while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
                echo "<tr><td>" . $row[0] . "</td><td>" . $row[1] . "</td><td>" . $row[2] .
                     "</td><td>" . $row[3] . "</td><td>" . $row[4] . "</td></tr>"; 
            }

            echo "</table>";
        }

        function handleCustomerConsoles() {
            global $db_conn;

            $console_name = $_GET['consoleName'];

            $result = executePlainSQL("SELECT Customer.cid, firstName, lastName FROM Customer INNER JOIN ConsolesBought ON Customer.cid=ConsolesBought.cid WHERE ConsolesBought.consoleName='" . $console_name . "'");
            
            printCustomerConsoles($result);
        }

        function printCustomerConsoles($result) {
            echo "<br>Customer who have bought the queried console:  <br>";
            echo "<table>";
            echo "<tr><th>Customer ID</th><th>First Name</th><th>Last Name</th></tr>";

            while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
                echo "<tr><td>" . $row[0] . "</td><td>" . $row[1] . "</td><td>" . $row[2] .
                     "</td></tr>"; 
            }

            echo "</table>";
        }

        function handleAggregateRequest() {
            global $db_conn;

            $result = executePlainSQL("SELECT AVG(spentOnGames + spentOnConsoles) FROM Customer");
            printAverageSpent($result);
        }

        function printAverageSpent($result) {
            $row = OCI_Fetch_Array($result, OCI_BOTH);
            echo "<br>Average Customer Spending: $$row[0]<br>";
        }

        function handlePopularConsoles() {
            global $db_conn;

            $result = executePlainSQL("SELECT consoleName, COUNT(*) FROM ConsolesBought GROUP BY consoleName");

            printPopularConsoles($result);
        }

        function printPopularConsoles($result) {
            echo "<br>Retrieved popular consoles: <br>";
            echo "<table>";
            echo "<tr><th>Console</th><th>Number Sold</th></tr>";

            while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
                echo "<tr><td>" . $row[0] . "</td><td>" . $row[1] .
                     "</td></tr>"; 
            }

            echo "</table>";
        }

        function handleAllConsolesOwned() {
            global $db_conn;
            
            $result = executePlainSQL(
                "SELECT Customer.cid, firstName, lastName
                 FROM Customer
                 WHERE NOT EXISTS
                 ((SELECT ConsolesBought.consoleName FROM ConsolesBought) 
                   MINUS
                   (SELECT ConsolesBought.consoleName FROM ConsolesBought
                    WHERE Customer.cid=ConsolesBought.cid))");

            printAllConsolesOwned($result);
        }

        function printAllConsolesOwned($result) {
            echo "<br>Customer who have bought all consoles:  <br>";
            echo "<table>";
            echo "<tr><th>Customer ID</th><th>First Name</th><th>Last Name</th></tr>";

            while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
                echo "<tr><td>" . $row[0] . "</td><td>" . $row[1] . "</td><td>" . $row[2] .
                     "</td></tr>"; 
            }

            echo "</table>";
        }

        function handleDisplayRequest() {
            global $db_conn;

            $result = executePlainSQL("SELECT * FROM Customer");

            printCustomerResults($result);

            $result = executePlainSQL("SELECT * FROM ConsolesBought");

            printConsolesBought($result);

            $result = executePlainSQL("SELECT * FROM CustomerSpending");

            printCustomerSpending($result);
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
                } else if (array_key_exists('averageSpending', $_GET)) {
                    handleAggregateRequest();
                } else if (array_key_exists('popularConsoles', $_GET)) {
                    handlePopularConsoles();
                } else if (array_key_exists('customerConsoles', $_GET)) {
                    handleCustomerConsoles();
                } else if (array_key_exists('allConsolesOwned', $_GET)) {
                    handleAllConsolesOwned();
                } else if (array_key_exists('findCustomer', $_GET)) {
                    handleFindCustomerRequest();
                } else if (array_key_exists('customerSpent', $_GET)) {
                    handleSelectionRequest();
                }
                disconnectFromDB();
            }
        }

		if (isset($_POST['reset']) || isset($_POST['updateSubmit']) || isset($_POST['insertCustomer']) 
        || isset($_POST['removeCustomer']) || isset($_POST['updateCustomer'])) {
            handlePOSTRequest();
        } else if (isset($_GET['countTupleRequest']) || isset($_GET['displayCustomersRequest'])
        || isset($_GET['averageSpendingRequest']) || isset($_GET['popularConsolesRequest'])
        || isset($_GET['customerConsolesRequest']) || isset($_GET['allConsolesOwnedRequest'])
        || isset($_GET['findCustomerRequest']) || isset($_GET['customerSpentRequest'])) {
            handleGETRequest();
        }
		?>
	</body>
</html>
