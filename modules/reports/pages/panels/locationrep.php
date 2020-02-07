<?php

if (empty($tripdata) && !isset($tripdata)) {
    $tripdata = NULL;
}
$title = 'Location Report';
$subTitle = array(
    "Vehicle No: {$_POST['vehicleno']}",
    "Start Date: $STdate {$_POST['STime']}",
    "End Date: $EDdate {$_POST['ETime']}"
);
if ($_POST["frequency"] == 1) {
    $subTitle[] = "Report Generated for every <b>$interval</b> mins";
} else {
    $subTitle[] = "Report Generated for every <b>$distance</b> kms";
}
$columns = array(
    'Time',
    'Location'
);
$columnlinks = array(
    '',
    ''
);
if ($_SESSION['customerno'] != 96 && $_SESSION['customerno'] != 66) {
    $columns[] = "Speed [km/hr]";
    //$columnlinks[] = "";
    $columnlinks[] = "<a href='javascript:void(0)' title='View Overspeed Report' onclick='getSpeedReport(\"" . $userkey . "\",\"" . $EDdate . "\",\"" . $ETime . "\",\"" . $STdate . "\",\"" . $STime . "\",\"" . $deviceid . "\",\"" . $vehicleno . "\");'>ViewReport</a>";
}
$tempCols = array();
$tempColLinks = array();
$tripmin = '';
$tripmax = '';

if (!empty($tripid)) {
    $tripmax = $maxtemp;
    $tripmin = $mintemp;
}
switch ($_SESSION['temp_sensors']) {
    case 4:
        $tempCols[] = "Temperature 4";
        $tempColLinks[] = "<a href='javascript:void(0)' title='View Temperature Report' onclick='getTemperatureReport(\"" . $userkey . "\",\"" . $tripmin . "\",\"" . $tripmax . "\",\"" . $EDdate . "\",\"" . $ETime . "\",\"" . $STdate . "\",\"" . $STime . "\",\"" . $deviceid . "\",\"" . $interval . "\",\"" . $vehicleno . "\",\"4\");'>ViewReport</a>";
    case 3:
        $tempCols[] = "Temperature 3";
        $tempColLinks[] = "<a href='javascript:void(0)' title='View Temperature Report' onclick='getTemperatureReport(\"" . $userkey . "\",\"" . $tripmin . "\",\"" . $tripmax . "\",\"" . $EDdate . "\",\"" . $ETime . "\",\"" . $STdate . "\",\"" . $STime . "\",\"" . $deviceid . "\",\"" . $interval . "\",\"" . $vehicleno . "\",\"3\");'>ViewReport</a>";
    case 2:
        $tempCols[] = "Temperature 2";
        $tempColLinks[] = "<a href='javascript:void(0)' title='View Temperature Report' onclick='getTemperatureReport(\"" . $userkey . "\",\"" . $tripmin . "\",\"" . $tripmax . "\",\"" . $EDdate . "\",\"" . $ETime . "\",\"" . $STdate . "\",\"" . $STime . "\",\"" . $deviceid . "\",\"" . $interval . "\",\"" . $vehicleno . "\",\"2\");'>ViewReport</a>";
    case 1:
        $tempCols[] = "Temperature 1";
        $tempColLinks[] = "<a href='javascript:void(0)' title='View Temperature Report' onclick='getTemperatureReport(\"" . $userkey . "\",\"" . $tripmin . "\",\"" . $tripmax . "\",\"" . $EDdate . "\",\"" . $ETime . "\",\"" . $STdate . "\",\"" . $STime . "\",\"" . $deviceid . "\",\"" . $interval . "\",\"" . $vehicleno . "\",\"1\");'>ViewReport</a>";
}
$tempCols = array_reverse($tempCols);
$tempColLinks = array_reverse($tempColLinks);
foreach ($tempCols as $tempCol) {
    $columns[] = $tempCol;
}
foreach ($tempColLinks as $tempColLink) {
    $columnlinks[] = $tempColLink;
}

if ($_SESSION['use_genset_sensor'] == 1) {
    $columns[] = " Genset";
    //$columnlinks[] = "";
    $columnlinks[] = "<a href='javascript:void(0)' title='View Genset Report' onclick='getGensetReport(\"" . $userkey . "\",\"" . $EDdate . "\",\"" . $STdate . "\",\"" . $deviceid . "\",\"" . $vehicleno . "\");'>ViewReport</a>";
}

