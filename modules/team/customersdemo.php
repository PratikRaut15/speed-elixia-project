<?php
//error_reporting(0);
//error_reporting(E_ALL ^ E_STRICT);
//ini_set('display_errors', 'On');
include_once("session.php");
include("loginorelse.php");
include_once("../../constants/constants.php");
include_once("db.php");
include_once("../../lib/components/gui/objectdatagrid.php");
include_once("../../lib/system/DatabaseManager.php");

class customers{
    
}

//Datagtrid
$db = new DatabaseManager();
$srno = 0;
$totalsimcount = 0;
$totalunits = 0;
$totalpayment = 0;
$customers = Array();
$SQL = sprintf ("SELECT c.renewal, c.totalsms, c.customerno, c.customername, c.smsleft, c.customercompany,count(unit.unitno) AS cunit FROM ".DB_PARENT.".customer c
                 LEFT OUTER JOIN unit ON c.customerno=unit.customerno AND unit.trans_statusid not in (10) WHERE c.renewal = -1 AND c.customerno <> 1
                 GROUP BY c.customerno");
$db->executeQuery($SQL);
if ($db->get_rowCount() > 0) {
    while ($row = $db->get_nextRow())
    {
        $customer = new customers();
        $srno++;        
        $customer->srno = $srno;
        $customer->totalsms  = $row["totalsms"];
        $customer->customerno  = $row["customerno"];
        $customer->customername  = $row["customername"];
        $customer->smsleft  = $row["smsleft"];
        $customer->customercompany  = $row["customercompany"];
        $totalunits+=$row["cunit"];
        $customer->cunit  = $row["cunit"];
        if($row["renewal"] == 0)
        {
            $customer->crenewal  = "Not Assigned";        
        }
        if($row["renewal"] == -2)
        {
            $customer->crenewal  = "Closed";        
        }        
        if($row["renewal"] == -3)
        {
            $customer->crenewal  = "Lease";        
        }                
        if($row["renewal"] == -1)
        {
            $customer->crenewal  = "Demo";        
        }                
        if($row["renewal"] == 1)
        {
            $customer->crenewal  = "Monthly";        
        }        
        if($row["renewal"] == 3)
        {
            $customer->crenewal  = "Quarterly";        
        }                
        if($row["renewal"] == 6)
        {
            $customer->crenewal  = "Six Monthly";        
        }                
        if($row["renewal"] == 12)
        {
            $customer->crenewal  = "Yearly";        
        }                        
        $customer->pending_amt = '0';                
        $customers[] = $customer;        
    }    
}

if(isset($customers))
{
    foreach($customers as $thiscustomerno)
    {
        //----------------------------------to find pending amt------------------------------------------------------------------------
        $SQL2 = sprintf("SELECT sum(pending_amt) as pending_amount FROM ".DB_PARENT.".invoice WHERE customerno = %d",$thiscustomerno->customerno);
    $db->executeQuery($SQL2);
    if ($db->get_rowCount() > 0) {
        while ($row = $db->get_nextRow())
        {
            $totalpayment+=$row["pending_amount"];
            $thiscustomerno->pending_amt = "Rs ".$row["pending_amount"]." /-";
            if($row["pending_amount"]=="" ||$row["pending_amount"]=="0")
            {
                $thiscustomerno->pending_amt=0;
            }
        }
    }
    //--------------------------------to find sim count-------------------------------------------------------------------------------
    $SQL3 = sprintf("SELECT devices.deviceid,devices.customerno,devices.uid,count(simcardid) AS sim FROM devices 
                     INNER JOIN unit ON unit.uid =devices.uid AND unit.trans_statusid =23
                     INNER JOIN simcard ON devices.simcardid = simcard.id AND simcard.trans_statusid =24
                     WHERE devices.customerno = %d AND simcard.vendorid <> 4",$thiscustomerno->customerno);
    $db->executeQuery($SQL3);
    if ($db->get_rowCount() > 0) {
        while ($row = $db->get_nextRow())
        {
            $totalsimcount=$totalsimcount + $row["sim"];
            $thiscustomerno->simcount = $row["sim"];
            if($row["sim"]=="" ||$row["sim"]=="0")
            {
                $thiscustomerno->simcount=0;
            }
        }
    }
    
    }
    
}

$timezones = Array();
$SQL = sprintf ("SELECT * from ".DB_PARENT.".timezone");
$db->executeQuery($SQL);
if ($db->get_rowCount() > 0) {
    while ($row = $db->get_nextRow())
    {
        $time = new customers();
        $time->tid = $row['tid'];
        $time->zone = $row['timezone'];
        $timezones[] = $time;        
    }    
}


$dg = new objectdatagrid($customers);
$dg->AddAction("View/Edit", "../../images/edit.png", "modifycustomer.php?cid=%d");
$dg->AddColumn("Serial #", "srno");
$dg->AddColumn("Customer #", "customerno");
$dg->AddColumn("Name", "customername");
$dg->AddColumn("Company", "customercompany");
$dg->AddColumn("Subscription", "crenewal");
$dg->AddColumn("SMS Left", "smsleft");
$dg->AddColumn("Total SMS", "totalsms");
$dg->AddColumn("Total Units", "cunit");
$dg->AddColumn("Sim Count", "simcount");
if(IsHead() || IsAdmin() || IsCRM())
{
    
$dg->AddColumn("Pending Collection", "pending_amt");
}
$dg->AddRightAction("View", "../../images/history.png", "historycust.php?cid=%d");
//$dg->AddActionAnotherRight("View Users", "../../images/user.png", "user_view.php?cid=%d");
$dg->SetNoDataMessage("No Customer");
$dg->AddIdColumn("customerno");

$_scripts[] = "../../scripts/trash/prototype.js";
 
include("header.php");

?>

<br/>
<div class="panel">
<div class="paneltitle" align="center">Demo Customer List <span style="float: right;">Total Customers: <?php echo($srno); ?>, Total Units: <?php echo($totalunits); ?>, Total Simcards : <?php echo($totalsimcount); ?>, Pending Collection : Rs <?php echo($totalpayment); ?> /- </span></div>
<div class="panelcontents">
<?php $dg->Render(); ?>
</div>

</div>
<br/>

<?php
include("footer.php");
?>

<script type="text/javascript">

function show_heirarchy()
{
    if($("#cmaintenance").is(':checked'))
         $("#heir_tr").show()
      else
         $("#heir_tr").hide()
}

function show_routing()
{
    if($("#cdelivery").is(':checked'))
         $("#routing_tr").show()
      else
         $("#routing_tr").hide()
}

function show_features()
{
    if($("#ctracking").is(':checked'))
    {
        $("#load_sensor").show()
        $("#reverse_geo").show()
        $("#ac_tr").show()
        $("#genset_tr").show()        
        $("#fuel_tr").show()
        $("#door_tr").show()
        $("#temp_tr").show()
        $("#portable_tr").show()        
        $("#advanced_tr").show()        
        $("#panic_tr").show()        
        $("#buzzer_tr").show()        
        $("#immobilizer_tr").show()                
    }
    else
    {
        $("#load_sensor").hide()
        $("#reverse_geo").hide()        
        $("#ac_tr").hide()    
        $("#genset_tr").hide()
        $("#fuel_tr").hide()
        $("#door_tr").hide()
        $("#temp_tr").hide()
        $("#portable_tr").hide()        
        $("#advanced_tr").hide()        
        $("#panic_tr").hide()        
        $("#buzzer_tr").hide()        
        $("#immobilizer_tr").hide()                        
    }
}

function ValidateForm(){
    var cprimaryname = $("#cprimaryname").val();
    var ccompany = $("#ccompany").val();
    var cprimaryusername = $("#cprimaryusername").val();
    var cprimarypassword = $("#cprimarypassword").val();
    
    if(cprimaryname==""){
        alert("Please enter Realname");
        return false;
    }else if(ccompany==""){
        alert("Please enter company name");
        return false;
    }else if(cprimaryusername==""){
        alert("Please enter username");
        return false;
    }else if(cprimarypassword==""){
        alert("Please enter password");
        return false;
    }else{
        $("#myformcustomer").submit();
    }
}
    
</script>    