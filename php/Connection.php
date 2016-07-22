<?php
    $host = "";
    $username = "";
    $password = "";
    $db = "";
    $link = mysqli_connect($host,$username,$password,$db);
    if(!$link){
        die("Connection Failure!");
    }
?>
