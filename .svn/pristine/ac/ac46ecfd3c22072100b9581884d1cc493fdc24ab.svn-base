<?php
include_once("loginorelse.php");
include_once("../../constants/constants.php");
include_once("db.php");
include_once("../../lib/components/gui/objectdatagrid.php");
include_once("../../lib/system/DatabaseManager.php");
$_scripts_custom[] = "../../scripts/team/invoice_generate.js";
include("header.php");
?>
<style>
    .recipientbox {
        border: 1px solid #999999;
        float: left;
        font-weight: 700;
        padding: 4px 27px;
        /*    width: 100px;*/


        float:left;
        -webkit-transition:all 0.218s;
        -webkit-user-select:none;
        background-color:#000;
        /*background-image:-webkit-linear-gradient(top, #4D90FE, #4787ED);*/
        border:1px solid #3079ED;
        color:#FFFFFF;
        text-shadow:rgba(0, 0, 0, 0.0980392) 0 1px;
        border:1px solid #DCDCDC;
        border-bottom-left-radius:2px;
        border-bottom-right-radius:2px;
        border-top-left-radius:2px;
        border-top-right-radius:2px;

        cursor:default;
        display:inline-block;
        font-size:11px;
        font-weight:bold;
        height:27px;
        line-height:27px;
        min-width:46px;
        padding:0 8px;
        text-align:center;

        border: 1px solid rgba(0, 0, 0, 0.1);
        color:#fff !important;
        font-size: 11px;
        font: bold 11px/27px Arial,sans-serif !important;
        vertical-align: top;
        margin-left:5px;
        margin-top:5px;
        text-align:left;
    }
    .recipientbox img {
        float:right;
        padding-top:5px;
    }

</style>
<?php

class GenerateInvoice {
    
}

