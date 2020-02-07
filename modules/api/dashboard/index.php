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
            $pageIndex = isset($pageIndex) ? $pageIndex : 1;
            $pageSize = isset($pageSize) ? $pageSize : 2;
            $searchstring = isset($searchstring) ? $searchstring : '';
            $groupid = isset($groupid) ? $groupid : 0;
            $isRequiredThirdParty = isset($isRequiredThirdParty) ? $isRequiredThirdParty : 0;
            $deviceData = $apiobj->dashboard($userkey, $pageIndex, $pageSize, $searchstring, $groupid, $isRequiredThirdParty);
            //prettyPrint($deviceData);
            if (isset($deviceData) && !empty($deviceData['result'])) {
                $totalPageSize = 0;
                $a = 0;
                $b = 0;
                $a = floor($deviceData['totalWareHouseCount'] / $pageSize);
                //$b = floor($deviceData['totalWareHouseCount']%$pageSize);
                if ($deviceData['totalWareHouseCount'] != $a) {
                    $b = 1;
                }
                $totalPageSize = $a + $b;
            } else {
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
    .temperatureFont{
        font-size: 300%;
    }
    .tempRangeFont{
        font-size: 100%;
    }
</style>

<body>
<input type="hidden" name="userkey" id="userkey" value="<?php echo $userkey; ?>">
<input type="hidden" name="pageSize" id="pageSize" value="<?php echo $pageSize; ?>">
<input type="hidden" name="totalPageSize" id="totalPageSize" value="<?php echo $totalPageSize; ?>">
<input type="hidden" name="pageIndex" id="pageIndex" value="">
<div id="dashboard"></div>
</body>
</html>

<script type='text/javascript' src='../../../bootstrap/js/jquery.min.js' ></script>
<script type="text/javascript" src='../../../scripts/livedashboard.js'></script>
