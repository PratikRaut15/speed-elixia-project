<?php
include_once("session.php");
include_once("../db.php");
include_once("../lib/system/utilities.php");
include("loginorelse.php");
include_once("../constants/constants.php");
include_once("../lib/system/DatabaseManager.php");
include_once("../lib/system/Date.php");
include_once("../lib/system/Sanitise.php");
$db = new DatabaseManager();

if (IsSourcing() || IsHead()) {
	$msg = "<P>You are an authorized user</p>";
} else {
	header( "Location: index.php");
	exit;
}

if(isset($_POST))
{
    foreach($_POST as $key => $value )
    {
        if( substr($key, 0,3) == "grp")
        {
            $elixiaids[] = substr($key,3);
        }
    }
    if(isset($elixiaids))
    {
        foreach($elixiaids as $thisid)
        {
            $SQL = sprintf("DELETE FROM purchase WHERE purchaseid=%d", Sanitise::Long($thisid));
            $db->executeQuery($SQL);            
        }    
    }
}

header("Location: purchase.php");
exit;
?>