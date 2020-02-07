<?php
    include_once "session.php";
    include "loginorelse.php";
    include_once "db.php";
    include_once "../../constants/constants.php";
    include_once "../../lib/system/Sanitise.php";
    include_once "../../lib/components/gui/objectdatagrid.php";
    include_once "../../lib/system/DatabaseManager.php";
    include "header.php";
    $db = new DatabaseManager();
    $exportInput = '';
    $Display = array();
    $dg = new objectdatagrid($Display);
    if (isset($_REQUEST['action']) && $_REQUEST['action'] == "getLedger") {
        $customername = $_POST['customername'];
        $customerno = $_POST['customerno'];
        $SQL = "SELECT * FROM " . DB_PARENT . ".invoice WHERE customerno = " . $_POST['customerno'] . " AND isdeleted=0 ORDER BY invoiceid DESC";
        $db->executeQuery($SQL);
        if ($db->get_rowCount() > 0) {
            while ($row = $db->get_nextRow()) {
                $Datacap = new stdClass();
                $Datacap->invoiceid = $row['invoiceid'];
                $Datacap->invoiceno = $row['invoiceno'];
                $Datacap->customerno = $row['customerno'];
                $Datacap->clientname = $row['clientname'];
                $Datacap->invdate = date("d-m-Y", strtotime($row['inv_date']));//date('Y-m-d', strtotime("+30 days"));
                $Datacap->invexpirydate = date("d-m-Y", strtotime("+30 days", strtotime($Datacap->invdate)));
                $Datacap->invamt = $row['inv_amt'];
                $Datacap->status = $row['status'];
                $Datacap->pamt = $row['pending_amt'];
                $Datacap->tax = $row['tax'];
                $Datacap->taxamt = $row['tax_amt'];
                $Datacap->paymode = $row['pay_mode'];
                $Datacap->paid_amt = $row['paid_amt'];
                if (isset($row['paymentdate']) && $row['paymentdate'] != '0000-00-00') {
                    $Datacap->paydate = date("d-m-Y", strtotime($row['paymentdate']));
                }
                $Datacap->tds_amt = $row['tds_amt'];
                $Datacap->upaid = $row['unpaid_amt'];
                if (isset($row['inv_expiry']) && $row['inv_expiry'] != '0000-00-00') {
                    $Datacap->inv_expirydate = date("d-m-Y", strtotime($row['inv_expiry']));
                }
                $Datacap->comment = $row['comment'];
                $Display[] = $Datacap;
            }
        }
        if (count($Display) > 0) {
            $dataString = "exportData(" . $customerno . ")";
            $exportInput = 'Export To Excel '.' <input type="button" title="Export Raw Data" id="dataQuery" name="dataQuery" value="" onclick="' . $dataString . '"/>';
        }
        $dg = new objectdatagrid($Display);
        $dg->AddColumn("Sr.No", "invoiceid");
        $dg->AddColumn("Invoice No", "invoiceno");
        $dg->AddColumn("Customer No", "customerno");
        $dg->AddColumn("Client Name", "clientname");
        $dg->AddColumn("Invoice Date", "invdate");
        $dg->AddColumn("Invoice Exp.Date", "invexpirydate");
        $dg->AddColumn("Invoice Amt", "invamt");
        $dg->AddColumn("Status", "status");
        $dg->AddColumn("Paid Amt", "paid_amt");
        $dg->AddColumn("Tax", "tax");
        $dg->AddColumn("Tax Amt", "taxamt");
        $dg->AddColumn("Payment Mode", "paymode");
        $dg->AddColumn("Pending Amt", "pamt");
        $dg->AddColumn("Payment Date", "paydate");
        $dg->AddColumn("TDS Amt", "tds_amt");
        $dg->AddColumn("Unpaid Amt", "upaid");
        $dg->AddColumn("Expiry Date", "inv_expirydate");
        $dg->AddColumn("Comment", "comment");
        $dg->AddRightAction("View", "../../images/edit.png", "invoice_edit.php?inid=%d");
        $dg->SetNoDataMessage("No Invoices");
        $dg->AddIdColumn("invoiceid");

    } else {
        $dg->SetNoDataMessage("");
    }

$start_date = date('d-m-Y', strtotime('first day of April this year'));
$end_date = date('d-m-Y'); 

?>
<link rel="stylesheet" href="../../css/invoicePayment.css">

