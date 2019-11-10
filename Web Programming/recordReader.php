<!--This page creates a form allowing the logged on user to make amendments to the record being stored in the database.
The form fields will load pre-loaded with the orginal input. The user is not allowed to change the recordID however.
To access this page the user must be logged in and have a session pre-loaded. The fields will be loaded from the
recordToEditSelect.php page.-->
<?php
require_once("function.php");
/*Creates a session for the user */
try {
    startSession();
} catch (Exception $e) {
    echo "<p>Failed to start session: " . $e->getMessage() . "</p>\n";
}
/*Checks to see if the user is logged in to allow them to view the page*/
if (isset($_SESSION['logged-in']) == true) {
echo "Logged in";
echo logoutButton();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Record Reader</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
<?php


$recordID = filter_has_var(INPUT_GET, 'recordID') ? $_GET['recordID'] : null;

/*Checks to see if a record has been passed over to the form.*/
if (empty($recordID)) {
    echo "<p>Please select a record to update<a href='recordToEditSelect.php'></a>\n";
} else {

}
try {

    $rowObj = query1("$recordID");

    /*Creates the form and populates it with the information stored against that record on the database.*/
    echo "<h1>Update Record'{$rowObj->recordTitle}'</h1>
            <form id='UpdateRecord' action='recordUpdate.php' method='get'>
            <p>RecordID <input type='text' name='recordID' size='50' value='{$rowObj->recordID}'  style=\"width:50%;margin-left: 50%;\"readonly  /></p>
            <p>Record Title <input type='text' name='recordTitle' size ='50' value='{$rowObj->recordTitle}'  style=\"width:50%;margin-left: 50%;\" /></p>
			<p>Year <input type='text' name='recordYear' size='50' value='{$rowObj->recordYear}'  style=\"width:50%;margin-left: 50%;\" /></p>
			<p>Record Price <input type='text' name='recordPrice' value='{$rowObj->recordPrice}'  style=\"width:50%;margin-left: 50%;\" /></p></div>";

    /*Publisher drop down query.*/
    $dbConn = getConnection();
    $id = "{$rowObj->pubName}";
    $sql = "SELECT nmc_publisher.pubID, pubName FROM nmc_publisher where pubName != '$id'";
    $query = $dbConn->Query($sql);

    ?>
    <!--Creates a dynamic drop down with the record Publisher Name pre-selected-->
    <div id="publisher">
        <p>Publisher Name:
            <select name='pubiD' style="width:50%;margin-left: 50%;">

                <?php

                echo "<option value = {$rowObj->pubID}>{$rowObj->pubName}</option>\n";
                while ($test = $query->fetchObject()) {
                    echo "<option value={$test->pubID}>{$test->pubName} </option>\n";
                }

                ?>

            </select>
        </p>
    </div>
    <!--Category description drop down query.-->
    <?php
    $dbConn = getConnection();
    $name = "{$rowObj->catDesc}";
    $sql = "SELECT catDesc, nmc_category.catID FROM nmc_category where catDesc != '$name'";
    $query = $dbConn->Query($sql);
    ?>
    <!--Creates a dynamic drop down with the category description pre-selected.-->
    <div id="category">
        <p>Category Description:
            <select name="catiD" style="width:50%;margin-left: 50%;">
                Category Description:
                <?php
                echo "<option value={$rowObj->catID}>{$rowObj->catDesc}</option>\n";
                while ($test = $query->fetchObject()) {
                    echo "<option value={$test->catID}>{$test->catDesc}</option>\n";
                }

                ?>
            </select>
        </p>
    </div>


    <input type='submit' name='submit' value='Update Record' style="width:10%;margin-left: 20px;"">
    </form>


    <?php
    echo returnToRecordButton();
} catch (Exception $e) {
    echo "<p>Record details not found: " . $e->getMessage() . "</p>\n";
    log_error($e);
}
}
/*If the user is not logged in this prompts them to login in using the loginForm.*/
else {
    echo "<p>Error! Please log in to view the content on this page</p>";
    echo createLoginForm();
}

?>
</body>
</html>