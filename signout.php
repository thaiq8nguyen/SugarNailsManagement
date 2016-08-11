<?php
/**
 * Created by PhpStorm.
 * User: tnguyen
 * Date: 8/11/2016
 * Time: 9:54 AM
 */
    session_start();
    session_destroy();
?>
<!DOCTYPE html>
<html lang="eng-us">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Sugar Nails</title>
    <link rel = "stylesheet" href = "https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
    <link rel = "stylesheet" type="text/css" href = "/css/signin.css">
    <script type = "application/javascript" src = "https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
    <script type = "application/javascript" src = "https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
</head>
<body>
    <div class  ="container">
        <div class = "row">
            <div class = "col-md-4 col-md-offset-4" style = "margin-top:100px;text-align: center;">
                <h3>You have signed out!</h3>
                <a class="btn btn-primary" href="index.html" role="button">Sign In</a>
            </div>
        </div>

    </div>
</body>