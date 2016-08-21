<?php
/**
 * Created by PhpStorm.
 * User: tnguyen
 * Date: 4/25/2016
 * Time: 11:47 AM
 */

$base = dirname(dirname(__FILE__));
require($base . '/php/Class.PayDay.php');

    $payDay = new Payday();
    $payPeriod = $payDay->GetActivePayPeriod();

    echo json_encode($payPeriod);

