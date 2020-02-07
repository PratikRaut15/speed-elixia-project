<?php
error_reporting(0);
//error_reporting(E_ALL ^ E_STRICT);
ini_set('display_errors', 'On');
include_once("session.php");
include("loginorelse.php");
include_once("../../constants/constants.php");
include_once("db.php");
include_once("../../lib/components/gui/datagrid.php");
include_once("../../lib/system/DatabaseManager.php");
include_once '../../lib/bo/VehicleManager.php';
include_once '../../modules/realtimedata/rtd_functions.php';

//
class PendingRenewals{
    
}
$db = new DatabaseManager();
$cnt="";
$cnt1="";
$cnt2="";

if(isset($_POST['rfind'])){
    $expdate =  GetSafeValueString(date("Y-m-d",strtotime($_POST['expdate'])),"string");
    $insdate =  GetSafeValueString(date("Y-m-d",strtotime($_POST['insdate'])),"string");
    $customerno =  GetSafeValueString($_POST['cno'],"string");
    $total=getsearch_pending_renewal($expdate,$customerno,$insdate);// for expired devices
    $cnt = count($total);
    $total1=getsearch_pending_inv($expdate,$customerno);//for pending invoice
    $cnt1 = count($total1);
    $total2=getSearch_ExpIn15($expdate,$customerno,$insdate);//for expired device in 15 days
    $cnt2 = count($total2);
    
}else{
$result = getvehicles_pending_inv1();
$cnt = count($result);
$result1 = getvehicles_pending_inv();
$cnt1 = count($result1);
$result2 = getExpIn15();
$cnt2 = count($result2);
}
//////////////to find renewal in next 15 days////////////////////////////


function getExpIn15(){
    $db = new DatabaseManager();
    $vehicles=Array();
    $start_date =date('Y-m-d');
    $end_date =date('Y-m-d', strtotime("+15 days"));
    
    $sql ="SELECT *,vehicle.vehicleno, unit.unitno, unit.uid, unit.customerno, devices.lastupdated, devices.registeredon, devices.installdate, devices.expirydate, devices.deviceid,devices.lastupdated, unit.is_ac_opp, simcard.simcardno FROM vehicle 
           INNER JOIN devices ON devices.uid = vehicle.uid INNER JOIN driver ON driver.driverid = vehicle.driverid 
           INNER JOIN unit ON devices.uid = unit.uid LEFT OUTER JOIN simcard ON simcard.id = devices.simcardid 
           WHERE vehicle.isdeleted= 0 AND (devices.expirydate BETWEEN'$start_date' AND '$end_date') AND unit.customerno NOT IN (-1,1) AND devices.expirydate !='1970-01-01' AND devices.expirydate!='0000-00-00' AND unit.trans_statusid NOT IN(23,24,10)
           ORDER BY `devices`.`expirydate`  ASC";
    
    $db->executeQuery($sql);
    if ($db->get_rowCount() > 0) {
            while ($row = $db->get_nextRow()) {
                $vehicle = new PendingRenewals();
                $vehicle->customerno = $row['customerno'];
                $vehicle->deviceid = $row['deviceid'];
                $vehicle->vehicleid = $row['vehicleid'];
                $vehicle->phone = $row['simcardno'];
                $vehicle->vehicleno = $row['vehicleno'];
                $vehicle->install = date("d-m-Y",strtotime($row['installdate']));
                $vehicle->expiry = date("d-m-Y",strtotime($row['expirydate']));
                $vehicle->unitno = $row['unitno'];
                $vehicle->uid = $row['uid'];
                if ($row['lastupdated'] != '0000-00-00 00:00:00')
                    $vehicle->lastupdated = $row['lastupdated'];
                else
                    $vehicle->lastupdated = $row['registeredon'];
               
                $vehicles[] = $vehicle;
            }
            return $vehicles;
        }
        return null;
    }

