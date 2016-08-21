<?php
    $base = dirname(dirname(__FILE__));
    require($base . "/php/Class.PayDay.php");


    $payment = $_POST["payment"];
    $payPeriod = $_POST["payPeriod"];

    $payDay = new Payday();
    $response = $payDay->Pay($payment,$payPeriod);

    echo json_encode($response);




















