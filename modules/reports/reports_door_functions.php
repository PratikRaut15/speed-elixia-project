<?php
include_once '../../lib/system/utilities.php';
include_once '../../lib/bo/UnitManager.php';
include_once '../../lib/bo/GeoCoder.php';
include_once '../../lib/comman_function/reports_func.php';
if (!isset($_SESSION)) {
    session_start();
    if (!isset($_SESSION['timezone'])) {
        $_SESSION['timezone'] = 'Asia/Kolkata';
    }
    date_default_timezone_set('' . $_SESSION['timezone'] . '');
}
function getDoorHist($STdate, $EDdate, $deviceid) {
    $customerno = $_SESSION['customerno'];
    $um = new UnitManager($customerno);
    $unit = $um->getunitdetailsfromdeviceid($deviceid);
    $days = get_door_data($STdate, $EDdate, $customerno, $unit->unitno, $deviceid, $unit->isDoorExt);
    if ($days != NULL && count($days) > 0) {
        $finalreport = create_door_html($days, $unit->is_door_opp, $STdate, $EDdate);
    } else {
        $finalreport = "<tr><td colspan='100%' style='text-align:center;'>No Data</td></tr></tbody></table>";
    }
    echo $finalreport;
}

function getDoorHist_pdf($customerno, $STdate, $EDdate, $deviceid, $vehicleno, $vgroupname = null) {
    $um = new UnitManager($customerno);
    $unitno = $um->getuidfromdeviceid($deviceid);
    $doorinvertval = $um->getDoorStatus($unitno);
    $days = get_door_data($STdate, $EDdate, $customerno, $unitno, $deviceid);
    $title = 'Door Sensor History';
    $subTitle = array(
        "Vehicle No: $vehicleno",
        "Start Date: $STdate",
        "End Date: $EDdate",
    );
    if (!is_null($vgroupname)) {
        $subTitle[] = "Group-name: $vgroupname";
    }
    echo pdf_header($title, $subTitle);
    echo "<table id='search_table_2' align='center' style='width: auto; font-size:13px; text-align:center;border-collapse:collapse; border:1px solid #000;'>";
    echo "<tbody>";
    echo "<tr style='background-color:#CCCCCC;font-weight:bold;'>";
    echo '<td style="width:auto;height:auto;">Start Time</td>';
    echo '<td style="width:auto;height:auto;">Start Location</td>';
    echo '<td style="width:auto;height:auto;">End Time</td>';
    echo '<td style="width:auto;height:auto;">End Location</td>';
    echo '<td style="width:auto;height:auto;">Door Status</td>';
    echo '<td style="width:auto;height:auto;">Duration [HH:MM:SS]</td>';
    echo "</tr>";
    if ($days != NULL && count($days) > 0) {
        $finalreport = create_door_html($days, $doorinvertval['is_door_op'], $STdate, $EDdate);
    } else {
        $finalreport = "<tr><td colspan='100%' style='text-align:center;'>No Data</td></tr></tbody></table>";
    }
    echo $finalreport;
}

function getDoorHist_excel($customerno, $STdate, $EDdate, $deviceid, $vehicleno, $vgroupname = null) {
    $um = new UnitManager($customerno);
    $unitno = $um->getuidfromdeviceid($deviceid);
    $doorinvertval = $um->getDoorStatus($unitno);
    $days = get_door_data($STdate, $EDdate, $customerno, $unitno, $deviceid);
    $title = 'Door Sensor History';
    $subTitle = array(
        "Vehicle No: $vehicleno",
        "Start Date: $STdate",
        "End Date: $EDdate",
    );
    if (!is_null($vgroupname)) {
        $subTitle[] = "Group-name: $vgroupname";
    }
    echo excel_header($title, $subTitle);
    echo "<table  id='search_table_2' style='width: 1120px; font-size:12px; text-align:center;border-collapse:collapse; border:1px solid #000;'><tbody>";
    echo "<tbody>";
    echo "<tr style='background-color:#CCCCCC;font-weight:bold;'>";
    echo '<td style="width:auto;height:auto;">Start Time</td>';
    echo '<td style="width:auto;height:auto;">Start Location</td>';
    echo '<td style="width:auto;height:auto;">End Time</td>';
    echo '<td style="width:auto;height:auto;">End Location</td>';
    echo '<td style="width:auto;height:auto;">Door Status</td>';
    echo '<td style="width:auto;height:auto;">Duration [HH:MM:SS]</td>';
    echo "</tr>";
    if ($days != NULL && count($days) > 0) {
        $finalreport = create_door_html($days, $doorinvertval['is_door_op'], $STdate, $EDdate);
    } else {
        $finalreport = "<tr><td colspan='100%' style='text-align:center;'>No Data</td></tr></tbody></table>";
    }
    echo $finalreport;
}

function get_door_data($STdate, $EDdate, $customerno, $unitno, $deviceid, $doorExt = NULL) {
    $GeoCoder_Obj = new GeoCoder($customerno);
    $use_location = $GeoCoder_Obj->get_use_geolocation();
    $totaldays = gendays_cmn($STdate, $EDdate);
    $days = array();
    if (isset($totaldays)) {
        foreach ($totaldays as $userdate) {
            $data = NULL;
            $location = "../../customer/$customerno/unitno/$unitno/sqlite/$userdate.sqlite";
            if (file_exists($location)) {
                $location = "sqlite:" . $location;
                $data = get_doordata_fromsqlite_new($location, $deviceid, $use_location, $doorExt);
            }
            if ($data != NULL && count($data) > 0) {
                $days = array_merge($days, $data);
            }
        }
    }
    return $days;
}

