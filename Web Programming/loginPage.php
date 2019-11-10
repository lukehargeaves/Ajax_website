<!--This page verifies if the login information is correct for the user and directs them onto the correct page.-->
<?php
require_once("function.php");
ini_set("session.save_path", "/home/unn_w17004274/sessionData");
session_start();
/*This creates the session for the previous page so the user can be returned back to their previous page after
logging in.*/
if(!isset($_SESSION['org_referer']))
{
    $_SESSION['org_referer'] = $_SERVER['HTTP_REFERER'];
}
$var = $_SESSION['org_referer'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login script</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
<?php
/*Returns the username and password from the login form.*/
$username = filter_has_var(INPUT_POST, 'username') ? $_POST['username'] : null;

$password = filter_has_var(INPUT_POST, 'password') ? $_POST['password'] : null;

/*Trims the login down to validate.*/
$username = trim($username);
ifEmpty($username, 'User Name');
$password = trim($password);
ifEmpty($password,'Password');


/*Validates the login to make sure it matches the one in the database.*/
try {

    $user = selectUsername("$username");

    if ($user) {
        $passwordHash = $user->passwordHash;

        if (password_verify($password, $passwordHash)) {
            echo 'Password is valid!';
            $_SESSION['username'] = $username;
            $_SESSION['password'] = $password;
            echo header('Location:restricted.php');
        } else {

            echo "<p>password or username missmatch</p>";
            echo "<a href='$var'>Restricted page</a>\n";

        }
    } else {

        echo "<p>password or username missmatch</p>";
        echo "<a href='$var'>Restricted page</a>\n";

    }
}
/*Catches the error and returns the error message.*/
catch (Exception $e) {
    echo "<p>There was a problem:</p> " . $e->getMessage();
}

?>

</body>
</html>
