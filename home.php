<?php

    session_start();
    if(!isset($_SESSION['userID'])){
        header('Location: index.html');
        die();
    }
    else{
        $header = file_get_contents('header.html');
        $header = str_replace('%username%',$_SESSION['username'],$header);
    }

    $pageTitle = "Home";
    $pageCSS = "../css/home.css";
    $pageScript = "test";
?>
    <?php include('inc/header.php');?>
    <div class = "container top-buffer">
        <div class = "row">
            <div class = "col-md-8">
                <div class = "panel panel-default">
                    <div class = "panel-heading">Update</div>
                    <div class = "panel-body">
                        <h3>03/25/2016</h3>
                        <ul>
                            <li>Created home.php</li>
                        </ul>
                    </div>
                </div>

            </div>
            <div class = "col-md-4">
                <div class = "panel panel-default">
                    <div class = "panel-heading">Sale</div>
                    <div class = "panel-body">
                        <h3>Sale Placeholder</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php include('inc/footer.php');?>


