<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="mystyle.css">
    <title>keycheck home</title>
</head>
</html>

<?php
$key = $_POST["key"];

if ($key == "griffinandtaha"){
    header("Location: adminpage.php");
}
else {
	echo 'Invalid key, <a href="http://ec2-44-202-112-86.compute-1.amazonaws.com/~griffinlorimer/m2login.html">click here to go back to the login page</a>';
}

?>