<?php
include_once '../../deliveryapi/class/config.inc.php';
include_once '../../lib/comman_function/reports_func.php';


//error_reporting(E_ALL ^ E_STRICT);
//ini_set('display_errors', 'On');

$of = retval_issetor($_REQUEST['of'], 'zone');
$groupQ = '';
$join = '';
if($of=='zone'){
    $table = 'zone_master as a';
    $primaryKey = 'zone_master_id';
    $columns = array(
        array('db' => 'a.zone_id', 'dt' => 0 ),
        array('db' => 'a.zonename', 'dt'=> 1),

    );
    if(isset($_SESSION['groupid']) && $_SESSION['groupid']!= 0){
        $grpid = (int)$_SESSION['groupid'];
        $groupQ = " AND a.groupid = $grpid ";
    }
}
elseif($of=='area'){
    $table = 'area_master as a';
    $join = " left join zone_master as b on a.zone_id=b.zone_id and a.customerno=b.customerno ";
    $primaryKey = 'area_master_id';
    $columns = array(
        array('db' => 'a.area_id', 'dt' => 0 ),

        array('db' => 'a.areaname', 'dt'=>1,
        'formatter' => function($d, $row){
            return ucwords($d);
         }),
        array('db' => 'b.zonename', 'dt'=> 2),
        array('db' => 'a.lat', 'dt'=> 3),
        array('db' => 'a.lng', 'dt'=> 4),

        /**
       array('db' => 'a.entry_date', 'dt'=>5,
        'formatter' => function($d, $row){
            //return (!empty($d)) ? "$d" :  "Ongoing";
            return ($d =='0000-00-00 00:00:00' || $d == '') ? "" : date(speedConstants::DEFAULT_DATETIME, strtotime($d)) ;

        }),
        array('db' => 'a.update_date', 'dt'=>6,
        'formatter' => function($d, $row){
            //return (!empty($d)) ? "$d" :  "Ongoing";
        return ($d == '0000-00-00 00:00:00' || $d == '') ? "" : date(speedConstants::DEFAULT_DATETIME, strtotime($d)) ;

        }),
         *
         */
        array('db' => 'area_master_id', 'dt'=>5, 'formatter' => function($d, $row){
            return "<a href='pick.php?id=10&oid=$d'><img src='../../images/edit.png' width='16px;' height='16px;' alt='Edit' title='Edit'  /></a>";
        }),
    );
    if(isset($_SESSION['groupid']) && $_SESSION['groupid']!= 0){
        $grpid = (int)$_SESSION['groupid'];
        $groupQ = " AND b.groupid = $grpid ";
    }
}
elseif($of=='slot'){
    $table = 'slot_master as a';
    $primaryKey = 'slot_id';
    $columns = array(
        array('db' => 'a.customer_slot_id', 'dt' => 0 ),
        array('db' => 'concat_ws(" - ", a.start_time,a.end_time) as times', 'dt'=> 1, 'alias'=>'times'),

        array('db' => 'slot_id', 'dt'=>5, 'formatter' => function($d, $row){
            return "<a href='pick.php?id=19&oid=$d'><img src='../../images/edit.png' width='16px;' height='16px;' alt='Edit' title='Edit'/></a>";
        }),
    );
    if(isset($_SESSION['groupid']) && $_SESSION['groupid']!= 0){
        $grpid = (int)$_SESSION['groupid'];
        $groupQ = " AND b.groupid = $grpid ";
    }
}

// SQL server connection information
$sql_details = array(
    'user' => DATABASE_USER,
    'pass' => DATABASE_PASSWORD,
    'db'   => DATABASE_PICKUP,
    'host' => DATABASE_HOST
);
$customerno = exit_issetor($_SESSION['customerno']);
$customWhere = " a.customerno=$customerno and a.isdeleted= 0  $groupQ ";

require( 'ssp.class.php' );

echo json_encode(
    SSP::simple( $_GET, $sql_details, $table, $primaryKey, $columns, $customWhere, $join)
);

?>
