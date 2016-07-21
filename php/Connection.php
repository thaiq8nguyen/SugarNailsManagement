<?php
    $host = "localhost";
    $username = "thaieng8_tnguyen";
    $password = "beta2site";
    $db = "thaieng8_sugarnails";
    $link = mysqli_connect($host,$username,$password,$db);
    if(!$link){
        die("Connection Failure!");
    }
?>