<style>
label{
    display: inline !important;
} 
</style>
<br/>
<!-- <div class="panel">
    <div class="paneltitle" align="center">Search Customer Ledger</div>
    <div class="panelcontents">
        <form method="post" id='frmCustomerLedger' action="customerLedger.php?action=getLedger" >
            <table>
                <tr>
                    <td>Customer </td>
                    <td>

                        <input type="hidden" id="customerno" name="customerno" value="<?php
                        if (isset($customerno)) {
                            echo $customerno;
                        }
                        ?>"/>
                    </td>
                    <td>
                        <!-- <input type="button" name="search" value="Search"  onclick='frmValidate();'/> -->
                    </td>
                </tr>
            </table>
        </form>
    </div>
</div>
<br/>
<br/>
<div  class="panel">
    <div class="paneltitle" align="center">LEDGER PDF</div>
        <form name="ledger_pdf" id="ledger_pdf" method="post" action="ledger_pdf.php" target="_blank">
            <label>Customer</label>
            <input  type="text" name="customername" id="customername"  autocomplete="off" placeholder="Enter Customer Name or No." onkeypress="getCustomer();"/>    
        &nbsp;&nbsp;
            <label>Ledger</label>
            <select name="ledger_name" id="ledger_name" style="width:150px !important;s">
                <option value="0">Select Ledger</option>
            </select>  
           <label>FROM DATE</label>

            <input type="text" name="fromdate" id="fromdate" placeholder="dd-mm-yyyy" value="<?php echo $today; ?>" required>
            <label>TO DATE
              </label>

            <input type="text" name="todate" id="todate" placeholder="dd-mm-yyyy" value="<?php echo $today; ?>" required>
            <input type="hidden" name="ledgerid" id="ledgerid" value="<?php echo $id ?>">
            <input type="submit"  name="ledger_pdf_btn" id="ledger_pdf_btn" onclick="generatePDF_ledger();">
        </form>
</div>
<br>
<!-- <div class="panel">
    <div class="paneltitle" align="center">Ledgers <span style="float: right;"><?php echo $exportInput; ?></span></div>
    <div class="panelcontents">
        <?php $dg->Render();?>
    </div>
</div> -->
<br/>

<?php
    include "footer.php";
?>
<script type="text/javascript">
function exportData(customerno) {
    window.open("route_ajax.php?paymentDue=1&customerno=" + customerno, '_blank');
}
function frmValidate() {
    var customername = jQuery("#customername").val();
    if(customername == '') {
        alert("Please Select Customer");
    } else {
        jQuery("#frmCustomerLedger").submit();
    }
}
function getCustomer() {
        jQuery("#customername").autocomplete({
            source: "route_ajax.php?customername=getcustomer",
            select: function (event, ui) {
                jQuery(this).val(ui.item.value);
                jQuery('#customerno').val(ui.item.cid);
               var customerno = ui.item.cid;

                jQuery.ajax({
                type: "POST",
                url: "ledger_ajax.php",
                cache: false,
                data: {
                    work: "getMappedLedger", custno: customerno
                },
                success: function(data){
                var data=JSON.parse(data);
                $('#ledger_name').html("");
                $('#ledger_name').append('<option value = "0">'+"Select Ledger"+'</option>');
                //<-------- add this line
                $.each(data ,function(i,text){
                  $('#ledger_name').append('<option value = '+text.ledgerid+'>'+text.ledgerid+'-'+text.ledgername+'</option>');
                });
    }
    });
}
});
    }
</script>
<script>

 jQuery(document).ready(function () {
    jQuery('#fromdate').datepicker({
        dateFormat: "dd-mm-yy",
        language: 'en',
        autoclose: 1,
        startDate: Date()
    });
   jQuery('#todate').datepicker({
        dateFormat: "dd-mm-yy",
        language: 'en',
        autoclose: 1,
        startDate: Date()
    });

   jQuery('#fromdate').val('<?php echo $start_date ?>'); 
   jQuery('#todate').val('<?php echo $end_date ?>');    

    $("#ledger_pdf_btn").attr('disabled','disabled');
    });

    function generatePDF_ledger(){

        if($("#ledger_name").val()==0){
            alert("Please Select a Ledger");
            $("#ledger_pdf_btn").attr('disabled','disabled');
        } 
        else{
           $("#ledger_pdf").submit();
        }
    }
    
    $("#ledger_name").change(function(){
        if($("#ledger_name").val()==0){
            $("#ledger_pdf_btn").attr('disabled','disabled');
        }
        else{
            $("#ledger_pdf_btn").attr('disabled',false);
        }
    });
    </script>
<style>
    #dataQuery{
        background: url(../../images/xls.gif);
        /*border: 1px solid black;*/

        height: 33px;
        width: 33px;
    }

#ledger_pdf_btn{
        background: url(../../images/pdf_icon.png);
        /*border: 1px solid black;*/
        color: transparent;
        border:none;
        height: 33px;
        width: 33px;
    }

</style>
