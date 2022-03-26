<?php
session_start();
// make sure the user is logged in
if (!(isset($_SESSION['username']))) {
    echo('You need to be logged in to comment / like on a story, <a href="homepage.php">Click here to return to the home page</a><br>');
    echo('You can login or create an account here <a href="login-page.php">Click here to return to the login page</a><br>');
    exit;
}

$username = (string) $_SESSION["username"];
$id = (string)  $_POST['id'];

// connect to the DB and make sure it was successful
$mysqli = new mysqli('localhost', 'defaultuser', 'password', 'news_website');
if ($mysqli->connect_errno) {
    printf("Connection Failed: %s\n", $mysqli->connect_error);
    exit;
}

// update the article from the DB
$users_stmnt = $mysqli->prepare("SELECT liked_posts from users where username = ?");
if (!$users_stmnt) {
    printf("Query prep Failed: %s\n", $mysqli->error);
    exit;
}
$users_stmnt->bind_param('s', $username);
$users_stmnt->execute();
$result = $users_stmnt->get_result();
$liked_posts = $result->fetch_array(MYSQLI_NUM)[0];
$users_stmnt->close();

// check if the post is already in the liked posts
// https://www.tutorialrepublic.com/faq/how-to-check-if-a-string-contains-a-specific-word-in-php.php#:~:text=You%20can%20use%20the%20PHP,at%200%2C%20and%20not%201.
if (strpos($liked_posts, $id) !== false) {
    header("Location: homepage.php");
    exit;
}


// add the liked post to the list of liked articles then update the num of likes on the article
$liked_posts = $liked_posts . " " . strval($id);
$update_stmnt = $mysqli->prepare('UPDATE users SET liked_posts=(?) WHERE username=(?)');
if (!$update_stmnt) {
    printf("Query prep Failed: %s\n", $mysqli->error);
    exit;
}
$update_stmnt->bind_param('ss', $liked_posts, $username);
$update_stmnt->execute();
$update_stmnt->close();

// increment the liked posts
// add the liked post to the list of liked articles then update the num of likes on the article
$update_likes_stmnt = $mysqli->prepare('UPDATE articles SET likes=likes+1 WHERE id=(?)');
if (!$update_stmnt) {
    printf("Query prep Failed: %s\n", $mysqli->error);
    exit;
}
$update_likes_stmnt->bind_param('s', $id);
$update_likes_stmnt->execute();
$update_likes_stmnt->close();

// header("Location: homepage.php");
?>