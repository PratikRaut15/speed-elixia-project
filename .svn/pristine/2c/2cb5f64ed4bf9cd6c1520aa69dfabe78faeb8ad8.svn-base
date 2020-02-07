<?php
//error_reporting(E_ALL ^ E_STRICT);
//ini_set('display_errors', 'On');

include_once 'assign_function.php';

$data = array();
$customerno = exit_issetor($_SESSION['customerno']);
$data['orderid'] = (int)exit_issetor($_POST['orderid']);
$data['lat'] = (float)exit_issetor($_POST['latitude']);
$data['longi'] = (float)exit_issetor($_POST['longitude']);
$data['accu'] = (int)retval_issetor($_POST['accuracy'],3);

include_once '../../lib/bo/DeliveryManager.php';

//$data['fenceid'] = get_fenceid_by_latlong($data['lat'], $data['longi'], $customerno);

$dm = new DeliveryManager($customerno);
$dm->updateOrders($data);

exit('<span style="font-weight:bold;color:green;">Order locations Updated successfully</span>');

?>