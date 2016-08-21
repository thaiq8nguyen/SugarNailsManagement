<?php
$base = dirname(dirname(__FILE__));
require_once ($base. '/php/Connection.php');
require_once ($base . '/php/Class.Technician.php');
require_once ($base . '/php/Class.PayReport.php');

/**
 * Created by PhpStorm.
 * User: tnguyen
 * Date: 8/10/2016
 * Time: 1:35 PM
 */



class Payday
{

    private $techID;
    private $periodID;
    private $payPeriod;
    private $balance;
    /**
     * Return active pay period
     * @return array|string
     */
    public function GetActivePayPeriod(){
        global $link;
        $period = "";
        $query = "SELECT p.id, p.period,p.active FROM payperiod as p WHERE p.active = 1";
        $result = mysqli_query($link,$query);
        if($result){
            while($row = mysqli_fetch_assoc($result)){
                $period[] = array("id" => $row["id"], "period" => $row["period"]);
            }
        }
        return $period;
    }

    /**
     * @param $periodID
     * @param $techID
     * @return array|string
     */
    public function GetDailySale($periodID, $techID){
        global $link;

        $data = $this->GetPayPeriodDetail($periodID);

        $payPeriod = $data['period'];

        $fromDate = substr($payPeriod,0,-13);
        $toDate = substr($payPeriod,13);

        $sale = "";
        $query = "SELECT COALESCE(s.saleID,0) AS saleID,CONCAT(DATE(fS.saleDate),' - ',DATE_FORMAT(fS.saleDate,'%a')) AS workDay,
                COALESCE(amount,0.00) AS Sale, COALESCE(tip,0.00) AS Tip FROM fromSquare_DailySale AS fS
                LEFT JOIN sales AS s ON DATE(fS.saleDate)= DATE(s.saleDate)
                AND s.techID = " . $techID . " WHERE fS.saleDate BETWEEN '" . $fromDate . "' AND '" .$toDate . "'
                ORDER BY DATE(fS.saleDate) ASC" ;

        $result = mysqli_query($link, $query);
        if(mysqli_num_rows($result) > 1){

            while($row = mysqli_fetch_assoc($result)){
                $sale[] =  array("workDay" => $row["workDay"],"sale" => $row["Sale"], "tip" => $row["Tip"]);
            }
        }
        return($sale);
    }

    /**
     * Return payperiod id, payperiod description, payperiod status
     * @param $periodID
     * @return array|string
     */
    public function GetPayPeriodDetail($periodID){
        global $link;
        $query = "SELECT p.id, p.period,p.active FROM payperiod as p WHERE p.id = ". $periodID;
        $period = "";
        $result = mysqli_query($link,$query);

        if($result){
            $row = mysqli_fetch_assoc($result);
            $period = array("id" => $row["id"], "period" => $row["period"], "active" => $row["active"]);

        }
        return($period);
    }

