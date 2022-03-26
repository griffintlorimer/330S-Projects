<!-- this inserts the comment to the comments table then update the  comments field in the articles table for the corresponding article -->
<?php
session_start();
// make sure the user is logged in and didn't change the url to get to the comment form
if (!(isset($_SESSION['username'])) || !(isset($_POST["title"]))) {
    echo('You need to be logged in to comment on a story, <a href="homepage.php">Click here to return to the home page</a><br>');
    echo('You can login or create an account here <a href="login-page.php">Click here to return to the login page</a><br>');
    exit;
}

// check to make sure all the fields were filled out
if ($_POST["comment"] == null) {
    printf('You must fill in all fields, <a href="comment-form.html">Try Again</a><br>');
    exit;
}

$body = (string) $_POST["comment"];
$commenter = (string) $_SESSION['username'];
$title = (string) $_POST["title"];
$id = (int) $_POST["id"];

// connect to the DB and make sure it was successful
$mysqli = new mysqli('localhost', 'defaultuser', 'password', 'news_website');
if ($mysqli->connect_errno) {
    printf("Connection Failed: %s\n", $mysqli->connect_error);
    exit;
}

// insert the comment into the DB
$create_stmnt = $mysqli->prepare("insert into comments (title, body, commenter, article_id) values (?, ?, ?, ?)");
if (!$create_stmnt) {
    printf("Query Prep Failed: %s\n", $mysqli->error);
    exit;
}
$create_stmnt->bind_param('sssi', $title, $body, $commenter, $id);
$create_stmnt->execute();
$create_stmnt->close();

// update the num of comments
$title_stmnt = $mysqli->prepare("select comments from articles where id='" . $id . "'");
if (!$title_stmnt) {
    printf("Query prep Failed: %s\n", $mysqli->error);
    exit;
}

$title_stmnt->execute();
$result = $title_stmnt->get_result();

$row = $result->fetch_array(MYSQLI_NUM);
$num = $row[0];
printf($num);
$num = $num + 1;
printf($num);

$update_stmnt = $mysqli->prepare("update articles set comments =? where id=?");
if (!$update_stmnt) {
    printf("Query Prep Failed: %s\n", $mysqli->error);
    exit;
}

$update_stmnt->bind_param('ii', $num, $id);
$update_stmnt->execute();
$update_stmnt->close();

header("Location: homepage.php");

?>