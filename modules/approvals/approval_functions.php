<?php

include_once '../../lib/bo/GeofenceManager.php';
include_once '../../lib/bo/CheckpointManager.php';
include_once '../../lib/bo/VehicleManager.php';
//include '../../lib/bo/GroupManager.php';
include_once '../../lib/system/utilities.php';
include_once '../../lib/bo/UnitManager.php';
include_once '../../lib/bo/DealerManager.php';
include_once '../../lib/bo/MaintananceManager.php';
include_once '../../lib/bo/PartManager.php';
include_once '../../lib/bo/TaskManager.php';
include_once '../../lib/bo/AccessoryManager.php';

if (!isset($_SESSION)) {
    session_start();
}

class VODatacap {

}

function getdate_IST() {
    $ServerDate_IST = strtotime(date("Y-m-d H:i:s"));
    return $ServerDate_IST;
}

function getbattbyid($maintenanceid) {
    $vehiclemanager = new VehicleManager($_SESSION['customerno']);
    $battery = $vehiclemanager->getbattery_id($maintenanceid);
    return $battery;
}

function getgeneral($vehicleid) {
    $vm = new VehicleManager($_SESSION['customerno']);
    $general = $vm->get_general($vehicleid);
    return $general;
}

function getTyreRepairType() {
    $vm = new VehicleManager($_SESSION['customerno']);
    $rtype = $vm->getTyreRepair();
    return $rtype;
}


function getbatteryno_byvehicle($vehicleid) {
    $vm = new VehicleManager($_SESSION['customerno']);
    $bsrno = $vm->getbatteryno_byvehicle($vehicleid);
    return $bsrno;
}

function getbatteryno_byvehicle_history($vehicleid,$tid){
    $vm = new VehicleManager($_SESSION['customerno']);
    $bsrno = $vm->getbatteryno_byvehicle_history($vehicleid,$tid);
    return $bsrno;
}

function getTyreTypedata($vehicleid) {
    $vm = new VehicleManager($_SESSION['customerno']);
    $values = $vm->gettyreTypedata($vehicleid);
    return $values;
}

function getTyredata($vehicleid,$tid){
    $vm = new VehicleManager($_SESSION['customerno']);
    $values = $vm->getTyredata($vehicleid,$tid);
    return $values;
}

function getTyredataOld($vehicleid,$tid){
    $vm = new VehicleManager($_SESSION['customerno']);
    $values = $vm->getTyredataOld($vehicleid,$tid);
    return $values;
}

function getTyreTypedataold($vehicleid){
    $vm = new VehicleManager($_SESSION['customerno']);
    $values = $vm->gettyreTypedataold($vehicleid);
    return $values;
}

function searchForId($id, $array, $array_col){
    $vm = new VehicleManager($_SESSION['customerno']);
    $values = $vm->searchForId($id, $array, $array_col);
    return $values;
}

function getapproved_vehicles() {
    $vehiclemanager = new VehicleManager($_SESSION['customerno']);
    $vehicles = $vehiclemanager->get_approved_vehicles();
    return $vehicles;
}

function get_trans_approval_status() {
    $maintanace_obj = new MaintananceManager($_SESSION['customerno']);
    $status = $maintanace_obj->get_trans_approval_status();
    return $status;
}

function getvehicle($vehicleid) {
    $vehicleid = GetSafeValueString($vehicleid, "string");
    $vehiclemanager = new VehicleManager($_SESSION['customerno']);
    $vehicle = $vehiclemanager->get_vehicle_with_driver($vehicleid);
    if ($vehicle->isdeleted == '1')
        header("location:vehicle.php");

    return $vehicle;
}

function getvehicles_for_approval() {
    $vehiclemanager = new VehicleManager($_SESSION['customerno']);
    $vehicles = $vehiclemanager->get_all_vehicles_forapproval();
    return $vehicles;
}

function getmaintanances() {
    $maintanace_obj = new MaintananceManager($_SESSION['customerno']);
    $maintanaces = $maintanace_obj->get_all_transaction(1, 1);
    return $maintanaces;
}

function getfilteredmaintanances($transid, $vehicleid, $categoryid, $statusid, $tyre, $parts, $dealerid) {
    $maintanace_obj = new MaintananceManager($_SESSION['customerno']);
    $maintanaces = $maintanace_obj->get_filtered_transaction($transid, $vehicleid, $categoryid, $statusid, $tyre, $parts, $dealerid, 1);
    return $maintanaces;
}

function getfilteredaccidents($transid, $vehicleid, $statusid) {
    $accident_obj = new VehicleManager($_SESSION['customerno']);
    $accidents = $accident_obj->get_filtered_accidents($transid, $vehicleid, $statusid);
    return $accidents;
}

function getaccidents() {
    $accident_obj = new VehicleManager($_SESSION['customerno']);
    $accidents = $accident_obj->get_accident_transaction(1);
    return $accidents;
}

function get_approval_form_by_vehicle_id($main_id) {
    $maintanace_obj = new MaintananceManager($_SESSION['customerno']);
    $maintanaces = $maintanace_obj->get_approval_form_by_vehicleid($main_id);
}

function get_acc_approval_form_by_vehicle_id($acc_id) {
    $maintanace_obj = new MaintananceManager($_SESSION['customerno']);
    $maintanaces = $maintanace_obj->get_acc_approval_form_by_vehicle_id($acc_id);
}

function pushstatus($main_id, $status, $note) {
    $maintanace_obj = new MaintananceManager($_SESSION['customerno']);
    $vm = new VehicleManager($_SESSION['customerno']);
    $maintanaces = $maintanace_obj->set_transaction_status($main_id, $status, $note);
    if (isset($maintanaces) == 'ok'){
        $roleid = $maintanace_obj->getTransactionRole($main_id);
        $creator = $vm->get_transaction_creator($main_id);
        $approver = '';
        /* TODO UNCOMMENT AFTER SP */
        $approver = $vm->get_transaction_approver($main_id, $roleid);

        if (!empty($creator)) {
            send_transactionmail($creator, $main_id);
        }
        if (!empty($approver)) {
            //send_transactionmail($approver, $main_id);
        }
    }
}

function pushacc($main_id, $status, $note) {
    $maintanace_obj = new MaintananceManager($_SESSION['customerno']);
    $vm = new VehicleManager($_SESSION['customerno']);
    $maintanaces = $maintanace_obj->set_acc_status($main_id, $status, $note);
    if (isset($maintanaces) == 'ok') {
        $roleid = $maintanace_obj->getAccidentRole($main_id);
        $creator = $vm->get_accident_creator($main_id);
        $approver = $vm->get_accident_approver($main_id, $roleid);

        if (!empty($creator)) {
            send_accidentmail($creator, $main_id);
        }
        if (!empty($approver)) {
            //send_accidentmail($approver, $main_id);
        }
    }
}

function approved($vehicleid, $notes, $userid, $veh_notes) {
    $vehiclemanager = new VehicleManager($_SESSION['customerno']);
    $vehicles = $vehiclemanager->approved($vehicleid, $notes, $userid, $veh_notes);
    if ($vehicles == 'ok') {
        $approver = $vehiclemanager->get_vehicle_approver($vehicleid);
        $creator = $vehiclemanager->get_vehicle_creator($vehicleid);
        $general = $vehiclemanager->get_general_formail($vehicleid);
        if (!empty($creator)) {
            send_creationmail($creator, $general, $notes);
        }
        if (!empty($approver)) {
            //send_creationmail($approver, $general, $notes);
        }
    }
    return $vehicles;
}

function reject($vehicleid, $notes, $userid, $veh_notes) {
    $vehiclemanager = new VehicleManager($_SESSION['customerno']);
    $vehicles = $vehiclemanager->reject($vehicleid, $notes, $userid, $veh_notes);
    if ($vehicles == 'ok') {
        $approver = $vehiclemanager->get_vehicle_approver($vehicleid);
        $creator = $vehiclemanager->get_vehicle_creator($vehicleid);
        $general = $vehiclemanager->get_general_formail($vehicleid);
        if (!empty($creator)) {
            send_creationmail($creator, $general, $notes);
        }
        if (!empty($approver)) {
            //send_creationmail($approver, $general, $notes);
        }
    }
    return $vehicles;
}

