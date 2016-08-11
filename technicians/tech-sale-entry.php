<?php

    session_start();
    if(!isset($_SESSION['userID'])){
        header('Location: index.html');
        die();
    }
    else{
        $header = file_get_contents('../header.html');
        $header = str_replace('%username%',$_SESSION['username'],$header);

    }

    date_default_timezone_set('America/Los_Angeles');
    $pageTitle = "Tech Sale Entry";
    $pageCSS = "../css/tech-sale-entry.css";
    $pageScript = "../script/techsale-entry.js";

?>
    <?php include('../inc/header.php');?>
    <div class = "container sale-entry-container">
        <div class = "row">
            <div class = "col-md-12">
                <div class = "panel panel-default">
                    <div class = "panel-heading">
                        <h3 class = "panel-title">Sale Date: <input type = "text" id = "datepicker"><h3>
                    </div>
                    <div class = "panel-body">
                        <table id = "sale-table" class = "table">
                            <thead>
                            <tr>
                                <th>Gift Card Sold</th>
                                <th>Gift Card Redeemed</th>
                                <th>Total Sale</th>
                                <th>Square Sale</th>
                                <th>Sale Difference</th>
                                <th>Total Tip</th>
                                <th>Square Tip</th>
                                <th>Tip Difference</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td id = "gc-sold"></td>
                                <td id = "gc-redeemed"></td>
                                <td id = "total-sale"></td>
                                <td id = "sq-sale"></td>
                                <td id = "sale-dif"></td>
                                <td id = "total-tip"></td>
                                <td id = "sq-tip"></td>
                                <td id = "tip-dif"></td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class = "row">
            <div class = "col-md-4" id = "tech-list"></div>
            <div class = "col-md-3" id = "form-container"></div>
            <div class = "col-md-5" id = "sale-summary"></div>
        </div>
    </div>


    <?php include("../inc/footer.php");

