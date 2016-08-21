<?php
    $base = dirname(dirname(__FILE__));
    require($base . "/php/Class.PayDay.php");

    $payPeriodID = $_GET["payPeriodID"];
    $techID = $_GET["techID"];
    $payData = new Payday();
    $sale = $payData->GetDailySale($payPeriodID,$techID);

    echo json_encode($sale);




