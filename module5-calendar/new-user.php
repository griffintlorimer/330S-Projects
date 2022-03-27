<?php
ini_set("session.cookie_httponly", 1);

header("Content-Type: application/json");
//Because you are posting the data via fetch(), php has to retrieve it elsewhere.
$json_str = file_get_contents('php://input');
//This will store the data into an associative array
$json_obj = json_decode($json_str, true);

$username = (string) htmlentities($json_obj["newuser"]);
$password = (string) htmlentities($json_obj["password"]);
$ug = (string) htmlentities($json_obj["user_group"]);

if ($ug == ''){
    $ug = 'NULL';
}

// validate the username and password
if (!preg_match('/^[\w_\-]+$/', $username)) {
    echo json_encode(array(
		"success" => false,
		"message" => "Invalid Username"
	));
	exit;
}

// validates the length of the username and password
// username : [4,32]
// password : [4,32]
if (strlen($username) < 4 || strlen($username) > 32) {
    echo json_encode(array(
		"success" => false,
		"message" => "Invalid Username"
	));
	exit;
}
if (strlen($password) < 4 || strlen($password) > 32) {
    echo json_encode(array(
		"success" => false,
		"message" => "Invalid Password"
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

// check to make sure the username does not already exist
$users_stmnt = $mysqli->prepare("select username from users");
if (!$users_stmnt) {
    echo json_encode(array(
		"success" => false,
		"message" => "Connection Failed:\n"
    ));
    exit;
}
$users_stmnt->execute();
// https://www.php.net/manual/en/mysqli-result.fetch-array.php
$result = $users_stmnt->get_result();
while ($row = $result->fetch_array(MYSQLI_NUM)) {
    foreach ($row as $u) {
        if (strcmp($username, $u) === 0) {
            echo json_encode(array(
                "success" => false,
                "message" => "Username already exists"
            ));
            exit;
        }
    }
}


// now we are ready to create the user
$hashed_password = password_hash($password, PASSWORD_BCRYPT);
$create_stmnt = $mysqli->prepare("INSERT INTO users (username, password, user_group) VALUES (?, ?, ?)");

if (!$create_stmnt) {
    echo json_encode(array(
        "success" => false,
        "message" => "Query Prep failed"
    ));
    exit;
}

$create_stmnt->bind_param('sss', $username, $hashed_password, $ug);
print_r($create_stmnt);
$create_stmnt->execute();
$create_stmnt->close();

// start the session
session_start();
// session_destroy();
// session_start();

$_SESSION["username"] = $username;
$_SESSION['token'] = bin2hex(openssl_random_pseudo_bytes(32));
$_SESSION['ug'] = $ug;
// header("Location: index.html");
echo json_encode(array(
    "success" => true,
    "message" => 'success'
));
exit;
?>