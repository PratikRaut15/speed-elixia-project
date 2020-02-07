<?php
/**
 * Start page of mobility-module
 */
//error_reporting(E_ALL ^ E_STRICT);
//ini_set('display_errors', 'On');

require_once '../panels/header.php';
require_once "mobility_function.php";
if(!isset($_SESSION['userid'])){
    header("Location: ".$_SESSION['subdir']);
}
if($_SESSION['use_mobility']==0){
    exit('No access to this module');
}

$pg = isset($_GET['pg']) ? $_GET['pg'] : 'def';

if($pg=='add-city'){require_once 'pages/mobility_menu.php';require_once 'pages/city_master.php';}
elseif($pg=='view-city'){require_once 'pages/mobility_menu.php';require_once 'pages/view_city.php';}
elseif($pg=='edit-city'){require_once 'pages/mobility_menu.php';require_once 'pages/edit_city_master.php';} //edit city
elseif($pg=='add-location'){require_once 'pages/mobility_menu.php';require_once 'pages/location_master.php';}
elseif($pg=='view-location'){require_once 'pages/mobility_menu.php';require_once 'pages/view_location.php';}
elseif($pg=='edit-location'){require_once 'pages/mobility_menu.php';require_once 'pages/edit_location_master.php';} //edit location
elseif($pg=='add-service'){require_once 'pages/mobility_menu.php';require_once 'pages/service_list.php';}
elseif($pg=='view-service'){require_once 'pages/mobility_menu.php';require_once 'pages/view_service.php';}
elseif($pg=='edit-service'){require_once 'pages/mobility_menu.php';require_once 'pages/edit_service_list.php';}  //edit service list
elseif($pg=='add-client'){require_once 'pages/mobility_menu.php';require_once 'pages/client_master.php';}
elseif($pg=='view-client'){require_once 'pages/mobility_menu.php';require_once 'pages/view_client.php';}
elseif($pg=='edit-client'){require_once 'pages/mobility_menu.php';require_once 'pages/edit_client_master.php';} //edit client
elseif($pg=="add-$rename"){require_once 'pages/mobility_menu.php';require_once 'pages/trackie_master.php';}
elseif($pg=="view-$rename"){require_once 'pages/mobility_menu.php'; require_once 'pages/view_trackie.php';}
elseif($pg=="edit-$rename"){require_once 'pages/mobility_menu.php';require_once 'pages/edittrackie_master.php';}  //edit trackie
elseif($pg=='view-call'){require_once 'pages/view_servicecall.php';}
elseif($pg=='viewservice'){require_once 'pages/viewservicedetails.php';} //view service call details 
elseif($pg=='addpayment'){require_once 'pages/addpayment.php';} //Add Payment
elseif($pg=='add-discount'){require_once 'pages/mobility_menu.php';require_once 'pages/add_discount.php';}
elseif($pg=='view-discount'){require_once 'pages/mobility_menu.php';require_once 'pages/view_discount.php';}
elseif($pg=='edit-discount'){require_once 'pages/mobility_menu.php';require_once 'pages/edit_discount.php';}  //edit discount
elseif($pg=='add-feedback'){require_once 'pages/mobility_menu.php';require_once 'pages/add_feedback.php';}
elseif($pg=='view-feedback'){require_once 'pages/mobility_menu.php';require_once 'pages/view_feedback.php';}
elseif($pg=='edit-feedback'){require_once 'pages/mobility_menu.php';require_once 'pages/edit_feedback.php';}  //edit feedback
elseif($pg=='add-category'){require_once 'pages/mobility_menu.php';require_once 'pages/add_category.php';}
elseif($pg=='view-category'){require_once 'pages/mobility_menu.php';require_once 'pages/view_category.php';}
elseif($pg=='edit-category'){require_once 'pages/mobility_menu.php';require_once 'pages/edit_category.php';}  //edit category
elseif($pg=='add-package'){require_once 'pages/mobility_menu.php';require_once 'pages/addpackage.php';}
elseif($pg=='view-package'){require_once 'pages/mobility_menu.php';require_once 'pages/viewpackage.php';}
elseif($pg=='edit-package'){require_once 'pages/mobility_menu.php';require_once 'pages/editpackage.php';} //edit Package
else{require_once 'pages/timeline.php';}
require_once '../panels/footer.php';
?>
