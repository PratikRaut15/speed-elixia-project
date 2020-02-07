<?php

include_once '../../lib/bo/GeofenceManager.php';
include_once '../../lib/bo/CheckpointManager.php';
include_once '../../lib/bo/VehicleManager.php';
include_once '../../lib/bo/GroupManager.php';
include_once '../../lib/system/utilities.php';
include_once '../../lib/bo/UnitManager.php';
include_once '../../lib/bo/DealerManager.php';
include_once '../../lib/bo/CityManager.php';
include_once '../../lib/bo/PartManager.php';
include_once '../../lib/bo/TaskManager.php';
include_once '../../lib/bo/AccessoryManager.php';
include_once '../../lib/bo/MaintananceManager.php';
include_once '../../lib/bo/DealerManager.php';
include_once '../../lib/comman_function/reports_func.php';

if (!isset($_SESSION)) {
    session_start();
}

$tyre_arr = array(1 => "Right front", 2 => 'Right Back', 3 => 'Left front', 4 => 'Left Back', 5 => 'Stepney');

class VODatacap {

}

function getdate_IST() {
    $ServerDate_IST = strtotime(date("Y-m-d H:i:s"));
    return $ServerDate_IST;
}

function getvehicle($vehicleid) {
    $vehicleid = GetSafeValueString($vehicleid, "string");
    $vehiclemanager = new VehicleManager($_SESSION['customerno']);
    $vehicle = $vehiclemanager->get_vehicle_with_driver($vehicleid);
    if ($vehicle->isdeleted == '1')
        header("location:vehicle.php");

    return $vehicle;
}

function getallmaintanances() {
    $maintanace_obj = new MaintananceManager($_SESSION['customerno']);
    $maintanaces = $maintanace_obj->get_all_transaction();
    return $maintanaces;
}

function getfilteredmaintanances($transid, $vehicleid, $categoryid, $statusid, $tyre, $parts, $dealerid) {
    $maintanace_obj = new MaintananceManager($_SESSION['customerno']);
    $maintanaces = $maintanace_obj->get_filtered_transaction($transid, $vehicleid, $categoryid, $statusid, $tyre, $parts, $dealerid);
    return $maintanaces;
}

function getallfuels() {
    $maintanace_obj = new MaintananceManager($_SESSION['customerno']);
    $maintanaces = $maintanace_obj->get_all_fuels();
    return $maintanaces;
}

function getallaccidents() {
    $accident_obj = new VehicleManager($_SESSION['customerno']);
    $accidents = $accident_obj->get_accident_transaction();
    return $accidents;
}

function getfilteredaccidents($transid, $vehicleid, $statusid) {
    $accident_obj = new VehicleManager($_SESSION['customerno']);
    $accidents = $accident_obj->get_filtered_accidents($transid, $vehicleid, $statusid);
    return $accidents;
}

function getfilteredfuels($transid, $vehicleid, $dealerid) {
    $m_obj = new MaintananceManager($_SESSION['customerno']);
    $ffuels = $m_obj->get_filtered_fuels($transid, $vehicleid, $dealerid);
    return $ffuels;
}

function getvehicles() {
    $vehiclemanager = new VehicleManager($_SESSION['customerno']);
    $vehicles = $vehiclemanager->get_all_vehicles_by_groupname_for_transactions();
    return $vehicles;
}

function getapproved_vehicles() {
    $vehiclemanager = new VehicleManager($_SESSION['customerno']);
    $vehicles = $vehiclemanager->get_approved_vehicles();
    return $vehicles;
}

