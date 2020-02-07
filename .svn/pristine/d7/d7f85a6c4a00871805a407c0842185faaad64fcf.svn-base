<?php
include_once '../../lib/autoload.php';
include_once '../../lib/system/utilities.php';
include_once '../../lib/components/ajaxpage.inc.php';

class jsonop {
    // Empty class!
}

if (!isset($_SESSION)) {
    session_start();
}
if(isset($_POST['action']) && isset($_POST['chkN']))
{
    if ($_POST['action']==='ajaxCheckPointTypeCreation') {
        $data["chktype"] = $_POST['chkN'];  
        addchktype($data);
    } 
}

if(isset($_POST['action']) && ($_POST['action']==='updateCheckpointTypes'))
{
        $getCheckPointTypes = getchktypes(); 
        $renderData = '<option value="0">Select Type</option>';
         foreach ($getCheckPointTypes as $type) 
         {
            $renderData.= "<option value='$type->ctid'>$type->name</option>";
         }
         echo $renderData;
}

function getchks() {
    $checkpointmanager = new CheckpointManager($_SESSION['customerno']);
    $checkpoints = $checkpointmanager->getcheckpointsforcustomer();
    return $checkpoints;
}

function getchktypes() { 
    $checkpointmanager = new CheckpointManager($_SESSION['customerno']);
    $checkpoints = $checkpointmanager->getcheckpointtypesforcustomer();
    return $checkpoints;
}

function getchk($chkid) {
    $chkid = GetSafeValueString($chkid, 'long');
    $checkpointmanager = new CheckpointManager($_SESSION['customerno']);
    $checkpoint = $checkpointmanager->get_checkpoint($chkid);
    return $checkpoint;
}

function getchktype($ctid) {
    $ctid = GetSafeValueString($ctid, 'long');
    $checkpointmanager = new CheckpointManager($_SESSION['customerno']);
    $checkpoint = $checkpointmanager->get_checkpointtype($ctid);
    return $checkpoint;
}

function deletechk($chkid) {
    $chkid = GetSafeValueString($chkid, 'long');
    $checkpointmanager = new CheckpointManager($_SESSION['customerno']);
    $checkpointmanager->DeleteCheckpoint($chkid, $_SESSION['userid']);
}

function deletechktype($ctid) {
    $ctid = GetSafeValueString($ctid, 'long');
    $checkpointmanager = new CheckpointManager($_SESSION['customerno']);
    $checkpointmanager->DeleteCheckpointtype($ctid, $_SESSION['userid']);
}

function addchk($form) {
    $checkpoint = new VOCheckpoint();
    $checkpoint->cphone = GetSafeValueString($form["phonenumber"], "string");
    $checkpoint->cemail = GetSafeValueString($form["cemail"], "string");
    $checkpoint->cadd = GetSafeValueString($form["chkA"], "string");
    $checkpoint->cgeolat = GetSafeValueString($form["cgeolat"], "float");
    $checkpoint->cgeolong = GetSafeValueString($form["cgeolong"], "float");
    $checkpoint->cname = GetSafeValueString($form["chkName"], "string");
    $checkpoint->crad = GetSafeValueString($form["crad"], "float");
    $checkpoint->eta = GetSafeValueString($form["STime"], "string");
    $checkpoint->chktype = GetSafeValueString($form["chktypes"], "string");
    $checkpoint->customerno = $customerno;
    $vehicles = array();
    foreach ($form as $single_post_name => $single_post_value) {
        if (substr($single_post_name, 0, 11) == "to_vehicle_") {
            $vehicles[] = substr($single_post_name, 11, 12);
        }
    }
    //print_r($Groups);
    $checkpoint->vehicles = $vehicles;
    $checkpointmanager = new CheckpointManager($_SESSION['customerno']);
    $checkpointmanager->SaveCheckpoint($checkpoint, $_SESSION['userid']);
}

function addchktype($form) {
    $checkpoint = new stdClass();
    $checkpoint->name = GetSafeValueString($form["chktype"], "string"); 
    $checkpoint->customerno = $_SESSION['customerno']; 
    $checkpointmanager = new CheckpointManager($_SESSION['customerno']);
    $checkpointmanager->SaveCheckpointType($checkpoint, $_SESSION['userid']);
}

