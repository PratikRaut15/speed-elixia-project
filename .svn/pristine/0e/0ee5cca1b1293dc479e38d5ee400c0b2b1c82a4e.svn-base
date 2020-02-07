<?php

$vehiclereps = array();
$counter = 0;
$vehiclename;
$total = 0;
$vehicles = GetVehicles_SQLite();
if($ReportType == 'Temperature')
{
foreach($reports as $report)
{
    $sqlitedate = date("Y-m-d H:i:s",strtotime($report->lastupdated));
    list($date, $time) = explode(' ', $sqlitedate);
    list($hour, $min, $sec) = explode(':', $time);
    $date1 = explode('-', $date);
    $date1[1] = (int)$date1[1]-1;
    $date1[2] = (int)$date1[2];
    $vehiclereps[$counter][0] = "new Date($date1[0], $date1[1], $date1[2],$hour, $min, $sec)";
    
    switch ($ReportType)
    {
        case 'Temperature':
            $id = $vehicleid;
            $vehiclereps[$counter][1] = gettemp($report->analog1);
            $vehiclename = $vehicles[$id]['vehicleno'];
            break;
    }
    $counter += 1;
}
}
else if($ReportType == 'TemperatureDaily')
{
foreach($reports as $report)
{
    $sqlitedate = date("Y-m-d H:i:s",strtotime($report->lastupdated));
    list($date, $time) = explode(' ', $sqlitedate);
    list($hour, $min, $sec) = explode(':', $time);
    $date1 = explode('-', $date);
    $date1[1] = (int)$date1[1]-1;
    $date1[2] = (int)$date1[2];
    $vehiclereps[$counter][0] = "new Date($date1[0], $date1[1], $date1[2],$hour, $min, $sec)";
    
    switch ($ReportType)
    {
        case 'TemperatureDaily':
            $id = $vehicleid;
            
        // Temperature Sensor        
            if($_SESSION['temp_sensors'] == '1')
            {
                $vehicle = getunitdetailsfromvehid($id);                
                $tempselect = '1';
                $tempcontrol = new tempcontrol();                
                 $vehiclereps[$counter][1] = 'Not Active';
                $s = "analog".$vehicle->tempsen1;                                                                     
                if($vehicle->tempsen1 != 0 && $report->$s != 0)
                {
                    $vehiclereps[$counter][1] = gettemp($report->$s);
                    $tempcontrol->min1 = $vehicle->temp1_min;
                    $tempcontrol->max1 = $vehicle->temp1_max;                            
                    $vehiclename = $vehicles[$id]['vehicleno'];
                }
                else
                {
                    $vehiclereps[$counter][1] = '0';
                    $vehiclename = $vehicles[$id]['vehicleno'];
                }
            }
            
            if($_SESSION['temp_sensors'] == '2')
            {
                $s = "analog".$vehicle->tempsen1;                                                
                $vehiclereps[$counter][1] = gettemp($report->$s);
                $vehiclename = $vehicles[$id]['vehicleno'];                
            }
    }
    $counter += 1;
}
}
?>