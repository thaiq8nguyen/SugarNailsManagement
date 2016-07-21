<?php
/**
 * Created by PhpStorm.
 * User: tnguyen
 * Date: 7/5/2016
 * Time: 2:26 PM
 */
    session_start();
    require_once 'Connection.php';
    if(isset($_GET['certificate-number-input'])){
        $number = $_GET['certificate-number-input'];
        $balance = 0.00;
        $selectCert = $selectCert = "SELECT giftCertificateID,certificateNumber,soldDate,balance,status,CONCAT(firstName,' ',lastName) AS name from giftcertificates INNER JOIN giftcertificatestatus ".
            "ON giftcertificates.statusID = giftcertificatestatus.giftCertificateStatusID ".
            "INNER JOIN technicians ON giftcertificates.technicianID = technicians.technicianID ".
            "WHERE certificateNumber = '".$number."'";

        $result = mysqli_query($link,$selectCert);

        if($result) {
            if (mysqli_num_rows($result) == 0) {
                $isValid = false;
                $response = array("status" => "failure", "msg" => "The certificate number is not found.");
            } else {
                $certDetail = mysqli_fetch_assoc($result);
                $balance = $certDetail['balance'];
                $certNum = $certDetail['certificateNumber'];
                $date = new DateTime($certDetail['soldDate']);
                $solddate = $date->format('m/d/Y');

                $response = array("status" => "success", "balance" => $balance, "certNum"=>$certNum,"soldDate"=>$solddate);
            }
        }
        else{
            $response = array("status"=>"failure","msg"=>"Database error");
        }
    }
    else{
        $response = array("status"=>"failure", "msg"=>"Please verify certificate");
    }
    echo json_encode($response);