function get_doordata_fromsqlite_new($location, $deviceid, $use_location, $doorExt = NULL) {
    $devices = array();
    if ($doorExt == 1) {
        $query = "SELECT    devicehistory.ignition
                            , devicehistory.status
                            , devicehistory.lastupdated
                            , devicehistory.devicelat
                            , devicehistory.devicelong
                            , CASE WHEN (vehiclehistory.extbatt/100)>0 THEN 1 ELSE 0 END as digitalio
                    FROM    devicehistory
                    INNER JOIN unithistory ON unithistory.lastupdated = devicehistory.lastupdated
                    INNER JOIN vehiclehistory ON vehiclehistory.lastupdated = devicehistory.lastupdated
                    WHERE   devicehistory.deviceid= $deviceid
                    AND     devicehistory.status!='F'
                    group by devicehistory.lastupdated";
    } else {
        $query = "SELECT devicehistory.ignition, devicehistory.status, devicehistory.lastupdated, devicehistory.devicelat, devicehistory.devicelong,
            unithistory.digitalio from devicehistory INNER JOIN unithistory ON unithistory.lastupdated = devicehistory.lastupdated
            WHERE devicehistory.deviceid= $deviceid AND devicehistory.status!='F' group by devicehistory.lastupdated";
    } 
    try {
        $database = new PDO($location);
        $query1 = $query . " ORDER BY devicehistory.lastupdated ASC"; 
        $result = $database->query($query1);
        $laststatus = array();
        if (isset($result) && $result != "") {
            $key = 0;
            $last_key = -1;
            foreach ($result as $row) {
                if (@$laststatus['digitalio'] != $row['digitalio']) {
                    $device = new stdClass();
                    $device->uselocation = $use_location;
                    $device->digitalio = $row['digitalio'];
                    $device->ignition = $row['ignition'];
                    $device->starttime = $row['lastupdated'];
                    if (isset($devices[$last_key])) {
                        $duration = getduration_cmn($row['lastupdated'], $devices[$last_key]->starttime);
                        $devices[$last_key]->endtime = $row['lastupdated'];
                        $devices[$last_key]->endcgeolat = $row['devicelat'];
                        $devices[$last_key]->endcgeolong = $row['devicelong'];
                        $devices[$last_key]->duration = $duration;
                    }
                    $device->startcgeolat = $row['devicelat'];
                    $device->startcgeolong = $row['devicelong'];
                    $laststatus['digitalio'] = $row['digitalio'];
                    $devices[$key] = $device;
                    $last_key = $key;
                    $key++;
                }
            }
            $last_duration = getduration_cmn($row['lastupdated'], $devices[$last_key]->starttime);
            $devices[$last_key]->endtime = $row['lastupdated'];
            $devices[$last_key]->endcgeolat = $row['devicelat'];
            $devices[$last_key]->endcgeolong = $row['devicelong'];
            $devices[$last_key]->duration = $last_duration;
        }
    } catch (PDOException $e) {
        die($e);
    }
    return $devices;
}

/**
 * For html, pdf and excel
 */
function create_door_html($datarows, $doorinvert, $STdate, $ETdate) {
    $total_open = 0;
    $ETdate = date('Y-m-d', strtotime($ETdate));
    $end_time = ($ETdate == date('Y-m-d')) ? date('Y-m-d H:i:s') : "$ETdate 23:59:59";
    $total_time = round((getduration_cmn($end_time, "$STdate 00:00:00") / 60)); //in minutes
    if (isset($datarows)) {
        $open = 'OPEN';
        $close = 'CLOSED';
        $lastdate = NULL;
        $display = '';
        foreach ($datarows as $change) {
            $comparedate = date('d-m-Y', strtotime($change->endtime));
            if (strtotime($lastdate) != strtotime($comparedate)) {
                $display .= "<tr style='background-color:#D8D5D6;font-weight:bold;'><th colspan='6' style='text-align:center;'>Date: $comparedate</th></tr>";
                $lastdate = date('d-m-Y', strtotime($change->endtime));
            }
            //Removing Date Details From DateTime
            $change->starttime = date(speedConstants::DEFAULT_DATETIME, strtotime($change->starttime));
            $change->endtime = date(speedConstants::DEFAULT_DATETIME, strtotime($change->endtime));
            if (!empty($change->startcgeolat) || !empty($change->startcgeolong)) {
                $use_location = isset($change->uselocation) ? $change->uselocation : 0;
                $change->startlocation = location_cmn($change->startcgeolat, $change->startcgeolong, $use_location);
                $change->endlocation = location_cmn($change->endcgeolat, $change->endcgeolong, $use_location);
            } else {
                $change->startlocation = "Unable to Pull Location";
                $change->endlocation = "Unable to Pull Location";
            }
            $start_time_disp = date(speedConstants::DEFAULT_TIME, strtotime($change->starttime));
            $end_time_disp = date(speedConstants::DEFAULT_TIME, strtotime($change->endtime));
            $display .= "<tr><td>$start_time_disp</td><td>$change->startlocation</td><td>$end_time_disp</td><td>$change->endlocation</td>";
            if (door_status($doorinvert, $change->digitalio)) {
                $display .= "<td>$close</td>";
            } else {
                $total_open += $change->duration;
                $display .= "<td>$open</td>";
            }
            $hh_mm_ss = get_hh_ss($change->duration);
            $display .= "<td>$hh_mm_ss</td>";
            $display .= '</tr>';
        }
    }
    $total_open_min = round($total_open / 60);
    $total_close_min = $total_time - $total_open_min;
    $display .= '</tbody>';
    $display .= '</table>';
    $display .= '<br/><br/>';
    $display .= "<table align='center'  style='width:45%;text-align:center;border-collapse:collapse; border:1px solid #000;' class='table newTable'>";
    $display .= '<tbody><tr style="background-color:#CCCCCC;font-weight:bold;"><td colspan = "9" style="text-align:center;border-top:1px solid"><h4>Statistics</h4></td></tr>';
    $display .= '<tr><td style="text-align:center;">Total Door OPEN Time = ' . get_hh_mm($total_open_min * 60) . ' Hours</td></tr>';
    $display .= '<tr><td style="text-align:center;">Total Door CLOSE Time = ' . get_hh_mm($total_close_min * 60) . ' Hours</td></tr>';
    $display .= '</tbody></table>';
    return $display;
}

