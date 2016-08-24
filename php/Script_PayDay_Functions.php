<?php
$base = dirname(dirname(__FILE__));
require ($base. '/php/Class.PayDay.php');
/**
 * Created by PhpStorm.
 * User: tnguyen
 * Date: 8/23/2016
 * Time: 1:12 PM
 */
$payDay = new Payday();
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    $action = $_GET["action"];
    if($action == "getPayment"){
        $payment = $payDay->GetPayment($_GET["periodID"],$_GET["techID"]);
        print_r( json_encode($payment));
    }
}
else if ($_SERVER['REQUEST_METHOD'] === 'POST') {

}