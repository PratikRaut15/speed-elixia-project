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

if (IsHead()) {
	$msg = "<P>You are an authorized user</p>";
} else {
	header( "Location: index.php");
	exit;
}

function savefile( $db, $customerid, $formfield, $savefield )
{
    if(isset($_FILES[$formfield]['name']) && $_FILES[$formfield]['name']!= "")
    {
        $uploaddir = "../customer/". $customerid. "/images" ;
        $allowed_ext = "jpg, gif, png, jpeg" ;
        $extension = pathinfo($_FILES[$formfield]['name']);
        $extension = $extension['extension'];
        $allowed_paths = explode(", ", $allowed_ext);
        for($i = 0; $i < count($allowed_paths); $i++) {
         if ($allowed_paths[$i] == "$extension") {
         $ok = "1";
         }
        }
        if(is_uploaded_file($_FILES[$formfield]['tmp_name']))
        {
            move_uploaded_file($_FILES[$formfield]['tmp_name'],$uploaddir.'/'.$_FILES[$formfield]['name']);
        }
        $target_path = $uploaddir.'/'.$_FILES[$formfield]['name'];
        $rlogo=$_FILES[$formfield]['name'];

        $sql = sprintf( "UPDATE customer SET `%s`='%s' WHERE customerno = %d LIMIT 1",
        $savefield,$rlogo, $customerid);
        $db->executeQuery($sql);
    }
}

// Based on details passed in, Create the customer.
$custname = GetSafeValueString($_POST["cname"], "string");
$custcompany = GetSafeValueString($_POST["ccompany"], "string");
$custaddress1 = GetSafeValueString($_POST["cadd1"], "string");
$custaddress2 = GetSafeValueString($_POST["cadd2"], "string");
$custcity = GetSafeValueString($_POST["ccity"], "string");
$custstate = GetSafeValueString($_POST["cstate"], "string");
$custzip = GetSafeValueString($_POST["czip"], "string");
$custphone = GetSafeValueString($_POST["cphone"], "string");
$custcell = GetSafeValueString($_POST["ccell"], "string");
$custemail = GetSafeValueString($_POST["cemail"], "string");
$custnotes = GetSafeValueString($_POST["cnotes"], "string");
$teamid = GetSafeValueString($_POST["teamid"], "string");

$primaryusername = GetSafeValueString($_POST["cprimaryname"], "string");
$primaryuserlogin = GetSafeValueString($_POST["cprimaryusername"], "string");
$primaryuserpassword = GetSafeValueString($_POST["cprimarypassword"], "string");
$supportuserlogin = GetSafeValueString($_POST["csupportusername"], "string");
$supportuserpassword = GetSafeValueString($_POST["csupportpassword"], "string");
$ref = GetSafeValueString($_POST["ref"], "string");
$itemdel = 0;
if(isset($_POST["item_del"]) && $_POST["item_del"] == "on")
{
    $itemdel = 1;
}
$geofencing = 0;
if(isset($_POST["geofencing"]) && $_POST["geofencing"] == "on")
{
    $geofencing = 1;
}
$elixiacode = 0;
if(isset($_POST["elixiacode"]) && $_POST["elixiacode"] == "on")
{
    $elixiacode = 1;
}
$messaging = 0;
if(isset($_POST["messaging"]) && $_POST["messaging"] == "on")
{
    $messaging = 1;
}
$service = 0;
if(isset($_POST["service"]) && $_POST["service"] == "on")
{
    $service = 1;
}

// is track and is service
$istrack=0;
if(isset($_POST["istrack"]) && $_POST["istrack"] == "on")
{
    $istrack = 1;
}
$isservice=0;
if(isset($_POST["isservice"]) && $_POST["isservice"] == "on")
{
    $isservice = 1;
}



$date = new Date();
$today = $date->add_hours(date("Y-m-d"), 0);

