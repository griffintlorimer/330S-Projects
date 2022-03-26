<?php
session_start();
// make sure the user is logged in
if (!hash_equals($_SESSION['token'], $_POST['token'])) {
    die('You need to be logged in to comment on a story, <a href="homepage.php">Click here to return to the home page</a><br>');
}

$username = (string) $_SESSION["username"];
$body = (string) $_POST['body'];
$id = (string) $_POST['id'];

echo($id . $body);

// connect to the DB and make sure it was successful
$mysqli = new mysqli('localhost', 'defaultuser', 'password', 'news_website');
if ($mysqli->connect_errno) {
    printf("Connection Failed: %s\n", $mysqli->connect_error);
    exit;
}

// update the article from the DB
$articles_stmnt = $mysqli->prepare("
    UPDATE comments
    SET body = (?)
    WHERE id = '" . $id . "'");
if (!$articles_stmnt) {
    printf("Query prep Failed: %s\n", $mysqli->error);
    exit;
}

$articles_stmnt->bind_param('s', $body);
$articles_stmnt->execute();
$articles_stmnt->close();
header("Location: homepage.php");




?>