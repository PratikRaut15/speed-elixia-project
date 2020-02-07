<?php
include_once '../../deliveryapi/class/config.inc.php';
include_once '../../config.inc.php';
include_once '../../lib/comman_function/reports_func.php';

//error_reporting(E_ALL ^ E_STRICT);
//ini_set('display_errors', 'On');

$table = 'order_route_sequence as a';
$join = ' left join master_orders as b on b.id=a.order_id ';
$join .= ' left join area_master as am on am.area_id=b.areaid and am.customerno=b.customerno';
$join .= ' left join zone_master as z on z.zone_id=b.fenceid and z.customerno=b.customerno';
$join .= ' left join master_shipping_address as msa on msa.orderid=a.order_id';
$join .= ' left join '.DATABASE_SPEED.'.vehicle as v on v.vehicleid=a.vehicle_id and v.isdeleted=0 ';
    
$primaryKey = 'sequence_id';
$columns = array(
    array('db' => 'b.delivery_date','dt'=> 0),
    array('db' => 'v.vehicleno','dt'=> 1),
    array('db' => 'b.slot', 'dt'=> 2),
    array('db' => 'a.sequence', 'dt'=> 3),
    array('db' => 'round(a.time_taken/60) as tt', 'dt'=> 4, 'alias'=>'tt'),
    array('db' => 'b.order_id', 'dt' => 5),
    array('db' => 'z.zonename', 'dt'=> 6),
    array('db' => 'concat_ws(",", msa.flat, msa.building,msa.landmark,am.areaname,msa.city) as address', 'dt'=> 7, 'alias'=>'address'),
    array('db' => 'a.vehicle_id', 'dt'=> 8),
    
);
// SQL server connection information
$sql_details = array(
    'user' => $db_loginname,
    'pass' => $db_loginpassword,
    'db'   => DATABASE_NAME,
    'host' => 'localhost'
);
$customerno = exit_issetor($_SESSION['customerno']);
$groupQ = '';
if(isset($_SESSION['groupid']) && $_SESSION['groupid']!= 0){
    $grpid = (int)$_SESSION['groupid'];
    $groupQ = " AND z.groupid = $grpid ";
}
$customWhere = " b.customerno=$customerno $groupQ ";
 
 
/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * If you just want to use the basic configuration for DataTables with PHP
 * server-side, there is no need to edit below this line.
 */


require( 'ssp.class.php' );
 
echo json_encode(
    SSP::simple( $_GET, $sql_details, $table, $primaryKey, $columns, $customWhere, $join)
);

?>
