$(document).ready(function(){
    var $fromDate = $('#from-datepicker');
    var $toDate = $('#to-datepicker');
    var $dateForm = $('#date-form');



    var $earningTableBody = $('#earning-table tbody');

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
            GetTechSaleByPeriod($fromDate.val(),$toDate.val());
        }
        else{
            alert('Select the dates');
        }

    });

    $('#submit-pay').click(function(){
        Pay();
    });



    function GetTechSaleByPeriod(from,to){
        $.ajax({
            type: 'get',
            url: '../php/Script_GetTechSaleByPeriod.php',
            data:{fromDate:from,toDate:to},
            dataType:'json'
        }).done(function(response){
            var $earning = '';
            for(var i = 0; i < response.length; i++){
                $earning += '<tr><td><input type = "hidden" name = "techid" value = "' + response[i].techID + '"></td>' +
                        '<td><input type="checkbox" name = "to-pay"></td>'+
                        '<td>' + response[i].technician + '</td>' +
                        '<td>$ ' + response[i].grossSale + '</td>' +
                        '<td>$ ' + response[i].grossTip + '</td>' +
                        '<td>$ ' + response[i].saleEarning + '</td>' +
                        '<td>$ ' + response[i].tipEarning + '</td>' +
                        '<td><strong>$ ' + response[i].totalEarning + '</strong></td>' +
                        '<td><input type = "number" name = "check-payment" class = "form-control"></td>' +
                        '<td><input type = "number" name = "check-number" class = "form-control"</td>' +
                        '<td><input type = "number" name = "other-payment" class = "form-control"></td>' +
                        '</tr>';
            }
            $earningTableBody.empty().append($earning);
        });
    }
    function Pay(){
        if (PayValidation()){
            var payData = [];
            $('#earning-table tbody tr').each(function (row, tr) {
                if ($(tr).find('td:eq(1) input').prop('checked')) {
                    payData[row] = {
                        "techID": $(tr).find('td:eq(0) input').val(),
                        "payment":
                            [
                                {"checkAmount": $(tr).find('td:eq(8) input').val(),
                                "checkRef": $(tr).find('td:eq(9) input').val()},
                                {"otherPayment": $(tr).find('td:eq(10) input').val()}
                            ]
                    };
                }
            });
            console.log(payData);
            /*
            $.ajax({
            type:'post',
            url:'../php/Script_PayDay.php',
            data: {payment:JSON.stringify(payData),startPeriod:$fromDate.val(),endPeriod:$toDate.val()},
            dataType: 'text'

            }).done(function(response) {
                window.location.href = 'paydaycomplete.php?startPeriod=' +
                $fromDate.val() + '&endPeriod=' + $toDate.val() + '&status=' + response;
            });
            */
        }
    }

    function PayValidation(){
        var isValidPay = true;
        var payData = [];
        var $earningTableBody = $('#earning-table tbody tr');

        $earningTableBody.each(function(row,tr){
            if($(tr).find('td:eq(1) input').prop('checked')){
                payData[row] = {
                    "techID": $(tr).find('td:eq(0) input').val(),
                    "checkPayment": $(tr).find('td:eq(8) input').val(),
                    "checkNumber": $(tr).find('td:eq(9) input').val(),
                    "otherPayment": $(tr).find('td:eq(10) input').val()
                };
            }

        });

        if (payData.length == 0){
            alert('Select at least one employee to pay');
            isValidPay = false;
        }

        return isValidPay;
    }
});