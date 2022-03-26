<!-- When a user tries to login this script validates them -->
<?php
session_start();
// check to make sure the username / password were filled out
if (!(isset($_POST["username"])) || !(isset($_POST["password"]))) {
	printf("Please fill in the username and password fields.\n");
	printf('<a href="login-page.php">Click here to return to the login page</a><br>');
	exit;
}

$username = (string) $_POST["username"];
$password = (string) $_POST["password"];

// validate the username and password
if (!preg_match('/^[\w_\-]+$/', $username)) {
	printf('Invalid username, <a href="login-page.php">Click here to return to the login page</a><br>');
	exit;
}

// connect to the DB and make sure it was successful
$mysqli = new mysqli('localhost', 'defaultuser', 'password', 'news_website');
if ($mysqli->connect_errno) {
	printf("Connection Failed: %s\n", $mysqli->connect_error);
	exit;
}

// code referenced from https://classes.engineering.wustl.edu/cse330/index.php?title=PHP_and_MySQL 
// Use a prepared statement
$stmt = $mysqli->prepare('SELECT COUNT(*), username, password FROM users WHERE username=(?)');

// Bind the parameter
$stmt->bind_param('s', $username);
$stmt->execute();

// Bind the results
$stmt->bind_result($cnt, $user_id, $pwd_hash);
$stmt->fetch();

$pwd_guess = $_POST['password'];
// Compare the submitted password to the actual password hash

if ($cnt == 1 && password_verify($pwd_guess, $pwd_hash)) {
	// Login succeeded!
	$_SESSION['username'] = $user_id;
	$_SESSION['token'] = bin2hex(openssl_random_pseudo_bytes(32));
	// Redirect to your target page
	header("Location: homepage.php");
}
else {
	// Login failed; redirect back to the login screen
	printf('Login failed, invalid username or password <a href="login-page.php">Click here to return to the login page</a><br>');
}
?>