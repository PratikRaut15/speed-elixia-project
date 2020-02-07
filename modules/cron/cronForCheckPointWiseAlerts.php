<?php 
/**
 * This cron is used for sending checkpoint wise stoppage alerts
 */
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
ini_set('max_execution_time', 0);
class cronForCheckPointWiseAlerts
{
    
    function __construct()
    {
        /* Loading manager */
        include_once '../../lib/autoload.php';

        require_once "../../lib/system/utilities.php";
        require_once 'files/calculatedist.php';
        require_once 'files/push_sqlite.php';

        $route_dashboard_customer = explode(",", speedConstants::ROUTE_DASHBOARD_CUSTNO);
        $customerExceptionList = "161,59,100,328,520,682";
        $chkpts = $this->getAllDevicesForCheckPointWiseAlerts($customerExceptionList);
    }

    private function getAllDevicesForCheckPointWiseAlerts($customerExceptionList) 
    { 
        $cronManagerObject = new CronManager();
        $checkPointsData = $cronManagerObject->getAllDevicesForCheckPointWiseAlerts($customerExceptionList);
        $dataArray = [];
        $bulkInsertQuery = '';
        $arrayOfVehiclesAlredayPresentInCheckPoint = [];
        foreach ($checkPointsData as $key => $value) {
            if($this->checkWhetherVehcileIsAlreadyPickedUp($arrayOfVehiclesAlredayPresentInCheckPoint,$value['vehicleid']))
            {
                continue;
            }
            $devicelat = $value['devicelat'];
            $devicelong = $value['devicelong'];
            $cgeolat = $value['cgeolat'];
            $cgeolong = $value['cgeolong'];
            $crad = (float)$value['crad'];
            $checkPointStoppageTime = $value['checkPointStoppageTime'];
            $stoppageInterval = $value['stoppageInterval'];
            $chkpoint_status = $value['chkpoint_status'];
            $distance = calculate($devicelat, $devicelong, $cgeolat, $cgeolong);
            // Checking whether vehicle is in checkpoint or not
            if($this->checkVehicleIsInCheckPoint($distance,$crad)){
                //checking the set stoopage time of checkpoint with vehcile which is entered in checkpoint
                if($this->checkWithCheckPointsStoppageTime($checkPointStoppageTime,$stoppageInterval,$chkpoint_status)){
                    $cqm = new ComQueueManager();
                    /* $cvo = new VOComQueue(); */
                    $cvo = new stdClass();
                    $cvo->customerno = $value['customerno'];
                    $cvo->lat = $value['devicelat'];
                    $cvo->long = $value['devicelong'];
                    $cvo->status = 0;
                    $cvo->chkid = $value['checkpointid'];
                    $cvo->vehicleid = $value['vehicleid'];
                    $cvo->cmid = $value['cmid'];
                    $arrayOfVehiclesAlredayPresentInCheckPoint[] = $cvo->vehicleid;
                   // $cvo->userid = $value['userid'];
                    $cvo->today = date(speedConstants::DEFAULT_TIMESTAMP);
                    $cvo->type = 18;
                    $cvo->message = $value['vehicleno'] . " is not moving at " . $value['cname'] . " for more than " . $checkPointStoppageTime . " mins";
                    $dataArray[] = $cvo;
        
                }
            }

        }

        $bulkInsertQueryData = $this->createBulkQueryStatement(json_decode(json_encode($dataArray),true));
        if(isset($bulkInsertQueryData) && $bulkInsertQueryData!='')
        {   $bulkInsertQuery = 'INSERT INTO comqueue(customerno,vehicleid,devlat,devlong,`type`,timeadded,status,message) VALUES '.$bulkInsertQueryData[0];
            $bulkUpdateQuery = 'UPDATE checkpointwise_stoppage_alerts SET isAlertSent = 1 WHERE id IN ('.$bulkInsertQueryData[1].')';
            $compqueueManagerObject = new ComQueueManager(); 
            $compqueueManagerObject->pushBulkDataInComqueue($bulkInsertQuery);
            $compqueueManagerObject->pushBulkDataInComqueue($bulkUpdateQuery);
        }
    }    

    private function checkVehicleIsInCheckPoint($distance,$crad)
    {
        if($distance < $crad){
            return true;
        } else {
            return false;
        }
    }

    private function checkWithCheckPointsStoppageTime($checkPointStoppageTime,$stoppageInterval,$chkpoint_status)
    {
        if(($stoppageInterval > $checkPointStoppageTime) && $chkpoint_status==0){
            return true;
        } else {
            return false; 
        }
    }

    private function createBulkQueryStatement(array $dataArray)
    {
        //$countDataArrayElement = count($dataArray);
        $insertQueryString = '';
        $updateQueryString = '';
        $returnDataArray = [];
        foreach ($dataArray as $key => $value) {
            $insertQueryString .= '('.$value['customerno'].','.$value['vehicleid'].','.$value['lat'].','.$value['long'].',18,"'.$value['today'].'",0,"'.$value['message'].'"),';
            $updateQueryString .= $value['cmid'].',';
        }

        $returnDataArray[0] = rtrim($insertQueryString, ',');
        $returnDataArray[1] = rtrim($updateQueryString, ',');

        return $returnDataArray;
    }

    private function checkWhetherVehcileIsAlreadyPickedUp(array $vehicleArray,$valueToBeFind)
    {
        if(count($vehicleArray)>0)
        {
            if(in_array($valueToBeFind,$vehicleArray))
            {
                return true;
            }
            else
            {
                return false;
            }
        }
        else
        {
            return false;
        }
    }
}

// Initializing the class object
$start = microtime(TRUE);
$cronObject = new cronForCheckPointWiseAlerts();
$end = microtime(TRUE);
echo "The code took " . ($end - $start) . " microseconds to complete.";
?>