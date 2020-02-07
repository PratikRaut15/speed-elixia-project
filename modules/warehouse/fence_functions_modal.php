<?php
include_once '../../lib/bo/GeofenceManager.php';
include_once '../../lib/bo/VehicleManager.php';
include_once '../../lib/bo/DeviceManager.php';
include_once '../../lib/system/utilities.php';
include_once '../../lib/components/ajaxpage.inc.php';


if(!isset($_SESSION))
{
    session_start();
}
function getfences()
{
    $geofencemanager = new GeofenceManager($_SESSION['customerno']);
    $geofences = $geofencemanager->getdistinctfencenames();
    return $geofences;
}
function delfence($fenceid)
{
    $geofencemanager = new GeofenceManager($_SESSION['customerno']);
    $fenceid = GetSafeValueString($fenceid, "string");
    $geofencemanager->DeleteGeofence($fenceid, $_SESSION['userid']);
}
function printfencesformapping()
{
    $geofences = getfences();
    if(isset($geofences))
    {
        foreach ($geofences as $geofence)   
        {
            $row = '<ul id="mapping">';
            $row .= "<li id='d_$geofence->fenceid'";
            if($geofence->vehicleid>0)
            {
                $row .= ' class="driverassigned"';
            }
            else
            {
                $row .= ' class="driver"';
            }
            $row .= " onclick='st($geofence->fenceid)'>";
            $row .= $geofence->fencename;
            $row .= "<input type='hidden' id='dl_$geofence->fenceid'";
            if($geofence->vehicleid!=0 && isset($geofence->vehicleid))
                $row .= " value='$geofence->vehicleid'>";
            $row .= "</li></ul>";
            echo $row;
        }
    }
}
function createfence($lat,$long,$fencename)
{
    $customerno = $_SESSION['customerno'];
    $userid = $_SESSION['userid'];
    $gm = new GeofenceManager($customerno);

    if(isset($fencename) && $fencename != "")
    {
        $fenceid = $gm->getfencefromname($fencename);
        if(isset($fenceid) && $fenceid > 0)
        {       
            echo('{"status":"samename"}');                
        }
        else
        {
            $fence = new VOFence();
            $fence->fencename = $fencename;
            $fence->userid = $userid;
            $gm->InsertFence($fence);

            $latarray = explode(",",$lat);
            $longarray = explode(",",$long);

            for($i=0;$i<count($latarray);$i++)
            {
                $geofence = new VOGeofence();
                $geofence->fenceid = $fence->fenceid;
                $geofence->geolat = $latarray[$i];
                $geofence->geolong = $longarray[$i];
                $geofence->userid = $userid;                
                $gm->SaveGeofence($geofence);
            }
            echo('{"status":"ok"}');
        }
    }
    else
    {
        echo('{"status":"detailsreqd"}');
    }
}
function createfencebyvehicle($lat,$long,$fencename,$vehicle_array)
{
    $customerno = $_SESSION['customerno'];
    $userid = $_SESSION['userid'];
    $gm = new GeofenceManager($customerno);
    $fencename;
    if(isset($fencename) && $fencename != "")
    {
        $fenceid = $gm->getfencefromname($fencename);
        if(isset($fenceid) && $fenceid > 0)
        {       
            echo('{"status":"samename"}');                
        }
        else
        {
            $fence = new VOFence();
            $fence->fencename = $fencename;
            $fence->userid = $userid;
            $fence->vehicles = $vehicle_array;
            $gm->InsertFence($fence);

            $latarray = explode(",",$lat);
            $longarray = explode(",",$long);

            for($i=0;$i<count($latarray);$i++)
            {
                $geofence = new VOGeofence();
                $geofence->fenceid = $fence->fenceid;
                $geofence->geolat = $latarray[$i];
                $geofence->geolong = $longarray[$i];
                $geofence->userid = $userid;                
                $gm->SaveGeofence($geofence);
            }
            
            $vehicles = explode(",",$vehicle_array);

            for($i=0;$i<count($vehicles);$i++)
            {
            $geofence = new VOGeofence();
            $geofence->fenceid = $fence->fenceid;
            $geofence->vehicle = $vehicles[$i];
            $geofence->userid = $userid;         
            $gm->InsertFenceMan($geofence);
            }
            //$geofence = new VOFence();
            //$geofence->$vehicles = $vehicle_array;
            //echo $geofence->$vehicles;
            //$gm->InsertFenceMan($geofence);
            
        }
    }
    else
    {
        echo('{"status":"detailsreqd"}');
    }
}
function createfencebyvehiclearray($form)
{
    $customerno = $_SESSION['customerno'];
    $userid = $_SESSION['userid'];
    $gm = new GeofenceManager($customerno);
    $fencename = GetSafeValueString($form["fenceName"], "string");
    $lat = GetSafeValueString($form["lt"], "string");
    $long = GetSafeValueString($form["lng"], "string");
    if(isset($fencename) && $fencename != "")
    {
        $fenceid = $gm->getfencefromname($fencename);
        if(isset($fenceid) && $fenceid > 0)
        {       
            $status = "samename";                
        }
        else
        {
            $fence = new VOFence();
            $fence->fencename = $fencename;
            $fence->userid = $userid;
            //$fence->vehicles = $vehicle_array;
            $gm->InsertFence($fence);

            $latarray = explode(",",$lat);
            $longarray = explode(",",$long);

            for($i=0;$i<count($latarray);$i++)
            {
                $geofence = new VOGeofence();
                $geofence->fenceid = $fence->fenceid;
                $geofence->geolat = $latarray[$i];
                $geofence->geolong = $longarray[$i];
                $geofence->userid = $userid;                
                $gm->SaveGeofence($geofence);
            }
            $Vehicles = array();
            foreach($form as $single_post_name=>$single_post_value)
            {
                if (substr($single_post_name, 0, 11) == "to_vehicle_")
                    $Vehicles[] = substr($single_post_name, 11, 12);
            }

            for($i=0;$i<count($Vehicles);$i++)
            {
            $geofence = new VOGeofence();
            $geofence->fenceid = $fence->fenceid;
            $geofence->vehicle = $Vehicles[$i];
            $geofence->userid = $userid;         
            $gm->InsertFenceMan($geofence);
            }
            $status = "ok";
            //$geofence = new VOFence();
            //$geofence->$vehicles = $vehicle_array;
            //echo $geofence->$vehicles;
            //$gm->InsertFenceMan($geofence);
            
        }
    }
    else
    {       
            $status = "detailsreqd";
    }
    echo $status;
}
function mapfence($fenceid,$vehicleid)
{
    $fenceid= GetSafeValueString($fenceid,"string");
    $vehicleid = GetSafeValueString($vehicleid,"string");
    
    if(isset($vehicleid))
    {
        $gm = new GeofenceManager($_SESSION['customerno']);
        $gm->mapfencetovehicle($fenceid, $vehicleid);
    }
}
function addfencetovehicle($form)
{
    $fencess = array();
    foreach($form as $single_post_name=>$single_post_value)
    {
        if (substr($single_post_name, 0, 9) == "to_fence_")
            $fencess[] = substr($single_post_name, 9, 10);
        else if (substr($single_post_name, 0, 15) == "to_added_fence_")
            $fencess[] = substr($single_post_name, 15, 16);
    }
    $fences = $fencess;
    $vehicleid = GetSafeValueString($form["fencetovehicle"], "string");
    $gm = new GeofenceManager($_SESSION['customerno']);
    $gm->addfencetovehicle($fences, $vehicleid, $_SESSION['userid']);
}
function demapfence($vehicleid)
{
    $vehicleid= GetSafeValueString($vehicleid,"string");
    
    if(isset($vehicleid))
    {
        $gm = new GeofenceManager($_SESSION['customerno']);
        $gm->demapfence($vehicleid);
    }
}
function getvehicleforfence($fenceid)
{
    $devicemanager = new DeviceManager($_SESSION['customerno']);
    $fenceid = GetSafeValueString($fenceid,'long');
    $devices = $devicemanager->deviceforfence($fenceid);
    $finaloutput = array();
    if(isset($devices))
    {
        foreach($devices as $thisdevice)
        {
            $output = new jsonop();
            $date = new DateTime($thisdevice->lastupdated);
            $output->cgeolat = $thisdevice->devicelat;
            $output->cgeolong = $thisdevice->devicelong;
            $output->cname = $thisdevice->vehicleno;   
            $output->cdrivername = $thisdevice->drivername;
            $output->cdriverphone = $thisdevice->driverphone;    
            $output->cspeed = $thisdevice->curspeed;
            $output->clastupdated = $date->format('D d-M-Y H:i');
            $output->cvehicleid = $thisdevice->vehicleid;
            if($thisdevice->type=='Car')
                $output->image = '../../images/Car.png';
            else if($thisdevice->type=='Truck')
                $output->image = '../../images/Truck.png';
            else if($thisdevice->type=='Cab')
                $output->image = '../../images/Cab.png';
            else if($thisdevice->type=='Bus')
                $output->image = '../../images/Bus.png';
            $finaloutput[] = $output;
        }
    }
    $ajaxpage = new ajaxpage();
    $ajaxpage->SetResult($finaloutput);
    $ajaxpage->Render();
}
function fence_eligibility($fenceid)
{
    $gm = new GeofenceManager($_SESSION['customerno']);
    $fenceid= GetSafeValueString($fenceid,'long');

    if(isset($fenceid))
    {
        $fence = $gm->getvehicleforfence($fenceid);
        if(isset($fence) && $fence->vehicleid > 0)
        {
            echo("notok");
        }
        else 
        {
            echo("ok");
        }
    }   
    else
    {
        echo("notok");
    }
}
function fenceonmap($fenceid)
{
    $fenceid = GetSafeValueString($fenceid,'long');
    $fencemanager = new GeofenceManager($_SESSION['customerno']);
    $fence = $fencemanager->get_geofence_from_fenceid($fenceid);
    $finaloutput = array();
    if(isset($fence))
    {
        foreach($fence as $fencepart)
        {
            $output = new jsonop();
            $output->cgeolat = $fencepart->geolat;
            $output->cgeolong = $fencepart->geolong;
            $finaloutput[] = $output;
        }
    }
    $ajaxpage = new ajaxpage();
    $ajaxpage->SetResult($finaloutput);
    $ajaxpage->Render();
}
function editfence($fenceid, $fencename)
{
    $geofencemanager = new GeofenceManager($_SESSION['customerno']);
    $fenceid = GetSafeValueString($fenceid, "string");
    $fencename = GetSafeValueString($fencename, "string");
    $geofencemanager->updategeofence($fenceid, $fencename);
}
function DelFenceByVehicleid($fenceid,$vehicleid)
{
    $geofencemanager = new GeofenceManager($_SESSION['customerno']);
    $fenceid = GetSafeValueString($fenceid, "string");
    $vehicleid = GetSafeValueString($vehicleid, "string");
    $geofencemanager->DeleteFenceByVehicleid($fenceid, $vehicleid, $_SESSION['userid']);
}
function get_all_fence()
{
    $geofencemanager = new GeofenceManager($_SESSION['customerno']);
    $geofences = $geofencemanager->getallfencenames();
    return $geofences;
}
?>
