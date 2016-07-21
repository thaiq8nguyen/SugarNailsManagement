<?php
require "Connection.php";
    if(isset($_GET['saleDate'])){

        $saleDate = $_GET['saleDate'];

        $query = "SELECT COALESCE(SUM(amount),0.00) AS totalSale, COALESCE(SUM(tip),0.00) AS totalTip FROM sales " .
            "WHERE DATE(saleDate) = '" . $saleDate . "'";

        $result = mysqli_query($link,$query);
        if(mysqli_num_rows($result) > 0){
            while($row = mysqli_fetch_assoc($result)){
                $grossSale = array("grossSale" => $row['totalSale'], "grossTip" => $row['totalTip']);
            }
        }

        echo json_encode($grossSale);
    }
