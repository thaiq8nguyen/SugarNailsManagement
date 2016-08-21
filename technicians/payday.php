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
    $pageTitle = "Pay Day";
    $pageScript = "../script/payday.js";
    $pageCSS = "../css/payday.css"

?>
<?php include('../inc/header.php');?>
<div class = "container top-buffer">
    <div class = "row">
        <div class = "col-md-12">
            <form class = "form-inline" id = "pay-period-form" role = "form">
                <div class = "form-group">
                    <label for = "pay-period-select">Pay Period:</label>
                    <select id  = "pay-period-select" class = "form-control">
                    </select>
                </div>
                <button type = "submit" class = "btn btn-primary">Apply</button>
            </form>
        </div>
    </div>
    <div class = "row top-buffer">
        <div class = "col-md-12" id = "group-wage-container">
        </div>
    </div>
</div>
    <div class = "modal fade" id = "pay-modal" tabindex = "-1" role = "dialog">
        <div class = "modal-dialog modal-lg" role = "document">
            <div class = "modal-content">
                <div class = "modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                    <div class = "modal-title pay-modal-title"><h3></h3></div>
                </div>
                <div class = "modal-body pay-modal-body">
                        <div class = "row">
                            <div class = "col-md-4">
                                <div id = "daily-sale-table-container">                         <!--Daily sale table-->
                                    <table class = "table table-condensed daily-sale-table">
                                        <thead>
                                        <tr><th>Date</th><th>Sale</th><th>Tip</th><th></th></tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                </div>
                            </div>
                            <div class = "col-md-8">
                                    <div class = "panel panel-default"><div class = "panel-heading">Wage</div>
                                        <div class = "panel-body">
                                            <table class = "table table-condensed wage-table">
                                                <tr><th>Gross Sale</th><td></td></tr>
                                                <tr><th>Gross Tip</th><td></td></tr>
                                                <tr><th>Sale Wage</th><td></td></tr>
                                                <tr><th>Tip Wage</th><td></td></tr>
                                                <tr><th>Wage</th><td></td></tr>
                                                <tr><th>Account Balance</th><td></td></tr>
                                                <tr><th>Net Wage</th><td></td></tr>
                                                <tr><th>Pay Status</th><td></td></tr>
                                            </table>
                                        </div>
                                    </div>
                                <div class = "panel panel-default payment-pending">
                                    <div class = "panel-heading payment-panel-heading">Payments</div>
                                    <div class = "panel-body payment-panel-body">
                                        <table class = "table table-condensed payment-table">
                                            <thead><tr><th>#</th><th>Amount</th><th>Method</th><th>Reference</th><th>Action</th></tr></thead>
                                            <tbody>
                                            <tr class = "payment-row">
                                                <td>1</td>
                                                <td><div class = "input-group payment-group">
                                                        <span class="input-group-addon">$</span>
                                                        <input type = "number"  class = "form-control payment-amount">
                                                        </div></td>
                                                <td><div class = "form-group payment-group">
                                                        <select id = "payment-method-1" class = "form-control payment-method">
                                                            <option value = "1">Cash</option>
                                                            <option value = "2" selected>Check</option>
                                                            </select>
                                                        </div></td>
                                                <td><div class = "form-group payment-group">
                                                        <input class = "form-control payment-reference">
                                                        </div></td>
                                                <td><button type = "button"  class = "btn btn-primary add-payment-btn">
                                                        <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
                                                        </button></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class = "panel panel-default payment-made"><div class = "panel-heading payment-panel-heading">Pay</div>
                                    <div class = "panel-body payment-panel-body"><h3>Payments Made!</h3>
                                        <p></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                </div>
                <div class = "modal-footer pay-modal-footer">
                    <button type="button" class="btn btn-primary make-payment-btn">Pay</button>
                    <button type = "button" class="btn btn-primary" data-dismiss = "modal">Close</button>'
                </div><!-- pay-modal-footer-->
            </div><!-- modal-content-->
        </div><!-- modal-dialog-->
    </div><!-- modal-->

<?php include("../inc/footer.php");


