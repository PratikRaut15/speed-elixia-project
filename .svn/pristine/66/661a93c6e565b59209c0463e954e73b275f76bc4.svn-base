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
$SQL = sprintf ("SELECT c.renewal, c.totalsms, c.customerno, c.customername, c.smsleft, c.customercompany,c.lease_duration,c.lease_price,c.renewal,c.unit_msp,count(unit.unitno) AS cunit FROM ".DB_PARENT.".customer c
                 LEFT OUTER JOIN unit ON c.customerno=unit.customerno AND unit.trans_statusid not in (10) WHERE c.renewal NOT IN (-1,-2) AND c.use_trace = 1
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
        if(($row["renewal"] == 0 || $row["renewal"] == 1 || $row["renewal"] == 3 || $row["renewal"] == 6 || $row["renewal"] == 12) && $row['unit_msp'] == 0){
             $customer->renewalprice  = "Not Assigned";
        }else{
            $customer->renewalprice  = $row['unit_msp'];
        }
        if($row["renewal"] == -3 && $row['lease_price'] == 0){
            $customer->leaseprice  = "Not Assigned";
        }else if($row["renewal"] == -3 && $row['lease_price'] != 0){
            $customer->leaseprice  = $row['lease_price'];
        }else{
            $customer->leaseprice = "NA";
        }
        if($row["renewal"] == -3 && $row['lease_duration'] == 0){
            $customer->leaseduration  = "Not Assigned";
        }else if($row["renewal"] == -3 && $row['lease_duration'] != 0){
            $customer->leaseduration  = $row['lease_duration'];
        }else{
            $customer->leaseduration = "NA";
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
                     INNER JOIN simcard ON devices.simcardid = simcard.id AND simcard.trans_statusid IN (13,14,24,25)
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
if(IsHead() || IsAdmin())
{
$dg->AddColumn("Subscription Price", "renewalprice");
$dg->AddColumn("Lease Period", "leaseduration");
$dg->AddColumn("Lease Price", "leaseprice");
}
if(!IsAdmin()){
$dg->AddColumn("SMS Left", "smsleft");
$dg->AddColumn("Total SMS", "totalsms");
$dg->AddColumn("Total Units", "cunit");
$dg->AddColumn("Sim Count", "simcount");
}
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

if(IsHead())
{
?>
    <div class="panel">
    <div class="paneltitle" align="center">
        New Customer - Credentials</div>
    <div class="panelcontents">
    <form method="post" name="myformcustomer" id="myformcustomer" action="createcustomer.php" onsubmit="return ValidateForm(); return false;" enctype="multipart/form-data">
    <table width="100%" align="center">
        <tr>
        <td>Real Name <span style="color:red;">*</span></td><td><input name = "cprimaryname" id="cprimaryname" type="text"></td>            
        <td>Company <span style="color:red;">*</span></td><td><input name = "ccompany" id="ccompany" type="text"></td>
        <td>Email Address <span style="color:red;">*</span></td><td><input id="cprimaryusername" name="cprimaryusername" type="text"/></td>
        <td>Password <span style="color:red;">*</span></td><td><input id="cprimarypassword" name="cprimarypassword" type="password"/></td>
        
        </tr>
    </table>
<br/>        
<div class="paneltitlemid" align="center">Modules</div><br/>
    <table width="50%" align="center">
        <tr>
            <td>SMS Package</td><td><input name = "csmspack" type="text"></td>
        </tr>
        <tr>
            <td>Telephonic Alerts Package</td><td><input name = "ctelephonepack" type="text"></td>
        </tr>
         <tr>
            <td>Tracking Module</td>
            <td><input name="ctracking" id="ctracking" type="checkbox" checked="" onclick="show_features();"/></td>             
        </tr>
        <tr id="load_sensor">
            <td>Load Sensor</td>
            <td><input name="cloading" id="cloading" type="checkbox" /><br/>(Note: Point all the devices using this feature to 9990)</td>             
        </tr>        
        <tr id="reverse_geo">
            <td>Reverse Geo-Location</td>
            <td><input name="cgeolocation" id="cgeolocation" type="checkbox" /><br/>(Note: Location will be pulled from Google Maps API)</td>             
        </tr>        
        <tr id="ac_tr">
            <td></td>
            <td><input name="cac" id="cac" type="checkbox"/>Use AC Sensor</td>             
        </tr>                
        <tr id="genset_tr">
            <td></td>
            <td><input name="cgenset" id="cgenset" type="checkbox"/>Use Genset Sensor</td>             
        </tr>                
        <tr id="fuel_tr">
            <td></td>
            <td><input name="cfuel" id="cfuel" type="checkbox"/>Use Fuel Sensor</td>             
        </tr>                
        <tr id="door_tr">
            <td></td>
            <td><input name="cdoor" id="cdoor" type="checkbox"/>Use Door Sensor</td>             
        </tr>                
        <tr id="portable_tr">
            <td></td>
            <td><input name="cportable" id="cportable" type="checkbox" />Portable Module</td>             
        </tr>                
        <tr id="temp_tr">
            <td>Temperature Sensors</td>
            <td><input type="radio" name="ctempsensor" value="0" checked>0 <input type="radio" name="ctempsensor" value="1">1 <input type="radio" name="ctempsensor" value="2">2 
            <input type="radio" name="ctempsensor" value="3">3 <input type="radio" name="ctempsensor" value="4">4</td>
        </tr>
        <tr id="advanced_tr">
            <td>Advanecd Alerts</td>
            <td><input type="radio" name="advancedAlerts" value="0" checked>No <input type="radio" name="advancedAlerts" value="1">Yes</td>
        </tr>        
        <tr id="panic_tr">
            <td></td>
            <td><input name="cpanic" id="cpanic" type="checkbox"/>Use Panic</td>             
        </tr>                        
        <tr id="buzzer_tr">
            <td></td>
            <td><input name="cbuzzer" id="cbuzzer" type="checkbox"/>Use Buzzer</td>             
        </tr>                        
        <tr id="immobilizer_tr">
            <td></td>
            <td><input name="cimmobilizer" id="cimmobilizer" type="checkbox"/>Use Immobilizer</td>             
        </tr>
        <tr id="mobility_tr">
            <td></td>
            <td><input name="cimobility" id="cimobility" type="checkbox"/>Use Mobility</td>             
        </tr>
        <tr id="mobility_tr">
            <td></td>
            <td><input name="csalesengage" id="csalesengage" type="checkbox"/>Use Sales Engage</td>             
        </tr>
        
        <tr>
            <td>Maintenance Module</td>
            <td><input name="cmaintenance" id="cmaintenance" type="checkbox" onclick="show_heirarchy();"/></td>             
        </tr>        
        <tr id="heir_tr" style="display:none">
            <td></td>
            <td><input name="cheirarchy" id="cheirarchy" type="checkbox"/>Use Hierarchy</td>             
        </tr>                

        <tr>
            <td>Delivery Module</td>
            <td><input name="cdelivery" id="cdelivery" type="checkbox" onclick="show_routing();"/></td>             
        </tr> 
        <tr>
            <td>Timezone</td>
            <td>
                <select name="timezone" id="timezone">
                    <option value="0">Select Timezone</option>
                    <?php
                    if(isset($timezones)){
                        foreach($timezones as $timezone){
                            ?>
                    <option value="<?php echo $timezone->tid;?>" <?php if($timezone->tid==143){ echo "selected=''"; }?> ><?php echo $timezone->zone;?></option>
                            <?php
                        }
                    }
                    ?>
                </select>
            </td>             
        </tr>
        <tr id="routing_tr" style="display:none">
            <td></td>
            <td><input name="crouting" id="crouting" type="checkbox"/>Use Routing</td>             
        </tr>                
        
    </table>

        
    <hr/> 
    <div align="center"><input type="submit" id="submit" name="submit" value="Create new Customer"/></div
    </form>
    </div>
    </div>
<?php
}
?>

<br/>
<div class="panel">
<div class="paneltitle" align="center">Customer List <span style="float: right;">Total Customers: <?php echo($srno); ?>, Total Units: <?php echo($totalunits); ?>, Total Simcards : <?php echo($totalsimcount); ?>, Pending Collection : Rs <?php echo($totalpayment); ?> /- </span></div>
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