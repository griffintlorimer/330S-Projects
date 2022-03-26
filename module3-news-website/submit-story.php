<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Submit a Story</title>
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
?>
    <!-- https://www.w3schools.com/tags/att_textarea_form.asp -->
    <form action="story-uploader.php" name="story-uploader" id="story-uploader" method="post">
    Title: <input type="text" class="border" name="title">
    Link: <input type="text" class="border" name="link">
    <label for="body">Body:</label>
    <textarea id="body" name="body" rows="4" cols="50" class="border"> </textarea>
    <?php
$token = $_POST['token'];
echo('<input type="hidden" name="token" value=' . $token . '>');
?>
    <input type="submit" value="submit">
    </form>
</body>
</html>