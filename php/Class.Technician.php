<?php
$base = dirname(dirname(__FILE__));
require ($base. '/php/Connection.php');
/**
 * Created by PhpStorm.
 * User: tnguyen
 * Date: 8/10/2016
 * Time: 11:49 AM
 */
class Technician
{
    private $firstName;
    private $lastname;
    private $position;
    private $saleCommissionRate;
    private $tipCommissionRate;
    private $payMethodRatio;
    private $hideDate;
    private $email;
    private $mobilePhone;

    public function GetFullName($techID){
        global $link;
        $lastName = "";
        $firstName = "";
        $query = "SELECT * from technicians WHERE technicianID = " . $techID;

        $result =  mysqli_query($link,$query);

        if($result){
            if(mysqli_num_rows($result) > 0){
                $row = mysqli_fetch_assoc($result);
                $lastName = $row["lastName"];
                $firstName = $row["firstName"];
            }
        }
        return ($firstName . " " . $lastName);
    }
    public function GetEmployedTechnician(){

    }

}