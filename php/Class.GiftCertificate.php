<?php
$base = dirname(dirname(__FILE__));
require($base . "/php/WS_GetSquareCertificate.php");
require_once ($base . "/php/Connection.php");
/**
 * Created by PhpStorm.
 * User: tnguyen
 * Date: 8/19/2016
 * Time: 11:17 PM
 */


class GiftCertificate
{
    public function CreateCertificate($beginDate,$endDate){
        global $link;

        mysqli_autocommit($link,false);

        $locationID = getLocationIds();
        $payment = getTodayPayment($locationID,$beginDate,$endDate);
        $certificates = getGiftCertificate($payment);
        foreach($certificates as $certificate){

            $query = "INSERT INTO giftcertificates(technicianID,statusID,balance,soldDate,transationID)
              VALUES(1,2,'" . $certificate["amount"] . "','" . $certificate["saleDate"] . "','" .$certificate["transactionID"]."')";
            $insertResult = mysqli_query($link,$query);
            if($insertResult){

                mysqli_commit($link);
                $id = mysqli_insert_id($link);
                $certificateNumber = "C" . str_pad($id + 1,5,0,STR_PAD_LEFT);
                $update = "UPDATE giftcertificates SET 
                certificateNumber = '" . $certificateNumber . "' WHERE giftCertificateID = " .$id;
                $updateResult = mysqli_query($link,$update);

                if($updateResult){
                    mysqli_commit($link);
                    $status = "success";
                }
                else{
                    mysqli_rollback($link);
                }
            }
            else{
                mysqli_rollback($link);
            }
        }

    }


}