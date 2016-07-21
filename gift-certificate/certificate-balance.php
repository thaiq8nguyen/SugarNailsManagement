<?php
/**
 * Created by PhpStorm.
 * User: tnguyen
 * Date: 7/2/2016
 * Time: 8:52 AM
 */

session_start();
if(!isset($_SESSION['userID'])) {
    header('Location:signin.php');
    die();
}
?>

<!DOCTYPE html>
<html lang = "en">
    <head>
        <meta name="Sugar Nails" content="Certificate Balance" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" />
        <meta name="apple-mobile-web-app-capable" content="yes" />
        <meta name="apple-mobile-web-app-status-bar-style" content="black" />
        <title>Certificate Balance</title>
        <link rel="stylesheet" href="//ajax.googleapis.com/ajax/libs/jquerymobile/1.4.5/jquery.mobile.min.css" />
        <link rel="stylesheet" href = "../style/mobile-custom-ui.css" />
        <script type="application/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
        <script type="application/javascript" src="//ajax.googleapis.com/ajax/libs/jquerymobile/1.4.5/jquery.mobile.min.js"></script>
        <script type="application/javascript" src= "../js/mobile-sugar-management.js"></script>
    </head>
    <body>
        <div data-role = "page" id = "certificate-balance">
            <div data-role = "header" data-position = "fixed">
                <h1>Certificate Balance</h1>
                <a href = "#" id = "signout-btn" class = "ui-btn-right">Sign Out</a>
                <a href = "#" id = "back-btn" class = "ui-btn-left back-btn" data-rel = "back" data-transition = "slide">Back</a>
            </div>
            <div data-role = "content" class = "ui-content">
                <form id = "balance-lookup" data-ajax = "false" action = "#">
                    <label for = "certificate-number-input">Certificate #:</label>
                    <input type = "text" id = "certificate-number-input" name = "certificate-number-input">
                    <input type = "submit" id = "balance-lookup-btn" name = "balance-lookup-btn" value = "Look Up">
                </form>
                <div id = "show-balance">
                </div>
            </div>
            <div data-role = "footer" data-position = "fixed">
            </div>

        </div>
    </body>
</html>