/* Funtion to fetch data of door sensor starts here */

function getDoorSensorData($STdate, $EDdate, $deviceid,$doorsensor,$isReport=0)
{
    $customerno = $_SESSION['customerno'];

    $EDdate = date('Y-m-d', strtotime($EDdate));
    $end_time = ($EDdate == date('Y-m-d')) ? date('Y-m-d H:i:s') : "$EDdate 23:59:59";
    $total_time = round((getduration_cmn($end_time, "$STdate 00:00:00") / 60)); //in minutes

    $um = new UnitManager($customerno);
    $unit = $um->getunitdetailsfromdeviceid($deviceid);
    @$days = getDoorSensorDataForDoor1OrDoor2($STdate, $EDdate, $customerno, $unit->unitno, $deviceid, $unit->isDoorExt,$doorsensor);
    if($_SESSION["role_modal"] == 'elixir'){
       
    }
    if($isReport){
        return @$days;
    }
    else{
    //
    if ($days != NULL && count($days) > 0) {
        $finalreport = createDoorSensorDataForDoor1OrDoor2Html($days,$doorsensor,$total_time);
    } else {
        $finalreport = "<tr><td colspan='100%' style='text-align:center;'>No Data</td></tr></tbody></table>";
    }
    echo $finalreport;
    }
}

/* Function to fetch darta of door sensor ends here */

function getYMDFormatDate($date)
{
    $format = 'Y-m-d H:i:s';
    $datee = DateTime::createFromFormat($format, date("Y-m-d", strtotime('12-02-2019') ));
    //echo "Format: $format; " . $date->format('Y-m-d H:i:s') . "\n";

    return $datee->format('Y-m-d H:i:s');
}

function getDoubleDoorHist($STdate, $EDdate, $deviceid){ 
    $customerno = $_SESSION['customerno'];
    $um = new UnitManager($customerno);
    $unit = $um->getunitdetailsfromdeviceid($deviceid);
    $days = get_double_door_data($STdate, $EDdate, $customerno, $unit->unitno, $deviceid, $unit->isDoorExt);
    if($_SESSION["role_modal"] == 'elixir'){
       // prettyPrint($days);
    }
    //
    if ($days != NULL && count($days) > 0) {
        $finalreport = create_double_door_html($days, $unit->is_door_opp, $STdate, $EDdate);
    } else {
        $finalreport = "<tr><td colspan='100%' style='text-align:center;'>No Data</td></tr></tbody></table>";
    }
    echo $finalreport;
}

function get_double_door_data($STdate, $EDdate, $customerno, $unitno, $deviceid, $doorExt = NULL) {
    $GeoCoder_Obj = new GeoCoder($customerno);
    $use_location = $GeoCoder_Obj->get_use_geolocation();
    $totaldays = gendays_cmn($STdate, $EDdate);
    $days = array();
    if (isset($totaldays)) {
        foreach ($totaldays as $userdate) {
            $data = NULL;
            $location = "../../customer/$customerno/unitno/$unitno/sqlite/$userdate.sqlite";
            if (file_exists($location)) {
                $location = "sqlite:" . $location;
                $data = get_doubledoordata_fromsqlite_new($location, $deviceid, $use_location, $doorExt);
            }
            if ($data != NULL && count($data) > 0) {
                $days = array_merge($days, $data);
            }
        }
    }
    return $days;
}

function getDoorSensorDataForDoor1OrDoor2($STdate, $EDdate, $customerno, $unitno, $deviceid, $doorExt = NULL,$doorsensor) {
    $GeoCoder_Obj = new GeoCoder($customerno);
    $use_location = $GeoCoder_Obj->get_use_geolocation();
    $totaldays = gendays_cmn($STdate, $EDdate);
    $days = array();
    if (isset($totaldays)) {
        foreach ($totaldays as $userdate) {
            $data = NULL;
            $location = "../../customer/$customerno/unitno/$unitno/sqlite/$userdate.sqlite";
            if (file_exists($location)) {
                $location = "sqlite:" . $location;
                $data = getDoorSensorDataForDoor1OrDoor2Fromsqlite($location, $deviceid, $use_location, $doorExt,$doorsensor);
            }
            if ($data != NULL && count($data) > 0) {
                $days = array_merge($days, $data);
            }
        }
    }
    return $days;
}

function getDoorSensorDataForDoor1OrDoor2Fromsqlite($location, $deviceid, $use_location, $doorExt = NULL,$doorsensor) {
    $devices = array();
    $query = "SELECT    devicehistory.ignition
                            , devicehistory.status
                            , devicehistory.lastupdated
                            , devicehistory.devicelat
                            , devicehistory.devicelong
                            , unithistory.digitalio
                            , CASE WHEN CAST(vehiclehistory.extbatt/100 AS int)>0 THEN 1 ELSE 0 END as doordigitalio
                    FROM    devicehistory
                    INNER JOIN unithistory ON unithistory.lastupdated = devicehistory.lastupdated
                    INNER JOIN vehiclehistory ON vehiclehistory.lastupdated = devicehistory.lastupdated
                    WHERE   devicehistory.deviceid= $deviceid
                    AND     devicehistory.status!='F'
                    group by devicehistory.lastupdated";
    try {
        $database = new PDO($location);
        $query1 = $query . " ORDER BY devicehistory.lastupdated ASC";
        $result = $database->query($query1); //echo "Query 1: ".$query1; exit();
        $newDataArray = [];
        /* Testing code starts here */
            foreach ($result as $key => $value) 
            {
                $newDataArray[$key]['ignition'] = $value['ignition'];
                $newDataArray[$key]['status'] = $value['status'];
                $newDataArray[$key]['lastupdated'] = $value['lastupdated'];
                $newDataArray[$key]['devicelat'] = $value['devicelat'];
                $newDataArray[$key]['devicelong'] = $value['devicelong'];
                $newDataArray[$key]['digitalio'] = $value['digitalio'];
                $newDataArray[$key]['doordigitalio'] = $value['doordigitalio'];
            }

        //$iterator1 = new ArrayCallbackIterator($valueList, "generateDoorStatusResults");  
        $generateDoorStatusResults = generateDoorStatusResults($newDataArray,$doorsensor);
       

        return $generateDoorStatusResults;
     /*   echo"<pre>";
        print_r($generateDoorStatusResults);
        exit();
        echo "<pre>";
        print_r($newDataArray); exit();*/
        /* Testing code ends here */

        
        $laststatus = array();
        
    } catch (PDOException $e) {
        die($e);
    }
    return $devices;
}

