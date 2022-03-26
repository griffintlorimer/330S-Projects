<?php
session_start();
// make sure the user is logged in
if (!hash_equals($_SESSION['token'], $_POST['token'])) {
    die('You need to be logged in to comment on a story, <a href="homepage.php">Click here to return to the home page</a><br>');
}
// storing the data into values
$username = $_SESSION["username"];
$id = (int) $_POST['title'];
$type = (string) $_POST['type'];

// connect to the DB and make sure it was successful
$mysqli = new mysqli('localhost', 'defaultuser', 'password', 'news_website');
if ($mysqli->connect_errno) {
    printf("Connection Failed: %s\n", $mysqli->connect_error);
    exit;
}

if ($type == "article") {
    $id = (int) $id;
    // update the article from the DB
    $comment_stmnt = $mysqli->prepare("DELETE FROM comments WHERE  article_id" . " = (?)");
    if (!$comment_stmnt) {
        printf("Query prep Failed: %s\n", $mysqli->error);
        exit;
    }
    $comment_stmnt->bind_param('i', $id);
    $comment_stmnt->execute();
    $comment_stmnt->close();

    $articles_stmnt = $mysqli->prepare("DELETE FROM articles WHERE  id" . " = (?)");
    $articles_stmnt->bind_param('i', $id);
    $articles_stmnt->execute();
    $articles_stmnt->close();

    header("Location: homepage.php");
}
else {
    $table = "comments";
    $cond = "id";
    $id = intval($id);
    // update the article from the DB
    $articles_stmnt = $mysqli->prepare("DELETE FROM " . $table . " WHERE " . $cond . " = (?)");
    if (!$articles_stmnt) {
        printf("Query prep Failed: %s\n", $mysqli->error);
        exit;
    }
    if ($type == "article") {
        $types = 's';
    }
    else {
        $types = 'i';
    }
    $articles_stmnt->bind_param($types, $id);
    $articles_stmnt->execute();
    $articles_stmnt->close();
    header("Location: homepage.php");
}
?>