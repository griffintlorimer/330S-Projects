<?php
ini_set("session.cookie_httponly", 1);

header("Content-Type: application/json");
//Because you are posting the data via fetch(), php has to retrieve it elsewhere.
$json_str = file_get_contents('php://input');
//This will store the data into an associative array
$json_obj = json_decode($json_str, true);

$username = (string) htmlentities($json_obj["username"]);
$password = (string) htmlentities($json_obj["password"]);

// validate the username and password
if (!preg_match('/^[\w_\-]+$/', $username)) {
	echo json_encode(array(
		"success" => false,
		"message" => $username
	));
	exit;
}

// connect to the DB and make sure it was successful
$mysqli = new mysqli('localhost', 'defaultuser', 'password', 'calendar');
if ($mysqli->connect_errno) {
	echo json_encode(array(
		"success" => false,
		"message" => "Connection Failed:\n"
    ));
    exit;
}

// code referenced from https://classes.engineering.wustl.edu/cse330/index.php?title=PHP_and_MySQL 
// Use a prepared statement
$stmt = $mysqli->prepare('SELECT COUNT(*), username, password, user_group FROM users WHERE username=(?)');

// Bind the parameter
$stmt->bind_param('s', $username);
$stmt->execute();

// Bind the results
$stmt->bind_result($cnt, $user_id, $pwd_hash, $ug);
$stmt->fetch();


// $pwd_guess = $password;
// Compare the submitted password to the actual password hash

if ($cnt == 1 && password_verify($password, $pwd_hash)) {
	session_start();
	// Login succeeded!
	$_SESSION["username"] = $username;
	$_SESSION['token'] = bin2hex(openssl_random_pseudo_bytes(32));
	$_SESSION['ug'] = $ug;
	echo json_encode(array(
		"success" => true,
		"message" => "success"
	));
	exit;

}
else {
	// Login failed; redirect back to the login screen
    echo json_encode(array(
        "success" => false,
        "message" => 'fail'
    ));
    exit;
}

?>