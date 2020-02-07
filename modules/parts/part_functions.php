<?php
include_once '../../lib/bo/PartManager.php';
include_once '../../lib/system/utilities.php';
if(!isset($_SESSION))
{
    session_start();
}
function addpart($data)
{
    $partmanager = new PartManager($_SESSION['customerno']);
    $partmanager->add_part($data, $_SESSION['userid']); 
}
function editpart($data)
{
    $partmanager = new PartManager($_SESSION['customerno']);
    $partmanager->edit_part($data, $_SESSION['userid']); 
}
function getpart()
{
    $PartManager = new PartManager($_SESSION['customerno']);
    $parts = $PartManager->get_all_part();
    return $parts;
}

function delpart($partid)
{
    $PartManager = new PartManager($_SESSION['customerno']);
    $PartManager->DeletePart($partid, $_SESSION['userid']);
}

function getpartsbyid($id)
{
    $PartManager = new PartManager($_SESSION['customerno']);
    $parts = $PartManager->get_part($id);
    return $parts;
}
?>