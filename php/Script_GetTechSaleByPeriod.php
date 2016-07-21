<?php
require "Connection.php";

    $fromDate = $_GET["fromDate"];
    $toDate = $_GET["toDate"];



    $query = "SELECT t.technicianID AS technicianID,CONCAT(firstName,' ',lastName) AS technician ," .
    "SUM(amount) AS grossSale, SUM(tip) AS grossTip, " .
    "CAST((t.commissionRate * SUM(amount)) AS DECIMAL(6,2)) AS saleEarning, " .
    "CAST((t.`tipRate` * SUM(tip)) AS DECIMAL(6,2)) AS tipEarning, " .
    "CAST((t.commissionRate * SUM(amount) + t.`tipRate` * SUM(tip)) AS DECIMAL(6,2)) AS totalEarning " .
    "FROM technicians AS t LEFT JOIN sales AS s " .
    "ON t.`technicianID` = s.`technicianID` WHERE DATE(saleDate) " .
    "BETWEEN CAST('" . $fromDate . "' AS DATE) AND CAST('" . $toDate . "' AS DATE) " .
    "GROUP BY t.firstName,t.lastName ORDER BY t.firstName ";

    $result = mysqli_query($link,$query);

    if($result){
        while($row = mysqli_fetch_assoc($result)){

            $earning[] = array("techID" => $row['technicianID'],"technician" => $row['technician'],
                "grossSale" => $row['grossSale'], "grossTip" => $row['grossTip'],
                "saleEarning" => $row['saleEarning'], "tipEarning" => $row['tipEarning'],
                "totalEarning" => $row['totalEarning']);
        }

    }
    echo json_encode($earning);
