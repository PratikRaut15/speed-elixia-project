<?php
include_once("session.php");
include_once("../lib/system/utilities.php");
include("loginorelse.php");
include_once("../constants/constants.php");
include_once("../db.php");
include_once("../lib/system/DatabaseManager.php");
include_once("../lib/system/Date.php");
include_once("../lib/system/Sanitise.php");
include_once("../lib/components/gui/objectdatagrid.php");
include_once("../lib/model/VODevices.php");
function savefile( $db, $customerid, $formfield, $savefield )
{
    if(isset($_FILES[$formfield]['name']) && $_FILES[$formfield]['name']!= "")
    {
        $uploaddir = "../customer/". $customerid."/images" ;
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

        $sql = sprintf( "Update customer set `%s`='%s' WHERE customerno = %d LIMIT 1",
        $savefield,$rlogo, $customerid);
        $db->executeQuery($sql);
    }
}

$customerid = GetSafeValueString( isset($_GET["cid"])?$_GET["cid"]:$_POST["customerid"], "long");
$teamid = GetLoggedInUserId();
$db = new DatabaseManager();

if(isset($_POST["register"]))
{
    $contract = GetSafeValueString($_POST["ccontract"], "string");        
    $crate = GetSafeValueString($_POST["crate"], "string");            
    $noofcontracts = $_POST['noofdev'];
    $key=1;
    while($key<=$noofcontracts)
    {
        $devicekey = mt_rand();
        $today = date("Y-m-d H:i:s");        
        $SQL = sprintf( "INSERT INTO devices (
        `customerno` ,`devicekey`,`registeredon`,`contract`,`rate`)
        VALUES (
        '%d', '%s', '%s', '%d', '%d')",
        $customerid, $devicekey, Sanitise::DateTime($today), $contract, $crate);
        $db->executeQuery($SQL);
        $key++;
    }
}    

