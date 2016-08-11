/**
 * Created by tnguyen on 3/25/2016.
 */
$(document).ready(function(){
    var $techList = $('#tech-list');
    var $formContainer = $('#form-container');
    var $summaryContainer = $('#sale-summary');
    var $datepicker = $('#datepicker');

    $datepicker.datepicker({
        dateFormat: 'yy-mm-dd'
    });

    $datepicker.datepicker('setDate', new Date());

    $datepicker.on('change',function(){
        CreateTechList();
        UpdateTotalSaleAndTip();
        $formContainer.hide();
        $summaryContainer.hide();
    });

    /*INITIALIZE
    ** -Technician List with sale data on the left hand size
    ** -Populate current internal sale data
    ** -Populate current Square sale data
     */
    CreateTechList();
    UpdateTotalSaleAndTip();




    /* Display sale entry form when the user clicks on a technician's name*/
    $(document).on('click','.select-tech-btn',function(){
        var techID = $(this).attr('id');
        var techName = $(this).attr('name');
        var $saleEntry = '';
        var saleID = '';
        $(this).siblings().removeClass('active'); //remove active styling from other buttons
        $(this).addClass('active');

        $.ajax({
            type: 'get',
            url: '../php/Script_GetIndividualTechSale.php',
            data:{techID: techID , saleDate:$datepicker.val()},
            dataType: 'json'
        }).done(function(response){
            console.log(response);
            var sale = response.sale;
            var cctip = response.cctip;

            $saleEntry = '<form id = "sale-entry-form" id = "sale-entry-form" name = "sale-entry-form">' +
                '<div class = "panel panel-primary">' +
                '<div class = "panel-heading">' +
                '<h3 class = "panel-title">' + techName + '</h3>' +
                '</div>' +
                '<div class = "panel-body">' +
                '<div class = "form-group">' +
                '<label for = "sale">Sale: ($)</label>' +
                '<div class = "input-group">' +
                '<div class = "input-group-addon">$</div>' +
                '<input type = "number" id = "sale" class = "form-control" name = "sale" ' +
                'value = "' + sale + '">' +
                '</div>' +
                '</div>' +
                '<div class = "form-group">' +
                '<label for = "cctip">Tip on Credit Card: ($)</label>' +
                '<div class = "input-group">' +
                '<div class = "input-group-addon">$</div>' +
                '<input type = "number" id = "cctip" class = "form-control" name = "cctip" ' +
                'value = "' + cctip + '">' +
                '</div>' +
                '<input type = "hidden" name = "techID" id = "techID" value = "' + techID  + '">' +
                '<input type = "hidden" name = "saleID" id = "saleID" value = "' + response.saleID  + '">' +
                '<input type = "hidden" name = "saleDate" id = "saleDate" value = "' + $datepicker.val() + '">' +
                '</div>' +
                '<button type = "button" id = "add-sale-btn" class = "btn btn-primary">Add Sale</button>' +
                '</div>' +
                '</div>' +
                '</form>';
            $formContainer.empty().append($saleEntry);
            $formContainer.show();
            $summaryContainer.show();
            UpdateTechSaleSummary(techID,techName,$datepicker.val())
        });
    });
    $(document).on('click','#add-sale-btn',function(){
        var $confirmation = '';

        EntryValidation();

        if($('#saleID').val() === 'nosale'){
            $.ajax({
                type: 'post',
                url:'../php/Script_TechSaleEntry.php',
                data: $('#sale-entry-form').serializeArray(),
                dataType: 'json'
            }).done(function(response){
                console.log(response);
                if(response.status == 'success'){
                    $confirmation = '<div class = "panel panel-success">' +
                        '<div class = "panel-heading">' +
                        '<h3 class = "panel-title">Success</h3>' +
                        '</div>' +
                        '<div class = "panel-body">' +
                        '<p>' + response.techName + ' makes $' + response.sale + ' in sale and $'
                        + response.cctip + ' in credit card tip </p>' +
                        '</div>' +
                        '</div>';
                }
                else{
                    $confirmation = '<div class = "panel panel-danger">' +
                        '<div class = "panel-heading">' +
                        '<h3 class = "panel-title">Failure</h3>' +
                        '</div>' +
                        '<div class = "panel-body">' +
                        '<p>Technician sale has NOT been entered.</p>' +
                        '</div>' +
                        '</div>';
                }
                $formContainer.empty().append($confirmation);
                CreateTechList();
                UpdateTotalSaleAndTip();
                UpdateTechSaleSummary();
            });
        }
        else{
            $.ajax({
                type: 'post',
                url:'../php/Script_TechSaleUpdate.php',
                data: $('#sale-entry-form').serializeArray(),
                dataType: 'text'
            }).done(function(response){
                if(response == 'success'){
                    $confirmation = '<div class = "panel panel-success">' +
                        '<div class = "panel-heading">' +
                        '<h3 class = "panel-title">Success</h3>' +
                        '</div>' +
                        '<div class = "panel-body">' +
                        '<p>Technician sale has been updated.</p>' +
                        '</div>' +
                        '</div>';
                }
                else{
                    $confirmation = '<div class = "panel panel-danger">' +
                        '<div class = "panel-heading">' +
                        '<h3 class = "panel-title">Failure</h3>' +
                        '</div>' +
                        '<div class = "panel-body">' +
                        '<p>Technician sale has NOT been update.</p>' +
                        '</div>' +
                        '</div>';
                }
                $formContainer.empty().append($confirmation);
                CreateTechList();
                UpdateTotalSaleAndTip();
                UpdateTechSaleSummary();
            });

        }

    });



    function CreateTechList(){
        $.ajax({
            type: 'get',
            url: '../php/Script_GetTechSaleByDate.php',
            data:{saleDate:$datepicker.val()},
            dataType: 'json'
        }).done(function(response){
            var $list = '';
            for(var i = 0; i < response.length; i++){
                if(response[i].sale !== '0.00'){

                    $list += '<button type = "button" id = "'+ response[i].techID +
                        '" class = "list-group-item select-tech-btn" name = "' + response[i].name + '">' +
                        '<span class = "badge">Sale: $' + response[i].sale +
                        ' - Tip: $' + response[i].tip + '</span>'+response[i].name+'</button>';
                }
                else{
                    $list += '<button type = "button" id = "'+ response[i].techID +
                        '" class = "list-group-item select-tech-btn" name = "' + response[i].name + '">' +
                        '<span class = "badge">No Sale</span>'+response[i].name+'</button>';
                }
            }
            $techList.empty().append($list);
        });
    }

    function UpdateTotalSaleAndTip(){
        var internalSale = $.ajax({
            type: 'get',
            url: '../php/Script_GetTotalSaleAndTip.php',
            data:{saleDate:$datepicker.val()},
            dataType: 'json'
        });
        $.when(internalSale).done(function(sale){
            $('#total-sale').text('$ ' + sale.grossSale);
            $('#total-tip').text('$ ' + sale.grossTip);
        });
    }
    function UpdateTechSaleSummary(techID,techName,saleDate){
        var $saleSummary = '';
        $.ajax({
            type: 'get',
            url: '../php/Script_GetIndividualTechSale.php',
            data:{techID: techID , saleDate:saleDate},
            dataType: 'json'
        }).done(function(response){
        console.log(response);
            if(response !== 'nosale'){
                var saleEarning = parseFloat(response.saleEarning).toFixed(2);
                var tipEarning = parseFloat(response.tipEarning).toFixed(2);
                var totalEarning = (parseFloat(response.saleEarning) + parseFloat(response.tipEarning)).toFixed(2);
            }
            else{

                saleEarning = response.saleEarning;
                tipEarning = response.tipEarning;
                totalEarning = '0.00';
            }

            $saleSummary = '<div class = "panel panel-primary">' +
                '<div class = "panel-heading">' +
                '<h3 class = "panel-title">' + techName + '\'s Summary</h3>' +
                '</div>' +
                '<div class = "panel-body">'+
                '<div class = "row">' +
                '<div class = "col-md-4">' +
                '<h4>Sale Earning</h4>' +
                '<p> $ ' + saleEarning + '</p>' +
                '</div>' +
                '<div class = "col-md-4">' +
                '<h4>Tip  Earning</h4>' +
                '<p>$ ' + tipEarning +'</p>' +
                '</div>' +
                '<div class = "col-md-4">' +
                '<h4>Total  Earning</h4>' +
                '<p>$ ' + totalEarning + '</p>' +
                '</div>' +
                '</div>' +
                '</div>' +
                '</div>';

            $summaryContainer.empty().append($saleSummary);
        });

    }
    function EntryValidation(){
        var sale = $('#sale').val();
        var cctip = $('#cctip').val();

        if(sale == ''){
            $('#sale').val('0.00');
        }
        if(cctip == ''){
            $('#cctip').val('0.00');
        }

    }





});