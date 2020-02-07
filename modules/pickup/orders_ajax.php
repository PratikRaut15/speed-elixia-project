<?php

session_start();
include_once '../../config.inc.php';
include_once '../../lib/comman_function/reports_func.php';

//error_reporting(E_ALL ^ E_STRICT);
//ini_set('display_errors', 'On');

$table = 'pickup_order as a';
$join = '';
$join = '
    inner join vendormapping as vm on vm.vendor_no = a.vendorno
    inner join pickup_customer as b on a.customerid=b.customerid
    inner join pickup_vendor as c on vm.vendorid=c.vendorid
    left join pickup_shiper as d on a.shipperid=d.sid
    left join ' . SPEEDDB . '.user as e on a.pickupboyid=e.userid
    ';
$primaryKey = 'oid';

$columns = array(
   array('db' => 'a.oid', 'dt' => 0),
   array('db' => 'b.customername', 'dt' => 1),
   array('db' => 'c.vendorname', 'dt' => 2),
   array('db' => 'c.vendorid', 'dt' => 3),
   array('db' => 'a.orderid', 'dt' => 4),
   array('db' => 'e.realname', 'dt' => 5),
   array('db' => 'a.pickupdate', 'dt' => 6,
      'formatter' => function($a) {
       $view = date("d-m-Y", strtotime($a));
       return $view;
      }),
   array('db' => 'status', 'dt' => 7, 'formatter' => function($d, $row) {
       return ($d == '2') ? "Cancelled" : (($d == '1') ? "Picked Up" : "Ongoing");
      }),
   array('db' => 'oid', 'dt' => 8, 'formatter' => function($d, $row) {
       $status = $row['status'];
       if ($status == 1) {
        $view = "<a href='pick.php?id=19&oid=$d'><img src='../../images/edit.png' width='25px' height='25px'/></a>";
       } else {
        $view = "<a href='pick.php?id=19&oid=$d'><img src='../../images/edit.png' width='25px' height='25px'/></a>";
       }
       return $view;
      }),
);
// SQL server connection information
$sql_details = array(
   'user' => DB_LOGIN,
   'pass' => DB_PWD,
   'db' => DB_PICKUP,
   'host' => 'localhost'
);

$customerno = exit_issetor($_SESSION['customerno']);

$customWhere = " a.customerno=$customerno  and a.isdeleted = 0 ";


/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * If you just want to use the basic configuration for DataTables with PHP
 * server-side, there is no need to edit below this line.
 */


require( '../routing/ssp.class.php' );

echo json_encode(
 SSP::simple($_GET, $sql_details, $table, $primaryKey, $columns, $customWhere, $join)
);
?>