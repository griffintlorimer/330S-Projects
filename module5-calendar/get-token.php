<?php
ini_set("session.cookie_httponly", 1);


    session_start();

    echo json_encode(array(
        "token" => $_SESSION['token']
    ));

?>