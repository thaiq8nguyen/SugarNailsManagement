/**********************************************************************************************************************/
/*PAGE: signin.php*****************************************************************************************************/
/**********************************************************************************************************************/
$(document).on('pagecreate','#signin', function(){
    return false
});

$(document).on('pageshow','#signin',function(){
    var signinForm = $('#signin-form');
    signinForm.submit(function(event){
        event.preventDefault();
        event.stopImmediatePropagation();
        SignIn();
    });
});
/**********************************************************************************************************************/
/*PAGE: signin.php*****************************************************************************************************/
/**********************************************************************************************************************/

/**********************************************************************************************************************/
/*PAGE: dashboard.php**************************************************************************************************/
/**********************************************************************************************************************/
$(document).on('pageshow','#dashboard',function(){
    var signOut = $('#signout-btn');
    signOut.click(function(){
        SignOut();
    });
});
/**********************************************************************************************************************/
/*PAGE: dashboard.php**************************************************************************************************/
/**********************************************************************************************************************/

/**********************************************************************************************************************/
/*PAGE: sell-certificate.php*******************************************************************************************/
/**********************************************************************************************************************/
$(document).on('pagecreate','#sell-cert',function(){
    var customAmountForm = $('#custom-amount');
    customAmountForm.hide();
    $('#validCertNumberCheckBox').hide();
    $('#validAmountCheckBox').hide();
});
$(document).on('pageshow','#sell-cert',function(){
    var sellCertForm = $('#sell-certificate-form');
    var customAmountForm = $('#custom-amount');
    var customAmountBtn = $('#gift-custom-btn');
    var radioAmount = $('input:radio[name="amount"]');
    var certInput = $('#certificate-number-input');
    var amountInput = $('#amount-input');
    var signOut = $('#signout-btn');
    
    certInput.on('blur keydown',function(event){
        if(event.keyCode === 13 || event.type == 'blur'){
            validateCert(certInput.val(),'sale');
        }
    });

    customAmountBtn.click(function(){
        if(customAmountForm.is(':hidden')){
            $('#amount-input').val('');
            customAmountForm.show();
            radioAmount.each(function(){
                $('#validAmountCheckBox').prop('checked',false);
                $(this).prop('checked',false).checkboxradio('refresh');
            });
        }
        else{
            customAmountForm.hide();
        }
    });
    radioAmount.click(function(){
        customAmountForm.hide();
        amountInput.val($(this).val());
        $('#validAmountCheckBox').prop('checked',true);
    });
    amountInput.change(function(){
        if($(this).val() > 0 && $(this).val()<75){
            $('#validAmountCheckBox').prop('checked',true);
        }
        else{
            $('#validAmountCheckBox').prop('checked',false);
        }
    });
    sellCertForm.submit(function(event){
        event.preventDefault();
        SellCert();
    });
    signOut.click(function(){
        SignOut();
    });

});
$(document).on('pagebeforehide','#sell-cert',function(){
    resetSale();
});
/**********************************************************************************************************************/
/*PAGE: sell-certificate.php*******************************************************************************************/
/**********************************************************************************************************************/