$SQL = sprintf( "INSERT INTO customer (
`customername` ,`customercompany` ,`customeradd1` ,`customeradd2` ,`customercity` ,`customerstate` ,`customerzip` ,
`customerphone` ,`customercell` ,`customeremail` ,
`dateadded` , `teamid` , `notes` , `itemdelivery` , `fencing` , `elixiacode` , `messaging`,referral, `service`,`istrack`,`isservice`
)
VALUES (
'%s', '%s', '%s', '%s', '%s', '%s', '%s',
'%s', '%s', '%s',
 '%s', '%d', '%s', '%d', '%d', '%d', '%d','%s','%d', '%d', '%d')" ,
    $custname,$custcompany,$custaddress1,$custaddress2,$custcity,$custstate,$custzip,
    $custphone,$custcell,$custemail, Sanitise::Date($today),
    $teamid, $custnotes, $itemdel, $geofencing, $elixiacode, $messaging, $ref, $service,$istrack,$isservice);

$db->executeQuery($SQL);
$customerid = $db->get_insertedId();

// If the customer record was saved OK, Create their directories.
$relativepath = "..";
if(!is_dir( $relativepath.'/customer/'.$customerid ))
{
    // Directory doesn't exist.
    mkdir($relativepath.'/customer/'.$customerid,0777, true ) or die("Could not create directory");
}
if(!is_dir( $relativepath.'/customer/'.$customerid.'/images' ))
{
    // Directory doesn't exist.
    mkdir($relativepath.'/customer/'.$customerid.'/images',0777, true ) or die("Could not create directory");
}
if(!is_dir( $relativepath.'/customer/'.$customerid.'/xml' ))
{
    // Directory doesn't exist.
    mkdir($relativepath.'/customer/'.$customerid.'/xml',0777, true ) or die("Could not create directory");
}

// Once directories are created, upload logo and banner.
// LOGO
    savefile($db, $customerid, "clogo", "logoimage");
    // Save Banner if required
    savefile($db, $customerid, "cbanner", "bannerimage");
    
// Add the primary user.
$userkey1 = mt_rand();    
$sql ="INSERT into user (customerno, realname, username, password, role, userkey)
VALUES ($customerid,'$primaryusername','$primaryuserlogin', sha1('$primaryuserpassword'),'Administrator', $userkey1)";
$db->executeQuery($sql);

// Add the support user.
$userkey2 = mt_rand();
$sql ="INSERT into user (customerno, realname, username, password, role, userkey)
VALUES ($customerid, 'Elixir', '$supportuserlogin', sha1('$supportuserpassword'),'Administrator', $userkey2)";
$db->executeQuery($sql);

// Set the default custom fields
//Customer DOES NOT have custom fileds defined yet so insert them
$db->executeQuery("INSERT into customfield (customerno, defaultname, usecustom)
    VALUES ($customerid,'Tracker',0)");
$db->executeQuery("INSERT into customfield (customerno, defaultname, usecustom)
    VALUES ($customerid,'Trackee',0)");
$db->executeQuery("INSERT into customfield (customerno, defaultname, usecustom)
    VALUES ($customerid,'Item',0)");
$db->executeQuery("INSERT into customfield (customerno, defaultname, usecustom)
    VALUES ($customerid,'Service Call',0)");
$db->executeQuery("INSERT into customfield (customerno, defaultname, usecustom)
    VALUES ($customerid,'Checkpoint',0)");
$db->executeQuery("INSERT into customfield (customerno, defaultname, usecustom)
    VALUES ($customerid,'SerUserField1',0)");
$db->executeQuery("INSERT into customfield (customerno, defaultname, usecustom)
    VALUES ($customerid,'SerUserField2',0)");
$db->executeQuery("INSERT into customfield (customerno, defaultname, usecustom)
    VALUES ($customerid,'ClientExtra',0)");
$db->executeQuery("INSERT into customfield (customerno, defaultname, usecustom)
    VALUES ($customerid,'CallExtra1',0)");
$db->executeQuery("INSERT into customfield (customerno, defaultname, usecustom)
    VALUES ($customerid,'CallExtra2',0)");

$db->executeQuery("INSERT into alerts (alertname, alerttype, customerno)
    VALUES ('depart','sms',$customerid)");
$db->executeQuery("INSERT into alerts (alertname, alerttype, customerno)
    VALUES ('depart','email',$customerid)");
$db->executeQuery("INSERT into alerts (alertname, alerttype, customerno)
    VALUES ('thankyou','sms',$customerid)");
$db->executeQuery("INSERT into alerts (alertname, alerttype, customerno)
    VALUES ('thankyou','email',$customerid)");
$db->executeQuery("INSERT into alerts (alertname, alerttype, customerno)
    VALUES ('panic','sms',$customerid)");

header("Location: customers.php");
?>