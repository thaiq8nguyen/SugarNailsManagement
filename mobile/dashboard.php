<?php
    session_start();
    if(!isset($_SESSION['userID'])){
        header('Location:signin.php');
        die();
    }

?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" lang="en">
<head>
    <meta name="Sugar Nails" content="Mobile Dashboard" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" />
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <meta name="apple-mobile-web-app-status-bar-style" content="black" />
    <title>Dashboard</title>
    <link rel="stylesheet" href="//ajax.googleapis.com/ajax/libs/jquerymobile/1.4.3/jquery.mobile.min.css" />
    <link rel="stylesheet" href = "../style/mobile-custom-ui.css" />
    <script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
    <script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquerymobile/1.4.3/jquery.mobile.min.js"></script>
    <script type="application/javascript" src= "../js/mobile-sugar-management.js"></script>
</head>
<body>
    <div data-role = "page" id = "dashboard">
        <div data-role = "header" data-position = "fixed"><h1>Dashboard</h1>
            <a href = "#" id = "signout-btn" class = "ui-btn-right">Sign Out</a>
        </div>
        <div data-role = "content">
            <h3 class = "ui-bar ui-bar-a">News</h3>
            <div class = "ui-body">
                <p>What you can do in the dashboard</p>
                <ul>
                    <li>Issue or redeem gift certificate</li>
                    <li>Plus many more features available in the future</li>
                </ul>
            </div>

            <div class = "ui-grid-a">
                <div class = "ui-block-a"><h4 class = "ui-bar ui-bar-a">Gift Certificates</h4></div>
                <div class = "ui-block-a"><a href = "../gift-certificate/sell-certificate.php" id = "open-sell-cert-page" class = "ui-btn ui-corner-all" data-transition = "slide">Sell </a></div>
                <div class = "ui-block-a"><a href = "../gift-certificate/redeem-certificate.php" id = "open-redeem-cert-page" class = "ui-btn ui-corner-all" data-transition = "slide">Redeem</a></div>
                <div class = "ui-block-a"><a href = "../gift-certificate/certificate-balance.php" id = "open-check-balance-page" class = "ui-btn ui-corner-all" data-transition = "slide">Balance</a></div>
            </div>
        </div>
        <div data-role = "footer" data-position = "fixed"></div>
    </div>
</body>
</html>

