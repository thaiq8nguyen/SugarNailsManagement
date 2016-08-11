<?php
/**
 * Created by PhpStorm.
 * User: tnguyen
 * Date: 8/9/2016
 * Time: 9:20 AM
 */
    $base = dirname(dirname(__FILE__));
    require_once ($base. '/php/class.technician.php');
    require_once ($base. '/php/class.payday.php');
    require ($base.'/phplib/fpdf/fpdf.php');

    $payPeriod = $_GET['payPeriod'];
    $techID = $_GET['techID'];
    $fromDate = substr($payPeriod,0,-13);
    $toDate = substr($payPeriod,13);
    $payDate = date('Y-m-d');
    $displayPayPeriod = $fromDate . " to " . $toDate;



    $payDay = new Payday();
    $wage = $payDay->GetPayPeriodSale($fromDate,$toDate,$techID);


    $technician = new Technician();
    $techName = $technician->GetFullName($techID);


    class PDF extends FPDF
    {
        function Header(){
            global $techName;
            global $displayPayPeriod;
            global $payDate;
            $this->SetFont('Arial','B',16);
            $this->Cell(40,10,$techName);
            $x = $this->GetX();


            $this->SetX($x+100);
            $this->Cell(60,10,"Wage Report");
            $this->Ln(8);
            $this->SetFont('Arial','',14);
            $this->Cell(40,10,"Pay Date: 2016-07-30");
            $x = $this->GetX();
            $this->SetX($x+60);

            $this->Cell(60,10, "Pay Period: " . $displayPayPeriod);
            $this->Ln(16);
        }

        function ReportBody($wage){
            $header = array("Date","Sale","Tip");
            $this->SetFont('Arial','',10);
            // Header
            foreach ($header as $col){
                $this->Cell(30, 5, $col, 1);

            }
            $this->Ln();
            // Date
            foreach ($wage as $row) {
                foreach ($row as $col){
                    $this->Cell(30, 5, $col, 1);
                }
                $this->Ln();
            }
            $this->Ln(6);
            $this->SetFont('Arial','B',12);
            $this->Cell(20,12,"Wage",0);
            $this->Ln();
            $this->SetFont('Arial','',10);
            $this->Cell(20,10,"Gross Sale: ");
            $this->Cell(25,10,"$ 110.00");
            $this->Cell(20,10, "Sale Wage: ");
            $this->Cell(50,10, "$ 66.00");
            $this->SetFont('Arial','B',12);
            $this->Cell(30,10, "Wage: ");
            $this->Cell(25,10,"$ 75.00");
            $this->Ln();
            $this->SetFont('Arial','',10);
            $this->Cell(20,10,"Gross Tip: ");
            $this->Cell(25,10,"$ 25.00");
            $this->Cell(20,10, "Tip Wage: ");
            $this->Cell(50,10,"$ 17.50");
            $this->SetFont('Arial','B',12);
            $this->Cell(30,10, "Balance: ");
            $this->Cell(25,10,"$ 20.00");
            $x = $this->GetX();
            $y = $this->GetY();
            $this->Ln();
            $this->Line($x-60,$y+10,$x,$y+10);
            $this->SetX(125);
            $this->Cell(30,10, "Total Wage: ");
            $this->Cell(25,10,"$ 95.00");

        }

        function PrintReport($wage){
            $this->AddPage();
            $this->ReportBody($wage);
        }
    }

    $report = new PDF();
    $report->PrintReport($wage);
    $report->Output();