function display_renewals_in15() {
    include '../realtimedata/pages/pending_renewals.php';

    $vehicledata1 = getExpIn15();

    if (isset($vehicledata1)) 
        {
        print_renewal($vehicledata1);

        include '../common/locationmessage.php';
    } else {
        echo "<tr><td colspan=100%>No Data Avialable</td></tr>";
    }
    echo "</table>";
}
    function print_renewal($vehicledata1) {
        //include '../realtimedata/pages/pending_renewals.php';
    $x = 1;
    foreach ((array)$vehicledata1 as $vehicle1) {
        echo "<tr style='background:#FFE0CC;'>";
        echo "<td>" . $x . "</td>";
        echo "<td>" . $vehicle1->vehicleno . "</td>";
        echo "<td>" . $vehicle1->customerno . "</td>";
        echo "<td>" . $vehicle1->unitno . "</td>";
        echo "<td>" . $vehicle1->install . "</td>";
        echo "<td>" . $vehicle1->expiry . "</td>";
        echo "<td>$vehicle1->phone</td>";
        echo "<td><a style='cursor:pointer; color:blue;' href='modify_renewals.php?uid=$vehicle1->uid'>Renewals</a></td>";
        echo "</tr>";
        $x++;
    }
    echo "</tbody>";
}

/////////////to search data for expire in 15days///////////////////////
function getSearch_ExpIn15($expdate,$customerno,$insdate){
    $exp_date =$expdate;
    $cust_no =$customerno;
    $ins_date =$insdate;
    
    $db = new DatabaseManager();
    $vehicles=Array();
    $start_date =date('Y-m-d');
    $end_date =date('Y-m-d', strtotime("+15 days"));
    
    $sql1 ="SELECT *,vehicle.vehicleno, unit.unitno, unit.uid, unit.customerno, devices.lastupdated, devices.registeredon, devices.installdate, devices.expirydate, devices.deviceid,devices.lastupdated, unit.is_ac_opp, simcard.simcardno FROM vehicle 
           INNER JOIN devices ON devices.uid = vehicle.uid INNER JOIN driver ON driver.driverid = vehicle.driverid 
           INNER JOIN unit ON devices.uid = unit.uid LEFT OUTER JOIN simcard ON simcard.id = devices.simcardid 
           WHERE vehicle.isdeleted= 0 AND (devices.expirydate BETWEEN'$start_date' AND '$end_date') AND unit.customerno NOT IN (-1,1) AND devices.expirydate !='1970-01-01' AND devices.expirydate!='0000-00-00' AND unit.trans_statusid NOT IN(23,24,10)
           ";
    if($cust_no!="" && $cust_no!='0' )
        {
            $sql1 .= sprintf(" AND unit.customerno='$cust_no'");
        }
        if($exp_date!="1970-01-01")
        {
             $sql1 .= sprintf(" AND devices.expirydate='$exp_date'");
        }
        if($ins_date!="1970-01-01")
        {
             $sql1 .= sprintf(" AND devices.installdate='$ins_date'");
        }
        //echo $sql1;
    $db->executeQuery($sql1);
    if ($db->get_rowCount() > 0) {
            while ($row = $db->get_nextRow()) {
                $vehicle = new PendingRenewals();
                $vehicle->customerno = $row['customerno'];
                $vehicle->deviceid = $row['deviceid'];
                $vehicle->vehicleid = $row['vehicleid'];
                $vehicle->phone = $row['simcardno'];
                $vehicle->vehicleno = $row['vehicleno'];
                $vehicle->install = date("d-m-Y",strtotime($row['installdate']));
                $vehicle->expiry = date("d-m-Y",strtotime($row['expirydate']));
                $vehicle->unitno = $row['unitno'];
                $vehicle->uid = $row['uid'];
                if ($row['lastupdated'] != '0000-00-00 00:00:00')
                    $vehicle->lastupdated = $row['lastupdated'];
                else
                    $vehicle->lastupdated = $row['registeredon'];
               
                $vehicles[] = $vehicle;
            }
            return $vehicles;
        }
        return null;
    }
