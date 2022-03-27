<?php
ini_set("session.cookie_httponly", 1);

session_start();
if (!isset($_SESSION['username'])){
    echo json_encode(array(
		"success" => false,
		"message" => "you must be logged in"
    ));
    exit;
}



//const data = { 'name': name, 'date': date, 'time': time, 'category': category, 'additional_users' : au, 'user_group' : ug };
header("Content-Type: application/json");
//Because you are posting the data via fetch(), php has to retrieve it elsewhere.
$json_str = file_get_contents('php://input');
//This will store the data into an associative array
$json_obj = json_decode($json_str, true);

$username =  htmlentities($_SESSION['username']);
$name = (string)  htmlentities($json_obj['name']);

$token = (string) $json_obj['token'];

if ($token != $_SESSION['token']){
    echo json_encode(array(
		"success" => false,
		"message" => "invalid CSRF token"
    ));
    return;
}

// connect to the DB and make sure it was successful additional_users
$mysqli = new mysqli('localhost', 'defaultuser', 'password', 'calendar');
if ($mysqli->connect_errno) {
	echo json_encode(array(
		"success" => false,
		"message" => "Connection Failed:\n"
    ));
    exit;
}

$delete_stmnt = $mysqli->prepare("DELETE FROM events WHERE name = (?)");
if (!$delete_stmnt) {
    $error = $mysqli->error;
    echo json_encode(array(
        "success" => false,
        "message" => strval($mysqli->error)
    ));
    exit;
}

//const data = { 'name': name, 'date': date, 'time': time, 'category': category, 'additional_users' : au, 'user_group' : ug };
$delete_stmnt->bind_param('s', $name);
$delete_stmnt->execute();
$delete_stmnt->close();

$name = htmlentities($name);

echo json_encode(array(
    "success" => true,
    "name" => $name 
));

?>