function generateDoorStatusResults(array $newDataArray, $doorSensor /*, $start_index,$current_sensor_status*/)
{
    $countDataArray = count($newDataArray);
    $currentStatus;
    $currentDoorStatus;
    $currentDoorStatusBoolean;
    $currentStartTime;
    $newDoorStatusArray = [];
    if($doorSensor==1)
    {
            foreach ($newDataArray as $key => $value) 
            {
               
                    if($key==0)
                    {
                        $currentStatus = $value['digitalio'];
                        $currentDoorStatusBoolean= $value['digitalio'];
                        $currentStartTime = $value['lastupdated'];
                        if($value['digitalio']==1)
                        {
                            $currentDoorStatus = 'Open';
                        }
                        else
                        {
                            $currentDoorStatus = 'Closed';
                        }
                    }
                    else if($key==$countDataArray-1)
                    {
                        if($value['digitalio']==$currentDoorStatusBoolean)
                        {
                            $newDoorStatusArray[$key]['Status'] = $currentDoorStatus;
                            $newDoorStatusArray[$key]['StartTime'] = $currentStartTime;
                            $newDoorStatusArray[$key]['EndTime'] = $value['lastupdated'];  
                            $newDoorStatusArray[$key]['Duration'] = getduration_cmn($value['lastupdated'], $currentStartTime);
                        }
                        else
                        {
                            $newDoorStatusArray[$key-1]['Status'] = $currentDoorStatus;
                            $newDoorStatusArray[$key-1]['StartTime'] = $currentStartTime;
                            $newDoorStatusArray[$key-1]['EndTime'] = $newDataArray[$key-1]['lastupdated'];  
                            $newDoorStatusArray[$key-1]['Duration'] = getduration_cmn($newDataArray[$key-1]['lastupdated'], $currentStartTime);

                            if($currentDoorStatus=='Open')
                            {
                                $newDoorStatusArray[$key]['Status'] = 'Closed';
                            }
                            if($currentDoorStatus=='Closed')
                            {
                                $newDoorStatusArray[$key]['Status'] = 'Open';
                            }

                            $newDoorStatusArray[$key]['StartTime'] = $newDataArray[$key-1]['lastupdated'];
                            $newDoorStatusArray[$key]['EndTime'] = $value['lastupdated'];  
                            $newDoorStatusArray[$key]['Duration'] = getduration_cmn($value['lastupdated'], $newDataArray[$key-1]['lastupdated']);
                        }
                    }
                    else
                    {
                        if($value['digitalio']==$currentDoorStatusBoolean)
                        {
                            continue;
                        }
                        else
                        {
                            $newDoorStatusArray[$key]['Status'] = $currentDoorStatus;
                            $newDoorStatusArray[$key]['StartTime'] = $currentStartTime;
                            $newDoorStatusArray[$key]['EndTime'] = $value['lastupdated'];  
                            $newDoorStatusArray[$key]['Duration'] = getduration_cmn($value['lastupdated'], $currentStartTime);

                            $currentStatus = $value['digitalio'];
                            $currentDoorStatusBoolean= $value['digitalio'];
                            $currentStartTime = $value['lastupdated'];
                            if($value['digitalio']==1)
                            {
                                $currentDoorStatus = 'Open';
                            }
                            else
                            {
                                $currentDoorStatus = 'Closed';
                            }
                        }
                    }
             
            } 
    }
    else
    {
        foreach ($newDataArray as $key => $value) 
            {
                    if($key==0)
                    {
                        $currentStatus = $value['doordigitalio'];
                        $currentDoorStatusBoolean= $value['doordigitalio'];
                        $currentStartTime = $value['lastupdated'];
                        if($value['doordigitalio']==1)
                        {
                            $currentDoorStatus = 'Open';
                        }
                        else
                        {
                            $currentDoorStatus = 'Closed';
                        }
                    }
                    else if($key==$countDataArray-1)
                    {
                        if($value['doordigitalio']==$currentDoorStatusBoolean)
                        {
                            $newDoorStatusArray[$key]['Status'] = $currentDoorStatus;
                            $newDoorStatusArray[$key]['StartTime'] = $currentStartTime;
                            $newDoorStatusArray[$key]['EndTime'] = $value['lastupdated'];  
                            $newDoorStatusArray[$key]['Duration'] = getduration_cmn($value['lastupdated'], $currentStartTime);
                        }
                        else
                        {
                            $newDoorStatusArray[$key-1]['Status'] = $currentDoorStatus;
                            $newDoorStatusArray[$key-1]['StartTime'] = $currentStartTime;
                            $newDoorStatusArray[$key-1]['EndTime'] = $newDataArray[$key-1]['lastupdated'];  
                            $newDoorStatusArray[$key-1]['Duration'] = getduration_cmn($newDataArray[$key-1]['lastupdated'], $currentStartTime);

                            if($currentDoorStatus=='Open')
                            {
                                $newDoorStatusArray[$key]['Status'] = 'Closed';
                            }
                            if($currentDoorStatus=='Closed')
                            {
                                $newDoorStatusArray[$key]['Status'] = 'Open';
                            }

                            $newDoorStatusArray[$key]['StartTime'] = $newDataArray[$key-1]['lastupdated'];
                            $newDoorStatusArray[$key]['EndTime'] = $value['lastupdated'];  
                            $newDoorStatusArray[$key]['Duration'] = getduration_cmn($value['lastupdated'], $newDataArray[$key-1]['lastupdated']);
                        }
                    }
                    else
                    {
                        if($value['doordigitalio']==$currentDoorStatusBoolean)
                        {
                            continue;
                        }
                        else
                        {
                            $newDoorStatusArray[$key]['Status'] = $currentDoorStatus;
                            $newDoorStatusArray[$key]['StartTime'] = $currentStartTime;
                            $newDoorStatusArray[$key]['EndTime'] = $value['lastupdated'];  
                            $newDoorStatusArray[$key]['Duration'] = getduration_cmn($value['lastupdated'], $currentStartTime);

                            $currentStatus = $value['doordigitalio'];
                            $currentDoorStatusBoolean= $value['doordigitalio'];
                            $currentStartTime = $value['lastupdated'];
                            if($value['doordigitalio']==1)
                            {
                                $currentDoorStatus = 'Open';
                            }
                            else
                            {
                                $currentDoorStatus = 'Closed';
                            }
                        }
                    }
            } 
    }
    
    return array_values($newDoorStatusArray);
}

