<?php
$base = dirname(dirname(__FILE__));
require ($base. '/php/Class.DailySale.php');

    //GET METHOD
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        $action = $_GET["action"];
        if( $action === "getTechDailySale"){

            $sale = GetTechDailySale($_GET["techID"],$_GET["saleDate"]);
            echo json_encode($sale);
        }
        elseif($action == "getTotalSaleAndTip"){
            $sale = GetTotalSaleAndTip($_GET["saleDate"]);
            echo json_encode($sale);
        }
        elseif($action == "getAllTechDailySale"){
            $sale = GetAllTechDailySale($_GET["saleDate"]);
            echo json_encode($sale);
        }
    }//POST METHOD
    elseif($_SERVER['REQUEST_METHOD'] === 'POST'){
        $action = $_POST['action'];
        if($action == "setTechSale"){
            $sale = SetTechSale($_POST["techID"],$_POST{"sale"},$_POST["tip"],$_POST["saleDate"]);
            echo json_encode($sale);

        }
        else if($action == "updateTechSale"){
            $sale = UpdateTechSale($_POST["saleID"],$_POST["sale"],$_POST["tip"],$_POST["saleDate"]);
            echo $sale;
        }
        else if($action == "deleteTechSale"){
            $sale = DeleteTechSale($_POST["saleDate"],$_POST["saleID"]);
            echo $sale;
        }
    }





    function GetTechDailySale($techID,$saleDate){
        $dailySale = new DailySale($saleDate);
        $sale =  $dailySale->GetTechDailySale($techID);
        return $sale;

    }
    function GetAllTechDailySale($saleDate){
        $dailySale = new DailySale($saleDate);
        $sale = $dailySale->GetAllTechDailySale();
        return $sale;
    }
    function UpdateTechSale($saleID, $sale, $tip, $saleDate){
        $dailySale = new DailySale($saleDate);
        $sale =  $dailySale->UpdateTechSale($saleID,$sale,$tip);
        return $sale;
    }
    function GetTotalSaleAndTip($saleDate){
        $dailySale = new DailySale($saleDate);
        $totalSale = $dailySale->GetTotalSaleAndTip();
        return $totalSale;
    }

    function SetTechSale($techID,$sale,$tip,$saleDate){
        $dailySale = new DailySale($saleDate);
        $sale = $dailySale->SetTechSale($techID,$sale,$tip);
        return $sale;
    }

    function DeleteTechSale($saleDate,$saleID){
        $dailySale = new DailySale($saleDate);
        $sale = $dailySale->DeleteTechSale($saleID);
        return $sale;
    }