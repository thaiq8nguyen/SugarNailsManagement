<?php
    session_start();
    require_once 'Connection.php';
    if(!empty($_POST['cert-input']) && !empty($_POST['amount-input'])){
        $number = $_POST['cert-input'];
        $redeemAmount = $_POST['amount-input'];
        $oldBalance = 0.00;
        $balance = 0.00;
        $response = "";

        $isValid = true;
        $selectCert = "SELECT giftCertificateID,soldDate,balance,status,CONCAT(firstName,' ',lastName) AS name from giftcertificates INNER JOIN giftcertificatestatus ".
            "ON giftcertificates.statusID = giftcertificatestatus.giftCertificateStatusID ".
            "INNER JOIN technicians ON giftcertificates.technicianID = technicians.technicianID ".
            "WHERE certificateNumber = '".$number."'";

        $result = mysqli_query($link,$selectCert);

        if($result){
            if(mysqli_num_rows($result) == 0){
                $isValid = false;
                $response = array("status" => "failure","msg" => "The certificate number is not found.");
            }
            else{
                $certDetail = mysqli_fetch_assoc($result);
                $balance = $certDetail['balance'];
                if($redeemAmount <= $balance){
                    $id = getCertificateStatusID($link,"redeemed");

                    //copy the current balance
                    $oldBalance = $balance;

                    //Deduct redeem from the current balance
                    $balance = $balance - $redeemAmount;

                    $updateCert = "UPDATE giftcertificates SET balance = " . $balance .
                        ",statusID = '" . $id . "'  WHERE certificateNumber = '" . $number . "'";
                    $updateResult = mysqli_query($link,$updateCert);
                        if($updateResult){
                            $response = array("status"=>"success","originalBalance" => $oldBalance,"redeemAmount"=> $redeemAmount,
                                "newBalance" => $balance);
                        }
                }
                else{
                    $response = array("status"=>"failure","msg"=>"The redeem amount is more than the balance." , "balance" => $balance);
                }
            }
        }
        else{
            $response = array("status"=>"failure","msg"=>"Database error");
        }
    }
    else{
        $response = array("status"=>"failure", "msg"=>"Please verify certificate and amount input");
    }

    echo json_encode($response);

    function getCertificateStatusID($link,$status){
        $result = mysqli_query($link,"SELECT giftCertificateStatusID 
        FROM giftcertificatestatus WHERE status = '" . $status ."'");
        $detail = mysqli_fetch_assoc($result);
        return $detail['giftCertificateStatusID'];

    }
