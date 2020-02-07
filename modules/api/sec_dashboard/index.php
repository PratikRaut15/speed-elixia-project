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
    $depotname = '';
    $error = '';
    $dcDetails = array('lat'=>19.02148000,'lng'=>73.10307000,'rad'=>0.24,'eta'=>'03:00:00');
    if ($action == "dashboard") {
        if (isset($userkey)) {
            $pageIndex = isset($pageIndex) ? $pageIndex : 1;
            $pageSize = isset($pageSize) ? $pageSize : 2;
            $searchstring = isset($searchstring) ? $searchstring : '';
            $groupid = isset($groupid) ? $groupid : 0;
            $isRequiredThirdParty = isset($isRequiredThirdParty) ? $isRequiredThirdParty : 0;
            $deviceData = $apiobj->dashboard($userkey, $pageIndex, $pageSize, $searchstring, $groupid, $isRequiredThirdParty,$dcDetails);
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
                $depotname = $deviceData['depotname'];
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
        *padding-top: 2%;
        width: 100%;
        height: 100%;
        text-align:  center;
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
        width: 100%;
        height: 50px;

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
    .rowHeader{
        /* Permalink - use to edit and share this gradient: http://colorzilla.com/gradient-editor/#b7df2d+5,f8ffe8+100 */
background: #b7df2d; /* Old browsers */
background: -moz-linear-gradient(left, #b7df2d 5%, #f8ffe8 100%); /* FF3.6-15 */
background: -webkit-linear-gradient(left, #b7df2d 5%,#f8ffe8 100%); /* Chrome10-25,Safari5.1-6 */
background: linear-gradient(to right, #b7df2d 5%,#f8ffe8 100%); /* W3C, IE10+, FF16+, Chrome26+, Opera12+, Safari7+ */
filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#b7df2d', endColorstr='#f8ffe8',GradientType=1 ); /* IE6-9 */
    }
     .colText{
        height: 30%;
        font-size: 100%;
        }

    tr:nth-child(even):not(:first-child) {background: #CCC}
    tr:nth-child(odd) {background: #FFF}
    table { border-collapse:collapse }
</style>

<body>

<input type="hidden" name="userkey" id="userkey" value="<?php echo $userkey; ?>">
<input type="hidden" name="pageSize" id="pageSize" value="<?php echo $pageSize; ?>">
<input type="hidden" name="totalPageSize" id="totalPageSize" value="<?php echo $totalPageSize; ?>">
<input type="hidden" name="pageIndex" id="pageIndex" value="">
<div id="dashboard">
<center>
<div id="pageloaddiv"></div>
    <table  id="tableTemp">
        <thead>
            <tr style="background-color: none; /* Permalink - use to edit and share this gradient: http://colorzilla.com/gradient-editor/#b7df2d+5,f8ffe8+100 */
background: #b7df2d; /* Old browsers */
background: -moz-linear-gradient(left, #b7df2d 5%, #f8ffe8 100%); /* FF3.6-15 */
background: -webkit-linear-gradient(left, #b7df2d 5%,#f8ffe8 100%); /* Chrome10-25,Safari5.1-6 */
background: linear-gradient(to right, #b7df2d 5%,#f8ffe8 100%); /* W3C, IE10+, FF16+, Chrome26+, Opera12+, Safari7+ */
filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#b7df2d', endColorstr='#f8ffe8',GradientType=1 ); /* IE6-9 */" class="rowHeader">
                <td colspan="6">
                <img src="../../../images/localmovingcompany.gif" style=" height:70px;width: 150px;">
                <span class="temperatureFont" style="margin-top:-10px;">ARRIVALS </span>
                <?php
                $depotString = '';
                if($depotname!=''){
                    $depotString = "DC - ".$depotname;
                }
                ?>
                <span class="temperatureFont" style="padding-left: 100px;"><?php echo $depotString;?></span>
                <p style="float: right;">
                <span style="padding-left: 10px;" id="currentTimeStamp" class="colHeading"></span><br/> Refresh-
                <span id="time"></span> min
                </p>
                </td>
            </tr>

            <tr style="background-color: green; color: #fff;">
                <th class="colHeading">Fleet</th>
                <th class="colHeading">Source</th>
                <th class="colHeading">ETA</th>
                <th class="colHeading">KM Away</th>
                <th class="colHeading">STA</th>
                <th class="colHeading">Status</th>
            </tr>

        </thead>
        <tbody id="tblRows">

        </tbody>
            <tr style="/* Permalink - use to edit and share this gradient: http://colorzilla.com/gradient-editor/#f8ffe8+0,b7df2d+0,e3f5ab+0,e3f5ab+17,e3f5ab+100 */
background: #f8ffe8; /* Old browsers */
background: -moz-linear-gradient(left, #f8ffe8 0%, #b7df2d 0%, #e3f5ab 0%, #e3f5ab 17%, #e3f5ab 100%); /* FF3.6-15 */
background: -webkit-linear-gradient(left, #f8ffe8 0%,#b7df2d 0%,#e3f5ab 0%,#e3f5ab 17%,#e3f5ab 100%); /* Chrome10-25,Safari5.1-6 */
background: linear-gradient(to right, #f8ffe8 0%,#b7df2d 0%,#e3f5ab 0%,#e3f5ab 17%,#e3f5ab 100%); /* W3C, IE10+, FF16+, Chrome26+, Opera12+, Safari7+ */
filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#f8ffe8', endColorstr='#e3f5ab',GradientType=1 ); /* IE6-9 */">
                <th>
                    <img src="../../../images/328/rkfoodland.gif" style="width: 150px; height: 50px;">
                </th>
                <th class="colHeading">Gate In <br/>2</th>
                <th class="colHeading">In Transit<br/>12</th>
                <th class="colHeading">Docked<br/>3</th>
                <th class="colHeading">Docked Out<br>15</th>
                <th>
                    <img src="../../../images/elixiatech-logo.png" style="height: 50px;">
                </th>

            </tr>
    </table>
</center>

</div>
</body>
</html>

<script type='text/javascript' src='../../../bootstrap/js/jquery.min.js' ></script>
<script type="text/javascript" src='../../../scripts/sec_livedashboard.js'></script>