function get_doubledoordata_fromsqlite_new($location, $deviceid, $use_location, $doorExt = NULL) {
    $devices = array();
    $query = "SELECT    devicehistory.ignition
                            , devicehistory.status
                            , devicehistory.lastupdated
                            , devicehistory.devicelat
                            , devicehistory.devicelong
                            , unithistory.digitalio
                            , CASE WHEN (vehiclehistory.extbatt/100)>0 THEN 1 ELSE 0 END as doordigitalio
                    FROM    devicehistory
                    INNER JOIN unithistory ON unithistory.lastupdated = devicehistory.lastupdated
                    INNER JOIN vehiclehistory ON vehiclehistory.lastupdated = devicehistory.lastupdated
                    WHERE   devicehistory.deviceid= $deviceid
                    AND     devicehistory.status!='F'
                    group by devicehistory.lastupdated";
    try {
        $database = new PDO($location);
        $query1 = $query . " ORDER BY devicehistory.lastupdated ASC";
        $result = $database->query($query1);
        $laststatus = array();
        if (isset($result) && $result != "") {
            $key = 0;
            $last_key = -1;
            foreach ($result as $row) {
                if (@$laststatus['digitalio'] != $row['digitalio'] || @$laststatus['doordigitalio'] != $row['doordigitalio']) {
                    $device = new stdClass();
                    $device->digitalio = $row['digitalio'];
                    $device->doordigitalio = $row['doordigitalio'];
                    $device->ignition = $row['ignition'];
                    $device->firstDoorStartTime = $row['lastupdated'];
                    $device->secondDoorStartTime = $row['lastupdated'];
                    if (isset($devices[$last_key]) && @$laststatus['digitalio'] != $row['digitalio']) {
                        $firstDoorDuration = getduration_cmn($row['lastupdated'], $devices[$last_key]->firstDoorStartTime);
                        $devices[$last_key]->firstDoorEndTime = $row['lastupdated'];
                        $devices[$last_key]->firstDoorDuration = $firstDoorDuration;
                    }
                    if (isset($devices[$last_key]) && @$laststatus['doordigitalio'] != $row['doordigitalio']) {
                        $secondDoorDuration = getduration_cmn($row['lastupdated'], $devices[$last_key]->secondDoorStartTime);
                        $devices[$last_key]->secondDoorEndTime = $row['lastupdated'];
                        $devices[$last_key]->secondDoorDuration = $secondDoorDuration;
                    }
                    $laststatus['digitalio'] = $row['digitalio'];
                    $laststatus['doordigitalio'] = $row['doordigitalio'];
                    $devices[$key] = $device;
                    $last_key = $key;
                    $key++;
                }
            }
            $previousFirstDoorDuration = getduration_cmn($row['lastupdated'], $devices[$last_key]->firstDoorStartTime);
            $previousSecondDoorDuration = getduration_cmn($row['lastupdated'], $devices[$last_key]->secondDoorStartTime);
            $devices[$last_key]->firstDoorEndTime = $row['lastupdated'];
            $devices[$last_key]->secondDoorEndTime = $row['lastupdated'];
            $devices[$last_key]->firstDoorDuration = $previousFirstDoorDuration;
            $devices[$last_key]->secondDoorDuration = $previousSecondDoorDuration;
        }
    } catch (PDOException $e) {
        die($e);
    }
    return $devices;
}

