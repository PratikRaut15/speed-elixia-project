<?php
//error_reporting(E_ALL ^ E_STRICT);
//ini_set('display_errors', 'On');

include_once 'assign_function.php';

$action = exit_issetor($_REQUEST['action'], json_encode(array('result' => 'failure')));

/*get devicekey from route*/
if($action=='devicekey')
{
    $customerno = exit_issetor($_SESSION['customerno'], 0); //please login
    $routeid = exit_issetor($_POST['routeid'], 0); //routeid not found
    
    include_once '../../lib/bo/RouteManager.php';
    
    $dm = new RouteManager($customerno);
    $status = $dm->getdevicekey($routeid);
    echo $status; exit;
}
elseif($action=='areaauto')
{
    
    $customerno = exit_issetor($_SESSION['customerno'], 0); //please login
    $areaname = exit_issetor($_REQUEST['term'], 0); //areaname not found
    $limit = ri($_REQUEST['limit'], 20); //limit
    
    include_once '../../lib/bo/MappingManager.php';
    
    $dm = new MappingManager($customerno);
    $areas = $dm->getCustomerAreas($areaname,$limit);
    echo json_encode($areas); exit;
}
elseif($action=='zoneauto')
{
    
    $customerno = exit_issetor($_SESSION['customerno'], 0); //please login
    $zonename = exit_issetor($_REQUEST['term'], 0); //areaname not found
    $limit = ri($_REQUEST['limit'], 20); //limit
    
    include_once '../../lib/bo/MappingManager.php';
    
    $dm = new MappingManager($customerno);
    $zones = $dm->getCustomer_Zones($zonename,$limit);
    echo json_encode($zones); exit;
}
elseif($action=='getLBHeatData')
{
    
    $customerno = exit_issetor($_SESSION['customerno']); //please login
    
    include_once '../../lib/bo/MappingManager.php';
    
    $dm = new MappingManager($customerno);
    $heatmap = $dm->getHeatMap();
    echo json_encode($heatmap); exit;
}
else{
    echo "exit";
}
?>