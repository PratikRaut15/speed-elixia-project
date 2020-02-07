<?php
include_once("session.php");
include("loginorelse.php");
include_once("db.php");
include_once("../../constants/constants.php");
include_once("../../lib/system/DatabaseManager.php");
include_once("../../lib/components/gui/datagrid.php");
include_once("../../lib/system/Date.php");
include_once("../../lib/system/Sanitise.php");

$uid = GetSafeValueString(isset($_GET["uid"])? $_GET["uid"]:$_POST["uid"], "long");
// See if we need to save a new one.
$todaydate =date("Y-m-d");
$message="";
$db = new DatabaseManager();

$message="";
$tinvoice="";
$pono="";
$podate="";
$ucexpiry="";
if(isset($_POST["save"]))
{
   $tinvoice = GetSafeValueString($_POST["tinvoice"], "string");
   $pono = GetSafeValueString($_POST["pono"],"string");
   $podate = GetSafeValueString($_POST["cpodate"],"string");
   $ucexpiry = GetSafeValueString($_POST["ucexpiry"],"string");
   $uid = GetSafeValueString($_POST["uid"], "string");
    //if(empty($tinvoice) && empty($uid)|| empty($pono)||empty($podate)||empty($ucexpiry)){
    $podate = date('Y-m-d', strtotime($podate));
    $ucexpiry =date('Y-m-d',strtotime($ucexpiry));
    if($ucexpiry == $todaydate){
        $message="Expiry date should not be todays date.";
    }elseif(empty($tinvoice) && empty($ucexpiry)){
        $message="Fields cannot be blank";
    }else{
        
         $SQL = sprintf("UPDATE devices SET invoiceno='".$tinvoice."', expirydate='".$ucexpiry."', po_no='".$pono."', po_date='".$podate."' where uid=$uid");    
         $db->executeQuery($SQL);
        header("Location: pending_invoice.php");
    }
 
}
$sql = sprintf("Select * from `devices` where uid=%d",$uid);
$db->executeQuery($sql);
if($db->get_rowCount()>0)
{
    while($row = $db->get_nextRow())
    {
      
        $expirydate = $row["expirydate"];
        $tinvoice = $row["invoiceno"];
        $pono = $row["po_no"];
        $podate = $row["po_date"];

    }
}


include("header.php");
?>
<script>
    function deleteteam()
    {
        if(confirm("Delete this Customer?"))
        {
            $("form1").submit();
        }
    }
</script>
<div class="panel">
<div class="paneltitle" align="center">Update Renewals </div>
<div class="panelcontents">
    <form method="post" id="form1" name="form1" onsubmit="return ValidateForm(); return false;" action="modify_renewals.php">
<?php if(!empty($message)){
    echo"<span style='color:red; font-size:10px;'>".$message."</span>";
} ?>
<input type="hidden" name = "uid" value="<?php echo($uid) ?>"/>
<table width="100%">
        <tr>
             <td>Po No.</td><td><input id="pono" name = "pono" type="text" value="<?php echo $pono;?>"></td>
        </tr>
        <tr>
            <td>Invoice No.</td>
            <td> <input  type="text" name="tinvoice" id="tinvoice" value="<?php echo $tinvoice; ?>"/>
            </td>
        </tr>
        <?php
        if($podate =="0000-00-00" || $podate=="1970-01-01"){
            $podate = "";
        }else{
            $podate = date('d-m-Y', strtotime($podate));
        }
        ?>
        <tr>
            <td>PO Date </td>
            <td> <input type="text" name="cpodate" id="cpodate" value="<?php echo $podate;?>"/><button id="trigger8">...</button>
            </td>
        </tr> 
        
        <tr>
            <td>Expiry Date </td>
            <td> <input name="ucexpiry" id="ucexpiry" type="text" value="<?php echo $expiry = date('d-m-Y', strtotime($expirydate)); ?>"/><button id="trigger4">...</button>
            </td>
        </tr> 
    
</table>
<input type="submit" name="save" value="Save Renewals"/>
</form>
</div>
</div>

<?php 
include("footer.php");
?>
<script>
    Calendar.setup(
{
    inputField : "cpodate", // ID of the input field
    ifFormat : "%d-%m-%Y", // the date format
    button : "trigger8" // ID of the button
});

Calendar.setup(
{
    inputField : "ucexpiry", // ID of the input field
    ifFormat : "%d-%m-%Y", // the date format
    button : "trigger4" // ID of the button
});
</script>