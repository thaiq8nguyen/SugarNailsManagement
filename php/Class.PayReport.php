<?php

/**
 * Created by PhpStorm.
 * User: tnguyen
 * Date: 8/14/2016
 * Time: 8:19 PM
 */

    $base = dirname(dirname(__FILE__));
    require ($base.'/phplib/fpdf/fpdf.php');



    class PayReport extends FPDF
    {
        private $techName;
        private $payPeriod;
        private $wage;
        private $grossSale;
        private $grossTip;
        private $saleWage;
        private $tipWage;
        private $periodWage;
        private $totalWage;
        private $balance;


        public function getName(){
            $this->techName;
        }
        function Header(){

            $this->SetFont('Arial','B',16);
            $this->Cell(40,10,$this->techName);
            $x = $this->GetX();
            $this->SetX($x+100);
            $this->Cell(60,10,"Wage Report");
            $this->Ln(8);
            $this->SetFont('Arial','',14);
            $this->Cell(40,10,"Pay Date: " . date("Y-m-d"));
            $x = $this->GetX();
            $this->SetX($x+60);

            $this->Cell(60,10, "Pay Period: " . $this->payPeriod);
            $this->Ln(16);
        }

        function ReportBody(){

            $header = array("Date","Sale","Tip");
            $this->SetFont('Arial','',10);
            // Header
            foreach ($header as $col){
                $this->Cell(30, 5, $col, 1);

            }
            $this->Ln();
            // Date
            foreach ($this->wage as $row) {
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
            $this->Cell(25,10,"$ " . $this->grossSale);
            $this->Cell(20,10, "Sale Wage: ");
            $this->Cell(50,10, "$ " . $this->saleWage);
            $this->SetFont('Arial','B',12);
            $this->Cell(30,10, "Wage: ");
            $this->Cell(25,10,"$ " . $this->totalWage);
            $this->Ln();
            $this->SetFont('Arial','',10);
            $this->Cell(20,10,"Gross Tip: ");
            $this->Cell(25,10,"$ " . $this->grossTip);
            $this->Cell(20,10, "Tip Wage: ");
            $this->Cell(50,10,"$" . $this->tipWage);
            $this->SetFont('Arial','B',12);
            $this->Cell(30,10, "Balance: ");
            $this->Cell(25,10,"$ " . $this->balance);
            $x = $this->GetX();
            $y = $this->GetY();
            $this->Ln();
            $this->Line($x-60,$y+10,$x,$y+10);
            $this->SetX(125);
            $this->Cell(30,10, "Total Wage: ");
            $this->Cell(25,10,"$ " . $this->totalWage);

        }
        public function SetName($techName){
            $this->techName = $techName;
        }
        public function SetWage($wage){
            $this->wage = $wage;
        }
        public function SetPayPeriod($payPeriod){
            $this->payPeriod = $payPeriod;
        }
        public function SetWageMetric($grossSale,$grossTip,$saleWage,$tipWage,$periodWage,$totalWage,$balance){
            $this->grossSale = $grossSale;
            $this->grossTip = $grossTip;
            $this->saleWage = $saleWage;
            $this->tipWage = $tipWage;
            $this->periodWage = $periodWage;
            $this->totalWage = $totalWage;
            $this->balance = $balance;
        }

        public function PrintReport()
        {
            $this->AddPage();
            $this->ReportBody();
        }
    }
