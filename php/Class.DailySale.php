<?php
$base = dirname(dirname(__FILE__));
require ($base. '/php/Connection.php');
/**
 * Created by PhpStorm.
 * User: tnguyen
 * Date: 8/17/2016
 * Time: 3:20 PM
 */
class DailySale
{
    private $saleDate;

    public function __construct($saleDate)
    {
        $this->saleDate = $saleDate;
    }

    public function SetTechSale($techID ,$sale,$tip){
        global $link;

        $query = "INSERT INTO sales(techID,serviceID, saleDate, amount,tip)
            VALUES(" . $techID . ",6,'" . $this->saleDate . "','".$sale."','" . $tip . "')";

        $result = mysqli_query($link,$query);
        $saleID = mysqli_insert_id($link); //Get sale ID

        if($result){
            $response = array("status"=>"success","saleID"=>$saleID);
        }
        else{
            $response = array("status"=>"failure");
        }
        return $response;
    }

    public function UpdateTechSale($saleID,$sale,$tip){

        global $link;

        $query = "UPDATE sales SET amount = '" . $sale ."', tip = '" . $tip . "' WHERE saleID = " . $saleID;
        $result = mysqli_query($link,$query);
        if($result){
            $response = "success";
        }
        else{
            $response = "failure";
        }
        return $response;
    }

    public function DeleteTechSale($saleID){
        global $link;

        $query = "DELETE FROM sales WHERE saleID = " . $saleID;
        $result = mysqli_query($link,$query);

        if($result){
            $response = "success";
        }
        else{
            $response = "failure";
        }

        return $response;
    }
    public function GetAllTechDailySale(){
        global $link;

        $techSale = "";
        $query = "SELECT t.technicianID AS techID, firstName, lastName, COALESCE(amount,0.00) AS amount,
            COALESCE(tip,0.00) AS tip FROM technicians AS t INNER JOIN employeestatus AS e ON t.employeeStatusID = e.id 
            INNER JOIN usertype AS u ON t.userTypeID = u.userTypeID LEFT JOIN 
            sales AS s ON (t.`technicianID` = s.techID AND DATE(s.`saleDate`) = '" . $this->saleDate ."')" .
            "WHERE e.id = 1 AND u.userTypeID = 3 ORDER BY firstName ASC";

        $result = mysqli_query($link, $query);

        if($result){
            if(mysqli_num_rows($result) > 0){
                while($row = mysqli_fetch_assoc($result)){
                    $techSale[] = array("techID" => $row["techID"],
                        "name" => $row['firstName'] . " " . $row['lastName'],
                        "sale" => $row['amount'], "tip" => $row['tip']);

                }
            }
        }

        return $techSale;


    }
    public function GetTechDailySale($techID){
        global $link;
        $techSale = "";

        $query = "SELECT saleID ,COALESCE(amount,'0.00') AS amount,
            COALESCE(tip, '0.00') AS tip,commissionRate, tipRate,
            COALESCE(CAST((amount * commissionRate) AS DECIMAL(13,2)),0.00) AS saleEarning,
            COALESCE(CAST((tip * tipRate) AS DECIMAL(13,2)),0.00) AS tipEarning,
            (COALESCE(CAST((amount * commissionRate) AS DECIMAL(13,2)),0.00) + 
            COALESCE(CAST((tip * tipRate) AS DECIMAL(13,2)),0.00)) As totalEarning
            FROM technicians AS t LEFT JOIN sales AS s ON t.`technicianID` = s.techID
            AND DATE(saleDate) = '" . $this->saleDate . "'
            WHERE t.technicianID = " . $techID;

        $result = mysqli_query($link, $query);

        if ($result) {
            $row = mysqli_fetch_assoc($result);
            if(($saleID = $row['saleID']) == null){
                $saleStatus = "pending";
            }
            else{
                $saleStatus = "recorded";
                $saleID = $row['saleID'];
            }
            $sale = $row['amount'];
            $tip = $row['tip'];
            $saleEarning = $row['saleEarning'];
            $tipEarning = $row['tipEarning'];
            $totalEarning = $row["totalEarning"];
            $techSale = array("saleID" => $saleID, "sale" => $sale, "tip" => $tip,"saleStatus" => $saleStatus,
                "wage"=>array("saleWage" => $saleEarning, "tipWage" => $tipEarning,
                    "totalWage" => $totalEarning));
        }
        return $techSale;
    }

    public function GetTotalSaleAndTip(){
        global $link;
        $totalSale = "";
        $query = "SELECT DATE(sq.saleDate) AS saleDate,COALESCE(SUM(amount),0.00) AS totalSale, sq.grossSale AS sQSale, COALESCE(SUM(tip),0.00) AS totalTip,
        sq.grossTip AS sQTip, sq.cashPayment AS sQCash, sq.creditCardPayment AS sQCreditCard,
        (sq.cashPayment - sq.grossTip) AS expectedCash   FROM fromSquare_DailySale AS sq
        LEFT JOIN sales AS s ON DATE(s.saleDate) = DATE(sq.saleDate)
        GROUP BY sq.id HAVING saleDate = '" .$this->saleDate . "'";

        $result = mysqli_query($link,$query);
        $numResult = mysqli_num_rows($result);
        if($numResult == 1){
            while($row = mysqli_fetch_assoc($result)){
                $totalSale = array("status" => "success","sale" => array("grossSale" => $row['totalSale'],
                    "sQSale" => $row["sQSale"], "grossTip" => $row['totalTip'],"sQTip" => $row["sQTip"],
                    "sQCash" => $row["sQCash"], "sQCreditCard" => $row["sQCreditCard"],
                    "expectedCash" => $row["expectedCash"]));
            }
        }
        elseif($numResult == 0){
            $totalSale = array("status" => "failure","message" => "No Sale Data Available");

        }

        return $totalSale;
    }

}