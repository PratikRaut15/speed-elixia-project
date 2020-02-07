<?php

// $status   //live =0 & offline=1
//error_reporting(E_ALL ^ E_STRICT);
//ini_set('display_errors', 'On');
require_once 'trips_function.php';
$action = exit_issetor($_REQUEST['action'], failure('Action not found')); //'Action not found'
/* Login */
if ($action == 'pullcredentials') {
    $username = exit_issetor($_REQUEST['username'], json_encode(array('result' => 'failure'))); //'Username not found'
    $password = exit_issetor($_REQUEST['password'], json_encode(array('result' => 'failure'))); //'Password not found'
    $um = new UserManager();
    $dpd = $um->get_person_details($username, $password); //delivery person data
    if (!empty($dpd)) {
        echo success_json($dpd);
        exit;
    }else{
        echo failure('Please check username or password');
        exit;
    }
} else if ($action == 'pulltripdetails') { // pull all shops 
    $userkey = exit_issetor($_REQUEST['userkey'], json_encode(array('result' => 'failure'))); //'Userkey not found'
    $vehicleid = exit_issetor($_REQUEST['vehicleid'], json_encode(array('result' => 'failure'))); //'Userkey not found'
    $um = new UserManager();
    $dpd = $um->get_person_details_by_key($userkey); // person data
    $saleid = $dpd['userid'];
    if (empty($dpd)) {
        echo failure('No Userdata');
        exit;
    }
    $dm1 = new Trips($dpd['customerno'], $dpd['userid']);
    $alldata = $dm1->gettripdetails($vehicleid); // vehicleid  
    if (empty($alldata)) {
        echo failure('No tripdata available.');
        exit;
    }else{
        echo success_json($alldata);
        exit;
    }
}else{
    echo failure('Action not listed');
    exit;
}
?>