    /**
     * Return payperiod id, payperiod description, payperiod status
     * @param $payPeriod
     * @return array|string
     */
    public function GetPayPeriodID($payPeriod){
        global $link;

        $period = "";

        $query = "SELECT p.id, p.period,p.active FROM payperiod as p WHERE p.period = '". $payPeriod . "'";
        $result = mysqli_query($link,$query);
        if($result){
            $row = mysqli_fetch_assoc($result);
            $period = array("id" => $row["id"], "period" => $row["period"], "active" => $row["active"]);

        }
        return($period);
    }
    /**
     * Return gross metric, earn metric, and pay status
     * @param $periodID
     * @return array|string
     */
    public function GetGrossSaleAndEarning($periodID){
        global $link;
        $earning = '';
        $data = $this->GetPayPeriodDetail($periodID);

        $payPeriod = $data['period'];

        $fromDate = substr($payPeriod,0,-13);
        $toDate = substr($payPeriod,13);

        $q1 = "SELECT t.technicianID AS technicianID,CONCAT(firstName,' ',lastName) AS technician,
            SUM(amount) AS grossSale, SUM(tip) AS grossTip,
            CAST((t.commissionRate * SUM(amount)) AS DECIMAL(6,2)) AS saleEarning,
            CAST((t.`tipRate` * SUM(tip)) AS DECIMAL(6,2)) AS tipEarning,
            CAST((t.commissionRate * SUM(amount) + t.`tipRate` * SUM(tip)) AS DECIMAL(6,2)) AS totalEarning,t.totalBalance,
            (CAST((t.commissionRate * SUM(amount) + t.`tipRate` * SUM(tip)) AS DECIMAL(6,2)) + t.totalBalance) AS netWage,
            IF(s.payPeriodID is null,'Pending','Paid') AS payStatus
            FROM technicians AS t INNER JOIN sales AS s 
            ON t.`technicianID` = s.techID WHERE DATE(s.saleDate) BETWEEN '" . $fromDate . "' AND '" . $toDate . "' 
            GROUP BY t.firstName,t.lastName ASC";

        $result = mysqli_query($link,$q1);

        if($result){
            while($row = mysqli_fetch_assoc($result)){

                $earning[] = array("techID" => $row['technicianID'],"technician" => $row['technician'],
                    "sale" => array("grossSale" => $row['grossSale'], "grossTip" => $row['grossTip'],
                    "saleEarning" =>  $row['saleEarning'],"tipEarning" => $row['tipEarning'],
                    "totalEarning" => $row['totalEarning'], "totalBalance"=> $row["totalBalance"],
                        "netWage"=>$row["netWage"],
                        "payStatus" => $row['payStatus']));
            }
        }
        return $earning;
    }

    public function GetGrossSaleAndEarningByTechID($periodID,$techID){
        global $link;
        $earning = '';
        $data = $this->GetPayPeriodDetail($periodID);

        $payPeriod = $data['period'];

        $fromDate = substr($payPeriod,0,-13);
        $toDate = substr($payPeriod,13);

        $query = "SELECT t.technicianID AS technicianID,CONCAT(firstName,' ',lastName) AS technician,
            SUM(amount) AS grossSale, SUM(tip) AS grossTip,
            CAST((t.commissionRate * SUM(amount)) AS DECIMAL(6,2)) AS saleEarning,
            CAST((t.`tipRate` * SUM(tip)) AS DECIMAL(6,2)) AS tipEarning,
            CAST((t.commissionRate * SUM(amount) + t.`tipRate` * SUM(tip)) AS DECIMAL(6,2)) AS totalEarning,
            IF(s.payPeriodID is null,'Pending','Paid') As payStatus, t.totalBalance
            FROM technicians AS t INNER JOIN sales AS s 
            ON t.`technicianID` = s.techID WHERE s.techID = " . $techID .
            " AND DATE(s.saleDate) BETWEEN '" . $fromDate . "' AND '" . $toDate . "' 
            GROUP BY t.firstName,t.lastName ASC";

        $result = mysqli_query($link,$query);

        if($result){
            $row = mysqli_fetch_assoc($result);
                $earning = array("techID" => $row['technicianID'],"technician" => $row['technician'],
                    "grossSale" => $row['grossSale'], "grossTip" => $row['grossTip'],
                    "saleEarning" => $row['saleEarning'], "tipEarning" => $row['tipEarning'],
                    "totalEarning" => $row['totalEarning'], "payStatus" => $row['payStatus'],
                    "totalBalance" => $row["totalBalance"]);

        }
        return $earning;
    }


