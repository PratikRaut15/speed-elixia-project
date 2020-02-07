<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include_once("session.php");
include("loginorelse.php");
include_once("db.php");
include_once("../../constants/constants.php");
include_once("../../lib/system/Sanitise.php");
include_once("../../lib/system/DatabaseManager.php");
include("../../lib/bo/TeamManager.php");
include_once("../../lib/system/Date.php");
// include_once("../../lib/components/gui/datagrid.php");
include_once("../../lib/components/gui/objectdatagrid.php");
require_once ('../../lib/bo/CronManager.php');
class Invoice_Approval {
    
}

$cronm = new CronManager();
$db = new DatabaseManager();
$totalpayment = 0;
$month=date('Y-m');
$next_month = date('Y-m', strtotime('+1 month'));
$previous_month = date('Y-m', strtotime('-1 month'));
// print_r($previous_month); exit;
$details1 = array();
$customernos = $cronm->getMonthlySubscCust(1);
if (isset($customernos) && !empty($customernos)) {
 foreach ($customernos as $value) {
     $customerno=$value['customerno'];
     $invoice_month_type=$value['generate_invoice_month'];
     if ($invoice_month_type=='next') {
         $month= $next_month;
     } else if ($invoice_month_type=='previous') {
         $month= $previous_month;
     }
        $ledgerid = $cronm->getLedgerOfCustomer($customerno);
     if (isset($ledgerid) && !empty($ledgerid)) {
     $Datacap = new Invoice_Approval();
     $details = $cronm->getInvoiceData($ledgerid,$month);
     $Datacap->customerno = $details['customerno'];
        $Datacap->ledgerid = $ledgerid;
        $Datacap->simcardno = $details['simcardno'];
        $Datacap->invoiceno = $details['invoiceno'];
        $Datacap->start_date = $details['start_date'];
        $Datacap->end_date = $details['end_date'];
        $Datacap->expiry_date = $details['expiry_date'];
        $Datacap->noofdevices = $details['count1'];
        $Datacap->subscriptionprice = $details['unit_msp']; 
        $Datacap->state = $details['state'];
        
        $invoiceno = '';
        if ($details != NULL) {
            $datetime = date('Y-m-d H:i:s');
            if($details['currentMonthInv']==0){
                $Datacap->start_date = date('Y-m-d', strtotime($details['end_date'] . "+1 days")); //Default process
            }
            if($details['currentMonthInv']==1){
                $Datacap->start_date = date('Y-m-01', strtotime($datetime)); // Custom process 
            }
            $Datacap->end_date = date("Y-m-d", strtotime($start_date));
            $Datacap->expiry_date = date('Y-m-d', strtotime('+1 months', strtotime($datetime)));

            $Datacap->taxname = "4";
            if ($details['state_code'] == 27) {
                $tax_percent = 0.09;
                $Datacap->tax_cgst = round($details['total'] * $tax_percent);
                $Datacap->tax_sgst = round($details['total'] * $tax_percent);
                $Datacap->tax_igst = 0;
                $Datacap->amount = round($details['total'] + $tax_cgst + $tax_sgst);
                $gst = 'sgst';
            } else {
                $tax_percent = 0.18;
                $Datacap->tax_cgst = 0;
                $Datacap->tax_sgst = 0;
                $Datacap->tax_igst = round($details['total'] * $tax_percent);
                $Datacap->amount = round($details['total'] + $tax_igst);
                $gst = 'igst';
            }
            $Datacap->taxamount = $tax_cgst + $tax_sgst + $tax_igst;

            if ($details['customerno'] < 10) {
                $Datacap->invoiceno .= 'ESS' . '0' . $details['customerno'];
            } else {
                $Datacap->invoiceno .= 'ESS' . $details['customerno'];
            }
            if ($ledgerid < 10) {
                $Datacap->invoiceno .= '0' . $ledgerid . $details['invoiceid'];
            } else {
                $Datacap->invoiceno .= $ledgerid . $details['invoiceid'];
            }
            if ($details['renewal'] == -3) {
                $Datacap->lease = 'Lease';
            } else {
                $Datacap->lease = '';
            }
        }

       $details1[] = $Datacap;
       $invoice_data[]= (array) $Datacap;
   }
 }
}

if (isset($invoice_data) && !empty($invoice_data)) {
    foreach ($invoice_data as $value) {
        $insert_invoice_approval = $cronm->insertInvoiceApproval($value);
    }
}
    
     $invoice_approval_list = $cronm->getInvoiceApproval();
