<?php
include_once '../../lib/system/utilities.php';
include_once '../../lib/autoload.php';
require_once '../../lib/comman_function/reports_func.php';

if (!isset($_SESSION)) {
    session_start();
}

function addsupport($details) {
 
    $issuetitle         = GetSafeValueString($details['issuetitle'], "string");
    $tickettype         = GetSafeValueString($details['tickettype'], "string");
    $notes_support      = GetSafeValueString($details['notes_support'], "string");
    $priority           = GetSafeValueString($details['priority'], "string");
    $priorityName       = GetSafeValueString($details['priorityName'], "string");
    $mailid             = GetSafeValueString($details['sentoEmail'], "string");
    $callback           = isset($details['callback']) ? $details['callback'] : '0';
    $callback           = GetSafeValueString($callback, "string");
    $phone              = isset($details['phone']) ? $details['phone'] : '0';
    $phone              = GetSafeValueString($phone, "string");
    $typeName           = GetSafeValueString($details['typeName'], "string");
    $showtouser         = GetSafeValueString($details['showtoUser'], "string");
    //$showtoUserId       = GetSafeValueString($details['showtoUserId'], "string");
    $created_by         = 1;

    /*To get userids selected in show to users box*/
 foreach($details as $key=>$value){
  if(substr($key,0,11) == "um_vehicles"){
   $key_value= explode('_',$key);
   $user_ids[] = $key_value[2];
  }
} 
/*end foreach*/

    $adddata = array(
        'issuetitle'        => $issuetitle,
        'tickettype'        => $tickettype,
        'typeName'          => $typeName,
        'notes_support'     => $notes_support,
        'priority'          => $priority,
        'priorityName'      => $priorityName,
        'created_by'        => '1',
        'mailid'            => $mailid,
        'callback'          => $callback,
        'phone'             => $phone,
        'showtouser'        => $showtouser,
        'showtouserids'     => $user_ids
    );

    $supportmanager = new SupportManager($_SESSION['customerno']);
    $supportmanager->add_support($adddata);

}

function modifysupport($details) {
    $issuetitle = GetSafeValueString($details['issuetitle'], "string");
    $tickettype = GetSafeValueString($details['tickettype'], "string");
    $typeName = GetSafeValueString($details['typeName'], "string");
    $notes_support = GetSafeValueString($details['notes_support'], "string");
    $priority = GetSafeValueString($details['priority'], "string");
    $ticketid = GetSafeValueString($details['supportid'], "string");
    $sentoEmail = GetSafeValueString($details['sentoEmail'], "string");
    $showtouser = GetSafeValueString($details['showtoUser'], "string");
    $created_by = 1;
    $modifydata = array(
        'issuetitle' => $issuetitle,
        'tickettype' => $tickettype,
        'typeName'   => $typeName,
        'notes_support' => $notes_support,
        'priority'      => $priority,
        'ticketid'      => $ticketid,
        'sentoEmail'    => $sentoEmail,
        'showtouser'    => $showtouser,
        'created_by'    => '1',
    );
    $supportmanager = new SupportManager($_SESSION['customerno']);
    $supportmanager->modissue($modifydata);
}

/*
  function modifysupport($details)
  {
  $supportmanager = new SupportManager($_SESSION['customerno']);
  $supportid = GetSafeValueString($details['supportid'], "string");
  $type = GetSafeValueString($details['issuetype'], "string");
  $notes = GetSafeValueString($details['notes_support'], "string");
  $closecheck = GetSafeValueString($details['closecheck'], "string");
  if(isset($closecheck) && $closecheck == "on")
  {
  $closecheckpush = 1;
  $closingremark = GetSafeValueString($details['closingremark'], "string");
  }
  else
  {
  $closecheckpush = 0;
  $closingremark = "";
  }
  $supportmanager->modissue($supportid, $type, $notes, $closecheckpush, $closingremark);
  }
 */

function getUser($customerno, $userid) {
    $usermanager = new UserManager();
    $user = $usermanager->get_user($customerno, $userid);
    return $user;
}

function deldriver($did) {
    $drivermanager = new DriverManager($_SESSION['customerno']);
    $driverid = GetSafeValueString($did, "string");
    $drivermanager->deldriver($driverid, $_SESSION['userid']);
}

