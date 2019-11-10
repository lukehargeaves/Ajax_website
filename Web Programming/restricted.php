<!--This page returns the user back to the original page if the login was a success or produces a message
telling the user why the login failed.-->

<?php
require_once("function.php");
ini_set("session.save_path", "/home/unn_w17004274/sessionData");
session_start();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Restricted</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
<?php
/*Validates the login is not empty for both username and password.*/
if (isset($_SESSION['username'])) {
    $username = $_SESSION['username'];

    if (empty($_SESSION['username'])) {
        echo "You have not entered your username";
    }


    if (isset($_SESSION['password'])) {
        $password = $_SESSION['password'];

        if (empty($_SESSION['password'])) {
            echo "You have not entered your password";
        }
    }
    /*Sets the login status to true. This is used on all of the restricted pages to make sure the user is logged in.*/
    if (!empty($_SESSION['username']) && !empty($_SESSION['password'])) {
        $_SESSION ['logged-in'] = true;
        pageReturn();;

    }


}
?>
</body>
</html>