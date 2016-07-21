<?php
    session_start();
    require_once 'Connection.php';
    date_default_timezone_set('American/Los_Angeles');

    if(!empty($_POST['amount-input']) && !empty($_POST['certID'])){

        $certID = $_POST['certID'];
        $amount = $_POST['amount-input'];
        $technicianID = $_SESSION['technicianID'];

        mysqli_autocommit($link,false); //Setup transaction to false

        $isValid = true;

        $updateCert = "UPDATE giftcertificates SET technicianID = ".$technicianID.", balance = ".$amount
            .", statusID = 1, soldDate = CONVERT_TZ(UTC_TIMESTAMP(),'UTC','US/Pacific') ".
            "WHERE giftCertificateID = ".$certID;

        $insertTransaction = "INSERT INTO certificatetransactions(technicianID,giftCertificateID,transactionDate,amount,transactionCode) ".
            "VALUES(".$technicianID.",".$certID.",CONVERT_TZ(UTC_TIMESTAMP(),'UTC','US/Pacific'),".$amount.",1)";

        $selectCert = "SELECT certificateNumber, balance FROM giftcertificates WHERE giftCertificateID = " .$certID;

        $result = mysqli_query($link,$updateCert);

        if(!$result){ //To do when the first query execution is failed
            $isValid = false;
            $response = array("status"=>"failure","msg"=>mysqli_error($link));
        }
        $result = mysqli_query($link,$insertTransaction);

        if(!$result){ //To do when the second query execution is failed
            $isValid = false;

            $response = array("status"=>"failure","msg"=>mysqli_error($link));
        }
        if($isValid){ //To do when both query executions are success
            mysqli_commit($link);
            $result = mysqli_query($link, $selectCert);
            $certDetail = mysqli_fetch_assoc($result);
            $response = array("status"=>"success" , "certNumber" => $certDetail['certificateNumber'], "balance" => $certDetail['balance']);
        }else{
            mysqli_rollback($link); // Rollback when both query execution are failed
            $response = array("status"=>"failure","msg"=>"Sale  Error");
        }
    }else{
        $response = array("status"=>"failure","msg"=>"Input Error");
    }
    echo json_encode($response);

    mysqli_close($link);