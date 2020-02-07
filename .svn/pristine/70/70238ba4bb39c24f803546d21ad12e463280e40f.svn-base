<?php
require_once("class/config.inc.php");
require_once("class/class.devices.php");
require_once("class/class.trackee.php");
require_once("class/class.geotag.php");
require_once("class/Date.php");
include_once("lib/system/utilities.php");
include_once 'map_common_functions.php';

if (!isset($_SESSION)) 
{
    session_start();
}
class VOOutput {}

function getvehicles()
{
    $devicemanager = new DeviceManager($_SESSION['customerno']);
    $devices = $devicemanager->devicesformapping();
    return $devices;
}

function getdate_IST() {
    $ServerDate_IST = date("Y-m-d H:i:s");            
    return $ServerDate_IST;
}

function route_hist($vehicleid,$SDdate,$EDdate,$Shour,$Ehour)
{
    $device = Array();
    $device2 = Array();
    $totaldays = Array();
    
    $date = new Date();
    $currentdate = date("Y-m-d H:i:s"); 
    
    $vehicleid = GetSafeValueString($vehicleid,"string");
    
	$trackeeclass=new trackee();
    $devicekey = $trackeeclass->getdevicekey($vehicleid);
	
	
	
    $SDdate = GetSafeValueString($SDdate, 'string');
    $EDdate = GetSafeValueString($EDdate, 'string');
    
    $Shour = GetSafeValueString($Shour,"string");
    $Ehour = GetSafeValueString($Ehour,"string");
    
    $SDdate = date('Y-m-d',  strtotime($SDdate));
    $EDdate = date('Y-m-d',  strtotime($EDdate));
    
    if($Shour==24)
    {
        $Shour = "23:59:59";
        $startdate = $SDdate." ".$Shour;
    }
    else
    {
        $startdate = $SDdate." ".$Shour.":00:00";
    }
    if($Ehour==24)
    {
        $Ehour = "23:59:59";
        $enddate = $EDdate." ".$Ehour;
    }
    else
    {
        $enddate = $EDdate." ".$Ehour.":00:00";
    }

    if($SDdate!=$EDdate)
    {
        $devicedata[]=NULL;

        $STdate = $startdate;
        $STdate_end = date('Y-m-d', strtotime('+1 day', strtotime($STdate)));
        $STdate_end .= " 23:59:59";
        $counter = 0;
        while (strtotime($STdate) < strtotime($EDdate)) 
        {
            $totaldays[$counter][0] = $STdate;
            $totaldays[$counter][1] = $STdate_end;
            $STdate = date('Y-m-d', strtotime('+1 day', strtotime($STdate)));
            $STdate_end = $STdate." 23:59:59";
            $counter +=1;
        }
        $totaldays[$counter][0] = date('Y-m-d', strtotime($enddate));
        $totaldays[$counter][1] = date('Y-m-d H:i:s',  strtotime($enddate));
    }
    else
    {
        $totaldays[0][0] = $startdate;
        $totaldays[0][1] = $enddate;
    }
   
    if(isset($totaldays))
    {
        foreach ($totaldays as $Date)
        {
            $date = date("Ymd",strtotime($Date[0]));
           // $trackeeclass=new trackee();
            
			
          ///  $devicekey = $trackeeclass->getdevicekey($vehicleid);
            $customerno = $_SESSION['customerno'];
           $location = "customer/$customerno/$devicekey/sqlitefiles/locate/$date.sqlite";
            
            if(file_exists($location))
            {
                $location = "sqlite:".$location;
                $device = getdatafromsqlite($location,$Date,$vehicleid,$device);
            }
			
        }
    }
    
    if(isset($device2) && $device2!=NULL)
        $devicedata = array_merge($device,$device2);
    else
        $devicedata = array_merge($device);
    $finaloutput = array();
    if(isset($devicedata) && count($devicedata)>0)
    {
        $finaloutput = vehicleonmap($devicedata);
    }
    else
    {
        $Date = $totaldays[0];
        $date = date("Ymd",strtotime($Date[0]));
        $location = "=customer/$customerno/$devicekey/sqlitefiles/locate/$date.sqlite";
        if(file_exists($location))
        {
           // $trackeeclass = new trackee();
           // $devicekey = $trackeeclass->getdevicekey($vehicleid);
            $customerno = $_SESSION['customerno'];
            $location = "sqlite:".$location;
            $device = firstmappingforvehiclebydate_fromsqlite($location,$Date,$vehicleid);
        }
        $devicedata[] = $device;
        if(count(end($totaldays))>0)
        {
            $Date = end($totaldays);
        }
        $date = date("Ymd",strtotime($Date[0]));
        if(file_exists($location))
        {
         //   $trackeeclass = new trackee();
            //$devicekey = $trackeeclass->getdevicekey($vehicleid);            
            $customerno = $_SESSION['customerno'];
            $location = "sqlite:".$location;
            $device = firstmappingforvehiclebydate_fromsqlite($location,$Date,$vehicleid);
        }
        $devicedata[] = $device;
        $finaloutput = vehicleonmap($devicedata);
    }
    echo(json_encode($finaloutput));
}




