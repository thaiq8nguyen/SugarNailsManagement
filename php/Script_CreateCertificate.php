<?php
/**
 * Created by PhpStorm.
 * User: tnguyen
 * Date: 7/5/2016
 * Time: 3:51 PM
 */
    require_once 'Connection.php';
    require_once '../phplib/php-excel/PHPExcel.php';

    $list = CreateCertificate(2);
    ExcelExport($list);



    function ExcelExport($list){
        $objPHPExcel = new PHPExcel();

        // Set document properties
        $objPHPExcel->getProperties()->setCreator("Thai Nguyen")
            ->setLastModifiedBy("Thai Nguyen")
            ->setTitle("Office 2007 XLSX Test Document")
            ->setSubject("Office 2007 XLSX Test Document")
            ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
            ->setKeywords("office 2007 openxml php")
            ->setCategory("Test result file");


        // Add some data
        $objPHPExcel->setActiveSheetIndex(0);
        $objPHPExcel->getActiveSheet()->setCellValue('A1', 'CertificateNumber');
        $columnArray = array_chunk($list,1);
        $objPHPExcel->getActiveSheet()->fromArray($columnArray,NULL,'A2');

        // Rename worksheet
        $objPHPExcel->getActiveSheet()->setTitle('Certificate');


        // Set active sheet index to the first sheet, so Excel opens this as the first sheet
        $objPHPExcel->setActiveSheetIndex(0);

        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="CertificateNumber.xlsx"');
        header('Cache-Control: max-age=0');
        // If you're serving to IE 9, then the following may be needed
        header('Cache-Control: max-age=1');

        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save('php://output');
        exit;
    }


    function CreateCertificate($quantity){

        $link = $GLOBALS['link'];

        $certificateCount = 0;
        $certificateList = array();

        // Find the largest certificate id index
        while ($certificateCount < $quantity){
            $query = "SELECT MAX(giftCertificateID) AS id FROM giftcertificates";
            $result = mysqli_query($link, $query);
            $detail = mysqli_fetch_assoc($result);
            $certificate = "C" . str_pad($detail['id'] + 1,5,0,STR_PAD_LEFT);
            $insert = mysqli_query($link,
                "INSERT INTO giftcertificates(statusID,createDate,balance,certificateNumber) " .
                "VALUES(4,CONVERT_TZ(UTC_TIMESTAMP(),'UTC','US/Pacific'),0.00,'$certificate')");

            $certificateCount++;
            $certificateList[$certificateCount] = $certificate;

        }
        return $certificateList;
    }




    