<!--This is a functions file that contains all the php functions used within this assignment. -->
<?php

/*Function to establish the database connection. If the connection isn't able to be established a connection error will
be thrown.*/
function getConnection()
{

    try {
        $connection = new PDO("mysql:host=localhost;dbname=unn_w17004274",
            "unn_w17004274", "Porsche21");
        $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $connection;
    } catch (Exception $e) {
        /*The error is logged to a file*/
        throw new Exception("Connection error " . $e->getMessage(), 0, $e);
        log_error($e);
    }

}

/*This is a function to establish to create the login form for the user to login and use their session needed to access
the restricted parts of the website. This function references the loginPage.php to validate the login information entered
into the form.*/
function createLoginForm()
{
    $formLogin = <<<FORMLOGIN
    <form method="post" action="loginPage.php">
        Username <input type="text" name="username" />
    Password <input type="password" name="password" />
    <input type="submit" value="Logon" />
    </form>
FORMLOGIN;
    $formLogin .= "\n";
    return $formLogin;

}

/*This function create the logout button to log the user out of the website and end their session. This function references
the destroySession.php file which ends the current running session.*/
function logoutButton()
{
    $buttonLogout = <<<BUTTONLOGOUT
   <a href="destroySession.php">
   <button>Logout</button>
</a>
BUTTONLOGOUT;
    $buttonLogout .= "\n";
    return $buttonLogout;

}

/*This function creates a button to return to the recordToEditSelect.php page.*/
function returnToRecordButton()
{

    $buttonReturnRecord = <<<BUTTONRETURNRECORD
   <a href="recordToEditSelect.php">
   <button>Return To Record Select</button>
</a>
BUTTONRETURNRECORD;
    $buttonReturnRecord .= "\n";
    return $buttonReturnRecord;

}

/*This function creates a button that returns back to the previous page that the user was on.*/
function returnButton()
{
    try {
        ini_set("session.save_path", "/home/unn_w17004274/sessionData");

        if (!isset($_SESSION['org_referer'])) {
            $_SESSION['org_referer'] = $_SERVER['HTTP_REFERER'];
        }
        $tar = $_SESSION['org_referer'];

        $buttonReturn = <<<BUTTONRETURN
   <a href="$tar">
   <button>Return</button>
</a>
BUTTONRETURN;
        $buttonReturn .= "\n";
        return $buttonReturn;
    } catch (Exception $e) {
        /*The error is logged to a file*/
        throw new Exception("Redirect Error " . $e->getMessage(), $e);
        log_error($e);
    }
}

/*This function returns the user back to the previous page they where on when the function is called.*/
function pageReturn()
{
    try {
        ini_set("session.save_path", "/home/unn_w17004274/sessionData");
        if (!isset($_SESSION['org_referer'])) {
            $_SESSION['org_referer'] = $_SERVER['HTTP_REFERER'];
        }
        $tar = $_SESSION['org_referer'];
        header('Location: ' . $tar);
    } catch (Exception $e) {
        /*The error is logged to a file*/
        throw new Exception("Redirect Error " . $e->getMessage(), $e);
        log_error($e);
    }
}

/*This function checks to make sure the username and password entered match those entered into the database. This function
validates the login.*/
function selectUsername($username)
{
    try {
        $dbConn = getConnection();
        $querySQL = "SELECT passwordHash FROM nmc_users
                 WHERE username = :username";
        $stmt = $dbConn->prepare($querySQL);

        $stmt->execute(array(':username' => $username));
        $user = $stmt->fetchObject();
        return $user;
    } catch (Exception $e) {
        /*The error is logged to a file*/
        throw new Exception("SQL retrieval error " . $e->getMessage(), 0, $e);
        log_error($e);
    }

}

/*This function returns the required fields to populate the records. It works through the DB connection and then
fetching the data using an SQL query.*/
function populateRecords()
{
    try {
        $dbConn = getConnection();

        $sqlQuery = "SELECT recordID, recordTitle, recordYear, catDesc, recordPrice,nmc_publisher.pubID
                  From nmc_records
                  INNER JOIN nmc_publisher
                  ON nmc_publisher.pubID = nmc_records.pubID
                  INNER JOIN nmc_category
                  ON nmc_category.catID  = nmc_records.catID
                  ORDER BY recordID";
        $queryResult = $dbConn->query($sqlQuery);
        return $queryResult;
    } catch (Exception $e) {
        /*The error is logged to a file*/
        throw new Exception("SQL retrieval error " . $e->getMessage(), 0, $e);
        log_error($e);
    }

}

/*This function starts a session.*/
function startSession()
{

    ini_set("session.save_path", "/home/unn_w17004274/sessionData");
    session_start();

}

/*This function uses an SQL query and returns the required information from the database and passes it back.*/
function query1($recordID)
{
    try {
        $dbConn = getConnection();
        $sqlQuery = "SELECT recordID, recordTitle, recordYear, pubName,nmc_publisher.pubID, nmc_category.catID,catDesc, recordPrice
                     From nmc_records
                     INNER JOIN nmc_publisher
                     ON nmc_publisher.pubID = nmc_records.pubID
                     INNER JOIN nmc_category
                     ON nmc_category.catID  = nmc_records.catID
                     WHERE recordID = $recordID";
        $queryResult = $dbConn->query($sqlQuery);
        $rowObj = $queryResult->fetchObject();
        return $rowObj;
    } catch (Exception $e) {
        /*The error is logged to a file*/
        throw new Exception("SQL retrieval error " . $e->getMessage(), 0, $e);
        log_error($e);
    }
}

/*This function validates to see if the input is empty. If so it returns a messgae using the input.*/
function ifEmpty($input1, $text)
{

    if (empty($input1)) {
        echo "<p>You have not entered your $text</p>\n";

    }
}

/*This function gets the error message and writes it to the logErrorFile along with the date and time and then closes
the write.*/
function log_error($e)
{

    $fileHandle = fopen("error_log_file.log", "ab");
    $errorDate = date('D M j G:i:s T Y');
    $errorMessage = $e->getMessage();

    fwrite($fileHandle, "$errorDate|$errorMessage" . PHP_EOL);
    fclose($fileHandle);
}

/*This function validates the recordTitle input.*/
function valRT($recordTitle)
{

    $recordTitle = trim($recordTitle);
    $recordTitle = filter_var($recordTitle, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
    ifEmpty($recordTitle, 'RecordTitle');
    return $recordTitle;
}

/*This function validates the recordYear input.*/
function valRY($recordYear)
{

    $recordYear = trim($recordYear);
    $recordYear = filter_var($recordYear, FILTER_SANITIZE_NUMBER_INT);
    ifEmpty($recordYear, 'Record Year');
    return $recordYear;
}

/*This function validates the recordPrice input*/
function valRP($recordPrice)
{
    $recordPrice = trim($recordPrice);
    $recordPrice = filter_var($recordPrice, FILTER_SANITIZE_NUMBER_FLOAT);
    ifEmpty($recordPrice, 'Record Price');
    return $recordPrice;
}

?>

