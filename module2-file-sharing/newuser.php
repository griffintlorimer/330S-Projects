<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="mystyle.css">
    <title>New User</title>
</head>
</html>

<?php
$newname = $_POST["newname"];

if (!file_exists("/srv/uploads/$newname")){
    if (mkdir("/srv/uploads/$newname")){
        chmod("/srv/uploads/$newname", 0777);
        echo $newname. ' created sucsessfully, <a href="http://ec2-44-202-112-86.compute-1.amazonaws.com/~griffinlorimer/m2login.html">click here to go back to the login page</a>';
        // Getting the content of the file adding a new name then writing it back to the file
        $users = fopen("/srv/uploads/users.txt", "a");
        fwrite($users, "\n". $newname);
        fclose($users);
        }
    else {
        echo $newname . ' does not exist but a user could not be created, <a href="http://ec2-44-202-112-86.compute-1.amazonaws.com/~griffinlorimer/m2login.html">click here to go back to the login page</a>';       
    }

} else {
    echo $newname . ' is already a user, <a href="http://ec2-44-202-112-86.compute-1.amazonaws.com/~griffinlorimer/m2login.html">click here to go back to the login page</a>';
}

?>