if(isset($_POST["save"]))
{
    // Attempting to save changes now.
    // Save Banner if required.
    // LOGO
    savefile($db, $customerid, "customerlogo", "logoimage");
    // Save Banner if required
    savefile($db, $customerid, "customerbanner", "bannerimage");

    // then save record.
    $notes = GetSafeValueString($_POST["customernotes"], "string");
    $custname = GetSafeValueString($_POST["customername"], "string");
    $custcompany = GetSafeValueString($_POST["customercompany"], "string");
    $custaddress1 = GetSafeValueString($_POST["customeraddress1"], "string");
    $custaddress2 = GetSafeValueString($_POST["customeraddress2"], "string");
    $custcity = GetSafeValueString($_POST["customercity"], "string");
    $custstate = GetSafeValueString($_POST["customerstate"], "string");
    $custzip = GetSafeValueString($_POST["customerzip"], "string");
    $custphone = GetSafeValueString($_POST["customerphone"], "string");
    $custcell = GetSafeValueString($_POST["customercell"], "string");
    $custemail = GetSafeValueString($_POST["customeremail"], "string");
    $custnotes = GetSafeValueString($_POST["customernotes"], "string");
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

    
    $SQL = sprintf( "UPDATE customer SET
            `customername`='%s',
            `customercompany`='%s',
            `customeradd1`='%s',
            `customeradd2`='%s',
            `customercity`='%s',
            `customerstate`='%s',
            `customerzip`='%s',
            `customerphone`='%s',
            `customercell`='%s',
            `customeremail`='%s',
            `notes`='%s',
            `itemdelivery`='%s',
            `fencing`='%s',
            `elixiacode`='%s',
            `messaging`='%s',
            `service`='%s',
			`istrack`='%s',
			`isservice`='%s'            
            WHERE customerno = %d" ,
                $custname,
                $custcompany,
                $custaddress1,
                $custaddress2,
                $custcity,
                $custstate,
                $custzip,
                $custphone,
                $custcell,
                $custemail,
                $notes,
                $itemdel,
                $geofencing,
                $elixiacode,
                $messaging,
                $service,
				$istrack,
				$isservice,            
                $customerid);

    $db->executeQuery($SQL);

    header("Location: customers.php");
}
$SQL = sprintf("SELECT c.* from customer c 
where c.customerno = '%d' LIMIT 1 ",$customerid);
$db->executeQuery($SQL);
while($row = $db->get_nextRow())
{
    $custname = $row["customername"];
    $custcompany = $row["customercompany"];
    $custaddress1 = $row["customeradd1"];
    $custaddress2 = $row["customeradd2"];
    $custcity = $row["customercity"];
    $custstate = $row["customerstate"];
    $custzip = $row["customerzip"];
    $custphone = $row["customerphone"];
    $custcell = $row["customercell"];
    $custemail = $row["customeremail"];
    $logoimage = $row["logoimage"];
    $bannerimage = $row["bannerimage"];
    $itemdel = $row["itemdelivery"];
    $fencing = $row["fencing"];
    $elixiacode = $row["elixiacode"];
    $messaging = $row["messaging"];    
    $service = $row["service"];
	$istrack = $row["istrack"];
	$isservice = $row["isservice"];        
    $notes = $row["notes"];
}

include("header.php");
?>

<?php
if(IsHead())
{
?>
<div class="panel">
<div class="paneltitle" align="center">Update Customer</div>
<div class="panelcontents">

<p>Please adjust any inaccurate details. Understand that changing these values will have a very real and immediate impact on the customer.</p>
<form method="post" action="modifycustomer.php"  enctype="multipart/form-data">
<input type="hidden" name="customerid" value="<?php echo( $customerid ); ?>"/>
<table width="100%">
    <tr>
    <td>Customer</td><td><input name="customername" type="text" value="<?php echo($custname); ?>" /> </td>
    </tr>
    <tr>
    <td>Company</td><td><input name="customercompany" type="text"  value="<?php echo($custcompany); ?>" /></td>
    </tr>
    <tr>
    <td>Address</td><td><input name="customeraddress1" type="text"  value="<?php echo($custaddress1); ?>" /></td>
    </tr>
    <tr>
    <td></td><td><input name="customeraddress2" type="text"  value="<?php echo($custaddress2); ?>" /></td>
    </tr>
    <tr>
    <td>City</td><td><input name="customercity" type="text"  value="<?php echo($custcity); ?>" /></td>
    </tr>
    <tr>
    <td>State</td><td><input name="customerstate" type="text"  value="<?php echo($custstate); ?>" /></td>
    </tr>
    <tr>
    <td>Zip</td><td><input name="customerzip" type="text"  value="<?php echo($custzip); ?>" /></td>
    </tr>
    <tr>
    <td>Email</td><td><input name="customeremail" type="text"  value="<?php echo($custemail); ?>" /></td>
    </tr>
    <tr>
    <td>Phone</td><td><input name="customerphone" type="text"  value="<?php echo($custphone); ?>" /></td>
    </tr>
    <tr>
    <td>Cell</td><td><input name="customercell" type="text"  value="<?php echo($custcell); ?>" /></td>
    </tr>
    <tr>
    <td>Logo</td><td>
    <img src = "../customer/<?php echo( $customerid ); ?>/images/<?php echo($logoimage); ?>" style="height:32px;width:auto;" /><br/>
    <input name="customerlogo" type="file"/>Size Constraints</td>
    </tr>
    <tr>
    <td>Banner</td><td><img src = "../customer/<?php echo( $customerid ); ?>/images/<?php echo($bannerimage); ?>" style="height:32px;width:auto;" /><br/>
    <input name="customerbanner" type="file"/>Size Constraints</td>
    </tr>
    <tr>
    <td width="25%">Notes</td><td><textarea name="customernotes" cols="80" rows="6" ><?php echo($notes); ?></textarea>Any notes about this customer which may be useful later on.</td>
    </tr>
    <?php
    } 
    if(IsHead())
    {
    ?>
    <tr><td colspan="2"><h3>Operations</h3></td></tr>    
    <tr>
    <td>Item Delivery</td>
    <td>
        <input type="checkbox" name="item_del" id="item_del" <?php if($itemdel == 1) echo("checked"); ?>>
    </td>        
    </tr>
    <tr>
    <td>Geo Fencing</td>
    <td>
        <input type="checkbox" name="geofencing" id="geofencing" <?php if($fencing == 1) echo("checked"); ?>>
    </td>        
    </tr>
    <tr>
    <td>Elixia Code</td>
    <td>
        <input type="checkbox" name="elixiacode" id="elixiacode" <?php if($elixiacode == 1) echo("checked"); ?>>
    </td>        
    </tr>
    <tr>
    <td>Messaging System</td>
    <td>
        <input type="checkbox" name="messaging" id="messaging" <?php if($messaging == 1) echo("checked"); ?>>
    </td>        
    </tr>                
    <tr>
    <td>Service Call</td>
    <td>
        <input type="checkbox" name="service" id="service" <?php if($service == 1) echo("checked"); ?>>
    </td>        
    </tr> 
	<tr>
        <td><strong>Module Track</strong></td><td>
        <input id="istrack" name="istrack" type="checkbox" <?php if($istrack == 1) echo("checked"); ?>/></td>
        </tr>
		<tr>
        <td><strong>Module Service</strong></td><td>
        <input id="isservice" name="isservice" type="checkbox" <?php if($isservice == 1) echo("checked"); ?>/></td>
        </tr>                     
</table>
<input type="submit" name="save" value="Save Changes" />
</form>

<div id="registeradevice">
    <br></br>
    <div class="paneltitle" align="center">Register Device</div>
<form method="post" action="modifycustomer.php"  enctype="multipart/form-data">
<input type="hidden" name="customerid" value="<?php echo( $customerid ); ?>"/>
    <table width="100%">
        <tr>
            <td colspan="2">
                <h3> Register a Device </h3>
            </td>
        </tr>
        <tr>
            <td>No Of Devices</td>
            <td><input type="number" name="noofdev" min="1" max="50" /></td>
        </tr>
        <tr>
        <td>Contract </td>
        <td> <input name="ccontract" id="ccontract" type="text" size="3" maxlength="3" required/>
        <em>(in months)</em>
        </td>
        </tr>            
        
        <tr>
        <td>Rate </td>
        <td> <input name="crate" id="crate" type="text" size="7" maxlength="7" required/>
        <em>/ month</em>
        </td>
        </tr>                    
    </table>
</div>
<br/>
<input type="submit" name="register" value="Register" />
</form>
</div>
</div>
<?php
    }
?>    
<?php
//Datagtrid
$devices = array();
$itemno = 1;
$db = new DatabaseManager();
$SQL = sprintf("SELECT * FROM devices WHERE customerno = %d", $customerid);
$db->executeQuery($SQL);
if ($db->get_rowCount() > 0) {
    while ($row = $db->get_nextRow())
    {
        $device = new VODevices();
        $device->itemno = $itemno;
        $itemno++;
        $device->deviceid = $row["deviceid"];
        $device->customerno = $row["customerno"];
        $device->devicename = $row["devicename"];
        $device->devicekey = $row["devicekey"];
        if($row["isregistered"] == 1)
        {
            $device->isregistered = "YES";
        }
        else
        {
            $device->isregistered = "NO";            
        }        
        $device->androidid = $row["androidid"];
        if($row["trackeeid"] == 0)
        {
            $device->trackeeassigned = "Not yet";
        }
        else
        {
            $device->trackeeassigned = $row["trackeeid"];            
        }
        $device->phoneno = $row["phoneno"];                                
        $device->devicelat = $row["devicelat"];
        $device->devicelong = $row["devicelong"];
        $device->lastupdated = $row["lastupdated"];                                
        $device->registeredon = $row["registeredon"];
        $device->registrationapprovedon = $row["registrationapprovedon"];
        $device->contract = $row["contract"];
        $device->rate = $row["rate"];        
        if($row["registrationapprovedon"] != "0000-00-00 00:00:00")
        {
            if($row["isregistered"] == 1)
            {
                $device->status = "Valid";
            }
            else
            {
                $device->status = "Expired";
            }
        }
        else
        {
            $device->status = "Unregistered";            
        }
        $devices[] = $device;        
    }    
}

?>
<div class="panel">
<div class="paneltitle" align="center">My Devices</div>
<div class="panelcontents">
<?php
$dg = new objectdatagrid( $devices );
$dg->AddColumn("Device Key", "devicekey");
$dg->AddColumn("Registered", "isregistered");
$dg->AddColumn("Registration Approved", "registrationapprovedon");
$dg->AddColumn("Last Updated", "lastupdated");
$dg->AddColumn("Trackee Assigned", "trackeeassigned");
$dg->AddColumn("Contract", "contract");
$dg->AddColumn("Rate per month", "rate");
$dg->AddColumn("Status", "status");
$dg->SetNoDataMessage("No Devices");
$dg->AddIdColumn("itemno");
$dg->Render();
?>
</div>
</div>
<br/>

<?php
include("footer.php");
?>