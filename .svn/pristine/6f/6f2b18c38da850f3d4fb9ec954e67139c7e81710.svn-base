<?php
/**
 * Start page of Sales engage-module
 */
//error_reporting(E_ALL ^ E_STRICT);
//ini_set('display_errors', 'On');

require_once '../panels/header.php';
require_once "salesengage_function.php";
if(!isset($_SESSION['userid'])){
    header("Location: ".$_SESSION['subdir']);
}
if($_SESSION['use_sales']==0){
    exit('No access to this module');
}

$pg = isset($_GET['pg']) ? $_GET['pg'] : 'def';


if($pg=='view-client'){require_once 'pages/clientview.php';}
elseif($pg=='edit-client'){require_once 'pages/editclient.php';} //edit client
elseif($pg=='view-product'){require_once 'pages/productview.php';}
elseif($pg=='edit-product'){require_once 'pages/editproduct.php';} //edit product
elseif($pg=='view-lost'){require_once 'pages/lostview.php';}
elseif($pg=='edit-lost'){require_once 'pages/editlost.php';} //edit Lost
elseif($pg=='view-stage'){require_once 'pages/stageview.php';}
elseif($pg=='edit-stage'){require_once 'pages/editstage.php';} //edit Stages
elseif($pg=='view-reminder'){require_once 'pages/reminderview.php';}
elseif($pg=='edit-reminder'){require_once 'pages/editreminder.php';} //edit Reminder
elseif($pg=='edit-order'){ require_once 'pages/editorder.php';} //edit order
elseif($pg=='view-order'){ require_once 'pages/orders.php';}  
elseif($pg=='edit-template'){require_once 'pages/edittemplate.php';} //edit Template
elseif($pg=='view-template'){require_once 'pages/viewtemplate.php';}  
elseif($pg=='edit-activity'){require_once 'pages/editactivity.php';} //edit Activity
elseif($pg=='view-activity'){require_once 'pages/viewactivity.php';}  
elseif($pg=='edit-sourceorder'){require_once 'pages/editsourceorder.php';} //edit Activity
elseif($pg=='view-sourceorder'){require_once 'pages/viewsourceorder.php';}  
elseif($pg=='view-importclient'){require_once 'pages/viewimportclients.php';}  

else{     
       require_once 'pages/orders.php';
    }


require_once '../panels/footer.php';
?>
