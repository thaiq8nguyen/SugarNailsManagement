<?php
/**
 * Created by PhpStorm.
 * User: tnguyen
 * Date: 8/9/2016
 * Time: 9:20 AM
 */
    $base = dirname(dirname(__FILE__));
    require ($base.'/php/Class.PayDay.php');

    $payPeriod = $_GET["payPeriod"];
    $techID = $_GET["techID"];

    $payDay = new Payday();
    $payDay->GetPayReport($payPeriod,$techID);













