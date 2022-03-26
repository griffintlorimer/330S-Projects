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
    if (!hash_equals($_SESSION['token'], $_POST['token']) || $_POST['article'] == null) {
        die('You need to be logged in to comment on a story, <a href="homepage.php">Click here to return to the home page</a><br>');
    }
    // storing the data into values
    $username = (string) htmlentities($_SESSION["username"]);
    $id = (string) htmlentities($_POST['article']);

    // connect to the DB and make sure it was successful
    $mysqli = new mysqli('localhost', 'defaultuser', 'password', 'news_website');
    if ($mysqli->connect_errno) {
	    printf("Connection Failed: %s\n", $mysqli->connect_error);
        exit;
    }

    // get the articles from the DB
    $articles_stmnt = $mysqli->prepare("select * from articles where id = (?)");
    if (!$articles_stmnt){
        printf("Query prep Failed: %s\n", $mysqli->error);
        exit;
    }

    // bind parama and execute
    $articles_stmnt->bind_param('i', $id);
    $articles_stmnt->execute();
    $result = $articles_stmnt->get_result();

    // storing the data into values
    $data = $result->fetch_array(MYSQLI_NUM);
    $title = (string) htmlentities($data[0]);
    $link = (string) htmlentities($data[2]);
    $body = (string) htmlentities($data[1]);

    echo ('<form action="article-modifier-script.php" name="article-modifier-script" id="story-uploader" method="post">
    Title: <input type="text" name="title" class="border" value= "' . htmlentities($title) .'">
    Link: <input type="text" name="body" class="border" value= "' . htmlentities($link) .'">
    <label for="link">Body:</label>
    <textarea name="link" rows="4" cols="70"  class="border">' . htmlentities($body) . ' </textarea>
    <input type="hidden" name="old-title" value="' . htmlentities($title) . '">
    <input type="hidden" name="token" value=' . $_SESSION['token'] . '>
    <input type="submit" value="submit">
    </form>');

    echo ('<form action="deleter.php" method="post">
            <input type="submit" value="Delete ' . htmlentities($title) . '">
                <input type="hidden" name="type" value="article">
                <input type="hidden" name="title" value="' . htmlentities($id) . '">
                <input type="hidden" name="token" value=' . $_SESSION['token'] . '>
        </form>');
    ?>
</body>
</html>