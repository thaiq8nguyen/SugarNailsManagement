/**
 * Created by tnguyen on 3/25/2016.
 */
$(document).ready(function(){
    var $techList = $('#tech-list');
    var $saleEntryPanel = $('#sale-entry-panel');
    var $formContainer = $('#form-container');
    var $summaryContainer = $('#sale-summary');
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
        $formContainer.hide();
        $summaryContainer.hide();
    });

    $datepicker.datepicker('setDate', new Date());

    $previousDayBtn.click(function(){
        var currentDate = $datepicker.val().replace(/-/g, '\/');
        var previousDate = new Date(currentDate);

        previousDate.setDate(previousDate.getDate()-1);

        var dd = previousDate.getDate();
        var mm = previousDate.getMonth()+1;
        var yy = previousDate.getFullYear();

        var aDate = yy + '-' + mm + '-' + dd;
        $datepicker.datepicker('setDate', new Date(aDate));
        $datepicker.change();
    });

    $nextDayBtn.click(function(){
        var currentDate = $datepicker.val().replace(/-/g, '\/');
        var nextDate = new Date(currentDate);

        nextDate.setDate(nextDate.getDate()+1);

        var dd = nextDate.getDate();
        var mm = nextDate.getMonth()+1;
        var yy = nextDate.getFullYear();

        var aDate = yy + '-' + mm + '-' + dd;
        $datepicker.datepicker('setDate', new Date(aDate));
        $datepicker.change();
    });

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
        //EntryValidation();
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

                $saleEntryAlert.empty().
                append('<h3>Success!</h3><p>The sale has been recorded.</p>').addClass('alert alert-success').show();
                $addSaleBtn.hide();
                $updateSaleBtn.show();
                $deleteSaleBtn.show();
                sessionStorage.setItem("saleID",response.saleID);
            }
            CreateTechList();
            UpdateTotalSaleAndTip();

        });
    });

    $updateSaleBtn.click(function(){

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

            if(response === 'success'){

                $saleEntryAlert.empty().
                append('<h3>Success!</h3><p>The sale has been updated.</p>').addClass('alert alert-success').show();
                $addSaleBtn.hide();
                $updateSaleBtn.show();
                $deleteSaleBtn.show();

            }
            CreateTechList();
            UpdateTotalSaleAndTip();

        });
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
        $.ajax({
            type: 'get',
            url: '../php/Script_DailySale_Functions.php',
            data: {action: "getTotalSaleAndTip", saleDate: $datepicker.val()},
            dataType: 'json'

        }).done(function(response){
            if(response.status === 'success'){
                $.each(response.sale,function(key,value){
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
            else if(response.status === 'failure'){
                $saleSummaryTable.hide();
                $saleSummaryAlert.empty().append('<h3>' + response.message + '</h3>').show();

            }

        });
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
        var sale = $('#sale').val();
        var cctip = $('#cctip').val();

        if(sale == ''){
            $('#sale-input').val('0.00');
        }
        if(cctip == ''){
            $('#tip-input').val('0.00');
        }

    }

});