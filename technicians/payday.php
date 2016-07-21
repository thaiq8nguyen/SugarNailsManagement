<?php
    $pageTitle = "Pay Day";
    $pageScript = "../script/payday.js";
    $pageCSS = "../css/payday.css"

?>
<?php include('../inc/header.php');?>
<div class = "container top-buffer">
    <div class = "row">
        <div class = "col-md-12">
            <form class = "form-inline" id = "date-form">
                <div class = "form-group">
                    <label for = "from-datepicker">From Date: </label>
                    <input type = "text" id = "from-datepicker" class = "form-control">
                </div>
                <div class = "form-group">
                    <label for = "to-datepicker">To Date: </label>
                    <input type = "text" id = "to-datepicker" class = "form-control">
                </div>
                <button type = "submit" class = "btn btn-primary">Apply</button>
            </form>
        </div>
    </div>
    <div class = "row top-buffer">
        <div class = "col-md-12">
            <table id = "earning-table" class = "table table-striped table-hover">
                <thead>
                <tr>
                    <th></th>
                    <th>Pay</th>
                    <th>Technician</th>
                    <th>Gross Sale</th>
                    <th>Gross Tip</th>
                    <th>Sale Earning</th>
                    <th>Tip Earning</th>
                    <th>Total Earning</th>
                    <th>Check Amount</th>
                    <th>Check #</th>
                    <th>Other Payment</th>
                </tr>
                </thead>
                <tbody></tbody>
            </table>

        </div>
        <div class = "col-md-4 col-md-offset-10">
            <button type = "button" id = "submit-pay" class = "btn btn-primary">Pay</button>
        </div>
    </div>
</div>
<?php include("../inc/footer.php");


