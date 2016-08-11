<?php
$base = dirname(dirname(__FILE__));
require ($base. '/php/Connection.php');
/**
 * Created by PhpStorm.
 * User: tnguyen
 * Date: 8/10/2016
 * Time: 1:35 PM
 */
class Payday
{
    public function GetPayPeriodSale($fromDate,$toDate,$techID){
        global $link;
        $sale = '';
        $query = "SELECT COALESCE(s.saleID,0) AS saleID,CONCAT(DATE(fS.saleDate),' - ',DATE_FORMAT(fS.saleDate,'%a')) AS workDay,
                COALESCE(amount,0.00) AS Sale, COALESCE(tip,0.00) AS Tip FROM fromSquare_DailySale AS fS 
                LEFT OUTER JOIN sales AS s ON DATE(fS.saleDate)= DATE(s.saleDate) " .
            "AND s.technicianID = " . $techID . " WHERE fS.saleDate BETWEEN '" . $fromDate . "' AND '" .$toDate . "'" ;

        $result = mysqli_query($link, $query);
        if(mysqli_num_rows($result) > 1){

            while($row = mysqli_fetch_assoc($result)){
                $sale[] =  array("workDay" => $row["workDay"],"sale" => $row["Sale"], "tip" => $row["Tip"]);
            }
        }
        return($sale);
    }

}