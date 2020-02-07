<?php
if (!isset($RELATIVE_PATH_DOTS) || trim($RELATIVE_PATH_DOTS) == "") {
   $RELATIVE_PATH_DOTS = "../../";
}
include_once 'transaction_functions.php';


if (isset($_POST['vehicleno']) && !isset($_POST['vehicleid'])) {
    checkvehiclename($_POST['vehicleno']);
} else if (isset($_POST['d']) && !isset($_POST['ds'])) {
    uniteligibility($_POST["d"]);
} else if (isset($_POST['d']) && isset($_POST['ds'])) {
    mapvehicle($_POST['d'], $_POST['ds']);
} else if (isset($_POST['ds'])) {
    demap($_POST['ds']);
} else if (isset($_GET['work']) && $_GET['work'] == "transaction") {
    add_transaction($_REQUEST, $_FILES);
} else if (isset($_POST['work']) && $_POST['work'] == "cancelled_transaction") {
    $result = cancel_transaction($_REQUEST);
    echo $result;
}else if (isset($_POST['work']) && $_POST['work'] == "rollback_transaction"){
    $result = rollback_transaction($_REQUEST);
    echo $result;
}else if (isset($_POST['accessory_id'])) {
    $acc = get_amount_from_accessory($_POST['accessory_id']);
    echo $acc->max_amount;
} else if (isset($_POST['accident_vehicleid']) && !isset($_POST['accident_vehicleid_history'])) {
    $status = addaccident_approval($_POST);
    echo $status;
} else if (isset($_POST['acc_maintenance_id'])) {
    $status = get_accident_details($_POST['acc_maintenance_id']);
    echo $status;
} else if (isset($_POST['editacc_maintainanceid'])) {
    $status = editacc($_POST);
    echo $status;
} else if (isset($_POST['accident_vehicleid_history'])) {
    $status = addaccident_history($_POST);
    echo $status;
} else if (isset($_POST['dealer_vehicle_id'])) {
    $dealer = pulldealer($_POST);
    echo $dealer;
} else if (isset($_POST['vehicleno']) && isset($_POST['vehicleid'])) {
    $vehicleid = GetSafeValueString($_POST['vehicleid'], "string");
    $vehicleno = GetSafeValueString($_POST['vehicleno'], "string");
    editvehicle($vehicleid, $vehicleno);
} else if (isset($_POST['vehicleid']) && isset($_POST['drivername']) && isset($_POST['driverno'])) {
    $vehicleid = GetSafeValueString($_POST['vehicleid'], "string");
    $drivername = GetSafeValueString($_POST['drivername'], "string");
    $driverno = GetSafeValueString($_POST['driverno'], "string");
    editdriver($vehicleid, $drivername, $driverno);
} else if (isset($_POST['makeid'])) {
    $makeid = GetSafeValueString($_POST['makeid'], "string");
    $model_data = getmodel($makeid);
    echo $model_data;
} else if (isset($_POST['vehicle_no'])) {
    $vehicleid = addvehicle($_POST);
    echo $vehicleid;
} else if (isset($_POST['edit_vehicle_no'])) {
    $vehicleid = edit_vehicle($_POST);
    echo $vehicleid;
} else if (isset($_POST['branchdata'])) {
    $branchid = GetSafeValueString($_POST['branchdata'], "string");
    $branch_data = getbranch($branchid);
    echo $branch_data;
} else if (isset($_POST['dealerdata'])) {
    $dealerid = GetSafeValueString($_POST['dealerdata'], "string");
    $dealer_data = getdealer($dealerid);
    echo $dealer_data->code;
} else if (isset($_POST['engineno']) && isset($_POST['vehicleid'])) {
    $status = adddescription($_POST);
    echo $status;
} else if (isset($_POST['edit_engineno']) && isset($_POST['edit_vehicle_id'])) {
    $status = editdescription($_POST);
    echo $status;
} else if (isset($_POST['tax_type'])) {
    $status = addtax($_POST);
    echo $status;
} else if (isset($_POST['edit_tax_type'])) {
    $status = edittaxbyid($_POST);
    echo $status;
} else if (isset($_POST['tax_vehicle_id'])) {
    $status = gettax($_POST['tax_vehicle_id'], $_POST['edit_veh_readonly_tax']);
    echo $status;
} else if (isset($_POST['notes_vehicle_id'])) {
    $status = getnotes($_POST['notes_vehicle_id']);
    echo $status;
} else if (isset($_POST['tax_vehicle_id_view'])) {
    $status = gettax_view($_POST['tax_vehicle_id_view']);
    echo $status;
} else if (isset($_POST['tax_id'])) {
    $status = gettaxbyid($_POST['tax_id']);
    echo $status;
} else if (isset($_POST['delete_tax_id'])) {
    $status = deletetaxbyid($_POST['delete_tax_id'], $_POST['tax_vehicleid']);
    echo $status;
} else if (isset($_POST['delete_maintenanceid'])) {
    $status = deletemaintenancebyid($_POST['delete_maintenanceid'], $_POST['delete_vehicleid']);
    echo $status;
} else if (isset($_POST['delete_accidentid'])) {
    $status = deleteaccbyid($_POST['delete_accidentid']);
    echo $status;
} else if (isset($_POST['insurance_company'])) {
    $status = addinsurance($_POST);
    echo $status;
} else if (isset($_POST['edit_insurance_company'])) {
    $status = editinsurance($_POST);
    echo $status;
} else if (isset($_POST['cap_cost'])) {
    $status = addcapitalization($_POST);
    echo $status;
} else if (isset($_POST['edit_cap_cost'])) {
    $status = editcapitalization($_POST);
    echo $status;
} else if (isset($_POST['geo_vehicleid'])) {
    $checkpoints = array();
    $fences = array();
    foreach ($_POST as $single_post_name => $single_post_value) {
        if (substr($single_post_name, 0, 14) == "to_checkpoint_")
            $checkpoints[] = substr($single_post_name, 14, 25);
    }
    foreach ($_POST as $single_post_name => $single_post_value) {
        if (substr($single_post_name, 0, 9) == "to_fence_")
            $fences[] = substr($single_post_name, 9, 25);
    }
    $status = addgeotag($_POST['geo_vehicleid'], $checkpoints, $fences);
    echo $status;
}
else if (isset($_POST['editgeo_vehicleid'])){
    $checkpoints = array();
    $fences = array();
    foreach ($_POST as $single_post_name => $single_post_value) {
        if (substr($single_post_name, 0, 14) == "to_checkpoint_")
            $checkpoints[] = substr($single_post_name, 14, 25);
    }
    foreach ($_POST as $single_post_name => $single_post_value) {
        if (substr($single_post_name, 0, 9) == "to_fence_")
            $fences[] = substr($single_post_name, 9, 25);
    }
    $status = editgeotag($_POST['editgeo_vehicleid'], $checkpoints, $fences);
    echo $status;
}
else if (isset($_POST['new_batt_vehicleid'])) {
    $status = addbattery($_POST);
    echo $status;
} else if (isset($_POST['edit_batt_vehicleid'])){
    $status = editbattery($_POST, $_POST['status_maintenance']);
    echo $status;
} else if (isset($_POST['battery_maintenanceid'])) {
    $status = getbattbyid($_POST['battery_maintenanceid']);
    echo $status;
} else if (isset($_POST['tyre_maintenanceid'])) {
    $status = gettyrebyid($_POST['tyre_maintenanceid']);
    echo $status;
} else if (isset($_POST['repair_maintenanceid'])) {
    $status = getrepairbyid($_POST['repair_maintenanceid']);
    echo $status;
} else if (isset($_POST['acc_maintenanceid'])) {
    $status = get_accident_details($_POST['acc_maintenanceid']);
    echo $status;
} else if (isset($_POST['new_tyre_vehicleid'])) {
    $status = addtyre($_POST);
    echo $status;
} else if (isset($_POST['new_repair_vehicleid'])) {
    $status = addrepair($_POST);
    echo $status;
} else if (isset($_POST['edit_tyre_vehicleid'])) {
    $status = edittyre($_POST);
    echo $status;
} else if (isset($_POST['edit_repair_vehicleid'])) {
    $status = editrepair($_POST);
    echo $status;
} else if (isset($_POST['battery_vehicleid'])) {
    $status = addbattery_approval($_POST);
    echo $status;
} else if (isset($_POST['batt_vehicle_id'])) {
    $status = get_batt_hist($_POST);
    echo $status;
} else if (isset($_POST['insurance_vehicle_id'])) {
    $status = get_insurance_data($_POST);
    echo $status;
} else if (isset($_POST['tyre_vehicle_id'])) {
    $status = get_tyre_hist($_POST);
    echo $status;
} else if (isset($_POST['repair_vehicle_id'])) {
    $status = get_repair_hist($_POST);
    echo $status;
} else if (isset($_POST['accident_hist_vehicle_id'])) {
    $status = get_accident_hist($_POST);
    echo $status;
} else if (isset($_POST['batt_vehicle_id_view'])) {
    $status = get_batt_hist_view($_POST);
    echo $status;
} else if (isset($_POST['tyre_vehicle_id_view'])) {
    $status = get_tyre_hist_view($_POST);
    echo $status;
} else if (isset($_POST['repair_vehicle_id_view'])) {
    $status = get_repair_hist_view($_POST);
    echo $status;
} else if (isset($_POST['accident_vehicle_id_view'])) {
    $status = get_accident_hist_view($_POST);
    echo $status;
} else if (isset($_POST['approval_vehicle_id'])) {
    $status = send_approval($_POST['approval_vehicle_id']);
    echo $status;
} else if (isset($_POST['nation_id'])) {
    $state_data = get_statelist($_POST['nation_id']);
    echo $state_data;
} else if (isset($_POST['state_id'])) {
    $district_data = get_districtlist($_POST['state_id']);
    echo $district_data;
} else if (isset($_POST['district_id'])) {
    $city_data = get_citylist($_POST['district_id']);
    echo $city_data;
} else if (isset($_POST['city_id'])) {
    $branch_data = get_branchlist($_POST['city_id']);
    echo $branch_data;
} else if (isset($_POST['other_submit'])) {
    $status = uploadfilename($_POST['other_vehicleid'], $_POST['other']);
    echo $status;
} else if (isset($_POST['other_submit1'])) {
    $status = uploadfilename1($_POST['other_vehicleid'], $_POST['other1']);
    echo $status;
} else if (isset($_POST['pushfuel'])) {
    $status = addFuel($_POST);
    echo $status;
    //print_r($_POST);
} else if (isset($_POST['name']) && !isset($_POST['dealerid'])) {
    $name = GetSafeValueString($_POST['name'], "string");
    $phoneno = GetSafeValueString($_POST['phoneno'], "string");
    $cellphone = GetSafeValueString($_POST['cellphone'], "string");
    $notes = GetSafeValueString($_POST['notes'], "string");
    $address = GetSafeValueString($_POST['address'], "string");
    $code = GetSafeValueString($_POST['code'], "string");
    $other1 = GetSafeValueString($_POST['other1'], "string");
    $other2 = GetSafeValueString($_POST['other2'], "string");

    $vendor = '';
    if ($_POST['battery'] == 'Battery') {
        $vendor .= '1';
    } else {
        $vendor .= '0';
    }
    if ($_POST['tyre'] == 'Tyre') {
        $vendor .= '1';
    } else {
        $vendor .= '0';
    }
    if ($_POST['service'] == 'Service') {
        $vendor .= '1';
    } else {
        $vendor .= '0';
    }
    if ($_POST['repair'] == 'Repair') {
        $vendor .= '1';
    } else {
        $vendor .= '0';
    }
    if ($_POST['vehicle'] == 'Vehicle') {
        $vendor .= '1';
    } else {
        $vendor .= '0';
    }
    if ($_POST['accessory'] == 'Accessory') {
        $vendor .= '1';
    } else {
        $vendor .= '0';
    }
    if ($_POST['fuel'] == 'Fuel') {
        $vendor .= '1';
    } else {
        $vendor .= '0';
    }
    $response = adddealer($name, $phoneno, $cellphone, $notes, $address, $vendor, $code, $other1, $other2);
    return $response;
} elseif (isset($_REQUEST['action']) && $_REQUEST['action'] == 'editfuel') {

    $vm = new VehicleManager($_SESSION['customerno']);
    $status = $vm->edit_fuel_transaction($_REQUEST);
    echo $status;
} elseif (isset($_REQUEST['action']) && $_REQUEST['action'] == 'addpart') {
    $data = array();
    include_once '../../lib/bo/PartManager.php';

    $partname = exit_issetor($_REQUEST['partname'], json_encode(array(0, 'Part name not found')));
    $customerno = exit_issetor($_SESSION['customerno'], 'Customerno not found');
    $userid = exit_issetor($_SESSION['userid'], 'Userid not found');

    $partmanager = new PartManager($customerno);
    if ($partmanager->partname_exists($partname)){
        echo json_encode(array(0, "Part-name already exists"));
        exit;
    }else{
        $pid = $partmanager->add_part($_POST, $userid);
        echo json_encode(array(1, "Part added Successfully", $pid));
        exit;
    }
} elseif (isset($_REQUEST['action']) && $_REQUEST['action'] == 'addtask') {

    include_once '../../lib/bo/TaskManager.php';

    $taskname = exit_issetor($_REQUEST['taskname'], json_encode(array(0, 'Task name not found')));
    $customerno = exit_issetor($_SESSION['customerno'], 'Customerno not found');
    $userid = exit_issetor($_SESSION['userid'], 'Userid not found');

    $taskmanager = new TaskManager($customerno);
    if ($taskmanager->taskname_exists($taskname)) {
        echo json_encode(array(0, "Task-name already exists"));
        exit;
    } else {
        $taskid = $taskmanager->add_task($_POST, $userid);
        echo json_encode(array(1, "Task added Successfully", $taskid));
        exit;
    }
} elseif (isset($_REQUEST['delfuelid'])) {
    $delid = $_REQUEST['delfuelid'];
    $vm = new VehicleManager($_SESSION['customerno']);
    $status = $vm->delete_fuel_transaction($delid);
    if ($status == 'ok') {
        header("location:transaction.php?id=2");
    }
} elseif (isset($_REQUEST['delmainid'])) {
    $delmainid = $_REQUEST['delmainid'];
    $vm = new VehicleManager($_SESSION['customerno']);
    $status = $vm->delete_maintenance_transaction($delmainid);
    if ($status == 'ok') {
        header("location:transaction.php?id=2");
    }
} elseif (isset($_REQUEST['delaccid'])) {
    $delaccid = $_REQUEST['delaccid'];
    $vm = new VehicleManager($_SESSION['customerno']);
    $status = $vm->delete_acc($delaccid);
    if ($status == 'ok') {
        header("location:transaction.php?id=2");
    }
}else if (isset($_REQUEST['work']) && $_REQUEST['work'] == 'viewtransaction') {

    if (isset($_REQUEST['filter']) && $_REQUEST['filter'] == 1) {
        $result_filter = array();
        $transid = GetSafeValueString($_POST["transactionid"], "string");
        $vehicleid = GetSafeValueString($_POST["vehicleid"], "string");
        $categoryid = GetSafeValueString($_POST["category"], "string");
        $statusid = GetSafeValueString($_POST["statusid"], "string");
        $tyre = GetSafeValueString($_POST['tyre_type'], "string");
        $parts = GetSafeValueString($_POST['parts_select'], "string");
        $dealerid = GetSafeValueString($_POST['trans_dealer'], "string");
        if (($categoryid != '4' && $categoryid != '6') || $categoryid == '-1') {
            $maintances = getfilteredmaintanances($transid, $vehicleid, $categoryid, $statusid, $tyre, $parts, $dealerid);
            if (is_array($maintances)) {
                $result_filter = array_merge($result_filter, $maintances);
            }
        }
        if (($categoryid == '4' || $categoryid == '-1') && $dealerid == '-1') {
            $dealerclmn = false;
            $accidents = getfilteredaccidents($transid, $vehicleid, $statusid);
            if (is_array($accidents)){
                $result_filter = array_merge($result_filter, $accidents);
            }
        }
        if (($categoryid == '6' || $categoryid == '-1') && $statusid == '') {
            $fuels = getfilteredfuels($transid, $vehicleid, $dealerid);
            if (is_array($fuels)) {
                $result_filter = array_merge($result_filter, $fuels);
            }
        }
    } else {
        $result = array();
        $maintances = getallmaintanances();
        $accidents = getallaccidents();
        $fuels = getallfuels();
        if (isset($maintances)) {
            $result = array_merge($result, $maintances);
        }
        if (isset($accidents)) {
            $result = array_merge($result, $accidents);
        }
        if (isset($fuels)) {
            $result = array_merge($result, $fuels);
        }
    }
    $result_json = isset($result_filter) ? $result_filter : $result;
    echo json_encode($result_json);
}else if (isset($_POST['work']) && $_POST['work'] == 'multiple_approval'){
    doMultipleClosed($_POST);
}else if (isset($_POST['work']) && $_POST['work'] == 'getpartsDetail'){
    $partid = $_POST['partid'];
    $result = getPartsDetails($partid);
    echo json_encode($result);
}else if (isset($_POST['work']) && $_POST['work'] == 'gettaskDetail'){
    $taskid = $_POST['taskid'];
    $result = gettaskDetails($taskid);
    echo json_encode($result);
}else if(isset($_POST['work']) && $_POST['work']=="getregionalmanagerlist"){
    $zoneuserid = $_POST['zoneuserid'];
    $result = getRegionalManagerList($zoneuserid);
    $regionalhtml="";
    if(isset($result)&& !empty($result)){
      $regionalhtml .=" <input type='radio' name='behalfradiobtn' value='2'>Regional Users<br><select name='regionalmaster' id='regionalmaster' onchange='getbranch()'>";
      if(isset($result)){
          $regionalhtml .="<option value='0'>Select Regional Manager</option>";
      foreach($result as $row){
          $userid = $row['userid'];
          $username = $row['username'];
          $regionalhtml .="<option value='".$userid."'>".$username."</option>";
      }  
    }
     $regionalhtml .="</select>";
    }
    echo $regionalhtml;
}else if(isset($_POST['work']) && $_POST['work']=="getbranchmanagerlist"){
    $regionalid = $_POST['regionalid'];
    $result = getBranchManagerList($regionalid);
    $branchhtml="";
    if(isset($result)&& !empty($result)){
      $branchhtml .="<input type='radio' name='behalfradiobtn' value='3'>Branch Users<br><select name='branchmaster' id='branchmaster'>";
      if(isset($result)){
          $branchhtml .="<option value='0'>Select Branch Manager</option>";
      foreach($result as $row){
          $userid = $row['userid'];
          $username = $row['username'];
          $branchhtml .="<option value='".$userid."'>".$username."</option>";
      }  
    }
     $branchhtml .="</select>";
    }
    echo $branchhtml;
}
//else if(isset($_FILES['edit_puc']))
//{
//    $allowedExts = array("gif", "jpeg", "jpg", "png");
//    $temp = explode(".", $_FILES["edit_puc"]["name"]);
//    $extension = end($temp);
//
//    if ((($_FILES["file"]["type"] == "image/gif")
//    || ($_FILES["file"]["type"] == "image/jpeg")
//    || ($_FILES["file"]["type"] == "image/jpg")
//    || ($_FILES["file"]["type"] == "image/pjpeg")
//    || ($_FILES["file"]["type"] == "image/x-png")
//    || ($_FILES["file"]["type"] == "image/png"))
//    && ($_FILES["file"]["size"] < 20000)
//    && in_array($extension, $allowedExts)) {
//      if ($_FILES["file"]["error"] > 0) {
//        echo "Return Code: " . $_FILES["file"]["error"] . "<br>";
//      } else {
//        echo "Upload: " . $_FILES["file"]["name"] . "<br>";
//        echo "Type: " . $_FILES["file"]["type"] . "<br>";
//        echo "Size: " . ($_FILES["file"]["size"] / 1024) . " kB<br>";
//        echo "Temp file: " . $_FILES["file"]["tmp_name"] . "<br>";
//        if (file_exists("upload/" . $_FILES["file"]["name"])) {
//          echo $_FILES["file"]["name"] . " already exists. ";
//        } else {
//          move_uploaded_file($_FILES["file"]["tmp_name"],
//          "upload/" . $_FILES["file"]["name"]);
//          echo "Stored in: " . "upload/" . $_FILES["file"]["name"];
//        }
//      }
//    } else {
//      echo "Invalid file";
//    }
//    $upload_file = upload_files($_POST['district_id']);
//    echo $upload_file;
//}
?>