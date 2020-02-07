<?php

include_once '../../config.inc.php';
include_once 'salesengage_function.php';

//error_reporting(E_ALL ^ E_STRICT);
//ini_set('display_errors', 'On');
// SQL server connection information
$sql_details = array(
    'user' => DB_Service_user,
    'pass' => DB_Service_pass,
    'db' => DB_Salesengage,
    'host' => 'localhost'
);
$customerno = exit_issetor($_SESSION['customerno']);
$of = retval_issetor($_GET['of']);
$join = '';
$groupby = '';
$customWhere = '';

if ($of == 'viewclient') {
    $table = 'clients as a';
    $primaryKey = 'clientid';
    $columns = array(
        array('db' => 'a.name', 'dt' => 0),
        array('db' => 'a.address', 'dt' => 1),
        array('db' => 'a.email', 'dt' => 2),
        array('db' => "a.mobileno", 'dt' => 3,),
        array('db' => 'a.clientid', 'dt' => 4,
            'formatter' => function($d) {
                $view = "<a href='salesengage.php?pg=edit-client&id=$d'><img src='../../images/edit_black.png'/></a>";
                return $view;
            }),
        array('db' => 'a.clientid', 'dt' => 5,
            'formatter' => function($d) {
                $view = "<a href='javascript:void(0);' onclick='deleteclient($d);'><img src='../../images/Delete_red.png'/></a>";
                return $view;
            }),
    );
} else if ($of == 'viewproduct') {
    $table = 'products as a';
    $primaryKey = 'productid';
    $columns = array(
        array('db' => 'a.productname', 'dt' => 0),
        array('db' => 'a.unit_price', 'dt' => 1),
        array('db' => 'a.entrytime', 'dt' => 2,
            'formatter' => function($a) {
                $view = date("d-m-Y H:i:s", strtotime($a));
                return $view;
            }),
        array('db' => 'a.productid', 'dt' => 3,
            'formatter' => function($d) {
                $view = "<a href='salesengage.php?pg=edit-product&id=$d'><img src='../../images/edit_black.png'/></a>";
                return $view;
            }),
        array('db' => 'a.productid', 'dt' => 4,
            'formatter' => function($d) {
                $view = "<a href='javascript:void(0);' onclick='deleteproduct($d);'><img src='../../images/Delete_red.png'/></a>";
                return $view;
            }),
    );
} else if ($of == 'viewsourceorder') {
    $table = 'sourceorder as a';
    $primaryKey = 'srcordid';
    $columns = array(
        array('db' => 'a.source_order', 'dt' => 0),
        array('db' => 'a.entrytime', 'dt' => 1,
            'formatter' => function($a) {
                $view = date("d-m-Y H:i:s", strtotime($a));
                return $view;
            }),
        array('db' => 'srcordid', 'dt' => 2,
            'formatter' => function($d) {
                $view = "<a href='salesengage.php?pg=edit-sourceorder&id=$d'><img src='../../images/edit_black.png'/></a>";
                return $view;
            }),
        array('db' => 'srcordid', 'dt' => 3,
            'formatter' => function($d) {
                $view = "<a href='javascript:void(0);' onclick='deletesrcorder($d);'><img src='../../images/Delete_red.png'/></a>";
                return $view;
            }),
    );
} else if ($of == 'viewlost') {
    $table = 'lost_reasons as a';
    $primaryKey = 'lostreasonid';
    $columns = array(
        array('db' => 'a.reasons', 'dt' => 0),
        array('db' => 'a.entrytime', 'dt' => 1,
            'formatter' => function($a) {
                $view = date("d-m-Y H:i:s", strtotime($a));
                return $view;
            }),
        array('db' => 'a.lostreasonid', 'dt' => 2,
            'formatter' => function($d) {
                $view = "<a href='salesengage.php?pg=edit-lost&id=$d'><img src='../../images/edit_black.png'/></a>";
                return $view;
            }),
        array('db' => 'a.lostreasonid', 'dt' => 3,
            'formatter' => function($d) {
                $view = "<a href='javascript:void(0);' onclick='deletelost($d);'><img src='../../images/Delete_red.png'/></a>";
                return $view;
            }),
    );
} else if ($of == 'viewstage') {
    $table = 'stages as a';
    $primaryKey = 'stageid';
    $columns = array(
        array('db' => 'a.stagename', 'dt' => 0),
        array('db' => 'a.entrytime', 'dt' => 1,
            'formatter' => function($a) {
                $view = date("d-m-Y H:i:s", strtotime($a));
                return $view;
            }),
        array('db' => 'a.stageid', 'dt' => 2,
            'formatter' => function($d) {
                $view = "<a href='salesengage.php?pg=edit-stage&id=$d'><img src='../../images/edit_black.png'/></a>";
                return $view;
            }),
        array('db' => 'a.stageid', 'dt' => 3,
            'formatter' => function($d) {
                $view = "<a href='javascript:void(0);' onclick='deletestage($d);'><img src='../../images/Delete_red.png'/></a>";
                return $view;
            }),
    );
} else if ($of == 'viewreminder') {
    $table = 'reminders as a';
    $primaryKey = 'reminderid';
    $columns = array(
        array('db' => 'a.remindername', 'dt' => 0),
        array('db' => 'a.entrytime', 'dt' => 1,
            'formatter' => function($a) {
                $view = date("d-m-Y H:i:s", strtotime($a));
                return $view;
            }),
        array('db' => 'a.reminderid', 'dt' => 2,
            'formatter' => function($d) {
                $view = "<a href='salesengage.php?pg=edit-reminder&id=$d'><img src='../../images/edit_black.png'/></a>";
                return $view;
            }),
        array('db' => 'a.reminderid', 'dt' => 3,
            'formatter' => function($d) {
                $view = "<a href='javascript:void(0);' onclick='deletereminder($d);'><img src='../../images/Delete_red.png'/></a>";
                return $view;
            }),
    );
} else if ($of == 'vieworder') {
    $table = 'orders as a';
    $join = 'left join productsinorder as pi on pi.orderid = a.orderid left  join products as p on p.productid = pi.productid  inner join clients as c on c.clientid = a.clientid left join stages as s on s.stageid = a.stageid  ';
    $groupby = ' group by a.orderid';
    $primaryKey = 'orderid';
    $columns = array(
        array('db' => 'a.orderno', 'dt' => 0),
        array('db' => 'a.orderid', 'dt' => 1,
            'formatter' => function($f) {
                $view = "<a href='salesengage.php?pg=view-activity&id=$f'><img src='../../images/11-clock.png'/></a> ";
                return $view;
            }),
        array('db' => ' c.name', 'dt' => 2),
        array('db' => ' s.stagename', 'dt' => 3),
        array('db' => ' a.expectedordercomplitiondate', 'dt' => 4,
            'formatter' => function($a) {
                $view = date("d-m-Y", strtotime($a));
                return $view;
            }),
        array('db' => ' GROUP_CONCAT(DISTINCT p.productname ORDER BY p.productname ASC SEPARATOR ",") as productlist', 'dt' => 5, 'alias' => 'productlist'),
        array('db' => ' a.orderid', 'dt' => 6,
            'formatter' => function($d) {
                $view = "<a href='salesengage.php?pg=edit-order&id=$d'><img src='../../images/edit_black.png'/></a>";
                return $view;
            }),
        array('db' => ' a.orderid', 'dt' => 7,
            'formatter' => function($e) {
                $view = "<a href='javascript:void(0);' onclick='deleteorder($e);'><img src='../../images/Delete_red.png'/></a>";
                return $view;
            }),
    );


    $customWhere .= "pi.isdeleted=0 AND a.addedby=".$_SESSION['userid']." AND ";
} else if ($of == 'viewtemplate') {
    $table = 'templates as a ';
    $join = ' left join reminders as r on r.reminderid = a.reminderid left join stages as s on s.stageid = a.stageid ';
    $primaryKey = 'templateid';
    $columns = array(
        array('db' => 'r.remindername', 'dt' => 0, 'formatter' => function($d, $row) {
                return ($d == "") ? "-" : $d;
            }),
        array('db' => 's.stagename', 'dt' => 1, 'formatter' => function($d, $row) {
                return ($d == "") ? "-" : $d;
            }),
        array('db' => 'a.recipienttype', 'dt' => 2, 'formatter' => function($d, $row) {
                return ($d == '1') ? "Client" : (($d == '2') ? "User" : "-");
            }),
        array('db' => 'a.templateid', 'dt' => 3,
            'formatter' => function($d) {
                $view = "<a href='salesengage.php?pg=edit-template&id=$d'><img src='../../images/edit_black.png'/></a>";
                return $view;
            }),
        array('db' => 'a.templateid', 'dt' => 4,
            'formatter' => function($d) {
                $view = "<a href='javascript:void(0);' onclick='deletetemplate($d);'><img src='../../images/Delete_red.png'/></a>";
                return $view;
            }),
    );
}

$customWhere .= "a.customerno=$customerno and a.isdeleted=0 ";


require( '../routing/ssp.class.php' );

echo json_encode(
        SSP::simple($_GET, $sql_details, $table, $primaryKey, $columns, $customWhere, $join, $groupby)
);
?>