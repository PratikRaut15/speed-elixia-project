<?php
include_once("session.php");
include("loginorelse.php");
include_once("db.php");
include_once("../../constants/constants.php");
include_once("../../lib/system/Sanitise.php");
include_once("../../lib/components/gui/objectdatagrid.php");
include_once("../../lib/system/DatabaseManager.php");

//include_once("../../lib/system/Date.php");

class Invoice {
    
}

$db = new DatabaseManager();


$SQL = sprintf("SELECT  *
                FROM    product");
$db->executeQuery($SQL);

$x = 0;
$products = Array();
if ($db->get_rowCount() > 0) {
    while ($row = $db->get_nextRow()) {
        $product1['id'] = $row['id'];
        $product1['name'] = $row['name'];
        $products[] = $product1;
    }
}


if (isset($_POST['submitinv'])) {
    $invno = GetSafeValueString($_POST['invno'], "string");
    $invdate = GetSafeValueString(date("Y-m-d", strtotime($_POST['invdate'])), "string");
    $startdate = GetSafeValueString(date("Y-m-d", strtotime($_POST['startdate'])), "string");
    $enddate = GetSafeValueString(date("Y-m-d", strtotime($_POST['enddate'])), "string");
    $customerno = GetSafeValueString($_POST['cno'], "string");
    $customername = GetSafeValueString($_POST['cname'], "string");
    $invamt = GetSafeValueString($_POST['invamt'], "string");
    $status = GetSafeValueString($_POST['sid'], "string");
    $pending_amt = GetSafeValueString($_POST['pamt'], "string");
    $tax = GetSafeValueString($_POST['tid'], "string");
    $taxamt = GetSafeValueString($_POST['tamt'], "string");
    $product = GetSafeValueString($_POST['product'], "string");
    $ledgerid = GetSafeValueString($_POST['ledgerid'], "string");
    $uidlist = GetSafeValueString($_POST['uidlist'], "string");
    $statecode = GetSafeValueString($_POST['statecode'], "string");
    $quantity = GetSafeValueString($_POST['quantity'], "string");
    $misc = GetSafeValueString($_POST['misc1text'], "string");


    $due_date = date('Y-m-d', strtotime('+1 months', strtotime($invdate)));
    $todaysdate = date('Y-m-d H:i:s');
    $uidlist = explode(',', $uidlist);
    $uidlist = array_filter($uidlist);
    $uidlist = implode(',', $uidlist);

    if ($statecode == 27) {
        $cgst = $invamt * 0.09;
        $sgst = $invamt * 0.09;
        $igst = 0;
    } else {
        $cgst = 0;
        $sgst = 0;
        $igst = $invamt * 0.18;
    }
    $pdo = $db->CreatePDOConn();


    $sp_params = "'" . $invno . "'"
            . ",'" . $customerno . "'"
            . ",'" . $ledgerid . "'"
            . ",'" . $invdate . "'"
            . ",'" . $invamt . "'"
            . ",'" . $status . "'"
            . ",'" . $pending_amt . "'"
            . ",'" . $tax . "'"
            . ",'" . $cgst . "'"
            . ",'" . $sgst . "'"
            . ",'" . $igst . "'"
            . ",'" . $due_date . "'"
            . ",'" . $quantity . "'"
            . ",'" . $todaysdate . "'"
            . ",'" . $product . "'"
            . ",'" . $startdate . "'"
            . ",'" . $enddate . "'"
            . ",'" . $misc . "'"
            . ",'" . $uidlist . "'"
            . ",@is_executed";

    $queryCallSP = "CALL " . speedConstants::SP_INSERT_TAX_INVOICE . "($sp_params)";

    $arrResult = $pdo->query($queryCallSP);

    $outputParamsQuery = "SELECT    @is_executed AS is_executed";
    $outputResult = $pdo->query($outputParamsQuery)->fetch(PDO::FETCH_ASSOC);
    $db->ClosePDOConn($pdo);

    if ($outputResult['is_executed'] == 1) {
        header("location:invoicedata.php?msg=success");
    } else {
        header("location:invoicedata.php?msg=fail");
    }
}
//QUERY for display
$Display = array();
$SQL = "SELECT  i.invoiceid
                ,i.invoiceno
                ,i.customerno
                ,i.inv_date
                ,i.inv_amt
                ,i.status
                ,i.pending_amt
                ,i.tax
                ,i.tax_amt
                ,i.paid_amt
                ,i.paymentdate
                ,i.tds_amt
                ,i.unpaid_amt
                ,i.inv_expiry
                ,i.comment
                ,i.product_id 
                ,l.ledgername
        FROM    " . DB_PARENT . ".invoice i
        INNER JOIN ledger l ON l.ledgerid = i.ledgerid
        WHERE   i.isdeleted = 0 
        ORDER BY i.invoiceid DESC";
$db->executeQuery($SQL);
if ($db->get_rowCount() > 0) {
    while ($row = $db->get_nextRow()) {
        $Datacap = new Invoice();
        //$x++;
        $Datacap->invoiceid = $row['invoiceid'];
        $Datacap->invoiceno = $row['invoiceno'];
        $Datacap->customerno = $row['customerno'];
        $Datacap->clientname = $row['ledgername'];
        $Datacap->invdate = date("d-m-Y", strtotime($row['inv_date']));
        $Datacap->invamt = $row['inv_amt'];
        $Datacap->status = $row['status'];
        $Datacap->pamt = $row['pending_amt'];
        if ($row['tax'] == 1) {
            $Datacap->tax = 'ST';
        } elseif ($row['tax'] == 2) {
            $Datacap->tax = 'VAT';
        } elseif ($row['tax'] == 3) {
            $Datacap->tax = 'CST';
        } else {
            $Datacap->tax = 'NA';
        }
        $Datacap->taxamt = $row['tax_amt'];
        $Datacap->paid_amt = $row['paid_amt'];
        if (isset($row['paymentdate']) && $row['paymentdate'] != '0000-00-00') {// to provide empty date if not inserted
            $Datacap->paydate = date("d-m-Y", strtotime($row['paymentdate']));
        }
        $Datacap->tds_amt = $row['tds_amt'];
        $Datacap->upaid = $row['unpaid_amt'];
        if (isset($row['inv_expiry']) && $row['inv_expiry'] != '0000-00-00') {// to provide empty date if not inserted
            $Datacap->inv_expirydate = date("d-m-Y", strtotime($row['inv_expiry']));
        }
        $Datacap->comment = $row['comment'];
        foreach ($products as $data) {
            if ($row['product_id'] == $data['id']) {
                $Datacap->product = $data['name'];
            }
        }

        //$Datacap->x =$x;
        $Display[] = $Datacap;
    }
}
$dg = new objectdatagrid($Display);
$dg->AddColumn("Sr.No", "invoiceid");
$dg->AddColumn("Invoice No", "invoiceno");
$dg->AddColumn("Customer No", "customerno");
$dg->AddColumn("Client Name", "clientname");
$dg->AddColumn("Product Name", "product");
$dg->AddColumn("Invoice Date", "invdate");
$dg->AddColumn("Invoice Amt", "invamt");
$dg->AddColumn("Status", "status");
$dg->AddColumn("Paid Amt", "paid_amt");
$dg->AddColumn("Tax", "tax");
$dg->AddColumn("Tax Amt", "taxamt");
$dg->AddColumn("Pending Amt", "pamt");
$dg->AddColumn("Payment Date", "paydate");
$dg->AddColumn("TDS Amt", "tds_amt");
$dg->AddColumn("Unpaid Amt", "upaid");
$dg->AddColumn("Expiry Date", "inv_expirydate");
$dg->AddColumn("Comment", "comment");
$Datacap->generate = '<a href="invoice_pdf.php?gpdf=' . $row['pi_id'] . '&work=Proforma"><img src="../../images/pdf_icon.png"></img></a>';
$dg->AddRightAction("View", "../../images/edit.png", "invoice_edit.php?inid=%d");
$dg->SetNoDataMessage("No Invoices");
$dg->AddIdColumn("invoiceid");


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
<div class="panel">
    <div class="paneltitle" align="center">Add Invoice Data</div> 
    <div class="panelcontents">
        <form method="post" name="invoiceform" id="invoiceform" action="invoicedata.php" onsubmit="ValidateForm();
                return false;" enctype="multipart/form-data">
            <div style="height:20px;">
                <span id="general_success" style="display:none; color: #00FF00">Data Added Successfully.</span> 
                <span id="invno_error" style="display:none; color: #FF0000">Please Enter Invoice No.</span> 
                <span id="invdate_error" style="display:none; color: #FF0000">Please Enter Invoice Date.</span> 
                <span id="cid_error" style="display:none; color: #FF0000">Please Select Customer No.</span> 
                <span id="invamt_error" style="display:none; color: #FF0000">Please Enter Invoice Amt.</span> 
                <span id="sid_error" style="display:none; color: #FF0000">Please Select Status.</span>           
                <span id="pamt_error" style="display:none; color: #FF0000">Please Enter Pending Amt.</span> 
                <span id="tid_error" style="display:none; color: #FF0000">Please Select Tax.</span> 
                <span id="tamt_error" style="display:none; color: #FF0000">Please Enter Tax Amt.</span>
                <span id="success_msg" style="display:none; color: green">Successfully added Invoice.</span>
                <span id="fail_msg" style="display:none; color: red">Invoice insertion failed.</span>
            </div>
            <table width="100%">
                <tr>
                    <td width="10%">
                        <input type="hidden" name="price" id="price" />
                        <input type="hidden" name="quantity" id="quantity" />
                        <input type="hidden" name="renewal" id="renewal" />
                        <input type="hidden" name="uidlist" id="uidlist" />
                        <input type="hidden" name="statecode" id="statecode" />
                    </td>
                    <td></td>
                    <td></td>
                </tr>
                <tr><td>Client</td>
                    <td>    
                        <input  type="text" name="icustomer" id="icustomer" size="25" value="<?php
                        if (isset($_POST['icustomer'])) {
                            echo $_POST['icustomer'];
                        }
                        ?>" autocomplete="off" placeholder="Enter Customer No Or Name" onkeyup="getCust()" />
                        <input type="hidden" name="cno" id="cno" value="<?php
                        if (isset($_POST['cno'])) {
                            echo $_POST['cno'];
                        }
                        ?>"/>
                        <input type="hidden" name="cname" id="cname" value="<?php
                        if (isset($_POST['cname'])) {
                            echo $_POST['cname'];
                        }
                        ?>"/>
                    </td>
                    <td></td>
                </tr>
                <tr>
                    <td>Ledger</td>
                    <td>
                        <input  type="text" name="iledger" id="iledger" size="25" value="<?php
                        if (isset($_POST['iledger'])) {
                            echo $_POST['iledger'];
                        }
                        ?>" autocomplete="off" placeholder="Enter Ledger Name" onkeyup="getLedger()" />
                        <input type="hidden" name="ledgerid" id="ledgerid" value="<?php
                        if (isset($_POST['ledgerid'])) {
                            echo $_POST['ledgerid'];
                        }
                        ?>" />
                    </td>
                    <td></td>
                </tr>
                <tr>
                    <td>Product :</td> 
                    <td>
                        <select id="product" name="product" onchange="getInvoiceNo(this.value);"/>
                <option value="0">Choose product</option>
                <option value="1">Device</option>
                <option value="2">Renewal</option>
                </select>
                </td> 
                <td></td>
                </tr>
                <tr>
                    <td>Invoice Number</td>
                    <td>
                        <input type="text" name ="invno" id ="invno" value ="">
                    </td>  
                    <td></td>
                </tr>
                <tr>
                    <td>Invoice Date</td>
                    <td>
                        <input type="text" name ="invdate" id ="invdate" placeholder="dd-mm-yyyy" value =""><button id="trigger1">...</button>
                    </td>  
                    <td></td>
                </tr>
                <tr>
                    <td>Service Start Date</td>
                    <td>
                        <input type="text" name ="startdate" id ="startdate" placeholder="dd-mm-yyyy" value =""><button id="trigger2">...</button>
                    </td>  
                    <td></td>
                </tr>
                <tr>
                    <td>Service End Date</td>
                    <td>
                        <input type="text" name ="enddate" id ="enddate" placeholder="dd-mm-yyyy" value =""><button id="trigger3">...</button>
                    </td>  
                    <td></td>
                </tr>
                <tr class="veh_tr">
                    <td></td>
                    <td>
                        Alloted Vehicle
                    </td>
                    <td></td>
                </tr>
                <tr class="veh_tr">
                    <td></td>
                    <td colspan="80%">
                        <div id="vehicle_list"></div>
                    </td>
                    <td></td>
                </tr>
                <tr class="device_tr">
                    <td>Type</td>
                    <td>
                        <input name="basic" id="basic" type="radio" value="0" onclick="HideAdv();"checked=""/> &nbsp;Basic &nbsp;&nbsp;&nbsp;&nbsp;
                        <input name="basic" id="advance" type="radio" value="1" onclick="DisplayAdv();"/> &nbsp; Advanced &nbsp;&nbsp;&nbsp;&nbsp;
                    </td>
                    <td></td>
                </tr>

                <tr id="adv_tr">
                    <td></td>
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

                        <input type="checkbox" name="comm" id="comm" value="1">&nbsp; Two way Communication  &nbsp;
                        Quantity <input name="commquantity" id="commquantity" type="text" value="" size="3"/>  &nbsp;
                        Price <input name="commprice" id="commprice" type="text" value="" size="6"/>  &nbsp;<br>

                        <input type="checkbox" name="port" id="port" value="1">&nbsp; Portable  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        Quantity <input name="portquantity" id="portquantity" type="text" value="" size="3"/>  &nbsp;
                        Price <input name="portprice" id="portprice" type="text" value="" size="6"/>  &nbsp;<br>

                    </td>
                </tr> 

                <tr>
                    <td>Amount</td>
                    <td>
                        <input type="text" name ="invamt" id ="invamt" value ="" onblur="getPendingamt();">
                    </td>   
                    <td></td>
                </tr>
                <tr>
                    <td>Status</td>
                    <td>    
                        <select name ="sid" >
                            <option value ="pending">Pending</option>
                            <option value ="paid">Paid</option>
                        </select>
                    </td>
                    <td></td>
                </tr>
                <tr>
                    <td>Pending Amount</td>
                    <td><input type="text" name ="pamt" id ="pamt" value=""></td>
                    <td></td>
                </tr>
                <tr>
                    <td>Tax</td>
                    <td>    
                        <select name ="tid" id="tid" onchange="calculateTax(this.value)">
                            <option value ="0" selected>Select Tax</option>
                            <option value ="1">ST</option>
                            <option value ="2">VAT</option>
                            <option value ="3">CST</option>
                            <option value ="4">GST</option>
                        </select>
                    </td>
                    <td></td>
                </tr>
                <tr>
                    <td>Tax Amount</td>
                    <td><input type="text" name ="tamt" id ="tamt" value=""></td>
                    <td></td>
                </tr>
            </table>
            <input type="submit" id="submitinv" name="submitinv" class="btn btn-default" value="Create Invoice">
        </form>
    </div>
</div>
<!------------------------------------upload excel---------------------------------------------------->
<!--</br>
<div class="panel">
     <div class="paneltitle" align="center">Upload Invoice File</div> 
     <div class="panelcontents">
         <form name="dataupload" id='uploadPeople' action="" method="post" onsubmit='return validate_upload();' enctype="multipart/form-data">
 <table width="80%">
             <tr>
                 <td colspan="100%" style="display:none;text-align:center;" id='StatusTD'>
                     <span id="loading" style='display:none;'>Loading....</span>
                     <span id="Status" ></span>
                 </td>
             </tr>
             <tr>
                 <td>Upload File:<br/><a href="upload_invoice.xlsx">Download Example File</a></td>
                 <td><input type="file" name="invoiceFile" id="invoiceFile"   required></td>
             </tr>
             <tr>
                 <td colspan="100%"><button type="submit" class="btn btn-default" name="uploadFile">Upload</button></td>
             </tr>
 </table>
     </form>
     </div>
</div>-->
<!-----------------------------------------invoice list--------------------------------------------->
<br/>
<div class="panel">
    <div class="paneltitle" align="center">Invoice Data List</div>
    <div class="panelcontents">
        <?php $dg->Render(); ?>
    </div>

</div>
<br/>
<?php
include("footer.php");
?>
<script>
    $(document).ready(function () {
        Calendar.setup(
                {
                    inputField: "invdate", // ID of the input field
                    ifFormat: "%d-%m-%Y", // the date format
                    button: "trigger1" // ID of the button
                });
        Calendar.setup(
                {
                    inputField: "startdate", // ID of the input field
                    ifFormat: "%d-%m-%Y", // the date format
                    button: "trigger2" // ID of the button
                });
        Calendar.setup(
                {
                    inputField: "enddate", // ID of the input field
                    ifFormat: "%d-%m-%Y", // the date format
                    button: "trigger3" // ID of the button
                });

        jQuery("#adv_tr").hide();

<?php
if ($_REQUEST['msg'] == 'success') {

    echo 'jQuery("#success_msg").show();';
    echo 'jQuery("#success_msg").fadeOut(6000);';
} elseif ($_REQUEST['msg'] == 'fail') {
    echo 'jQuery("#fail_msg").show();';
    echo 'jQuery("#fail_msg").fadeOut(6000);';
}
?>
    });

    function getPendingamt() {//
        var inv_amt = jQuery("#invamt").val();
        jQuery("#pamt").val(inv_amt);

    }
    function ValidateForm() {

        var invno_id = jQuery('#invno').val();
        var invdate_id = jQuery('#invdate').val();
        var cid_id = jQuery('#cid').val();
        var invamt_id = jQuery('#invamt').val();
        var sid_id = jQuery('#sid').val();
        var pamt_id = jQuery('#pamt').val();
        var tid_id = jQuery('#tid').val();
        var tamt_id = jQuery('#tamt').val();
        if (invno_id == "")
        {
            jQuery("#invno_error").show();
            jQuery("#invno_error").fadeOut(6000);
        } else if (invdate_id == "")
        {
            jQuery("#invdate_error").show();
            jQuery("#invdate_error").fadeOut(6000);
        } else if (cid_id == "0")
        {
            jQuery("#cid_error").show();
            jQuery("#cid_error").fadeOut(6000);
        } else if (invamt_id == "")
        {
            jQuery("#invamt_error").show();
            jQuery("#invamt_error").fadeOut(6000);
        } else if (sid_id == "0")
        {
            jQuery("#sid_error").show();
            jQuery("#sid_error").fadeOut(6000);
        } else if (pamt_id == "")
        {
            jQuery("#pamt_error").show();
            jQuery("#pamt_error").fadeOut(6000);
        } else if (tid_id == "0")
        {
            jQuery("#tid_error").show();
            jQuery("#tid_error").fadeOut(6000);
        } else if (tamt_id == "")
        {
            jQuery("#tamt_error").show();
            jQuery("#tamt_error").fadeOut(6000);
        } else {
            jQuery("#invoiceform").submit()
        }
    }
    function getCust() {
        jQuery("#icustomer").autocomplete({
            source: "route_ajax.php?customername=getcust",
            select: function (event, ui) {

                /*clear selected value */
                jQuery(this).val(ui.item.value);
                jQuery('#cno').val(ui.item.cid);
                jQuery('#cname').val(ui.item.cname);
                jQuery('#price').val(ui.item.unitprice);
                jQuery('#renewal').val(ui.item.renewal);
                jQuery("#invamt").val('');
                jQuery("#pamt").val('');
                jQuery("#tamt").val('');
                return false;
            }
        });
    }
    function getLedger() {
        var customerno = jQuery('#cno').val();
        if (customerno != '') {
            jQuery("#iledger").autocomplete({
                source: "route_ajax.php?ledgername=" + customerno,
                select: function (event, ui) {
                    /*clear selected value */
                    jQuery("#iledger").val(ui.item.value);
                    jQuery('#ledgerid').val(ui.item.lid);
                    jQuery('#statecode').val(ui.item.state_code);
                    jQuery("#invamt").val('');
                    jQuery("#pamt").val('');
                    jQuery("#tamt").val('');
                    jQuery("#vehicle_list").html('');
                    return false;
                }
            });
        } else {
            alert("Please select correct customer no.");
            jQuery("#icustomer").focus();
        }
    }
    function getInvoiceNo(invtype) {
        var price = jQuery("#price").val();
        var selected_cust_grp = jQuery("#cno").val();
        var ledger = jQuery("#ledgerid").val();
        if (selected_cust_grp == '') {
            alert("Please Enter Correct Customer.");
            return false;
        } else if (ledger == '') {
            alert("Please Enter Correct Ledger.");
            return false;
        } else {
            jQuery.ajax({
                type: "POST",
                url: "invoice_ajax.php",
                cache: false,
                data: {work: "get_invoiceno",
                    cust_grp: selected_cust_grp,
                    invtype: invtype,
                    ledger: ledger
                },
                success: function (res) {
                    jQuery("#invno").val(res);
                }
            });
        }

        if (invtype == 1) {
            jQuery.ajax({
                type: "POST",
                url: "invoice_ajax.php",
                cache: false,
                data: {
                    work: "allotedvehnewdevice",
                    cust_no: selected_cust_grp,
                    ledgerid: ledger
                },
                success: function (res) {
                    var map = jQuery.parseJSON(res);
                    jQuery("#vehicle_list").html("");
                    if (map == '0') {
                        var status = 'no new alloted vehicles';
                        jQuery("#vehicle_list").append(status);
                    } else {
                        jQuery("#quantity").val(map.length);
                        jQuery("#invamt").val(Math.round((map.length * price) + (map.length * price * 0.18)));
                        jQuery("#pamt").val(map.length * price);
                        printMapvehicle(map);
                    }
                }
            });
        }
    }
    function printMapvehicle(map)
    {
        var print = '';
        jQuery(map).each(function (i, v) {
            if (v.vehicleid > -1 && jQuery('#to_vehicle_div_' + v.vehicleid).val() == null) {
                var uidlist = jQuery("#uidlist").val();
                jQuery("#uidlist").val(uidlist + "," + v.uid);
                var div = document.createElement('div');
                var remove_image = document.createElement('img');
                remove_image.src = '../../images/boxdelete.png';
                remove_image.className = 'clickimage';
                remove_image.onclick = function () {
                    removeVehicle(v.vehicleid, v.uid);
                };
                div.className = 'recipientbox';
                div.id = 'to_vehicle_div_' + v.vehicleid;
                div.innerHTML = "<span>" + v.vehicleno + "</span><input type='hidden' class='v_list_element' name='to_vehicle_" + v.vehicleid + "' value= '" + v.vehicleid + "'/>";
                jQuery("#vehicle_list").append(div);
                jQuery(div).append(remove_image);

            }
        });
    }
    function removeVehicle(vehicleid, uid) {
        var price = jQuery("#price").val();
        var amount = jQuery("#invamt").val();
        var mapveh = jQuery("#quantity").val();
        var uidlist = jQuery("#uidlist").val();
        uidlist = uidlist.replace(',' + uid, '');
        jQuery("#uidlist").val(uidlist);
        jQuery('#to_vehicle_div_' + vehicleid).remove();
        jQuery("#quantity").val(mapveh - 1);
        if (amount > 0) {
            jQuery("#invamt").val(Math.round(((mapveh - 1) * price) + ((mapveh - 1) * price * 0.18)));
            jQuery("#pamt").val(jQuery("#invamt").val());
            jQuery("#tamt").val(Math.round((mapveh - 1) * price * 0.18));
        }
    }

    function calculateTax(id) {
        if (id == 4) {
            var quantity = jQuery("#quantity").val();
            var price = jQuery("#price").val();
            jQuery("#tamt").val(Math.round(quantity * price * 0.18));
        }
    }

    function HideAdv() {
        jQuery("#adv_tr").hide();
    }
    function DisplayAdv() {
        jQuery("#adv_tr").show();
    }

</script>