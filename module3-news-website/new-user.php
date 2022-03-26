<!-- This script is used for creating new users -->

<?php
session_start();

// check to make sure the username / password were filled out
if (!(isset($_POST["newuser"])) || !(isset($_POST["password"]))) {
    printf("Please fill in the username and password fields.\n");
    printf('<a href="login-page.php">Click here to return to the login page</a><br>');
    exit;
}
$username = (string) $_POST["newuser"];
$password = (string) $_POST["password"];
$password_check = (string) $_POST["password-check"];

if ($password != $password_check) {
    printf("Passwords don't match .\n");
    printf('<a href="login-page.php">Click here to return to the login page</a><br>');
    exit;
}

// validate the username and password
if (!preg_match('/^[\w_\-]+$/', $username)) {
    printf('Invalid username, <a href="login-page.php">Click here to return to the login page</a><br>');
    exit;
}

// validates the length of the username and password
// username : [4,32]
// password : [4,32]
if (strlen($username) < 4 || strlen($username) > 32) {
    printf('Invalid username length, <a href="login-page.php">Click here to return to the login page</a><br>');
    exit;
}
if (strlen($password) < 4 || strlen($password) > 32) {
    printf('Invalid password length, <a href="login-page.php">Click here to return to the login page</a><br>');
    exit;
}


// connect to the DB and make sure it was successful
$mysqli = new mysqli('localhost', 'defaultuser', 'password', 'news_website');
if ($mysqli->connect_errno) {
    printf("Connection Failed: %s\n", $mysqli->connect_error);
    exit;
}

// check to make sure the username does not already exist
$users_stmnt = $mysqli->prepare("select username from users");
if (!$users_stmnt) {
    printf("Query prep Failed: %s\n", $mysqli->error);
    exit;
}
$users_stmnt->execute();
// https://www.php.net/manual/en/mysqli-result.fetch-array.php
$result = $users_stmnt->get_result();
while ($row = $result->fetch_array(MYSQLI_NUM)) {
    foreach ($row as $u) {
        if (strcmp($username, $u) === 0) {
            printf('Username already exists, <a href="login-page.php">Click here to return to the login page</a><br>');
            exit;
        }
    }
}


// now we are ready to create the user
$hashed_password = password_hash($password, PASSWORD_BCRYPT);
$create_stmnt = $mysqli->prepare("insert into users (username, password) values (?, ?)");

if (!$create_stmnt) {
    printf("Query Prep Failed: %s\n", $mysqli->error);
    exit;
}

$create_stmnt->bind_param('ss', $username, $hashed_password);
$create_stmnt->execute();
$create_stmnt->close();

// start the session
$_SESSION["username"] = $username;
$_SESSION['token'] = bin2hex(openssl_random_pseudo_bytes(32));
echo($_SESSION["username"]);
header("Location: homepage.php");

?>