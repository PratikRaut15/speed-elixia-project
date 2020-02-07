<?php
include_once 'vehicle_functions.php';

if((isset($_POST['approval_vehicle_id'])) && isset($_POST['action'])=='send_approval')
{
    $status = send_approval($_POST['approval_vehicle_id'],$_REQUEST);
    echo $status;
}
else if(isset ($_POST['vehicleno']) && !isset($_POST['vehicleid']))
{
    checkvehiclename($_POST['vehicleno']);
}
else if(isset($_POST['d']) && !isset ($_POST['ds']))
{
    uniteligibility($_POST["d"]);
}
else if(isset ($_POST['d']) && isset ($_POST['ds']))
{
    mapvehicle($_POST['d'], $_POST['ds']);
}
else if(isset ($_POST['ds']))
{
    demap($_POST['ds']);
}
else if(isset ($_GET['work']) && $_GET['work']=="transaction")
{
	add_transaction($_REQUEST,$_FILES);
}
else if(isset($_POST['accident_vehicleid']))
{
    $status = addaccident_approval($_POST);
    echo $status;
}
else if(isset($_POST['acc_maintenance_id']))
{
    $status = get_accident_details($_POST['acc_maintenance_id']);
    echo $status;
}
else if(isset($_POST['editacc_maintainanceid']))
{
    $status = editacc($_POST);
    echo $status;
}