function createDoorSensorDataForDoor1OrDoor2Html($datarows,$doorsensor,$totalSelectedTime){
    $display = '';
    $totalDoorSensorOpenTime = 0;
    $totalDoorSensorCloseTime = 0;
    $total_open = 0;
    if(isset($datarows))
    {
        if($doorsensor==1)
        {
            $sensorDoorName = 'Door 1';
        }
        else
        {
            $sensorDoorName = 'Door 2';
        }
        foreach ($datarows as $key => $value) {
            if($value['Status']=='Open')
            {
                $total_open = $total_open + $value['Duration'];
            }
            
            $display .="<tr>";
            $display .= "<td>".$sensorDoorName."</td>";
            $display .= "<td>".$value['StartTime']."</td>";
            $display .= "<td>".$value['EndTime']."</td>";
            $display .= "<td>".$value['Status']."</td>";
            $display .= "<td>".get_hh_ss($value['Duration'])."</td>";
            $display .="</tr>";
        }
        $display .= '</tbody>';
        $display .= '</table>';
        $display .= '<br/><br/>';
    }

    $total_open_min = round($total_open / 60);
    $total_close_min = $totalSelectedTime - $total_open_min;

    $display .= "<table align='center'  style='width:45%;text-align:center;border-collapse:collapse; border:1px solid #000;' class='table newTable'>";
    $display .= '<tbody><tr style="background-color:#CCCCCC;font-weight:bold;"><td colspan = "9" style="text-align:center;border-top:1px solid"><h4>Statistics</h4></td></tr>';
    $display .= '<tr><td style="text-align:center;">Total '.$sensorDoorName.' OPEN Time =  '.get_hh_mm($total_open_min* 60).' Hours</td></tr>';
    $display .= '<tr><td style="text-align:center;">Total '.$sensorDoorName.' CLOSE Time =  '.get_hh_mm($total_close_min* 60).' Hours</td></tr>';
    $display .= '</tbody></table>';
    return $display;

}

function create_double_door_html($datarows, $doorinvert, $STdate, $ETdate) {
    $total_open = 0;
    $total_open_2 = 0;
    $ETdate = date('Y-m-d', strtotime($ETdate));
    $end_time = ($ETdate == date('Y-m-d')) ? date('Y-m-d H:i:s') : "$ETdate 23:59:59";
    $total_time = round((getduration_cmn($end_time, "$STdate 00:00:00") / 60)); //in minutes
    if (isset($datarows)) {
        $open = 'OPEN';
        $close = 'CLOSED';
        $lastdate = NULL;
        $display = '';
        foreach ($datarows as $change) {
            $comparedate = date('d-m-Y', strtotime($change->firstDoorEndTime));
            if (isset($change->firstDoorEndTime) && strtotime($lastdate) != strtotime($comparedate)) {
                $display .= "<tr style='background-color:#D8D5D6;font-weight:bold;'><th colspan='8' style='text-align:center;'>Date: $comparedate</th></tr>";
                $lastdate = date('d-m-Y', strtotime($change->firstDoorEndTime));
            }
            //Removing Date Details From DateTime
            $change->firstDoorStartTime = date(speedConstants::DEFAULT_DATETIME, strtotime($change->firstDoorStartTime));
            $change->firstDoorEndTime = date(speedConstants::DEFAULT_DATETIME, strtotime($change->firstDoorEndTime));
            $start_time_disp = date(speedConstants::DEFAULT_TIME, strtotime($change->firstDoorStartTime));
            $end_time_disp = date(speedConstants::DEFAULT_TIME, strtotime($change->firstDoorEndTime));
            $change->secondDoorStartTime = date(speedConstants::DEFAULT_DATETIME, strtotime($change->secondDoorStartTime));
            $change->secondDoorEndTime =isset($change->secondDoorEndTime) ? $change->secondDoorEndTime:$change->firstDoorEndTime;
            $change->secondDoorEndTime = date(speedConstants::DEFAULT_DATETIME, strtotime($change->secondDoorEndTime));
            $start_time_disp1 = date(speedConstants::DEFAULT_TIME, strtotime($change->secondDoorStartTime));
            $end_time_disp1 = date(speedConstants::DEFAULT_TIME, strtotime($change->secondDoorEndTime));
            $display .= "<tr><td>$start_time_disp</td><td>$end_time_disp</td>";
            if (door_status($doorinvert, $change->digitalio)) {
                $display .= "<td>$close</td>";
            } else {
                $total_open += $change->firstDoorDuration;
                $display .= "<td>$open</td>";
            }
            $hh_mm_ss = get_hh_ss($change->firstDoorDuration);
            $display .= "<td>$hh_mm_ss</td>";
            $display .= "<td>$start_time_disp1</td><td>$end_time_disp1</td>";
            if (door_status($doorinvert, $change->doordigitalio)) {
                $display .= "<td>$close</td>";
            } else {
                $total_open_2 += $change->secondDoorDuration;
                $display .= "<td>$open</td>";
            }
            $change->secondDoorDuration = isset($change->secondDoorDuration) ? $change->secondDoorDuration : $change->firstDoorDuration;
            $hh_mm_ss = get_hh_ss($change->secondDoorDuration);
            $display .= "<td>$hh_mm_ss</td>";
            $display .= '</tr>';
        }
    }
    $total_open_min = round($total_open / 60);
    $total_close_min = $total_time - $total_open_min;
    $total_open_min_2 = round($total_open_2 / 60);
    $total_close_min_2 = $total_time - $total_open_min_2;
    $display .= '</tbody>';
    $display .= '</table>';
    $display .= '<br/><br/>';
    $display .= "<table align='center'  style='width:45%;text-align:center;border-collapse:collapse; border:1px solid #000;' class='table newTable'>";
    $display .= '<tbody><tr style="background-color:#CCCCCC;font-weight:bold;"><td colspan = "9" style="text-align:center;border-top:1px solid"><h4>Statistics</h4></td></tr>';
    $display .= '<tr><td style="text-align:center;">Total Door1 OPEN Time = ' . get_hh_mm($total_open_min * 60) . ' Hours</td></tr>';
    $display .= '<tr><td style="text-align:center;">Total Door1 CLOSE Time = ' . get_hh_mm($total_close_min * 60) . ' Hours</td></tr>';
    $display .= '<tr><td style="text-align:center;">Total Door2 OPEN Time = ' . get_hh_mm($total_open_min_2 * 60) . ' Hours</td></tr>';
    $display .= '<tr><td style="text-align:center;">Total Door3 CLOSE Time = ' . get_hh_mm($total_close_min_2 * 60) . ' Hours</td></tr>';
    $display .= '</tbody></table>';
    return $display;
}

