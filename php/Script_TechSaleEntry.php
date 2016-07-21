<?php
    require "Connection.php";

    if(isset($_POST['techID']) &&  isset($_POST['sale']) &&
        isset($_POST['cctip']) && isset($_POST['saleDate'])){


        $techID = $_POST['techID'];
        $sale = $_POST['sale'];
        $cctip = $_POST['cctip'];
        $saleDate = $_POST['saleDate'];


        mysqli_autocommit($link,false);
        $isValid = true;

        $saleQuery = "INSERT INTO sales(technicianID,serviceID, saleDate, amount,tip) " .
            "VALUES(" . $techID . ",6,'" . $saleDate . "','".$sale."','" . $cctip . "')";

        $result = mysqli_query($link,$saleQuery);
        if(!$result){
            $isValid = false;
        }
        else{
            $saleID = mysqli_insert_id($link);
            $wageQuery = "INSERT INTO wages(technicianID,saleID) " .
                "VALUES(". $techID. "," . $saleID . ")";
            $result = mysqli_query($link,$wageQuery);
            if(!$result){
                $isValid = false;
            }
        }

        if($isValid){
            mysqli_commit($link);
            $response = "success";
        }
        else{
            mysqli_rollback($link);
            $response = "failure";
        }
    }

    #print_r($response);
    echo ($response);
