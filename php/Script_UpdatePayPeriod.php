<?php
$base = dirname(dirname(__FILE__));
require($base . '/php/Connection.php');
$date = new DateTime();

$payPeriod = GetPayPeriod(GetBeginPay($date));

UpdatePayPeriodTable($payPeriod);

function GetBeginPay($date){

    //If date is less than 14
    if($date->format("d") < 14){
        $date->setDate($date->format("Y"),$date->format("m"),14);
    }
    //If date is greater than 14
    elseif($date->format("d") > 14 && $date->format("d") < 29){
        $date->setDate($date->format("Y"),$date->format("m"),29);
    }
    elseif($date->format("d") == 30 || $date->format("d") == 31){
        $date->setDate($date->format("Y"),$date->format("m")+1,14);
    }
    return $date;
}

function GetPayPeriod($date){
    $payPeriod = '';
    $endPay = '';
    $endDate = clone $date;
    $endDate = $endDate->modify('+1 month');

    while($date < $endDate){
        $beginPay = $date->format("Y-m-d");

        if($date->format("d") == 14){
            $date = $date->modify("+14 days");
            $endPay = $date->format("Y-m-d");
        }
        elseif($date->format("d") == 29 && $date->format("t") == 31) {

            $date = $date->modify("+15 days");
            $endPay = $date->format("Y-m-d");
        }
        elseif($date->format("d") == 29 && $date->format("t") == 30){

            $date = $date->modify("+14 days");
            $endPay = $date->format("Y-m-d");

        }
        //handling leap year
        elseif($date->format("d") == 29 && $date->format("L") == 1){
            $date = $date->modify("+13 days");
            $endPay = $date->format("Y-m-d");
        }
        elseif($date->format("d") == 1 && $date->format("L") == 0){
            $date = $date->modify("+12 days");
            $endPay = $date->format("Y-m-d");

        }
        $date2 = clone $date;
        $date2 = $date2->modify('+2 days');
        $payDate = $date2->format("Y-m-d");
        $date = $date->modify("+1 day");

        $payPeriod[] = array("payDate" => $payDate, "payPeriod" => $beginPay . " - " . $endPay, "active"=>1 );
    }
    return $payPeriod;
}
function UpdatePayPeriodTable($payPeriod){
    global $link;

    foreach($payPeriod as $period){
        $query = "INSERT INTO payperiod(payDate,period) 
          VALUES('" . $period["payDate"] . "','" . $period["payPeriod"] ."')";
        $result = mysqli_query($link,$query);
    }
    
}






















