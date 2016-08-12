<?php
/**
 * Created by PhpStorm.
 * User: tnguyen
 * Date: 8/2/2016
 * Time: 2:59 PM
 */

    $base = dirname(dirname(__FILE__));
    require ($base.'/php/Connection.php');
    require ($base.'/php/WS_SquareTotalSale.php');



    $today = date('2016-08-13');

    $date = new DateTime($today);
    $date->modify('-1 day');
    $yesterday = $date->format('Y-m-d');

    $payments = getTodayPayment(getLocationIds(),$yesterday,$today);
    $sale = getTodaySale($payments);

    $grossSale = $sale['grossSale'];
    $grossTip = $sale['grossTip'];
    $cashPayment = $sale['cash'];
    $creditCardPayment = $sale['creditCard'];

    $now = date('Y-m-d H:i:s');

    $query = "INSERT INTO fromSquare_DailySale(saleDate,grossSale,grossTip,cashPayment,creditCardPayment,lastUpdate) 
        VALUES('" . $yesterday . "'," . $grossSale . "," . $grossTip . ","
        . $cashPayment ."," . $creditCardPayment . ",'" . $now . "')";
    $result = mysqli_query($link,$query);

    /*
    if($result){
        //Email admin@sugarnailspa.com
    }
    */

