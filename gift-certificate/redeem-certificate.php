<?php
session_start();
if(!isset($_SESSION['userID'])){
    header('Location:signin.php');
    die();
}
?>
<!DOCTYPE html>
<html lang = "en">
<head>
    <meta name="Sugar Nails" content="Redeem Certificate" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" />
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <meta name="apple-mobile-web-app-status-bar-style" content="black" />
    <title>Redeem Certificate</title>
    <link rel="stylesheet" href="//ajax.googleapis.com/ajax/libs/jquerymobile/1.4.5/jquery.mobile.min.css" />
    <link rel="stylesheet" href = "../style/mobile-custom-ui.css" />
    <script type="application/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
    <script type="application/javascript" src="//ajax.googleapis.com/ajax/libs/jquerymobile/1.4.5/jquery.mobile.min.js"></script>
    <script type="application/javascript" src= "../js/mobile-sugar-management.js"></script>
</head>
<body>
<div data-role = "page" id = "redeem-certificate">
    <div data-role = "header" data-position = "fixed"><h1>Redeem Certificate</h1>
        <a href = "#" id = "signout-btn" class = "ui-btn-right">Sign Out</a>
        <a href = "#" id = "back-btn" class = "ui-btn-left" data-rel = "back" data-transition = "slide">Back</a>
    </div>
    <div data-role = "content">
        <div class = "ui-grid-a">
            <div class = "ui-block-a">
                <form id = "redeem-form" data-ajax = "false" action = "#">
                    <fieldset>
                        <label for = "certificate-number-input">Certificate #:</label>
                        <input type = "text" id = "certificate-number-input" name = "cert-input">
                        <p class = "cert-validate-msg"></p>
                        <label for = "amount-input">Redeem Amount ($):</label>
                        <input type = "number" id = "amount-input" name = "amount-input">
                    </fieldset>
                    <fieldset>
                        <input type = "submit" id = "redeem-cert-btn" data-wrapper-class = "submit-btn" value = "Redeem">
                    </fieldset>
                    <input type = "hidden" id = "cert-id" name = "cert-id">
                </form>
                <div id = "redeem-msg">
                </div>
            </div>
        </div>
    </div>
    <div data-role = "footer" data-position = "fixed"></div>
</div>
</body>
</html>



