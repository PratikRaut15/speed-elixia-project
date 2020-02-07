<?php
include 'group_functions.php';

if(isset($_POST['groupname']) && isset($_POST['grouparray']) && !isset($_POST['groupid']))
{
    $groupname = GetSafeValueString($_POST['groupname'],"string");
    $grouparray = GetSafeValueString($_POST['grouparray'],"string");
    if($_SESSION['use_maintenance']=='1' && $_SESSION['use_hierarchy'] == '1'){
    $cityid = GetSafeValueString($_POST['cityid'],"string");
    $code = GetSafeValueString($_POST['code'],"string");
    $address = GetSafeValueString($_POST['address'],"string");
    }
    else{
    $cityid = null;
    $code = null;
    $address = null;
    }
    addgroup($groupname, $grouparray, $cityid, $code, $address);
}
else if(isset($_POST['groupid']) && isset($_POST['grouparray']))
{
    
    $groupid = GetSafeValueString($_POST['groupid'],"string");
    $groupname = GetSafeValueString($_POST['groupname'],"string");
    $grouparray = GetSafeValueString($_POST['grouparray'],"string");    
    $code = GetSafeValueString($_POST['code'],"string");    
    $city = GetSafeValueString($_POST['cityid'],"string");    
    editgroup($groupid, $grouparray, $groupname, $code, $city);
}
else if(isset($_POST['groupn']))
{
    $groupn = GetSafeValueString($_POST['groupn'],"string");
    get_group_name($groupn);
}
else if(isset($_POST['regionId'])){
    $regionId = GetSafeValueString($_POST['regionId'],"long");
    $zoneName =  getZoneForRegions($regionId);
    return $zoneName;
}
else if(isset($_POST['addregion'])){
  $posteddata       = $_POST['addregion'];
  $regionName       = GetSafeValueString($posteddata['regionName'],"string");
  $regionCode       = GetSafeValueString($posteddata['regionCode'],"string");
  $regionZoneId     = GetSafeValueString($posteddata['regionZoneId'],"string");
  $regionZoneName   = GetSafeValueString($posteddata['regionZoneName'],"long");
  $postDataArray    = array('regionName'=>$regionName,'regionCode'=>$regionCode,'regionZoneId'=>$regionZoneId,'regionZoneName'=>$regionZoneName);
  /*Insert new region*/
  insertRegions($postDataArray);
  unset($_POST['addregion']);
}
else if(isset($_POST['addzone'])){
    $posteddata         =   $_POST['addzone'];
    $zoneName           =   $posteddata['zoneName'];
    $zoneCode           =   $posteddata['zoneCode'];
    $state              =   $posteddata['state'];
    $postDataArray      =   array('zoneName'=>$zoneName,'zoneCode'=>$zoneCode,'state'=>$state);
    insertZones($postDataArray);
    unset($_POST['addzone']);
}
if(isset($_POST['group_history'])){

  $historyObj = new stdClass();
  $group_details = array();
  $historyObj->groupId = $_POST['groupId'];
  $historyObj->start_date = $_POST['STdate'];
  $historyObj->end_date = $_POST['EDdate'];
  $historyObj->total_records=$_POST['total_records'];
  $gm = new GroupManager($_SESSION['customerno']);
  $group_details= $gm->getGroupHistoryLogs($historyObj);
  echo json_encode($group_details);
}
?>
