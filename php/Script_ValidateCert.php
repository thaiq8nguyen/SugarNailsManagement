<?php
    require_once "Connection.php";
    $result = '';
    if(!empty($_GET['certificate']) && !empty($_GET['validation'])){
        $certNumber = $_GET['certificate'];
        $validation = $_GET['validation'];

        if($validation == 'sale'){
            $result = ValidateForSale($certNumber);
        }

    }


    echo json_encode($result);
    //print_r($result);

    function ValidateForSale($certNumber){
        $validation = array();
        $link = $GLOBALS['link'];
        $query = "SELECT giftCertificateID,soldDate FROM giftcertificates WHERE " .
            "certificateNumber = '" . $certNumber . "' AND statusID = 4";
        $result = mysqli_query($link,$query);
        if(mysqli_num_rows($result) == 1){
                $info = mysqli_fetch_assoc($result);

                $validation['status'] = "valid";
                $validation['msg'] = "Valid certificate";
                $validation['certID'] = $info['giftCertificateID'];
        }
        else{
                $validation['status'] = "invalid";
                $validation['msg'] = "Invalid certificate and cannot be sold.";
        }

        return $validation;
    }



