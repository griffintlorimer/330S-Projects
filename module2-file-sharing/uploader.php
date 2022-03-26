<?php
session_start();

// Code from course wiki
// https://classes.engineering.wustl.edu/cse330/index.php?title=PHP
$file_name = $_FILES['uploadedfile']['name'];

// Get the filename and make sure it is valid
$filename = basename($_FILES['uploadedfile']['name']);
if( !preg_match('/^[\w_\.\-]+$/', $filename) ){
    echo 'Invalid file name, <a href="http://ec2-44-202-112-86.compute-1.amazonaws.com/~griffinlorimer/userpage.php">click here to go back to the user page</a>';
    exit;
}

// Get the username and make sure it is valid
$username = $_SESSION['username'];
if( !preg_match('/^[\w_\-]+$/', $username) ){
    echo 'Invalid username, <a href="http://ec2-44-202-112-86.compute-1.amazonaws.com/~griffinlorimer/userpage.php">click here to go back to the user page</a>';
    exit;
}

$full_path = "/srv/uploads/" . $username . "/" . $file_name;

if (move_uploaded_file($_FILES['uploadedfile']['tmp_name'], $full_path) ){
    header("Location: userpage.php");
    exit;
} else{
    echo "Location: upload_failure.html";
    exit;
}

?>