<?php
    session_start();
    if(!isset($_SESSION['userID'])){
        header('Location:signin.php');
        die();
    }

?>
<!DOCTYPE html>
<html>
<head>
    <meta name="Sugar Nails" content="Sell Certificate" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" />
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <meta name="apple-mobile-web-app-status-bar-style" content="black" />
    <title>Sell Certificate</title>
    <link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jquerymobile/1.4.5/jquery.mobile.min.css">
    <link rel="stylesheet" href = "../style/mobile-custom-ui.css" />
    <script type = "application/javascript" src = "https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
    <script type = "application/javascript" src = "https://ajax.googleapis.com/ajax/libs/jquerymobile/1.4.5/jquery.mobile.min.js"></script>
    <script type = "application/javascript" src= "../js/mobile-sugar-management.js"></script>
</head>
<body>
<div data-role = "page" id = "sell-cert">
    <div data-role = "header" data-position = "fixed"><h1>Sell Certificate</h1>
        <a href = "#" id = "signout-btn" class = "ui-btn-right">Sign Out</a>
        <a href = "#" id = "back-btn" class = "ui-btn-left back-btn" data-rel = "back" data-transition = "slide">Back</a>
    </div>
    <div data-role = "content">
        <div class = "ui-grid-a">
            <div class = "ui-block-a">
                <!--<h3 class = "ui-bar ui-bar-a">Sale</h3>-->
                    <div class = "ui-body">
                        <!--<ol>
                            <li>Enter or scan a gift certificate number</li>
                            <li>Select an amount or click 'Custom Amount'</li>
                            <li>Click 'Sell' button</li>
                        </ol>-->
                        <form id = "sell-certificate-form" data-ajax = "false">
                            <input = "number" id = "certificate-number-input"
                            name = "certificate-number-input" placeholder = "Enter a certificate #">
                            <p class = "cert-validate-msg"></p>
                            <h4 class = "ui-bar ui-bar-a"> Select an amount</h4>
                                <div class = "ui-grid-a">
                                    <div class = "ui-block-a">
                                        <fieldset data-role = "controlgroup">
                                            <input type="radio" name="amount" id = "gift5" value = "5">
                                            <label for="gift5">$ 5</label>
                                            <input type="radio" name="amount" id = "gift15" value = "15">
                                            <label for="gift15">$ 15</label>
                                            <input type="radio" name="amount" id = "gift25" value = "25">
                                            <label for="gift25">$ 25</label>
                                        </fieldset>
                                    </div>
                                    <div class = "ui-block-b">
                                        <fieldset data-role = "controlgroup">
                                            <input type="radio" name="amount" id = "gift10" value = "10">
                                            <label for="gift10">$ 10</label>
                                            <input type="radio" name="amount" id = "gift20" value = "20">
                                            <label for="gift20">$ 20</label>
                                            <input type="radio" name="amount" id = "gift50" value = "50">
                                            <label for="gift50">$ 50</label>
                                        <fieldset>
                                    </div>
                                </div>
                            <div class = "ui-grid-solo"><a href="#" id = "gift-custom-btn" class = "ui-btn ui-btn-corner all">Custom Amount</a></div>
                            <div id = "custom-amount" class = "fieldcontain">
                                <label for = "amount-input" class = "ui-hidden-accessible">Amount ($):</label>
                                <input type = "number" name = "amount-input" id = "amount-input" min = "5" step = "1" max = "75"
                                       placeholder = "Enter Custom Amount">
                            </div>
                            <p class = "amount-validate-msg"></p>

                            <input type = "submit" id = "sell-certificate-btn" value = "Sell">

                            <input type = "hidden" name = "certID" id = "certID" value ="">
                            <input type = "checkbox" id = "validCertNumberCheckBox">
                            <p><input type = "checkbox" id = "validAmountCheckBox"></p>
                        </form>

                    </div>
            </div>
            <div class = "ui-block-b">
                    <div id = "sale-status"></div>
            </div>
        </div>
    </div>
    <div data-role = "footer" data-position = "fixed"><h4></h4></div>
</div>


</body>
</html>
