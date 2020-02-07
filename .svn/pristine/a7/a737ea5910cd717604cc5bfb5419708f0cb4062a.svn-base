<?php
include_once '../../lib/autoload.php';
if (!isset($_SESSION)) {
    session_start();
}
function add_new_exception($userid) {
    $data = array();
    $data['send_sms'] = isset($_POST['exceptionSms'][0]) ? $_POST['exceptionSms'][0] : 0;
    $data['send_email'] = isset($_POST['exceptionEmail'][0]) ? $_POST['exceptionEmail'][0] : 0;
    $data['send_telephone'] = isset($_POST['exceptionTelephone'][0]) ? $_POST['exceptionTelephone'][0] : 0;
    $data['report_type_val'] = (isset($_POST['report_type_input'][0]) && trim($_POST['report_type_input'][0]) !== '') ? $_POST['report_type_input'][0] : '';
    if ($data['send_sms'] != 'on' && $data['send_email'] == 'on') {
        return false;
    }
    if ($data['report_type_val'] == '') {
        return false;
    }
    $data['vehicles'] = get_vehicle_id_array();
    if (empty($data['vehicles'])) {
        return false;
    }
    $data['start_checkpoint_id'] = isset($_POST['checkpoint_start'][0]) ? $_POST['checkpoint_start'][0] : 0;
    $data['end_checkpoint_id'] = isset($_POST['checkpoint_end'][0]) ? $_POST['checkpoint_end'][0] : 0;
    if ($data['start_checkpoint_id'] == $data['end_checkpoint_id']) {return false;}
    $data['report_type'] = isset($_POST['report_type'][0]) ? $_POST['report_type'][0] : 0;
    $data['report_type_condition'] = isset($_POST['condition'][0]) ? $_POST['condition'][0] : 0;
    $customerno = $_SESSION['customerno'];
    $data['userid'] = $userid;
    $exception = new ExceptionManager($customerno);
    $status = $exception->add_exception_alert($data);
}

function get_vehicle_id_array() {
    $vehicles = array();
    foreach ($_REQUEST as $single_post_name => $single_post_value) {
        if (substr($single_post_name, 0, 11) == "to_vehicle_") {
            $vehicles[] = (int) substr($single_post_name, 11, 12);
        }
    }
    return $vehicles;
}

function add_exception() {
    $customerno = isset($_SESSION['customerno']) ? $_SESSION['customerno'] : exit('Please Login');
    $data = array();
    $data['userid'] = isset($_POST['uid']) ? $_POST['uid'] : $_SESSION['userid'];
    $data['send_sms'] = isset($_POST['exceptionSms'][0]) ? $_POST['exceptionSms'][0] : 0;
    $data['send_email'] = isset($_POST['exceptionEmail'][0]) ? $_POST['exceptionEmail'][0] : 0;
    $data['send_telephone'] = isset($_POST['exceptionTelephone'][0]) ? $_POST['exceptionTelephone'][0] : 0;
    $data['start_checkpoint_id'] = isset($_POST['checkpoint_start'][0]) ? $_POST['checkpoint_start'][0] : exit('Please select start Checkpoint');
    $data['end_checkpoint_id'] = isset($_POST['checkpoint_end'][0]) ? $_POST['checkpoint_end'][0] : exit('Please select end Checkpoint');
    if ($data['start_checkpoint_id'] == $data['end_checkpoint_id']) {exit('Start and End Checkpoint should be different');}
    $data['report_type'] = isset($_POST['report_type'][0]) ? $_POST['report_type'][0] : exit('Please select report type');
    $data['report_type_condition'] = isset($_POST['condition'][0]) ? $_POST['condition'][0] : exit('Please select condition');
    $data['report_type_val'] = (isset($_POST['report_type_input'][0]) && trim($_POST['report_type_input'][0]) !== '') ? $_POST['report_type_input'][0] : exit('Please enter value');
    $data['vehicles'] = get_vehicle_id_array();
    if (empty($data['vehicles'])) {
        exit('Please Enter Vehicle No.');
    }
    $exception = new ExceptionManager($customerno);
    $status = $exception->add_exception_alert($data);
    echo $status['result'];
    if ($status[0]) {
        echo "<script>location.reload();</script>";
    }
}

function delete_exception() {
    $excep_id = isset($_POST['excep_id']) ? $_POST['excep_id'] : exit('Exception id not defined');
    $customerno = isset($_SESSION['customerno']) ? $_SESSION['customerno'] : exit('Please Login');
    $userid = isset($_SESSION['userid']) ? $_SESSION['userid'] : exit('Please Login');
    $exception_id = (int) $excep_id;
    $exception = new ExceptionManager($customerno);
    $data = $exception->update_exception($userid, $exception_id);
    if ($data[0]) {
        echo "Deleted Successfully";
    } else {
        echo "Unable to delete";
    }
}

function delete_exception_by_userid($del_id, $user_id) {
    $customerno = isset($_SESSION['customerno']) ? $_SESSION['customerno'] : exit('Please Login');
    $exception = new ExceptionManager($customerno);
    $data = $exception->update_exception_userid($del_id, $user_id);
}