function getaddedvehicles_chkpt($checkpointid) {
    $checkpointmanager = new CheckpointManager($_SESSION['customerno']);
    $vehicles = $checkpointmanager->get_added_vehicles_chkpt($checkpointid);
    return $vehicles;
}

function modifychk($form) {
    $checkpoint = new VOCheckpoint();
    $checkpoint->checkpointid = GetSafeValueString($form["chkId"], "string");
    $checkpoint->cphone = GetSafeValueString($form["cphone"], "string");
    $checkpoint->cemail = GetSafeValueString($form["cemail"], "string");
    $checkpoint->cgeolat = GetSafeValueString($form["cgeolat"], "float");
    $checkpoint->cgeolong = GetSafeValueString($form["cgeolong"], "float");
    $checkpoint->cname = GetSafeValueString($form["chkName"], "string");
    $checkpoint->crad = GetSafeValueString($form["crad"], "float");
    $checkpoint->eta = GetSafeValueString($form["STime"], "string");
    $checkpoint->chktype = GetSafeValueString($form["chktype"], "string");
    $vehicles = array();
    foreach ($form as $single_post_name => $single_post_value) {
        if (substr($single_post_name, 0, 11) == "to_vehicle_") {
            $vehicles[] = substr($single_post_name, 11, 12);
        }
    }
    //print_r($Groups);
    $checkpoint->vehicles = $vehicles;
    $checkpointmanager = new CheckpointManager($_SESSION['customerno']);
    $checkpointmanager->SaveCheckpoint($checkpoint, $_SESSION['userid']);
}

function modifychktype($form) {
    $checkpoint = new stdClass();
    $checkpoint->ctid = GetSafeValueString($form["ctid"], "string");
    $checkpoint->name = GetSafeValueString($form["typename"], "string");

    $checkpointmanager = new CheckpointManager($_SESSION['customerno']);
    $checkpointmanager->SaveCheckpointType($checkpoint, $_SESSION['userid']);
}

function check_chk_name($chkN) {
    $chkN = GetSafeValueString($chkN, 'string');
    $checkpointmanager = new CheckpointManager($_SESSION['customerno']);
    $checkpoints = $checkpointmanager->getallcpoints();
    $status = NULL;
    if (isset($checkpoints)) {
        foreach ($checkpoints as $thischeckpoint) {
            if ($thischeckpoint->cname == $chkN) {
                $status = "notok";
                break;
            }
        }
        if (!isset($status)) {
            $status = "ok";
        }
    } else {
        $status = "ok";
    }
    echo $status;
}

function check_chk_type_name($chkN) { 
    $chkN = GetSafeValueString($chkN, 'string');
    $checkpointmanager = new CheckpointManager($_SESSION['customerno']);
    $checkpoints = $checkpointmanager->getallcpointtypes($chkN);
    $status = NULL;
    if (isset($checkpoints)) {
        foreach ($checkpoints as $thischeckpoint) {
            if (strtolower($thischeckpoint->name) == strtolower($chkN)) {
                $status = "notok";
                break;
            }
        }
        if (!isset($status)) {
            $status = "ok";
        }
    } else {
        $status = "ok";
    }
    echo $status;
}

function printcheckpointsformapping() {
    $checkpoints = getchks();
    if (isset($checkpoints)) {
        foreach ($checkpoints as $checkpoint) {
            $row = '<ul id="mapping">';
            $row .= "<li id='d_$checkpoint->checkpointid'";
            if ($checkpoint->vehicleid > 0) {
                $row .= ' class="driverassigned"';
            } else {
                $row .= ' class="driver"';
            }
            $row .= " onclick='st($checkpoint->checkpointid)'>";
            $row .= $checkpoint->cname;
            $row .= "<input type='hidden' id='dl_$checkpoint->checkpointid'";
            if ($checkpoint->vehicleid != 0 && isset($checkpoint->vehicleid)) {
                $row .= " value='$checkpoint->vehicleid'>";
            }

            $row .= "</li></ul>";
            echo $row;
        }
    }
}

function printvehiclesformapping() {
    $vehicles = getvehicles();
    if (isset($vehicles)) {
        foreach ($vehicles as $vehicle) {
            $row = "<ul id='mapping'>";
            $row .= "<li id='v_$vehicle->vehicleid'";
            if ($vehicle->checkpointid > 0) {
                $row .= ' class="vehicleassigned"';
            } else {
                $row .= ' class="vehicle"';
            }
            $row .= " onclick='sd($vehicle->vehicleid)'>";
            $row .= $vehicle->vehicleno;
            $row .= "<input type='hidden' id='vl_$vehicle->vehicleid'";
            if ($vehicle->checkpointid != 0 && isset($vehicle->checkpointid)) {
                $row .= " value='$vehicle->checkpointid'>";
            }

            $row .= "</li></ul>";
            echo $row;
        }
    }
}

