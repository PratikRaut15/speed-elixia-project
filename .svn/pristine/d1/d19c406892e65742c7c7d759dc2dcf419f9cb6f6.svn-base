<?php
include_once '../../lib/system/utilities.php';
include_once '../../lib/comman_function/reports_func.php';
include_once '../../lib/autoload.php';
if (!isset($_SESSION)) {
    session_start();
    if (!isset($_SESSION['timezone'])) {
        $_SESSION['timezone'] = 'Asia/Kolkata';
    }
    date_default_timezone_set('' . $_SESSION['timezone'] . '');
}
function getVehicleFreezeIgnOn($sdate, $edate, $vehicleid, $vehicleno, $customerno, $reportType) {
    $arrSummaryData = array();
    $tableHeader = '';
    $tableRows = '';
    $conditionalHeader  = '';
    $conditional_abbreviations = '';
    //$totaldays = gendays_cmn($sdate, $edate);
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
            if ($reportType == speedConstants::REPORT_PDF) {
                $tableHeader .= pdf_header($title, $subTitle, $customer_details);
            } elseif ($reportType == speedConstants::REPORT_XLS) {
                $tableHeader .= excel_header($title, $subTitle, $customer_details);
            } else {
                $tableHeader .= table_header($title, $subTitle);
            }
            $tableRows .= processFreezeIgnOnSummary($arrSummaryData, $customer_details, $reportType);
            $arrSummaryData['tableHeader'] = $tableHeader;
            $arrSummaryData['conditionalHeader'] = $conditionalHeader;
            $arrSummaryData['conditional_abbreviations'] = $conditional_abbreviations;
            $arrSummaryData['tableRows'] = $tableRows;
        }
    } else {
        echo "File Not exists";
    }
    //prettyPrint($arrSummaryData);
    return $arrSummaryData;
}
function getVehicleFreezeIgnOnReportData($location, $sdate, $edate, $vehicleid) {
    //$dayscount = count($days);
    //$totalmin = $dayscount * 24 * 60;
    // print_r("Zzz");
    // die();
    $today = date("Y-m-d");
    $recordStart=$sdate;
    $recordEnd = $edate;
    //$REPORT = array();
    // echo $sdate;
    // echo $edate;
    // die();
        $REPORT = array();
        
        $sqlResult = new stdClass();
        //print_r($sdate);
        if((strtotime($sdate) != strtotime($today)) || (strtotime($edate) != strtotime($today))){
            $location = "sqlite:".$location;
            $i=0;
                //$checkRecord = checkRecordsInFreezeLog($vehicleid,$sdate,$edate); 
                // if(!empty($checkRecord)){
                    while (strtotime($sdate) <= strtotime($edate)) {
                        $sqliteResult = new stdClass();
                        
                        $tableDate = date("dmy",strtotime($sdate));
                        $tableName = 'A'.$tableDate;
                        $query = "SELECT freezeIgnitionOnTime FROM '" . $tableName . "'"." WHERE vehicleid=".$vehicleid;
                        //print_r($query."<br/>");
                        $database = new PDO($location);
                        $temp_result = $database->query($query);
                        if($temp_result !== FALSE){         
                            $result = $temp_result->fetch(PDO::FETCH_ASSOC); 
                            if(!empty($result) && is_array($result)){
                              //print_r($sdate);
                              $sqliteResult->reportDate = $sdate;
                              //$sqliteResult->endDate = $checkRecord['updatedon'];
                              $sqliteResult->freezeIgnOnTime = $result['freezeIgnitionOnTime']; 
                              $REPORT[$i]=$sqliteResult;   
                            }
                             
                        }
                        $sdate = date ("Y-m-d", strtotime("+1 day", strtotime($sdate)));
                        $i++;
                    }    
                //}

        }
                
        if(strtotime($recordStart) == strtotime($today) || strtotime($recordEnd) == strtotime($today)){
            $isdel=1;
            $checkRecord = checkRecordsInFreezeLog($vehicleid,$today);
            if(!empty($checkRecord)){

                $result = fetchRecordFromMysql($vehicleid,$today);
                //print_r($checkRecord);
                $sqlResult->reportDate = $checkRecord['updatedon'];
                //$sqlResult->endDate = $checkRecord['updatedon'];
                $sqlResult->freezeIgnOnTime = $result;
                $REPORT[] = $sqlResult; 
                
            }    
            
        }
    return $REPORT;
}
function processFreezeIgnOnSummary($arrReportData, $customerDetails, $reportType) {
    $table = '';
    if (isset($arrReportData)) {
        $Tablerows = reportTableRows($arrReportData, $customerDetails, $reportType);
    }
    if ($Tablerows != '') {
        $table .= $Tablerows;
    } else {
        $table .= "<tr><td colspan='100%'>Data Not Available</td></tr>";
    }

    return $table;
}
function reportTableRows($arrReportData, $customerDetails, $reportType) {
    $rows = '';
    $i = 1;
    $width = '';
    if($reportType == speedConstants::REPORT_PDF) {
        $width = "style='width:120px;'";
    }
    $geocode = isset($_POST['geocode']) ? $_POST['geocode'] : null;
    foreach ($arrReportData as $data) {
        $data = (object) $data;
        /*Running Time*/
        $hours = 
        $rows .= "<tr>";
        $rows .= "<td> " . $i++ . "</td>";
        $rows .= "<td> " . date("d-m-Y",strtotime($data->reportDate)) . "</td>";
        //$rows .= "<td> " . date("d-m-Y",strtotime($data->endDate)) . "</td>";
        $rows .= "<td> " . $data->freezeIgnOnTime. "</td>";
        $rows .= "</tr>";
    }
    return $rows;
}
// ******** THE TIME IS EPOCH TIME. IT NEEDS TO BE CONVERTED TO NORMAL TIME ********

function convertMinsToHours($time){
    $time = $time*60;
    $dt = new DateTime("@$time");
    return $dt->format('H:i');
}
function checkRecordsInFreezeLog($vehicleid,$today){
    $db1 = new DatabaseManager();
    //$freezeLog = array();
    $todayDate = date("Y-m-d");
    $freezeLogdata = array();
    $status = false;

    $query1 = "SELECT createdon,updatedon FROM freezelog WHERE vehicleid=" . $vehicleid." AND date(updatedon)='".$today."'";    
            $result = $db1->executeQuery($query1);
            if($db1->get_rowCount()>0){
                while($row = $db1->get_nextRow()){
                    $freezeLogdata['createdon'] = date("d-m-Y",strtotime($row['createdon'])); 
                    $freezeLogdata['updatedon'] = date("d-m-Y",strtotime($row['updatedon']));
                    return $freezeLogdata;
                }
            }
        return $freezeLogdata;    
}
function fetchRecordFromMysql($vehicleid,$date){
    $db1 = new DatabaseManager();
    $status = false;
    $query1 = "SELECT freezeIgnitionOnTime FROM dailyreport WHERE vehicleid=" . $vehicleid." AND daily_date='".$date."'";
        $result = $db1->executeQuery($query1);
            if($db1->get_rowCount()>0){
                while($row = $db1->get_nextRow()){
                    $mysqlRecords= isset($row['freezeIgnitionOnTime'])?$row['freezeIgnitionOnTime']:'00:00:00'; 
                }
            }
     return $mysqlRecords;
}
    

?>