function getdatafromsqlite($location,$Date,$vehicleid,$device)
{   
    $customerno = $_SESSION['customerno'];
    $basequery = "SELECT customerno,trackeeid,devicelat,devicelong,lastupdated FROM devicehistory ";
    $devicequery = "WHERE lastupdated BETWEEN '$Date[0]' AND '$Date[1]' ORDER BY lastupdated ASC";   
    $database = new PDO($location);
    $result = $database->query($basequery.$devicequery);
    $trackeeclass = new trackee();
    $trackees = $trackeeclass->getallmappedtrackees();
    if(isset($result))
    {
        if(isset($result) && $result != "")
        {
            foreach($result as $row)
            {
                if($row['devicelat']>0 && $row['devicelong']>0)
                {
                    $device[] = managerow($trackees, $row, $customerno);
                }
            }
        }
    }
    return $device;
}

function firstmappingforvehiclebydate_fromsqlite($location,$Date,$vehicleid)
{
    $customerno = $_SESSION['customerno'];
    $basequery = "SELECT customerno,trackeeid,devicelat,devicelong,lastupdated FROM devicehistory ";
    $devicequery = "WHERE lastupdated between '$Date[0]' and '$Date[1]' ORDER BY `lastupdated` ASC LIMIT 0,1";
    
    $database = new PDO($location);
    $result = $database->query($basequery.$devicequery);

    $trackeeclass = new trackee();
    $trackees = $trackeeclass->getallmappedtrackees();

    if(isset($result) && $result != "")
    {
        foreach($result as $row)
        {
            if($row['devicelat']>0 && $row['devicelong']>0)
            {
                $device = managerow($trackees, $row, $customerno);
            }
        }
    }
    return $device;
}
function managerow($drivers,$row, $customerno)
{
    
    
    $output = new VOOutput();                        
    $output->trackeeid = $row['trackeeid'];
    $output->devicelat = $row['devicelat'];
    $output->devicelong = $row['devicelong'];
    $output->lastupdated = $row['lastupdated'];    
    foreach($drivers as $driver)
    {
        if($driver->trackeeid == $output->trackeeid)
        {
            $output->tname = $driver->tname;
        }
    }
    return $output;
}
function vehicleonmap($device)
{
    //var_dump($device);
    class jsonop
    {
        // Empty class!
    }
    $finaloutput = array();
    $length = count($device);
    $counter = 0;
    $prevlat = 0;
    $prevlong = 0;
	$geotag_obj= new geotag();
    foreach($device as $thisdevice)
    {
        if($thisdevice==null)
        {
            break;
        }
        $counter++;
        $output = new jsonop();
        $date = new DateTime($thisdevice->lastupdated);
        $output->cgeolat = $thisdevice->devicelat;
        $output->cgeolong = $thisdevice->devicelong;
        $output->ctname = $thisdevice->tname;
        $output->clastupdated = $date->format('Y-m-d H:i:s');
        
        $lastupdated = strtotime($output->clastupdated);
        $today = date("Y-m-d H:i:s");        
        $today = strtotime($today);
        $output->clastupdated = diffdate($today, $lastupdated);
       
	   	
		$add=$geotag_obj->get_location_bylatlong($output->cgeolat,$output->cgeolong);               
        $output->geotag = $add;                                
        
        $output->ctrackeeid = $thisdevice->trackeeid;
        if($counter == 1)
            $output->image = 'images/Sflag.png';
        else if($counter == $length)
            $output->image = 'images/Eflag.png';
        else 
            $output->image = 'images/circle.png';
        if($prevlat == $thisdevice->devicelat && $prevlong == $thisdevice->devicelong)
        {
            // Do Nothing
        }
        else
        {
            $finaloutput[] = $output;            
        }
        $prevlat = $thisdevice->devicelat;
        $prevlong = $thisdevice->devicelong;        
    }
    return $finaloutput;
}
?>