function getvehicles_all() { 
    $VehicleManager = new VehicleManager($_SESSION['customerno']);
    $vehicles = $VehicleManager->get_all_vehicles();
    return $vehicles;
}

function getvehicles() {
    $vehiclemanager = new VehicleManager($_SESSION['customerno']);
    $vehicles = $vehiclemanager->getvehicleswithchks();
    return $vehicles;
}

function chk_eligibility($chkid) {
    $chkid = GetSafeValueString($chkid, 'long');

    if (isset($chkid)) {
        $checkpointmanager = new CheckpointManager($_SESSION['customerno']);
        $checkpoint = $checkpointmanager->getvehicleforchk($chkid);
        if (isset($checkpoint) && $checkpoint->vehicleid > 0) {
            echo ("notok");
        } else {
            echo ("ok");
        }
    } else {
        echo ("notok");
    }
}

/* function mapchk($chkid,$vehicleid)
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

function chkformapping() {
    $checkpointmanager = new CheckpointManager($_SESSION['customerno']);
    $checkpoints = $checkpointmanager->getcheckpointsforcustomer();
    $finaloutput = array();
    if (isset($checkpoints)) {
        foreach ($checkpoints as $thischeckpoint) {
            $output = new jsonop();
            $output->cgeolat = $thischeckpoint->cgeolat;
            $output->cgeolong = $thischeckpoint->cgeolong;
            $output->cname = $thischeckpoint->cname;
            $output->crad = $thischeckpoint->crad * 1000;
            $finaloutput[] = $output;
        }
    }
    $ajaxpage = new ajaxpage();
    $ajaxpage->SetResult($finaloutput);
    $ajaxpage->Render();
}

function view_checkpoint_by_id($chkid) {
    $chkid = GetSafeValueString($chkid, 'long');
    $checkpointmanager = new CheckpointManager($_SESSION['customerno']);
    $add = $checkpointmanager->get_checkpoint($chkid);
    echo json_encode($add);
}

function editchk($chkid, $chkname, $chkrad) {
    $chkname = GetSafeValueString($chkname, "string");
    $chkrad = GetSafeValueString($chkrad, "string");
    //$vehicleid= GetSafeValueString($vehicleid,"string");
    $chkid = GetSafeValueString($chkid, "string");
    $checkpointmanager = new CheckpointManager($_SESSION['customerno']);
    $checkpointmanager->updatechk($chkname, $chkrad, $chkid);
}

function DelChkByVehicleid($chkid, $vehicleid) {
    $checkpointmanager = new CheckpointManager($_SESSION['customerno']);
    $checkpointid = GetSafeValueString($chkid, "string");
    $vehicleid = GetSafeValueString($vehicleid, "string");
    $checkpointmanager->DeleteCheckpointModal($checkpointid, $vehicleid, $_SESSION['userid']);
}

function upload_checkpoint($all_form, $vehicles) {

    $customerno = $_SESSION['customerno'];
    $userid = $_SESSION['userid'];
    $checkpointmanager = new CheckpointManager($customerno);
    $skipped = 0;
    $added = 0;

    foreach ($all_form as $form) {

        $cname = trim(GetSafeValueString($form["checkpointname"], "string"));
        if ($checkpointmanager->CheckName_exists($cname) || $cname == '') {
            $skipped++;
            continue;
        }

        $checkpoint = new stdClass();
        $checkpoint->cadd = null;
        $checkpoint->cgeolat = GetSafeValueString($form["latitude"], "float");
        $checkpoint->cgeolong = GetSafeValueString($form["longitude"], "float");
        $checkpoint->cname = $cname;
        $checkpoint->crad = isset($form["radius"]) ? GetSafeValueString($form["radius"], "float") : 1;
        $checkpoint->eta = null;
        $checkpoint->customerno = $customerno;
        $checkpoint->vehicles = $vehicles;
        $checkpoint->cphone = '';
        $checkpoint->cemail = '';

        $checkpointmanager->SaveCheckpoint($checkpoint, $userid);
        $added++;
    }
    return array(
        'added' => $added,
        'skipped' => $skipped,
    );
}

function get_veh_drop_down() {
    $vehicles = getvehicles_all();
    $veh_dropdown = '';
    if (isset($vehicles)) {
        foreach ($vehicles as $veh_no) {
            $veh_dropdown .= "<option value='$veh_no->vehicleid'>$veh_no->vehicleno</option>";
        }
    }
    return $veh_dropdown;
}

function insertException($objException) {
    $chkManager = new CheckpointManager($objException->customerno);
    $status = $chkManager->insertException($objException);
    return $status;
}

function getCheckpointExceptions() {
    $arrExceptions = array();
    $chkManager = new CheckpointManager($_SESSION['customerno']);
    $exceptions = $chkManager->getCheckpointExceptions();

    $array = json_decode(json_encode($exceptions), true);
    $arrExceptions = array_reduce($array, function ($result, $currentItem) {
        if (isset($result[$currentItem['exceptionId']])) {
            if (!in_array($currentItem['vehicleNo'], $result[$currentItem['exceptionId']]['vehicleList'])) {
                $result[$currentItem['exceptionId']]['vehicleList'][] = $currentItem['vehicleNo'];
            }
            if (!in_array($currentItem['checkpointName'], $result[$currentItem['exceptionId']]['checkpointList'])) {
                $result[$currentItem['exceptionId']]['checkpointList'][] = $currentItem['checkpointName'];
            }
        } else {
            $result[$currentItem['exceptionId']]['startTime'] = $currentItem['startTime'];
            $result[$currentItem['exceptionId']]['endTime'] = $currentItem['endTime'];
            $result[$currentItem['exceptionId']]['exceptionType'] = $currentItem['exceptionType'];
            $result[$currentItem['exceptionId']]['exceptionName'] = $currentItem['exceptionName'];
            $result[$currentItem['exceptionId']]['exceptionTypeName'] = $currentItem['exceptionTypeName'];
            //$result[$currentItem['exceptionId']]['username'] = $currentItem['username'];
            //$result[$currentItem['exceptionId']]['email'] = $currentItem['email'];
            //$result[$currentItem['exceptionId']]['phone'] = $currentItem['phone'];
            $result[$currentItem['exceptionId']]['vehicleList'] = array($currentItem['vehicleNo']);
            $result[$currentItem['exceptionId']]['checkpointList'] = array($currentItem['checkpointName']);
        }
        return $result;
    });
    $objExceptions = json_decode(json_encode($arrExceptions), false);
    return $objExceptions;
}

function upload_chkEtaData($all_form) {
    $customerno = $_SESSION['customerno'];
    $userid = $_SESSION['userid'];
    $objCheckpointManager = new CheckpointManager($customerno);
    $skippedArray = array();
    $skipped = 0;
    $added = 0;
    foreach ($all_form as $form) {
        $chkptId = trim(GetSafeValueString($form["Checkpoint ID"], "string"));
        if ($chkptId != '' && is_numeric($chkptId)) {
            $objChkptDetails = $objCheckpointManager->get_checkpoint($chkptId);
            if (!isset($objChkptDetails->checkpointid)) {
                $skipped++;
                $skippedArray[] = $form;
                continue;
            }

            $checkpoint = new VOCheckpoint();
            $checkpoint->checkpointid = $objChkptDetails->checkpointid;
            $checkpoint->cname = $objChkptDetails->cname;
            $checkpoint->cphone = $objChkptDetails->cphone;
            $checkpoint->cemail = $objChkptDetails->cemail;
            $checkpoint->cadd = $objChkptDetails->cadd;
            $checkpoint->cgeolat = $objChkptDetails->cgeolat;
            $checkpoint->cgeolong = $objChkptDetails->cgeolong;
            $checkpoint->crad = $objChkptDetails->crad;
            $checkpoint->eta = GetSafeValueString($form["ETA (HH:MM)"], "string");
            $checkpoint->customerno = $customerno;

            $chkPtId = $objCheckpointManager->SaveCheckpoint($checkpoint, $userid);

            if ($chkPtId > 0) {
                $added++;
            } else {
                $skipped++;
                $skippedArray[] = $form;
                continue;
            }
        }
    }
    return array(
        'added' => $added,
        'skipped' => $skipped,
        'skippedData' => $skippedArray,
    );
}

?>
