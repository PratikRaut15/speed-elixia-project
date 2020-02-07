<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
include_once '../../lib/system/utilities.php';
require_once '../../lib/autoload.php';
$cronm = new CronManager();

$sale = $cronm->dailySalesReport();
if (!empty($sale)) {
    $table = "<table border='1'>
                <tr><th>Sr No</th><th>Sales Person</th><th>Pipeline Id</th><th>Comapny Name</th><th>New Stage</th><th>Old Stage</th><th>Remarks</th><th>Product Name</th><th>Created By</th><th>Time</th></tr>";
    $sr = 1;
    $team = "";
    foreach ($sale as $data) {
        $remarks = '';
        $remarks = $data['remarks'];
        if(strlen($remarks)>50){
            $remarks = substr($data['remarks'],0,50);
            $remarks .= "...";

        }
        $table.="<tr><td>" . $sr . "</td><td>" . $data['name'] . "</td><td>P00" . $data['pipelineid'] . "</td><td>" . $data['company_name'] . "</td><td>" . $data['newstage'] . "</td><td>" . $data['oldstage'] . "</td><td>".$remarks."</td><td>" . $data['product_name'] . "</td><td>" . $data['teamid_creator'] . "</td><td>" . date('H:i', strtotime($data['timestamp'])) . "</td></tr>";
        $sr++;
    }
    $table.="</table>";

    $html = "Dear Team,<br>
                Please find the Daily sales report : <br><br>";
    $html .= $table;
    $subject = "Daily Sales Report of Date :" . date('d-m-Y', strtotime('-1 day', strtotime(date('Y-m-d'))));
    $to = array("sanketsheth@elixiatech.com", "mihir@elixiatech.com", "kushal.d@elixiatech.com", "kartik.elixiatech@gmail.com");
    $strCCMailIds = "";
    $strBCCMailIds = "";
    $attachmentFilePath = "";
    $attachmentFileName = "";
    $isTemplatedMessage = 1;
    echo $html;
    @sendMailUtil($to, $strCCMailIds, $strBCCMailIds, $subject, $html, $attachmentFilePath, $attachmentFileName, $isTemplatedMessage);
}
$sales = $cronm->dailySalesReportForSR();
//echo "<pre>";
//print_r($sales);
if(!empty($sales)){
    foreach ($sales as $sale){
        if(!empty($sale['sales'])){
                //print_r($sale);
                $table = "<table border='1'>
                            <tr><th>Sr No</th><th>Sales Person</th><th>Pipeline Id</th><th>Comapny Name</th><th>New Stage</th><th>Old Stage</th><th>Remarks</th><th>Product Name</th><th>Created By</th><th>Time</th></tr>";
                $sr = 1;
                $team = "";
                foreach ($sale['sales']  as $data) {
                    $remarks = '';
                    $remarks = $data['remarks'];
                    if(strlen($remarks)>50){
                        $remarks = substr($data['remarks'],0,50);
                        $remarks .= "...";

                    }
                        $table.="<tr><td>" . $sr . "</td><td>" . $data['name'] . "</td><td>P00" . $data['pipelineid'] . "</td><td>" . $data['company_name'] . "</td><td>" . $data['newstage'] . "</td><td>" . $data['oldstage'] . "</td><td>".$remarks."</td><td>" . $data['product_name'] . "</td><td>" . $data['teamid_creator'] . "</td><td>" . date('H:i', strtotime($data['timestamp'])) . "</td></tr>";
                        $sr++;
                }
                $table.="</table>";
                $html = "Dear ".$sale['details']['name'].",<br>
                            Please find the Daily sales report : <br><br>";
                $html .= $table;
               	$subject = "Daily Sales Report of Date :" . date('d-m-Y', strtotime('-1 day', strtotime(date('Y-m-d'))));
                $to = array($sale['details']['email']);
                $strCCMailIds = "";
                $strBCCMailIds = "";
                $attachmentFilePath = "";
                $attachmentFileName = "";
                $isTemplatedMessage = 1;
                echo $html;
                @sendMailUtil($to, $strCCMailIds, $strBCCMailIds, $subject, $html, $attachmentFilePath, $attachmentFileName, $isTemplatedMessage);
        }
    }
}


?>
