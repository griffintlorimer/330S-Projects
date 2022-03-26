<!-- This inserts the articles into the articles table -->
<!-- It first checks the DB to make sure the article title trying to be created doesn't already exist -->
<?php
session_start();

// double check that the user didn't change the url without being logged in
if (!hash_equals($_SESSION['token'], $_POST['token'])) {
    printf('You must be logged in, <a href="login-page.php">Click here to return to the login page</a><br>');
    exit;
}

// check to make sure all the fields were filled out
if ($_POST["title"] == null || $_POST["link"] == null || $_POST["body"] == null) {
    printf('You must fill in all fields, <a href="submit-story.php">Try Again</a><br>');
    exit;
}

$title = (string) $_POST['title'];
$link = (string) $_POST['link'];
$body = (string) $_POST['body'];
$username = (string) $_SESSION['username'];

// https://www.w3docs.com/snippets/php/url-validation.html
if (!preg_match("/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i", $link)) {
    echo('Invalid link, <a href="submit-story.php">Try Again</a><br> ');
    exit;
}

// check if the article title already exists in the DB
// connect to the DB and make sure it was successful
$mysqli = new mysqli('localhost', 'defaultuser', 'password', 'news_website');
if ($mysqli->connect_errno) {
    printf("Connection Failed: %s\n", $mysqli->connect_error);
    exit;
}
// check to make sure the username does not already exist
$articles_stmnt = $mysqli->prepare("select title from articles");
if (!$articles_stmnt) {
    printf("Query prep Failed: %s\n", $mysqli->error);
    exit;
}

$articles_stmnt->execute();
// https://www.php.net/manual/en/mysqli-result.fetch-array.php
$result = $articles_stmnt->get_result();
while ($row = $result->fetch_array(MYSQLI_NUM)) {
    foreach ($row as $u) {
        if (strcmp($title, $u) === 0) {
            printf('article title already exists,  <a href="submit-story.php">Try Again</a><br>');
            exit;
        }
    }
}

// insert the article into the DB
$create_stmnt = $mysqli->prepare("insert into articles (title, body, link, author) values (?, ?, ?, ?)");
if (!$create_stmnt) {
    printf("Query Prep Failed: %s\n", $mysqli->error);
    exit;
}

$create_stmnt->bind_param('ssss', $title, $body, $link, $username);
$create_stmnt->execute();
$create_stmnt->close();

header("Location: homepage.php");

?>