function send_creationmail($creator, $general, $notes) {
    $vm = new VehicleManager($_SESSION['customerno']);

    foreach ($creator as $user) {
        $message = '';
        $message .= '<html><body>';
        $message .= 'Dear ' . $user->realname . ' ,<br>';
        $message .= '<p></p></br>';
        $message .= 'Greetings from Elixia Tech!<br/>';
        $message .= 'Please find the vehicle detail. In case of any queries, you may contact us at 25137470 / 71. Thank you for choosing Elixia Tech!<br/><br/>';

        $message .= '<table>
                                    <thead>
                                    <tr>
                                        <th colspan="5" id="formheader">Vehicle Details</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr>
                                        <td>Vehicle No</td>
                                        <td>' . $general->vehicleno . '</td>
                                        <td>Kind</td>
                                        <td>' . $general->kind . '</td>
                                    </tr>
                                    <tr>
                                        <td>Make</td>
                                        <td>' . $general->make . '</td>
                                        <td>Model</td>
                                        <td>' . $general->model . '</td>
                                    </tr>
                                    <tr>
                                        <td>Year Of Manufacture</td>
                                        <td>' . $general->manufacturing_year . '</td>
                                        <td>Purchase Date</td>
                                        <td>' . $general->purchase_date . '</td>
                                    </tr>
                                    <tr>
                                        <td>Branch</td>
                                        <td>' . $general->branch . '</td>
                                        <td>Fuel Type</td>
                                        <td>' . $general->fueltype . '</td>
                                    </tr>
                                    <tr>
                                        <td>Start Meter Reading</td>
                                        <td>' . $general->start_meter_reading . '</td>
                                        <td>Overspeed Limit</td>
                                        <td>' . $general->overspeed_limit . '</td>
                                    </tr>
                                    ';
        if ($general->status_id == '2') {
            $message .= '<tr>
                                        <td>Reason For Approval</td>
                                        <td colspan="3">' . $notes . '</td>
                                        </tr>';
        }
        if ($general->status_id == '3') {
            $message .= '<tr>
                                        <td>Reason For Rejection</td>
                                        <td colspan="3">' . $notes . '</td>
                                        </tr>';
        }



        $message .= "</body></html>";

        $to = $user->email;
        $subject = "Vehicle " . $general->status_name;
        $from = 'noreply@elixiatech.com';
        include_once("../cron/class.phpmailer.php");
        $mail = new PHPMailer();
        $mail->IsMail();
        $mail->AddAddress($to);
        $mail->From = $from;
        $mail->FromName = "Elixia Speed";
        $mail->Sender = $from;
        $mail->Subject = $subject;
        $mail->Body = $message;
        $mail->IsHTML(true);
        $mail->AddReplyTo($from, "Elixia Speed");
        // done to log msg for testing
        $isMailSent = $mail->Send();

        if ($isMailSent) {
            $datamail = '';
            $datamail.='Subject :' . $subject;
            $datamail.='<br>';
            $datamail.='To :' . $user->email;
            $datamail.='<br>';
            $datamail.=$message;
            $vm->sendEmailTolog($datamail);
        } else {
            $message = '';
            $vm->sendEmailTolog($mail->ErrorInfo);
        }
    }
}

function send_transactionmail($creator, $mainid) {
    $mm = new MaintananceManager($_SESSION['customerno']);
    $vm = new VehicleManager($_SESSION['customerno']);
    $status = $mm->getTransactionSatus($mainid);
    $printdata = $mm->get_approval_form_by_vehicleid_formail($mainid);
    $type = $mm->gettransaction_type_formail($mainid); //added for subject

    foreach ($creator as $user) {
        $message .= '<html><body>';
        $message .= 'Dear ' . $user->realname . ' ,<br>';
        $message .= '<p></p></br>';
        $message .= 'Greetings from Elixia Tech!<br/>';
        $message .= 'Please find the vehicle detail. In case of any queries, you may contact us at 25137470 / 71. Thank you for choosing Elixia Tech!<br/><br/>';

        $message .= '' . $printdata . '';

        $message .= "</body></html>";

        $to = $user->email;
        //$to = 'sshrikanth@elixiatech.com';
        $cc = '';
        $subject = $status . ' for ' . $type;
        $from = 'noreply@elixiatech.com';
        $headers .= "From: " . $from . "\r\n";
        $headers .= "CC:" . $cc . "\r\n";
        $headers .= "MIME-Version: 1.0\r\n";
        $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
        include_once("../cron/class.phpmailer.php");
        $mail = new PHPMailer();
        $mail->IsMail();


        $mail->AddAddress($to);
        $mail->From = $from;
        $mail->FromName = "Elixia Speed";
        $mail->Sender = $from;
        $mail->Subject = $subject;
        $mail->Body = $message;
        $mail->IsHTML(true);
        $mail->AddBCC($cc);
        $mail->AddReplyTo($from, "Elixia Speed");

        // done to log msg for testing
        if (!$mail->Send()) {
            $message = '';
            $message .='Error sending: ' . $mail->ErrorInfo;
            '<br/>';
            $vm->sendEmailTolog($message);
        } else {
            $vm->sendEmailTolog($message);
            //echo "Mail sent";echo "<br/>";
            //$message = '';
        }

        //$vm->sendEmailTotable($from, $to, 'test');
    }
}

function send_accidentmail($creator, $mainid) {
    $mm = new MaintananceManager($_SESSION['customerno']);
    $vm = new VehicleManager($_SESSION['customerno']);
    $status = $mm->getAccidentSatus($mainid);
    $printdata = $mm->get_acc_approval_form_by_vehicle_id_formail($mainid);
    foreach ($creator as $user) {
        $message .= '<html><body>';
        $message .= 'Dear ' . $user->realname . ' ,<br>';
        $message .= '<p></p></br>';
        $message .= 'Greetings from Elixia Tech!<br/>';
        $message .= 'Please find the vehicle detail. In case of any queries, you may contact us at 25137470 / 71. Thank you for choosing Elixia Tech!<br/><br/>';


        $message .= '' . $printdata . '';
        $message .= "</body></html>";

        $to = $user->email;
        //$to = 'sshrikanth@elixiatech.com';
        $cc = '';
        $subject = $status;
        $from = 'noreply@elixiatech.com';
        $headers .= "From: " . $from . "\r\n";
        $headers .= "CC:" . $cc . "\r\n";
        $headers .= "MIME-Version: 1.0\r\n";
        $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
        include_once("../cron/class.phpmailer.php");
        $mail = new PHPMailer();
        $mail->IsMail();


        $mail->AddAddress($to);
        $mail->From = $from;
        $mail->FromName = "Elixia Speed";
        $mail->Sender = $from;
        $mail->Subject = $subject;
        $mail->Body = $message;
        $mail->IsHTML(true);
        $mail->AddBCC($cc);
        $mail->AddReplyTo($from, "Elixia Speed");

        if (!$mail->Send()) {
            $message = '';
            $message .='Error sending: ' . $mail->ErrorInfo;
            '<br/>';
            $vm->sendEmailTolog($message);
        } else {
            $vm->sendEmailTolog($message);
        }

        //$vm->sendEmailToTable($from, $to, 'test');
    }
}

function getaccessories_approval($mainid) {
    $mm = new MaintananceManager($_SESSION['customerno']);
    $data = $mm->getaccessories_forapproval($mainid);
    return $data;
}

function get_accident_details($accidentid) {
    $vm = new VehicleManager($_SESSION['customerno']);
    $status = $vm->getacc_id($accidentid);
    return $status;
}

function getvehicles() {
    $vehiclemanager = new VehicleManager($_SESSION['customerno']);
    $vehicles = $vehiclemanager->get_all_vehicles_with_drivers_by_groupname();
    return $vehicles;
}

function gettax($vehicleid, $veh_read = null) {
    $vehiclemanager = new VehicleManager($_SESSION['customerno']);
    $vehicles = $vehiclemanager->gettax($vehicleid, $veh_read);
    return $vehicles;
}

function getnotes($vehicleid) {
    $vehiclemanager = new VehicleManager($_SESSION['customerno']);
    $vehicles = $vehiclemanager->getnotes($vehicleid);
    return $vehicles;
}

function gettax_view($vehicleid) {
    $vehiclemanager = new VehicleManager($_SESSION['customerno']);
    $vehicles = $vehiclemanager->gettax_view($vehicleid);
    return $vehicles;
}

function gettaxbyid($taxid) {
    $vehiclemanager = new VehicleManager($_SESSION['customerno']);
    $tax = $vehiclemanager->gettax_id($taxid);
    return $tax;
}

function deletetaxbyid($taxid, $vehicleid) {
    $vehiclemanager = new VehicleManager($_SESSION['customerno']);
    $tax = $vehiclemanager->delete_tax($taxid, $vehicleid);
    return $tax;
}