function get_exception_alerts($format, $userid) {
    $customerno = isset($_SESSION['customerno']) ? $_SESSION['customerno'] : exit('Please Login');
    $exception = new ExceptionManager($customerno);
    $data = $exception->get_exception($userid);
    if ($data[0]) {
        if ($format == 'table') {
            $table_format = get_table_format($data['result']);
            return $table_format;
        } else {
            return null;
        }
    } else {
        return '';
    }
}

function display_units($report_type) {
    switch ($report_type) {
    case 'distance':
        $unit = 'KM';
        $name = 'Distance';
        break;
    case 'avg_speed':
        $unit = 'KM/Hr';
        $name = 'Avg Speed';
        break;
    case 'idle_time':
        $unit = 'Hr';
        $name = 'Idle Time';
        break;
    case 'genset_avg':
        $unit = 'Mins';
        $name = 'Genset Average';
        break;
    }
    return array($unit, $name);
}

function display_condition($cond) {
    $r = ">";
    switch ($cond) {
    case 'gt':
        $r = ">";
        break;
    case 'lt_eq':
        $r = "<=";
        break;
    }
    return $r;
}

function get_table_format($data) {
    $table = '';
    $count = 0;
    $checkpointmanager = new CheckpointManager($_SESSION['customerno']);
    $all_vehicles = getvehicles_all();
    foreach ($data as $value) {
        $exc_id = $value['exception_id'];
        $sms_check = ($value['send_sms'] == 1) ? 'checked' : '';
        $mail_check = ($value['send_email'] == 1) ? 'checked' : '';
        $telephone_check = ($value['send_telephone'] == 1) ? 'checked' : '';
        $s_chk_id = $value['start_checkpoint_id'];
        $s_check_name = $checkpointmanager->get_checkpoint($s_chk_id);
        $e_chk_id = $value['end_checkpoint_id'];
        $e_check_name = $checkpointmanager->get_checkpoint($e_chk_id);
        $disp = display_units($value['report_type']);
        $disp_cond = display_condition($value['report_type_condition']);
        $vehicleno = $all_vehicles[$value['vehicleid']]['vehicleno'];
        $table .= "<tr class='$exc_id'>";
        $table .= "<td width='25px'><input type='checkbox' $sms_check /></td>";
        $table .= "<td width='30px'><input type='checkbox' $mail_check /></td>";
        $table .= "<td width='30px'><input type='checkbox' $telephone_check /></td>";
        $table .= "<td><input type='text' value='$vehicleno' /></td>";
        $table .= "<td><select style='width: 150px;' ><option value='$s_chk_id'>$s_check_name->cname</option></select></td>";
        $table .= "<td><select style='width: 150px;' ><option value='$e_chk_id'>$e_check_name->cname</option></select></td>";
        $table .= "<td><select style='width: 130px;' ><option value='" . $value['report_type'] . "'>" . $disp[1] . "</option></select></td>";
        $table .= "<td><select><option value='" . $value['report_type_condition'] . "'>$disp_cond</option></select></td>";
        $table .= "<td><input type='text' class='input-mini' value='" . $value['report_type_val'] . "' />  {$disp[0]}  ";
        $table .= '<input type="submit" value="Delete" class="btn  btn-primary" onclick="delete_exception(' . $exc_id . ');return false;" style="float:right" name="exceptionDelete"></td>';
        $table .= "</tr>";
        $count++;
    }
    return array($table, $count);
}

function getvehicles_all() {
    $VehicleManager = new VehicleManager($_SESSION['customerno']);
    $all_vehicles = $VehicleManager->Get_All_Vehicles_SQLite();
    return $all_vehicles;
}

function update_trip_endtime_flag($excep_id, $enddate) {
    $exception = new ExceptionManager(null);
    $exception->update_flag($excep_id, $enddate);
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
            $result[$currentItem['exceptionId']]['exceptionId'] = $currentItem['exceptionId'];
            $result[$currentItem['exceptionId']]['startTime'] = $currentItem['startTime'];
            $result[$currentItem['exceptionId']]['endTime'] = $currentItem['endTime'];
            $result[$currentItem['exceptionId']]['exceptionType'] = $currentItem['exceptionType'];
            $result[$currentItem['exceptionId']]['exceptionName'] = $currentItem['exceptionName'];
            $result[$currentItem['exceptionId']]['exceptionTypeName'] = $currentItem['exceptionTypeName'];
            $result[$currentItem['exceptionId']]['vehicleList'] = array($currentItem['vehicleNo']);
            $result[$currentItem['exceptionId']]['checkpointList'] = array($currentItem['checkpointName']);
        }
        return $result;
    });
    $objExceptions = json_decode(json_encode($arrExceptions), false);
    return $objExceptions;
}

function getUserAlertMapping($objUser) {
    $chkException = null;
    $objUserManager = new UserManager();
    $mappedException = $objUserManager->getUserAlertMapping($objUser);
    return $mappedException;
}

function getUserExceptionMapping($objUser) {
    $chkException = null;
    $objUserManager = new UserManager();
    $mappedException = $objUserManager->getUserExceptionMapping($objUser);
    return $mappedException;
}

?>
