<?php

if (!isset($RELATIVE_PATH_DOTS) || trim($RELATIVE_PATH_DOTS) == "") {
    $RELATIVE_PATH_DOTS = "../../";
}
include_once $RELATIVE_PATH_DOTS . 'lib/system/utilities.php';
include_once $RELATIVE_PATH_DOTS . 'lib/autoload.php';
include_once $RELATIVE_PATH_DOTS . 'lib/comman_function/reports_func.php';

function mergeDailyReport_Listener($objVehDetail){

    if(isset($objVehDetail) && !empty($objVehDetail)){

        $objDailyReportManager = new DailyReportManager($objVehDetail->customerNo);
        $objVehDetail->status = isset($objVehDetail->status) ? $objVehDetail->status : "";   
        $todaysDateTime = new DateTime();
        // if ($objDeviceData->gpsfixed == "A" && $objDeviceData->isOffline == 0) {

        if ($objVehDetail->isOffline == 0) {

            /*error_reporting(E_ALL);
            ini_set('display_errors', 1);
            ini_set('display_startup_errors', 1);*/

            $objRequest = new stdClass();
            $objRequest->timestamp  = $objVehDetail->lastUpdated; ///////////////////////////////
            $objRequest->customerno = $objVehDetail->customerNo;
            $objRequest->vehicleid  = $objVehDetail->vehicleId;
            $objRequest->unitid     = $objVehDetail->uid;
            // $objRequest->devicelat  = $vehData->latitude; 
            $objRequest->devicelat  = $objVehDetail->deviceLat;
            $objRequest->devicelong = $objVehDetail->deviceLng;
            // $objRequest->odometer   = $objVehDetail->odometer;
            $objRequest->odometer   = $objVehDetail->odometer;
            $objRequest->driverid   = $objVehDetail->driverId;
            $objRequest->daily_date = isset($objVehDetail->lastUpdated) ? 
                                            date(speedConstants::DATE_Ymd,strtotime($objVehDetail->lastUpdated))
                                             : null;
                        
            $arrDailyReportResult = $objDailyReportManager->GetDailyReport_Listener($objRequest);
            
            // print("<pre>"); print_r($arrDailyReportResult); die;


            if(isset($arrDailyReportResult) && is_array($arrDailyReportResult) && count($arrDailyReportResult) == 1) {
                // echo "if"; die;
                $objRequest->flag_harsh_break = $arrDailyReportResult[0][0];
                $objRequest->flag_sudden_acc = $arrDailyReportResult[0][1];
                $objRequest->flag_towing = $arrDailyReportResult[0][2];
                $objRequest->last_odometer = $arrDailyReportResult[0][3];
                $objRequest->topspeed = $arrDailyReportResult[0][4];
                $objRequest->last_online_updated = $arrDailyReportResult[0][5];
                $objRequest->offline_data_time = $arrDailyReportResult[0][6];
                $objRequest->topspeed_lat = $arrDailyReportResult[0][7];
                $objRequest->topspeed_long = $arrDailyReportResult[0][8];
                $objRequest->harsh_break = $arrDailyReportResult[0][9];
                $objRequest->sudden_acc = $arrDailyReportResult[0][10];
                $objRequest->towing = $arrDailyReportResult[0][11];
                $objRequest->max_odometer = $arrDailyReportResult[0][12];
                $objRequest->end_lat = $arrDailyReportResult[0][13];
                $objRequest->end_long = $arrDailyReportResult[0][14];
                $objRequest->first_odometer = $arrDailyReportResult[0][15];
                $objRequest->topspeed_time = $arrDailyReportResult[0][16];
                $objRequest->daily_date = $arrDailyReportResult[0][17];
                
                if ($objRequest->first_odometer == 0) {
                    $objRequest->first_odometer = $objRequest->last_odometer;
                }
                $last_online_updatedDate = new DateTime($objRequest->last_online_updated);
                $diffInterval = $last_online_updatedDate->diff($todaysDateTime);
                
                $diffInSeconds = ($diffInterval->y * 12 * 30 * 24 * 60 * 60) 
                                + ($diffInterval->m * 30 * 24 * 60 * 60) 
                                + ($diffInterval->d * 24 * 60 * 60)
                                + ($diffInterval->h * 60 * 60)
                                + ($diffInterval->i * 60)
                                + ($diffInterval->s);

                if ($diffInSeconds > 600) {
                    $objRequest->offline_data_time = $objRequest->offline_data_time + $diffInSeconds;
                }
                # Top Speed
                if ($objVehDetail->speed > $objRequest->topspeed) {
                    $objRequest->topspeed = $objVehDetail->speed;
                    $objRequest->topspeed_lat = $objRequest->devicelat;
                    $objRequest->topspeed_long = $objRequest->devicelong;
                    $objRequest->topspeed_time = $objRequest->timestamp;
                }
                # Harsh Break
                if ($objVehDetail->status == 'S' && $objRequest->flag_harsh_break == 0){
                    $objRequest->harsh_break = $objRequest->harsh_break + 1;
                    $objRequest->flag_harsh_break = 1;
                }
                if ($objVehDetail->status != 'S') {
                    $objRequest->flag_harsh_break = 0;                            
                }
                # Sudden Acceleration

                if ($objVehDetail->status == 'U' and $objRequest->flag_sudden_acc == 0) {
                    $objRequest->sudden_acc = $objRequest->sudden_acc + 1;
                    $objRequest->flag_sudden_acc = 1;
                }
                if ($objVehDetail->status  != 'U'){
                    $objRequest->flag_sudden_acc = 0;
                }
                # Towing
                // print_r($objRequest->flag_towing); die;
                if (($objVehDetail->status == 'V' || ($objVehDetail->ignition == 0 && $objVehDetail->speed > 10)) && ($objRequest->flag_towing) == '0'){
                // if (($objVehDetail->status == 'V' || ($objVehDetail->ignition == 0 && $objVehDetail->speed > 10)) && str($objRequest->flag_towing) == '0'){
                    $objRequest->towing = $objRequest->towing + 1;
                    $objRequest->flag_towing = 1;
                }
                if ($objVehDetail->status != 'V' && (($objVehDetail->ignition == 0 && $objVehDetail->speed < 10) || $objVehDetail->ignition == 1)){
                    $objRequest->flag_towing = 0;
                }
                # Max Odometer Reading
                if ($objVehDetail->odometer > $objRequest->max_odometer) {
                    $objRequest->max_odometer = $objVehDetail->odometer;
                }
                /*echo $todaysDateTime->format("Y-m-d");
                echo "<br>".$objRequest->daily_date;*/
                if($todaysDateTime->format("Y-m-d") == $objRequest->daily_date) {
                    // echo "upadte"; die;
                    $objDailyReportManager->UpdateDailyReport_Listener($objRequest);
                }
                else {
                    // echo "insert"; die;
                    // print_r($objRequest); die;
                    $objDailyReportManager->InsertDailyReport_Listener($objRequest);
                }
            }
            else {
                /*echo "last else"; die;
                print("<pre>"); print_r($objRequest); die;*/
                $objDailyReportManager->InsertDailyReport_Listener($objRequest);
            }
        }else{
            echo "Not valid data, Odometer is less than previous Odometer";
        }
       /*End Daily Report*/
    }   
}

