<?php
session_start();
if(isset($_COOKIE['username'])){
    $username = $_COOKIE['username'];
}
?>
<!DOCTYPE html>

<html lang="en">
<head>
    <meta name="Sugar Nails" content="Mobile Sign In" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" />
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <meta name="apple-mobile-web-app-status-bar-style" content="black" />
    <title>Sign In</title>
    <link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jquerymobile/1.4.5/jquery.mobile.min.css" />
    <link rel="stylesheet" href = "../style/mobile-custom-ui.css" />
    <link rel = "apple-touch-icon" sizes="114x114" href="/image/sugarnails_logo_only_114x114.png"/>
    <!--<link rel="stylesheet" href="../mobile-style/custom-style/custom-theme.min.css" />-->
    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquerymobile/1.4.5/jquery.mobile.min.js"></script>
    <script type="application/javascript" src= "../js/mobile-sugar-management.js"></script>
</head>
<body>
<div data-role = "page" id = "signin">
    <div data-role = "header"><h1>Sugar Nails</h1></div>
    <div data-role = "content">
        <div data-role = "ui-bar" style = "text-align: center;"><h3>Salon Management</h3></div>
        <form id = "signin-form" data-ajax = "false" action="">
            <div data-role = "fieldcontain">
                <input type = "text" id = "username-input" name = "username"  placeholder = "user name" autocapitalize = "none">
                <input type = "password" id = "password-input" name = "password" placeholder="password">
                <!--<input type = "checkbox" id = "rememberUserNameChk" name = "rememberUserName">-->
                <!--<label for = "rememberUserNameChk" style = "color: black;background-color: #f9f9f9;text-shadow: none;">Remember User Name</label>-->
                <div id = "signin-msg"></div>
            </div>
            <div data-role = "fieldcontain">
                <input type = "submit" name = "signin-btn" id = "signin-btn" value = "Sign In">
            </div>
        </form>
    </div>
</div>


</body>
</html>