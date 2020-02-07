<?php
// Add the PHP include here also, not the best place perhaps
include_once("../../config.inc.php");
// use variables defined in config
ini_set('include_path',ini_get('include_path'). PATH_SEPARATOR .$_SERVER['DOCUMENT_ROOT'] . @$subdir . '/includes/');
$includeroot= $_SERVER['DOCUMENT_ROOT'] . @$subdir ."/";
include_once("../../lib/system/utilities.php");
session_start();
//header("Cache-control: private");

error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED);
function registerSession()
{
    if (!$_SESSION['sessionteamid'])
    {
        // The session variables have not been registered. Best do it then.
        $_SESSION['sessionteamid'] = $_GLOBAL['sessionteamid'];
    }
    
    if (!$_SESSION['sessionteamrid']) // CRM rid - relational_customer
    {
        $_SESSION['sessionteamrid'] = $_GLOBAL['sessionteamrid'];
    }
    
    if (!$_SESSION['sessionteamusername'])
    {
        $_SESSION['sessionteamusername'] = $_GLOBAL['sessionteamusername'];
    }
    if (!$_SESSION['sessionteamloginname'])
    {
        $_SESSION['sessionteamloginname'] = $_GLOBAL['sessionteamloginname'];
    }
    if (!$_SESSION['db_teamloginpassword'])
    {
        $_SESSION['db_teamloginpassword'] = $_GLOBAL['db_teamloginpassword'];
    }
    if (!$_SESSION['db_teamdatabasename'])
    {
        $_SESSION['db_teamdatabasename'] = $_GLOBAL['db_teamdatabasename'];
    }

}

registerSession();
// Store the values from the config file.
Setdb_databasename($db_databasename);
Setdb_hostname($db_hostname);
Setdb_loginname($db_loginname);
Setdb_loginpassword($db_loginpassword);

// DB Stuff

function Getdb_hostname()
{
    return $_SESSION["db_resellerhostname"];
}
function Getdb_loginname()
{
    return $_SESSION["db_teamloginname"];
}
function Getdb_loginpassword()
{
    return $_SESSION["db_teamloginpassword"];
}
function Getdb_databasename()
{
    return $_SESSION["db_teamdatabasename"];
}

function Setdb_hostname( $value )
{
    $_SESSION["db_teamhostname"] = $value;
}
function Setdb_loginname($value)
{
    $_SESSION["db_teamloginname"] = $value;
}
function Setdb_loginpassword($value)
{
    $_SESSION["db_teamloginpassword"] = $value;
}
function Setdb_databasename($value)
{
    $_SESSION["db_teamdatabasename"] = $value;
}

// Regular settings


function GetLoginUser()
{
	return $_SESSION["sessionteamloginuser"];
}
function GetCustomerno()
{
	return false;
}
function SetLoginUser( $PassedLoginUser )
{
	$_SESSION["sessionteamloginuser"] = $PassedLoginUser;
}
function setDepartment($department){
    $_SESSION["department"] = $department;
}
function setRoleId($role_id){
    $_SESSION["role_id"] = $role_id;
}
function setCompRoleId($comp_roleId){
    $_SESSION["company_roleId"] = $comp_roleId;
}
function checkUserType($department=0,$role=0,$company_role=null){
    if(($_SESSION["department"]==$department||$department==0)&&($_SESSION["role_id"]==$role||$role==0)&&($_SESSION["company_roleId"]==$company_role||$company_role==null)){
        return true;
    }
    return false;
}
function GetUsername()
{
	return $_SESSION["sessionteamusername"];
}
function SetUsername( $PassedUsername )
{
	$_SESSION["sessionteamusername"] = $PassedUsername;
}

function GetLoggedInUserId()
{
	return $_SESSION["sessionteamid"];
}
function SetLoggedInUserId( $PassedUserId )
{
	$_SESSION["sessionteamid"] = $PassedUserId;
}

function GetLoggerdInrid()
{
	return $_SESSION["sessionteamrid"];
}

function SetLoggedInrid($Passedrid)
{
	$_SESSION["sessionteamrid"] = $Passedrid;
}

function GetRole()
{
	return $_SESSION["sessionrole"];
}
function SetRole( $PassedUserId )
{
	$_SESSION["sessionrole"] = $PassedUserId;
}

function IsService()
{
    return (GetRole()=='Service');
}

function IsSales()
{
    return (GetRole()=='Sales');
}
function IsCRM()
{
    return (GetRole()=='CRM');
}
function IsHead()
{
    return (GetRole()=='Head');
}
function IsAdmin()
{
    return (GetRole()=='Admin');
}
function IsData()
{
    return (GetRole()=='Data');
}
function IsDistributor()
{
    return (GetRole()=='Distributor');
}
function IsDealer()
{
    return (GetRole()=='Dealer');
}
function IsRepair()
{
    return (GetRole()=='Repair');
}
function IsLoggedIn()
{
	return (GetLoggedInUserId()!=NULL);
}

function ClearSession()
{
    unset($_SESSION["sessionteamrid"]);
    unset($_SESSION["sessionteamid"]);    
    unset($_SESSION["sessionteamusername"]);
    unset($_SESSION["sessionteamloginuser"]);
    unset($_SESSION["sessiondistributorid"]);
    registerSession();
}
?>
