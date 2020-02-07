<?php

//error_reporting(E_ALL ^ E_STRICT);
//ini_set('display_errors', 'On');

require_once 'support_functions.php';
  /*
    $action="addticket";
    $userkey ="68132817";
    $issuetitle="testedit";
    $tickettype=4;
    $estdate="2015-09-26";
    $timeslot =1;
    $vehicleid="4502";
    $description="edit testdescription";
    $priority=1;
  */
    $issuetitle = ri($_REQUEST['issuetitle']);  //issuetitle
    $tickettype = ri($_REQUEST['tickettype']);  //tickettype
    $description = ri($_REQUEST['description']);  //Description
    $priority = ri($_REQUEST['priority']);  //priority

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
    } else {
        echo failure('Please check username or password');
        exit;
    }
} else if ($action == "addticket") {  // add ticket   
    $userkey = exit_issetor($_REQUEST['userkey'], json_encode(array('result' => 'Userkey not found'))); //'Userkey not found'
    $um = new UserManager();
    $dpd = $um->get_person_details_by_key($userkey); // person data
    if (empty($dpd)) {
        echo failure('No Userdata');
        exit;
    }
    $companyname = $um->getCompanyName($dpd['customerno']);
    $today = date("d-m-Y");
    
    $adddata = array(
        'issuetitle' => $issuetitle,
        'tickettype' => $tickettype,
        'notes_support' => $description,
        'priority' => $priority,
        'created_by' => '1',
        'customerno' => $dpd['customerno'],
        'userid' => $dpd['userid'],
        'companyname' => $companyname
    );
    $support = new SupportManager($dpd["customerno"]);
    $result = $support->add_supportapi($adddata);
    if (empty($result)) {
        echo failure('No Ticket Created');
        exit;
    }else{
        echo success_json($result);
        exit;
    }
}else {
    echo failure('Action not listed');
    exit;
}
?>