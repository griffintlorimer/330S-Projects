<?php
session_start();
// citing https://www.geeksforgeeks.org/how-to-delete-a-file-using-php/
$filename = $_POST["deletefile"];
$username = $_SESSION["username"];

$path = "/srv/uploads/" . $username . "/" . $filename;

if (!unlink($path)) { 
    echo ("ERROR DELETING $filename"); 
} 
else { 
    header("Location: userpage.php");
}

?>