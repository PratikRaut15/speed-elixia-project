<?php

include_once '../../lib/bo/GeofenceManager.php';
include_once '../../lib/bo/CheckpointManager.php';
include_once '../../lib/bo/VehicleManager.php';
include_once '../../lib/bo/GroupManager.php';
include_once '../../lib/system/utilities.php';
include_once '../../lib/bo/UnitManager.php';
include_once '../../lib/bo/DealerManager.php';
include_once '../../lib/bo/PartManager.php';
include_once '../../lib/bo/TaskManager.php';
include_once '../../lib/bo/AccessoryManager.php';
include_once "../../lib/comman_function/reports_func.php";
if (!isset($_SESSION)) {
  session_start();
  if (!isset($_SESSION['timezone'])) {
    $_SESSION['timezone'] = 'Asia/Kolkata';
  }
  date_default_timezone_set('' . $_SESSION['timezone'] . '');
}

class VODatacap {

}

function getdate_IST() {
  $ServerDate_IST = strtotime(date("Y-m-d H:i:s"));
  return $ServerDate_IST;
}

function getvehicle($vehicleid) {
  $vehicleid = GetSafeValueString($vehicleid, "string");
  $vehiclemanager = new VehicleManager($_SESSION['customerno']);
  $vehicle = $vehiclemanager->get_genset_vehicle($vehicleid);
  if ($vehicle->isdeleted == '1')
    header("location:genset.php");
  return $vehicle;
}

function getName_ByType($nid) {
  $vehiclemanager = new VehicleManager($_SESSION['customerno']);
  $vehicledata = $vehiclemanager->getNameForTemp($nid);
  return $vehicledata;
}

function getwarehouse($vehicleid) {
  $vehicleid = GetSafeValueString($vehicleid, "string");
  $vehiclemanager = new VehicleManager($_SESSION['customerno']);
  $vehicle = $vehiclemanager->get_warehouse_with($vehicleid);
  if ($vehicle->isdeleted == '1')
    header("location:warehouse.php");

  return $vehicle;
}

function getbatch($vehicleid) {
  $vehicleid = GetSafeValueString($vehicleid, "string");
  $vehiclemanager = new VehicleManager($_SESSION['customerno']);
  $vehicle = $vehiclemanager->get_vehicle_with_batch($vehicleid);
  if ($vehicle->isdeleted == '1')
    header("location:vehicle.php");

  return $vehicle;
}

function getvehicles($kind = '') {
  $vehiclemanager = new VehicleManager($_SESSION['customerno']);
  $vehicles = $vehiclemanager->get_all_vehicles_with_genset($kind);
  return $vehicles;
}

function getvehicles_warehouse() {
  $vehiclemanager = new VehicleManager($_SESSION['customerno']);
  $vehicles = $vehiclemanager->get_all_warehouse();
  return $vehicles;
}

