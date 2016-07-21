<?php
    require_once ("Connection.php");

    date_default_timezone_set('America/Los Angeles');

    $json = '[{"techID":"5",
    "payment":[
                {"amount":"10","reference":"check # 101"},
                {"amount:"7","reference":"cash"}
                ]},
    {"techID":"10",
    "payment":[
               {"amount":"28","reference":"check # 102"},
               {"amount":"","reference":""}
               ]
    }]';
    $jsonTwo = '[{
                   "techid": "5",
                   "payments":[
                               {"amount":"9.5","reference":"102"},
                               {"amount":"10","reference":"103"}
                             ]
                }]';



    //$json = $_POST["payment"];
    //$startPeriod = "2016-04-18";$_POST['startPeriod'];
    //$endPeriod = "2016-04-19"; $_POST['endPeriod'];
    //$payPeriod = $startPeriod . " - " . $endPeriod;

    $pay = json_decode($jsonTwo, true);

    foreach($pay as $data){
        echo($data["techid"] . "<br>");
        foreach($data["payments"] as $payment ){
            echo($payment["amount"]);
        }
    }

    /*
    $numPayment = count($pay);
    $numInsert = 0;


    foreach($pay as $key=>$data) {
        echo($data);

        $query = "INSERT INTO payments(paymentTypeID,recipientID,payPeriod,paydate) " .
            "VALUES(1," . $data["techID"] . ",'" . $payPeriod . "','" .
            date("Y-m-d")."')";
        $paymentResult = mysqli_query($link,$query);
        if(mysqli_query($link, $query)){
            $paymentID = mysqli_insert_id($link);
            $payments = $data["payment"];
            foreach($payments as $paymentKey=>$paymentData){
                $paymentQuery = "INSERT INTO paymentsdetail(paymentID,paymentAmount,paymentRef) ".
                    "VALUES(" . $paymentID .",'" . $paymentData["amount"] . "','" .
                    $paymentData["reference"] . "')";
                mysqli_query($link,$query);
            }

        };
        if($paymentResult){
            $numInsert += 1;
        }

    }
    if($numInsert == $numPayment){
        echo "success";
    }
    else{
        echo "failure";
    }
        */