$db = new DatabaseManager();
$display = Array();
$displays = Array();
$x = 0;
$client = "";
$incustid = "";
$pcount = 0;
$cno = GetSafeValueString($_GET['cno'], "int");
$ledid = GetSafeValueString($_GET['ledid'], "int");
//if (isset($_POST['ifind'])) {
//
//    $incustid = GetSafeValueString($_POST['icname'], "string");
//
//    $SQL = "SELECT vehicle.vehicleid,vehicle.vehicleno,vehicle.invcustid,vehicle.customerno,vehicle.uid,devices.deviceid,devices.device_invoiceno,devices.invoiceno,
//            invoice_customer_address.invoicename FROM vehicle
//            INNER JOIN devices ON vehicle.uid = devices.uid
//            INNER JOIN unit ON unit.uid = vehicle.uid
//            INNER JOIN invoice_customer_address ON invoice_customer_address.invcustid = vehicle.invcustid
//            WHERE unit.trans_statusid =5 AND devices.device_invoiceno='' AND unit.onlease=0
//            AND vehicle.customerno =$client AND vehicle.invcustid =$incustid";
//
//    $db->executeQuery($SQL);
//    if ($db->get_rowCount() > 0) {
//        while ($row = $db->get_nextRow()) {
//            $x++;
//            $data = new GenerateInvoice();
//            $data->vehicleno = $row['vehicleno'];
//            $data->customerno = $row['customerno'];
//            $data->invname = $row['invoicename'];
//            $data->x = $x;
//            $display[] = $data;
//        }
//    }
//    $pcount = $db->get_rowCount();
//
//    $dg = new objectdatagrid($display);
//    $dg->AddColumn("Sr no", "x");
//    $dg->AddColumn("Customer No", "customerno");
//    $dg->AddColumn("Vehicle No", "vehicleno");
//    $dg->AddColumn("Customer Invoice Name", "invname");
//    $dg->SetNoDataMessage("No Vehicles");
//    $dg->AddIdColumn("id");
//}
//
////////////////////to find invoice no//////////////////////////////////////////
//$invc = Array();
//$query = "SELECT invoiceid,invoiceno FROM invoice WHERE customerno='$client' AND isdeleted=0 ORDER BY invoiceid DESC";
//$db->executeQuery($query);
//while ($row1 = $db->get_nextRow()) {
//    $info = new GenerateInvoice();
//    $info->inid = $row1['invoiceid'];
//    $info->in_no = $row1['invoiceno'];
//    $invc[] = $info;
//}
//
//foreach ($invc as $res) {
//    $fin[] = $res->in_no;
//}
////to find count of invoice for new devices
//$count = 0;
//$count = substr_count(implode($fin), "A");
//$fcount = $count + 1;
//if ($fcount < 10) {
//    $fcount = '0' . $fcount;
//}
//// to find last invoiceno 
//
//$query1 = "SELECT invoiceno FROM invoice  WHERE isdeleted =0 ORDER BY invoiceid DESC LIMIT 1";
//$db->executeQuery($query1);
//$row2 = $db->get_nextRow();
//$lastinvoice = $row2['invoiceno'];
//$str = substr($lastinvoice, 7, -1);
//$str_count = (int) $str + 1;
//
//// update invoice no and invoice generation date in the corresponding vehicles in devices table
//if (isset($_POST['confirm'])) {
//    //print_r($_POST);
//    $customer_no = GetSafeValueString($_POST['custno'], "string");
//    $invcust_id = GetSafeValueString($_POST['invcid'], "string");
//    $invoice_no = GetSafeValueString($_POST['in_no'], "string");
//    $invoice_date = date("Y-m-d", strtotime(GetSafeValueString($_POST['in_date'], "string")));
//
//    $SQL1 = "SELECT vehicle.vehicleid,vehicle.vehicleno,vehicle.invcustid,vehicle.customerno,vehicle.uid,devices.deviceid,devices.device_invoiceno,devices.invoiceno,
//            invoice_customer_address.invoicename,customer.unitprice,customer.customercompany FROM vehicle
//            INNER JOIN devices ON vehicle.uid = devices.uid
//            INNER JOIN customer ON vehicle.customerno=customer.customerno
//            INNER JOIN unit ON unit.uid = vehicle.uid
//            INNER JOIN invoice_customer_address ON invoice_customer_address.invcustid = vehicle.invcustid
//            WHERE unit.trans_statusid =5 AND devices.device_invoiceno='' 
//            AND unit.onlease=0 AND vehicle.customerno =$customer_no AND vehicle.invcustid =$invcust_id";
//
//    $db->executeQuery($SQL1);
//    if ($db->get_rowCount() > 0) {
//        while ($row1 = $db->get_nextRow()) {
//
//            $datas = new GenerateInvoice();
//            $datas->deviceid = $row1['deviceid'];
//            //$datas->custname=$row1['customercompany'];
//            //$datas->unitprice=$row1['unitprice'];
//            $displays[] = $datas;
//        }
//    }
//    /*
//      echo "<pre>";
//      print_r($displays);echo"</pre>";die();
//
//     * 
//     */
//    foreach ($displays as $di) {
//        $SQL2 = sprintf('UPDATE devices SET device_invoiceno="' . $invoice_no . '",inv_generatedate="' . $invoice_date . '" WHERE deviceid=%d', $di->deviceid);
//
//        $db->executeQuery($SQL2);
//    }
//}
//$firstdate = date('01 M Y');

//$formatdate = date('')
//-----------populate customerno list-------
    $SQL = sprintf("SELECT customerno,customername,customercompany FROM ".DB_PARENT.".customer WHERE customerno=%d",$cno);
    $db->executeQuery($SQL);
    if ($db->get_rowCount() > 0) {
       $row = $db->get_nextRow();
            $customer = new GenerateInvoice();
            $customer->customerno = $row['customerno'];
            $customer->customername = $row['customername'];
            $customer->customercompany = $row['customercompany'];
    }
    $pdo = $db->CreatePDOConn();
    $sp_params1 = "'".$ledid."'"
            .",''"
            ;
    $QUERY1 = $db->PrepareSP('get_ledger', $sp_params1);
        $ledgerList = $pdo->query($QUERY1);
        if ($ledgerList->rowCount() > 0) {
            $row1 = $ledgerList->fetch(PDO::FETCH_ASSOC);
            $ledgerid = $row1['ledgerid'];
            $ledgername =  $row1['ledgername'];
        }
        $db->ClosePDOConn($pdo);
