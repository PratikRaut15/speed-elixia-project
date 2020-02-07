<?php

session_start();
include_once '../../config.inc.php';
include_once '../../lib/comman_function/reports_func.php';

//error_reporting(E_ALL ^ E_STRICT);
//ini_set('display_errors', 'On');

$table = 'orderrequest as a';
$join = '';
$join = ' left join timeslot as ts on ts.timeslotid = a.slotid  
          left join trackingstatus as trstatus on trstatus.trackingstatusid = a.trackingstatusid
          ';
$primaryKey = 'orderid';
$columns = array(
    array('db' => 'a.orderid', 'dt' => 0),
    array('db' => 'a.AWBno', 'dt' => 1),
    array('db' => 'ts.timeslot', 'dt' => 2),
    array('db' => 'a.pickupdate', 'dt' => 3,
        'formatter' => function($a) {
            $view = date("d-m-Y", strtotime($a));
            return $view;
        }),
    array('db' => 'trstatus.trackingstatusname', 'dt' => 4),
    array('db' => 'a.orderid', 'dt' => 5, 'formatter' => function($d) {
            $view = "<a href='pick.php?id=19&oid=$d'><img src='../../images/edit.png' width='25px' height='25px'/></a>";
            return $view;
        }),
);
// SQL server connection information
$sql_details = array(
    'user' => DB_LOGIN,
    'pass' => DB_PWD,
    'db' => WOWDB,
    'host' => 'localhost'
);

//$customerno = exit_issetor($_SESSION['customerno']);
$customWhere = ' a.isdeleted=0 ';

/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * If you just want to use the basic configuration for DataTables with PHP
 * server-side, there is no need to edit below this line.
 */


require( '../routing/ssp.class.php' );
echo json_encode(
        SSP::simple($_GET, $sql_details, $table, $primaryKey, $columns, $customWhere, $join)
);
?>