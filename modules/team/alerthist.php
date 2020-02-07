<?php 
include("header.php");
include_once("session.php");
include("loginorelse.php");
include_once("../../constants/constants.php");
include_once("db.php");
include_once("../../lib/components/gui/datagrid.php");
include_once("../../lib/system/DatabaseManager.php");
include '../reports/reports_common_functions.php';
 ?>
<div class="panel">
    <div class="paneltitle" align="center">Alert History</div>
<div class="panelcontents">
<form action="alerthist.php" method="POST">
<table class="sortable" width='100%' align='center' cellpadding='0' cellspacing='0'>
    <tbody>

<?php

    
class testing{
    
}
$db = new DatabaseManager();
$SQL = sprintf("SELECT customerno FROM ".DB_PARENT.".customer");
$db->executeQuery($SQL);
$customers = Array();
if ($db->get_rowCount() > 0) {
    while ($row = $db->get_nextRow())
    {
        $customer = new testing();
        $customer->customerno = $row["customerno"];        
        $customers[] = $customer;        
    }    
}
$customeropt = "";
foreach($customers as $customer){
    if(isset($_POST['customerno']) && $customer->customerno == $_POST['customerno'])
    {
    $customeropt .= "<option value = '$customer->customerno' selected = 'selected'>$customer->customerno</option>";
    }
    else
    {
    $customeropt .= "<option value = '$customer->customerno'>$customer->customerno</option>";
    }
}
$status = get_all_alerttype();
$statusopt = "";
foreach ($status as $type) 
{
    if(isset($_POST['alerttype']) && $type->id == $_POST['alerttype'])
    {
        $statusopt .= "<option value = '$type->id' selected = 'selected'>".ucfirst($type->status)."</option>";
    }
    else
    {
        $statusopt .= "<option value='$type->id'>".ucfirst($type->status)."</option>";
    }
}

if(isset($_POST['customerno']) && $_POST['customerno'] != '-1'){
            $devices = getvehiclesforteam($_POST['customerno']);
            if(isset($devices)){
            $devicesopt = "";
            foreach ($devices as $device) 
            {
                if(isset($_POST['vehicleid']) && $device->vehicleid == $_POST['vehicleid'])
                {
                    $devicesopt .= "<option value = '$device->vehicleid' selected = 'selected'>$device->vehicleno</option>";
                }
                else
                {
                    $devicesopt .= "<option value = '$device->vehicleid'>$device->vehicleno</option>";
                }
            }
            }
            $checkpoints = get_all_chkforteam($_POST['customerno']);
            if(isset($checkpoints)){
            $chkopt = "";
            foreach ($checkpoints as $checkpoint) 
            {
                if(isset($_POST['chkid']) && $checkpoint->checkpointid == $_POST['chkid'] && $_POST['alerttype'] == '2')
                {
                    $chkopt .= "<option value = '$checkpoint->checkpointid' selected = 'selected'>$checkpoint->cname</option>";
                }
                else
                {
                    $chkopt .= "<option value='$checkpoint->checkpointid'>$checkpoint->cname</option>";
                }
            }
            }
            $fences = get_all_fenceteam($_POST['customerno']);
            if(isset($fences)){
            $fenceopt = "";
            foreach ($fences as $fence) 
            {
                if(isset($_POST['fenceid']) && $fence->fenceid == $_POST['fenceid'] && $_POST['alerttype'] == '3')
                {
                    $fenceopt .= "<option value = '$fence->fenceid' selected = 'selected'>$fence->fencename</option>";
                }
                else
                {
                    $fenceopt .= "<option value='$fence->fenceid'>$fence->fencename</option>";
                }
            }
            }
}

if(!isset($_POST['SDate'])) { $Sdate = getdate_IST(); } else $Sdate = strtotime ($_POST['SDate']);
?>


