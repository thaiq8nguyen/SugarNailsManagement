<?php
    require "Connection.php";


    $payment = $_POST["payment"];
    $payPeriod = $_POST["payPeriod"];

    $isValid = false;
    $response = "";


    mysqli_autocommit($link,false);

    $pay = json_decode($payment, true);

    //Looping through each technician ID
    foreach($pay as $pays){
        $totalPayment = 0;
        $balance = 0;
        $techID = $pays["techID"];
        $wage = $pays["wage"];
        $makePayment = "INSERT INTO payments(paymentTypeID,recipientID,payPeriod,paydate) " .
            "VALUES(1," . $techID . ",'" . $payPeriod . "','" . date("Y-m-d")."')";
            $result = mysqli_query($link,$makePayment);
            if($result){
                $isValid = true;
                $paymentID = mysqli_insert_id($link);
                mysqli_commit($link);

                //Looping through each payment in each technician ID

                foreach($pays["payments"] as $paysDetail ){
                    $amount = $paysDetail["paymentAmount"];
                    $method = $paysDetail["paymentMethod"];
                    $reference = $paysDetail["paymentReference"];
                    $makePaymentDetail = "INSERT INTO paymentsdetail(paymentID,paymentMethodID,paymentAmount,paymentRef) ".
                        "VALUES(" . $paymentID . "," . $method . ",'" . $amount . "','" . $reference . "')";
                    $result = mysqli_query($link,$makePaymentDetail);
                    if($result){
                        mysqli_commit($link);

                    }
                    else{
                        mysqli_rollback($link);
                        $response = array("status" => "failure", "msg" => "Make payment detail error",
                            "error" => mysqli_error($link));
                    }

                    $totalPayment = $totalPayment + $amount;


                    $balance = $wage - $totalPayment;

                }


                $updateAccount = "INSERT INTO techaccount(techID,paymentID,salePeriod,wage,balance) " .
                "VALUES(" . $techID ."," . $paymentID . ",'" . $payPeriod . "'," . $wage . "," . $balance . ") ";
                $result = mysqli_query($link,$updateAccount);

                if($result){
                    $isValid = true;
                    mysqli_commit($link);
                    $response = array("status" => "success");
                }
                else{
                    mysqli_rollback($link);
                    $response = array("status" => "failure", "msg" => "Account update error ", "error" => mysqli_error($link));
                }
            }


            else{
                mysqli_rollback($link);
                $isValid = false;
                $response = array("status" => "failure", "msg" => "Make payment error", "error" => mysqli_error($link));
            }
    }


    echo(json_encode($response));


















