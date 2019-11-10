<!--This page updates the record by taking the data input into recordReader.php form and validating it before entering
it into the database. -->
<!--Creates the session.-->
<?php
require_once("function.php");
try {
    startSession();
} catch (Exception $e) {
    echo "<p>Failed to start session: " . $e->getMessage() . "</p>\n";
}
/*Checks to make sure the user is logged in.*/
if (isset($_SESSION['logged-in']) == true) {
echo "Logged in";
echo logoutButton();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Record Update</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
<?php
$errors = array();
/*Retrieves recordID and validates the input*/
$recordID = filter_has_var(INPUT_GET, 'recordID')
    ? $_GET['recordID'] : null;

/*Retrieves recordTitle and validates the input*/
$recordTitle = filter_has_var(INPUT_GET, 'recordTitle')
    ? $_GET['recordTitle'] : null;
$recordTitle = valRT($recordTitle);

/*Retrieves recordYear and validates the input*/
$recordYear = filter_has_var(INPUT_GET, 'recordYear')
    ? $_GET['recordYear'] : null;
$recordYear = valRY($recordYear);

/*Retrieves publisher name and validates the input*/
$pubID = filter_has_var(INPUT_GET, 'pubiD')
    ? $_GET['pubiD'] : null;
ifEmpty($pubID, 'PubID');

/*Retrieves category description and validates the input*/
$catID = filter_has_var(INPUT_GET, 'catiD')
    ? $_GET['catiD'] : null;
ifEmpty($catID, 'CatID');

/*Retrieves recordPrice and validates the input*/
$recordPrice = filter_has_var(INPUT_GET, 'recordPrice')
    ? $_GET['recordPrice'] : null;
$recordPrice = valRP($recordPrice);


/*Creates the database connection and retrieves the data from the database.*/
try {


    $dbConn = getConnection();

    $updateSQL = "UPDATE nmc_records
                  SET  recordYear = $recordYear, pubID = '$pubID', 
                  catID = '$catID', recordPrice = $recordPrice, recordTitle = '$recordTitle'
                  WHERE recordID = $recordID";
    $dbConn->exec($updateSQL);
    echo "<p>record updated</p>\n";
    echo "<p>Movie updated</p>\n";

    echo returnToRecordButton();

} catch (Exception $e) {

    echo "<p>record not updated </p>\n";
    echo returnButton();
    log_error($e);

}
}
/*Checks to make sure the user is logged in.*/
else {
    echo "Error! Please log in to view the content on this page";
    echo createLoginForm();
}
?>
</body>
</html>