    /**
     * @param $techID
     */
    public function GetTotalBalance($techID){
        global $link;
        $query = "SELECT a.techID,SUM(a.balance) as totalBalance FROM techaccount as a 
        WHERE a.techID = " . $techID;

        $result = mysqli_query($link,$query);
        $response = "";
        if($result){
            if(mysqli_num_rows($result) == 1){
                $row = mysqli_fetch_assoc($result);
                $response = $row["totalBalance"];
            }
        }
        $this->balance = $response;

    }
    /**
     * Pay the technician
     * @param $payment
     * @param $payPeriod
     * @return array|string
     */
    public function Pay($payment, $payPeriod){
        global $link;
        $response = "";
        $data = $this->GetPayPeriodID($payPeriod);

        $periodID = $data["id"];
        $payDate = date('Y-m-d');
        mysqli_autocommit($link,false);

        $pay = json_decode($payment, true);

        //Looping through each technician ID
        foreach($pay as $pays){
            $totalPayment = 0;
            $balance = 0;
            $techID = $pays["techID"];
            $wage = $pays["wage"];
            $makePayment = "INSERT INTO payments(paymentTypeID,techID,payPeriodID,paydate)
                VALUES(1," . $techID . ",'" . $periodID . "','" . $payDate . "')" ;
            $result = mysqli_query($link,$makePayment);
            if($result){
                $paymentID = mysqli_insert_id($link);
                mysqli_commit($link);

                //Looping through each payment in each technician ID

                foreach($pays["payments"] as $paysDetail ){
                    $amount = $paysDetail["paymentAmount"];
                    $method = $paysDetail["paymentMethod"];
                    $reference = $paysDetail["paymentReference"];
                    $makePaymentDetail = "INSERT INTO paymentsdetail(paymentID,paymentMethodID,paymentAmount,paymentRef)
                        VALUES(" . $paymentID . "," . $method . ",'" . $amount . "','" . $reference . "')";
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
                $updateAccount = "INSERT INTO techaccount(techID,paymentID,salePeriod,wage,balance)
                    VALUES(" . $techID ."," . $paymentID . ",'" . $payPeriod . "'," . $wage . "," . $balance . ") ";

                $accountResult = mysqli_query($link,$updateAccount);

                $updateSale = "UPDATE sales SET payPeriodID = " .$periodID ." WHERE techID = " .$techID;

                $saleResult = mysqli_query($link,$updateSale);

                $updateBalance = "UPDATE technicians AS t INNER JOIN
                (SELECT techID,SUM(balance) AS balance FROM techaccount GROUP BY techID)AS a ON t.`technicianID` = a.techID
                SET t.`totalBalance`= a.balance WHERE t.`technicianID`= " . $techID;

                $balanceResult = mysqli_query($link,$updateBalance);

                if($accountResult && $saleResult && $balanceResult){
                    mysqli_commit($link);
                    $response = array("status" => "success");
                }
                else{
                    if(!$accountResult){
                        $response = array("status" => "failure", "msg" => "Account update error ", "error" => $updateAccount);
                    }
                    else if(!$updateSale){
                        $response = array("status" => "failure", "msg" => "Sale update error ", "error" => mysqli_error($link));
                    }
                    else{
                        $response = array("status" => "failure", "msg" => "Balance update error ", "error" => mysqli_error($link));
                    }
                    mysqli_rollback($link);

                }
            }
            else{
                mysqli_rollback($link);
                $response = array("status" => "failure", "msg" => "Make payment error", "error" => mysqli_error($link));
            }
        }
        return($response);
    }

    public function GetPayReport($payPeriod,$techID){


        $data = $this->GetPayPeriodID($payPeriod);
        $this->GetTotalBalance($techID);

        $periodID = $data["id"];

        $saleData = $this->GetGrossSaleAndEarningByTechID($periodID,$techID);
        $techName = $saleData["technician"];
        $grossSale = $saleData["grossSale"];
        $grossTip = $saleData["grossTip"];
        $saleWage = $saleData["saleEarning"];
        $tipWage = $saleData["tipEarning"];
        $balance = $this->balance;
        $periodWage =  $saleWage + $tipWage;
        $totalWage = $periodWage + $balance;


        $wage = $this->GetDailySale($periodID,$techID);


        $report = new PayReport();
        $report->SetName($techName);
        $report->SetWage($wage);
        $report->SetPayPeriod("2016-07-14 - 2016-07-28");
        $report->SetWageMetric($grossSale,$grossTip,$saleWage,$tipWage,$periodWage,$totalWage,$balance);
        $report->PrintReport();
        $report->Output("",$techName);

    }
}


