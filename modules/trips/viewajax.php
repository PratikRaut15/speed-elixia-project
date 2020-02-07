<?php

include_once 'trips_function.php';
// SQL server connection information
$sql_details = array(
    'user' => DB_LOGIN,
    'pass' => DB_PWD,
    'db' => SPEEDDB,
    'host' => 'localhost'
);

$customerno = exit_issetor($_SESSION['customerno']);
$of = retval_issetor($_GET['of']);
$join = '';
$groupby = '';
$customWhere = '';
$istripend = '';
/*
if ($of == 'viewtrips') {
    $table = 'tripdetails as a';
    $join = "left join  tripstatus as ts  on a.tripstatusid= ts.tripstatusid  "
            . " left join tripdetail_history as th on th.tripstatusid= ts.tripstatusid AND  th.tripstatusid=3"
            . " left join tripconsignee as con on con.consid = a.consigneeid "
            . " left join tripconsignor as consr on consr.consrid = a.consignorid ";
    $primaryKey = 'tripid';
    $istripend = "AND a.is_tripend=0";

    $columns = array(
        array('db' => 'a.vehicleno', 'dt' => 0),
        array('db' => 'a.triplogno', 'dt' => 1,
            'formatter' => function($d, $row) {
//  print_r($row);
//                $statusdate = $row['statusdate'];
//                $SDate = date('y-m-d',strtotime($statusdate));
//                $STime = date('h:i',strtotime($statusdate));
//                $today = date('y-m-d h:i:s');
//                $EDate = date('y-m-d',strtotime($today));
//                $Etime = date('h:i',strtotime($today));
//                $vehicleno = $row['vehicleno'];
//                $triplogno = $row['triplogno'];
//                $interval = 30;
//$view = "<a href='javascript:void(0);' onclick='getLocationReport('" . $SDate . "','" . $STime . "','" . $EDate . "','" . $ETime . "','" . $interval . "','" . $vehicleno . "');'>".$row['triplogno']."</a>";
                //$view = "<a href='javascript:void(0);' onclick='getLocationReport();'>".$row['statusdate']."</a>";
                return $view;
            }),
        array('db' => 'ts.tripstatus', 'dt' => 2),
        array('db' => 'a.routename', 'dt' => 3),
        array('db' => 'a.budgetedkms', 'dt' => 4),
        array('db' => 'a.budgetedhrs', 'dt' => 5),
        array('db' => 'consr.consignorname', 'dt' => 6),
        array('db' => 'con.consigneename', 'dt' => 7),
        array('db' => 'a.billingparty', 'dt' => 8),
        array('db' => 'a.mintemp', 'dt' => 9),
        array('db' => 'a.maxtemp', 'dt' => 10),
        array('db' => 'a.drivername', 'dt' => 11),
        array('db' => 'a.drivermobile1', 'dt' => 12),
        array('db' => 'a.drivermobile2', 'dt' => 13),
        array('db' => 'th.statusdate', 'dt' => 14),
        array('db' => 'a.tripid', 'dt' => 15,
            'formatter' => function($d) {
                $view = "<a href='trips.php?pg=tripview&frm=edit&tripid=$d'><img src='../../images/edit_black.png'/></a>";
                return $view;
            })
    );
} else
    */
    if ($of == 'viewtripstatus') {
    $table = 'tripstatus as a';
    $primaryKey = 'tripstatusid';
    $columns = array(
        array('db' => 'a.tripstatus', 'dt' => 0),
        array('db' => 'a.tripstatusid', 'dt' => 1,
            'formatter' => function($d) {
                $view = "<a href='trips.php?pg=tripstatusedit&statusid=$d'><img src='../../images/edit_black.png'/></a>";
                return $view;
            }),
        array('db' => 'a.tripstatusid', 'dt' => 2,
            'formatter' => function($d) {
                $view = "<a href='javascript:void(0);' onclick='deletetripstatus($d);'><img src='../../images/Delete_red.png'/></a>";
                return $view;
            }),
    );
} elseif ($of == 'viewconsignee') {
    $table = 'tripconsignee as a';
    $primaryKey = 'consid';
    $columns = array(
        array('db' => 'a.consigneename', 'dt' => 0),
        array('db' => 'a.email', 'dt' => 1),
        array('db' => 'a.phone', 'dt' => 2),
        array('db' => 'a.consid', 'dt' => 3,
            'formatter' => function($d) {
                $view = "<a href='trips.php?pg=editconsignee&consid=$d'><img src='../../images/edit_black.png'/></a>";
                return $view;
            }),
        array('db' => 'a.consid', 'dt' => 4,
            'formatter' => function($d) {
                $view = "<a href='javascript:void(0);' onclick='deleteconsignee($d);'><img src='../../images/Delete_red.png'/></a>";
                return $view;
            }),
    );
} elseif ($of == 'viewconsignor') {
    $table = 'tripconsignor as a';
    $primaryKey = 'consrid';
    $columns = array(
        array('db' => 'a.consignorname', 'dt' => 0),
        array('db' => 'a.email', 'dt' => 1),
        array('db' => 'a.phone', 'dt' => 2),
        array('db' => 'a.consrid', 'dt' => 3,
            'formatter' => function($d) {
                $view = "<a href='trips.php?pg=editconsigneer&consrid=$d'><img src='../../images/edit_black.png'/></a>";
                return $view;
            }),
        array('db' => 'a.consrid', 'dt' => 4,
            'formatter' => function($d) {
                $view = "<a href='javascript:void(0);' onclick='deleteconsignor($d);'><img src='../../images/Delete_red.png'/></a>";
                return $view;
            }),
    );
}



$customWhere .= "a.customerno=$customerno and a.isdeleted=0 " . $istripend;

require( '../routing/ssp.class.php' );

echo json_encode(
        SSP::simple($_GET, $sql_details, $table, $primaryKey, $columns, $customWhere, $join, $groupby)
);
?>