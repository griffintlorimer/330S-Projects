<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Article Modifier</title>
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
// storing the data into values
$username = (string) $_SESSION["username"];
$title = (string)  $_POST['commmet'];


// connect to the DB and make sure it was successful
$mysqli = new mysqli('localhost', 'defaultuser', 'password', 'news_website');
if ($mysqli->connect_errno) {
    printf("Connection Failed: %s\n", $mysqli->connect_error);
    exit;
}

// get the articles from the DB
$articles_stmnt = $mysqli->prepare("select * from comments where id = (?)");
if (!$articles_stmnt) {
    printf("Query prep Failed: %s\n", $mysqli->error);
    exit;
}

// bind parama and execute
$articles_stmnt->bind_param('i', intval($title));
$articles_stmnt->execute();
$result = $articles_stmnt->get_result();

// storing the data into values
$data = $result->fetch_array(MYSQLI_NUM);
$title = (string)  $data[1];
$body = (string)  $data[2];
$id =(int) $data[0];

echo('<form action="comment-modifier-script.php" name="article-modifier-script" id="story-uploader" method="post">
<label for="body">Comment:</label>
<textarea name="body" rows="4" cols="50" class="border">' . htmlentities($body) . ' </textarea>
<input type="hidden" name="id" value="' . htmlentities($id) . '">
<input type="hidden" name="token" value=' . $_SESSION['token'] . '>
<input type="submit" value="submit">
</form>');

echo('<form action="deleter.php" method="post">
        <input type="submit" value="Delete Comment">
            <input type="hidden" name="type" value="comment">
            <input type="hidden" name="title" value="' . $id . '">
            <input type="hidden" name="token" value=' . $_SESSION['token'] . '>
    </form>');

?>
</body>
</html>