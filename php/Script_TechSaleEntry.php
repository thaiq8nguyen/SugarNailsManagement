<?php
    require "Connection.php";

    if(isset($_POST['techID']) &&  isset($_POST['sale']) &&
        isset($_POST['cctip']) && isset($_POST['saleDate'])){


        $techID = $_POST['techID'];
        $sale = $_POST['sale'];
        $cctip = $_POST['cctip'];
        $saleDate = $_POST['saleDate'];
        $response = "";

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
            $response = array("status" => "success", "techName" => GetTechDetail($techID), "sale" => $sale,
                "cctip" => $cctip);
        }
        else{
            mysqli_rollback($link);
            $response = array("status" => "failure");
        }
    }

    //print_r($response);
    echo json_encode($response);

    function GetTechDetail($techID){
        $link = $GLOBALS['link'];

        $selectTech =  mysqli_query($link,"SELECT * FROM technicians WHERE technicianID = " .$techID);
        $techDetail = mysqli_fetch_assoc($selectTech);

        $name = $techDetail['firstName'] . " " . $techDetail['lastName'];

        return $name;

    }