function getDoubleDoorHist_pdf($customerno, $STdate, $EDdate, $deviceid, $vehicleno, $vgroupname = null) {
    $um = new UnitManager($customerno);
    $unitno = $um->getuidfromdeviceid($deviceid);
    $doorinvertval = $um->getDoorStatus($unitno);
    $days = get_double_door_data($STdate, $EDdate, $customerno, $unitno, $deviceid);
    $title = 'Door Sensor History';
    $subTitle = array(
        "Vehicle No: $vehicleno",
        "Start Date: $STdate",
        "End Date: $EDdate",
    );
    if (!is_null($vgroupname)) {
        $subTitle[] = "Group-name: $vgroupname";
    }
    echo pdf_header($title, $subTitle);
    echo "<table id='search_table_2' align='center' style='width: auto; font-size:13px; text-align:center;border-collapse:collapse; border:1px solid #000;'>";
    echo "<tbody>";
    echo "<tr style='background-color:#CCCCCC;font-weight:bold;'>";
    echo '<td style="width:auto;height:auto;">Door1 Start Time</td>';
    echo '<td style="width:auto;height:auto;">Door1 End Time</td>';
    echo '<td style="width:auto;height:auto;">Door1 Status</td>';
    echo '<td style="width:auto;height:auto;">Door1 Duration [HH:MM:SS]</td>';
    echo '<td style="width:auto;height:auto;">Door2 Start Time</td>';
    echo '<td style="width:auto;height:auto;">Door2 End Time</td>';
    echo '<td style="width:auto;height:auto;">Door2 Status</td>';
    echo '<td style="width:auto;height:auto;">Door2 Duration [HH:MM:SS]</td>';
    echo "</tr>";
    if ($days != NULL && count($days) > 0) {
        $finalreport = create_double_door_html($days, $doorinvertval['is_door_op'], $STdate, $EDdate);
    } else {
        $finalreport = "<tr><td colspan='100%' style='text-align:center;'>No Data</td></tr></tbody></table>";
    }
    echo $finalreport;
}

function getDoubleDoorHist_excel($customerno, $STdate, $EDdate, $deviceid, $vehicleno, $vgroupname = null) {
    $um = new UnitManager($customerno);
    $unitno = $um->getuidfromdeviceid($deviceid);
    $doorinvertval = $um->getDoorStatus($unitno);
    $days = get_double_door_data($STdate, $EDdate, $customerno, $unitno, $deviceid);
    $title = 'Door Sensor History';
    $subTitle = array(
        "Vehicle No: $vehicleno",
        "Start Date: $STdate",
        "End Date: $EDdate",
    );
    if (!is_null($vgroupname)) {
        $subTitle[] = "Group-name: $vgroupname";
    }
    echo excel_header($title, $subTitle);
    echo "<table  id='search_table_2' style='width: 1120px; font-size:12px; text-align:center;border-collapse:collapse; border:1px solid #000;'><tbody>";
    echo "<tbody>";
    echo "<tr style='background-color:#CCCCCC;font-weight:bold;'>";
    echo '<td style="width:auto;height:auto;">Door1 Start Time</td>';
    echo '<td style="width:auto;height:auto;">Door1 End Time</td>';
    echo '<td style="width:auto;height:auto;">Door1 Status</td>';
    echo '<td style="width:auto;height:auto;">Door1 Duration [HH:MM:SS]</td>';
    echo '<td style="width:auto;height:auto;">Door1 Start Time</td>';
    echo '<td style="width:auto;height:auto;">Door1 End Time</td>';
    echo '<td style="width:auto;height:auto;">Door2 Status</td>';
    echo '<td style="width:auto;height:auto;">Door2 Duration [HH:MM:SS]</td>';
    echo "</tr>";
    if ($days != NULL && count($days) > 0) {
        $finalreport = create_double_door_html($days, $doorinvertval['is_door_op'], $STdate, $EDdate);
    } else {
        $finalreport = "<tr><td colspan='100%' style='text-align:center;'>No Data</td></tr></tbody></table>";
    }
    echo $finalreport;
}

function get_door_sensor_report_pdf($STdate, $EDdate,$deviceid,$vehicleno,$doorsensor){
        $title          = 'Door Sensor Report';
        $subTitle       = array(
             "Vehicle No: $vehicleno",
             "Start Date: $STdate",
             "End Date: $EDdate",
         );
     /*    if (!is_null($vgroupname)) {
             $subTitle[] = "Group-name: $vgroupname";
         }*/
         $customer_details = null;
         if (!isset($_SESSION['customerno'])) {
             $cm = new CustomerManager($customerno);
             $customer_details = $cm->getcustomerdetail_byid($customerno);
         }
        echo pdf_header($title, $subTitle, $customer_details);
         get_door_sensor_report_data($STdate, $EDdate,$deviceid,$doorsensor,$vehicleno,'pdf');
        // return $vehicleno;
}

