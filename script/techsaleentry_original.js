$(document).ready(function(){
    //$('nav').load('../navigation.php');
    var $techList = $('.tech-list');
    var $introContainer = $('.intro-container');
    var $entryContainer = $('.entry-container');
    var $techName = $('.name');
    var $grossSale = $('.gross-sale');
    var $grossTip = $('.gross-tip');
    var $sales = $('.sales');
    var $todayDate = $('.date');
    var $saleEntryForm = $('#saleEntryForm');
    var $submitSale = $('#submit-sale');
    var $saveWarning = $('#save-warning');
    $saveWarning.modal('hide');
    var d = new Date();
    var today = d.toDateString();

    var saleEntry ={
        name:'',
        techID:'',
        status:'complete',
        saleDate:today
    };
    var saleList = {}, tipList = {}, sales = 0;

    $entryContainer.hide();
    $grossSale.html('<h3>$ 0</h3>');
    $grossTip.html('<h3>$ 0</h3>');
    $sales.html('<h3>0</h3>');
    $todayDate.html('<h3>'+today+'</h3>');

    /*Load Technician List*/
    $.post('../php/Script_GetTechnician.php',function(response){
        var list = '';
        for(var i = 0;i < response.length;i++){
            list += '<button type = "button" id = "'+ response[i].techID + '" class = "list-group-item select-tech-btn">'+response[i].name+'</button>';
        }
        $techList.empty().append(list);
    },'json');
    /*Load Technician Sale Entry*/

    $(document).on('click','.select-tech-btn',function(){
        $.post('../php/Script_GetEntry.php',{techid:$(this).attr('id')},function(response){

        },'text');
        if(saleEntry.status == 'complete'){
            var technicianName = $(this).text();
            saleEntry.name = technicianName; //Set technician name
            saleEntry.techID = $(this).attr('id'); //Set technician ID
            saleEntry.status = 'pending'; //Set default entry status
            saleEntry.entryDate = today;

            $introContainer.hide();
            $entryContainer.show();

            $techName.html('<h3>'+technicianName+'</h3>');
            var saleTable = $.ajax({ //Get table jqXHR object
                type:'get',
                url:'../Template/SaleEntryTable.html',
                cache:false
            });
            var serviceMenu = $.getJSON('../php/Script_GetService.php',function(){}); //get service name jQXHR object

            $.when(saleTable,serviceMenu).done(function(table,service){
                $saleEntryForm.empty().append(table[0]);
                var option = '';
                for(var i = 0; i < service[0].length; i++){
                    option += '<option value = ' + service[0][i].serviceID + '>' + service[0][i].serviceName + '</option>';
                }
                $('.service-name').empty().append(option).val('5');
                $('.service-table tbody>tr:last').attr('id','pendingSale');
            });

        }
        else if(saleEntry.status == 'pending'){
            $('.technician-name').html(saleEntry.name);
            $saveWarning.modal('show');
        }
/*START Save Warning Modal*********************************************************************************************/
    var $doNotSave = $('#do-not-save-btn');
        $doNotSave.click(function(){

            $saveWarning.modal('close');
            console.log('click');
        });

/*END Save Warning Modal***********************************************************************************************/

    console.log(saleEntry);
    });
    $(document).on('click','.add-sale',function(){

        var saleID = $(this).closest('tr').attr('id');
        if(saleID == 'pendingSale'){
            var $currentRow  = $(this).closest('tr');
            var $currentSale = $currentRow.find('.service-sale');
            var $currentTip= $currentRow.find('.service-tip');
            var currentSale = parseInt($currentSale.val());
            var currentTip = parseInt($currentTip.val());


            if(isNaN(currentSale)){
                $currentSale.val('0');
                currentSale = 0;
            }
            if(isNaN(currentTip)){
                $currentTip.val('0');
                currentTip = 0;
            }


            sales++;
            $currentRow.attr('id','sale'+sales);

            saleList['sale' + sales] = currentSale;
            tipList['sale' + sales] = currentTip;
            var numSale = 0;
            var grossSale = 0;
            for(var sale in saleList){
                if(saleList.hasOwnProperty(sale)){
                    grossSale += parseInt(saleList[sale]);
                    numSale++;
                }
            }
            var grossTip = 0;
            for(var tip in tipList){
                if(tipList.hasOwnProperty(tip)){
                    grossTip += parseInt(tipList[tip]);
                }
            }

            $sales.html('<h3>'+numSale+'</h3>');
            $grossSale.html('<h3>$ ' + grossSale+'</h3>');
            $grossTip.html('<h3>$ ' + grossTip+'</h3>');
        }


        $('.service-table tbody>tr:last').clone(true).insertAfter('.service-table tbody>tr:last');//add a new sale row
        $('.service-table tbody>tr:last').attr('id','pending');
        $('.service-table tbody>tr:nth-last-child(2) .add-sale').hide();
        $('.service-table tbody>tr:last .service-sale').val('');
        $('.service-table tbody>tr:last .service-tip').val('');
        $('.service-table tbody>tr:last .service-name').val('5');

        console.log(saleList);
        console.log(tipList);
    });
    $(document).on('click','.remove-sale',function(){

        var saleID = $(this).closest('tr').attr('id');
        if(saleID !== 'pendingSale'){
            delete saleList[saleID];
            delete tipList[saleID];

            var numSale = 0;
            var grossSale = 0;
            for (var sale in saleList) {
                if (saleList.hasOwnProperty(sale)) {
                    grossSale += parseInt(saleList[sale]);
                    numSale++;
                }
            }
            var grossTip = 0;
            for (var tip in tipList) {
                if (tipList.hasOwnProperty(tip)) {
                    grossTip += parseInt(tipList[tip]);
                }
            }


            $sales.html('<h3>' + numSale + '</h3>');
            $grossSale.html('<h3>$ ' + grossSale + '</h3>');
            $grossTip.html('<h3>$ ' + grossTip + '</h3>');
        }
        $(this).closest('tr').remove();
        $('.service-table tbody>tr:last-child .add-sale').show();


        console.log(saleList);
        console.log(tipList);
    });
    $submitSale.click(function(){
        var saleData = $saleEntryForm.serializeArray();
        console.log(saleData);
        $.ajax({
            type: 'post',
            url: '../php/Script_TechSaleEntry.php',
            data:saleData,
            dataType:'json'
        }).done(function(response){
            console.log(response);
            saleEntry.status = 'complete';
            $entryContainer.hide();
            $introContainer.show();
        });
    });
});
