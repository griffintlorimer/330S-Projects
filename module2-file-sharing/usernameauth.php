<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="mystyle.css">
    <title>UserName Auth Home</title>
</head>

<?php
if(isset($_GET['username'])){
session_start();
$username = $_GET['username'];
// echo $username;
# Code taken from ' Sending a file to the browser'on course wiki 
# Make sure that username is in valid format, if not display an error
if( !preg_match('/^[\w_\-]+$/', $username) ){
	echo 'Invalid username, <a href="http://ec2-44-202-112-86.compute-1.amazonaws.com/~griffinlorimer/m2login.html">click here to go back to the login page</a>';
	exit;
}

// $h = fopen("users.txt", "r");
$h = fopen("/srv/uploads/users.txt", "r");
# code take from 'Reading a File Line-by-Line' on course wiki
$valid_username = FALSE;
while(!feof($h)){
    $line = fgets($h);
    if (trim($line) === $username){
        $valid_username = TRUE;
    }
}

if ($username == "admin") {
    $_SESSION['username'] = $username;
    header("Location: keyinput.html");
}

elseif ($valid_username){
    $_SESSION['username'] = $username;
    # code from 'Redirecting to a Different Page' on course wiki
    header("Location: userpage.php");
    session_start();
    exit;
}
else {
	echo 'Invalid username, <a href="http://ec2-44-202-112-86.compute-1.amazonaws.com/~griffinlorimer/m2login.html">click here to go back to the login page</a>';
}

echo "</ul>\n";

fclose($h);

} 

?>