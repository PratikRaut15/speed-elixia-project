<?php
include_once '../../lib/bo/CheckpointManager.php';
include_once '../../lib/bo/VehicleManager.php';
include_once '../../lib/system/utilities.php';
include_once '../../lib/components/ajaxpage.inc.php';

class jsonop
{
    // Empty class!
}

if(!isset($_SESSION))
{
    session_start();
}
function getenhchks()
{
    $checkpointmanager = new CheckpointManager($_SESSION['customerno']);
    $checkpoints = $checkpointmanager->getenhcheckpointsforcustomer();
    return $checkpoints;
}
function getenhchk($chkid)
{
    $chkid = GetSafeValueString($chkid, 'long');
    $checkpointmanager = new CheckpointManager($_SESSION['customerno']);
    $checkpoint = $checkpointmanager->get_enhcheckpoint($chkid);
    return $checkpoint;
}
function get_all_chk()
{
    $checkpointmanager = new CheckpointManager($_SESSION['customerno']);
    $checkpoints = $checkpointmanager->getallcheckpoints();
    return $checkpoints;
}
function delete_enhchk($enh_chkid)
{
    $enh_chkid = GetSafeValueString($enh_chkid, 'long');
    $checkpointmanager = new CheckpointManager($_SESSION['customerno']);
    $checkpointmanager->DeleteEnhCheckpoint($enh_chkid, $_SESSION['userid']);
}
function add_enh_chk($form)
{
    $checkpoint = new VOCheckpoint();
    $checkpoint->checkpointid = GetSafeValueString($form["checkpointid"], "string");
    $Vehicles = array();
    foreach($form as $single_post_name=>$single_post_value)
    {
        if (substr($single_post_name, 0, 11) == "to_vehicle_")
            $Vehicles[] = substr($single_post_name, 11, 12);
    }
    $checkpoint->vehicles = $Vehicles;
    $checkpoint->emails = GetSafeValueString($form["emailarray"], "string");
    $checkpoint->phones = GetSafeValueString($form["phonearray"], "string");
    //$checkpoint->customerno = $customerno;
    $checkpointmanager = new CheckpointManager($_SESSION['customerno']);
    $checkpointmanager->save_enhcheckpoint($checkpoint, $_SESSION['userid']);
}
function modify_enhchk($form)
{
    $checkpoint = new VOCheckpoint();
    $checkpoint->enh_checkpointid = GetSafeValueString($form["enh_chkId"], "string");
    $checkpoint->comdet = GetSafeValueString($form["comdet"], "string");
    $checkpointmanager = new CheckpointManager($_SESSION['customerno']);
    $checkpointmanager->edit_enhcheckpoint($checkpoint, $_SESSION['userid']);
}
function check_chk_name($chkN)
{
    $chkN = GetSafeValueString($chkN, 'string');
    $checkpointmanager = new CheckpointManager($_SESSION['customerno']);
    $checkpoints = $checkpointmanager->getallcpoints();
    $status = NULL;
    if(isset($checkpoints))
    {
        foreach($checkpoints as $thischeckpoint)
        {
            if($thischeckpoint->cname == $chkN)
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
