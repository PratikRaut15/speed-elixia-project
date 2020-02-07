<?php
include '../../lib/bo/GeoCoder.php';
include '../../lib/bo/VehicleManager.php';
include '../../lib/system/utilities.php';
include '../../lib/components/ajaxpage.inc.php';

class jsonop
{
    // Empty class!
}

if(!isset($_SESSION))
{
    session_start();
}
function getlocs()
{
    $geomanager = new GeoCoder($_SESSION['customerno']);
    $locations = $geomanager->get_all_location();
    return $locations;
}
function getloc($geotestid)
{
    $geotestid = GetSafeValueString($geotestid, 'long');
    $geomanager = new GeoCoder($_SESSION['customerno']);
    $location = $geomanager->get_location($geotestid);
    return $location;
}
function deletechk($chkid)
{
    $chkid = GetSafeValueString($chkid, 'long');
    $checkpointmanager = new CheckpointManager($_SESSION['customerno']);
    $checkpointmanager->DeleteCheckpoint($chkid, $_SESSION['userid']);
}
function addlocation($form)
{
    $location = new VOVehicle();
    $location->location = GetSafeValueString($form["locName"], "string");
    $location->city = GetSafeValueString($form["tbCity"], "string");
    $location->state = GetSafeValueString($form["tbState"], "string");
    $location->geolat = GetSafeValueString($form["geolat"], "float");
    $location->geolong = GetSafeValueString($form["geolong"], "float");
    $location->customerno = $_SESSION['customerno'];
    $geocoder = new GeoCoder($_SESSION['customerno']);
    $geocoder->SaveLocation($location, $_SESSION['userid']);
}
function modifylocation($form)
{
    $location = new VOVehicle();
    $location->geotestid = GetSafeValueString($form["geotestid"], "string");
    $location->location = GetSafeValueString($form["locName"], "string");
    $location->city = GetSafeValueString($form["tbCity"], "string");
    $location->state = GetSafeValueString($form["tbState"], "string");
    $location->geolat = GetSafeValueString($form["geolat"], "float");
    $location->geolong = GetSafeValueString($form["geolong"], "float");
    $location->customerno = $_SESSION['customerno'];
    $geocoder = new GeoCoder($_SESSION['customerno']);
    $geocoder->EditLocation($location, $_SESSION['userid']);
}
function check_loc_name($locN)
{
    $locN = GetSafeValueString($locN, 'string');
    $geomanager = new GeoCoder($_SESSION['customerno']);
    $locations = $geomanager->get_all_location();
    $status = NULL;
    if(isset($locations))
    {
        foreach($locations as $location)
        {
            if($location->location == $locN)
            {
                $status = "notok";
                break;
            }
        }
        if(!isset($status))
        {
            $status = "ok";
        }
    }   
    else
    {
        $status = "ok";
    }
    echo $status;
}
function printcheckpointsformapping()
{
    $checkpoints = getchks();
    if(isset($checkpoints))
    {
        foreach ($checkpoints as $checkpoint)   
        {
            $row = '<ul id="mapping">';
            $row .= "<li id='d_$checkpoint->checkpointid'";
            if($checkpoint->vehicleid>0)
            {
                $row .= ' class="driverassigned"';
            }
            else
            {
                $row .= ' class="driver"';
            }
            $row .= " onclick='st($checkpoint->checkpointid)'>";
            $row .= $checkpoint->cname;
            $row .= "<input type='hidden' id='dl_$checkpoint->checkpointid'";
            if($checkpoint->vehicleid!=0 && isset($checkpoint->vehicleid))
                $row .= " value='$checkpoint->vehicleid'>";
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
            if($vehicle->checkpointid>0)
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
            if($vehicle->checkpointid!=0 && isset($vehicle->checkpointid))
                $row .= " value='$vehicle->checkpointid'>";
            $row .="</li></ul>";
            echo $row;
        }
    }
}
function getvehicles()
{
    $vehiclemanager = new VehicleManager($_SESSION['customerno']);
    $vehicles = $vehiclemanager->getvehicleswithchks();
    return $vehicles;
}
function chk_eligibility($chkid)
{
    $chkid= GetSafeValueString($chkid,'long');

    if(isset($chkid))
    {
        $checkpointmanager = new CheckpointManager($_SESSION['customerno']);
        $checkpoint = $checkpointmanager->getvehicleforchk($chkid);
        if(isset($checkpoint) && $checkpoint->vehicleid > 0)
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

/*function mapchk($chkid,$vehicleid)
{
    $chkid= GetSafeValueString($chkid,'long');
    $vehicleid = GetSafeValueString($vehicleid,'long');
    
    if(isset($vehicleid))
    {
        $checkpointmanager = new CheckpointManager($_SESSION['customerno']);
        $checkpointmanager->mapchktovehicle($chkid, $vehicleid, $_SESSION['userid']);
    }
}
function demapchk($vehicleid)
{
    $vehicleid= GetSafeValueString($vehicleid,'long');
    
    if(isset($vehicleid))
    {
        $checkpointmanager = new CheckpointManager($_SESSION['customerno']);
        $checkpointmanager->demapchk($vehicleid);
    }
}
 * 
 */
function chkformapping()
{
    $checkpointmanager = new CheckpointManager($_SESSION['customerno']);
    $checkpoints = $checkpointmanager->getcheckpointsforcustomer();
    $finaloutput = array();
    if(isset($checkpoints))
    {
        foreach($checkpoints as $thischeckpoint)
        {
            $output = new jsonop();
            $output->cgeolat = $thischeckpoint->cgeolat;
            $output->cgeolong = $thischeckpoint->cgeolong;
            $output->cname = $thischeckpoint->cname;        
            $output->crad = $thischeckpoint->crad*1000;
            $finaloutput[] = $output;
        }
    }
    $ajaxpage = new ajaxpage();
    $ajaxpage->SetResult($finaloutput);
    $ajaxpage->Render();
}

function view_checkpoint_by_id($chkid)
{
	$chkid= GetSafeValueString($chkid,'long');
	$checkpointmanager = new CheckpointManager($_SESSION['customerno']);
	$add=$checkpointmanager->get_checkpoint($chkid);
   	echo json_encode($add);
	
 
 
}

function editchk($chkid, $chkname, $chkrad)
{
    $chkname = GetSafeValueString($chkname,"string");
    $chkrad = GetSafeValueString($chkrad,"string");
    //$vehicleid= GetSafeValueString($vehicleid,"string");
    $chkid= GetSafeValueString($chkid,"string");
    $checkpointmanager = new CheckpointManager($_SESSION['customerno']);
    $checkpointmanager->updatechk($chkname, $chkrad, $chkid); 
}

function DelChkByVehicleid($chkid, $vehicleid){
    $checkpointmanager = new CheckpointManager($_SESSION['customerno']);
    $checkpointid = GetSafeValueString($chkid, "string");
    $vehicleid = GetSafeValueString($vehicleid, "string");
    $checkpointmanager->DeleteCheckpointModal($checkpointid, $vehicleid, $_SESSION['userid']);
}

?>
