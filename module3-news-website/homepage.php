<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Griffin and Taha News</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div>
        <h1>Welcome to Griffin and Taha News</h1>
        <h2>A news website</h2>
    </div>
        <?php
session_start();
// Will only appear if user is NOT logged in
if (!(isset($_SESSION['username']))) 
{
    echo('<form action="login-page.php">
            <input type="submit" value="login or register" class="btn-sm btn-primary">
        </form>');
}
?>
    <div id="display-articles">
    <?php
// this dynamically displays the homepage based on data in the DB
// session_start() != users being logged on
// it just lets us use the $_SESSION global variables to check if the username is not null which means the user is logged in
if (isset($_SESSION['username'])) 
{
    echo('<p>Hello ' . $_SESSION['username'] . ' </p>');
}

// connect to the DB and make sure it was successful
$mysqli = new mysqli('localhost', 'defaultuser', 'password', 'news_website');
if ($mysqli->connect_errno) 
{
    printf("Connection Failed: %s\n", $mysqli->connect_error);
    exit;
}

$mysqli = new mysqli('localhost', 'defaultuser', 'password', 'news_website');
if ($mysqli->connect_errno) 
{
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
      <label>Choose an author to filter by</label>
      <select class="form-select" name="author" >');
$authors = [];

while ($row = $result->fetch_array(MYSQLI_NUM)) 
{
    if (in_array($row[0], $authors)) {
        continue;
    }
    array_push($authors, $row[0]);
    echo(' <option value="' . $row[0] . '">' . htmlentities($row[0]) . '</option>');
}
echo('</select><input type="submit" class="btn btn-info" value="submit"></form>');

// get the articles from the DB
// https://www.w3schools.com/sql/sql_ref_order_by.asp
$articles_stmnt = $mysqli->prepare("select * from articles ORDER BY likes DESC");
if (!$articles_stmnt) 
{
    printf("Query prep Failed: %s\n", $mysqli->error);
    exit;
}

$articles_stmnt->execute();

// https://www.php.net/manual/en/mysqli-result.fetch-array.php
// output the articles
$result = $articles_stmnt->get_result();
while ($row = $result->fetch_array(MYSQLI_NUM)) 
{
    $title = (string) $row[0];
    $body = (string) $row[1];
    $link = (string) $row[2];
    $author = (string) $row[3];
    $num_comments = (int) $row[4];
    $likes = (int) $row[5];
    $id = (int) $row[6];
    printf('<h2>' . htmlentities($title) . '</h2>');
    printf('<a href="' . htmlentities($link) . '"' . '>' . htmlentities($link) . "</a>");
    echo('<h5>' . htmlentities($body) . '</h5>');
    printf('<p>Written by ' . htmlentities($author) . '</p>');
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
    echo('<form action="comment-form.php" method="post"> 
            <input type="submit"  class="btn-sm btn-primary" value="comment">  
            <input type="hidden" name="title" value="' . htmlentities($title) . '">
            <input type="hidden" name="id" value=' . $id . '>
        </form>');
    echo('<form action="like-script.php"  method="post">
            <input type="submit" value="like" class="btn-sm btn-primary" > 
            <input type="hidden" name="id" value=' . $id . '>
        </form>');
    printf('<p>' . $likes . '</p>');
}

?>
    <?php
// Will only appear if user is logged in
if (isset($_SESSION['username'])) 
{
    echo('
        <form action="submit-story.php" method="post">
            <input type="submit" value="submit an article" class="btn-sm btn-secondary"">
            <input type="hidden" name="token" value=' . $_SESSION['token'] . '>
        </form>');
}
?>
    </div>
    
    <div>
    <?php
// Will only appear if user is logged in
if (isset($_SESSION['username'])) 
{
    echo('<form action="user-page.php" method="post">
            <input type="submit" value="Manage Your stories and comments" class="btn-sm btn-secondary"">
            <input type="hidden" name="token" value=' . $_SESSION['token'] . '>
        </form>');
    echo('<form action="logout.php">
        <input type="submit" value="logout" class="btn-sm btn-secondary"" id="logout">
    </form>');
}

?>
    </div>
</body>
</html>
