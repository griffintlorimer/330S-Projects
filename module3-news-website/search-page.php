<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Search Page</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="styles.css">  
</head>
  <body>
    <?php
session_start();
$author = (string)$_POST["author"];
echo($author);

// connect to the DB and make sure it was successful
$mysqli = new mysqli('localhost', 'defaultuser', 'password', 'news_website');
if ($mysqli->connect_errno) {
    printf("Connection Failed: %s\n", $mysqli->connect_error);
    exit;
}

$mysqli = new mysqli('localhost', 'defaultuser', 'password', 'news_website');
if ($mysqli->connect_errno) {
    printf("Connection Failed: %s\n", $mysqli->connect_error);
    exit;
}

// get the authors from the DB
$authors_stmnt = $mysqli->prepare("select author from articles");
if (!$authors_stmnt) 
{
    printf("Query prep Failed: %s\n", $mysqli->error);
    exit;
}
$authors_stmnt->execute();
$result = $authors_stmnt->get_result();
// https://www.w3schools.com/tags/tryit.asp?filename=tryhtml_select
echo('
    <form action="search-page.php" method="post">
      <label for="author">Choose an author</label>
      <select name="author" id="author">');
$authors = [];

while ($row = $result->fetch_array(MYSQLI_NUM)) 
{
    if (in_array($row[0], $authors)) {
        continue;
    }
    array_push($authors, $row[0]);
    echo(' <option value="' . htmlentities($row[0]) . '">' . htmlentities($row[0]) . '</option>');
}
echo('</select><input type="submit" value="submit"></form>');

// get the articles from the DB
// https://www.w3schools.com/sql/sql_ref_order_by.asp
$articles_stmnt = $mysqli->prepare("select * from articles where author=(?) ORDER BY likes DESC");
if (!$articles_stmnt) 
{
    printf("Query prep Failed: %s\n", $mysqli->error);
    exit;
}
$articles_stmnt->bind_param('s', $author);
$articles_stmnt->execute();

// https://www.php.net/manual/en/mysqli-result.fetch-array.php
// output the articles
$result = $articles_stmnt->get_result();
while ($row = $result->fetch_array(MYSQLI_NUM)) 
{
    $title = (string)$row[0];
    $body = (string)$row[1];
    $link = (string)$row[2];
    $author = (string)$row[3];
    $num_comments = (int)$row[4];
    $likes = (int)$row[5];
    $id = (int)$row[6];
    printf('<h2 id="title">' . htmlentities($title) . '</h2>');
    printf('<a href="' . htmlentities($link) . '"' . '>' . $link . "</a>");
    printf('<h5 id="body">' . htmlentities($body) . '</h4>');
    printf('<p id="author">Written by ' . htmlentities($author) . '</p>');
    if ($num_comments > 0) {
        echo('<p>Comments</p>');
        $comments_stmnt = $mysqli->prepare("select body, commenter from comments where article_id='" . $id . "'");
        if (!$comments_stmnt) {
            printf("Query prep Failed: %s\n", $mysqli->error);
            exit;
        }
        $comments_stmnt->execute();
        $coments_result = $comments_stmnt->get_result();
        while ($comments = $coments_result->fetch_array(MYSQLI_NUM)) {
            printf('<p>' . $comments[1] . ' : ' . $comments[0] . '</p>');

        }
    }
    // this will take you to the comment form page, with a hidden field to pass the name of the articles through
    echo('<form action="comment-form.php" id="comment-btn" method="post"> <input type="submit" class="btn-sm btn-primary" value="comment"> <input type="hidden" name="title" value=' . htmlentities($title) . '><input type="hidden" name="id" value=' . $id . '></form>');
    echo('<form action="like-script.php"  id="like-btn" method="post"> <input type="submit" value="like" id="comment-btn" class="btn-sm btn-primary" > <input type="hidden" name="id" value=' . $id . '></form>');
    printf('<p id="likes">' . $likes . '</p>');
}
echo('<form action="homepage.php">
<input type="submit" value="Return Home">
</form>');
?>

</body>
</html>