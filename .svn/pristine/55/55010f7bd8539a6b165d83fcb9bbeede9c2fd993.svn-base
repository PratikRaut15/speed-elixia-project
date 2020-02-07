<?php
include_once '../../lib/bo/GeofenceManager.php';
include_once '../../lib/bo/VehicleManager.php';
include_once '../../lib/bo/DeviceManager.php';
include_once '../../lib/system/utilities.php';
include_once '../../lib/comman_function/reports_func.php';
include_once '../../lib/components/ajaxpage.inc.php';

class jsonop
{
    // Empty class!
}

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
function getvehicles_all()
{
    $VehicleManager = new VehicleManager($_SESSION['customerno']);
    $vehicles = $VehicleManager->get_all_vehicles();
    return $vehicles;
}
function getaddedvehicles_fence($fenceid)
{
    $geofencemanager = new GeofenceManager($_SESSION['customerno']);
    $vehicles = $geofencemanager->get_added_vehicles_fence($fenceid);
    return $vehicles;
}
function getvehicles()
{
    $vehiclemanager = new VehicleManager($_SESSION['customerno']);
    $vehicles = $vehiclemanager->getvehiclewithfences();
    return $vehicles;
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
function printvehiclesformapping()
{
    $vehicles = getvehicles();
    if(isset($vehicles))
    {
        foreach ($vehicles as $vehicle)   
        {
            $row = "<ul id='mapping'>";
            $row .= "<li id='v_$vehicle->vehicleid'";
            if($vehicle->fenceid>0)
            {
                $row .= ' class="vehicleassigned"';
            }
            else
            {
                $row .= ' class="vehicle"';
            }
            $row .= " onclick='sd($vehicle->vehicleid)'>";
            $row .=  $vehicle->vehicleno;
            $row .= "<input type='hidden' id='vl_$vehicle->vehicleid'";
            if($vehicle->fenceid!=0 && isset($vehicle->fenceid))
                $row .= " value='$vehicle->fenceid'>";
            $row .="</li></ul>";
            echo $row;
        }
    }
}
function createfence($lat,$long,$fencename,$vehicle_array)
{   
    $customerno = $_SESSION['customerno'];
    $userid = $_SESSION['userid'];
    $gm = new GeofenceManager($customerno);
    $ploygonLatLongJsonArray = [];
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
            /* $gm->InsertFence($fence); */

            $latarray = explode(",",$lat);
            $longarray = explode(",",$long);

            for($i=0;$i<count($latarray);$i++)
            {
               /*  $geofence = new VOGeofence();
                $geofence->fenceid = $fence->fenceid;
                $geofence->geolat = $latarray[$i];
                $geofence->geolong = $longarray[$i];
                $geofence->userid = $userid;      */
                
                $ploygonLatLongJsonArray[$i]['cgeolat'] = $latarray[$i];
                $ploygonLatLongJsonArray[$i]['cgeolong'] = $longarray[$i];
                //$gm->SaveGeofence($geofence);
            }   
            /* echo"Normal data :<pre>"; print_r($ploygonLatLongJsonArray); 
            echo"<br>JSON DATA: ".json_encode($ploygonLatLongJsonArray); */
            $jsonData = json_encode($ploygonLatLongJsonArray);
            $gm->InsertFence($fence,$jsonData);
            
            $vehicles = explode(",",$vehicle_array);

            for($i=0;$i<count($vehicles);$i++)
            {
            $geofence = new VOGeofence();
            $geofence->fenceid = $fence->fenceid;
            $geofence->vehicle = $vehicles[$i];
            $geofence->userid = $userid;         
            $gm->InsertFenceMan($geofence);
            }
            $status = "ok";
        }
    }
    else
    {
        $status = "detailsreqd";
    }
    echo $status;
}
function editfencing($lat,$long,$fencename,$fenceid,$vehicle_array)
{
    $customerno = $_SESSION['customerno'];
    $userid = $_SESSION['userid'];
    $gm = new GeofenceManager($customerno);
    $ploygonLatLongJsonArray = [];
    if(isset($fencename) && $fencename != "")
    {
        $fencingid = $gm->getfencefromnameid($fencename,$fenceid);
        if(isset($fencingid) && $fencingid > 0)
        {       
            $status = "notok";               
        }
        else
        {
            $gm->updategeofence($fenceid, $fencename) ;               
            //$gm->DeleteGeofenceData($fenceid, $userid);

            $latarray = explode(",",$lat);
            $longarray = explode(",",$long);

            for($i=0;$i<count($latarray);$i++)
            {
                $geofence = new stdClass();
                $geofence->fenceid = $fenceid;
               /*  $geofence->geolat = $latarray[$i];
                $geofence->geolong = $longarray[$i]; */
                $geofence->userid = $userid;       
                $ploygonLatLongJsonArray[$i]['cgeolat'] = $latarray[$i];
                $ploygonLatLongJsonArray[$i]['cgeolong'] = $longarray[$i];         
                $geofence->ploygonLatLongJsonArray = json_encode($ploygonLatLongJsonArray);
                $gm->SaveGeofence($geofence);
            }
            $vehicles = explode(",",$vehicle_array);
           /*  if($vehicles){
            $gm->delfenceman($fenceid);
            } */
            for($i=0;$i<count($vehicles);$i++)
            {
            $geofence = new VOGeofence();
            $geofence->fenceid = $fenceid;
            $geofence->vehicle = $vehicles[$i];
            $geofence->userid = $userid;         
            $gm->InsertFenceMan($geofence);
            }
            $status = "ok";
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
    echo $fence[0]->polygonLatLongJson; //exit();
    //echo"Fence Data is: <pre>"; print_r($fence); exit();
   /*  $finaloutput = array();
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
    $ajaxpage->Render(); */
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
function getfencenamebyid($fenceid)
{
    $geofencemanager = new GeofenceManager($_SESSION['customerno']);
    $fenceid = GetSafeValueString($fenceid, "string");
    $geofences = $geofencemanager->fencenamebyid($fenceid);
    return $geofences;
}
?>
