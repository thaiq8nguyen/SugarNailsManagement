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

    $today = date('Y-m-d');

    $date = new DateTime($today);
    $date->modify('+1 day');
    $tomorrow= $date->format('Y-m-d');

    $payments = getTodayPayment(getLocationIds(),$today,$tomorrow);
    $sale = getTodaySale($payments);

    $grossSale = $sale['grossSale'];
    $grossTip = $sale['grossTip'];
    $cashPayment = $sale['cash'];
    $creditCardPayment = $sale['creditCard'];

    $now = date('Y-m-d H:i:s');

    $query = "SELECT id from fromSquare_DailySale WHERE DATE(saleDate) = '" . $today."'";


    if($result = mysqli_query($link,$query)){
        if(mysqli_num_rows($result) == 0)  {
            $query = "INSERT INTO fromSquare_DailySale(saleDate,grossSale,grossTip,cashPayment,creditCardPayment,lastUpdate) 
            VALUES('" . $today . "'," . $grossSale . "," . $grossTip . ","
                . $cashPayment ."," . $creditCardPayment . ",'" . $now . "')";
            $result = mysqli_query($link,$query);
        }
        else if(mysqli_num_rows($result) == 1){
            $detail = mysqli_fetch_assoc($result);
            $id = $detail['id'];
            $query = "UPDATE fromSquare_DailySale SET grossSale = " . $grossSale . ", grossTip = " . $grossTip .
                ", cashPayment = " . $cashPayment . ", creditCardPayment = " . $creditCardPayment .
                ",lastUpdate = '" . $now . "' WHERE id = " . $id;

            $result = mysqli_query($link, $query);


        }
    }




    /*
    if($result){
        //Email admin@sugarnailspa.com
    }
    */

