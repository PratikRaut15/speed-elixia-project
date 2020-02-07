<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
require "../../lib/system/utilities.php";
require '../../lib/autoload.php';
$customerNo = 135;
$vm = new VehicleManager($customerNo);
$vehicles = $vm->getFrozenVehicles($customerNo);

function getVehicleFreezeIgnOn($sdate, $edate, $vehicleid, $vehicleno, $customerno) {
    $arrSummaryData = array();
    $sdate = date("Y-m-d",strtotime($sdate));
    $edate = date("Y-m-d",strtotime($edate));

    $objCustomerManager = new CustomerManager();
    $customer_details = $objCustomerManager->getcustomerdetail_byid($customerno);
    $location = "../../customer/" . $customerno . "/reports/dailyreport.sqlite";
    if (file_exists($location)) {
        $arrSummaryData = getVehicleFreezeIgnOnReportData($location,$sdate, $edate, $vehicleid);
        if (isset($arrSummaryData)) {
            $title = 'Freeze Ignition On Time';
            $subTitle = array(
                "Vehicle No: {$vehicleno}",
                "Start Date: {$sdate}",
                "End Date: {$edate}",
            );
        }
    } else {
        echo "File Not exists";
    }
    return $arrSummaryData;
}

function getVehicleFreezeIgnOnReportData($location, $sdate, $edate, $vehicleid) {
    $today = date("Y-m-d");
    $REPORT = array();
    if((strtotime($sdate) != strtotime($today)) || (strtotime($edate) != strtotime($today))){
        $location = "sqlite:".$location;
        $i=0;
        while (strtotime($sdate) <= strtotime($edate)) {
            $sqliteResult = new stdClass();
            $tableDate = date("dmy",strtotime($sdate));
            $tableName = 'A'.$tableDate;
            $query = "SELECT freezeIgnitionOnTime FROM '" . $tableName . "'"." WHERE vehicleid=".$vehicleid;
            $database = new PDO($location);
            $temp_result = $database->query($query);
            if($temp_result !== FALSE){
                $result = $temp_result->fetch(PDO::FETCH_ASSOC);
                if(!empty($result) && is_array($result)){
                    $sqliteResult->reportDate = $sdate;
                    $sqliteResult->freezeIgnOnTime = $result['freezeIgnitionOnTime'];
                    $REPORT[$i]=$sqliteResult;
                }

            }
            $sdate = date ("Y-m-d", strtotime("+1 day", strtotime($sdate)));
            $i++;
        }

    }

    return $REPORT;
}

function sendMail($to, $subject, $content) {
    echo $subject;
    echo $content;
    $headers = "From: noreply@elixiatech.com\r\n";
    // $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
    $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n"."CC: software@elixiatech.com";

    if (!@mail($to, $subject, $content, $headers)) {
        // message sending failed
        return false;
    }

    return true;
}
$startDate = $endDate = date('Y-m-d' ,strtotime('-1 day'));
$message = "";
$tableHeader = "<table border='1' ><thead><th>Vehicle No.</th><th>Time (Minutes)</th></thead><tbody>";
$tableBody = "";
foreach($vehicles as $k=>$vehicle){
    $data = getVehicleFreezeIgnOn($startDate,$endDate,$vehicle->vehicleid,$vehicle->vehicleno,135);
    if(isset($data[0]->freezeIgnOnTime)){
        $data[0]->vehicleId = $vehicle->vehicleid;
        $data[0]->vehicleNo = $vehicle->vehicleno;
        // echo "<pre>";
        // print_r($data);
        // echo "</pre>";
        if($data[0]->freezeIgnOnTime > 0){
            $tableBody .= "<tr><td>".$data[0]->vehicleNo."</td><td>".$data[0]->freezeIgnOnTime."</td></tr>";
        }
    }
}
$tableBody .= "</tr></tbody></table>";
$table = $tableHeader . $tableBody;
echo $table;
$toArray = array("baldev.singh@allcargologistics.com",
"balwant.mehta@allcargologistics.com",
"mobilization1@allcargologistics.com",
"harnam.singh@allcargologistics.com",
"satish.naik@allcargologistics.com",
"sriharsha.bhat@allcargologistics.com",
"sneha.mhatre@allcargologistics.com",
"sandeep.anand@allcargologistics.com",
"arbind.sharma@allcargologistics.com",
"shibu.joseph@allcargologistics.com",
"jeevan.shetty@allcargologistics.com",
"sohan.yadav@allcargologistics.com",
"deepak.sangle@allcargologistics.com",
"manjeet.pahadia@allcargologistics.com",
"sathwik.adyanthya@allcargologistics.com");
//$toArray = array('kartikj@elixiatech.com');
sendMailUtil($toArray,"","","Freeze report",$table,"","");
?>