function getvehicles() {
    $vehiclemanager = new VehicleManager($_SESSION['customerno']);
    $vehicles = $vehiclemanager->get_all_vehicles_with_drivers_by_groupname($kind = "");
    return $vehicles;
}

function getalltickettype() {
    $supportmanager = new SupportManager($_SESSION['customerno']);
    $result = $supportmanager->get_all_tickettype();
    return $result;
}

function getallpriority() {
    $supportmanager = new SupportManager($_SESSION['customerno']);
    $result = $supportmanager->get_all_priority();
    return $result;
}

function getissues() {
    $supportmanager = new SupportManager($_SESSION['customerno']);
    $issues = $supportmanager->get_all_issues();
    
    $ticketdata = array();
    if (!empty($issues)) {
        foreach ($issues as $thisissue) {
            $ticketdata[] = array(
                "ticketid" => $thisissue->ticketid,
                "title" => $thisissue->title,
                "ticket_type" => $thisissue->ticket_type,
                "sub_ticket_issue" => $thisissue->sub_ticket_issue,
                "customerid" => $thisissue->customerid,
                "create_on_date" => $thisissue->create_on_date,
                "create_by" => $thisissue->create_by,
                "allot_to" => $thisissue->allot_to,
                "created_type" => $thisissue->created_type,
                "priority" => $thisissue->priority,
                "timestamp" => $thisissue->timestamp,
                "status" => $thisissue->status,
                "getusername" => $thisissue->realname,
                "create_platform" => $thisissue->create_platform,
                "getclosedate" => $getclosedate = $supportmanager->getclosedate($thisissue->ticketid),
            );
        }

        return $ticketdata;
    }
    return null;
}

function getissue($id) {
    $id = GetSafeValueString($id, "string");
    $supportmanager = new SupportManager($_SESSION['customerno']);
    $issue = $supportmanager->get_issue($id);
    $ticketdata = array();
    if (!empty($issue)) {
        foreach ($issue as $thisissue) {
            $ticketdata[] = array(
                "ticketid" => $thisissue->ticketid,
                "title" => $thisissue->title,
                "description" => $thisissue->description,
                "ticket_type" => $thisissue->ticket_type,
                "customerid" => $thisissue->customerid,
                "create_on_date" => $thisissue->create_on_date,
                "send_mail_to" => $thisissue->send_mail_to,
                "create_by" => $thisissue->create_by,
                "created_type" => $thisissue->created_type,
                "priority" => $thisissue->priority,
                "timestamp" => $thisissue->timestamp,
                "status" => $thisissue->status,
                "getusername" => $thisissue->realname,
                "getclosedesc" => $getclosedesc = $supportmanager->getclosedesc($thisissue->ticketid),
                "getclosedate" => $getclosedate = $supportmanager->getclosedate($thisissue->ticketid),
            );
        }
        return $ticketdata;
    }
    return null;
}

function getissuedesc($tid) {
    $tid = GetSafeValueString($tid, "string");
    $supportmanager = new SupportManager($_SESSION['customerno']);
    $issuedesc = $supportmanager->get_issue_desc($tid);
    $ticketdesc = array();
    if (!empty($issuedesc)) {
        foreach ($issuedesc as $thisissuedesc) {
            $ticketdesc[] = array(
                "note" => $thisissuedesc->note,
                "timestamp" => $thisissuedesc->timestamp,
            );
        }
        return $ticketdesc;
    }
    return null;
}

function mapdriver($did, $vid) {
    $driverid = GetSafeValueString($did, "string");
    $vehicleid = GetSafeValueString($vid, "string");
    if (isset($vehicleid) && isset($driverid)) {
        $dm = new DriverManager($_SESSION['customerno']);
        $dm->mapdrivertovehicle($vehicleid, $driverid, $_SESSION['userid']);
    }
}

function demapdriver($vid) {
    $vehicleid = GetSafeValueString($vid, "string");
    if (isset($vehicleid)) {
        $dm = new DriverManager($_SESSION['customerno']);
        $dm->demapdriver($vehicleid, $_SESSION['userid']);
    }
}

