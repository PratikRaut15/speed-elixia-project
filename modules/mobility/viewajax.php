<?php
include_once '../../config.inc.php';
include_once 'mobility_function.php';

error_reporting(E_ALL ^ E_STRICT);
ini_set('display_errors', 'On');

// SQL server connection information
$sql_details = array(
    'user' => DB_Service_user,
    'pass' => DB_Service_pass,
    'db'   => DB_Service,
    'host' => 'localhost'
);
$customerno = exit_issetor($_SESSION['customerno']);
$of = retval_issetor($_GET['of']);
$join = '';
$groupby = '';

if($of=='location'){
    $table = 'location_master as a';
    $join = ' left join city_master as b on a.cityid=b.cityid and a.customerno=b.customerno';
    $primaryKey = 'locid';
    $columns = array(
       // array('db' => 'a.locid', 'dt' => 0 ),
        array('db' => 'a.location', 'dt'=> 0),
        array('db' => 'b.cityname', 'dt'=> 1),
        array('db' => 'a.entrytime','dt'=> 2,
             'formatter' => function($a){
            $view = date("d-m-Y H:i:s", strtotime($a));
            return $view;
        }),
        array('db' => 'a.locid', 'dt'=>3,
            'formatter' => function($d){
            $view = "<a href='mobility.php?pg=edit-location&id=$d' >Edit</a> <a href='javascript:void(0);' onclick='deleteloc($d);' >Delete</a>";
            return $view;
        }),
    );
}
elseif($of=='service'){
    $table = 'service_list as a';
    $primaryKey = 'serviceid';
    $join = ' left join service_category as sc on sc.cid=a.cid and sc.customerno=a.customerno';
    $columns = array(
        array('db' => 'sc.category_name', 'dt'=> 0),
        array('db' => 'a.service_name', 'dt'=> 1),
        array('db' => 'a.cost', 'dt'=> 2),
        array('db' => 'a.expected_time', 'dt'=> 3),
        array('db' => 'a.entrytime','dt'=> 4,
             'formatter' => function($a){
            $view = date("d-m-Y H:i:s", strtotime($a));
            return $view;
        }),
         array('db' => 'a.serviceid', 'dt'=>5,
            'formatter' => function($d){
            $view = "<a href='mobility.php?pg=edit-service&id=$d'>Edit</a> <a href='javascript:void(0);' onclick='deleteserv($d);' >Delete</a>";
            return $view;
        }),
    );
}
elseif($of=='client'){
    $table = 'client as a';
    $primaryKey = 'clientid';
    $join = " left join client_address as ca on a.clientid = ca.clientid";
    $join .= " left join location_master as b on ca.locationid is not null and ca.locationid=b.locid ";
    $join .= " left join city_master as c on ca.cityid is not null and ca.cityid=c.cityid ";
    $groupconcat = "GROUP_CONCAT('<br>',concat(c.cityname,',',b.location))";
    $groupby = " group by a.clientid ";
    $columns = array(
        array('db' => 'a.clientno', 'dt' => 0 ),
        array('db' => 'a.client_name', 'dt'=> 1),
        array('db' => 'a.mobile','dt'=> 2),
        array('db' => 'a.email','dt'=> 3),
        array('db' => "$groupconcat as clocation",'dt'=> 4, 'alias'=>'clocation', 'where'=>$groupconcat),
        array('db' => 'a.min_billing','dt'=> 5),
        array('db' => 'a.entrytime','dt'=> 6,
             'formatter' => function($a){
            $view = date("d-m-Y H:i:s", strtotime($a));
            return $view;
        }),
         array('db' => 'ca.clientid', 'dt'=>7,
            'formatter' => function($d){
            $view = "<a href='mobility.php?pg=edit-client&id=$d'>Edit</a> <a href='javascript:void(0);' onclick='deleteclient($d);' >Delete</a>";
            return $view;
        }),
    );
}
elseif($of=='trackie'){
    $table = 'trackie as a';
    $primaryKey = 'trackid';
    $columns = array(
        //array('db' => 'a.trackid', 'dt' => 0 ),
        array('db' => 'a.name', 'dt'=> 0),
        array('db' => 'a.phone','dt'=> 1),
        array('db' => 'a.email','dt'=> 2),
        array('db' => "a.address",'dt'=> 3,),
        array('db' => 'a.entrytime','dt'=> 4,
             'formatter' => function($a){
            $view = date("d-m-Y H:i:s", strtotime($a));
            return $view;
        }),
         array('db' => 'a.trackid', 'dt'=> 5,
            'formatter' => function($d){
            $view = "<a href='mobility.php?pg=edit-stylist&id=$d'>Edit</a> <a href='javascript:void(0);' onclick='deletetrackie($d);'>Delete</a>";
            return $view;
        }),
    );
}
elseif($of=='servicecall'){
    $table = 'service_call as a';
    $join = " left join client as b on a.clientid=b.clientid and a.customerno=b.customerno ";
    $join .= " left join trackie as c on a.trackieid=c.trackid and a.customerno=c.customerno ";
    $join .= " left join service_ordered as d on a.scid=d.scid ";
    $join .= " left join service_list as e on d.serviceid=e.serviceid ";
    $primaryKey = 'scid';
    $columns = array(
        array('db' => 'b.client_name', 'dt' => 0 ),
        array('db' => 'c.name', 'dt'=> 1),
        array('db' => 'e.service_name','dt'=> 2),
        array('db' => 'a.service_date','dt'=> 3),
        array('db' => "a.entrytime",'dt'=> 4,
             'formatter' => function($a){
            $view = date("d-m-Y H:i:s", strtotime($a));
            return $view;
        }), 
         array('db' => 'a.scid', 'dt'=> 5,
            'formatter' => function($d){
            $view = "<a href='mobility.php?pg=viewservice&id=$d'>View</a> <a href='mobility.php?pg=addpayment&id=$d'>Payment</a>";
            return $view;
        }),        
//please make it scid for delete 
//        array('db' => 'scid', 'dt'=> 5,
//            'formatter' => function($d){
//            $view = "<a href='javascript:void(0);' onclick='deleteservicecall($d);'>Delete</a>";
//            return $view;
//        }),
    );
}elseif($of=='viewdiscount'){
    $table = 'discount_master as a';
    $primaryKey = 'discountid';
    $columns = array(
        //array('db' => 'a.discountid', 'dt' => 0 ),
        array('db' => 'a.discount_code', 'dt'=> 0),
        array('db' => 'a.amount', 'dt'=> 1),
        array('db' => 'a.percentage', 'dt'=> 2),
        array('db' => 'a.expiry_date', 'dt'=> 3),
        array('db' => 'a.entrytime','dt'=> 4,
               'formatter' => function($a){
            $view = date("d-m-Y H:i:s", strtotime($a));
            return $view;
        }),
         array('db' => 'a.discountid', 'dt'=>5,
            'formatter' => function($d){
            $view = "<a href='mobility.php?pg=edit-discount&id=$d'>Edit</a> <a href='javascript:void(0);' onclick='deleteDiscount($d);' >Delete</a>";
            return $view;
        }),
    );
}elseif($of=='viewfeedback'){
    $table = 'feedback_master as a';
    $primaryKey = 'fid';
    $columns = array(
        //array('db' => 'a.fid', 'dt' => 0 ),
        array('db' => 'a.feedback_question', 'dt'=> 0),
        array('db' => 'a.entrytime','dt'=> 1,
               'formatter' => function($a){
            $view = date("d-m-Y H:i:s", strtotime($a));
            return $view;
        }),
        array('db' => 'a.fid', 'dt'=>2,
            'formatter' => function($d){
            $view = "<a href='mobility.php?pg=edit-feedback&id=$d' >Edit</a> <a href='javascript:void(0);' onclick='deleteFeedback($d);' >Delete</a>";
            return $view;
        }),
    );
}elseif($of=='viewcat'){
    $table = 'service_category as a';
    $primaryKey = 'cid';
    $columns = array(
        array('db' => 'a.category_name', 'dt'=> 0),
        array('db' => 'a.entrytime','dt'=> 1,
               'formatter' => function($a){
            $view = date("d-m-Y H:i:s", strtotime($a));
            return $view;
        }),
        array('db' => 'a.cid', 'dt'=>2,
            'formatter' => function($d){
            $view = "<a href='mobility.php?pg=edit-category&id=$d' >Edit</a> <a href='javascript:void(0);' onclick='deleteCategory($d);' >Delete</a>";
            return $view;
        }),
    );
}
elseif($of=='viewpack'){
    $table = 'package_master as a';
    $primaryKey = 'pckgid';
    $columns = array(
        array('db' => 'a.package_code', 'dt'=> 0),
        array('db' => 'a.amount', 'dt'=> 1),
        array('db' => 'a.validity','dt'=> 2,
               'formatter' => function($a){
            $view = date("d-m-Y", strtotime($a));
            return $view;
        }),
        array('db' => 'a.entrytime','dt'=> 3,
               'formatter' => function($a){
            $view = date("d-m-Y H:i:s", strtotime($a));
            return $view;
        }),
        array('db' => 'a.pckgid', 'dt'=>4,
            'formatter' => function($d){
            $view = "<a href='mobility.php?pg=edit-package&id=$d' >Edit</a> <a href='javascript:void(0);' onclick='deletePackage($d);' >Delete</a>";
            return $view;
        }),
    );
}else{
    $table = 'city_master as a';
    $primaryKey = 'cityid';
    $columns = array(
        //array('db' => 'cityid', 'dt' => 0 ),
        array('db' => 'cityname', 'dt'=> 0),
        array('db' => 'entrytime','dt'=> 1,
            'formatter' => function($a){
            $view = date("d-m-Y H:i:s", strtotime($a));
            return $view;
        }),
        array('db' => 'cityid', 'dt'=>2,
            'formatter' => function($d){
            $view = "<a href='mobility.php?pg=edit-city&id=$d' >Edit</a> <a href='javascript:void(0);' onclick='deleteCity($d);' >Delete</a>";
            return $view;
        }),
    );
}


$customWhere = " a.customerno=$customerno and a.isdeleted=0 ";
 
require( '../routing/ssp.class.php' );
 
echo json_encode(
    SSP::simple( $_GET, $sql_details, $table, $primaryKey, $columns, $customWhere, $join,$groupby)
);

?>