$(document).on('pagecreate pageshow', '#redeem-certificate',function(){
    var $redeemMsg = $('#redeem-msg');
    var $redeemForm = $('#redeem-form');
    var signOut = $('#signout-btn');
    var responseMsg = '';

    $redeemForm.submit(function(event){
        event.preventDefault();
        event.stopImmediatePropagation();
        $.ajax({
            type: 'post',
            url: '../php/Script_RedeemCert.php',
            data:$redeemForm.serializeArray(),
            dataType:'json'
        }).done(function(response){
            if(response.status === 'success'){
                responseMsg = '<h3>Redeemed</h3><p>Original Balance : $ ' + response.originalBalance + '<br>' +
                        'Redeem Amount: $ ' + response.redeemAmount + '<br>' +
                        'New Balance: $ ' + response.newBalance + '</p>';
                $redeemMsg.html(responseMsg).addClass('sale-success');
            }
            else if(response.status === 'failure'){
                responseMsg = '<h3>Not Redeemed</h3><p>' + response.msg + '<br>' +
            'Balance: $ ' + response.balance + '</p>';
                $redeemMsg.html(responseMsg).addClass('sale-failure');
            }
            $redeemForm[0].reset();
        }).fail(function(jqXHR,textStatus){
            console.log(jqXHR+' '+textStatus);
        });
    });
    signOut.click(function(event){
        SignOut();
    })

});
$(document).on('pagebeforehide','#redeem-certificate',function(){
    resetRedeem();
});
/*START PAGE: CERTIFICATE-BALANCE.PHP**********************************************************************************/
$(document).on("pagecreate pageshow", "#certificate-balance",function(){
    var $balanceLookUpForm = $('#balance-lookup');
    var $balanceMsg = $('#show-balance');
    var signOut = $('#signout-btn');
    var $responseMsg = '';

    $balanceLookUpForm.submit(function(event){
        event.preventDefault();
        event.stopImmediatePropagation();
        $.ajax({
            type: 'get',
            url: '../php/Script_GetCertificateBalance.php',
            data: $balanceLookUpForm.serializeArray(),
            dataType:'json'
        }).done(function(response){
            if(response.status === 'success'){
                $responseMsg = '<p>Certificate #: ' + response.certNum + '</br>' +
                    'Balance : $ ' + response.balance + '</br>' +
                        'Sold Date: ' + response.soldDate + '</p>';
                $balanceMsg.empty().html($responseMsg).addClass('sale-success');
                $('#balance-lookup')[0].reset();
            }
            else if(response.status === 'failure'){
                $responseMsg = '<h3>Error</h3><p>' + response.msg + '</p>';
                $balanceMsg.empty().html($responseMsg).addClass('sale-failure');
            }
        }).fail(function(jqXHR,textStatus){
            console.log(jqXHR+' '+textStatus);
        });
    });
    signOut.click(function(){
        SignOut();
    });
});
$(document).on('pagebeforehide','#certificate-balance',function(){
    resetBalance();
});
/*END PAGE: CERTIFICATE-BALANCE.PHP**********************************************************************************/
/*BEGIN FUNCTION validateSubmission()**********************************************************************************/
function validateSaleData(){
    var isValid;

    if($('#validCertNumberCheckBox').is(':checked')){
        isValid = true;
        $('.cert-validate-msg').empty();
    }
    else{
        isValid = false;
        $('.cert-validate-msg').empty().text('Invalid certificate and cannot be sold').css("color","#d91e18");
    }

    if($('#validAmountCheckBox').is(':checked')){
        isValid = true;
        $('.amount-validate-msg').empty();
    }
    else{
        isValid = false;
        $('.amount-validate-msg').empty().text('Invalid amount').css("color","#d91e18");
    }

    if($('#validCertNumberCheckBox').is(':checked') && $('#validAmountCheckBox').is(':checked')){
        isValid = true;
    }
    else{
        isValid = false;
    }
    return isValid;
}
/*END FUNCTION validateSubmission()************************************************************************************/
/*BEGIN FUNCTION verifyCert(certNumber)********************************************************************************/
function validateCert(certNumber,validation){
    $.ajax({
        type: 'get',
        url: '../php/Script_ValidateCert.php',
        data:{certificate:certNumber,validation:validation},
        dataType: 'json'
    }).done(function(result){
        if(result){
            if(result.status === 'valid'){
                $('.cert-validate-msg').empty().text(result.msg).css("color","#26a65b").show();
                $('#validCertNumberCheckBox').prop('checked',true).checkboxradio('refresh');
                $('#certID').val(result.certID);
            }
            else if(result.status === 'invalid'){
                $('.cert-validate-msg').empty().text(result.msg).css("color","#d91e18").show();
                $('#validCertNumberCheckBox').prop('checked',false).checkboxradio('refresh');

            }
        }
        else{
            $('.cert-validate-msg').empty().text(result.msg).css("color","#d91e18");
            $('#validCertNumber').prop('checked',false).checkboxradio('refresh');
        }
    }).fail(function(jqXHR,textStatus){
        console.log('fail');
    })
}
/*BEGIN FUNCTION sellCert(certNumber,amount)***************************************************************************/
function SellCert(){
    var sellCertificateForm = $('#sell-certificate-form');
    var $saleStatus = $('#sale-status');
    var message = '';
    if(validateSaleData()){
        $.ajax({
            type: 'post',
            url: '../php/Script_SellCertificate.php',
            data: sellCertificateForm.serializeArray(),
            dataType: 'json'
        }).done(function(response){
            if(response.status === 'success'){
                //Display certificate has been sold
                message = "<h3>Sold</h3><p>Certificate #: " + response['certNumber'] + "<br><br> Balance: $ "
                    + response['balance'] + "</p>";
                $saleStatus.html(message).addClass('ui-body ui-body-a sale-success');
                resetSale();
            }else if(response.status === 'failure'){
                //Display certificate has not been sold
                message = "<h3>Not Sold</h3><p>The certificate cannot be sold</p>";
                $saleStatus.html(message).addClass('ui-body ui-body-a sale-failure');
                resetSale();
            }

        }).fail(function(jqXHR, textStatus){
            console.log(jqXHR + ' ' + textStatus);
        });
    }

}
/*END FUNCTION sellCert(certNumber,amount)**************************************************************************************/
/*BEGIN SignIn Function()**********************************************************************************************/
function SignIn(){
    var signInForm = $("#signin-form");
    var msg = $("#signin-msg");

    $.ajax({
        type: "post",
        url: "../php/UserAuthentication.php",
        data: signInForm.serializeArray(),
        dataType: "text",
        success: function(response){

            if($.trim(response) === "pass"){
                signInForm[0].reset();
                $("body").pagecontainer("change","dashboard.php",{transition:"slide"});
            }
            else{
                signInForm[0].reset();
                msg.empty().append("Your username or password is incorrect").css("color","red");
            }
        },
        error: function(request,error){
            console.log("Request: "+request+"/Error: "+error);
        }
    });
}
/*END SignIn Function()************************************************************************************************/
/*BEGIN SignOut Function()*********************************************************************************************/
function SignOut(){
    $.ajax({
        type: "post",
        url: "../php/Script_SignOut.php",
        dataType: "text"
    }).done(function(response){
           window.location.href = ("../mobile/signin.php");
    }).fail(function(jqXHR, textStatus){
        console.log(jqXHR + ' ' + textStatus);
    });
}
/*END SignOut Function()***********************************************************************************************/
/*BEGIN sellCertificateForm Reset()************************************************************************************/
function resetSale(){
    var $sellForm = $('#sell-certificate-form');
    var $validateCertNumber = $('.cert-validate-msg');
    var $validateAmount = $('.amount-validate-msg');
    var $customAmount = $('#custom-amount');
    var $saleStatus = $('#sale-status')

    if($validateCertNumber.is(':visible')){
        $validateCertNumber.hide();
    }
    if($validateAmount.is(':visible')){
        $validateAmount.hide();
    }
    if($customAmount.is(":visible")){
        $customAmount.hide();
    }

    $sellForm[0].reset();
    $saleStatus.empty();
}
/*END sellCertificateForm Reset()**************************************************************************************/
function resetRedeem(){
    $('.redeem-msg').empty();
    $('#redeem-form')[0].reset();
}
function resetBalance(){
    $('#balance-lookup')[0].reset();
    $('#show-balance').empty();
}