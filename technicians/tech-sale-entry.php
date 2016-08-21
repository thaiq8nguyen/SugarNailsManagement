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
    <div class = "container sale-entry-container top-buffer">
        <div class = "row">
            <div class = "col-md-12">
                <div class = "panel panel-default">
                    <div class = "panel-heading">
                        <div class = "form-inline">
                            <button type="button" id = "previous-day-btn" class="btn btn-default">
                                <span class = "glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
                            </button>
                            <input type = "text" id = "datepicker" class = "form-control">
                            <button type="button" id = "next-day-btn" class="btn btn-default">
                                <span class = "glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
                            </button>
                        </div>
                    </div>
                    <div class = "panel-body">
                    </div>
                </div>
            </div>
        </div>
        <div class = "row sub-sale-entry-container">
            <div class = "col-md-4" id = "tech-list"></div>
            <div class = "col-md-3">
                <div class = "panel panel-default" id = "sale-entry-panel">
                <div class = "panel-heading"><h3 class = "panel-title"></h3></div>
                <div class = "panel-body">
                    <table class = "table table-condensed wage-table">
                        <thead><tr><th>Sale Wage</th><th>Tip Wage</th><th>Total Wage</th></tr></thead>
                        <tbody><tr><td></td><td></td><td></td></tr></tbody>
                    </table>
                    <div id = "sale-entry-alert" role = "alert">
                    </div>
                    <form class = "form" id = "sale-entry-form" name ="sale-entry-form">
                        <div class = "form-group">
                            <label for = "sale">Sale: ($)</label>
                            <div class = "input-group">
                                <div class = "input-group-addon">$</div>
                                <input type = "number" id = "sale-input" min = 1 max = 1000 class = "form-control" name = "sale">
                            </div>
                        </div>
                        <div class = "form-group">
                            <label for = "cctip">Tip on Credit Card: ($)</label>
                            <div class = "input-group">
                                <div class = "input-group-addon">$</div>
                                <input type = "number" id = "tip-input" min = 1 max = 1000 class = "form-control" name = "tip">
                            </div>
                        </div>
                        <button type = "button" id = "add-sale-btn" class = "btn btn-primary">Add</button>
                        <button type = "button" id = "update-sale-btn" class = "btn btn-primary">Update</button>
                        <button type = "button" id = "delete-sale-btn" class = "btn btn-warning">Delete</button>
                    </form>

                </div>
                </div>
            </div>
            <div class = "col-md-5">
                <div class = "panel panel-default sale-summary-panel " >
                    <div class = "panel-heading"><h3 class = "panel-title">Sale Summary</h3></div>
                    <div class = "panel-body">
                        <table class = "table sale-summary-table" >
                            <tr><th>Sale Entry</th><td></td></tr>
                            <tr><th>SQ Sale (SQ Cash + SQ Credit - SQ Tip)</th><td></td></tr>
                            <tr><th>Tip Entry</th><td></td></tr>
                            <tr><th>SQ Tip</th><td></td></tr>
                            <tr><th>SQ Cash</th><td></td></tr>
                            <tr><th>SQ Credit Card</th><td></td></tr>
                            <tr><th>Expected Cash</th><td></td></tr>
                        </table>
                        <div id = "sale-summary-alert" role = "alert"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include("../inc/footer.php");

