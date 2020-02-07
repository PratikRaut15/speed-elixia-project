<?php
include '../../lib/bo/SmsTrackManager.php';
include '../../lib/system/utilities.php';
if(!isset($_SESSION))
{
    session_start();
}
function addsmstrack($name, $phone)
{
    $name = GetSafeValueString($name,"string");
    $phoneno = GetSafeValueString($phone,"string");
    $smsmanager = new SmsTrackManager($_SESSION['customerno']);
    $smsmanager->add_smstrack($name, $phoneno, $_SESSION['userid']); 
}
function editsmstrack($smstrackid, $name, $phoneno)
{
    $smstrackid = GetSafeValueString($smstrackid,"string");
    $name = GetSafeValueString($name,"string");
    $phoneno = GetSafeValueString($phoneno,"string");
    $smsmanager = new SmsTrackManager($_SESSION['customerno']);
    $smsmanager->edit_smstrack($smstrackid, $name, $phoneno, $_SESSION['userid']); 
}
function edituserphone($userid, $name, $phoneno)
{
    $userid = GetSafeValueString($userid,"string");
    $name = GetSafeValueString($name,"string");
    $phoneno = GetSafeValueString($phoneno,"string");
    $smsmanager = new SmsTrackManager($_SESSION['customerno']);
    $smsmanager->edit_userphone($userid, $name, $phoneno, $_SESSION['userid']); 
}
function check_phone_no($phoneno)
{
    $phoneno = GetSafeValueString($phoneno, 'string');
    $smsmanager = new SmsTrackManager($_SESSION['customerno']);
    $phones = $smsmanager->get_all_simphone();
    $status = NULL;
    if(isset($phones))
    {
        foreach($phones as $thisphone)
        {
            if($thisphone->phoneno == $phoneno)
            {
                $status = "notok";
                break;
            }
        }
        if(!isset($status))
        {
            $status = "ok";
        }
    }   
    else
    {
        $status = "ok";
    }
    echo $status;
}

function delsmstrack($trackid)
{
    $smsmanager = new SmsTrackManager($_SESSION['customerno']);
    $smsmanager->DeleteSmsTrack($trackid, $_SESSION['userid']);
}

function getsmstrack()
{
    $smsmanager = new SmsTrackManager($_SESSION['customerno']);
    $smsdata = $smsmanager->get_all_simdata();
    return $smsdata;
}

function getsmstrackbyid($trackid)
{
    $smsmanager = new SmsTrackManager($_SESSION['customerno']);
    $smsdata = $smsmanager->get_simdata($trackid);
    return $smsdata;
}

function getuserphonebyid($trackid)
{
    $smsmanager = new SmsTrackManager($_SESSION['customerno']);
    $smsdata = $smsmanager->get_userdata($trackid);
    return $smsdata;
}

function getuserphone()
{
    $smsmanager = new SmsTrackManager($_SESSION['customerno']);
    $userdata = $smsmanager->get_all_userphone();
    return $userdata;
}
?>