<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="mystyle.css">
    <title>User deleter</title>
</head>
</html>

<?php
$user = $_POST["deleteuser"];


$path = "/srv/uploads/" . $user;
$files = scandir($path);

foreach ($files as &$file) {
    if ($file == "." or $file == ".."){
        continue;
    }
    if (!unlink($path . "/" . $file)){
        echo "error deleting files";
        return false;
    }
}



if (!rmdir($path)) { 
    echo ("ERROR DELETING " . $user . '<a href="http://ec2-44-202-112-86.compute-1.amazonaws.com/~griffinlorimer/m2login.html">click here to go back to the login page</a>');
} 

else { 
    // https://www.geeksforgeeks.org/how-to-delete-text-from-file-using-preg_replace-function-in-php/
    $a = "/srv/uploads/users.txt";
    $b = file_get_contents("/srv/uploads/users.txt");
    $c = preg_replace('/'.$user.'/', '', $b);      
    file_put_contents($a, $c);

    header("Location: adminpage.php");
}

?>