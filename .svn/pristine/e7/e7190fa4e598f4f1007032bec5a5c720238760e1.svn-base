<?php
include_once '../../deliveryapi/class/config.inc.php';
include_once '../../config.inc.php';
include_once '../../lib/comman_function/reports_func.php';

//error_reporting(E_ALL ^ E_STRICT);
//ini_set('display_errors', 'On');

$table = 'master_orders as a';
$join = ' left join zone_master as b on a.fenceid=b.zone_id and a.customerno=b.customerno
    left join area_master as d on a.fenceid=d.zone_id and a.areaid=d.area_id and a.customerno=d.customerno
    left join master_shipping_address as c on a.id=c.orderid
    left join master_shipment as e on a.id = e.orderid
    left join master_status as f on e.shipping_status = f.statusid';
$primaryKey = 'id';
$columns = array(
    array('db' => 'a.order_id', 'dt' => 0 ),
    //array('db' => 'accuracy', 'dt'=> 1),
    array('db' => 'b.zonename','dt'=> 1),
    array('db' => 'd.areaname','dt'=> 2),
    array('db'=>'c.flat','dt'=>3),
    array('db'=>'c.building','dt'=>4),
    array('db'=>'c.city','dt'=>5),
    array('db'=>'c.landmark','dt'=>6),
    array('db' => 'slot','dt'=> 7),
    array('db' => 'delivery_date','dt'=> 8),
    array('db' => 'date(created_on)','dt'=> 9),
    /**
    array('db' => 'statusname', 'dt'=>10,
        'formatter' => function($d, $row){
            return (!empty($d)) ? "$d" :  "Ongoing";
        }),
      
     * 
     */         
       
        array('db' => 'statusname', 'dt'=>10,
        'formatter' => function($d, $row){
            return ($d=='0-1') ? "Cancelled" : (($d=='1-0') ? "Delivered" : "Ongoing");
        }),
     
    array('db' => 'id', 'dt'=>11, 'formatter' => function($d, $row){
            $rowt = json_encode($row);
            $view = " <a href='' data-toggle='modal' onclick='viewOrder($rowt);' data-target='#vOrderModal'><img src='../../images/icon_pages.png' width='16px;' height='16px;' alt='View'  title='View' /></a>";
            $payment = "<a href='assign.php?id=13&oid=$d'><img src='../../images/icon_money.png' width='16px;' height='16px;' alt='Payment' title='Payment' /></a>";
            return "<a href='assign.php?id=9&oid=$d'><img src='../../images/edit.png' width='16px;' height='16px;' alt='Edit' title='Edit'  /></a>$view $payment";
    }),
    array('db' => 'route', 'dt' => 12 ),
    array('db' => 'id', 'dt' => 13 ),
    
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
    $groupQ = " AND b.groupid = $grpid ";
}
$customWhere = " a.customerno=$customerno $groupQ ";
 
 
/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * If you just want to use the basic configuration for DataTables with PHP
 * server-side, there is no need to edit below this line.
 */


require( 'ssp.class.php' );
 
echo json_encode(
    SSP::simple( $_GET, $sql_details, $table, $primaryKey, $columns, $customWhere, $join)
);

?>