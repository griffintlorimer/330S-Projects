<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Comments and Stories</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="styles.css">  
</head>
<body>
    <?php
session_start();
// make sure the user is logged in
if (!hash_equals($_SESSION['token'], $_POST['token'])) {
    die('You need to be logged in to comment on a story, <a href="homepage.php">Click here to return to the home page</a><br>');
}

$username = (string) $_SESSION["username"];
echo("<h2>Your Articles</h2>");
// connect to the DB and make sure it was successful
$mysqli = new mysqli('localhost', 'defaultuser', 'password', 'news_website');
if ($mysqli->connect_errno) {
    printf("Connection Failed: %s\n", $mysqli->connect_error);
    exit;
}

// get the articles from the DB
$articles_stmnt = $mysqli->prepare("select * from articles where author = '" . $username . "'");
if (!$articles_stmnt) {
    printf("Query prep Failed: %s\n", $mysqli->error);
    exit;
}
$articles_stmnt->execute();
$result = $articles_stmnt->get_result();
// https://www.w3schools.com/tags/tryit.asp?filename=tryhtml_select
echo('
    <form action="article-modifier.php" method="post" class="form-label">
      <label for="article" >Choose an article to modify / delete</label>
      <select name="article" id="article" class="form-control">');
while ($row = $result->fetch_array(MYSQLI_NUM)) {
    echo(' <option value="' . htmlentities($row[6]) . '">' . htmlentities($row[0]) . '</option>');
}
echo('</select>
<input type="hidden" name="token" value=' . $_SESSION['token'] . '>


    <br><br>
    <input type="submit" value="Submit">
    </form>');


//*******************COMMENTS********************* */

// get the articles from the DB
$comments_stmnt = $mysqli->prepare("select * from comments where commenter = '" . htmlentities($username) . "'");
if (!$comments_stmnt) {
    printf("Query prep Failed: %s\n", $mysqli->error);
    exit;
}
$comments_stmnt->execute();
$result2 = $comments_stmnt->get_result();
// https://www.w3schools.com/tags/tryit.asp?filename=tryhtml_select
echo('
        <form action="comment-modifier.php" method="post" class="form-label">
            <label for="commmet">Choose comment to modify / delete</label>
            <select name="commmet" id="commmet" class="form-control">');
while ($row = $result2->fetch_array(MYSQLI_NUM)) {
    echo(' <option value="' . htmlentities($row[0]) . '">' . htmlentities($row[1]) . ' : ' . htmlentities($row[2]) . '</option>');
}

echo('</select>
        <input type="hidden" name="token" value=' . $_SESSION['token'] . '>
        <br><br>
        <input type="submit" value="Submit">
        </form>');
        echo('<br>
        <form action="homepage.php">
            <input type="submit" value="Back" class="btn-sm btn-secondary"">
        </form>');
?> 

</body>
</html>