function get_trans_status() {
    $maintanace_obj = new MaintananceManager($_SESSION['customerno']);
    $status = $maintanace_obj->get_trans_status();
    return $status;
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

function getbattbyid($maintenanceid) {
    $vehiclemanager = new VehicleManager($_SESSION['customerno']);
    $battery = $vehiclemanager->getbattery_id($maintenanceid);
    return $battery;
}

function getpartslist($maintenanceid) {
    $mm = new MaintananceManager($_SESSION['customerno']);
    $parts = $mm->getPartsList($maintenanceid);
    return $parts;
}

function gettasklist($maintenanceid) {
    $mm = new MaintananceManager($_SESSION['customerno']);
    $parts = $mm->getTasksList($maintenanceid);
    return $parts;
}

function getmaintananceHistory($maintenanceid) {
    $mm = new MaintananceManager($_SESSION['customerno']);
    $history = $mm->getMaintenanceHistory($maintenanceid);
    return $history;
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

function getaccessories_approval($mainid) {
    $mm = new MaintananceManager($_SESSION['customerno']);
    $data = $mm->getaccessories_forapproval($mainid);
    return $data;
}

function addvehicle($form) {
    $vm = new VehicleManager($_SESSION['customerno']);
    $vehicleid = $vm->addvehicle($form, $_SESSION['userid']);
    return $vehicleid;
}

function edit_vehicle($form) {
    $vm = new VehicleManager($_SESSION['customerno']);
    $vehicleid = $vm->editvehicle($form, $_SESSION['userid']);
    return $vehicleid;
}

function getgeneral($vehicleid) {
    $vm = new VehicleManager($_SESSION['customerno']);
    $general = $vm->get_general($vehicleid);
    return $general;
}

function getdescription($vehicleid) {
    $vm = new VehicleManager($_SESSION['customerno']);
    $general = $vm->get_description($vehicleid);
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

function editbattery($form, $statusid = null){
    $vm = new VehicleManager($_SESSION['customerno']);
    $mm = new MaintananceManager($_SESSION['customerno']);
    $roleid = $mm->getTransactionRole($form['maintenanceid']);
    $creator = $vm->get_transaction_creator($form['maintenanceid']);
    $approver = '';
                if ($_SESSION['use_hierarchy'] == '1') {
                    $tyre_list = isset($form['newtyre_list']) ? $form['newtyre_list'] : '';
                    $roleid = $vm->get_approvarid_for_transaction($form['category_id'], $form['edit_vehicle_id'], $form['batt_amount_invoice'], $form['vehicle_meter_reading'], $tyre_list,$statusid);
                    $creator = $vm->get_transaction_creator($form['maintenanceid']);
                    $approver = '';
                    /* TODO CREATE SP for below function */
                    $approver = $vm->get_transaction_approver($form['maintenanceid'], $roleid);
                    //echo $roleid; die($statusid);
                    $status = $vm->edit_battery_by_id($form, $statusid,$roleid);
                    if ($status == 'ok') {
                        if (!empty($approver)) {
                            send_transactionmail($approver, $form['maintenanceid']);
                        }
                    }
                }
    return $status;
}

function editacc($form) {
    $vm = new VehicleManager($_SESSION['customerno']);
    $status = $vm->edit_accident_ofas($form);
    if ($status == 'ok') {
        $mm = new MaintananceManager($_SESSION['customerno']);
        $roleid = $mm->getAccidentRole($form['maintenanceid']);
        $creator = $vm->get_accident_creator($form['maintenanceid']);
        $approver = $vm->get_accident_approver($form['maintenanceid'], $roleid);
        if (!empty($creator)) {
            send_accidentmail($creator, $form['maintenanceid']);
        }
        if (!empty($approver)) {
            //send_accidentmail($approver, $form['maintenanceid']);
        }
    }
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
    $status = $vm->add_accident_approval($form);
    //notok

    if (isset($status) != 'notok') {

        $mm = new MaintananceManager($_SESSION['customerno']);
        $roleid = $mm->getAccidentRole($status);
        $creator = $vm->get_accident_creator($status);
        $approver = $vm->get_accident_approver($status, $roleid);
        if (!empty($creator)) {
            //send_accidentmail($creator, $status);
        }
        if (!empty($approver)) {
            send_accidentmail($approver, $status);
        }
        //$status = 'ok';
        return $status;
    } else {
        return $status;
    }
}

function addaccident_history($form) {
    $vm = new VehicleManager($_SESSION['customerno']);
    $status = $vm->add_accident($form);
    return $status;
}

function pulldealer($form) {
    $dm = new DealerManager($_SESSION['customerno']);
    $dealers = $dm->pull_dealers($form);
    return $dealers;
}

function pulldealer_byvehicle($vehicleid, $type) {
    $dm = new DealerManager($_SESSION['customerno']);
    $dealers = $dm->pull_dealers_by_vehicle($vehicleid, $type);
    return $dealers;
}

function pulldealer_byvehicle_arr($vehicleid, $type) {
    $dm = new DealerManager($_SESSION['customerno']);
    $dealers = $dm->pull_dealers_by_vehicle_arr($vehicleid, $type);
    return $dealers;
}

function pullendkm($vehicleid) {
    $vm = new VehicleManager($_SESSION['customerno']);
    $dealers = $vm->getEnddingKm($vehicleid);
    return $dealers;
}

function get_all_cities($nationid) {
    $citymanager = new CityManager($_SESSION['customerno']);
    $cities = $citymanager->get_all_cities_nation($nationid);
    return $cities;
}

function adddealer($name, $phoneno, $cellphone, $notes, $address, $vendor, $code, $other1, $other2) {
    $name = GetSafeValueString($name, "string");
    $phoneno = GetSafeValueString($phoneno, "string");
    $cellphone = GetSafeValueString($cellphone, "string");
    $notes = GetSafeValueString($notes, "string");
    $address = GetSafeValueString($address, "string");
    $vendor = GetSafeValueString($vendor, "string");
    $code = GetSafeValueString($code, "string");

    $dealermanager = new DealerManager($_SESSION['customerno']);
    $response = $dealermanager->add_dealer($name, $phoneno, $cellphone, $notes, $address, $vendor, $code, $_SESSION['userid'], $other1, $other2);
    return $response;
}

function addbattery($form) {
    $vm = new VehicleManager($_SESSION['customerno']);
    $status = $vm->add_battery($form);
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

function addFuel($form) {
    $vm = new VehicleManager($_SESSION['customerno']);
    $status = $vm->add_fuel_transaction($form);
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

function get_accident_details($accidentid) {
    $vm = new VehicleManager($_SESSION['customerno']);
    $status = $vm->getacc_id($accidentid);
    return $status;
}

function editgeotag($vehicleid, $checkpoints, $fences) {
    $vm = new VehicleManager($_SESSION['customerno']);
    $status = $vm->edit_geo($vehicleid, $checkpoints, $fences, $_SESSION['userid']);
    return $status;
}

function getdealersbytype($type) {
    $dealermanager = new DealerManager($_SESSION['customerno']);
    $dealers = $dealermanager->get_dealers_by_type($type);
    return $dealers;
}

function getdealer($dealerid) {
    $dealermanager = new DealerManager($_SESSION['customerno']);
    $dealers = $dealermanager->get_dealer($dealerid);
    return $dealers;
}

function gettask() {
    $TaskManager = new TaskManager($_SESSION['customerno']);
    $tasks = $TaskManager->get_all_task();
    return $tasks;
}

function getpart_byid($vehicleid, $customerno) {
    $PartManager = new PartManager($customerno);
    $parts = $PartManager->get_all_part();
    return $parts;
}

function gettask_byid($vehicleid) {
    $TaskManager = new TaskManager($customerno);
    $tasks = $TaskManager->get_all_task();
    return $tasks;
}

function getaccessories() {
    $AccManager = new AccessoryManager($_SESSION['customerno']);
    $accessories = $AccManager->get_all_accessories();
    return $accessories;
}

function get_amount_from_accessory($acc_id) {
    $AccManager = new AccessoryManager($_SESSION['customerno']);
    $acc = $AccManager->get_accessory($acc_id);
    return $acc;
}

function uploadfilename($vehicleid, $filename) {
    $vehiclemanager = new VehicleManager($_SESSION['customerno']);
    $status = $vehiclemanager->filename_upload($vehicleid, $filename);
    return $status;
}

function get_uploaded_filename($vehicleid) {
    $vehiclemanager = new VehicleManager($_SESSION['customerno']);
    $vehicle = $vehiclemanager->get_filename($vehicleid);
    return $vehicle;
}

function uploadfilename1($vehicleid, $filename) {
    $vehiclemanager = new VehicleManager($_SESSION['customerno']);
    $status = $vehiclemanager->filename_upload1($vehicleid, $filename);
    return $status;
}

function get_vehicle_details_by_id($vehid) {
    $vehiclemanager = new VehicleManager($_SESSION['customerno']);
    $vehno = $vehiclemanager->get_vehicle_details_by_id($vehid);
    return $vehno;
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

function cancel_transaction($data){
    $mm = new MaintananceManager($_SESSION['customerno']);
    $result = $mm->cancel_transaction($data);
    return $result;
}

function rollback_transaction($data){
    $mm = new MaintananceManager($_SESSION['customerno']);
    $result = $mm->rollback_transaction($data);
    return $result;
}

function add_transaction($formdata, $filedata){
    $resp = array();
    $resp['status'] = true;
    $resp['mainid'] = 0;

    if (isset($formdata['vehicle_id'])) {
        $vehicleid = GetSafeValueString($formdata['vehicle_id'], "int");
    }
    if (isset($formdata['dealer_id'])) {
        $dealer_id = GetSafeValueString($formdata['dealer_id'], "int");
    }
    if (isset($formdata['notes'])) {
        $notes = GetSafeValueString($formdata['notes'], "string");
    }
    //$notes = GetSafeValueString($formdata['note_batt'], "string"); if dosent work uncomment this line and comment above line
    if (isset($formdata['filename'])) {
        $filename = GetSafeValueString($formdata['filename'], "string");
    }
    if (isset($formdata['amount_quote'])) {
        $amount_quote = GetSafeValueString($formdata['amount_quote'], "int");
    }
    $statusid = isset($formdata['status']) ? GetSafeValueString($formdata['status'], "int") : 7;

    if (isset($_SESSION['customerno'])) {
        $vm = new VehicleManager($_SESSION['customerno']);

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
        if (!empty($filedata)) {
            foreach ($filedata as $file) {
                $filename = $uploaddir . basename($file['name']);
                $path_parts = pathinfo($filename);
                $ext = $path_parts['extension'];
                if ($ext == "pdf" || $ext == "gif" || $ext == "jpeg" || $ext == "jpg" || $ext == "png" || $ext == "pjpeg" || $ext == "x-png") {
                    if ($statusid == '10') {
                        if (move_uploaded_file($file['tmp_name'], $uploaddir . '_invoice.' . $ext)) {
                            $files[] = $uploaddir . $file['name'];
                        } else {
                            $error = true;
                            $resp['status'] = false;
                            $resp['status_msg'] = "File type no valid";
                        }
                    } else {
                        if (move_uploaded_file($file['tmp_name'], $uploaddir . '_quote.' . $ext)) {
                            $files[] = $uploaddir . $file['name'];
                        } else {
                            $error = true;
                            $resp['status'] = false;
                            $resp['status_msg'] = "File type no valid";
                        }
                    }
                }
            }
        }
        if ($error == false) {
            $mainid = $vm->make_transaction($formdata, $_SESSION['userid']);
            $resp['mainid'] = $mainid;
            if ($mainid != '0') {
                if ($_SESSION['use_hierarchy'] == '1') {
                    $tyre_list = isset($formdata['tyre_list']) ? $formdata['tyre_list'] : '';
                    $roleid = $vm->get_approvarid_for_transaction($formdata['category_id'], $formdata['vehicle_id'], $formdata['amount_quote'], $formdata['meter_reading'], $tyre_list,$statusid);
                    $creator = $vm->get_transaction_creator($mainid);
                    $approver = '';
                    /* TODO CREATE SP for below function */
                    $approver = $vm->get_transaction_approver($mainid, $roleid);

                    if (!empty($creator)) {
                        //send_transactionmail($creator, $mainid);
                    }
                    if (!empty($approver)){
                        //send_transactionmail($approver, $mainid);
                        send_transactionmail_htmltemplate($approver, $mainid);
                    }
                } else {
                    $roleid = 5;
                }
            }
            if ($mainid != '0') {
                if (!empty($filedata)) {
                    if ($statusid == '10'){
                        $resp['status'] = true;
                        $oldq1 = $uploaddir . '_invoice.pdf';
                        $oldq2 = $uploaddir . '_invoice.png';
                        $oldq3 = $uploaddir . '_invoice.jpg';
                        $oldq4 = $uploaddir . '_invoice.jpeg';
                        $newq1 = $uploaddir . $mainid . "_" . $formdata['category_id'] . '_invoice.pdf';
                        $newq2 = $uploaddir . $mainid . "_" . $formdata['category_id'] . '_invoice.png';
                        $newq3 = $uploaddir . $mainid . "_" . $formdata['category_id'] . '_invoice.jpg';
                        $newq4 = $uploaddir . $mainid . "_" . $formdata['category_id'] . '_invoice.jpeg';
                        if (file_exists($oldq1)) {
                            rename($oldq1, $newq1);
                            $file_name_array[] = $mainid . "_" . $formdata['category_id'] . '_invoice.pdf';
                        }
                        if (file_exists($oldq2)) {
                            rename($oldq2, $newq2);
                            $file_name_array[] = $mainid . "_" . $formdata['category_id'] . '_invoice.png';
                        }
                        if (file_exists($oldq3)) {
                            rename($oldq3, $newq3);
                            $file_name_array[] = $mainid . "_" . $formdata['category_id'] . '_invoice.jpg';
                        }
                        if (file_exists($oldq4)) {
                            rename($oldq4, $newq4);
                            $file_name_array[] = $mainid . "_" . $formdata['category_id'] . '_invoice.jpeg';
                        }
                        $vm->update_file_names_for_transaction($mainid, $file_name_array);
                    } else {
                        $resp['status'] = true;
                        $oldq1 = $uploaddir . '_quote.pdf';
                        $oldq2 = $uploaddir . '_quote.png';
                        $oldq3 = $uploaddir . '_quote.jpg';
                        $oldq4 = $uploaddir . '_quote.jpeg';
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
                    }
                    $vm->update_file_names_for_transaction($mainid, $file_name_array, $statusid);
                }
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
        $resp['status_msg'] = "Session Timed out";
    }echo json_encode($resp);
}

function get_mnt_history($vehicleid, $type) {
    $mm = new MaintananceManager($_SESSION['customerno']);
    $data = $mm->get_veh_mnt_history($vehicleid, $type);
    return $data;
}

function getpartsby_maintenanceid($main_id) {
    $PartManager = new PartManager($_SESSION['customerno']);
    $parts = $PartManager->getpartsby_maintenanceid($main_id);
    return $parts;
}

function gettaskby_maintenanceid($main_id) {
    $TaskManager = new TaskManager($_SESSION['customerno']);
    $tasks = $TaskManager->gettaskby_maintenanceid($main_id);
    return $tasks;
}

function get_mnt_accident_history($vehicleid) {
    $mm = new MaintananceManager($_SESSION['customerno']);
    $data = $mm->get_accident_history($vehicleid);
    return $data;
}

function get_tyre_names($tyre_arr, $tyre) {
    $tcsv = '';
    if ($tyre) {
        $t_arr = explode(',', $tyre);
        $f_arr = array();
        foreach ($t_arr as $t) {
            $f_arr[] = ri($tyre_arr[$t]);
        }
        $tcsv = implode(', ', $f_arr);
    }
    return $tcsv;
}

function get_parts_name($parts, $thispart) {
    $pcsv = '';
    if ($parts) {
        $p_arr = array_filter(explode(',', $thispart));
        if (!empty($p_arr)) {
            $f_arr = array();
            foreach ($p_arr as $p) {
                if (ri($parts[$p]->part_name) != '') {
                    $f_arr[] = ri($parts[$p]->part_name);
                }
            }
            $pcsv = implode(', ', $f_arr);
        }
    }
    return $pcsv;
}

function get_task_name($tasks, $thistask) {
    $tcsv = '';
    if ($tasks) {
        $t_arr = array_filter(explode(',', $thistask));
        if (!empty($t_arr)) {
            $f_arr = array();
            foreach ($t_arr as $t) {
                if (ri($tasks[$t]->task_name) != '') {
                    $f_arr[] = ri($tasks[$t]->task_name);
                }
            }
            $tcsv = implode(', ', $f_arr);
        }
    }
    return $tcsv;
}

function send_transactionmail($creator, $mainid) {
    $mm = new MaintananceManager($_SESSION['customerno']);
    $vm = new VehicleManager($_SESSION['customerno']);
    $status = $mm->getTransactionSatus($mainid);
    $type = $mm->gettransaction_type_formail($mainid); // added for subject
    //$printdata = $mm->get_approval_form_by_vehicleid_formail($mainid);
    $printdata = $mm->get_approval_form_by_vehicleid_formail_getdata($mainid);
    
    foreach ($creator as $user) {
        $message = '';
        $headers = '';
        $message .= '<html>';
        $message .= "<head>
                        <style type='text/css'>
                            body{
                                font-family:Arial;
                                font-size: 11pt;
                            }
                            table{
                                text-align: left;
                                border-collapse:collapse;
                                font-family:Arial;
                                font-size: 10pt;
                                width: 60%;
                            }
                            th{
                                text-align: center;
                                font-size: 11pt;
                                padding: 10px;
                            }
                        </style>
                    </head>
                    <body>";
        $message .= 'Dear ' . $user->realname . ' ,<br /><br />';
        $message .= 'Greetings from Elixia Tech!<br/>';
        $message .= 'Please find the transaction details below:<br/><br/>';
        $message .= '' . $printdata . '';
        $message .= "<br/><br/>"
                . "In case of any queries, you may contact us at 25137470 / 71. "
                . "Thank you for choosing Elixia Tech!";
        $message .= " </body></html>";
        //echo $message; die();
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


function send_transactionmail_htmltemplate($creator, $mainid) {
    $mm = new MaintananceManager($_SESSION['customerno']);
    $vm = new VehicleManager($_SESSION['customerno']);
    $status = $mm->getTransactionSatus($mainid);
    $type = $mm->gettransaction_type_formail($mainid); // added for subject
    $printdata = $mm->get_approval_form_by_vehicleid_formail($mainid);
    $transData = $mm->get_approval_form_by_vehicleid_formail_getdata($mainid);
    foreach ($creator as $user){
        $message = '';
        $headers = '';
        $html="";
       foreach($transData as $row){
                $vehicleid = $row["vehicleid"];
                $category = $row["category"];
                $url = "../../customer/" . $_SESSION['customerno'] . "/vehicleid/" . $row['vehicleid'] . "/";
                if ($row['category'] == '0') {
                    $cat = "Battery";
                }
                if ($row['category'] == 1) {
                    $cat = "Tyre";
                }
                if ($row['category'] == 2) {
                    $cat = "Repair";
                }
                if ($row['category'] == 3) {
                    $cat = "Service";
                }
                if ($row['category'] == 5) {
                    $cat = "Accessory";
                }
                
                 if ($row['category'] == '0') {
                     $transno ="B00" . $row['transid'];
                } elseif ($row['category'] == '1') {
                    $transno = "T00" . $row['transid'];
                } elseif ($row['category'] == '2') {
                    $transno ="R00" . $row['transid'];
                } elseif ($row['category'] == '3') {
                    $transno = "S00" . $row['transid'];
                } elseif ($row['category'] == '5') {
                    $transno = "A00" . $row['transid'];
                }
                $catch_arr = $mm->maintenance_percent($row['vehicleid'], $row['amount_quote'], $row["transid"], null);
                $placehoders = array();
                if($_SESSION['customerno']=="64"){
                       $submission_date = date("d-m-Y H:i A", strtotime($row['submission_date']));
                       $html = file_get_contents('../emailtemplates/maintenance_customer_64.html');
                        $placehoders['{{REQUEST_TYPE}}'] = $cat;
                        $placehoders['{{TRANSID}}'] = $transno;
                        $placehoders['{{CREATORNAME}}'] = $user->realname;
                        $placehoders['{{GROUPNAME}}'] = $row['groupname'];
                        $placehoders['{{DELEGATE_FOR}}'] = "";
                        $placehoders['{{REQUEST_EMP_CODE}}'] = "";
                        $placehoders['{{BRANCH_ADDRESS}}'] = "";
                        $placehoders['{{DEPTNAME}}'] = "";
                        $placehoders['{{USER_COMMENT}}'] = $row['approval_notes'];
                        $placehoders['{{REQUESTDATE}}'] = $submission_date;
                        $placehoders['{{APPROVER_NAME}}'] = "";
                        $placehoders['{{SECOND_APPROVER}}'] = "";
                        $placehoders['{{APPROVALPENDING}}'] = "";
                        $placehoders['{{USERMOBILENO}}'] = $user->phone;
                        $placehoders['{{DELEGATE_FOR_NAME}}'] = "";
                        $placehoders['{{STATUS}}'] = $row['statusname'];
                        $placehoders['{{ACTION}}'] = "";

                        foreach ($placehoders as $key => $val) {
                            $html = str_replace($key, $val, $html);
                        }
                        $message .= $html;
                    }else{
                        //default template    
                        $html = file_get_contents('../emailtemplates/maintenance_customer_default.html');
                        $placehoders['{{CREATORNAME}}'] = $user->realname;
                        $placehoders['{{PRINTDATA}}'] = $printdata;
                        foreach ($placehoders as $key => $val) {
                            $html = str_replace($key, $val, $html);
                        }
                        $message .= $html;
                    }
                }
                //echo $message; die();
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


function send_accidentmail($creator, $mainid) {
    $mm = new MaintananceManager($_SESSION['customerno']);
    $vm = new VehicleManager($_SESSION['customerno']);
    $status = $mm->getAccidentSatus($mainid);
    $printdata = $mm->get_acc_approval_form_by_vehicle_id_formail($mainid);
    foreach ($creator as $user) {
        $message = '';
        $headers = '';
        $message .= '<html>';
        $message .= "<head>
                        <style type='text/css'>
                            body{
                                font-family:Arial;
                                font-size: 11pt;
                            }
                            table{
                                text-align: left;
                                border-collapse:collapse;
                                font-family:Arial;
                                font-size: 10pt;
                                width: 60%;
                            }
                            th{
                                text-align: center;
                                font-size: 11pt;
                                padding: 10px;
                            }
                        </style>
                    </head>
                    <body>";
        $message .= 'Dear ' . $user->realname . ' ,<br>';
        $message .= '<p></p></br>';
        $message .= 'Greetings from Elixia Tech!<br/>';
        $message .= 'Please find the accident details below:<br/>';

        $message .= '' . $printdata . '';

        $message .= "<br /><br />"
                . "In case of any queries, you may contact us at 25137470 / 71. "
                . "Thank you for choosing Elixia Tech!";
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
        //$vm->sendEmailToTable($from, $to, 'test');
    }
}

function getcurrentdate() {
    $currentdate = strtotime(date("Y-m-d H:i:s"));
    $currentdate = substr($currentdate, '0', 11);
    return $currentdate;
}

//
function IsTrigon() {
    if ($_SESSION['use_maintenance'] == 1 && $_SESSION['customerno'] == 118) {
        return TRUE;
    }
}

function IsAuthTrigonDealer() {
    $masterRoleId = 18;
    $stateRoleId = 19;
    $zoneRoleId = 20;
    $regionRoleId = 21;
    $groupRoleId = 22;
    $accountRoleId = 42;
    $usersroleids = array($masterRoleId, $stateRoleId, "5", "6", "1");
    if ($_SESSION['use_maintenance'] == 1 && $_SESSION['customerno'] == 118 && (in_array($_SESSION["roleid"], $usersroleids))) {
        return TRUE;
    }
}

function gettransactionpdf($maintenanceid, $vehicleid) {
    //$history = getmaintananceHistory($maintenanceid);
    $details_json = getbattbyid($maintenanceid);
    $parts = getpartslist($maintenanceid);
    $tasks = gettasklist($maintenanceid);
    $details = $details_json;
    // print_r($details);
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
    if ($details['categoryid'] != 5) {
        $finalreport.=" <tr>
                    <td style='width:300px;height:auto;'><b>Quotation Approval Note</b></td>
                    <td style='width:300px;height:auto;'>" . $details['approval_notes'] . "</td>
                    </tr>";
    }
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
                    <td style='width:225px;height:auto;'><b>Accessory</b></td>
                    <td style='width:125px;height:auto;'><b>Cost</b></td>
                    <td style='width:125px;height:auto;'><b>Max. Perm. Amount</b></td>
                    </tr>";
        $accdata = getaccessories_approval($details['mainid']);
        if (isset($accdata)) {
            foreach ($accdata as $thisacc) {
                $finalreport.=" <tr>
                            <td style='width:50px;height:auto;'>$thisacc->count</td>
                            <td style='width:225px;height:auto;'>$thisacc->name</td>
                            <td style='width:125px;height:auto;'>$thisacc->cost</td>
                            <td style='width:125px;height:auto;'>$thisacc->max_amount</td>
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

    if ($details['categoryid'] == '2' || $details['categoryid'] == '3') {
        if (!empty($parts)) {
            $finalreport.="<table id='search_table_2' align='center' style='width: auto; font-size:13px; text-align:center;border-collapse:collapse; border:1px solid #000;'>
                                   <tr style='background-color:#CCCCCC;'><td colspan='4' style='width:600px;height:auto;'><b>Parts Consumed</b></td></tr>
                                  <tr>
                                      <td><b>Part</b></td>
                                      <td><b>Quantity</b></td>
                                      <td><b>Cost Per Unit</b></td>
                                      <td><b>Total</b></td>
                                  </tr>";

            foreach ($parts as $part) {
                if ($part->part_name != '') {

                    $finalreport.= "<tr>
                                              <td> $part->part_name</td>
                                              <td> $part->qty</td>
                                              <td>$part->amount</td>
                                              <td>$part->total</td>
                                          </tr>";
                }
            }

            $finalreport.='</table><br/>';
        }
        if (!empty($tasks)) {
            $finalreport.="<table id='search_table_2' align='center' style='width: auto; font-size:13px; text-align:center;border-collapse:collapse; border:1px solid #000;'>
                                   <tr style='background-color:#CCCCCC;'><td colspan='4' style='width:600px;height:auto;'><b>Tasks Performed</b></td></tr>
                                  <tr>
                                      <td><b>Tasks</b></td>
                                      <td><b>Quantity</b></td>
                                      <td><b>Cost Per Unit</b></td>
                                      <td><b>Total</b></td>
                                  </tr>";

            foreach ($tasks as $task) {
                if ($task->part_name != '') {

                    $finalreport.= "<tr>
                                              <td> $task->part_name</td>
                                              <td> $task->qty</td>
                                              <td>$task->amount</td>
                                              <td>$task->total</td>
                                          </tr>";
                }
            }

            $finalreport.='</table><br/>';
        }
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
                    <td style='width:300px;height:auto;'><b>Tax Amount</b></td>
                    <td style='width:300px;height:auto;'>" . $details['tax'] . "</td>
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
    $details = $details_json;
    $finalreport = '<div align="center" style="text-align: center; height:30px;">
                        <h3 style="text-transform:uppercase;">Accident Claim Details - (' . $details['transid'] . ')</h3>
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
                    <td style='width:300px;height:auto;'><b>Driver Name</b></td>
                    <td style='width:300px;height:auto;'>" . $details['drivername'] . "</td>
                    </tr>";
    $finalreport.=" <tr>
                    <td style='width:300px;height:auto;'><b>Driver License Validity From</b></td>
                    <td style='width:300px;height:auto;'>" . $details['val_from_Date'] . "</td>
                    </tr>";
    $finalreport.=" <tr>
                    <td style='width:300px;height:auto;'><b>Driver License Validity To</b></td>
                    <td style='width:300px;height:auto;'>" . $details['val_to_Date'] . "</td>
                    </tr>";
    $finalreport.=" <tr>
                    <td style='width:300px;height:auto;'><b>Type of License</b></td>
                    <td style='width:300px;height:auto;'>" . $details['licence_type'] . "</td>
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
                    <td style='width:300px;height:auto;'>" . $details['transid'] . "</td>
                    </tr>";
    $finalreport.=" <tr>
                    <td style='width:300px;height:auto;'><b>Transaction Approval Date</b></td>
                    <td style='width:300px;height:auto;'>" . $details['approval_date'] . "</td>
                    </tr>";
    $finalreport.=" <tr>
                    <td style='width:300px;height:auto;'><b>Category</b></td>
                    <td style='width:300px;height:auto;'>Accident</td>
                    </tr>";
    $finalreport.=" <tr>
                    <td style='width:300px;height:auto;'><b>Accident Date</b></td>
                    <td style='width:300px;height:auto;'>" . $details['acc_Date'] . "</td>
                    </tr>";
    $finalreport.=" <tr>
                    <td style='width:300px;height:auto;'><b>Accident Time</b></td>
                    <td style='width:300px;height:auto;'>" . $details['STime'] . "</td>
                    </tr>";
    $finalreport.=" <tr>
                    <td style='width:300px;height:auto;'><b>Accident Location</b></td>
                    <td style='width:300px;height:auto;'>" . $details['acc_location'] . "</td>
                    </tr>";
    $finalreport.=" <tr>
                    <td style='width:300px;height:auto;'><b>Third Party Injury / Property Damage</b></td>
                    <td style='width:300px;height:auto;'>" . $details['tpi'] . "</td>
                    </tr>";
    $finalreport.=" <tr>
                    <td style='width:300px;height:auto;'><b>Accident Description</b></td>
                    <td style='width:300px;height:auto;'>" . $details['acc_desc'] . "</td>
                    </tr>";
    $finalreport.=" <tr>
                    <td style='width:300px;height:auto;'><b>Name and Location of Workshop</b></td>
                    <td style='width:300px;height:auto;'>" . $details['add_workshop'] . "</td>
                    </tr>";
    $finalreport.=" <tr>
                    <td style='width:300px;height:auto;'><b>Report Sent to</b></td>
                    <td style='width:300px;height:auto;'>" . $details['send_report'] . "</td>
                    </tr>";
    $finalreport.=" <tr>
                    <td style='width:300px;height:auto;'><b>Estimated Loss Amount (INR)</b></td>
                    <td style='width:300px;height:auto;'>" . $details['loss_amount'] . "</td>
                    </tr>";
    $finalreport.=" <tr>
                    <td style='width:300px;height:auto;'><b>Settlement Amount (INR)</b></td>
                    <td style='width:300px;height:auto;'>" . $details['sett_amount'] . "</td>
                    </tr>";
    $finalreport.=" <tr>
                    <td style='width:300px;height:auto;'><b>Actual Repair Amount (INR)</b></td>
                    <td style='width:300px;height:auto;'>" . $details['actual_amount'] . "</td>
                    </tr>";
    $finalreport.=" <tr>
                    <td style='width:300px;height:auto;'><b>Amount Spent by " . $_SESSION['customercompany'] . " (INR)</b></td>
                    <td style='width:300px;height:auto;'>" . $details['mahindra_amount'] . "</td>
                    </tr>";
    if ($_SESSION["use_hierarchy"] == '1') {
        $finalreport.=" <tr>
                    <td style='width:300px;height:auto;'><b>OFAS Number</b></td>
                    <td style='width:300px;height:auto;'>" . $details['ofasnumber'] . "</td>
                    </tr>";
    } else {
        $finalreport.=" <tr>
                    <td style='width:300px;height:auto;'><b>Transaction Reference Number</b></td>
                    <td style='width:300px;height:auto;'>" . $details['ofasnumber'] . "</td>
                    </tr>";
    }
    $finalreport.=" <tr>
                    <td style='width:300px;height:auto;'><b>Approval Note</b></td>
                    <td style='width:300px;height:auto;'>" . $details['approval_notes'] . "</td>
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

function createVehiclehistory($vehicleid) {

    $finalreport = '';
    $inside = false;
    $hist = get_mnt_history($vehicleid, 0);
    if ($hist) {
        $finalreport .="<table id='search_table_2' align='center' style='width:90%; font-size:13px; text-align:center;border-collapse:collapse; border:1px solid #000;'>";
        $finalreport .="
        <tr style='background-color:#CCCCCC; height:45px;' ><td colspan='100%' style='height:40px;'>Battery history</td></tr>
        <tr style='background-color:#CCCCCC;'>
        <td>#</td>
        <td>Transaction ID</td>
        <td>Meter Reading</td>
        <td>Dealer Name</td>
        <td>Notes</td>
        <td>Battery Sr No</td>
        <td>Invoice No.</td>
        <td>Invoice Amt</td>
        <td>Invoice Date</td>
        <td>Quotation amount</td>";
        $finalreport .="<td>Modified Date</td><td>Status</td>";
        $finalreport .="</tr>";

        $i = 1;
        foreach ($hist as $record) {
            $mr = date('d-M-Y H:i', strtotime($record->mdate));
            $finalreport .="<tr><td style='height:30px;'>$i</td><td>{$record->transid}</td><td>{$record->meter_reading}</td><td>{$record->dname}</td><td>{$record->notes}</td><td>{$record->battery_srno}</td>";
            $finalreport .="<td>{$record->invno}</td><td>{$record->invamt}</td><td>{$record->invdate}</td><td>{$record->amount_quote}</td><td>$mr</td><td>{$record->msname}</td>";
            $finalreport .="</tr>";
            $i++;
        }
        $finalreport .="</table>";
        $inside = true;
    }

    $hist = get_mnt_history($vehicleid, 1);
    if ($hist) {
        $finalreport .="<table id='search_table_2' align='center' style='width:80%; font-size:11px; text-align:center;border-collapse:collapse; border:1px solid #000;'>";
        $finalreport .="<tr style='background-color:#CCCCCC;'><td colspan='100%' style='height:40px;'>Tyre history</td></tr>
        <tr style='background-color:#CCCCCC;'>
        <td>#</td>
        <td>Transaction ID</td>
        <td>Meter Reading</td>
        <td>Dealer Name</td>
        <td>Notes</td>
        <td>Invoice No.</td>
        <td>Invoice Amt</td>
        <td>Invoice Date</td>
        <td>Quotation amount</td>";
        $finalreport .="<td>Modified Date</td><td>Status</td>";
        $finalreport .="<td>Tyre Type</td>";
        $finalreport .="<td>Tyre Serial No.</td>";
        $finalreport .="</tr>";

        $i = 1;
        foreach ($hist as $record) {
            $mr = date('d-M-Y H:i', strtotime($record->mdate));
            $finalreport .="<tr><td style='height:30px;'>$i</td><td>{$record->transid}</td><td>{$record->meter_reading}</td><td>{$record->dname}</td><td>{$record->notes}</td>";
            $finalreport .="<td>{$record->invno}</td><td>{$record->invamt}</td><td>{$record->invdate}</td><td>{$record->amount_quote}</td><td>$mr</td><td>{$record->msname}</td>";
            $finalreport .="<td style='text-align:left;width:5%;>{$record->repairtype}</td>";
            $finalreport .="<td style='text-align:left;width:15%;'>{$record->tyre}</td>";
            $finalreport .="</tr>";
            $i++;
        }
        $finalreport .="</table>";
        $inside = true;
    }


    $hist = get_mnt_history($vehicleid, 2);
    if ($hist) {

        $finalreport .="<br/><table id='search_table_2' align='center' style='width:50%; font-size:11px; text-align:center;border-collapse:collapse; border:1px solid #000;'>";
        $finalreport .="<tr style='background-color:#CCCCCC;'><td colspan='100%' style='height:40px;'>Repair/Service History</td></tr>
        <tr><td colspan='100%' style='height:20px;font-weight:bold;'>Lables : U - Unit Price , Q - Quantity , T - Total Amount</td></tr>
        <tr style='background-color:#CCCCCC;'>
        <td>#</td>
        <td>Transaction ID</td>
        <td>Meter Reading</td>
        <td>Dealer Name</td>
        <td>Notes</td>
        <td>Invoice No.</td>
        <td>Invoice Amt</td>
        <td>Invoice Date</td>
        <td>Quotation Amt</td>";
        $finalreport .="<td>Modified Date</td><td>Status</td>";
        $finalreport .="<td>Parts</td><td>Tasks</td>";
        $finalreport .="</tr>";

        $i = 1;
        foreach ($hist as $record) {
            $record_parts = getpartsby_maintenanceid($record->mid);
            $record_tasks = gettaskby_maintenanceid($record->mid);
            //var_dump($record_tasks);
            $mr = date('d-M-Y H:i', strtotime($record->mdate));
            $finalreport .="<tr><td>$i</td><td>{$record->transid}</td><td>{$record->meter_reading}</td><td>{$record->dname}</td><td style='width:20%;'>{$record->notes}</td>";
            $finalreport .="<td>{$record->invno}</td><td>{$record->invamt}</td><td>{$record->invdate}</td><td>{$record->amount_quote}</td><td>$mr</td><td>{$record->msname}</td>";
            if (!empty($record_parts)) {
                $finalreport .="<td style='text-align:left;'>";
                $j = 1;
                foreach ($record_parts as $parts) {
                    $finalreport .= $j . ") ";
                    $finalreport .= wordwrap($parts, Location_Wrap, "<br>\n");
                    $finalreport .="<br />";
                    $j++;
                }
                $finalreport .="</td>";
            } else {
                $finalreport .="<td> </td>";
            }
            if (!empty($record_tasks)) {
                $finalreport .="<td style='text-align:left;'>";
                $k = 1;
                foreach ($record_tasks as $tasks) {
                    $finalreport .= $k . ") ";
                    $finalreport .= wordwrap($tasks, Location_Wrap, "<br>\n");
                    $finalreport .="<br />";
                    $k++;
                }
                $finalreport .="</td>";
            } else {
                $finalreport .="<td> </td>";
            }

            $finalreport .="</tr>";
            $i++;
        }
        $finalreport .="</table>";
        $inside = true;
    }


    $hist = get_mnt_history($vehicleid, 5);

    if ($hist) {
        $finalreport .="<br/><table id='search_table_2' align='center' style='width: 90%; font-size:13px; text-align:center;border-collapse:collapse; border:1px solid #000;'>";
        $finalreport .="<tr style='background-color:#CCCCCC;'><td colspan='100%' style='height:40px;'>Accesories History</td></tr>
        <tr style='background-color:#CCCCCC;'>
        <td>#</td>
        <td>Transaction ID</td>
        <td>Meter Reading</td>
        <td>Dealer Name</td>
        <td>Notes</td>
        <td>Invoice No.</td>
        <td>Invoice Amt</td>
        <td>Invoice Date</td>
        <td>Quotation amount</td>";
        $finalreport .="<td>Modified Date</td><td>Status</td>";
        $finalreport .="<td>Accessories</td>";
        $finalreport .="</tr>";

        $i = 1;
        foreach ($hist as $record) {
            $mr = date('d-M-Y H:i', strtotime($record->mdate));
            $finalreport .="<tr><td style='height:30px;'>$i</td><td>{$record->transid}</td><td>{$record->meter_reading}</td><td>{$record->dname}</td><td>{$record->notes}</td>";
            $finalreport .="<td>{$record->invno}</td><td>{$record->invamt}</td><td>{$record->invdate}</td><td>{$record->amount_quote}</td><td>$mr</td><td>{$record->msname}</td>";
            $finalreport .="<td>{$record->access}</td>";
            $finalreport .="</tr>";
            $i++;
        }
        $finalreport .="</table>";
        $inside = true;
    }


    $hist = get_mnt_accident_history($vehicleid);
    if ($hist) { //Accident claim history
        $finalreport .="<br/><table id='search_table_2' align='center' style='width: 1000px; font-size:13px; text-align:center;border-collapse:collapse; border:1px solid #000;'>";
        $finalreport .="<tr style='background-color:#CCCCCC;'><td colspan='100%' style='height:40px;'>Accident Claim History</td></tr>
        <tr style='background-color:#CCCCCC;'>
        <td style='height:40px; width:50px;'>#</td>
        <td style='height:40px; width:70px;'>Transaction ID</td>
        <td style='width:100px;'>Accident Date</td>
        <td style='width:100px;'>Location</td>
        <td style='width:100px;'>Injury/Damage</td>
        <td style='width:100px;'>Accident Description</td>";
        $finalreport .="<td style='width:80px;'>Driver Name</td>
        <td style='width:80px;'>License Validity</td>
        <td style='width:50px;'>License Type</td>
        <td style='width:50px;'>Workshop Location</td>";
        $finalreport .="<td style='width:50px;'>Loss amount</td><td style='width:50px;'>Settlement Amount</td><td style='width:50px;'>Repair Amount</td><td style='width:50px;'>Amount Spent</td>";
        $finalreport .="</tr>5";

        $i = 1;
        foreach ($hist as $record) {
            $adate = date('d-M-Y H:i', strtotime($record->accident_datetime));
            $lv = date('d-M-Y', strtotime($record->lvfrom)) . ' to ' . date('d-M-Y', strtotime($record->lvto));
            $finalreport .="<tr><td style='height:30px;'>$i</td><td>{$record->transid}</td><td>$adate</td><td>{$record->accident_location}</td><td>{$record->tpi_pd}</td><td>{$record->description}</td>";
            $finalreport .="<td>{$record->drivername}</td><td>$lv</td>";
            $finalreport .="<td>{$record->licence_type}</td>";
            $finalreport .="<td>{$record->workshop_location}</td>";
            $finalreport .="<td>{$record->loss_amount}</td>";
            $finalreport .="<td>{$record->sett_amount}</td>";
            $finalreport .="<td>{$record->actual_amount}</td>";
            $finalreport .="<td>{$record->mahindra_amount}</td>";
            $finalreport .="</tr>";
            $i++;
        }
        $finalreport .="</table>";
        $inside = true;
    }

    $hist = getfilteredfuels('', $vehicleid, null);
    if ($hist) { //Fuel history
        $finalreport .="<br/><table id='search_table_2' align='center' style='width: 1000px; font-size:13px; text-align:center;border-collapse:collapse; border:1px solid #000;'>";
        $finalreport .="<tr style='background-color:#CCCCCC;'><td colspan='100%' style='height:40px;'>Fuel History</td></tr>
        <tr style='background-color:#CCCCCC;'>
        <td style='height:40px; width:50px;'>#</td>
        <td style='height:40px; width:70px;'>Transaction ID</td>
        <td style='width:100px;'>Date & Time</td>
        <td style='width:50px;'>Fuel (In Lt.)</td>
        <td style='width:100px;'>Amount</td>
        <td style='width:50px;'>Rate</td>";
        $finalreport .="<td style='width:100px;'>Ref.No</td><td style='width:100px;'>Opening Km</td><td style='width:100px;'>Ending Km</td><td style='width:50px;'>Average</td><td style='width:100px;'>Vendor</td>";
        $finalreport .="</tr>";

        $i = 1;
        foreach ($hist as $record) {
            $finalreport .="<tr><td style='height:30px;'>$i</td><td>{$record->trans}</td><td>" . date('d-M-Y H:i', strtotime($record->submit_datetime)) . "</td><td>{$record->fuel}</td><td>{$record->amount}</td><td>{$record->rate}</td>";
            $finalreport .="<td>{$record->invno}</td>";
            $finalreport .="<td>{$record->openingkm}</td>";
            $finalreport .="<td>{$record->endingkm}</td>";
            $finalreport .="<td>{$record->average}</td>";
            $finalreport .="<td>{$record->dname}</td>";
            $finalreport .="</tr>";
            $i++;
        }
        $finalreport .="</table>";
        $inside = true;
    }
    return $finalreport;
}

function getvehiclehistorypdf($vehicleid) {

    //0=battery history, 1=tyre history, 2=repair/service history, 5=Accesories history

    $vno = get_vehicle_details_by_id($vehicleid);
    $pdfdata = '';
    $datareport = '';
    $title = "Transaction History";
    $subTitle = array("Vehicle No: $vno->vehicleno");

    $datareport = pdf_header($title, $subTitle);

    $pdfdata = createVehiclehistory($vehicleid);
    $datareport.=$pdfdata;
    if ($pdfdata == "") {
        $datareport .="<h2>No Data found</h2>";
    }
    $datareport .="
	<page_footer>
                    [[page_cu]]/[[page_nb]]
    </page_footer>";
    $datareport .= "<hr style='margin-top:5px;'>";
    $formatdate1 = date(speedConstants::DEFAULT_DATETIME);
    $datareport .= "<div align='right' style='text-align:center;'> PDF Generated On: $formatdate1 </div>";

    echo $datareport;
}

function getvehiclehistoryxls($vehicleid) {

    $vehno = get_vehicle_details_by_id($vehicleid);
    $xlsdata = '';
    $xlsdatareport = '';
    $title = "Transaction History";
    $subTitle = array("Vehicle No: $vehno->vehicleno");

    $xlsdatareport = excel_header($title, $subTitle);

    $xlsdata = createVehiclehistory($vehicleid);
    $xlsdatareport.=$xlsdata;
    if ($xlsdata == "") {
        $xlsdatareport .="<h2>No Data found</h2>";
    }
    echo $xlsdatareport;
}

function getTyretype_byvehicle($vehicleid) {
    $vm = new VehicleManager($_SESSION['customerno']);
    $ttype = $vm->getTyretype_byvehicle($vehicleid);
    return $ttype;
}

function searchForId($id, $array, $array_col) {
    $vm = new VehicleManager($_SESSION['customerno']);
    $values = $vm->searchForId($id, $array, $array_col);
    return $values;
}

function getTyreRepairType() {
    $vm = new VehicleManager($_SESSION['customerno']);
    $rtype = $vm->getTyreRepair();
    return $rtype;
}

function getTyreTypedata($vehicleid) {
    $vm = new VehicleManager($_SESSION['customerno']);
    $values = $vm->gettyreTypedata($vehicleid);
    return $values;
}

function getVehicleName($vehicleid) {
    $vm = new VehicleManager($_SESSION['customerno']);
    $vno = $vm->getVehicleName($vehicleid);
    return $vno;
}

function getbatteryno_byvehicle($vehicleid) {
    $vm = new VehicleManager($_SESSION['customerno']);
    $bsrno = $vm->getbatteryno_byvehicle($vehicleid);
    return $bsrno;
}

function doMultipleClosed($data){
    $action = $data['action'];
    $approvedata = $data['approvaldata'];
    $note = $data['note'];
    $slipno = $data['ofasnumber'];
    $chequeno = $data['chequeno'];
    $chequeamt = $data['chequeamt'];
    $chequedate = $data['chequedate'];
    $tdsamt = $data['tdsamt'];


    $approvelist = explode(",", $approvedata);
    $i=0;
    $listarr = array();
    foreach ($approvelist as $approve) {
        $listarr[] = explode("-", $approve);
    }
    foreach ($listarr as $key => $val) {
            $approve_obj = new stdClass();
            $approve_obj->mainid = $val[0];
            $approve_obj->statusid = $val[1];
            $approve_obj->transaction_type = $val[2];
            if ($approve_obj->transaction_type == "FUEL"){
                pushclosed_fuel($approve_obj->mainid, $note, $slipno,$chequeno,$chequeamt,$chequedate,$tdsamt);
            } else{
                pushclosed($approve_obj->mainid, $approve_obj->statusid, $note, $slipno,$chequeno,$chequeamt,$chequedate,$tdsamt);
            }
        }
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

function pushstatus($main_id, $status, $note){
    $maintanace_obj = new MaintananceManager($_SESSION['customerno']);
    $vm = new VehicleManager($_SESSION['customerno']);
    $maintanaces = $maintanace_obj->set_transaction_status($main_id, $status, $note);
    if (isset($maintanaces) == 'ok') {

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

function pushclosed($main_id, $status, $note, $slipno = null,$chequeno=null,$chequeamt=null,$chequedate=null,$tdsamt=null) {
    $maintanace_obj = new MaintananceManager($_SESSION['customerno']);
    $vm = new VehicleManager($_SESSION['customerno']);
    $maintanaces = $maintanace_obj->set_pushclose($main_id, $status, $note, $slipno,$chequeno,$chequeamt,$chequedate,$tdsamt);
}

function pushclosed_fuel($mainid, $note, $slipno = null,$chequeno=null,$chequeamt=null,$chequedate=null,$tdsamt=null) {
    $maintanace_obj = new MaintananceManager($_SESSION['customerno']);
    $vm = new VehicleManager($_SESSION['customerno']);
    $maintanaces = $maintanace_obj->set_pushclose_fuel($mainid, $note, $slipno,$chequeno,$chequeamt,$chequedate,$tdsamt);
}

function getPartsDetails($partid){
    $partsobj = new PartManager($_SESSION['customerno']);
    $partsdetails = $partsobj->get_part($partid);
    return $partsdetails;
}

function gettaskDetails($taskid){
    $tasksobj = new TaskManager($_SESSION['customerno']);
    $taskdetails = $tasksobj->get_task($taskid);
    return $taskdetails;
}

function edittyrepopup($data){
    $maintanancemanager = new MaintananceManager($_SESSION['customerno']);
    $maintanancemanager->edittyrepopup($data,$_SESSION['userid']);
    return true;
}

function editbatterypop($data){
    $maintanancemanager = new MaintananceManager($_SESSION['customerno']);
    $maintanancemanager->editbatterypopup($data);
    return true;
}

function deletepartspopup($partid){
    $maintanancemanager = new MaintananceManager($_SESSION['customerno']);
    $maintanancemanager->DeletePartspopup($partid);
}


function deletetaskspopup($taskid){
    $maintanancemanager = new MaintananceManager($_SESSION['customerno']);
    $maintanancemanager->DeleteTaskpopup($taskid);
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
function getpart() {
    $PartManager = new PartManager($_SESSION['customerno']);
    $parts = $PartManager->get_all_part();
    return $parts;
}

function updateinvamt_get($data){
    $maintanancemanager = new MaintananceManager($_SESSION['customerno']);
    $result = $maintanancemanager->updateinvamt_get($data);
    return $result;
}

function getBehalfMembersZoneMasters($roleid,$userid,$role){
    $maintanancemanager = new MaintananceManager($_SESSION['customerno']);
    $result = $maintanancemanager->getBehalfMembersZoneMasters($roleid,$userid,$role);
    return $result;
}


function getBehalfMembersRegionalManager($roleid,$userid,$role){
    $maintanancemanager = new MaintananceManager($_SESSION['customerno']);
    $result = $maintanancemanager->getBehalfMembersRegionalManager($roleid,$userid,$role);
    return $result;
}


function getRegionalManagerList($zoneuserid){
    $maintanancemanager = new MaintananceManager($_SESSION['customerno']);
    $result = $maintanancemanager->getRegionalManagerList($zoneuserid);
    return $result;
}

function getBranchManagerList($regionalid){
    $maintanancemanager = new MaintananceManager($_SESSION['customerno']);
    $result = $maintanancemanager->getBranchManagerList($regionalid);
    return $result;
}

?>
