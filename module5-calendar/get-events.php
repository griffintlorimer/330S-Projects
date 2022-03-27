<?php
ini_set("session.cookie_httponly", 1);

session_start();
if (!isset($_SESSION['username'])) {
    echo json_encode(array(
        "success" => false,
        "message" => "not logged in"
    ));
    exit;
}

$username = (string) htmlentities($_SESSION['username']);
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
$users_stmnt = $mysqli->prepare("select * from events");
if (!$users_stmnt) {
    echo json_encode(array(
        "success" => false,
        "message" => "Connection Failed:\n"
    ));
    exit;
}
$users_stmnt->execute();

$result = $users_stmnt->get_result();

$arr = array();
while ($row = $result->fetch_array(MYSQLI_NUM)) 
{
    if ($row[1] == $username) {
        array_push($arr, $row);
    }
    else if ($row[6] != '') {
        $au_string = (string)$row[6];
        $aus = explode(" ", $au_string);
        if (in_array($username, $aus)) {
            array_push($arr, $row);
        }
    }

    if ($row[7] == $_SESSION['ug'] && $row[7] != 'NULL') {
        array_push($arr, $row);
    }

}

echo json_encode(array(
    "success" => true,
    "message" => $arr
));

?>