$(document).ready(function(){
    var $payPeriodForm = $('#pay-period-form');
    var $payPeriodSelect = $('#pay-period-select');
    var $payModal = $('#pay-modal');

    $payModal.modal('hide');


    var $groupWageContainer = $('#group-wage-container');

    /* Get pay period selection */
    $.ajax({
        type:'get',
        url:'../php/Script_GetPayPeriods.php',
        dataType:'json'
    }).done(function(response){

        var $option = '';
        for(var i = 0; i < response.length; i++){
            $option += '<option value = "' + response[i].id + '">' + response[i].period + '</option>';

        }
        $payPeriodSelect.append($option);
    });

    $payPeriodForm.submit(function(event){
        event.preventDefault();
        var payPeriod = $payPeriodSelect.find('option:selected').text();
        var payPeriodID = $payPeriodSelect.val();
        sessionStorage.setItem("payPeriod",payPeriod);
        sessionStorage.setItem("payPeriodID",payPeriodID);
        GetTechSaleByPeriod(payPeriodID);


    });


    $(document).on('click',".open-pay-modal-btn",function(){
        var techID = $(this).attr('id');
        var wage = JSON.parse(sessionStorage.getItem('wage'));
        var result = $.grep(wage, function(e){
            return e.techID == techID;
        });
        sessionStorage.setItem("techSale", JSON.stringify(result));



        $payModal.modal('show');
    });
    $(document).on('click','.open-pay-report-btn',function(){
        var payPeriod = sessionStorage.getItem('payPeriod');
        var techID = $(this).data("tech-id");

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
            window.open('../php//Script_GetPayReportByPeriod.php?payPeriod=' + payPeriod + '&'+ 'techID=' + techID,'_blank');
        });
    });

    /*START PAY MODAL EVENT********************************************************************************************/
    var $paymentPending = $('.payment-pending');
    var $paymentMade = $('.payment-made');
    var $makePaymentBtn = $('.make-payment-btn');

    $payModal.on('show.bs.modal',function(){
        var techSale = JSON.parse(sessionStorage.getItem("techSale"));
        $('.pay-modal-title h3').text(techSale[0].technician);
        GetTechSaleListByPeriodModal();
        GetTechSaleSummaryModal();
        GetTechSalePaymentModal();
    });

    $payModal.on('hide.bs.modal', function(){
        var pay = sessionStorage.getItem('pay');
        if(pay == 'success'){
            GetTechSaleByPeriod(sessionStorage.getItem('payPeriodID'));
        }
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
    $makePaymentBtn.click(function(){
        Pay();
    });

    /*END PAY MODAL EVENT**********************************************************************************************/

    /*START FUNCTIONS**************************************************************************************************/

    function GetTechSaleListByPeriodModal(){
        var wage = JSON.parse(sessionStorage.getItem("techSale"));
        var techID = wage[0].techID;
        var payPeriodID = sessionStorage.getItem('payPeriodID');
        var $saleTable = $('.daily-sale-table');
        var $sale = '';
        $.ajax({
            type:'get',
            url:'../php/Script_GetTechSaleListByPeriod.php',
            data:{payPeriodID:payPeriodID,techID:techID},
            dataType:'json'
        }).done(function(response){
            for(var i = 0; i < response.length; i++){
                $sale += '<tr><td>' + response[i]['workDay'] + '</td><td>$ ' + response[i]['sale'] + '</td>' +
                    '<td>$ ' + response[i]['tip'] + '</td></tr>';
            }
            $saleTable.find('tbody').empty().append($sale);

        });
    }
    function GetTechSaleSummaryModal(){
        var wage = JSON.parse(sessionStorage.getItem("techSale"));
        var $wageTable = $('.wage-table tr');
        //Declare a 'w' array to store sale metric values
        var w = [];
        //Loop through the array object and store the sale value into the new array
        $.each(wage[0].sale,function(key,value) {
            w.push(value);
        });
        //Loop through each row and find each td, store the sale value in them
        $wageTable.each(function(index,row){
            var $row = $(row);
            $row.find('td').each(function(){
                $(this).html('$ ' + w[index]);
            });
        });

        var modPayStatus = $wageTable.find('td').last().text().replace('$','');
        $wageTable.find('td').last().text(modPayStatus);

        if(wage[0].sale.payStatus === 'Paid'){
            var paidStyles = {background:'#5cb85c',color:'white','font-weight':"bold"};
            $wageTable.find('td').last().css(paidStyles);
        }
        else{
            var pendingStyles = {background:'transparent',color:'black','font-weight':"normal"};
            $wageTable.find('td').last().css(pendingStyles);
        }

    }
    function GetTechSalePaymentModal(){
        var wage = JSON.parse(sessionStorage.getItem("techSale"));
        var payStatus = wage[0].sale.payStatus;


        if(payStatus === 'Pending'){
            $paymentPending.show();
            $paymentMade.hide();
        }
        else if(payStatus === 'Paid'){
            $paymentMade.show();
            $paymentPending.hide();
            $makePaymentBtn.hide();
        }

    }
    function GetTechSaleByPeriod(payPeriodID){
        $.ajax({
            type: 'get',
            url: '../php/Script_GetTechSaleByPeriod.php',
            data:{payPeriodID:payPeriodID},
            dataType:'json'
        }).done(function(response){
            var $techWage = '';
            sessionStorage.setItem("wage",JSON.stringify(response));
            for(var i = 0; i < response.length; i++){
                $techWage += '<div class = "panel panel-default single-container">' +
                    '<div class = "panel-heading"><h3 class = "panel-title">' + response[i].technician + '' +
                    '<span class = "label label-default pull-right">' + response[i].sale.payStatus + '</span></h3></div>' +
                        '<div class = "panel-body">' +
                            '<div class = "row">' +
                                '<div class = "col-md-6">' +
                                    '<h4>Total Wage</h4><p>$ ' + response[i].sale.totalEarning + '</p>' +
                                '</div>' +
                            '</div>' +
                        '<button type = "button" id = "' + response[i].techID +
                        '" class = "btn btn-primary open-pay-modal-btn" data-tech-id = "' + response[i].techID + '">Detail</button>'+
                        '<button type = "button" class = "btn btn-primary open-pay-report-btn" data-tech-id = "' + response[i].techID + '" >Report</button>'+
                        '</div>' +
                    '</div>';
            }
            $groupWageContainer.empty().append($techWage);
            $('.single-container').each(function () {
                var payStatus = $(this).find('.label').html();
                if(payStatus === 'Paid'){
                    $(this).find('.label').removeClass('label-default').addClass('label-success');
                }
            });
        });
    }
    function Pay(){
        var techSale = JSON.parse(sessionStorage.getItem("techSale"));
        var payPeriod = sessionStorage.getItem('payPeriod');
        var periodID = sessionStorage.getItem('periodID');

        if (PayValidation()){
            var techPay = [];
            var payDetail = {};
            payDetail["techID"] = techSale[0].techID;
            payDetail['wage'] = techSale[0].sale.netWage;
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
            console.log(techPay);
            $.ajax({
            type:'post',
            url:'../php/Script_PayDay.php',
            data: {payment:JSON.stringify(techPay),payPeriod:payPeriod},
            dataType: 'json'

            }).done(function(response) {
                if(response.status === "success"){
                    $paymentMade.show();
                    $paymentPending.hide();
                    $makePaymentBtn.hide();
                    sessionStorage.setItem("pay",response.status);
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