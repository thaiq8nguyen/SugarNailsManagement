<?php
    date_default_timezone_set('America/Los_Angeles');
    $host = "localhost";
    $username = "thaieng8_tnguyen";
    $password = "ThaiQEng19";
    $db = "thaieng8_sugarnails";
    $link = mysqli_connect($host,$username,$password,$db);
    if(!$link){
        die("Connection Failure!");
    }
?>