<tr>
        <td>
            <select id="customerno" name="customerno">
            <option value="-1">Please Select Customer</option>
            <?php echo $customeropt;?>
            </select>	
	</td>
	<td>
            <select id="alerttype" name="alerttype">
            <option value="-1">All Types</option>
            <?php echo $statusopt;?>
            </select>	
	</td>
	
    <td>
        <select id="vehicleid" name="vehicleid">
        <option value="">All Vehicles</option>
        <?php echo $devicesopt;?>
        </select>
    </td>
    <td>
        <?php if($_POST['alerttype'] == '2'){ ?>
        <select id="chkid" name="chkid">
        <option value="">All Checkpoints</option>
        <?php }
              else{ ?>
        <select id="chkid" name="chkid" style="display: none">
        <option value="">All Checkpoints</option>
        <?php } echo $chkopt;?>
        </select>
        <?php if($_POST['alerttype'] == '3'){ ?>
        <select id="fenceid" name="fenceid">
        <option value="">All Fences</option>
        <?php }
            else { ?>
        <select id="fenceid" name="fenceid" style="display: none">
        <option value="">All Fences</option>
        <?php } echo $fenceopt;?>
        </select>
    </td>
    <td>Date</td>
    <td>
        <input name="SDate" id="SDate" type="text" value="<?php echo date('d-m-Y',$Sdate);?>"/><button id="trigger">...</button>
    </td>
    <td colspan="100%"><input type="submit" data="g-button g-button-submit" class="btn  btn-primary" value="Submit" name="submit"></td>
</tr>
<tr></tr>
</tbody>
</table>
</form>
<?php 
if(isset($_POST['SDate']) && isset($_POST['customerno']))
{
    if($_POST['customerno'] != '-1'){
    //include_once '../../lib/system/utilities.php';
        $Date = GetSafeValueString($_POST['SDate'], 'string');
    
        $type = GetSafeValueString($_POST['alerttype'], 'string');
        $vehicleid = GetSafeValueString($_POST['vehicleid'], 'string');
        $checkpointid = GetSafeValueString($_POST['chkid'], 'string');
        $fenceid = GetSafeValueString($_POST['fenceid'], 'string');
        getalerthistTeam($Date,$type,$vehicleid,$checkpointid,$fenceid,$_POST['customerno']);
    }
    else{
        echo 'Please Select Customer no';
    }
        
}
?>
</div>
</div>
<?php
include("footer.php");
?>
<script>

Calendar.setup(
{
    inputField : "SDate", // ID of the input field
    ifFormat : "%d-%m-%Y", // the date format
    button : "trigger" // ID of the button
});


Event.observe('alerttype', 'change', function(){
    if(this.value == '2')
    {
    $('chkid').show();
    $('fenceid').hide(); 
    }
    else if(this.value == '3'){
    $('fenceid').show(); 
    $('chkid').hide(); 
    }
    else {
    $('chkid').hide(); 
    $('fenceid').hide(); 
    }

});

//Event.observe('customerno', 'change', function(){
//        var customerno = this.value;
//        //alert(customerno);exit;
//	jQuery.ajax({
//		type: "POST",
//		url: "alerthist.php",
//		async: true,
//		data: {
//			customerno: customerno
//		},
//		cache: false,
//		success: function (data) {
//                    alert('yes');
//		}
//	});
//        new Ajax.Request('modules/user/route_ajax.php', {
//		parameters: params,
//		onSuccess: function (transport) {
//			var statuscheck = transport.responseText;
//			if (statuscheck == "ok") {
//				$("message").show();
//				jQuery("#message").fadeOut(3000);
//			} else if (statuscheck == "notok") {
//				$("wuser").show();
//				jQuery("#wuser").fadeOut(3000);
//			} else if (statuscheck == "noemail") {
//				$("noemail").show();
//				jQuery("#noemail").fadeOut(3000);
//			}
//		},
//		onComplete: function () {}
//	});
//
//});
</script> 