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
                                <div id = "daily-sale-table-container"></div>
                            </div>
                            <div class = "col-md-8">
                                <div id = "wage-panel-container"></div>
                                <div id = "payment-panel-container"></div>
                            </div>
                        </div>

                </div>
                <div class = "modal-footer pay-modal-footer">
                    <button type="button" class="btn btn-primary make-payment-btn">Pay</button>
                </div><!-- pay-modal-footer-->
            </div><!-- modal-content-->
        </div><!-- modal-dialog-->
    </div><!-- modal-->

<?php include("../inc/footer.php");


