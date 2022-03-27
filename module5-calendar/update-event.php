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

$oldname = htmlentities($json_obj['oldname']);
$username = htmlentities($_SESSION['username']);
$name = (string) htmlentities($json_obj['name']);
$date = (string) htmlentities($json_obj['date']);
$time = (string) htmlentities($json_obj['time']);
$category = (string) htmlentities($json_obj['category']);
$additional_users = (string) htmlentities($json_obj['additional_users']);
$user_group = (string) htmlentities($json_obj['user_group']);

$token = (string) $json_obj['token'];

if ($token != $_SESSION['token']){
    echo json_encode(array(
		"success" => false,
		"message" => "invalid CSRF token"
    ));
    return;
}

if ($category == '') {
    $category = 'NULL';
}
if ($additional_users == '') {
    $additional_users = 'NULL';
}
if ($user_group == '') {
    $user_group = 'NULL';
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

$get_stmnt = $mysqli->prepare("select * from events where username = (?) AND name = (?)");
if (!$get_stmnt) {
    $error = $mysqli->error;
    echo json_encode(array(
        "success" => false,
        "message" => strval($mysqli->error)
    ));
    exit;
}

//const data = { 'name': name, 'date': date, 'time': time, 'category': category, 'additional_users' : au, 'user_group' : ug };
$get_stmnt->bind_param('ss', $username, $oldname);
$get_stmnt->execute();
$result = $get_stmnt->get_result();
$row = $result->fetch_array(MYSQLI_NUM);
$get_stmnt->close();

$id = $row[0];
// if ($name == '') $name = $row[2];
if ($date == '') $date = $row[3];
if ($time == '') $time = $row[4];
if ($category == '') $category = $row[5];
if ($additional_users == '') $category = $row[6];
if ($user_group == '') $user_group = $row[7];

$create_stmnt = $mysqli->prepare("UPDATE events SET username = (?), name = (?),  date = (?), time = (?), category = (?), additonal_users = (?), user_group = (?) where id = (?)");
if (!$create_stmnt) {
    $error = $mysqli->error;
    echo json_encode(array(
        "success" => false,
        "message" => strval($mysqli->error)
    ));
    exit;
}

//const data = { 'name': name, 'date': date, 'time': time, 'category': category, 'additional_users' : au, 'user_group' : ug };
$create_stmnt->bind_param('sssssssi', $username, $name, $date, $time, $category, $additional_users, $user_group, $id);
$create_stmnt->execute();
$create_stmnt->close();

$name = htmlentities($name);
$date = htmlentities($date);
$time = htmlentities($time);

echo json_encode(array(
    "success" => true,
    "name" => $name,
    "date" => $date,
    "time" => $time
));

?>