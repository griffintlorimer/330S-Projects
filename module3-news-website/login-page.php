<!--- This is the user login page-->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to our News Website</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="styles.css">
</head>

<body>
    <!-- username and password input input -->
    <div>
    <h3>Login to an existing user</h3>

    <?php
session_start();
if (isset($_SESSION['username'])) {
    header("Location: homepage.php");
    exit;
}
?>
    <form name="userinput" action="login-auth.php" method="post">
        Please log in <input type="text" name="username" class="form-control">
        Password <input type="password" name="password" class="form-control">
        <input type="submit" value="submit">
    </form>
    </div>

    <!-- create another user -->
    <div>
    <h3>Register a new user</h3>
    <form name="newuser" action="new-user.php" method="post">
        Create another user <input type="text" name="newuser" class="form-control">
        Password <input type="password" name="password" class="form-control">
        Please Enter your password again <input type="password" name="password-check" class="form-control">
        <input type="submit" value="submit">
    </form> 
    </div>

    <!-- return to homepage button -->
    <div>
        <form action="homepage.php">
        <input type="submit" value="Return to Homepage">
        </form>
    </div>
</body>
</html>

