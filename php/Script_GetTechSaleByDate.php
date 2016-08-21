<?php
    require_once "Connection.php";
    if(isset($_GET['saleDate'])){
        $saleDate = $_GET['saleDate'];
        $query = "SELECT t.technicianID AS techID, firstName, lastName, COALESCE(amount,0.00) AS amount,
            COALESCE(tip,0.00) AS tip FROM technicians AS t INNER JOIN usertype AS u ON t.userTypeID = u.userTypeID LEFT JOIN 
            sales AS s ON (t.`technicianID` = s.techID AND DATE(s.`saleDate`) = '" . $saleDate ."')
            WHERE u.usertypeID = 3 ORDER BY firstName ASC";

        $result = mysqli_query($link,$query);
        if(mysqli_num_rows($result) > 0){
            while($row = mysqli_fetch_assoc($result)){
                $techSale[] = array("techID" => $row["techID"],
                    "name" => $row['firstName'] . " " . $row['lastName'],
                    "sale" => $row['amount'], "tip" => $row['tip']);

            }
        }

        echo json_encode($techSale);
    }
