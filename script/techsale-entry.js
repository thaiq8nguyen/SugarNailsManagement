/**
 * Created by tnguyen on 3/25/2016.
 */
$(function(){
    var $techList = $('#tech-list');
    var $saleEntryPanel = $('#sale-entry-panel');
    var $datepicker = $('#datepicker');
    var $previousDayBtn = $('#previous-day-btn');
    var $nextDayBtn = $('#next-day-btn');
    var $wageTableRow = $('.wage-table tbody tr td');
    var $saleSummaryTable = $('.sale-summary-table');
    var $saleTableRow = $('.sale-summary-table tr');
    var $saleEntryAlert = $('#sale-entry-alert');
    var $saleSummaryAlert = $('#sale-summary-alert');
    var $addSaleBtn = $('#add-sale-btn');
    var $updateSaleBtn = $('#update-sale-btn');
    var $deleteSaleBtn = $('#delete-sale-btn');
    var $saleEntryForm = $('#sale-entry-form');

    $datepicker.datepicker({
        dateFormat: 'yy-mm-dd',
        setDate:new Date()

    }).on('change',function(){
        CreateTechList();
        UpdateTotalSaleAndTip();

    });

    $datepicker.datepicker('setDate', new Date());

    $previousDayBtn.click(function(){
        SetDate('previous');
    });


    $nextDayBtn.click(function(){
        SetDate('next');
    });

    function SetDate(newDate){
        var currentDate = $datepicker.val().replace(/-/g, '\/');
        var date = new Date(currentDate);

        if(newDate === 'next'){

            date.setDate(date.getDate()+1);
        }
        else if (newDate == 'previous'){

            date.setDate(date.getDate()-1);

        }
        var dd = date.getDate();
        var mm = date.getMonth()+1;
        var yy = date.getFullYear();

        var aDate = yy + '-' + mm + '-' + dd;
        $datepicker.datepicker('setDate', new Date(aDate));
        $datepicker.change();

        if($saleEntryPanel.is(':visible')){
            $saleEntryPanel.hide();
        }
    }

    /*INITIALIZE
    ** -Technician List with sale data on the left hand size
    ** -Populate current internal sale data
    ** -Populate current Square sale data
     */

    $saleEntryPanel.hide();
    CreateTechList();
    UpdateTotalSaleAndTip();
    sessionStorage.clear();

    /* Display sale entry form when the user clicks on a technician's name*/
    $(document).on('click','.select-tech-btn',function(){

        sessionStorage.setItem("techID",$(this).attr('id'));
        sessionStorage.setItem("techName", $(this).attr('name'));

        $(this).addClass('active').siblings().removeClass('active'); //remove active styling from other buttons

        SaleEntryView();
    });
    $addSaleBtn.on('click',function() {
        if(EntryValidation()){
            var techID = sessionStorage.getItem('techID');
            var data = $saleEntryForm.serializeArray();
            data.push({name:"action",value: "setTechSale"},{name:"techID",value:techID},
                {name:"saleDate",value:$datepicker.val()});
            $.ajax({
                type: 'post',
                url: '../php/Script_DailySale_Functions.php',
                data: data,
                dataType: 'json'
            }).done(function(response) {
                if(response.status === 'success'){
                    $saleEntryAlert.removeClass();
                    $saleEntryAlert.empty().
                    append('<h3>Success!</h3><p>The sale has been recorded.</p>').addClass('alert alert-success').show();
                    $addSaleBtn.hide();
                    $updateSaleBtn.show();
                    $deleteSaleBtn.show();
                    sessionStorage.setItem("saleID",response.saleID);
                    CreateTechList();
                    UpdateTotalSaleAndTip();
                }
                else if(response.status === 'failure'){
                    $saleEntryAlert.removeClass();
                    $saleEntryAlert.empty().
                    append('<h3>Failure!</h3><p>' + response.message + '</p>').addClass('alert alert-success').show();
                }



            });
        }
        else{
            $saleEntryAlert.removeClass();
            $saleEntryAlert.empty().
            append('<h3>Failure!</h3><p>Sale or tip entry has an invalid value.</p>').addClass('alert alert-danger').show();
        }

    });

    $updateSaleBtn.click(function(){
        if(EntryValidation()){
            var techID = sessionStorage.getItem('techID');
            var saleID = sessionStorage.getItem('saleID');
            var data = $saleEntryForm.serializeArray();
            data.push({name:"action",value: "updateTechSale"},{name:"saleID",value:saleID},
                {name:"saleDate",value:$datepicker.val()});
            $.ajax({
                type: 'post',
                url: '../php/Script_DailySale_Functions.php',
                data: data,
                dataType: 'text'
            }).done(function(response) {
                if(response == 'success'){
                    $saleEntryAlert.removeClass();
                    $saleEntryAlert.empty().
                    append('<h3>Success!</h3><p>The sale has been updated.</p>').addClass('alert alert-success').show();
                    $addSaleBtn.hide();
                    $updateSaleBtn.show();
                    $deleteSaleBtn.show();
                    CreateTechList();
                    UpdateTotalSaleAndTip();
                }
                else if(response == 'failure'){
                    $saleEntryAlert.removeClass();
                    $saleEntryAlert.empty().
                    append('<h3>Failure!</h3><p>' + response.message + '</p>').addClass('alert alert-success').show();
                }


            });
        }
        else{
            $saleEntryAlert.removeClass();
            $saleEntryAlert.empty().
            append('<h3>Failure!</h3><p>Sale or tip entry has an invalid value.</p>').removeClass().addClass('alert alert-danger').show();
        }

    });


    $deleteSaleBtn.click(function(){
        var saleID = sessionStorage.getItem('saleID');
        var data = [];
        data.push({name:"action",value:"deleteTechSale"},{name:"saleID",value:saleID},{name:"saleDate",value:$datepicker.val()});
        $.ajax({
            type: 'post',
            url:'../php/Script_DailySale_Functions.php',
            data: data,
            dataType: 'text'
        }).done(function(response){
            if(response === 'success'){
                $saleEntryAlert.removeClass();
                $saleEntryAlert.empty().
                append('<h3>Success!</h3><p>The sale has been <strong>deleted</strong>.</p>').addClass('alert alert-success').show();
                $saleEntryForm[0].reset();
                $deleteSaleBtn.hide();
                $updateSaleBtn.hide();
                $addSaleBtn.show();
                CreateTechList();
                UpdateTotalSaleAndTip();
            }
            else{

            }
        });
    });
    function CreateTechList(){
        $.ajax({
            type: 'get',
            url: '../php/Script_DailySale_Functions.php',
            data:{action:"getAllTechDailySale",saleDate:$datepicker.val()},
            dataType: 'json'
        }).done(function(response){
            var $list = '';
            for(var i = 0; i < response.length; i++){
                if(response[i].sale !== '0.00'){

                    $list += '<button type = "button" id = "'+ response[i].techID +
                        '" class = "list-group-item select-tech-btn" name = "' + response[i].name + '">' +
                        '<span class = "label label-success pull-right">Sale: $' + response[i].sale +
                        ' - Tip: $' + response[i].tip + '</span>'+response[i].name+'</button>';
                }
                else{
                    $list += '<button type = "button" id = "'+ response[i].techID +
                        '" class = "list-group-item select-tech-btn" name = "' + response[i].name + '">' +
                        '<span class = "label label-default pull-right">No Sale</span>'+response[i].name+'</button>';
                }
            }
            $techList.empty().append($list);
        });
    }
    function UpdateTotalSaleAndTip(){
        var s =[];

        var totalSaleAndTip = $.ajax({
            type: 'get',
            url: '../php/Script_DailySale_Functions.php',
            data: {action: "getTotalSaleAndTip", saleDate: $datepicker.val()},
            dataType: 'json'
        });

        $.when(totalSaleAndTip).done(function(response1){
            if(response1.status === 'success'){
                $.each(response1.sale,function(key,value){
                    s.push(value);
                });
                $.each($saleTableRow,function(index,row){
                    var $row = $(row);
                    $row.find('td').each(function(){
                        $(this).html('$ ' + s[index]);
                    });
                });
                $saleSummaryAlert.hide();
                $saleSummaryTable.show();
            }
            else if(response1.status === 'failure'){
                $saleSummaryTable.hide();
                $saleSummaryAlert.empty().append('<h3>' + response1.message + '</h3>').show();
            }
        })
    }
    function SaleEntryView(){
        var $saleInput = $('#sale-input');
        var $tipInput = $('#tip-input');
        var displaySaleValue = '';
        var displayTipvalue = '';
        var wage = [];

        var techID = sessionStorage.getItem('techID');
        var techName = sessionStorage.getItem('techName');

        //Update wage table
        $.ajax({
            type: 'get',
            url: '../php/Script_DailySale_Functions.php',
            data:{action: "getTechDailySale", techID: techID , saleDate:$datepicker.val()},
            dataType: 'json'
        }).done(function(response){
            $.each(response.wage,function(key,value){
                wage.push(value);
            });

            $wageTableRow.each(function(index){
                $(this).html('$ ' + wage[index]);
            });
            $saleEntryPanel.find('.panel-title').text(techName);
            if(response.sale !== '0.00'){
                displaySaleValue = response.sale;
            }
            if(response.tip !== '0.00'){
                displayTipvalue = response.tip;
            }
            $saleInput.val(displaySaleValue);
            $tipInput.val(displayTipvalue);

            $saleEntryAlert.empty().hide();
            if(response.saleStatus == "pending"){

                $addSaleBtn.show();
                $updateSaleBtn.hide();
                $deleteSaleBtn.hide();
                $saleEntryPanel.show();
            }
            else if(response.saleStatus == "recorded"){
                sessionStorage.setItem("saleID",response.saleID);
                $addSaleBtn.hide();
                $updateSaleBtn.show();
                $deleteSaleBtn.show();
                $saleEntryPanel.show();

            }
        });

    }
    function EntryValidation(){
        var isValid = false;
        var sale = $('#sale-input').val();
        var tip = $('#tip-input').val();

        if(sale > 0 && tip > 0){
            isValid = true;
        }

        return isValid;

    }

});