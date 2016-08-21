<?php
    $base = dirname(dirname(__FILE__));
    require($base . "/php/Class.PayDay.php");

    $payPeriodID = $_GET["payPeriodID"];

    $payDay = new Payday();

    $wage = $payDay->GetGrossSaleAndEarning($payPeriodID);

    echo (json_encode($wage));