if (isset($invoice_approval_list) && !empty($invoice_approval_list)) {
     foreach ($invoice_approval_list as $data) {
        $aprroval_details[]=$data;
    }
}

 if (isset($_POST['approved_ajax'])) {
    $approved_array = $_POST['approved_ajax'];
    $data=explode(',',$approved_array);
    $iv_id=$data[0];
    if (isset($details['deviceid']) && !empty($details['deviceid'])) {
       $devices = implode(',', $details['deviceid']);
    }
    // print_r($details['deviceid']); exit;
    
     if (isset($iv_id) && isset($devices)) {
        $invoiceid = $cronm->updateInvoiceApproval($iv_id,$devices);
     }
   
    if (isset($invoiceid) && !empty($invoiceid)) {
        $vehicles = $details['vehicleid'];
        if (isset($vehicles) && !empty($vehicles)) {
            foreach ($vehicles as $data) {
                $cronm->invoiceVehicleMapping($invoiceid,$data);
            }
        }
        
    }

    // if (isset($update_invoice))
    // {
    //     $vehicles = $details['vehicleid'];
    //     if (isset($vehicles)) {
    //        foreach ($vehicles as $data) {
    //                 $update_invoice = $cronm->invoiceVehicleMapping($data);
    //             }
    //     }
    // }
}

date_default_timezone_set("Asia/Calcutta");
$today = date("d-m-Y");
$time= date("h:i");
$created_on=date("Y-m-d H:i:s");

include("header.php");
?>
<link rel="stylesheet" href="../../css/customer_verification.css">
<style>
  .panel{
    width:1800px !important;
}

.paneltitle{
      width: 1800px !important;
}
</style>
<br/>
<div class="panel">
    <?php
    ?>
    <div class="paneltitle" align="center">Invoice Approval List </div>
    <div>
        <div class="panelcontents">
            <div id="myGrid" class="ag-theme-fresh" style="height:500px;width:100%;margin:0 auto;border: 1px solid gray">
            </div>
        </div>
    </div>
</div>
<?php include("footer.php"); ?>
<script src="https://unpkg.com/ag-grid-enterprise@17.0.0/dist/ag-grid-enterprise.min.js"></script>
<script>
    var img;
    agGrid.LicenseManager.setLicenseKey("Elixia_Tech_Solutions_Ltd_MultiApp_1Devs16_March_2019__MTU1MjY5NDQwMDAwMA==c4c29e5702e62789dbdb799433c73545");
    var details = <?php echo json_encode($aprroval_details);?>;
    // var details = <?php echo $details; ?>;
    // console.log(details); return false;
    var gridOptions;
    columnDefs = [
    // {headerName: 'Customerno', cellRenderer:'editCellRenderer',width:70,suppressFilter:true},
    {headerName: 'Action', cellRenderer:'approveCellRenderer',width:80,suppressFilter:true},
    {headerName:'Inv Approval ID',field: 'iv_id',width:150,filter: 'agTextColumnFilter'},
    {headerName:'Customerno',field: 'customerno',width:150,filter: 'agTextColumnFilter'},
    {headerName:'Ledger ID',field: 'ledgerid',width:100,filter: 'agTextColumnFilter'},
    {headerName:'Invoice No.',field: 'invoiceno',width:120,filter: 'agTextColumnFilter'},
    {headerName:'Start Date', field:'start_date',width:120,filter: 'agTextColumnFilter'},
    {headerName:'End Date', field:'end_date',width:100,filter: 'agTextColumnFilter'},
    {headerName:'Expiry Date',field: 'inv_expiry',width:150,filter: 'agTextColumnFilter'},
    {headerName:'No of Devices',field: 'quantity',width:150,filter: 'agTextColumnFilter'},
    {headerName:'Subscription Price',field: 'subscription_price',width:150,filter: 'agNumberColumnFilter'},
    {headerName:'Amount',field: 'total_amount',width:100,filter: 'agNumberColumnFilter'},
    {headerName:'Tax Amount',field: 'tax_amount',width:120,filter: 'agNumberColumnFilter'},
    {headerName:'State', field:'state',width:100,filter: 'agTextColumnFilter'},
    {headerName:'', field:'lease',width:100,filter: 'agTextColumnFilter'}
    ];
    
    function approveCellRenderer(params){
        var approve_array= Object.values(params.data);

    return "<a href='' alt='Approve'  id='approve' class='approve' onclick='insertApprovedInvoice(this)' invoicedata='"+approve_array+"' title='Approve Invoice' ><img style='text-align:center; width:20px; height:20px;' src='../../images/adde.jpg'/></a>";
  }
    
gridOptions = {
enableFilter:true,
enableSorting: true,
floatingFilter:true,
rowSelection: 'single',
rowData:details,
animateRows:true,
masterDetail: true,
columnDefs: columnDefs,
components: {approveCellRenderer : approveCellRenderer},
onFilterChanged: function() {
getRowData();
},
};

var gridDiv = document.getElementById('myGrid');
new agGrid.Grid(gridDiv,gridOptions);

$('#date_value').on('input', function() {
var charCode = window.event.keyCode;

if($(this).val() < 0){
$(this).val('');
}
});

function insertApprovedInvoice (event) {
    
    var approved_data = event.getAttribute('invoicedata');
    // console.log(approved_data); return false;
          $.ajax({
            url:"invoice_approval.php", //the page containing php script
            type: "post", //request type,
            dataType: 'json',
            data: {approved_ajax:approved_data}
           //  success:function(data){
           //      alert('1'); return false;
           //          location.reload();
           //      if(data.status == 'success')
           //      {
           //          alert(1);
           //          location.reload();
           //      }
                
           // }
         });
     }

</script>
<br/>