function getaccessories() {
    $AccManager = new AccessoryManager($_SESSION['customerno']);
    $accessories = $AccManager->get_all_accessories();
    return $accessories;
}

function deletemaintenancebyid($maintenanceid, $vehicleid) {
    $vehiclemanager = new VehicleManager($_SESSION['customerno']);
    $tax = $vehiclemanager->delete_maintenance($maintenanceid, $vehicleid);
    return $tax;
}

function deleteaccbyid($maintenanceid) {
    $vehiclemanager = new VehicleManager($_SESSION['customerno']);
    $tax = $vehiclemanager->delete_acc($maintenanceid);
    return $tax;
}

function get_batt_hist($form) {
    $vehiclemanager = new VehicleManager($_SESSION['customerno']);
    $vehicles = $vehiclemanager->get_battery_hist($form);
    return $vehicles;
}

function get_insurance_data($form) {
    $vehiclemanager = new VehicleManager($_SESSION['customerno']);
    $vehicles = $vehiclemanager->get_insurancedata($form);
    return $vehicles;
}

function get_batt_hist_view($form) {
    $vehiclemanager = new VehicleManager($_SESSION['customerno']);
    $vehicles = $vehiclemanager->get_battery_hist_view($form);
    return $vehicles;
}

function gettyrebyid($maintenanceid) {
    $vehiclemanager = new VehicleManager($_SESSION['customerno']);
    $battery = $vehiclemanager->getbattery_id($maintenanceid);
    return $battery;
}

function getrepairbyid($maintenanceid) {
    $vehiclemanager = new VehicleManager($_SESSION['customerno']);
    $battery = $vehiclemanager->getbattery_id($maintenanceid);
    return $battery;
}

function get_tyre_hist($form) {
    $vehiclemanager = new VehicleManager($_SESSION['customerno']);
    $vehicles = $vehiclemanager->get_tyre_hist($form);
    return $vehicles;
}

function get_tyre_hist_view($form) {
    $vehiclemanager = new VehicleManager($_SESSION['customerno']);
    $vehicles = $vehiclemanager->get_tyre_hist_view($form);
    return $vehicles;
}

function get_repair_hist($form) {
    $vehiclemanager = new VehicleManager($_SESSION['customerno']);
    $vehicles = $vehiclemanager->get_repair_hist($form);
    return $vehicles;
}

function get_accessory_hist($form) {
    $vehiclemanager = new VehicleManager($_SESSION['customerno']);
    $vehicles = $vehiclemanager->get_accessory_hist($form);
    return $vehicles;
}

function get_accident_hist($form) {
    $vehiclemanager = new VehicleManager($_SESSION['customerno']);
    $vehicles = $vehiclemanager->get_accident_hist($form);
    return $vehicles;
}

function get_accident_hist_view($form) {
    $vehiclemanager = new VehicleManager($_SESSION['customerno']);
    $vehicles = $vehiclemanager->get_accident_hist_view($form);
    return $vehicles;
}

function get_repair_hist_view($form) {
    $vehiclemanager = new VehicleManager($_SESSION['customerno']);
    $vehicles = $vehiclemanager->get_repair_hist_view($form);
    return $vehicles;
}

function getinsurance_company() {
    $vehiclemanager = new VehicleManager($_SESSION['customerno']);
    $vehicles = $vehiclemanager->getinsurance_companys();
    return $vehicles;
}

function getgroupss() {
    $groupmanager = new GroupManager($_SESSION['customerno']);
    $groups = $groupmanager->get_all_groups();
    return $groups;
}

function getdealers() {
    $dealermanager = new DealerManager($_SESSION['customerno']);
    $dealers = $dealermanager->get_all_dealers();
    return $dealers;
}

function getgroups($userid) {
    $groupmanager = new GroupManager($_SESSION['customerno']);
    $groups = $groupmanager->get_all_groups($userid);
    return $groups;
}

function getchks() {
    $checkpointmanager = new CheckpointManager($_SESSION['customerno']);
    $checkpoints = $checkpointmanager->getallcheckpointsforvehicles();
    return $checkpoints;
}

function getmappedchks($vehicleid) {
    $vehicleid = GetSafeValueString($vehicleid, "string");
    $checkpointmanager = new CheckpointManager($_SESSION['customerno']);
    if (isset($vehicleid)) {
        return $checkpointmanager->getchksforvehicle($vehicleid);
    }
}

function getfences() {
    $fencemanager = new GeofenceManager($_SESSION['customerno']);
    $fences = $fencemanager->getfences();
    return $fences;
}

function getmappedfences($vehicleid) {
    $vehicleid = GetSafeValueString($vehicleid, "string");
    $fencemanager = new GeofenceManager($_SESSION['customerno']);
    if (isset($vehicleid)) {
        return $fencemanager->getfencesforvehicle($vehicleid);
    }
}

function getgroup() {
    $groupmanager = new GroupManager($_SESSION['customerno']);
    $groups = $groupmanager->get_all_groups();
    return $groups;
}

function getunits() {
    $unitmanager = new UnitManager($_SESSION['customerno']);
    $units = $unitmanager->getunits();
    return $units;
}

function delvehicle($vehicleid) {
    $vehicleid = GetSafeValueString($vehicleid, "string");
    $vehiclemanager = new VehicleManager($_SESSION['customerno']);
    $vehiclemanager->delvehicle($vehicleid, $_SESSION['userid']);
}

function insertvehicle($vehicleno, $type, $checkpoints, $fences, $groupid, $overspeed_limit, $min_temp1 = 0, $max_temp1 = 0, $min_temp2 = 0, $max_temp2 = 0) {
    $vehicleno = GetSafeValueString($vehicleno, "string");
    $groupid = GetSafeValueString($groupid, "int");
    $type = GetSafeValueString($type, "string");
    $overspeed_limit = GetSafeValueString($overspeed_limit, "int");
    $vehiclemanager = new VehicleManager($_SESSION['customerno']);
    $vehiclemanager->add_vehicle($vehicleno, $type, $checkpoints, $fences, $_SESSION['userid'], $groupid, $overspeed_limit, $min_temp1, $max_temp1, $min_temp2, $max_temp2);
}

function modifyvehicle($vehicleno, $vehicleid, $type, $checkpoints, $fences, $groupid, $overspeed_limit, $min_temp1 = 0, $max_temp1 = 0, $min_temp2 = 0, $max_temp2 = 0) {
    $vehicleno = GetSafeValueString($vehicleno, "string");
    $groupid = GetSafeValueString($groupid, "int");
    $type = GetSafeValueString($type, "string");
    $overspeed_limit = GetSafeValueString($overspeed_limit, "int");
    $vehicleid = GetSafeValueString($vehicleid, "string");
    $vehiclemanager = new VehicleManager($_SESSION['customerno']);
    $vehiclemanager->modvehicle($vehicleno, $vehicleid, $type, $checkpoints, $fences, $_SESSION['userid'], $groupid, $overspeed_limit, $min_temp1, $max_temp1, $min_temp2, $max_temp2);
}

function uniteligibility($unitid) {
    $unitid = GetSafeValueString($unitid, "string");
    $um = new UnitManager($_SESSION['customerno']);
    $status = NULL;
    if (isset($unitid)) {
        $unit = $um->getvehiclefromunit($unitid);
        if (isset($unit) && $unit->vehicleid > 0) {
            $status = "notok";
        } else {
            $status = "ok";
        }
    } else {
        $status = "notok";
    }
    echo $status;
}

