<?php
error_reporting(0);
error_reporting(E_ALL ^ E_STRICT);
ini_set('display_errors', 'On');
include_once("session.php");
include("loginorelse.php");
include_once("../../constants/constants.php");
include_once("db.php");
include_once("../../lib/components/gui/objectdatagrid.php");
include_once("../../lib/system/DatabaseManager.php");

class PendingInvoice {
    
}

if (isset($_GET['move']) && isset($_GET['devid'])) {
    $db = new DatabaseManager();
    $is_moved = GetSafeValueString($_GET['move'], "string");
    $devid = GetSafeValueString($_GET['devid'], "string");
    $SQL2 = sprintf("UPDATE devices SET inv_device_priority = %d WHERE deviceid = %d", $is_moved, $devid);
    $db->executeQuery($SQL2);
    header('location:pending_invoice.php');
}

//-----------populate customerno list-------
function getcustomer_detail() {
    $db = new DatabaseManager();
    $customernos = Array();
    $SQL = sprintf("SELECT customerno,customername,customercompany FROM ".DB_PARENT.".customer");
    $db->executeQuery($SQL);
    if ($db->get_rowCount() > 0) {
        while ($row = $db->get_nextRow()) {
            $customer = new PendingInvoice();
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

function getPendingInvoiceData($status, $client = null) {
    $db = new DatabaseManager();
    $display = Array();
    $x = 0;
    $SQL = "SELECT vehicle.vehicleno, unit.unitno, unit.uid, unit.customerno,devices.installdate, devices.deviceid,devices.registeredon, simcard.simcardno,customer.customercompany,devices.inv_device_priority,devices.inv_deferdate FROM vehicle 
            INNER JOIN devices ON devices.uid = vehicle.uid 
            INNER JOIN driver ON driver.driverid = vehicle.driverid 
            INNER JOIN unit ON devices.uid = unit.uid 
            INNER JOIN ".DB_PARENT.".customer ON customer.customerno = unit.customerno
            LEFT OUTER JOIN simcard ON simcard.id = devices.simcardid 
            WHERE devices.inv_device_priority = " . $status . " AND vehicle.isdeleted= 0 AND devices.device_invoiceno='' AND unit.customerno NOT IN (-1,1) AND unit.trans_statusid NOT IN(23,22,10) AND unit.onlease = 0";
    if ($client != null) {
        $SQL .=" AND unit.customerno=$client";
    }
    $SQL .=" ORDER BY devices.customerno, devices.registeredon DESC";

    $db->executeQuery($SQL);
    if ($db->get_rowCount() > 0) {
        while ($row1 = $db->get_nextRow()) {
            $x++;
            $datas = new PendingInvoice();
            $datas->vehicleno = $row1['vehicleno'];
            $datas->customerno = $row1['customerno'];
            $datas->sim = $row1['simcardno'];
            $datas->regon = date("d-m-Y", strtotime($row1["registeredon"]));
            $datas->did = $row1['deviceid'];
            $datas->uno = $row1['unitno'];
            $datas->custcompany = $row1['customercompany'];
            if ($row1['installdate'] == '1970-01-01' || $row1['installdate'] == '0000-00-00') {
                $datas->insdate = '';
            } else {
                $datas->insdate = date("d-m-Y", strtotime($row1['installdate']));
            }
            $datas->priority = $row1['inv_device_priority'];
            if ($row1['inv_deferdate'] == '0000-00-00' || $row1['inv_deferdate'] == '1970-01-01') {
                $datas->deferdate = '';
            } else {
                $datas->deferdate = date("d-m-Y", strtotime($row1['inv_deferdate']));
            }
            $datas->x = $x;
            $display[] = $datas;
        }
    }
    Display($display);
}

function getPendingInvoiceCount($status, $client = null) {
    $db = new DatabaseManager();
    $totcnt = 0;
    $SQL = "SELECT vehicle.vehicleno, unit.unitno, unit.uid, unit.customerno,devices.installdate, devices.deviceid,devices.registeredon, simcard.simcardno,customer.customercompany,inv_device_priority FROM vehicle 
            INNER JOIN devices ON devices.uid = vehicle.uid 
            INNER JOIN driver ON driver.driverid = vehicle.driverid 
            INNER JOIN unit ON devices.uid = unit.uid 
            INNER JOIN ".DB_PARENT.".customer ON customer.customerno = unit.customerno
            LEFT OUTER JOIN simcard ON simcard.id = devices.simcardid 
            WHERE devices.inv_device_priority = " . $status . " AND vehicle.isdeleted= 0 AND devices.device_invoiceno='' AND unit.customerno NOT IN (-1,1) AND unit.trans_statusid NOT IN(23,22,10) AND unit.onlease = 0";
    if ($client != null) {
        $SQL .=" AND unit.customerno=$client";
    }
    $SQL .=" ORDER BY devices.customerno, devices.registeredon DESC";

    $db->executeQuery($SQL);
    return $totcnt = $db->get_rowCount();
}

function Display($display) {

    $dis = '';

    $dis = '<style>
            .inv {
                    border: 1px solid black;
                    border-collapse: collapse;
                    text-align: center;
                }
            </style>';
    $dis .='<table width="100%" class="inv">
    <tr>
        <th class="inv">Priority</th>
        <th class="inv">Sr No.</th>
        <th class="inv">Vehicle No</th>
        <th class="inv">Customer No</th>
        <th class="inv">Customer Name</th>
        <th class="inv">Unit No</th>
        <th class="inv">Device Registered On</th>
        <th class="inv">Device Installed On</th>
        <th class="inv">Deferred Date</th>
        <th class="inv">Edit</th>        
    </tr>
    <tbody>';
    if (empty($display)) {
        $dis .='<tr>';
        $dis .='<td colspan=100% style="text-align:center">No Data Found</td>';
        $dis .='</tr>';
    } else {
        foreach ($display as $displays) {
            $dis .='<tr>';
            if ($displays->priority == 0) {
                $dis .='<td class="inv"><a href="pending_invoice.php?devid=' . $displays->did . '&move=1"><img src="../../images/up_arrow_icon.png" alt="UP" title="UP"></a></td>';
            } else if ($displays->priority == 1) {
                $dis .='<td class="inv"><a href="pending_invoice.php?devid=' . $displays->did . '&move=0"><img src="../../images/down_arrow_icon.png" alt="DOWN" title="DOWN"></a></td>';
            }
            $dis .='<td class="inv">' . $displays->x . '</td>';
            $dis .='<td class="inv">' . $displays->vehicleno . '</td>';
            $dis .='<td class="inv">' . $displays->customerno . '</td>';
            $dis .='<td class="inv">' . $displays->custcompany . '</td>';
            $dis .='<td class="inv">' . $displays->uno . '</td>';
            $dis .='<td class="inv">' . $displays->regon . '</td>';
            $dis .='<td class="inv">' . $displays->insdate . '</td>';
            $dis .='<td class="inv">' . $displays->deferdate . '</td>';
            $dis .='<td class="inv"><a href="modify_pendinginvoice.php?did=' . $displays->did . '"><img src="../../images/edit.png" alt="EDIT" title="EDIT"></a></td>';
            $dis .='</tr>';
        }
    }
    $dis .='</tbody>
</table>';
    echo $dis;
}

include("header.php");
?>


<div class="panel">
    <div class="paneltitle" align="center">Search Pending  Invoices</div>
    <div class="panelcontents">
        <form method="POST" name="myform" id="myform" action="pending_invoice.php">
            <table>
                <tr>

                    <td>Customer No</td>
                    <td>    
                        <select name="cno" id="cno" style="width:200px;" onchange="getIncustomername()">
                            <option value="0">Select Customer No</option>
                            <?php
                            $cms = getcustomer_detail();
                            foreach ($cms as $customer) {
                                ?> 
                                <option value="<?php echo($customer->customerno); ?>" <?php
                                if (isset($_GET["cno"]) ? $_GET['cno'] == $customer->customerno : $_POST['cno'] == $customer->customerno) {
                                    echo "selected";
                                }
                                ?>><?php echo $customer->customerno; ?> - <?php echo $customer->customercompany ?></option>
                                        <?php
                                    }
                                    ?> 

                        </select>
                    </td>
                    <td></td>
                    <td>

                    </td>
                </tr>
                <tr>
                    <td><input type="submit"  name="ifind" id="ifind" class="btn btn-default" value="Search"></td>
                </tr>
            </table>
        </form>
    </div>
</div>

<!--------------------------------priority invoice------------------------>

<div class="panel">
    <div class="paneltitle" align="center">Pending Priority Invoices  <span style="float: right;">Number Of Pending Priority Invoices : 
            <?php
            $priorcnt = '';
            if (isset($_POST['ifind']) || $_GET['cno']) {
                $clientno = isset($_POST['ifind']) ? GetSafeValueString($_POST['cno'], "string") : GetSafeValueString($_GET['cno'], "string");
                echo $priorcnt = getPendingInvoiceCount(1, $clientno);
            } else {
                echo $priorcnt = getPendingInvoiceCount(1, null);
            }
            ?></span></div>
    <div class="panelcontents">
        <?php
        if (isset($_POST['ifind']) || $_GET['cno']) {

            $clientno = isset($_POST['ifind']) ? GetSafeValueString($_POST['cno'], "string") : GetSafeValueString($_GET['cno'], "string");
            getPendingInvoiceData(1, $clientno);
        } else {
            getPendingInvoiceData(1, null);
        }
        //$dg->Render();
        ?>
    </div>
</div>
<br>
<!---------------------normal/deferred invoice-------------->
<div class="panel">
    <div class="paneltitle" align="center">Deferred Invoices<span style="float: right;">Number Of Deferred Invoices : <?php
            $pendcnt = '';
            if (isset($_POST['ifind']) || $_GET['cno']) {
                $client = isset($_POST['ifind']) ? GetSafeValueString($_POST['cno'], "string") : GetSafeValueString($_GET['cno'], "string");
                echo $pendcnt = getPendingInvoiceCount(0, $client);
            } else {
                echo $pendcnt = getPendingInvoiceCount(0, null);
            }
            ?></span></div>
    <div class="panelcontents">
        <?php
        if (isset($_POST['ifind']) || $_GET['cno']) {
            $client = isset($_POST['ifind']) ? GetSafeValueString($_POST['cno'], "string") : GetSafeValueString($_GET['cno'], "string");
            getPendingInvoiceData(0, $client);
        } else {
            getPendingInvoiceData(0, null);
        }
        ?>
    </div>
</div>
<?php
include("footer.php");
?>
