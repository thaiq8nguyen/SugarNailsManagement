<?php
    $base = dirname(dirname(__FILE__));
    require ($base . "/php/Connection.php");


    $payPeriod = $_GET['payPeriod'];
    $techID = $_GET['techID'];
    $fromDate = substr($payPeriod,0,-13);
    $toDate = substr($payPeriod,13);





    $wage = '';
    $query = "SELECT COALESCE(s.saleID,0) AS saleID,CONCAT(DATE(fS.saleDate),' - ',DATE_FORMAT(fS.saleDate,'%a')) AS workDay,
            COALESCE(amount,0.00) AS Sale, COALESCE(tip,0.00) AS Tip FROM fromSquare_DailySale AS fS 
            LEFT OUTER JOIN sales AS s ON DATE(fS.saleDate)= DATE(s.saleDate) " .
        "AND s.technicianID = " . $techID . " WHERE fS.saleDate BETWEEN '" . $fromDate . "' AND '" .$toDate . "'" ;

    $result = mysqli_query($link, $query);
    if(mysqli_num_rows($result) > 1){

        while($row = mysqli_fetch_assoc($result)){
            $wage[] =  array("saleID" => $row['saleID'],"workDay" => $row["workDay"],
                "sale" => $row["Sale"], "tip" => $row["Tip"]);
        }
    }

    echo json_encode($wage);




