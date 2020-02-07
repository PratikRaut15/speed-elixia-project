<?php
include_once("session.php");
include("loginorelse.php");
include_once("db.php");
include_once("../../constants/constants.php");
include_once("../../lib/system/DatabaseManager.php");
include_once("../../lib/components/gui/datagrid.php");
include_once("../../lib/system/Sanitise.php");
$_scripts[] = "../../scripts/trash/prototype.js";
 
include("header.php");

class testing{
    
}
date_default_timezone_set("Asia/Calcutta");
$today = date("Y-m-d H:i:s");
$db = new DatabaseManager();

// UNIT PURCHASE
if(isset($_POST["usubmit"]))
{    
    $device_qty = GetSafeValueString($_POST["device_qty"], "string");   
    if(!is_numeric($device_qty) && !empty($device_qty)){
        $message = "Please enter only numbers";
    }else if($device_qty<10){
        $message = "Device Qty should be greater than 10.";
    }else{
    $SQLUnit =sprintf("INSERT INTO ".DB_PARENT.".distributor_purchase(`teamid`,`role`,`device_qty`,`timestamp`) VALUES (%d,'%s',%d,'%s')",$_SESSION['sessionteamid'],"Distributor",$device_qty,Sanitise::DateTime($today));
    $db->executeQuery($SQLUnit);
    }
}    
    $SQL = sprintf("select teamid,role,device_qty,timestamp from ".DB_PARENT.".distributor_purchase where teamid=".$_SESSION['sessionteamid']);
    $db->executeQuery($SQL);

    $dg = new datagrid( $db->getQueryResult());
   // $dg->AddAction("View/Edit", "../../images/edit.png", "modifydealer.php?sid=%d");
   // $dg->AddColumn("Teamid", "teamid");
    $dg->AddColumn("Role", "role");
    $dg->AddColumn("Device Qty", "device_qty");
    $dg->AddColumn("Date/Time", "timestamp");
    $dg->SetNoDataMessage("No Purchase");
    $dg->AddIdColumn("uid");

// ---------------------------------------- Unit Purchase Form  -------------------------------------------
if(IsDistributor())
{
?>
    <div class="panel">
    <div class="paneltitle" align="center">
        New Purchase</div>
    <div class="panelcontents">
        <form method="post" name="myform" id="myform" onsubmit="ValidateForm(); return false;"  enctype="multipart/form-data">
            <?php
            if(!empty($message)){
                echo "<span style='color:red; font-size:11px;'>".$message."</span>";
            }
            ?>
    <table width="50%">
        <tr>
            <td>Device Qty.</td><td><input name = "device_qty" id="device_qty" maxlength="5" onkeypress="return onlyNos(event,this);" type="text"></td>            
        </tr>       
        
    </table>
    <div><input type="submit" id="usubmit" name="usubmit" value="Purchase"/></div>
    </form>
    </div>
    </div>
<br/>
<div class="panel">
<div class="paneltitle" align="center">Dealers</div>
<div class="panelcontents">
<?php $dg->Render(); ?>
<br/>
</div>
</div>



<?php
}
?>
<br/>

<?php
include("footer.php");
?>
<script type="text/javascript">
function ValidateForm(){
   var device_qty = $("#device_qty").val();
   
    if(device_qty==""){
        alert("Please enter device quantity.");
        return false;
    }else if(device_qty <10){
        alert("Minimum quantity should be greater than 10.");
        return false;
    }else{
       $("#myform").submit();
       return true;
    }
}

function onlyNos(e, t) {
    try {
        if (window.event) {
            var charCode = window.event.keyCode;
        }
        else if (e) {
            var charCode = e.which;
        }
        else { return true; }
        if (charCode > 31 && (charCode < 48 || charCode > 57)) {
            return false;
        }
        return true;
    }
    catch (err) {
        alert(err.Description);
    }
} 
 
</script> 