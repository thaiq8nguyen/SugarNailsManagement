<?php

    require "Connection.php";
    if(isset($_POST['saleID']) &&  isset($_POST['sale']) && isset($_POST['cctip'])) {

        $saleID = $_POST['saleID'];
        $sale = $_POST['sale'];
        $cctip = $_POST['cctip'];

        $query = "UPDATE sales SET serviceID = 6, saleDate = CONVERT_TZ(UTC_TIMESTAMP(),'UTC','US/Pacific'), " .
            "amount = '" . $sale . "', tip = '" . $cctip . "' WHERE saleID = " . $saleID;

        $result =  mysqli_query($link, $query);

        if($result){
            $response = "success";
        }
        else{
            $response = "failure";
        }

        echo($response);
    }

