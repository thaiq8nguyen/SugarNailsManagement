$(document).ready(function(){
    var $fromDate = $('#from-datepicker');
    var $toDate = $('#to-datepicker');
    var $dateForm = $('#date-form');
    var $payModal = $('#pay-modal');

    $payModal.modal('hide');


    var $groupWageContainer = $('#group-wage-container');

    $fromDate.datepicker({
        dateFormat: 'yy-mm-dd'
    });
    $toDate.datepicker({
        dateFormat: 'yy-mm-dd'
    });

    $dateForm.submit(function(event){
        event.preventDefault();
        var validDate = true;
        if($fromDate.val() == ''){
            validDate = false;
        }
        if($toDate.val() == ''){
            validDate = false;
        }

        if(validDate){
            sessionStorage.setItem("payPeriod",$fromDate.val() + " - " + $toDate.val());
            GetTechSaleByPeriod($fromDate.val(),$toDate.val());
        }
        else{
            alert('Select the dates');
        }

    });

    $('.make-payment-btn').click(function(){
        Pay();
    });
    $(document).on("blur","#check-payment #other-payment",function(event){
        var checkAmount = parseInt($(this).val());
        var otherAmount = parseInt($(this).closest('tr').find('#payment-one').val());

        console.log(checkAmount + otherAmount);
    });

    $(document).on('click',".open-pay-modal-btn",function(){

        sessionStorage.setItem('techID',$(this).siblings('.row').children('.col-md-4').find('.techID').val());
        sessionStorage.setItem('techName',$(this).siblings('.row').children('.col-md-4').find('.techName').val());
        sessionStorage.setItem('grossSale',$(this).siblings('.row').children('.col-md-4').find('.grossSale').val());
        sessionStorage.setItem('grossTip',$(this).siblings('.row').children('.col-md-4').find('.grossTip').val());
        sessionStorage.setItem('saleEarning',$(this).siblings('.row').children('.col-md-4').find('.saleEarning').val());
        sessionStorage.setItem('tipEarning',$(this).siblings('.row').children('.col-md-4').find('.tipEarning').val());
        sessionStorage.setItem('totalEarning',$(this).siblings('.row').children('.col-md-4').find('.totalEarning').val());
        sessionStorage.setItem('payStatus',$(this).siblings('.row').children('.col-md-4').find('.payStatus').val());


        $payModal.modal('show');
    });
    $(document).on('click','.open-pay-report-btn',function(){
        var payPeriod = sessionStorage.getItem('payPeriod');
        var techID = $(this).siblings('.row').children('.col-md-4').find('.techID').val();
        $(function(){
            /*
            $.ajax({
                type: 'get',
                url: '../php/Script_GetPayReportByPeriod.php',
                data:{payPeriod:payPeriod,techID:techID},
                dataType: 'text'
            }).done(function(response){
                console.log(response);
            });
            */
            window.location.href = '../php//Script_GetPayReportByPeriod.php?techID=' + techID + '&'+ 'payPeriod=' + payPeriod;
        });
    });

    /*START PAY MODAL EVENT********************************************************************************************/

    var $openPDFPayReportBtn = '<button type = "button" class = "btn btn-primary open-pay-report-btn">Report</button>';
    var $closeModalBtn = '<button type = "button" class="btn btn-primary" data-dismiss = "modal">Close</button>';

    $payModal.on('show.bs.modal',function(){
        $('.pay-modal-title h3').text(sessionStorage.getItem('techName'));
        GetTechSaleListByPeriodModal(sessionStorage.getItem('payPeriod'));
        GetTechSaleSummaryModal();
        GetTechSalePaymentModal();
    });

    $payModal.on('hide.bs.modal', function(){
        //sessionStorage.clear();
    });

    $(document).on('click','.add-payment-btn',function(){

        /* Delete payment button template*/
        var deletePaymentBtn = '<button type = "button"  class = "btn btn-primary delete-payment-btn">' +
        '<span class="glyphicon glyphicon-minus" aria-hidden="true"></span>' +
        '</button>';

        /* Get payment number in the first column and parse to an integer*/
        var paymentNum = parseInt($(this).closest('tr').find('td:eq(0)').text());

        /* Add 1 to the current payment number to get the next payment number*/
        var nextPaymentNum = paymentNum + 1;

        /* Get the current payment row and clone it*/
        var paymentRow = $(this).closest('.payment-row').clone();

        /* Clear every input the next row*/
        paymentRow.find('input').each(function(){
            $(this).val('');
        });


        /* Append the next payment after the current payment row*/
        paymentRow.insertAfter('.payment-row:last');

        /* Insert the payment number to the next payment*/
        $('.payment-row:last').find('td:eq(0)').text(nextPaymentNum);

        /* Replace the add payment with the delete payment button*/
        $(this).replaceWith(deletePaymentBtn);

    });
    $(document).on('click','.delete-payment-btn',function(){
        $(this) .closest('tr').remove();
        var $paymentRow = $('.payment-table tbody tr');
        var paymentCount = 1;

        /* Looping through each payment row and insert the payment number*/
        $paymentRow.each(function(){
            $(this).find('td:eq(0)').text(paymentCount);
            paymentCount++;
        });
    });

    /*END PAY MODAL EVENT**********************************************************************************************/

    /*START FUNCTIONS**************************************************************************************************/

    function GetTechSaleListByPeriodModal(){
        var payPeriod = sessionStorage.getItem('payPeriod');
        var techID = sessionStorage.getItem('techID');
        var wageTable = '<table class = "table table-condensed">' +
            '<thead>' +
                '<tr>' +
                    '<th>Date</th>' +
                    '<th>Sale</th>' +
                    '<th>Tip</th>' +
                    '<th></th>' +
                '</tr>' +
            '</thead><tbody>';
        $.ajax({
            type:'get',
            url:'../php/Script_GetTechSaleListByPeriod.php',
            data:{payPeriod:payPeriod,techID:techID},
            dataType:'json'
        }).done(function(response){
            console.log(response);
            for(var i = 0; i < response.length; i++){
                wageTable += '<tr><td>' + response[i]['workDay'] + '</td><td>$ ' + response[i]['sale'] + '</td>' +
                    '<td>$ ' + response[i]['tip'] + '</td></tr>';
            }
            wageTable += '</tbody></table>';
            $('#daily-sale-table-container').empty().append(wageTable);
        });
    }
    function GetTechSaleSummaryModal(){
        var $wagePanel = '<div class = "panel panel-default"><div class = "panel-heading">Wage</div>' +
                '<div class = "panel-body">' +
                    '<table class = "table table-condensed">' +
                        '<tr>' +
                            '<th>Gross Sale</th>' +
                            '<td>$ ' + sessionStorage.getItem("grossSale") + '</td>' +
                        '</tr>' +
                        '<tr>' +
                            '<th>Gross Tip</th>' +
                            '<td>$ ' + sessionStorage.getItem("grossTip") + '</td>' +
                        '</tr>' +
                        '<tr>' +
                            '<th>Earn Sale</th>' +
                            '<td>$ ' + sessionStorage.getItem("saleEarning") + '</td>' +
                        '</tr>' +
                        '<tr>' +
                            '<th>Earn Tip</th>' +
                            '<td>$ ' + sessionStorage.getItem("tipEarning") + '</td>' +
                        '</tr>' +
                        '<tr>' +
                        '<th>Total Wage</th>' +
                        '<td>$ ' + sessionStorage.getItem("totalEarning") + '</td>' +
                        '</tr>' +
                        '<tr>' +
                            '<th>Account Balance</th>' +
                            '<td>$</td>' +
                        '</tr>' +
                    '</table>' +
                '</div>';
        $('#wage-panel-container').empty().append($wagePanel);
    }
    function GetTechSalePaymentModal(){
        var $paymentMade = '<div class = "panel panel-default"><div class = "panel-heading payment-panel-heading">Pay</div>' +
            '<div class = "panel-body payment-panel-body"><h3>Payments Made!</h3>'+
            '<p>' + sessionStorage.getItem("techName") + ' had been paid for pay period ' +
            sessionStorage.getItem("payPeriod") + ' </p>'+
            '</div>';
        var payStatus = sessionStorage.getItem('payStatus');

        var $paymentPanel = '<div class = "panel panel-default"><div class = "panel-heading payment-panel-heading">Pay</div>' +
                '<div class = "panel-body payment-panel-body">' +
                    '<table class = "table table-condensed payment-table">' +
                        '<thead><tr><th>#</th><th>Amount</th><th>Method</th><th>Reference</th><th>Action</th></tr></thead>' +
                        '<tbody>' +
                            '<tr class = "payment-row">' +
                                '<td>1</td>' +
                                '<td><div class = "input-group payment-group">' +
                                    '<span class="input-group-addon">$</span>' +
                                    '<input type = "number"  class = "form-control payment-amount">' +
                                    '</div></td>' +
                                '<td><div class = "form-group payment-group">' +
                                    '<select id = "payment-method-1" class = "form-control payment-method">' +
                                        '<option value = "1">Cash</option>' +
                                        '<option value = "2" selected>Check</option>' +
                                    '</select>' +
                                    '</div></td>' +
                                '<td><div class = "form-group payment-group">' +
                                '<input class = "form-control payment-reference">' +
                                '</div></td>' +
                                '<td><button type = "button"  class = "btn btn-primary add-payment-btn">' +
                                '<span class="glyphicon glyphicon-plus" aria-hidden="true"></span>' +
                                '</button></td>' +
                            '</tr>' +
                        '</tbody>'+
                    '</table>' +
                '</div>';
        if(payStatus === 'Pending'){
            $('#payment-panel-container').empty().append($paymentPanel);

        }
        else if(payStatus === 'Paid'){
            $('#payment-panel-container').empty().append($paymentMade);
            $('.make-payment-btn').replaceWith($closeModalBtn);
        }

    }
    function GetTechSaleByPeriod(from,to){
        $.ajax({
            type: 'get',
            url: '../php/Script_GetTechSaleByPeriod.php',
            data:{fromDate:from,toDate:to},
            dataType:'json'
        }).done(function(response){
            var $techWage = '';



            for(var i = 0; i < response.length; i++){
                $techWage += '<div class = "panel panel-default single-container">' +
                    '<div class = "panel-heading"><h3 class = "panel-title">' + response[i].technician +
                    '<span class = "label label-default pull-right ">' + response[i].payStatus + '</span></h3></div>' +
                        '<div class = "panel-body">' +
                            '<div class = "row">' +
                                '<div class = "col-md-4">' +
                                    '<h4>Wage</h4><p>$ ' + response[i].totalEarning + '</p>' +
                                    '<input type = "hidden" class = "techID" value = "' + response[i].techID + '">' +
                                    '<input type = "hidden" class = "techName" value = "' + response[i].technician + '">' +
                                    '<input type = "hidden" class = "grossSale" value = "' + response[i].grossSale + '">' +
                                    '<input type = "hidden" class = "grossTip" value = "' + response[i].grossTip + '">' +
                                    '<input type = "hidden" class = "saleEarning" value = "' + response[i].saleEarning + '">' +
                                    '<input type = "hidden" class = "tipEarning" value = "' + response[i].tipEarning + '">' +
                                    '<input type = "hidden" class = "totalEarning" value = "' + response[i].totalEarning + '">' +
                                    '<input type = "hidden" class = "payStatus" value = "' + response[i].payStatus + '">' +
                                '</div>' +

                            '</div>' +
                        '<button type = "button" class = "btn btn-primary open-pay-modal-btn">Detail</button>'+
                        '</div>' +
                    '</div>';
            }
            $groupWageContainer.empty().append($techWage);

            $('.single-container').each(function () {
                var payStatus = $(this).find('.payStatus').val();
                if(payStatus === 'Paid'){
                    $(this).find('.label').removeClass('label-default').addClass('label-success');
                    $(this).find('.open-pay-modal-btn').after($openPDFPayReportBtn);
                }
            });
        });
    }
    function Pay(){

        var payPeriod = sessionStorage.getItem('payPeriod');
        if (PayValidation()){
            var techPay = [];
            var payDetail = {};
            payDetail["techID"] = sessionStorage.getItem('techID');
            payDetail['wage'] = sessionStorage.getItem('totalEarning');
            var payment = [];
            $('.payment-table tbody tr').each(function (row, tr) {
                if ($(tr).find('td:eq(1) input').val().length !== 0) {
                    payment[row] =
                                {
                                    "paymentAmount": $(tr).find('td:eq(1) input').val(),
                                    "paymentMethod": $(tr).find('td:eq(2) select').val(),
                                    "paymentReference": $(tr).find('td:eq(3) input').val()
                                };
                }
            });
            payDetail["payments"] = payment;
            techPay.push(payDetail);

            $.ajax({
            type:'post',
            url:'../php/Script_PayDay.php',
            data: {payment:JSON.stringify(techPay),payPeriod:payPeriod},
            dataType: 'json'

            }).done(function(response) {
                if(response.status === "success"){
                    $('#payment-panel-container').empty().append($paymentMade);
                    $('.make-payment-btn').replaceWith($closeModalBtn);
                }

            });



        }
    }

    function PayValidation(){
        var isValidPay = true;
        var payData = [];
        var $paymentTable = $('.payment-table tbody tr');

        $paymentTable.each(function(row,tr){
            if($(tr).find('td:eq(1) input').val() !==''){
                payData[row] = {

                    "paymentAmount": $(tr).find('td:eq(1) input').val(),
                    "paymentMethod": $(tr).find('td:eq(2) select').val(),
                    "paymentReference": $(tr).find('td:eq(3) input').val()
                };
            }

        });

        if (payData.length == 0){
            alert('Create at least one payment');
            isValidPay = false;
        }

        return isValidPay;
    }


});