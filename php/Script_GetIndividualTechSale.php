<?php
require "Connection.php";

    if(isset($_GET["techID"]) && isset($_GET["saleDate"])) {

        $technicianID = $_GET["techID"];
        $saleDate = $_GET['saleDate'];


        $query = "SELECT saleID  ,COALESCE(amount,0.00) AS amount," .
            "COALESCE(tip, 0.00) AS tip,commissionRate, tipRate, " .
            "COALESCE(CAST((amount * commissionRate) AS DECIMAL(3,2)),0.00) AS saleEarning, " .
            "COALESCE(CAST((tip * tipRate) AS DECIMAL(3,2)),0.00) AS tipEarning " .
            "FROM technicians AS t LEFT JOIN sales AS s ON t.`technicianID` = s.`technicianID` " .
            "AND DATE(saleDate) = '" . $saleDate . "' " .
            "WHERE t.technicianID = " . $technicianID;


        $result = mysqli_query($link, $query);

        if ($result) {


            $row = mysqli_fetch_assoc($result);
            if(($saleID = $row['saleID']) == null){
                $saleID = "nosale";
            }
            else{
                $saleID = $row['saleID'];
            }

            $sale = $row['amount'];
            $tip = $row['tip'];
            $saleEarning = $row['saleEarning'];
            $tipEarning = $row['tipEarning'];
            $techSale = array("saleID" => $saleID, "sale" => $sale, "cctip" => $tip,
                "saleEarning" => $saleEarning, "tipEarning" => $tipEarning);


        }
        #print_r($techSale);
        echo json_encode($techSale);
    }