function checkvehiclename($vehicleno) {
    $vehicleno = GetSafeValueString($vehicleno, 'string');
    $vehiclemanager = new VehicleManager($_SESSION['customerno']);
    $vehicles = $vehiclemanager->get_all_vehicles();
    $status = NULL;
    if (isset($vehicles)) {
        foreach ($vehicles as $thisvehicle) {
            if ($thisvehicle->vehicleno == $vehicleno) {
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

function printunitsformapping() {
    $units = getunits();
    if (isset($units)) {
        foreach ($units as $unit) {
            $row = '<ul id="mapping">';
            $row .= "<li id='d_$unit->uid'";
            if ($unit->vehicleid > 0) {
                $row .= ' class="driverassigned"';
            } else {
                $row .= ' class="driver"';
            }
            $row .= " onclick='st($unit->uid)'>";
            $row .= $unit->unitno;
            $row .= "<input type='hidden' id='dl_$unit->uid'";
            if ($unit->vehicleid != 0 && isset($unit->vehicleid))
                $row .= " value='$unit->vehicleid'>";
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
            if ($vehicle->uid > 0) {
                $row .= ' class="vehicleassigned"';
            } else {
                $row .= ' class="vehicle"';
            }
            $row .= " onclick='sd($vehicle->vehicleid)'>";
            $row .= $vehicle->vehicleno;
            $row .= "<input type='hidden' id='vl_$vehicle->vehicleid'";
            if ($vehicle->uid != 0 && isset($vehicle->uid))
                $row .= " value='$vehicle->driverid'>";
            $row .="</li></ul>";
            echo $row;
        }
    }
}

function mapvehicle($unitid, $vehicleid) {
    $unitid = GetSafeValueString($unitid, "string");
    $vehicleid = GetSafeValueString($vehicleid, "string");
    $vm = new VehicleManager($_SESSION['customerno']);
    $vm->mapunit($vehicleid, $unitid, $_SESSION['userid']);
}

function demap($vehicleid) {
    $vehicleid = GetSafeValueString($vehicleid, "string");
    $vm = new VehicleManager($_SESSION['customerno']);
    $vm->demapunit($vehicleid, $_SESSION['userid']);
}

function editvehicle($vehicleid, $vehicleno) {
    $vehicleno = GetSafeValueString($vehicleno, "string");
    $vehicleid = GetSafeValueString($vehicleid, "string");
    $vm = new VehicleManager($_SESSION['customerno']);
    $vm->editvehicledata($vehicleno, $vehicleid);
}

function editdriver($vehicleid, $drivername, $driverno) {
    $drivername = GetSafeValueString($drivername, "string");
    $driverno = GetSafeValueString($driverno, "string");
    $vehicleid = GetSafeValueString($vehicleid, "string");
    $vm = new VehicleManager($_SESSION['customerno']);
    $vm->editdriverdata($drivername, $driverno, $vehicleid);
}

function getmodel($makeid) {
    $makeid = GetSafeValueString($makeid, "string");
    $vm = new VehicleManager($_SESSION['customerno']);
    $modeldata = $vm->get_model($makeid);
    return $modeldata;
}

function get_statelist($nationid) {
    $nationid = GetSafeValueString($nationid, "string");
    $vm = new VehicleManager($_SESSION['customerno']);
    $statedata = $vm->get_state_list($nationid);
    return $statedata;
}

function get_districtlist($stateid) {
    $stateid = GetSafeValueString($stateid, "string");
    $vm = new VehicleManager($_SESSION['customerno']);
    $districtdata = $vm->get_district_list($stateid);
    return $districtdata;
}

function get_citylist($districtid) {
    $districtid = GetSafeValueString($districtid, "string");
    $vm = new VehicleManager($_SESSION['customerno']);
    $citydata = $vm->get_city_list($districtid);
    return $citydata;
}

function get_branchlist($cityid) {
    $cityid = GetSafeValueString($cityid, "string");
    $vm = new VehicleManager($_SESSION['customerno']);
    $branchdata = $vm->get_branch_list($cityid);
    return $branchdata;
}

function getmodels($makeid) {
    $makeid = GetSafeValueString($makeid, "string");
    $vm = new VehicleManager($_SESSION['customerno']);
    $modeldata = $vm->get_models($makeid);
    return $modeldata;
}

function getmakes() {
    $vm = new VehicleManager($_SESSION['customerno']);
    $makedata = $vm->get_make();
    return $makedata;
}

function getbranch($branchid) {
    $vm = new VehicleManager($_SESSION['customerno']);
    $branchdata = $vm->get_branch($branchid);
    return $branchdata;
}

function addvehicle($form) {
    $vm = new VehicleManager($_SESSION['customerno']);
    $vehicleid = $vm->addvehicle($form, $_SESSION['userid']);
    return $vehicleid;
}

function edit_vehicle($form) {
    $vm = new VehicleManager($_SESSION['customerno']);
    $vehicleid = $vm->editvehicle_approval($form, $_SESSION['userid']);
    return $vehicleid;
}

function getdescription($vehicleid) {
    $vm = new VehicleManager($_SESSION['customerno']);
    $general = $vm->get_description($vehicleid);
    return $general;
}

function getloan($vehicleid) {
    $vm = new VehicleManager($_SESSION['customerno']);
    $general = $vm->get_loan($vehicleid);
    return $general;
}

function getinsurance($vehicleid) {
    $vm = new VehicleManager($_SESSION['customerno']);
    $insurance = $vm->get_insurance($vehicleid);
    return $insurance;
}

function getcapitalization($vehicleid) {
    $vm = new VehicleManager($_SESSION['customerno']);
    $capitalization = $vm->get_capitalization($vehicleid);
    return $capitalization;
}

function adddescription($form) {
    $vm = new VehicleManager($_SESSION['customerno']);
    $status = $vm->adddescription($form, $_SESSION['userid']);
    return $status;
}

function editdescription($form) {
    $vm = new VehicleManager($_SESSION['customerno']);
    $status = $vm->editdescription($form, $_SESSION['userid']);
    return $status;
}

function edittaxbyid($form) {
    $vm = new VehicleManager($_SESSION['customerno']);
    $status = $vm->edit_tax_by_id($form);
    return $status;
}

function editbattery($form) {
    $vm = new VehicleManager($_SESSION['customerno']);
    $status = $vm->edit_battery_history_by_id($form);
    return $status;
}

function editaccessory($form) {
    $vm = new VehicleManager($_SESSION['customerno']);
    $status = $vm->edit_accessory_history_by_id($form);
    return $status;
}

function editacc($form) {
    $vm = new VehicleManager($_SESSION['customerno']);
    $status = $vm->edit_accident($form);
    return $status;
}

function edittyre($form) {
    $vm = new VehicleManager($_SESSION['customerno']);
    $status = $vm->edit_tyre_by_id($form);
    return $status;
}

function editrepair($form) {
    $vm = new VehicleManager($_SESSION['customerno']);
    $status = $vm->edit_repair_by_id($form);
    return $status;
}

function addtax($form) {
    $vm = new VehicleManager($_SESSION['customerno']);
    $status = $vm->addtax($form);
    return $status;
}

function addinsurance($form) {
    $vm = new VehicleManager($_SESSION['customerno']);
    $status = $vm->add_insurance($form);
    return $status;
}

function editinsurance($form) {
    $vm = new VehicleManager($_SESSION['customerno']);
    $status = $vm->edit_insurance($form);
    return $status;
}

function addcapitalization($form) {
    $vm = new VehicleManager($_SESSION['customerno']);
    $status = $vm->add_capitalization($form);
    return $status;
}

function editcapitalization($form) {
    $vm = new VehicleManager($_SESSION['customerno']);
    $status = $vm->edit_capitalization($form);
    return $status;
}

function addbattery_approval($form) {
    $vm = new VehicleManager($_SESSION['customerno']);
    $status = $vm->add_battery_approval($form);
    return $status;
}

function addaccident_approval($form) {
    $vm = new VehicleManager($_SESSION['customerno']);
    $status = $vm->add_accident_approval_vehicle($form);
    return $status;
}

function addaccident_history($form) {
    $vm = new VehicleManager($_SESSION['customerno']);
    $status = $vm->add_accident($form);
    return $status;
}

function addbattery($form) {
    $vm = new VehicleManager($_SESSION['customerno']);
    $status = $vm->add_battery($form);
    return $status;
}

function get_amount_from_accessory($acc_id) {
    $AccManager = new AccessoryManager($_SESSION['customerno']);
    $acc = $AccManager->get_accessory($acc_id);
    return $acc;
}

function addaccessory($form) {
    $vm = new VehicleManager($_SESSION['customerno']);
    $status = $vm->add_accessory($form);
    return $status;
}

function addtyre($form) {
    $vm = new VehicleManager($_SESSION['customerno']);
    $status = $vm->add_tyre($form);
    return $status;
}

function addrepair($form) {
    $vm = new VehicleManager($_SESSION['customerno']);
    $status = $vm->add_repair($form);
    return $status;
}

function send_approval($vehicleid) {
    $vm = new VehicleManager($_SESSION['customerno']);
    $status = $vm->send_approval($vehicleid);
    return $status;
}

function send_incomplete_approval($vehicleid) {
    $vm = new VehicleManager($_SESSION['customerno']);
    $status = $vm->send_incomplete_approval($vehicleid);
    return $status;
}

function addgeotag($vehicleid, $checkpoints, $fences) {
    $vm = new VehicleManager($_SESSION['customerno']);
    $status = $vm->add_geo($vehicleid, $checkpoints, $fences, $_SESSION['userid']);
    return $status;
}

function editgeotag($vehicleid, $checkpoints, $fences) {
    $vm = new VehicleManager($_SESSION['customerno']);
    $status = $vm->edit_geo($vehicleid, $checkpoints, $fences, $_SESSION['userid']);
    return $status;
}

function getdealersbytype($type, $roleid, $heirarchy_id) {
    $dealermanager = new DealerManager($_SESSION['customerno']);
    $dealers = $dealermanager->get_dealers_by_type($type, $roleid, $heirarchy_id);
    return $dealers;
}

function getdealer($dealerid) {
    $dealermanager = new DealerManager($_SESSION['customerno']);
    $dealers = $dealermanager->get_dealer($dealerid);
    return $dealers;
}

function getpart() {
    $PartManager = new PartManager($_SESSION['customerno']);
    $parts = $PartManager->get_all_part();
    return $parts;
}

function gettask() {
    $TaskManager = new TaskManager($_SESSION['customerno']);
    $tasks = $TaskManager->get_all_task();
    return $tasks;
}

function uploadfilename($vehicleid, $filename) {
    $vehiclemanager = new VehicleManager($_SESSION['customerno']);
    $status = $vehiclemanager->filename_upload($vehicleid, $filename);
    return $status;
}

function uploadfilename1($vehicleid, $filename) {
    $vehiclemanager = new VehicleManager($_SESSION['customerno']);
    $status = $vehiclemanager->filename_upload1($vehicleid, $filename);
    return $status;
}

function uploadfilename2($vehicleid, $filename) {
    $vehiclemanager = new VehicleManager($_SESSION['customerno']);
    $status = $vehiclemanager->filename_upload2($vehicleid, $filename);
    return $status;
}

function uploadfilename3($vehicleid, $filename) {
    $vehiclemanager = new VehicleManager($_SESSION['customerno']);
    $status = $vehiclemanager->filename_upload4($vehicleid, $filename);
    return $status;
}


function get_uploaded_filename($vehicleid) {
    $vehiclemanager = new VehicleManager($_SESSION['customerno']);
    $vehicle = $vehiclemanager->get_filename($vehicleid);
    return $vehicle;
}

function getdevicehist_sqlite($location) {
    $path = "sqlite:$location";
    $db = new PDO($path);
    $queues = array();
    $query = "SELECT devicelat,devicelong,lastupdated,inbatt,status,ignition,powercut,tamper,gpsfixed,`online/offline`,gsmstrength,gsmregister,gprsregister from devicehistory ORDER by lastupdated DESC";
    $result = $db->query($query);
    if (isset($result) && $result != "") {
        foreach ($result as $row) {
            $Datacap = new VODatacap();
            $Datacap->devicelat = $row['devicelat'];
            $Datacap->devicelong = $row['devicelong'];
            $Datacap->lastupdated = date("g:i:s a", strtotime($row["lastupdated"]));
            $Datacap->inbatt = $row['inbatt'];
            $Datacap->status = $row['status'];
            $Datacap->ignition = $row['ignition'];
            $Datacap->powercut = $row['powercut'];
            $Datacap->tamper = $row['tamper'];
            $Datacap->gpsfixed = $row['gpsfixed'];
            $Datacap->onoff = $row['online/offline'];
            $Datacap->gsmstrength = $row['gsmstrength'];
            $Datacap->gsmregister = $row['gsmregister'];
            $Datacap->gprsregister = $row['gprsregister'];
            $queues[] = $Datacap;
        }
        return $queues;
    }
    return null;
}

function getunithist_sqlite($location) {
    $path = "sqlite:$location";
    $db = new PDO($path);
    $queues = array();
    $query = "SELECT analog1,analog2,analog3,analog4,digitalio,lastupdated,commandkey,commandkeyval from unithistory ORDER by lastupdated DESC";
    $result = $db->query($query);
    if (isset($result) && $result != "") {
        foreach ($result as $row) {
            $Datacap = new VODatacap();
            $Datacap->analog1 = $row['analog1'];
            $Datacap->analog2 = $row['analog2'];
            $Datacap->analog3 = $row['analog3'];
            $Datacap->analog4 = $row['analog4'];
            $Datacap->digitalio = $row['digitalio'];
            $Datacap->lastupdated = date("g:i:s a", strtotime($row["lastupdated"]));
            $Datacap->commandkey = $row['commandkey'];
            $Datacap->commandkeyval = $row['commandkeyval'];
            $queues[] = $Datacap;
        }
        return $queues;
    }
    return null;
}

function getvehhist_sqlite($location) {
    $path = "sqlite:$location";
    $db = new PDO($path);
    $queues = array();
    $query = "SELECT vehicleno,extbatt,odometer,lastupdated,curspeed from vehiclehistory ORDER by lastupdated DESC";
    $result = $db->query($query);
    if (isset($result) && $result != "") {
        foreach ($result as $row) {
            $Datacap = new VODatacap();
            $Datacap->vehicleno = $row['vehicleno'];
            $Datacap->extbatt = $row['extbatt'];
            $Datacap->odometer = $row['odometer'];
            $Datacap->lastupdated = date("g:i:s a", strtotime($row["lastupdated"]));
            $Datacap->curspeed = $row['curspeed'];
            $queues[] = $Datacap;
        }
        return $queues;
    }
    return null;
}

function add_transaction($formdata, $filedata) {

    $resp = array();
    $resp['status'] = false;

    $vehicleid = GetSafeValueString($formdata['vehicle_id'], "int");
    $dealer_id = GetSafeValueString($formdata['dealer_id'], "int");
    $notes = GetSafeValueString($formdata['notes'], "string");
    $filename = GetSafeValueString($formdata['filename'], "string");
    $amount_quote = GetSafeValueString($formdata['amount_quote'], "int");
    $vm = new VehicleManager($_SESSION['customerno']);
    $status = $vm->get_valid_transaction_for_battery($vehicleid);

    if ($status < 10) {

        // add directories

        $uploaddir = "../../customer/" . $_SESSION['customerno'] . "/vehicleid/" . $vehicleid . "/";
        $vehiclefolder = "../../customer/" . $_SESSION['customerno'] . "/vehicleid/";


        if (!file_exists($vehiclefolder)) {
            mkdir("../../customer/" . $_SESSION['customerno'] . "/vehicleid/", 0777);
        }
        $vehicleidfolder = "../../customer/" . $_SESSION['customerno'] . "/vehicleid/" . $vehicleid . "/";
        if (!file_exists($vehicleidfolder)) {
            mkdir("../../customer/" . $_SESSION['customerno'] . "/vehicleid/" . $vehicleid, 0777);
        }
        $error = false;
        foreach ($filedata as $file) {
            //echo 'AAAA'.$file['type'].'AAAAA';
//                        if (($file["type"] == "image/gif") || ($file["type"] == "image/jpeg") || ($file["type"] == "image/jpg")
            //                         || ($file["type"] == "image/pjpeg") || ($file["type"] == "image/x-png") || ($file["type"] == "image/png") || ($file["type"] == "application/pdf")){
            $filename = $uploaddir . basename($file['name']);



            $path_parts = pathinfo($filename);
            $ext = $path_parts['extension'];
            if ($ext == "pdf" || $ext == "gif" || $ext == "jpeg" || $ext == "jpg" || $ext == "png" || $ext == "pjpeg" || $ext == "x-png") {
                if (move_uploaded_file($file['tmp_name'], $uploaddir . '_battery_quote.' . $ext)) {
                    $files[] = $uploaddir . $file['name'];
                } else {
                    $error = true;
                    $resp['status'] = false;
                    $resp['status_msg'] = "File type no valid";
                }
            }
        }

        if ($error == false) {
            // sending from data
            ///$formdata['fname']=$myfilename.'.'.$ext;
            $mainid = $vm->make_transaction($formdata, $_SESSION['userid']);
            if ($mainid != '0') {
                $resp['status'] = true;
                $oldq1 = $uploaddir . '_battery_quote.pdf';
                $oldq2 = $uploaddir . '_battery_quote.png';
                $oldq3 = $uploaddir . '_battery_quote.jpg';
                $oldq4 = $uploaddir . '_battery_quote.jpeg';
                $newq1 = $uploaddir . $mainid . "_" . $formdata['category_id'] . '_quote.pdf';
                $newq2 = $uploaddir . $mainid . "_" . $formdata['category_id'] . '_quote.png';
                $newq3 = $uploaddir . $mainid . "_" . $formdata['category_id'] . '_quote.jpg';
                $newq4 = $uploaddir . $mainid . "_" . $formdata['category_id'] . '_quote.jpeg';
                if (file_exists($oldq1)) {
                    rename($oldq1, $newq1);
                    $file_name_array[] = $mainid . "_" . $formdata['category_id'] . '_quote.pdf';
                }
                if (file_exists($oldq2)) {
                    rename($oldq2, $newq2);
                    $file_name_array[] = $mainid . "_" . $formdata['category_id'] . '_quote.png';
                }
                if (file_exists($oldq3)) {
                    rename($oldq3, $newq3);
                    $file_name_array[] = $mainid . "_" . $formdata['category_id'] . '_quote.jpg';
                }
                if (file_exists($oldq4)) {
                    rename($oldq4, $newq4);
                    $file_name_array[] = $mainid . "_" . $formdata['category_id'] . '_quote.jpeg';
                }

                $vm->update_file_names_for_transaction($mainid, $file_name_array);
            } else {
                $resp['status'] = false;
                $resp['status_msg'] = "Some problem occured at the transaction level";
            }
        } else {
            $resp['status'] = false;
            $resp['status_msg'] = "Some problem occured";
        }
    } else {
        $resp['status'] = false;
        $resp['status_msg'] = "A transacion is already under processing";
    }


    echo json_encode($resp);
}

function gettransactionpdf($maintenanceid, $vehicleid) {
    $details = getbattbyid($maintenanceid);
    // print_r($details);
    //
    //$details = $details_json;
    $finalreport = '<div align="center" style="text-align: center; height:30px;">
                        <h3 style="text-transform:uppercase;">Transaction Details - (' . $details['transid'] . ')</h3>
                        </div>';
    $finalreport .= "<hr />
        <style type='text/css'>
        table, td { border: solid 1px  #999999; color:#000000; }
        hr.style-six { border: 0; height: 0; border-top: 1px solid rgba(0, 0, 0, 0.1); border-bottom: 1px solid rgba(255, 255, 255, 0.3); }
        </style>
        <tbody>";

    // Details here
    // Vehicle Details
    $finalreport.= "<table id='search_table_2' align='center' style='width: auto; font-size:13px; text-align:center;border-collapse:collapse; border:1px solid #000;'>";
    $finalreport.= "<tr style='background-color:#CCCCCC;'>
                     <td colspan='2' style='width:600px;height:auto;'>
                        <b>Vehicle Details</b>
                     </td>
                    </tr>";
    $finalreport.=" <tr>
                    <td style='width:300px;height:auto;'><b>Vehicle No.</b></td>
                    <td style='width:300px;height:auto;'>" . $details['vehicleno'] . "</td>
                    </tr>";
    $finalreport.=" <tr>
                    <td style='width:300px;height:auto;'><b>Branch</b></td>
                    <td style='width:300px;height:auto;'>" . $details['groupname'] . "</td>
                    </tr>";
    $finalreport.=" <tr>
                    <td style='width:300px;height:auto;'><b>GPS Odometer Reading</b></td>
                    <td style='width:300px;height:auto;'>" . $details['odometer'] . "</td>
                    </tr>";
    $finalreport.=" <tr>
                    <td style='width:300px;height:auto;'><b>Vehicle Meter Reading</b></td>
                    <td style='width:300px;height:auto;'>" . $details['meter_reading'] . "</td>
                    </tr>";
    $finalreport.="</table><br/>";

    // Transaction Details
    $finalreport.= "<table id='search_table_2' align='center' style='width: auto; font-size:13px; text-align:center;border-collapse:collapse; border:1px solid #000;'>";
    $finalreport.= "<tr style='background-color:#CCCCCC;'>
                     <td colspan='2' style='width:600px;height:auto;'>
                        <b>Transaction Details</b>
                     </td>
                    </tr>";
    $finalreport.=" <tr>
                    <td style='width:300px;height:auto;'><b>Transaction ID</b></td>
                    <td style='width:300px;height:auto;'>" . $details['transid'] . "</td>
                    </tr>";
    $finalreport.=" <tr>
                    <td style='width:300px;height:auto;'><b>Category</b></td>
                    <td style='width:300px;height:auto;'>" . $details['category'] . "</td>
                    </tr>";
    $finalreport.=" <tr>
                    <td style='width:300px;height:auto;'><b>Dealer Name</b></td>
                    <td style='width:300px;height:auto;'>" . $details['dealername'] . "</td>
                    </tr>";
    $finalreport.=" <tr>
                    <td style='width:300px;height:auto;'><b>Notes</b></td>
                    <td style='width:300px;height:auto;'>" . $details['notes'] . "</td>
                    </tr>";
    $finalreport.=" <tr>
                    <td style='width:300px;height:auto;'><b>Status</b></td>
                    <td style='width:300px;height:auto;'>" . $details['statusname'] . "</td>
                    </tr>";
    $finalreport.=" <tr>
                    <td style='width:300px;height:auto;'><b>Transaction Close Date</b></td>
                    <td style='width:300px;height:auto;'>" . $details['timestamp'] . "</td>
                    </tr>";
    $finalreport.=" <tr>
                    <td style='width:300px;height:auto;'><b>Vehicle In Date</b></td>
                    <td style='width:300px;height:auto;'>" . $details['vehicle_in_date'] . "</td>
                    </tr>";
    $finalreport.=" <tr>
                    <td style='width:300px;height:auto;'><b>Vehicle Out Date</b></td>
                    <td style='width:300px;height:auto;'>" . $details['vehicle_out_date'] . "</td>
                    </tr>";
    $finalreport.="</table><br/>";

    // Quotation Details
    $finalreport.= "<table id='search_table_2' align='center' style='width: auto; font-size:13px; text-align:center;border-collapse:collapse; border:1px solid #000;'>";
    $finalreport.= "<tr style='background-color:#CCCCCC;'>
                     <td colspan='2' style='width:600px;height:auto;'>
                        <b>Quotation Details</b>
                     </td>
                    </tr>";
    $finalreport.=" <tr>
                    <td style='width:300px;height:auto;'><b>Quotation Amount (INR)</b></td>
                    <td style='width:300px;height:auto;'>" . $details['amount_quote'] . "</td>
                    </tr>";
    $finalreport.=" <tr>
                    <td style='width:300px;height:auto;'><b>Quotation Submission Date</b></td>
                    <td style='width:300px;height:auto;'>" . $details['submission_date'] . "</td>
                    </tr>";
    $finalreport.=" <tr>
                    <td style='width:300px;height:auto;'><b>Quotation Approval Note</b></td>
                    <td style='width:300px;height:auto;'>" . $details['approval_notes'] . "</td>
                    </tr>";
    $finalreport.="</table><br/>";

    if ($details['categoryid'] == '5') {
        // Accessory Details
        $finalreport.= "<table id='search_table_2' align='center' style='width: auto; font-size:13px; text-align:center;border-collapse:collapse; border:1px solid #000;'>";
        $finalreport.= "<tr style='background-color:#CCCCCC;'>
                     <td colspan='4' style='width:600px;height:auto;'>
                        <b>Accessory Details</b>
                     </td>
                    </tr>";
        $finalreport.=" <tr>
                    <td style='width:50px;height:auto;'><b>Sr. No.</b></td>
                    <td style='width:30px;height:auto;'><b>Accessory</b></td>
                    <td style='width:175px;height:auto;'><b>Cost</b></td>
                    <td style='width:175px;height:auto;'><b>Max. Perm. Amount</b></td>
                    </tr>";
        $accdata = getaccessories_approval($details['mainid']);
        if (isset($accdata)) {
            foreach ($accdata as $thisacc) {
                $finalreport.=" <tr>
                            <td style='width:50px;height:auto;'>" . $thisacc['count'] . "</td>
                            <td style='width:300px;height:auto;'>" . $thisacc['name'] . "</td>
                            <td style='width:175px;height:auto;'>" . $thisacc['cost'] . "</td>
                            <td style='width:175px;height:auto;'>" . $thisacc['max_amount'] . "</td>
                            </tr>";
            }
        }
        $finalreport.="</table><br/>";
    }

    if ($details['categoryid'] == '1') {
        // Tyre Details
        $finalreport.= "<table id='search_table_2' align='center' style='width: auto; font-size:13px; text-align:center;border-collapse:collapse; border:1px solid #000;'>";
        $finalreport.= "<tr style='background-color:#CCCCCC;'>
                     <td colspan='2' style='width:600px;height:auto;'>
                        <b>Tyres Replaced</b>
                     </td>
                    </tr>";
        $finalreport.=" <tr>
                    <td style='width:50px;height:auto;'><b>Sr. No.</b></td>
                    <td style='width:300px;height:auto;'><b>Type</b></td>
                    </tr>";
        $data_parts = $details['tyre_type'];
        if (isset($data_parts) && $data_parts != "") {
            $data = explode(",", $data_parts);
            if (isset($data)) {
                $x = 1;
                foreach ($data as $thisdata) {
                    $finalreport.=" <tr>
                                <td style='width:50px;height:auto;'>$x</td>
                                <td style='width:300px;height:auto;'>$thisdata</td>
                                </tr>";
                    $x++;
                }
            }
        } else {
            $finalreport.=" <tr>
                            <td style='width:450px;height:auto;' colspan='2'>No Tyres Replaced</td>
                            </tr>";
        }
        $finalreport.="</table><br/>";
    }

    if ($details['categoryid'] == '2') {
        // Parts Details
        $finalreport.= "<table id='search_table_2' align='center' style='width: auto; font-size:13px; text-align:center;border-collapse:collapse; border:1px solid #000;'>";
        $finalreport.= "<tr style='background-color:#CCCCCC;'>
                     <td colspan='2' style='width:600px;height:auto;'>
                        <b>Parts Consumed</b>
                     </td>
                    </tr>";
        $finalreport.=" <tr>
                    <td style='width:50px;height:auto;'><b>Sr. No.</b></td>
                    <td style='width:300px;height:auto;'><b>Parts</b></td>
                    </tr>";
        $data_parts = $details['partsnew'];
        if (isset($data_parts) && $data_parts != "") {
            $data = explode(",", $data_parts);
            if (isset($data)) {
                $x = 1;
                foreach ($data as $thisdata) {
                    $finalreport.=" <tr>
                                <td style='width:50px;height:auto;'>$x</td>
                                <td style='width:300px;height:auto;'>$thisdata</td>
                                </tr>";
                    $x++;
                }
            }
        } else {
            $finalreport.=" <tr>
                            <td style='width:450px;height:auto;' colspan='2'>No Parts Consumed</td>
                            </tr>";
        }
        $finalreport.="</table><br/>";


        // Tasks Details
        $finalreport.= "<table id='search_table_2' align='center' style='width: auto; font-size:13px; text-align:center;border-collapse:collapse; border:1px solid #000;'>";
        $finalreport.= "<tr style='background-color:#CCCCCC;'>
                     <td colspan='2' style='width:600px;height:auto;'>
                        <b>Tasks Performed</b>
                     </td>
                    </tr>";
        $finalreport.=" <tr>
                    <td style='width:50px;height:auto;'><b>Sr. No.</b></td>
                    <td style='width:400px;height:auto;'><b>Tasks</b></td>
                    </tr>";
        $data_parts = $details['tasksnew'];
        if (isset($data_parts) && $data_parts != "") {
            $data = explode(",", $data_parts);
            if (isset($data)) {
                $x = 1;
                foreach ($data as $thisdata) {
                    $finalreport.=" <tr>
                                <td style='width:50px;height:auto;'>$x</td>
                                <td style='width:400px;height:auto;'>$thisdata</td>
                                </tr>";
                    $x++;
                }
            }
        } else {
            $finalreport.=" <tr>
                            <td style='width:450px;height:auto;' colspan='2'>No Tasks Performed</td>
                            </tr>";
        }
        $finalreport.="</table><br/>";
    }

    // Invoice Details
    $finalreport.= "<table id='search_table_2' align='center' style='width: auto; font-size:13px; text-align:center;border-collapse:collapse; border:1px solid #000;'>";
    $finalreport.= "<tr style='background-color:#CCCCCC;'>
                     <td colspan='2' style='width:600px;height:auto;'>
                        <b>Invoice Details</b>
                     </td>
                    </tr>";
    $finalreport.=" <tr>
                    <td style='width:300px;height:auto;'><b>Invoice Generation Date</b></td>
                    <td style='width:300px;height:auto;'>" . $details['invoice_date'] . "</td>
                    </tr>";
    $finalreport.=" <tr>
                    <td style='width:300px;height:auto;'><b>Invoice Number</b></td>
                    <td style='width:300px;height:auto;'>" . $details['batt_invoice_no'] . "</td>
                    </tr>";
    $finalreport.=" <tr>
                    <td style='width:300px;height:auto;'><b>Invoice Amount</b></td>
                    <td style='width:300px;height:auto;'>" . $details['invoice_amount'] . "</td>
                    </tr>";

    $finalreport.=" <tr>
                    <td style='width:300px;height:auto;'><b>" . $_SESSION['ref_number'] . "</b></td>
                    <td style='width:300px;height:auto;'>" . $details['ofasnumber'] . "</td>
                    </tr>";

    $finalreport.=" <tr>
                    <td style='width:300px;height:auto;'><b>Payment Approval Date</b></td>
                    <td style='width:300px;height:auto;'>" . $details['payment_approval_date'] . "</td>
                    </tr>";
    $finalreport.=" <tr>
                    <td style='width:300px;height:auto;'><b>Payment Approval Note</b></td>
                    <td style='width:300px;height:auto;'>" . $details['payment_approval_note'] . "</td>
                    </tr>";
    $finalreport.="</table><br/>";

    $finalreport .= '</tbody>';
    $finalreport .="
	<page_footer>
                    [[page_cu]]/[[page_nb]]
    </page_footer>";
    $finalreport .= "<hr style='margin-top:5px;'>";
    $formatdate1 = date(speedConstants::DEFAULT_DATETIME);
    $finalreport .= "<div align='right' style='text-align:center;'> PDF Generated On: $formatdate1 </div>";


    echo $finalreport;
}

function getaccidentpdf($maintenanceid, $vehicleid) {
    $details_json = get_accident_details($maintenanceid);
    $details = json_decode($details_json);
    $finalreport = '<div align="center" style="text-align: center; height:30px;">
                        <h3 style="text-transform:uppercase;">Accident Claim Details - (' . $details->transid . ')</h3>
                        </div>';
    $finalreport .= "<hr />
        <style type='text/css'>
        table, td { border: solid 1px  #999999; color:#000000; }
        hr.style-six { border: 0; height: 0; border-top: 1px solid rgba(0, 0, 0, 0.1); border-bottom: 1px solid rgba(255, 255, 255, 0.3); }
        </style>
        <tbody>";

    // Details here
    // Vehicle Details
    $finalreport.= "<table id='search_table_2' align='center' style='width: auto; font-size:13px; text-align:center;border-collapse:collapse; border:1px solid #000;'>";
    $finalreport.= "<tr style='background-color:#CCCCCC;'>
                     <td colspan='2' style='width:600px;height:auto;'>
                        <b>Vehicle Details</b>
                     </td>
                    </tr>";
    $finalreport.=" <tr>
                    <td style='width:300px;height:auto;'><b>Vehicle No.</b></td>
                    <td style='width:300px;height:auto;'>" . $details->vehicleno . "</td>
                    </tr>";
    $finalreport.=" <tr>
                    <td style='width:300px;height:auto;'><b>Branch</b></td>
                    <td style='width:300px;height:auto;'>" . $details->groupname . "</td>
                    </tr>";
    $finalreport.=" <tr>
                    <td style='width:300px;height:auto;'><b>GPS Odometer Reading</b></td>
                    <td style='width:300px;height:auto;'>$details->odometer</td>
                    </tr>";
    $finalreport.=" <tr>
                    <td style='width:300px;height:auto;'><b>Driver Name</b></td>
                    <td style='width:300px;height:auto;'>$details->drivername</td>
                    </tr>";
    $finalreport.=" <tr>
                    <td style='width:300px;height:auto;'><b>Driver License Validity From</b></td>
                    <td style='width:300px;height:auto;'>$details->val_from_Date</td>
                    </tr>";
    $finalreport.=" <tr>
                    <td style='width:300px;height:auto;'><b>Driver License Validity To</b></td>
                    <td style='width:300px;height:auto;'>$details->val_to_Date</td>
                    </tr>";
    $finalreport.=" <tr>
                    <td style='width:300px;height:auto;'><b>Type of License</b></td>
                    <td style='width:300px;height:auto;'>$details->licence_type</td>
                    </tr>";

    $finalreport.="</table><br/>";

    // Accident Details
    $finalreport.= "<table id='search_table_2' align='center' style='width: auto; font-size:13px; text-align:center;border-collapse:collapse; border:1px solid #000;'>";
    $finalreport.= "<tr style='background-color:#CCCCCC;'>
                     <td colspan='2' style='width:600px;height:auto;'>
                        <b>Accident Details</b>
                     </td>
                    </tr>";
    $finalreport.=" <tr>
                    <td style='width:300px;height:auto;'><b>Transaction ID</b></td>
                    <td style='width:300px;height:auto;'>$details->transid</td>
                    </tr>";
    $finalreport.=" <tr>
                    <td style='width:300px;height:auto;'><b>Transaction Approval Date</b></td>
                    <td style='width:300px;height:auto;'>$details->approval_date</td>
                    </tr>";
    $finalreport.=" <tr>
                    <td style='width:300px;height:auto;'><b>Category</b></td>
                    <td style='width:300px;height:auto;'>Accident</td>
                    </tr>";
    $finalreport.=" <tr>
                    <td style='width:300px;height:auto;'><b>Accident Date</b></td>
                    <td style='width:300px;height:auto;'>$details->acc_Date</td>
                    </tr>";
    $finalreport.=" <tr>
                    <td style='width:300px;height:auto;'><b>Accident Time</b></td>
                    <td style='width:300px;height:auto;'>$details->STime</td>
                    </tr>";
    $finalreport.=" <tr>
                    <td style='width:300px;height:auto;'><b>Accident Location</b></td>
                    <td style='width:300px;height:auto;'>$details->acc_location</td>
                    </tr>";
    $finalreport.=" <tr>
                    <td style='width:300px;height:auto;'><b>Third Party Injury / Property Damage</b></td>
                    <td style='width:300px;height:auto;'>$details->tpi</td>
                    </tr>";
    $finalreport.=" <tr>
                    <td style='width:300px;height:auto;'><b>Accident Description</b></td>
                    <td style='width:300px;height:auto;'>$details->acc_desc</td>
                    </tr>";
    $finalreport.=" <tr>
                    <td style='width:300px;height:auto;'><b>Name and Location of Workshop</b></td>
                    <td style='width:300px;height:auto;'>$details->add_workshop</td>
                    </tr>";
    $finalreport.=" <tr>
                    <td style='width:300px;height:auto;'><b>Report Sent to</b></td>
                    <td style='width:300px;height:auto;'>$details->send_report</td>
                    </tr>";
    $finalreport.=" <tr>
                    <td style='width:300px;height:auto;'><b>Estimated Loss Amount (INR)</b></td>
                    <td style='width:300px;height:auto;'>$details->loss_amount</td>
                    </tr>";
    $finalreport.=" <tr>
                    <td style='width:300px;height:auto;'><b>Settlement Amount (INR)</b></td>
                    <td style='width:300px;height:auto;'>$details->sett_amount</td>
                    </tr>";
    $finalreport.=" <tr>
                    <td style='width:300px;height:auto;'><b>Actual Repair Amount (INR)</b></td>
                    <td style='width:300px;height:auto;'>$details->actual_amount</td>
                    </tr>";
    $finalreport.=" <tr>
                    <td style='width:300px;height:auto;'><b>Amount Spent by Mahindra (INR)</b></td>
                    <td style='width:300px;height:auto;'>$details->mahindra_amount</td>
                    </tr>";
    if ($_SESSION["use_hierarchy"] == '1') {
        $finalreport.=" <tr>
                    <td style='width:300px;height:auto;'><b>OFAS Number</b></td>
                    <td style='width:300px;height:auto;'>$details->ofasnumber</td>
                    </tr>";
    } else {
        $finalreport.=" <tr>
                    <td style='width:300px;height:auto;'><b>Transaction Reference Number</b></td>
                    <td style='width:300px;height:auto;'>$details->ofasnumber</td>
                    </tr>";
    }
    $finalreport.=" <tr>
                    <td style='width:300px;height:auto;'><b>Approval Note</b></td>
                    <td style='width:300px;height:auto;'>$details->approval_notes</td>
                    </tr>";
    $finalreport.="</table><br/>";

    $finalreport .= '</tbody>';
    $finalreport .="
	<page_footer>
                    [[page_cu]]/[[page_nb]]
    </page_footer>";
    $finalreport .= "<hr style='margin-top:5px;'>";
    $formatdate1 = date(speedConstants::DEFAULT_DATETIME);
    $finalreport .= "<div align='right' style='text-align:center;'> PDF Generated On: $formatdate1 </div>";


    echo $finalreport;
}

function doMultipleApproval($action, $approvedata, $note) {
    $approvelist = explode(",", $approvedata);
    foreach ($approvelist as $approve) {
        $List[] = explode("-", $approve);
    }
    if ($action == '1') {
        foreach ($List as $key => $val) {
            $approve_obj = new stdClass();
            $approve_obj->mainid = $val[0];
            $approve_obj->statusid = $val[1];
            if ($approve_obj->statusid == '7') {
                $approve_obj->statusid = '8';
            } else if ($approve_obj->statusid == '10') {
                $approve_obj->statusid = '13';
            }
            pushstatus($approve_obj->mainid, $approve_obj->statusid, $note);
        }
    } else if ($action == '2') {
        foreach ($List as $key => $val) {
            $approve_obj = new stdClass();
            $approve_obj->mainid = $val[0];
            $approve_obj->statusid = $val[1];
            if ($approve_obj->statusid == '7') {
                $approve_obj->statusid = '9';
            } else if ($approve_obj->statusid == '10') {
                $approve_obj->statusid = '11';
            }
            pushstatus($approve_obj->mainid, $approve_obj->statusid, $note);
        }
    }
}

function deletetaskspopup($taskid){
    $maintanancemanager = new MaintananceManager($_SESSION['customerno']);
    $maintanancemanager->DeleteTaskpopup($taskid);
}

function deletepartspopup($partid){
    $maintanancemanager = new MaintananceManager($_SESSION['customerno']);
    $maintanancemanager->DeletePartspopup($partid);
}

function edittaskpopup($data){
    $maintanancemanager = new MaintananceManager($_SESSION['customerno']);
    $maintanancemanager->edittaskpopup($data);
    return true;
}

function editpartpopup($data){
    $maintanancemanager = new MaintananceManager($_SESSION['customerno']);
    $maintanancemanager->editpartspopup($data);
    return true;
}

function addpartspopup($data,$tid){
    $maintanancemanager = new MaintananceManager($_SESSION['customerno']);
    $maintanancemanager->addpartspopup($data,$tid);
    return true;
}

function addtaskspopup($data,$tid){
    $maintanancemanager = new MaintananceManager($_SESSION['customerno']);
    $maintanancemanager->addtaskspopup($data,$tid);
    return true;
}



function edittransdetailsvehicle($data){
    $maintanancemanager = new MaintananceManager($_SESSION['customerno']);
    $maintanancemanager->updateTransdetails_vehicle($data);
    return true;
}

function updatetax($data){
    $maintanancemanager = new MaintananceManager($_SESSION['customerno']);
    $maintanancemanager->updatetaxamt($data);
    return true;
}

function updateinvoice($data){
    $maintanancemanager = new MaintananceManager($_SESSION['customerno']);
    $maintanancemanager->updateinvamt($data);
    return true;
}

function updateTransdetails($data){
    $maintanancemanager = new MaintananceManager($_SESSION['customerno']);
    $maintanancemanager->updatetransdetails($data);
    return true;
}

function updateqtndetails($data,$filedata){
    $maintanancemanager = new MaintananceManager($_SESSION['customerno']);
    $maintanancemanager->updateqtndetails($data,$filedata);
    return true;
}

function editbatterypop($data){
    $maintanancemanager = new MaintananceManager($_SESSION['customerno']);
    $maintanancemanager->editbatterypopup($data);
    return true;
}

function edittyrepopup($data){
    $maintanancemanager = new MaintananceManager($_SESSION['customerno']);
    $maintanancemanager->edittyrepopup($data,$_SESSION['userid']);
    return true;
}

function getPartsDetails($partid){
    $partobj = new PartManager($_SESSION['customerno']);
    $result =  $partobj->get_part($partid);
    return $result;
}

function gettaskDetails($taskid){
    $taskobj = new TaskManager($_SESSION['customerno']);
    $result = $taskobj->get_task($taskid);
    return $result;
}


?>