else if(isset($_POST['vehicleno']) && isset($_POST['vehicleid']))
{
    $vehicleid = GetSafeValueString($_POST['vehicleid'],"string");
    $vehicleno = GetSafeValueString($_POST['vehicleno'],"string");
    $vehicleno = ltrim($vehicleno);
    $vehicleno = rtrim($vehicleno);
    editvehicle($vehicleid, $vehicleno);
}
else if(isset($_POST['vehicleid']) && isset($_POST['drivername']) && isset($_POST['driverno']))
{
    $vehicleid = GetSafeValueString($_POST['vehicleid'],"string");
    $drivername = GetSafeValueString($_POST['drivername'],"string");
    $driverno = GetSafeValueString($_POST['driverno'],"string");
    editdriver($vehicleid, $drivername, $driverno);
}
else if(isset($_POST['makeid']))
{
    $makeid = GetSafeValueString($_POST['makeid'],"string");
    $model_data = getmodel($makeid);
    echo $model_data;
}
else if(isset($_POST['vehicle_no']))
{
    $vehicleid = addvehicle($_POST);
    echo $vehicleid;
}
else if(isset($_POST['edit_vehicle_no']))
{
    $vehicleid = edit_vehicle($_POST);
    echo $vehicleid;
}
else if(isset($_POST['branchdata']))
{
    $branchid = GetSafeValueString($_POST['branchdata'],"string");
    $branch_data = getbranch($branchid);
    echo $branch_data;
}
else if(isset($_POST['dealerdata']))
{
    $dealerid = GetSafeValueString($_POST['dealerdata'],"string");
    $dealer_data = getdealer($dealerid);
    echo $dealer_data->code;
}
else if(isset($_POST['engineno']) && isset($_POST['vehicleid']))
{
    $status = adddescription($_POST);
    echo $status;
}
else if(isset($_POST['edit_engineno']) && isset($_POST['edit_vehicle_id']))
{
    $status = editdescription($_POST);
    echo $status;
}
else if(isset($_POST['tax_type']))
{
    $status = addtax($_POST);
    echo $status;
}
else if(isset($_POST['edit_tax_type']))
{
    $status = edittaxbyid($_POST);
    echo $status;
}
else if(isset($_POST['tax_vehicle_id']))
{
    $status = gettax($_POST['tax_vehicle_id'],$_POST['edit_veh_readonly_tax']);
    echo $status;
}
else if(isset($_POST['notes_vehicle_id']))
{
    $status = getnotes($_POST['notes_vehicle_id']);
    echo $status;
}
else if(isset($_POST['tax_vehicle_id_view']))
{
    $status = gettax_view($_POST['tax_vehicle_id_view']);
    echo $status;
}
else if(isset($_POST['tax_id']))
{
    $status = gettaxbyid($_POST['tax_id']);
    echo $status;
}
else if(isset($_POST['delete_tax_id']))
{
    $status = deletetaxbyid($_POST['delete_tax_id'],$_POST['tax_vehicleid']);
    echo $status;
}
else if(isset($_POST['delete_maintenanceid']))
{
    $status = deletemaintenancebyid($_POST['delete_maintenanceid'],$_POST['delete_vehicleid']);
    echo $status;
}
else if(isset($_POST['delete_accidentid']))
{
    $status = deleteaccbyid($_POST['delete_accidentid']);
    echo $status;
}
else if(isset($_POST['insurance_company']))
{
    $status = addinsurance($_POST);
    echo $status;
}
else if(isset($_POST['edit_insurance_company']))
{
    $status = editinsurance($_POST);
    echo $status;
}
else if(isset($_POST['cap_cost']))
{
    $status = addcapitalization($_POST);
    echo $status;
}
else if(isset($_POST['edit_cap_cost']))
{
    $status = editcapitalization($_POST);
    echo $status;
}
else if(isset($_POST['geo_vehicleid']))
{
    $checkpoints = array();
    $fences = array();
    foreach($_POST as $single_post_name=>$single_post_value)
    {
        if (substr($single_post_name, 0, 14) == "to_checkpoint_")
            $checkpoints[] = substr($single_post_name, 14, 25);
    }
    foreach($_POST as $single_post_name=>$single_post_value)
    {
        if (substr($single_post_name, 0, 9) == "to_fence_")
            $fences[] = substr($single_post_name, 9, 25);
    }
    $status = addgeotag($_POST['geo_vehicleid'],$checkpoints,$fences);
    echo $status;
}
else if(isset($_POST['editgeo_vehicleid']))
{
    $checkpoints = array();
    $fences = array();
    foreach($_POST as $single_post_name=>$single_post_value)
    {
        if (substr($single_post_name, 0, 14) == "to_checkpoint_")
            $checkpoints[] = substr($single_post_name, 14, 25);
    }
    foreach($_POST as $single_post_name=>$single_post_value)
    {
        if (substr($single_post_name, 0, 9) == "to_fence_")
            $fences[] = substr($single_post_name, 9, 25);
    }
    $status = editgeotag($_POST['editgeo_vehicleid'],$checkpoints,$fences);
    echo $status;
}
else if(isset($_POST['new_batt_vehicleid']))
{
    $status = addbattery($_POST);
    echo $status;
}
else if(isset($_POST['accessory_id']))
{
    $acc = get_amount_from_accessory($_POST['accessory_id']);
    echo $acc->max_amount;
}
else if(isset($_POST['new_accessory_vehicleid']))
{
    $status = addaccessory($_POST);
    echo $status;
}
else if(isset($_POST['edit_batt_vehicleid']))
{
    $status = editbattery($_POST);
    echo $status;
}
else if(isset($_POST['edit_accessory_vehicleid']))
{
    $status = editaccessory($_POST);
    echo $status;
}
else if(isset($_POST['battery_maintenanceid']))
{
    $status = getbattbyid($_POST['battery_maintenanceid']);
    echo $status;
}
else if(isset($_POST['tyre_maintenanceid']))
{
    $status = gettyrebyid($_POST['tyre_maintenanceid']);
    echo $status;
}
else if(isset($_POST['repair_maintenanceid']))
{
    $status = getrepairbyid($_POST['repair_maintenanceid']);
    echo $status;
}
else if(isset($_POST['acc_maintenanceid']))
{
    $status = getaccbyid($_POST['acc_maintenanceid']);
    echo $status;
}
else if(isset($_POST['new_tyre_vehicleid']))
{
    $status = addtyre($_POST);
    echo $status;
}
else if(isset($_POST['new_repair_vehicleid']))
{
    $status = addrepair($_POST);
    echo $status;
}
else if(isset($_POST['edit_tyre_vehicleid']))
{
    $status = edittyre($_POST);
    echo $status;
}
else if(isset($_POST['edit_repair_vehicleid']))
{
    $status = editrepair($_POST);
    echo $status;
}
else if(isset($_POST['battery_vehicleid']))
{
    $status = addbattery_approval($_POST);
    echo $status;
}
else if(isset($_POST['batt_vehicle_id']))
{
    $status = get_batt_hist($_POST);
    echo $status;
}
else if(isset($_POST['insurance_vehicle_id']))
{
    $status = get_insurance_data($_POST);
    echo $status;
}
else if(isset($_POST['tyre_vehicle_id']))
{
    $status = get_tyre_hist($_POST);
    echo $status;
}
else if(isset($_POST['repair_vehicle_id']))
{
    $status = get_repair_hist($_POST);
    echo $status;
}
else if(isset($_POST['accessory_vehicle_id']))
{
    $status = get_accessory_hist($_POST);
    echo $status;
}
else if(isset($_POST['accident_hist_vehicle_id']))
{
    $status = get_accident_hist($_POST);
    echo $status;
}
else if(isset($_POST['batt_vehicle_id_view']))
{
    $status = get_batt_hist_view($_POST);
    echo $status;
}
else if(isset($_POST['tyre_vehicle_id_view']))
{
    $status = get_tyre_hist_view($_POST);
    echo $status;
}
else if(isset($_POST['repair_vehicle_id_view']))
{
    $status = get_repair_hist_view($_POST);
    echo $status;
}
else if(isset($_POST['accident_vehicle_id_view']))
{
    $status = get_accident_hist_view($_POST);
    echo $status;
}
else if(isset($_POST['approval_vehicle_id']))
{
    $status = send_approval($_POST['approval_vehicle_id']);
    echo $status;
}