function get_door_sensor_report_data($STate,$EDdate,$deviceid,$doorsensor,$vehicleno,$report_type){
    $customerno     = $_SESSION['customerno'];
    $EDdate         = date('Y-m-d', strtotime($EDdate));
    $end_time       = ($EDdate == date('Y-m-d')) ? date('Y-m-d H:i:s') : "$EDdate 23:59:59";
    $total_time     = round((getduration_cmn($end_time, "$STate 00:00:00") / 60)); //in minutes

    $um = new UnitManager($customerno);
    $unit = $um->getunitdetailsfromdeviceid($deviceid);
     $customerno =$_SESSION['customerno'];
      $totaldays = gendays_cmn($STate, $EDdate);
     $days = array();
     if (isset($totaldays)) {
         foreach ($totaldays as $userdate) {
             $lastday = getDoorSensorData($STate,$EDdate,$deviceid,$doorsensor,1);
             /*if ($lastday != null) {
                 $days = array_merge($days, $lastday);
             }*/
         }
     }


         if (isset($lastday) && count($lastday) > 0) {
             $STDate = $STate  ."00:00";
             $ETDate = $EDdate  ."23:59";
             switch ($report_type) {
             case 'pdf':
                 dispalyDoorSensorData_pdf($lastday,$doorsensor,$total_time);
                 break;
             case 'excel':
                 dispalyDoorSensorData_excel($lastday,$doorsensor,$total_time);
                 break;
             }
         }
 }

   function dispalyDoorSensorData_pdf($datarows,$doorsensor,$total_time){
     echo '<table id="search_table_2" style="width: auto; font-size:13px; text-align:center;border-collapse:collapse; border:1px solid #000;">
   <tbody>';
    $display = '';
    $totalDoorSensorOpenTime = 0;
    $totalDoorSensorCloseTime = 0;
    $total_open = 0;
         if($doorsensor==1)
        {
            $sensorDoorName = 'Door 1';
        }
        else
        {
            $sensorDoorName = 'Door 2';
        }
        if(isset($datarows)){
            echo '</tbody></table>';
    echo '<table  id="search_table_2" align="center" style="width: auto; font-size:13px; text-align:center;border-collapse:collapse; border:1px solid #000;">';
     echo "<tbody> <tr style='background-color:#CCCCCC;font-weight:bold;'><td style='width:50px;' >Door</td><td style='width:100px;height:auto;'>Start Time</td><td style='width:100px;height:auto;'>End Time</td> <td style='width:100px;height:auto;'>Duration [HH:MM]</td><td style='width:50px;height:auto;'>Status</td></tr>
        ";
        foreach ($datarows as $key => $value) {
            if($value['Status']=='Open')
            {
                $total_open = $total_open + $value['Duration'];
            }
            
            echo "<tr>";
            echo "<td>".$sensorDoorName."</td>";
            echo "<td>".$value['StartTime']."</td>";
            echo  "<td>".$value['EndTime']."</td>";
            echo "<td>".$value['Status']."</td>";
            echo "<td>".get_hh_ss($value['Duration'])."</td>";
            echo "</tr>";
        }
    }
    echo "</tbody></table><br/><br/>";
    $total_open_min = round($total_open / 60);
    $total_close_min = $total_time - $total_open_min;
    echo "<table align='center'  style='width:45%;text-align:center;border-collapse:collapse; border:1px solid #000;' class='table newTable'>";
    echo '<tbody><tr style="background-color:#CCCCCC;font-weight:bold;"><td colspan = "9" style="text-align:center;border-top:1px solid"><h4>Statistics</h4></td></tr>';
    echo '<tr><td style="text-align:center;">Total '.$sensorDoorName.' OPEN Time =  '.get_hh_mm($total_open_min* 60).' Hours</td></tr>';
    echo '<tr><td style="text-align:center;">Total '.$sensorDoorName.' CLOSE Time =  '.get_hh_mm($total_close_min* 60).' Hours</td></tr>';
    echo '</tbody></table>';
    }

function dispalyDoorSensorData_excel($datarows,$doorsensor,$total_time){
    $report = "";
     $report .='<table id="search_table_2" style="width: auto; font-size:13px; text-align:center;border-collapse:collapse; border:1px solid #000;"> <tbody>';
    $totalDoorSensorOpenTime = 0;
    $totalDoorSensorCloseTime = 0;
    $total_open = 0;
         if($doorsensor==1)
        {
            $sensorDoorName = 'Door 1';
        }
        else
        {
            $sensorDoorName = 'Door 2';
        }
        if(isset($datarows)){
             $report .= "</tbody></table>";
    $report .= '<table  id="search_table_2" align="center" style="width: auto; font-size:13px; text-align:center;border-collapse:collapse; border:1px solid #000;">';
     $report .= "<tbody> <tr style='background-color:#CCCCCC;font-weight:bold;'><td style='width:50px;' >Door</td><td style='width:100px;height:auto;'>Start Time</td><td style='width:100px;height:auto;'>End Time</td> <td style='width:100px;height:auto;'>Duration [HH:MM]</td><td style='width:50px;height:auto;'>Status</td></tr>
        ";
        foreach ($datarows as $key => $value) {
            if($value['Status']=='Open')
            {
                $total_open = $total_open + $value['Duration'];
            }
            $report .= "<tr>";
            $report .="<td>".$sensorDoorName."</td>";
            $report .="<td>".$value['StartTime']."</td>";
            $report .= "<td>".$value['EndTime']."</td>";
            $report .= "<td>".$value['Status']."</td>";
            $report.= "<td>".get_hh_ss($value['Duration'])."</td>";
            $report .="</tr>";
        }        $report .= "</table>";
    }
    $report .= "</tbody></table><br/><br/>";
    $total_open_min = round($total_open / 60);
    $total_close_min = $total_time - $total_open_min;
    $report .= "<table align='center'  style='width:45%;text-align:center;border-collapse:collapse; border:1px solid #000;' class='table newTable'>";
    $report .= '<tbody><tr style="background-color:#CCCCCC;font-weight:bold;"><td colspan = "9" style="text-align:center;border-top:1px solid"><h4>Statistics</h4></td></tr>';
     $report .= '<tr><td style="text-align:center;">Total '.$sensorDoorName.' OPEN Time =  '.get_hh_mm($total_open_min* 60).' Hours</td></tr>';
    $report .='<tr><td style="text-align:center;">Total '.$sensorDoorName.' CLOSE Time =  '.get_hh_mm($total_close_min* 60).' Hours</td></tr>';
    $report .= '</tbody></table>';
    echo $report;
}
?>
