<?php
ini_set("session.cookie_httponly", 1);

session_start();
if (!isset($_SESSION['username'])){
    echo json_encode(array(
		"success" => false,
		"message" => "you must be logged in"
    ));
    return;
}

//const data = { 'name': name, 'date': date, 'time': time, 'category': category, 'additional_users' : au, 'user_group' : ug };
header("Content-Type: application/json");
//Because you are posting the data via fetch(), php has to retrieve it elsewhere.
$json_str = file_get_contents('php://input');
//This will store the data into an associative array
$json_obj = json_decode($json_str, true);


$username =  htmlentities($_SESSION['username']);
$name = (string)  htmlentities($json_obj['name']);
$date = (string)  htmlentities($json_obj['date']);
$time = (string)  htmlentities($json_obj['time']);
$category = (string)  htmlentities($json_obj['category']);
$additional_users = (string)  htmlentities($json_obj['additional_users']);
$user_group = (string)  htmlentities($json_obj['user_group']);
$token = (string)  htmlentities($json_obj['token']);

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

$create_stmnt = $mysqli->prepare("INSERT INTO events (username, name, date, time, category, additonal_users, user_group) VALUES (?, ?, ?, ?, ?, ?, ?)");
if (!$create_stmnt) {
    $error = $mysqli->error;
    echo json_encode(array(
        "success" => false,
        "message" => strval($mysqli->error)
    ));
    exit;
}

//const data = { 'name': name, 'date': date, 'time': time, 'category': category, 'additional_users' : au, 'user_group' : ug };
$create_stmnt->bind_param('sssssss', $username, $name, $date, $time, $category, $additional_users, $user_group);
$create_stmnt->execute();
$create_stmnt->close();

$name = htmlentities($name);
$time = htmlentities($time);
$date = htmlentities($date);

echo json_encode(array(
    "success" => true,
    "name" =>  $name,
    "time" => $time,
    "date" => $date,
    "ug" => $_SESSION['ug']
));


?>