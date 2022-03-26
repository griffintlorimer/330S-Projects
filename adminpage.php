<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="mystyle.css">
    <title>Admin Page</title>
</head>
<body>

<!-- Welcome Header -->
<div>
    <h2>Welcome Admin</h2>
</div>
    
<!-- delete users -->
<div>
<form action="userdeleter.php" method="POST">
        <label for="deleteuser">Chose a user to delete</label>
        <select name="deleteuser" id="deleteuser">
            <?php
                $users = scandir("/srv/uploads");
                for($i=2; $i<count($users); $i++){
                    if ($users[$i] != "users.txt"){
                    echo '<option value="' . $users[$i] . '">' . $users[$i] . '</option>';
                    }
                }
            ?>
        </select>
        <input type="submit" value="Submit">   
    </form>
</div>

<!-- Logout button -->
<div>
    <form action="logout.php">
        <input type="submit" value="Logout">
    </form>
</div>
</body>
</html>