function InsertDataInSqliteFunction($vehData, $objVehDetail){
    
    ### Converted into array because array_change_key_case function need to be passed a parameter in a ARRAY
    $arrVehDetail = (array) $objVehDetail; 

    ########### Converted into lower case in the sqlite manager keys are validtated with lower cse ######
    #####################################################################################################
    $objVehDetail = (object) array_change_key_case($arrVehDetail,CASE_LOWER);
    
    $objVehDetail->isPacketTimeValid = $objVehDetail->ispackettimevalid;
    $objVehDetail->isOffline = $objVehDetail->isoffline;
    $objVehDetail->devicelong = $objVehDetail->devicelng;
    $objVehDetail->timestamp = $objVehDetail->lastupdated;

    /*$objVehDetail->directionchange = '';
    $objVehDetail->hwv = '';
    $objVehDetail->swv = '';
    $objVehDetail->msgid = '';

    $objVehDetail->unitid = $objVehDetail->uid;
    $objVehDetail->status = '';
    $objVehDetail->powercut = 0;
    $objVehDetail->gpsfixed = '';
    $objVehDetail->gsmregister = 0;
    $objVehDetail->gprsregister = 0;
    $objVehDetail->satv = '';
    $objVehDetail->vehicleno = $objVehDetail->vehno;
    $objVehDetail->commandkey = '';
    $objVehDetail->commandkeyval = '';*/

    $objVehDetail->unitid = $objVehDetail->uid;

    unset($objVehDetail->ispackettimevalid, $objVehDetail->isoffline);

    ###### Encoded into json format because we are storing in sqlite as text format ######
    ######
    $vehData = json_encode($vehData);

    $objSqliteManager = new SqliteManager($objVehDetail->customerno, $objVehDetail->unitno, $objVehDetail->lastupdated);
    $objSqliteManager->InsertDataInSqlite($vehData,$objVehDetail);
}

?>
