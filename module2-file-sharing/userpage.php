<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="userpage.css">
    <title>File Sharing Home</title>
</head>
<body>
   
<!-- List the current files -->
<div>
   <?php 
    session_start();
    $username = $_SESSION["username"];
    echo "<h2>Hello " . $username . "</h2>"; 
    $files = scandir("/srv/uploads/" . $username);
    echo "<p>Here is a list of files that the user has uploaded:</p><ul>";
    for($i=2; $i<count($files); $i++){
        echo "<li>" . $files[$i] . "</li>" ;
    }
    echo "</ul>";
    ?>
</div>

<!-- Upload More Files -->
<div>
    <!-- Code taken from PHP course wiki Uploading a File -->
    <form enctype="multipart/form-data" action="uploader.php" method="POST">
        <p>
            <input type="hidden" name="MAX_FILE_SIZE" value="20000000" />
            <label for="uploadfile_input">Choose a file to upload:</label> <input name="uploadedfile" type="file" id="uploadfile_input" />
            <input type="submit" value="Upload" id="uploadbutton" />
        </p>
    </form>
 </div>
 
<!-- View a file -->
<div>
    <!-- Used https://www.w3schools.com/tags/tag_select.asp as a refrence -->
    <form action="fileviewer.php" method="POST">
        <label for="openfile">Choose a file to open:</label>
        <select name="openfile" id="openfile">
            <?php
                for($i=2; $i<count($files); $i++){
                    echo '<option value="' . $files[$i] . '">' . $files[$i] . '</option>';
                }
            ?>
        </select>
        <input type="submit" value="Submit">   
    </form>
</div>

<!-- Delete a file -->
<div>
    <!-- Used https://www.w3schools.com/tags/tag_select.asp as a refrence -->
    <form action="filedeleter.php" method="POST">
        <label for="deletefile">Choose a file to delete:</label>
        <select name="deletefile" id="deletefile">
            <?php
                for($i=2; $i<count($files); $i++){
                    echo '<option value="' . $files[$i] . '">' . $files[$i] . '</option>';
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