?>
<div class="panel">
    <div class="paneltitle" align="center">Generate Pending  Invoices</div>
    <div class="panelcontents">
        <span id="error_ledger" style="display: none;color: #FF0000;text-align: center">Please Select Ledger</span>
        <span id="error_cust" style="display: none;color: #FF0000;text-align: center">Please Select Customer No.</span>
        <form method="POST" name="myform" id="myform" action="invoice_generate.php">
            <table>
                <tr>               
                    <td>Customer No</td>
                    <td>
                        <input type="text" name="display" id="display" style="width:200px;" value="<?php echo $customer->customerno; ?> - <?php echo $customer->customercompany ?>" readonly/>
                        <input type="hidden" name="cno" id="cno" value="<?php echo $cno; ?>"/>
                    </td>
                    <td>Ledger</td>
                    <td>
                        <input type="text" name="ledger_display" id="ledger_display" style="width:200px;" value="<?php echo $ledgerid ?> - <?php echo $ledgername ?>" readonly/>
                        <input type="hidden" name="ledger" id="ledger" value="<?php echo $ledid; ?>">
                    </td>
                </tr>
                <!--
                <tr>
                    <td><input type="button"  name="ifind" id="ifind" class="btn btn-default" value="Search" onclick="showInvdetails();"></td>
                </tr>
                -->
            </table>
        </form>
    </div>
</div>

