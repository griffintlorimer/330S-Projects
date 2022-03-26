<!-- This displays the comment form and is done in php to pass the article title as the hidden field -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Comment Form</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="styles.css">
</head>
<body>
<?php
session_start();
// make sure the user is logged in
if (!(isset($_SESSION['username'])) || !(isset($_POST["title"]))) {
    echo('You need to be logged in to comment on a story, <a href="homepage.php">Click here to return to the home page</a><br>');
    echo('You can login or create an account here <a href="login-page.php">Click here to return to the login page</a><br>');
    exit;
}
$title = (string) $_POST["title"];
$id = (string) $_POST["id"];
printf($title);
printf('    <form action="comment-uploader.php" method="post">
Comment: <input type="text" name="comment" class="border">
<input type="submit" value="comment"> <input type="hidden" name="title" value="' . htmlentities($title) . '"><input type="hidden" name="id" value=' . $id . '>
</form>
</form>')
?>
</body>
</html>