else if(isset($_POST['nation_id']))
{
    $state_data = get_statelist($_POST['nation_id']);
    echo $state_data;
}
else if(isset($_POST['state_id']))
{
    $district_data = get_districtlist($_POST['state_id']);
    echo $district_data;
}
else if(isset($_POST['district_id']))
{
    $city_data = get_citylist($_POST['district_id']);
    echo $city_data;
}
else if(isset($_POST['city_id']))
{
    $branch_data = get_branchlist($_POST['city_id']);
    echo $branch_data;
}
else if(isset($_POST['other_submit']))
{

    $status = uploadfilename($_POST['other_vehicleid'],$_POST['filename']);
    echo $status;
}
else if(isset($_POST['other_submit1']))
{
    $status = uploadfilename1($_POST['other_vehicleid'],$_POST['filename']);
    echo $status;
}
else if(isset($_POST['other_submit2']))
{
    $status = uploadfilename2($_POST['other_vehicleid'],$_POST['filename']);
    echo $status;
}else if(isset($_POST['other_submit3']))
{
    $status = uploadfilename3($_POST['other_vehicleid'],$_POST['filename']);
    echo $status;
}else if(isset($_POST['other_submit4']))
{
    $status = uploadfilename4($_POST['other_vehicleid'],$_POST['filename']);
    echo $status;
}else if(isset($_POST['other_submit5']))
{
    $status = uploadfilename5($_POST['other_vehicleid'],$_POST['filename']);
    echo $status;
}else if(isset($_POST['puc_submit']))
{
    $status = uploadfilenamepuc($_POST['other_vehicleid'],$_POST['filename']);
    echo $status;
}else if(isset($_POST['reg_submit'])){
    $status = uploadfilenameRegistration($_POST['other_vehicleid'],$_POST['filename']);
    echo $status;
}else if(isset($_POST['insurance_submit'])){
    $status = uploadfilenameInsurance($_POST['other_vehicleid'],$_POST['filename']);
    echo $status;
}
else if(isset($_POST['notes']))
{
    $city_data = updatenotes($_POST['customerno'],$_POST['edit_vehicle_id'],$_POST['notes']);
    echo $status;
}
else if(isset($_POST['upload_vehicle_id']))
{
    $status = getupload($_POST['upload_vehicle_id']);
    echo $status;
}
?>
