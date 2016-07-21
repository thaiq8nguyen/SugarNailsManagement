<?php
?>
<div class = "container">
    <div class = "row">
        <div class = "col-md-3">
            <div class = "list-group tech-list"></div>
        </div>
        <div class = "col-md-9 intro-container"><h2>Tech Sale Entry</h2>
            <p>Click on the technician name on the left column to begin enter sale entry. </p></div>
        <div class = "col-md-9 entry-container">
            <div class = "row">
                <div class = "col-md-2 name"></div>
                <div class = "col-md-2 gross-sale"></div>
                <div class = "col-md-2 gross-tip"></div>
                <div class = "col-md-2 sales"></div>
                <div class = "col-md-3 date"></div>
            </div>
            <div class = "row">
                <div class = "col-md-2 name-heading">Technician</div>
                <div class = "col-md-2 total-sale-heading">Gross Sale</div>
                <div class = "col-md-2 total-tip-heading">Gross Tip</div>
                <div class = "col-md-2 number-of-sale-heading">Sales</div>
                <div class = "col-md-3 date-heading">Date</div>
            </div>
            <div class = "row" style = "margin-top:40px;"></div>
            <form id = "saleEntryForm"></form>
            <input type = "button" id = submit-sale class = "btn btn-primary" value = "Submit">
        </div>
    </div>
</div>
<div id = "save-warning" class = "modal fade" tabindex = "-1" role = "dialog">
    <div class = "modal-dialog">
        <div class = "modal-content">
            <div class = "modal-header">
                <button type = "button" class = "close" data-dismiss = "modal">&times</button>
                <h3>Warning</h3>
            </div>
            <div class = "modal-body">
                <p>You have not submit sale entry for <span class = "technician-name"></span></p>
            </div>
            <div class = "modal-footer">
                <button type = "button" id = "do-not-save-btn" class = "btn btn-default">Do Not Save</button>
                <button type = "button" class = "btn btn-default" data-dismiss = "modal">Close</button>
            </div>
        </div>
    </div>
</div>