// get vehicle data in excel - ganesh
function getvehicles_xls($kind) {
  $vehiclemanager = new VehicleManager($_SESSION['customerno']);
  $vehicles = $vehiclemanager->get_all_vehicles_xls($kind);
  $vehicledata = "<style>.table, td{border:1px solid #000; }</style>";
  $vehicledata .= "<table id='search_table_2' align='center' style='width: 1000px; font-size:13px; text-align:center;border-collapse:collapse; border:1px solid #000;'>";
  $vehicledata .="<tr style='background-color:#CCCCCC; border:1px solid #000;'>
                        <td>Vehicle No.</td>
                        <td>Status</td>
                        <td>Group Name</td>
                        <td>Owner Name</td>
                        <td>Purchase Date</td>
                        <td>Year of Manufacture</td>
                        <td>Meter Reading</td>
                        <td>Driver Name</td>
                        <td>Driver licenceno.</td>
                        <td>Kind</td>
                        <td>Make</td>
                        <td>Model</td>
                        <td>RTO Location</td>
                        <td>Fuel Type</td>
                        <td>Seat Capacity</td>
                        <td>Capital Cost</td>
                        <td>Capitaldate</td>
                        <td>loan Margin amount</td>
                        <td>Loan amt</td>
                        <td>Loan Financier</td>
                        <td>Loan Emi amt</td>
                        <td>Loan Start Date</td>
                        <td>Loan End Date</td>
                        <td>Insurance value/IDV</td>
                        <td>Insurance premium</td>
                        <td>Insurance NCB</td>
                        <td>Insurance Company Name</td>
                        <td>Insurance start date</td>
                        <td>Insurance end date</td>
                        <td>Insurance notes</td>
                        <td>Insurance claim place</td>
                        <td>Engine no</td>
                        <td>Chasis no</td>
                        <td>Dealer Name</td>
                        <td>Invoice no</td>
                        <td>Invoice date</td>
                        <td>Invoice amt</td>
                        <td>Tax amt</td>
                        <td>Tax from date</td>
                        <td>Tax to date</td>
                        <td>Tax Type</td>
                        <td>Tax Register no</td>
                        <td>Other Upload 1</td>
                        <td>Other Upload 2</td>
                        <td>Other Upload 3</td>
                        <td>PUC File Upload</td>
                        <td>Registration File Upload</td>
                        <td>Insurance File Upload</td>
                        </tr>";
  if (isset($vehicles) && !empty($vehicles)) {

    foreach ($vehicles as $row) {
      $vehicledata .="<tr style=border:1px solid #000;>"
       . "<td>" . $row->vehicleno . "</td>"
       . "<td>" . $row->status_name . "</td>"
       . "<td>" . $row->groupname . "</td>"
       . "<td>" . $row->owner_name . "</td>"
       . "<td>" . $row->purchase_date . "</td>"
       . "<td>" . $row->manufacturing_year . "</td>"
       . "<td>" . $row->meter_reading . "</td>"
       . "<td>" . $row->drivername . "</td>"
       . "<td>" . $row->driverlicno . "</td> "
       . "<td>" . $row->type . "</td>"
       . "<td>" . $row->makename . "</td>"
       . "<td>" . $row->modelname . "</td>"
       . "<td>" . $row->rtolocation . "</td>"
       . "<td>" . $row->fueltype . "</td>"
       . "<td>" . $row->seatcapacity . "</td>"
       . "<td>" . $row->capitalcost . "</td>"
       . "<td>" . $row->capitaldate . "</td>"
       . "<td>" . $row->loan_marginamt . "</td>"
       . "<td>" . $row->loan_loanamt . "</td>"
       . "<td>" . $row->loan_financier . "</td>"
       . "<td>" . $row->loan_emiamt . "</td>"
       . "<td>" . $row->loan_start_date . "</td>"
       . "<td>" . $row->loan_end_date . "</td>"
       . "<td>" . $row->insurance_value . "</td>"
       . "<td>" . $row->insurance_premium . "</td>"
       . "<td>" . $row->insurance_ncb . "</td>"
       . "<td>" . $row->insurance_coname . "</td>"
       . "<td>" . $row->insurance_start_date . "</td>"
       . "<td>" . $row->insurance_end_date . "</td>"
       . "<td>" . $row->insurance_notes . "</td>"
       . "<td>" . $row->insurance_claim_place . "</td>"
       . "<td>" . $row->engineno . "</td>"
       . "<td>" . $row->chasisno . "</td>"
       . "<td>" . $row->dealername . "</td>"
       . "<td>" . $row->invoiceno . "</td>"
       . "<td>" . $row->invoicedate . "</td>"
       . "<td>" . $row->invoiceamt . "</td>"
       . "<td>" . $row->tax_amt . "</td>"
       . "<td>" . $row->tax_from_date . "</td>"
       . "<td>" . $row->tax_to_date . "</td>"
       . "<td>" . $row->tax_type . "</td>"
       . "<td>" . $row->tax_reg_no . "</td>"
       . "<td>" . $row->u1 . "</td>"
       . "<td>" . $row->u2 . "</td>"
       . "<td>" . $row->u3 . "</td>"
       . "<td>" . $row->puc . "</td>"
       . "<td>" . $row->reg . "</td>"
       . "<td>" . $row->ins . "</td>"
       . "</tr>";
    }
  } else {
    $vehicledata .="<tr><td colspan='100%'><h2>No Data<h2></td></tr>";
  }
  echo $vehicledata .="</table>";
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

function getbattbyid($maintenanceid) {
  $vehiclemanager = new VehicleManager($_SESSION['customerno']);
  $battery = $vehiclemanager->getbattery_id($maintenanceid);
  return $battery;
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
  if ($_SESSION['use_hierarchy'] == '1') {
    $groups = $groupmanager->get_all_groups();
  } else {
    $groups = $groupmanager->getallgroups();
  }
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

function getworkmaster() {
  $groupmanager = new GroupManager($_SESSION['customerno']);
  $groups = $groupmanager->get_work_master();
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

function delwarehouse($vehicleid) {
  $vehicleid = GetSafeValueString($vehicleid, "string");
  $vehiclemanager = new VehicleManager($_SESSION['customerno']);
  $vehiclemanager->delwarehouse($vehicleid, $_SESSION['userid']);
}

function insertvehicle($vehicleno, $type, $checkpoints, $fences, $groupid, $overspeed_limit, $average, $fuelcapacity, $min_temp1 = 0, $max_temp1 = 0, $min_temp2 = 0, $max_temp2 = 0, $min_temp3 = 0, $max_temp3 = 0, $min_temp4 = 0, $max_temp4 = 0, $batch = Null, $work_key = Null, $stime = NUll, $sdate = Null, $dummybatch = Null, $sel_master = Null) {
  $vehicleno = GetSafeValueString($vehicleno, "string");
  $vehicleno = ltrim($vehicleno);
  $vehicleno = rtrim($vehicleno);
  $groupid = GetSafeValueString($groupid, "int");
  $type = GetSafeValueString($type, "string");
  $overspeed_limit = GetSafeValueString($overspeed_limit, "int");
  $vehiclemanager = new VehicleManager($_SESSION['customerno']);
  $vehiclemanager->add_vehicle($vehicleno, $type, $checkpoints, $fences, $_SESSION['userid'], $groupid, $overspeed_limit, $average, $fuelcapacity, $min_temp1, $max_temp1, $min_temp2, $max_temp2, $min_temp3, $max_temp3, $min_temp4, $max_temp4, $batch, $work_key, $stime, $sdate, $dummybatch, $sel_master);
}

function insertwarehouse($vehicleno, $type, $groupid, $overspeed_limit, $average, $fuelcapacity, $min_temp1 = 0, $max_temp1 = 0, $min_temp2 = 0, $max_temp2 = 0, $batch = Null, $work_key = Null, $stime = NUll, $sdate = Null, $dummybatch = Null, $sel_master = Null) {
  $vehicleno = GetSafeValueString($vehicleno, "string");
  $groupid = GetSafeValueString($groupid, "int");
  $type = GetSafeValueString($type, "string");
  $overspeed_limit = GetSafeValueString($overspeed_limit, "int");
  $vehiclemanager = new VehicleManager($_SESSION['customerno']);
  $vehiclemanager->add_warehouse($vehicleno, $type, $_SESSION['userid'], $groupid, $overspeed_limit, $average, $fuelcapacity, $min_temp1, $max_temp1, $min_temp2, $max_temp2, $batch, $work_key, $stime, $sdate, $dummybatch, $sel_master);
}

function modifyvehicle($vehicledetails) {
  $vehiclemanager = new VehicleManager($_SESSION['customerno']);
  $vehiclemanager->updateGenset($vehicledetails);
}
function gensetMappingLog($vehicledetails) {
  $vehiclemanager = new VehicleManager($_SESSION['customerno']);
  $vehiclemanager->insertGensetLog($vehicledetails);
}

function modifywarehouse($vehicleno, $vehicleid, $type, $groupid, $overspeed_limit, $average, $fuelcapacity, $min_temp1 = 0, $max_temp1 = 0, $min_temp2 = 0, $max_temp2 = 0, $batch = Null, $work_key = Null, $stime = NUll, $sdate = Null, $dummybatch = Null, $sel_master = Null) {
  $vehicleno = GetSafeValueString($vehicleno, "string");
  $groupid = GetSafeValueString($groupid, "int");
  $type = GetSafeValueString($type, "string");
  $overspeed_limit = GetSafeValueString($overspeed_limit, "int");
  $vehicleid = GetSafeValueString($vehicleid, "string");
  $vehiclemanager = new VehicleManager($_SESSION['customerno']);
  $vehiclemanager->modwarehouse($vehicleno, $vehicleid, $type, $_SESSION['userid'], $groupid, $overspeed_limit, $average, $fuelcapacity, $min_temp1, $max_temp1, $min_temp2, $max_temp2, $batch, $work_key, $stime, $sdate, $dummybatch, $sel_master);
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
  $vehicleid = $vm->editvehicle($form, $_SESSION['userid']);
  return $vehicleid;
}

function getgeneral($vehicleid) {
  $vm = new VehicleManager($_SESSION['customerno']);
  $general = $vm->get_general($vehicleid);
  return $general;
}

function getgeneralPDF($vehicleid) {
  $vm = new VehicleManager($_SESSION['customerno']);
  $general = $vm->get_generalPDF($vehicleid);
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

function getloan($vehicleid) {
  $vm = new VehicleManager($_SESSION['customerno']);
  $loan = $vm->get_loan($vehicleid);
  return $loan;
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
  if (isset($status)) {
    $approver = $vm->get_vehicle_approver($vehicleid);
    $creator = $vm->get_vehicle_creator($vehicleid);
    $general = $vm->get_general_formail($vehicleid);
    if (!empty($creator)) {
      //send_creationmail($creator, $general);
    }
    if (!empty($approver)) {
      send_creationmail($approver, $general);
    }
  }
  return $status;
}

function send_creationmail($creator, $general) {
  $vm = new VehicleManager($_SESSION['customerno']);
  foreach ($creator as $user) {
    $message = '';
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
    $message .= 'Please find the vehicle details below:<br/>';

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
                        </tbody>
                    </table>';
    $message .= "<br /><br />"
     . "In case of any queries, you may contact us at 25137470 / 71. "
     . "Thank you for choosing Elixia Tech!";
    $message .= "</body></html>";
    $to = $user->email;

    $subject = $general->status_name . ' For Vehicle No. ' . $general->vehicleno;
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
    //$vm->sendEmailToTable($from, $to, $general->vehicleno);
  }
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

function get_uploaded_filename($vehicleid) {
  $vehiclemanager = new VehicleManager($_SESSION['customerno']);
  $vehicle = $vehiclemanager->get_filename($vehicleid);
  return $vehicle;
}

function updatenotes($custno, $editvehicleid, $notes) {
  $vehiclemanager = new VehicleManager($_SESSION['customerno']);
  $vehicle = $vehiclemanager->update_notes($custno, $editvehicleid, $notes);
  return $status;
}

function getdevicehist_sqlite($location) {
  $path = "sqlite:$location";
  $db = new PDO($path);
  $queues = array();
  $query = "SELECT devicelat,devicelong,lastupdated,inbatt,status,ignition,powercut,tamper,gpsfixed,`online/offline`,gsmstrength,gsmregister,gprsregister,swv,hwv from devicehistory ORDER by lastupdated DESC";
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
      $Datacap->swv = $row['swv'];
      $Datacap->hwv = $row['hwv'];
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

function getvehiclepdf($customerno, $vehicleid) {
  $general = getgeneralPDF($vehicleid);

  //print_r($general);
  $finalreport = '<div style="width:auto; height:30px;">
                        <table style="width: auto; border:none;">
                        <tr>
                        <td style="width:430px; border:none;"><img style="width:50%; height: 50%;" src="../../images/elixiaspeed_logo.png" /></td>
                        <td style="width:420px; border:none;">

                                                <h3 style="text-transform:uppercase;">Vehicle Details</h3><br />
                        </td>
                        <td style="width:230px;border:none;">
                        <img src="../../images/elixia_logo_75.png"  /></td>
                        </tr>
                        </table>

                        </div>';
  $finalreport .= "<hr />
        <h4>
        <div align='center' style='text-align:center;'>
        Vehicle No. $general->vehicleno</div><div align='right' style='text-align:center;' >
        </div>
        </h4>
        <style type='text/css'>
        table, td { border: solid 1px  #999999; color:#000000; }
        hr.style-six { border: 0; height: 0; border-top: 1px solid rgba(0, 0, 0, 0.1); border-bottom: 1px solid rgba(255, 255, 255, 0.3); }
        </style>
        <tbody>";

  // Details here
  // General Details
  $finalreport.= "<table id='search_table_2' align='center' style='width: auto; font-size:13px; text-align:center;border-collapse:collapse; border:1px solid #000;'>";
  $finalreport.= "<tr style='background-color:#CCCCCC;'>
                     <td colspan='2' style='width:600px;height:auto;'>
                        <b>General Details</b>
                     </td>
                    </tr>";
  $finalreport.=" <tr>
                    <td style='width:300px;height:auto;'><b>Kind</b></td>
                    <td style='width:300px;height:auto;'>$general->kind</td>
                    </tr>";
  $finalreport.=" <tr>
                    <td style='width:300px;height:auto;'><b>Make</b></td>
                    <td style='width:300px;height:auto;'>$general->make</td>
                    </tr>";
  $finalreport.=" <tr>
                    <td style='width:300px;height:auto;'><b>Model</b></td>
                    <td style='width:300px;height:auto;'>$general->model</td>
                    </tr>";
  $finalreport.=" <tr>
                    <td style='width:300px;height:auto;'><b>Year of Manufacture</b></td>
                    <td style='width:300px;height:auto;'>$general->manufacturing_year</td>
                    </tr>";
  $finalreport.=" <tr>
                    <td style='width:300px;height:auto;'><b>Purchase Date</b></td>
                    <td style='width:300px;height:auto;'>" . date('d-m-Y', strtotime($general->purchase_date)) . "</td>
                    </tr>";
  $finalreport.="</table><br/>";

  // Branch Details
  $finalreport.= "<table id='search_table_2' align='center' style='width: auto; font-size:13px; text-align:center;border-collapse:collapse; border:1px solid #000;'>";
  $finalreport.= "<tr style='background-color:#CCCCCC;'>
                     <td colspan='2' style='width:600px;height:auto;'>
                        <b>Branch Details</b>
                     </td>
                    </tr>";
  $finalreport.=" <tr>
                    <td style='width:300px;height:auto;'><b>Branch Name</b></td>
                    <td style='width:300px;height:auto;'>$general->groupname</td>
                    </tr>";
  $finalreport.=" <tr>
                    <td style='width:300px;height:auto;'><b>Region</b></td>
                    <td style='width:300px;height:auto;'>$general->cname</td>
                    </tr>";
  $finalreport.=" <tr>
                    <td style='width:300px;height:auto;'><b>Code</b></td>
                    <td style='width:300px;height:auto;'>$general->gcode</td>
                    </tr>";
  $finalreport.=" <tr>
                    <td style='width:300px;height:auto;'><b>Address</b></td>
                    <td style='width:300px;height:auto;'>$general->caddress</td>
                    </tr>";
  $finalreport.="</table><br/>";

  // Misccellaneous
  $finalreport.= "<table id='search_table_2' align='center' style='width: auto; font-size:13px; text-align:center;border-collapse:collapse; border:1px solid #000;'>";
  $finalreport.= "<tr style='background-color:#CCCCCC;'>
                     <td colspan='2' style='width:600px;height:auto;'>
                        <b>Miscellaneous</b>
                     </td>
                    </tr>";
  $finalreport.=" <tr>
                    <td style='width:300px;height:auto;'><b>Start Meter Reading</b></td>
                    <td style='width:300px;height:auto;'>$general->start_meter_reading</td>
                    </tr>";
  $finalreport.=" <tr>
                    <td style='width:300px;height:auto;'><b>Overspeed Limit</b></td>
                    <td style='width:300px;height:auto;'>$general->overspeed_limit</td>
                    </tr>";
  $finalreport.=" <tr>
                    <td style='width:300px;height:auto;'><b>Fuel Type</b></td>
                    <td style='width:300px;height:auto;'>$general->fueltype</td>
                    </tr>";
  $finalreport.="</table><br/>";

  // Description
  $finalreport.= "<table id='search_table_2' align='center' style='width: auto; font-size:13px; text-align:center;border-collapse:collapse; border:1px solid #000;'>";
  $finalreport.= "<tr style='background-color:#CCCCCC;'>
                     <td colspan='2' style='width:600px;height:auto;'>
                        <b>Description</b>
                     </td>
                    </tr>";
  $finalreport.=" <tr>
                    <td style='width:300px;height:auto;'><b>Engine No.</b></td>
                    <td style='width:300px;height:auto;'>$general->engineno</td>
                    </tr>";
  $finalreport.=" <tr>
                    <td style='width:300px;height:auto;'><b>Chassis No.</b></td>
                    <td style='width:300px;height:auto;'>$general->chasisno</td>
                    </tr>";
  if ($general->vehicle_purpose == 1) {
    $general->vehicle_purpose = "Employee CTC";
  } else if ($general->vehicle_purpose == 2) {
    $general->vehicle_purpose = "Branch Vehicle";
  } else if ($general->vehicle_purpose == 3) {
    $general->vehicle_purpose = "Zone Vehicle";
  } else if ($general->vehicle_purpose == 4) {
    $general->vehicle_purpose = "Regional Vehicle";
  } else if ($general->vehicle_purpose == 5) {
    $general->vehicle_purpose = "Head Office Vehicle";
  } else {
    $general->vehicle_purpose = "N/A";
  }
  $finalreport.=" <tr>
                    <td style='width:300px;height:auto;'><b>Vehicle Purpose</b></td>
                    <td style='width:300px;height:auto;'>$general->vehicle_purpose</td>
                    </tr>";
  if ($general->vehicle_type == 1) {
    $general->vehicle_type = "New";
  } else if ($general->vehicle_type == 2) {
    $general->vehicle_type = "Repossesed";
  } else if ($general->vehicle_type == 3) {
    $general->vehicle_type = "Employee";
  } else {
    $general->vehicle_type = "N/A";
  }
  $finalreport.=" <tr>
                    <td style='width:300px;height:auto;'><b>Vehicle Type</b></td>
                    <td style='width:300px;height:auto;'>$general->vehicle_type</td>
                    </tr>";
  $finalreport.="</table><br/>";

  // Dealer Details
  $finalreport.= "<table id='search_table_2' align='center' style='width: auto; font-size:13px; text-align:center;border-collapse:collapse; border:1px solid #000;'>";
  $finalreport.= "<tr style='background-color:#CCCCCC;'>
                     <td colspan='2' style='width:600px;height:auto;'>
                        <b>Dealer Details</b>
                     </td>
                    </tr>";
  $finalreport.=" <tr>
                    <td style='width:300px;height:auto;'><b>Dealer Name</b></td>
                    <td style='width:300px;height:auto;'>$general->dname</td>
                    </tr>";
  $finalreport.=" <tr>
                    <td style='width:300px;height:auto;'><b>Code</b></td>
                    <td style='width:300px;height:auto;'>$general->dcode</td>
                    </tr>";
  $finalreport.=" <tr>
                    <td style='width:300px;height:auto;'><b>Invoice No.</b></td>
                    <td style='width:300px;height:auto;'>$general->invoiceno</td>
                    </tr>";
  $finalreport.=" <tr>
                    <td style='width:300px;height:auto;'><b>Invoice Date</b></td>
                    <td style='width:300px;height:auto;'>" . date('d-m-Y', strtotime($general->invoicedate)) . "</td>
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

function setPUCExpiry($customerno, $userid, $vehicleid, $pucexpiry, $pucrem) {
  $vm = new VehicleManager($customerno);
  $status = $vm->setPUCExpiry($customerno, $userid, $vehicleid, $pucexpiry, $pucrem);
  return $status;
}

function setREGExpiry($customerno, $userid, $vehicleid, $regexpiry, $regrem) {
  $vm = new VehicleManager($customerno);
  $status = $vm->setREGExpiry($customerno, $userid, $vehicleid, $regexpiry, $regrem);
  return $status;
}

function setINSExpiry($customerno, $userid, $vehicleid, $insexpiry, $insrem) {
  $vm = new VehicleManager($customerno);
  $status = $vm->setINSExpiry($customerno, $userid, $vehicleid, $insexpiry, $insrem);
  return $status;
}

function setOTH1Expiry($customerno, $userid, $vehicleid, $oth1expiry, $oth1rem) {
  $vm = new VehicleManager($customerno);
  $status = $vm->setOTH1Expiry($customerno, $userid, $vehicleid, $oth1expiry, $oth1rem);
  return $status;
}

function setOTH2Expiry($customerno, $userid, $vehicleid, $oth2expiry, $oth2rem) {
  $vm = new VehicleManager($customerno);
  $status = $vm->setOTH2Expiry($customerno, $userid, $vehicleid, $oth2expiry, $oth2rem);
  return $status;
}

function setOTH3Expiry($customerno, $userid, $vehicleid, $oth3expiry, $oth3rem) {
  $vm = new VehicleManager($customerno);
  $status = $vm->setOTH3Expiry($customerno, $userid, $vehicleid, $oth3expiry, $oth3rem);
  return $status;
}

function getAlert($vehicleid) {
  $vm = new VehicleManager($_SESSION['customerno']);
  $alert = $vm->getVehicleAlert($vehicleid);
  return $alert;
}

function get_tyretype() {
  $vm = new VehicleManager($_SESSION['customerno']);
  $t_type = $vm->get_tyretype();
  return $t_type;
}

function getTyretype_byvehicle($vehid) {
  $vm = new VehicleManager($_SESSION['customerno']);
  $ttype = $vm->getTyretype_byvehicle($vehid);
  return $ttype;
}

function getbatteryno_byvehicle($vehid) {
  $vm = new VehicleManager($_SESSION['customerno']);
  $bsrno = $vm->getbatteryno_byvehicle($vehid);
  return $bsrno;
}

function searchForId($id, $array, $array_col) {
  $vm = new VehicleManager($_SESSION['customerno']);
  $values = $vm->searchForId($id, $array, $array_col);
  return $values;
}

function getupload($vehicleid) {
  $vehiclemanager = new VehicleManager($_SESSION['customerno']);
  $vehicles = $vehiclemanager->getupload($vehicleid);
  return $vehicles;
}

function getvehiclelist_seq() {
  $vehicles = getvehicles();
  return $vehicles;
}

function getTyreTypedata($vehicleid) {
  $vm = new VehicleManager($_SESSION['customerno']);
  $values = $vm->gettyreTypedata($vehicleid);
  return $values;
}

?>
