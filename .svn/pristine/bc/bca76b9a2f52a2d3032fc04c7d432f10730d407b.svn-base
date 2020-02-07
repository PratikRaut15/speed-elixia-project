<?php
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    $RELATIVE_PATH_DOTS = "../../../";
    require_once $RELATIVE_PATH_DOTS . "lib/system/utilities.php";
    require_once $RELATIVE_PATH_DOTS . "lib/autoload.php";
    require_once "class/class.api.php";

    //ob_start("ob_gzhandler");
    //ojbect creation
    $apiobj = new api();
    extract($_REQUEST);
    $totalPageSize = 0;
    $a = 0;
    $b = 0;
    $error = '';
    if ($action == "dashboard") {
        if (isset($userkey)) {
            $tempSensorsDetails = $apiobj->getTempSensors($userkey);
             // print("<pre>"); print_r($tempSensorsDetails); die;
            if(isset($tempSensorsDetails) && $tempSensorsDetails['status'] == "successful"){
                $tempSensors = $tempSensorsDetails['temp_sensors'];
                /* hardcoaded temp_sensors = 1 for Icelings Vehicle temp dashboard*/
                if(isset($tempSensorsDetails['customerno']) && $tempSensorsDetails['customerno'] == 193){
                    if(isset($isWareHouse) && $isWareHouse == 0){ // $isWareHouse == 0 means Vehicle Temp dashboard
                        $tempSensors = 1; // Icelings has 4 sensors in warehouse and 1 sensor in Vehicle hence we have harcoaded $tempSensors = 1
                    }
                }
                /* End hardcoaded temp_sensors = 1 for Icelings Vehicle temp dashboard*/
                switch ($tempSensors) {
                    case 4:
                        $pageSize = 2;
                        break;
                    case 3:
                        $pageSize = 2;
                        break;
                     case 2:
                        $pageSize = 4;
                        break;
                    case 1:
                        $pageSize = 8;
                        break;
                    default:
                       $pageSize = 2;
                        break;
                }
            } //die;
            $isWareHouse = isset($isWareHouse) ? $isWareHouse : 1;
            $pageIndex = isset($pageIndex) ? $pageIndex : 1;
            $pageSize = isset($pageSize) ? $pageSize : 2;
            $searchstring = isset($searchstring) ? $searchstring : '';
            $groupid = isset($groupid) ? $groupid : 0;
            $isRequiredThirdParty = isset($isRequiredThirdParty) ? $isRequiredThirdParty : 0;
            $deviceData = $apiobj->dashboard($userkey, $pageIndex, $pageSize, $searchstring, $groupid, $isRequiredThirdParty, $isWareHouse);
            if (isset($deviceData) && !empty($deviceData['result'])) {
                $totalPageSize = 0;
                $a = 0;
                $b = 0;

                $a = floor($deviceData['totalWareHouseCount'] / $pageSize);
                $mod = floor($deviceData['totalWareHouseCount'] % $pageSize);
                //$b = floor($deviceData['totalWareHouseCount']%$pageSize);
                if ($mod > 0) {
                    $totalPageSize = $a + 1;
                }else{
                    $totalPageSize = $a ;
                }
            } else {
                $deviceData['status'] = "unsuccessful";
                $error = $deviceData['status'];
            }

        }
    }

?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
</head>
<style type="text/css">
    html {
        height: 100%;
        margin: 0px auto;
        overflow: scroll;
    }
    body {
        text-align: center;
        height: 100%;
        margin: 0px auto;
        font-family:Arial;
        font-size: 12pt;
    }
    .colHeading{
        height: 30%;
        font-size: 150%;
        }
    #dashboard{
        margin: 0px auto;
        padding-top: 2%;
        width: 100%;
        height: 100%;

        background-color: #ccc;
        overflow: hidden;
        position: relative;
    }
    .vehicleInactive{
        background-color: #ccc;
        opacity: 0.8;
    }
    .vehicleNormal{
        background-color: green;
        opacity: 0.8;
    }
    .vehicleConflict{
        background-color: red;
        opacity: 0.8;
    }
    .notActive{

    }
    #tableTemp{
        border: 5px solid #000;
        border-radius: 20px;
        width: 90%;
        height: 95%;

    }
    #tableMain{
        padding: 10px;
        width: 100%;
        height: 45%;
    }
    #tableMain1{
        padding: 10px;
        width: 25%;
        height: 45%;
        float: left;
    }

    #tableMain2{
        padding: 10px;
        width: 45%;
        height: 45%;
        float: left;
    }

    #tableMain3{
        padding: 10px;
        width: 100%;
        height: 45%;
    }

    #tableMain4{
        padding: 10px;
        width: 100%;
        height: 45%;
    }


    .temperatureFont{
        font-size: 200%;
    }
    .tempRangeFont{
        font-size: 100%;
    }
</style>

<body>
<input type="hidden" name="userkey" id="userkey" value="<?php echo $userkey; ?>">
<input type="hidden" name="pageSize" id="pageSize" value="<?php echo $pageSize; ?>">
<input type="hidden" name="totalPageSize" id="totalPageSize" value="<?php echo $totalPageSize; ?>">
<input type="hidden" name="isWareHouse" id="isWareHouse" value="<?php echo $isWareHouse; ?>">
<input type="hidden" name="groupid" id="groupid" value="<?php echo $groupid; ?>">
<input type="hidden" name="pageIndex" id="pageIndex" value="">
<div id="dashboard"></div>
</body>
</html>

<script type='text/javascript' src='../../../bootstrap/js/jquery.min.js' ></script>
<script type="text/javascript" src='../../../scripts/livedashboard.js'></script>