$columns[] = "Status";
$columnlinks[] = "";
if ($_SESSION['use_ac_sensor'] == 1) {
    $columns[] = "Compressor<br> Status";
    $columnlinks[] = "";
}
if ($_SESSION['userid'] != 391 && $_SESSION['userid'] != 392 && $_SESSION['customerno'] != 96 && $_SESSION['customerno'] != 66) {
    $columns[] = "Distance [KM]";
    $columns[] = "Cumulative Distance [KM] ";
    $columns[] = "Add As Checkpoint";
    $columnlinks[] = "";
    $vehicledata = getvehicleidfromdeviceid($deviceid);
    $vehicleid = $vehicledata['vehicleid'];



    $columnlinks[] = "<a href='javascript:void(0)' title='View Route' onclick='getRouteHistReport(\"" . $userkey . "\",\"" . $vehicleid . "\",\"" . $EDdate . "\",\"" . $ETime . "\",\"" . $STdate . "\",\"" . $STime . "\",\"" . $deviceid . "\",\"" . $vehicleno . "\");'>ViewRoute</a>";
    $columnlinks[] = '';
    if (isset($tripid) && !empty($tripid)) {
        $tripdetails = "<u>Trip History</u>";
    }
}


$middlecolumn = $triplogno = $tripstatus = $routename = $budgetedkms = $budgetedhrs = $consignor = $consignee = $billingparty = $mintemp = $maxtemp = $drivername = $drivermobile1 = $drivermobile2 = '';

if (isset($tripdata) && !empty($tripdata)) {
    /*print("<pre>"); 
    print_r($tripdata); die;*/
    if(isset($newedate) && $newedate !=""){
        $edate = $newedate;    
    }
    // $edate = $newedate;
    $tripstatus = $tripdata[0]->tripstatus;
    $triplogno = $tripdata[0]->triplogno;
    $routename = $tripdata[0]->routename;
    $budgetedkms = $tripdata[0]->budgetedkms;
    $budgetedhrs = $tripdata[0]->budgetedhrs;
    $consignor = $tripdata[0]->consignor;
    $consignee = $tripdata[0]->consignee;
    $billingparty = $tripdata[0]->billingparty;
    $mintemp = $tripdata[0]->mintemp;
    $maxtemp = $tripdata[0]->maxtemp;
    $drivername = $tripdata[0]->drivername;
    $drivermobile1 = $tripdata[0]->drivermobile1;
    $drivermobile2 = $tripdata[0]->drivermobile2;
    $tripend_odometer = 0;
    $tripstart_odometer = 0;
    $firstodometer = 0;
    $lastodometer = 0;
    $lastodometer = $getend_odometer;  // last odometer
    $firstodometer = $getstart_odometer; // first odometer
    if ($lastodometer < $firstodometer) {
        $tripobj = new Trips($_SESSION['customerno'], $_SESSION['userid']);
        //$lastodometermax = $tripobj->GetOdometerMax($tripend_date, $tripdata[0]->unitno);
        $days = gendays($tripstart_date, $tripend_date);
        if(count($days)>0){
            $lastodometerarr= array();
           foreach($days as $day){
               $lastodometerarr[] = $tripobj->GetOdometerMax($day, $tripdata[0]->unitno);
           }
            $lastodometermax = max($lastodometerarr);
        }
        
        $lastodometer = $lastodometermax + $lastodometer;
    }
    $totaldistance = $lastodometer - $firstodometer;
    $actualhrs = round((strtotime($tripstart_date) - strtotime($tripend_date)) / (60 * 60));
    if ($totaldistance != 0) {
        $res = $totaldistance / 1000;
    } else {
        $res = 0;
    }

    ////////////////Estimated Time calculate///////////////////////
    $estimated_time = 0;
    $estimated_time = $budgetedhrs - $actualhrs;
    ///////////////////////////////////////////////////////////////

    $styleset2 = 'style="width:28%; text-align:left;"';
    $middlecolumn = '<div class="newTableSubHeader" ' . $styleset2 . '> <span style="text-align:center; padding-left:20%;"> <u>Trip History</u> </span><br> 
               Triplogno : ' . $triplogno . '<br/> '
            . ' Route : ' . $routename . '<br/>'
            . ' Temprature Range : ' . round($mintemp) . ' To ' . round($maxtemp) . '<br/>'
            . ' Driver Name : ' . $drivername . '<br/>'
            . ' Driver No. : ' . $drivermobile1 . '<br/>'
            . ' Driver No.2 : ' . $drivermobile2 . '<br/>'
            . ' Total Budgeted kms : ' . $budgetedkms . 'kms<br/>'
            . ' Total Budgeted hrs : ' . $budgetedhrs . 'hrs<br/>'
            . ' Actual kms : ' . $res . 'kms<br/>'
            . ' Actual Hrs : ' . $estimated_time . 'hrs<br/>'
            . ' Billing Party : ' . $billingparty . 'kms<br/>'
            . ' Consignor : ' . $consignor . '<br/>'
            . ' Consignee : ' . $consignee . '<br/> '
            . '  </div>';
}

echo table_header($title, $subTitle, $columns, FALSE, $middlecolumn);


if ($columnlinks != null) {
    if (count($columnlinks) > 24) {
        echo "<style>.newTable th, .newTable td{padding:4px;}</style>";
    }
    $header = '';
    $header .= '<tr>';
    foreach ($columnlinks as $s_columns) {
        $header .= "<th>$s_columns</th>";
    }
    $header .= '</tr>';
    echo $header;
}
?>