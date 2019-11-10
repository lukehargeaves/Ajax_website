<!--This page creates a table of all the records in the bale for the user to select to edit.-->
<!--This creates the session and checks to make sure the user is logged in to view the contents.-->
<?php
require_once("function.php");
try {
    startSession();
} catch (Exception $e) {
    echo "<p>Failed to start session: " . $e->getMessage() . "</p>\n";
}
if (isset($_SESSION['logged-in']) == true) {
echo "Logged in";
echo logoutButton();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Record to edit select</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
<h1>Select record to edit</h1>
<?php
/*This connects to the database and runs the SQL query to populate the the table.*/
try {
    require_once("function.php");
    $dbConn = getConnection();

    $sqlQuery = "SELECT recordID, recordTitle, recordYear, catDesc, recordPrice
                  From nmc_records
                  INNER JOIN nmc_publisher
                  ON nmc_publisher.pubID = nmc_records.pubID
                  INNER JOIN nmc_category
                  ON nmc_category.catID  = nmc_records.catID
                  ORDER BY recordTitle";
    $queryResult = $dbConn->query($sqlQuery);

/*Here the results are used to populate the header and the rows in the table with the records from the
database.*/
    echo "<table>
          <tr>
		    <th>Title</th><th>Record Year</th><th>Category Description</th><th>Record Price</th>
		  </tr> ";
    while ($rowObj = $queryResult->fetchObject()) {
        echo "<div class='recordSelect'>\n
                <tr>
				   <td><a href='recordReader.php?recordID={$rowObj->recordID}'>{$rowObj->recordTitle}</td>
				   <td>{$rowObj->recordYear}</td>
				   <td>{$rowObj->catDesc}</td>
				   <td>{$rowObj->recordPrice}</td>
				</tr>
              </div>\n";
    }
    echo "</table>";
} catch (Exception $e) {
    echo "<p>Query failed: " . $e->getMessage() . "</p>\n";
}
}

/*This is called if the user isn't logged in and makes them log in to view the content on the page.*/
else {
    echo "<p>Error! Please log in to view the content on this page</p>";
    echo createLoginForm();
}

?>
</body>
</html>
