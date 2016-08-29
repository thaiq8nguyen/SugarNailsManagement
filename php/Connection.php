<?php
    date_default_timezone_set('America/Los_Angeles');
    $host = "";
    $username = "";
    $password = "";
    $db = "";
    $link = mysqli_connect($host,$username,$password,$db);
    if(!$link){
        die("Connection Failure!");
    }
?>