function displaySearch_renewals_in15($expdate,$customerno,$insdate) {
    include '../realtimedata/pages/pending_renewals.php';

    $vehicledata3 = getSearch_ExpIn15($expdate,$customerno,$insdate);

    if (isset($vehicledata3)) 
        {
        printSearch_renewal($vehicledata3);

        include '../common/locationmessage.php';
    } else {
        echo "<tr><td colspan=100%>No Data Avialable</td></tr>";
    }
    echo "</table>";
}
    function printSearch_renewal($vehicledata3) {
        //include '../realtimedata/pages/pending_renewals.php';
    $x = 1;
    foreach ((array)$vehicledata3 as $vehicle1) {
        echo "<tr style='background:#FFE0CC;'>";
        echo "<td>" . $x . "</td>";
        echo "<td>" . $vehicle1->vehicleno . "</td>";
        echo "<td>" . $vehicle1->customerno . "</td>";
        echo "<td>" . $vehicle1->unitno . "</td>";
        echo "<td>" . $vehicle1->install . "</td>";
        echo "<td>" . $vehicle1->expiry . "</td>";
        echo "<td>$vehicle1->phone</td>";
        echo "<td><a style='cursor:pointer; color:blue;' href='modify_renewals.php?uid=$vehicle1->uid'>Renewals</a></td>";
        echo "</tr>";
        $x++;
    }
    echo "</tbody>";
}
//-----------populate customerno list-------
function getcustomer_detail() {
        $db = new DatabaseManager();
        $customernos = Array();
        $SQL = sprintf("SELECT customerno,customername,customercompany FROM ".DB_PARENT.".customer");
        $db->executeQuery($SQL);
        if ($db->get_rowCount() > 0) {
            while ($row = $db->get_nextRow()) {
                $customer = new PendingRenewals();
                $customer->customerno = $row['customerno'];
                $customer->customername = $row['customername'];
                $customer->customercompany = $row['customercompany'];
                $customernos[] = $customer;
            }
            return $customernos;
            //print_r($customernos);
            }
        return false;
    }
    
    $_scripts[] = "../../scripts/tablesorter/jquery.tablesorter.js";
    include("header.php");
?>
<div class="panel">
<div class="paneltitle" align="center">Search Pending Renewals Invoices</div>
<div class="panelcontents">
    <form method="POST" name="myform" id="myform" action="pending_renewals.php">
        <table>
            <tr>
                <td>Install Date</td>
                <td><input type="text" name ="insdate" id ="insdate" placeholder="dd-mm-yyyy" value="<?php if(isset($_POST['insdate']) && $_POST['insdate']!='')  {echo date("d-m-Y",strtotime($_POST['insdate']));} ?>"><button id="trigger2">...</button>
            </td>
            
                <td>Expiry Date</td>
                <td><input type="text" name ="expdate" id ="expdate" placeholder="dd-mm-yyyy" value="<?php if(isset($_POST['expdate']) && $_POST['expdate']!='')  {echo date("d-m-Y",strtotime($_POST['expdate']));} ?>"><button id="trigger1">...</button>
            </td>
            
            <td>Customer No</td>
        <td>    
        <select name="cno" id="cno" style="width:200px;">
                 <option value="0">Select Customer No</option>
                <?php
                       
                        $cms = getcustomer_detail();
                       foreach($cms as $customer)
                       {
                ?> 
                <option value="<?php echo($customer->customerno);?>"<?php if($_POST['cno']==$customer->customerno){echo "selected";}?>><?php echo $customer->customerno;?> - <?php echo $customer->customercompany?></option>
                <?php
                        }
                ?> 
        
        </select>
        </td>
            </tr>
            <tr>
                <td><input type="submit"  name="rfind" id="rfind" class="btn btn-default" value="Search"></td>
            </tr>
        </table>
    </form>
</div>
</div>
<br>   
<div class="panel">
<div class="paneltitle" align="center">Pending  Renewals  <span style="float: right;">Number Of Pending Renewals : <?php echo $cnt1;?></span></div>
<div class="panelcontents">
<?php 

 if(isset($_POST['rfind']))
{
    display_search_invoice($expdate,$customerno);
} else{
display_pending_vehicles();
  }
?>
    </div>
    </div>
 <div class="panel">
<div class="paneltitle" align="center">Expired Devices  <span style="float: right;">Number Of Expired Devices  : <?php echo $cnt;?></span></div>
<div class="panelcontents">
<?php 
if(isset($_POST['rfind']))
{
    display_search_renewal($expdate,$customerno,$insdate);
}else{
       display_pending_renewals();
}

 //include("footer.php");
?>
</div>
</div>
<div class="panel">
<div class="paneltitle" align="center">Devices that will expire in 15 days <span style="float: right;">Number Of Devices That Will Expire  : <?php echo $cnt2;?></span></div>
<div class="panelcontents">
    <?php
    if(isset($_POST['rfind']))
{
    displaySearch_renewals_in15($expdate,$customerno,$insdate);
}else{   
    display_renewals_in15();
    //print_renewal($vehicles);
}
 include("footer.php");
    ?>


<script>
Calendar.setup(
{
    inputField : "expdate", // ID of the input field
    ifFormat : "%d-%m-%Y", // the date format
    
    button : "trigger1" // ID of the button
});
Calendar.setup(
{
    inputField : "insdate", // ID of the input field
    ifFormat : "%d-%m-%Y", // the date format
    
    button : "trigger2" // ID of the button
});

</script>