function printdriversformapping() {
    $drivers = getdrivers();
    if (isset($drivers)) {
        foreach ($drivers as $driver) {
            $row = '<ul id="mapping">';
            $row .= "<li id='d_$driver->driverid'";
            if ($driver->vehicleid > 0) {
                $row .= ' class="driverassigned"';
            } else {
                $row .= ' class="driver"';
            }
            $row .= " onclick='st($driver->driverid)'>";
            $row .= $driver->drivername;
            $row .= "<input type='hidden' id='dl_$driver->driverid'";
            if ($driver->vehicleid != 0 && isset($driver->vehicleid)) {
                $row .= " value='$driver->vehicleid'>";
            }

            $row .= "</li></ul>";
            echo $row;
        }
    }
}

function printvehiclesformapping() {
    $vehicles = getvehicles();
    if (isset($vehicles)) {
        foreach ($vehicles as $vehicle) {
            $row = "<ul id='mapping'>";
            $row .= "<li id='v_$vehicle->vehicleid'";
            if ($vehicle->driverid > 0) {
                $row .= ' class="vehicleassigned"';
            } else {
                $row .= ' class="vehicle"';
            }
            $row .= " onclick='sd($vehicle->vehicleid)'>";
            $row .= $vehicle->vehicleno;
            $row .= "<input type='hidden' id='vl_$vehicle->vehicleid'";
            if ($vehicle->driverid != 0 && isset($vehicle->driverid)) {
                $row .= " value='$vehicle->driverid'>";
            }

            $row .= "</li></ul>";
            echo $row;
        }
    }
}

function drivereligibility($did) {
    $driverid = GetSafeValueString($did, "string");
    if (isset($driverid)) {
        $dm = new DriverManager($_SESSION['customerno']);
        $driver = $dm->getdriverfromvehicles($driverid);
        if (isset($driver) && $driver->driverid > 0) {
            echo ("notok");
        } else {
            echo ("ok");
        }
    } else {
        echo ("notok");
    }
}

function checkdrivername($drivername) {
    $drivername = GetSafeValueString($drivername, 'string');
    $dm = new DriverManager($_SESSION['customerno']);
    $drivers = $dm->get_all_drivers();
    $status = NULL;
    if (isset($drivers)) {
        foreach ($drivers as $thisdriver) {
            if ($thisdriver->drivername == $drivername) {
                $status = "notok";
                break;
            }
        }
        if (!isset($status)) {
            $status = "ok";
        }
    } else {
        $status = "ok";
    }
    echo $status;
}

function view_notes($id) {
    $id = GetSafeValueString($id, 'string');
    $supportmanager = new SupportManager($_SESSION['customerno']);
    $supportmanager->view_notes($id);
}

function add_note($id, $note) {
    $id = GetSafeValueString($id, 'string');
    $note = GetSafeValueString($note, 'string');
    $supportmanager = new SupportManager($_SESSION['customerno']);
     $supportmanager->add_note($id, $note);

}

function getmailid($id) {
    $id = GetSafeValueString($id, 'string');
    $supportmanager = new SupportManager($_SESSION['customerno']);
    $mail = $supportmanager->getmailid($id);
    return $mail;
}
function getOtherIssue(){
    $supportmanager = new SupportManager($_SESSION['customerno']);
    $issues = $supportmanager->get_all_other_issues();
    $ticketdata = array();
    if (!empty($issues)) {
        foreach ($issues as $thisissue) {
            $ticketdata[] = array(
                "ticketid" => $thisissue->ticketid,
                "title" => $thisissue->title,
                "ticket_type" => $thisissue->ticket_type,
                "sub_ticket_issue" => $thisissue->sub_ticket_issue,
                "customerid" => $thisissue->customerid,
                "create_on_date" => $thisissue->create_on_date,
                "create_by" => $thisissue->create_by,
                "allot_to" => $thisissue->allot_to,
                "created_type" => $thisissue->created_type,
                "priority" => $thisissue->priority,
                "timestamp" => $thisissue->timestamp,
                "status" => $thisissue->status,
                "getusername" => $thisissue->realname,
                "create_platform" => $thisissue->create_platform,
                "getclosedate" => $getclosedate = $supportmanager->getclosedate($thisissue->ticketid),
            );
        }
        return $ticketdata;
    }
    return null;
}
function getShowToUsers($ticketid){
     $supportmanager  = new SupportManager($_SESSION['customerno']);
     $showToUsersList = $supportmanager->getShowToUserList($ticketid);
    return $showToUsersList;
}

?>