<?php
require "Connection.php";

    $fromDate = $_GET["fromDate"];
    $toDate = $_GET["toDate"];
    $payPeriod = $fromDate . " - " . $toDate;

    $earning = '';

    $query = "SELECT t.technicianID AS technicianID,CONCAT(firstName,' ',lastName) AS technician ," .
    "SUM(amount) AS grossSale, SUM(tip) AS grossTip, " .
    "CAST((t.commissionRate * SUM(amount)) AS DECIMAL(6,2)) AS saleEarning, " .
    "CAST((t.`tipRate` * SUM(tip)) AS DECIMAL(6,2)) AS tipEarning, " .
    "CAST((t.commissionRate * SUM(amount) + t.`tipRate` * SUM(tip)) AS DECIMAL(6,2)) AS totalEarning, ".
    "IF(ISNULL(account.`paymentID`),'Pending','Paid') AS payStatus " .
    "FROM technicians AS t LEFT JOIN sales AS s " .
    "ON t.`technicianID` = s.`technicianID` LEFT JOIN techaccount AS account ON t.technicianID = account.techID " .
        "WHERE DATE(saleDate) " .
    "BETWEEN CAST('" . $fromDate . "' AS DATE) AND CAST('" . $toDate . "' AS DATE) " .
    "GROUP BY t.firstName,t.lastName ORDER BY t.firstName ";

    $result = mysqli_query($link,$query);



    if($result){
        while($row = mysqli_fetch_assoc($result)){

            $earning[] = array("techID" => $row['technicianID'],"technician" => $row['technician'],
                "grossSale" => $row['grossSale'], "grossTip" => $row['grossTip'],
                "saleEarning" => $row['saleEarning'], "tipEarning" => $row['tipEarning'],
                "totalEarning" => $row['totalEarning'], "payStatus" => $row["payStatus"]);
        }

    }
    echo json_encode($earning);
