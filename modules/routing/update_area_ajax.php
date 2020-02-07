<?php
//error_reporting(E_ALL ^ E_STRICT);
//ini_set('display_errors', 'On');

include_once 'assign_function.php';

$data = array();
$customerno = exit_issetor($_SESSION['customerno']);
$data['areaid'] = (int)exit_issetor($_POST['areaid']);
$data['area_id'] = (int)exit_issetor($_POST['area_id']);
$data['lat'] = (float)exit_issetor($_POST['latitude']);
$data['longi'] = (float)exit_issetor($_POST['longitude']);
$data['zone_id'] = (int)exit_issetor($_POST['zone_id']);


include_once '../../lib/bo/DeliveryManager.php';

//$data['fenceid'] = get_fenceid_by_latlong($data['lat'], $data['longi'], $customerno);

$dm = new DeliveryManager($customerno);
//$dm->updateOrders($data);
$update = $dm->updateAreas($data);
if($update == 'ok'){
    $area_id = (int)exit_issetor($_POST['area_id']);
    $zone_id = (int)exit_issetor($_POST['zone_id']);
   $url = "http://jags.logixglobal.com/master.php";
   $ch = curl_init();
curl_setopt($ch, CURLOPT_URL,$url);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_RETURNTRANSFER,1); // return into a variable
curl_setopt($ch, CURLOPT_HEADER, false);
curl_setopt($ch, CURLOPT_SSLVERSION, 3);
curl_setopt($ch, CURLOPT_POSTFIELDS,"zoneid=".$zone_id."&areaid=".$area_id."&verify=1");
$result = curl_exec($ch);

//echo "<pre>".$result;
//$final =  json_decode($result);
//echo "<pre>";print_r($final->item_details);
curl_close($ch);
}
exit('<span style="font-weight:bold;color:green;">Area Approved successfully</span>');

?>