<br>
<!-----------------------------------------generate invoice panel--------------------------->
<div class="panel" id="invgen_div" style="display: none;">
    <div class="paneltitle" align="center">Generate Invoices</div>
    <div class="panelcontents">
        <form method="POST" name="gform" action="invoice_html.php" id="gform" target="_blank">
            <table width="80%">
                <input type="hidden" name="custno" id="custno" value="<?php echo $cno;?>"/>
                <input type="hidden" name="ledgerid" id="ledgerid" value="<?php echo $ledid ;?>"/>
                
                <tr>
                    <td>Invoice Type</td>
                </tr>
                <tr>
                    <td> 
                        <input name="invtype" id="devicetype" type="radio" value="0" onclick="showDevice();" checked=""/> &nbsp;Device &nbsp;&nbsp;&nbsp;&nbsp;
                        <input name="invtype" id="renewaltype" type="radio" value="1" onclick="showRenewal();"/> &nbsp; Renewals  &nbsp;&nbsp;&nbsp;&nbsp;
                        <input name="invtype" id="othertype" type="radio" value="2" onclick="showOther();"/> &nbsp; Other  &nbsp;&nbsp;&nbsp;&nbsp;
                        <input name="invtype" id="credittype" type="radio" value="3" onclick="showCredit();"/> &nbsp; Credit Note  &nbsp;&nbsp;&nbsp;&nbsp;
                        <input name="invtype" id="proratatype" type="radio" value="4" onclick="showPro();"/> &nbsp; Pro Rata  &nbsp;&nbsp;&nbsp;&nbsp;
                        <input name="invtype" id="leasetype" type="radio" value="5" onclick="show_lease();"/> &nbsp; Lease  &nbsp;&nbsp;&nbsp;&nbsp;
                    </td>
                </tr>
                <tr id="tr_creditnote">
                    <td>
                        <input name="crtype" id="crdevicetype" type="radio" value="0" onclick="showCrDevice();"/> &nbsp;Device &nbsp;&nbsp;&nbsp;&nbsp;
                        <input name="crtype" id="crrenewaltype" type="radio" value="1" onclick="showCrRenewal();"/> &nbsp; Renewals  &nbsp;&nbsp;&nbsp;&nbsp;
                    </td>
                </tr>
                <tr id="tr_renewalduration">
                    <td>
                        <input name="duration" id="mon_1" class="duration_class" type="radio" value="1"/> &nbsp; 1 Month &nbsp;&nbsp;&nbsp;&nbsp;
                        <input name="duration" id="mon_3" class="duration_class" type="radio" value="3"/> &nbsp; 3 Months  &nbsp;&nbsp;&nbsp;&nbsp;
                        <input name="duration" id="mon_6" class="duration_class" type="radio" value="6"/> &nbsp; 6 Months &nbsp;&nbsp;&nbsp;&nbsp;
                        <input name="duration" id="mon_12" class="duration_class" type="radio" value="12"/> &nbsp; 1 Year  &nbsp;&nbsp;&nbsp;&nbsp;
                        <input name="duration" id="mon_cust" class="duration_class" type="radio" value="0" onclick="showCusttext();"/> &nbsp; Custom  &nbsp;&nbsp;&nbsp;&nbsp;
                        <input type="text" name="duration_custom" id="duration_custom" maxlenght="2" style="display:none;"/>
                    </td>
                </tr>
                <tr>
                    <td>
                        Invoice No
                        <input type="text" name="invno" id="invno" value="" onkeyup="setInvno();"/>
                    </td>
                </tr>
                <tr>
                    <td>
                        Invoice Date
                        <input type="text" name="invdate" id="invdate" value="<?php echo date("d-m-Y"); ?>" onchange="setInvdate();"/><button  id="trigger1">...</button>
                    </td>
                </tr>
                <tr>
                    <td>PO
                        <select id="po" name="po">

                        </select>
                    </td>
                </tr>
                <tr class="veh_tr">
                    <td>
                        Alloted Vehicle
                    </td>
                </tr>
                <tr class="veh_tr">
                    <td colspan="100%"><div id="vehicle_list">

                        </div></td>
                </tr>
                <tr class="tr_lease">
                    <td>Alloted Lease Vehicles</td>
                </tr>
                <tr class="tr_lease">
                    <td colspan="100%"><div id="lease_list">

                        </div></td>
                </tr>
                <tr>
                    <td id="sdate_td">
                        Start Date
                        <input type="text" name="sdate" id="sdate" value="<?php echo date("d-m-Y"); ?>" onchange="getDescription();"/><button  id="trigger2">...</button>
                    </td>
                </tr>
                <tr style="height:10px;"></tr>
                <tr class="device_tr">
                    <td>Type</td>
                </tr>
                <tr class="device_tr">
                    <td> 
                        <input name="basic" id="basic" type="radio" value="0" onclick="HideAdv();"checked=""/> &nbsp;Basic &nbsp;&nbsp;&nbsp;&nbsp;
                        <input name="basic" id="advance" type="radio" value="1" onclick="DisplayAdv();"/> &nbsp; Advanced &nbsp;&nbsp;&nbsp;&nbsp;
                    </td>
                </tr>


                <tr id="adv_tr">
                    <td width="50%">Sensor<br>
                        <input name="ac" id="ac" type="checkbox" value="1"/>  &nbsp;  Ac Sensor   &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;   
                        Quantity <input name="acquantity" id="acquantity" type="text"  size="3">
                        Price <input name="acprice" id="acprice" type="text"  size="6"><br>


                        <input name="genset" id="genset" type="checkbox" value="1" />  &nbsp;  Genset Sensor   &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;   
                        Quantity <input name="gensetquantity" id="gensetquantity" type="text"  size="3">&nbsp;
                        Price <input name="gensetprice" id="gensetprice" type="text"  size="6"><br/>


                        <input name="door" id="door" type="checkbox" value="1"/>  &nbsp;  Door Sensor   &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;   
                        Quantity <input name="doorquantity" id="doorquantity" type="text" value="" size="3"/>  &nbsp;
                        Price <input name="doorprice" id="doorprice" type="text" size="6"/>  &nbsp;<br>

                        <input type="checkbox" name="fuel" id="fuel" value="1">&nbsp; Fuel Sensor &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        Quantity <input name="fuelquantity" id="fuelquantity" type="text"  size="3"/>  &nbsp;
                        Price <input name="fuelprice" id="fuelprice" type="text"  size="6"/>  &nbsp;<br>

                        <input type="checkbox" name="temp" id="temp" value="1">&nbsp; Temperature Sensor &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        Quantity <input name="tempquantity" id="tempquantity" type="text" value="" size="3"/>  &nbsp;
                        Price <input name="tempprice" id="tempprice" type="text" value="" size="6"/>  &nbsp;<br>

                        <input type="checkbox" name="misc1" id="misc1" value="1">&nbsp; Miscellellaneous <br/>
                        <textarea  name="misc1text" id="misc1text" rows="5" cols="30"></textarea><br/>
                        Quantity <input name="misc1quantity" id="misc1quantity" type="text" value="" size="3"/>  &nbsp;
                        Price <input name="misc1price" id="misc1price" type="text" value="" size="6"/>  &nbsp;<br>

                    </td>
                    <td><br>

                        <input type="checkbox" name="panic" id="fuelsensor" value="1">&nbsp; Panic &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        Quantity <input name="panicquantity" id="panicquantity" type="text" value="" size="3"/>  &nbsp;
                        Price <input name="panicprice" id="panicprice" type="text" value="" size="6"/>  &nbsp;<br>


                        <input type="checkbox" name="buzzer" id="buzzer" value="1">&nbsp; Buzzer &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        Quantity <input name="buzzerquantity" id="buzzerquantity" type="text" value="" size="3"/>  &nbsp;
                        Price <input name="buzzerprice" id="buzzerprice" type="text" value="" size="6"/>  &nbsp;<br>

                        <input type="checkbox" name="immo" id="immo" value="1">&nbsp; Immobilizer  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        Quantity <input name="immoquantity" id="immoquantity" type="text" value="" size="3"/>  &nbsp;
                        Price <input name="immopice" id="immopice" type="text" value="" size="6"/>  &nbsp;<br>

                        <input type="checkbox" name="comm" id="comm" value="1">&nbsp; Two way Communication  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        Quantity <input name="commquantity" id="commquantity" type="text" value="" size="3"/>  &nbsp;
                        Price <input name="commprice" id="commprice" type="text" value="" size="6"/>  &nbsp;<br>

                        <input type="checkbox" name="port" id="port" value="1">&nbsp; Portable  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        Quantity <input name="portquantity" id="portquantity" type="text" value="" size="3"/>  &nbsp;
                        Price <input name="portprice" id="portprice" type="text" value="" size="6"/>  &nbsp;<br>

                        <input type="checkbox" name="misc2" id="misc2" value="1">&nbsp; Miscellellaneous  <br/>
                        <textarea  name="misc2text" id="misc2text" rows="5" cols="30"></textarea><br/>
                        Quantity <input name="misc2quantity" id="misc2quantity" type="text" value="" size="3"/>  &nbsp;
                        Price <input name="misc2price" id="misc2price" type="text" value="" size="6"/>  &nbsp;<br>

                    </td>
                </tr>       
                <tr class="renewal_tr">
                    <td>
                        <table rules="all" style="background:#fff; width: 80%">
                            <tr>
                                <td style="border:none;">
                                    Description
                                </td>
                                <td style="border:none;">
                                    Quantity
                                </td>
                                <td style="border:none;">
                                    Price
                                </td>
                                <td style="border:none;" id="image_add">
                                    <span style="float: right;" onclick="addMoreRows(this.form);">
                                        <a> <img src="../../images/show.png" alt="Add Row"/> </a>
                                    </span>
                                </td>
                            </tr>
                        </table>
                        <table style="border:none;" id="rowCount1" class="rowadded" >
                            <tr>
                                <td style="border:none;"><textarea name="description1" rows="5" cols="30" size="60%" class="description" id="description1"></textarea></td>
                                <td style="border:none;"><input name="quantity1" id="quantity1" class="quantity" type="text" value="" size="25%"/></td>
                                <td style="border:none;"><input name="price1" id="price1" class="price" type="text" value="" size="25%"/></td>
                                <td style="border:none;"><a href="javascript:void(0);" onclick="removeRow(1);"><img src="../../images/hide.gif" alt="Delete"/></a><input name="inv_renewal1" type="hidden" id="inv_renewal1" class="inv_renewal"/></td>
                            </tr>
                        </table>
                        <table style="border:none;" id="rowCount2" class="rowadded" >
                            <tr>
                                <td style="border:none;"><textarea name="description2" rows="5" cols="30" size="60%" class="description" id="description2"></textarea></td>
                                <td style="border:none;"><input name="quantity2" id="quantity2" type="text" value="" size="25%"/></td>
                                <td style="border:none;"><input name="price2" id="price2" type="text" value="" size="25%"/></td>
                                <td style="border:none;"><a href="javascript:void(0);" onclick="removeRow(2);"><img src="../../images/hide.gif" alt="Delete"/></a><input name="inv_renewal2" type="hidden" id="inv_renewal2" class="inv_renewal"/></td>
                            </tr>
                        </table>
                        <table style="border:none;" id="rowCount3" class="rowadded" >
                            <tr>
                                <td style="border:none;"><textarea name="description3" rows="5" cols="30" size="60%" class="description" id="description3"></textarea></td>
                                <td style="border:none;"><input name="quantity3" id="quantity3" type="text" value="" size="25%"/></td>
                                <td style="border:none;"><input name="price3" id="price3" type="text" value="" size="25%"/></td>
                                <td style="border:none;"><a href="javascript:void(0);" onclick="removeRow(3);"><img src="../../images/hide.gif" alt="Delete"/></a><input name="inv_renewal3" type="hidden" id="inv_renewal3" class="inv_renewal"/></td>
                            </tr>
                        </table>
                        <table id="addedRows"></table>

                    </td>
                </tr>
                <tr class="other_tr">
                    <td>
                        <table rules="all" style="background:#fff; width: 80%">
                            <tr>
                                <td style="border:none;">
                                    Description
                                </td>
                                <td style="border:none;">
                                    Quantity
                                </td>
                                <td style="border:none;">
                                    Price
                                </td>
                                <td style="border:none;" id="image_add_other">
                                    <span style="float: right;" onclick="addMoreRows_other(this.form);">
                                        <a> <img src="../../images/show.png" alt="Add Row"/> </a>
                                    </span>
                                </td>
                            </tr>
                        </table>
                        <table style="border:none;" id="rowCountOther1" class="rowadded" >
                            <tr>
                                <td style="border:none;"><textarea name="descriptionOther1" rows="5" cols="30" size="60%" class="descriptionOther" id="descriptionOther1"></textarea></td>
                                <td style="border:none;"><input name="quantityOther1" id="quantityOther1" class="quantityOther" type="text" value="" size="25%"/></td>
                                <td style="border:none;"><input name="priceOther1" id="priceOther1" class="priceOther" type="text" value="" size="25%"/></td>
                                <td style="border:none;"><a href="javascript:void(0);" onclick="removeRowOther(1);"><img src="../../images/hide.gif" alt="Delete"/></a><input name="inv_renewalOther1" type="hidden" id="inv_renewalOther1" class="inv_renewalOther"/></td>
                            </tr>
                        </table>
                        <table id="addedRowsOther"></table>
                    </td>
                </tr>
                <tr>
                    <td>Comments <input type="text" id="comment" name="comment"/></td>
                </tr>
            </table>
            <input type="submit"  name="gpdf" id="gpdf" class="btn btn-primary" value="Generate/Preview PDF " onclick="
                    showPdf();
                    jQuery('#gform').attr('action', 'invoice_pdf.php');
                    return true;">
            <br/>
            <br/>
            <input type="submit"  name="ghtml" id="ghtml" class="btn btn-success" value="Generate/Print Invoice" onclick="
                    jQuery('#gform').attr('action', 'invoice_html.php');
                    return toSubmit();">
        </form>

    </div>    
</div>
<br>

<?php
include("footer.php");
?>




