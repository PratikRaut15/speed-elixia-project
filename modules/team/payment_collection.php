<?php
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

class Payment_Collection {
    
}

function display($data) {
    echo $data;
}
$db = new DatabaseManager();
$totalpayment = 0;
//QUERY for display
$Display = array();
$SQL = "SELECT  ipm.*
                ,i.invoiceno
                ,c.customerno
                ,c.customercompany
                ,t.name as collected_by
                ,t.teamid as collectedby_id
                ,t1.name as createdBy
        FROM " . DB_PARENT . ".invoice_payment_mapping ipm
        LEFT OUTER JOIN " . DB_PARENT . ".team t ON t.teamid = ipm.teamid
        LEFT OUTER JOIN " . DB_PARENT . ".team t1 ON t1.teamid = ipm.created_by
        LEFT OUTER JOIN " . DB_PARENT . ".customer c ON c.customerno = ipm.customerno 
        LEFT OUTER JOIN " . DB_PARENT . ".invoice i ON ipm.invoiceid = i.invoiceid
        Where ipm.isdeleted=0
        ORDER BY ipm.ip_id DESC";
$db->executeQuery($SQL);
if ($db->get_rowCount() > 0) {
    while ($row = $db->get_nextRow()) {
        $Datacap = new Payment_Collection();
        //$x++;
        $Datacap->ip_id = $row['ip_id'];
        $Datacap->customercompany = $row['customercompany'];
        $Datacap->payment_mode = $row['pay_mode'];
        $Datacap->paid_amt = $row['paid_amt'];
        // $Datacap->tds_amt = $row['tds_amt'];
        $Datacap->bank_name = $row['bank_name'];
        $Datacap->cheque_no = $row['cheque_no'];
        $Datacap->bank_branch = $row['bank_branch'];
        // $Datacap->bad_debts = $row['bad_debts'];
        $Datacap->cheque_status = $row['cheque_status'];
        $Datacap->status = $row['status'];
        $Datacap->remark = $row['remark'];
        $Datacap->invoiceno = $row['invoiceno'];
        $Datacap->collected_by = $row['collected_by'];
        $Datacap->payment_date = date("d-m-Y", strtotime($row['paymentdate']));
        $Datacap->cheque_date = date("d-m-Y", strtotime($row['cheque_date']));
        $Datacap->created_by = $row['createdBy'];
        $Datacap->created_on = date("d-m-Y", strtotime($row['created_on']));
        
        if (isset($row['payment_date']) && $row['payment_date'] != '0000-00-00') {// to provide empty date if not inserted
            $Datacap->payment_date = date("d-m-Y", strtotime($row['payment_date']));
        }
         if (isset($row['cheque_date']) && $row['cheque_date'] != '0000-00-00') {// to provide empty date if not inserted
            $Datacap->cheque_date = date("d-m-Y", strtotime($row['cheque_date']));
        }
        else
        {
          $Datacap->cheque_date='NA';
        }
        if (isset($row['cheque_date']) && $row['cheque_date'] != '0000-00-00') {// to provide empty date if not inserted
            $Datacap->cheque_date = date("d-m-Y", strtotime($row['cheque_date']));
        }
        else
        {
          $Datacap->cheque_date='NA';
        }
        $Display[] = $Datacap;
        $totalpayment+=round($Datacap->paid_amt,2);
    }
}

date_default_timezone_set("Asia/Calcutta");
$today = date("d-m-Y");
$time= date("h:i");
$created_on=date("Y-m-d H:i:s");

include("header.php");
?>
<!-- <link rel="stylesheet" href="../../css/customer_verification.css"> -->
<style>
/*#dataQuery{
        background: url(../../images/xls.gif);
        

        height: 33px;
        width: 33px;
    }*/
/*.panel{
    width:1324px !important;
}*/
/*.paneltitle{
    width: 1308px !important;
    margin-left: 235px !important;;
}*/
</style>
<link rel="stylesheet" href="../../css/invoicePayment.css">
<div class="panel">
  <div class="paneltitle" align="center">Payment Collection</div> 
  <div class="panelcontents">
    <div class="center">
      <form name="payment_collection" id="payment_collection">
        <label>Customer</label>
        <input type="text" name="customername" id="customername" size="30" value="" autocomplete="on" placeholder="Enter Customer Name or number" onkeypress="getCustomer();"/>
        <label>Invoice Number</label>
            <select name="invoiceno" id="invoiceno">
              <option value="0">Select Invoice Number</option>
            </select> 
        <label>Payment Mode</label>
            <select name="payment_mode" id="payment_mode">
              <option value="0">Select Mode</option>
              <option value="1">Cheque</option>
              <option value="2">Cash</option>
              <option value="3">Online</option>
            </select> 
        <label>Paid Amount</label>
          <input type="text" name="paid_amount" id="paid_amount" value="" autocomplete="off" placeholder="Enter Paid Amount"/>
        
       <!--  <label>TDS Amount</label>
          <input type="text" name="tds_amount" id="tds_amount" size="30" value="" autocomplete="off" placeholder="Enter TDS Amount"/> -->
        <div name="cheque_payment" id="cheque_payment">
          <label>Cheque Number</label>
          <input type="text" name="cheque_number" id="cheque_number" size="6" value="" autocomplete="off" placeholder="Enter Cheque Number"/>
          <label>Cheque Date</label>
          <input type="text" name="cheque_date" id="cheque_date" value="<?php echo $today; ?>"/>
           <label>Cheque Status</label>
            <select name="cheque_status" id="cheque_status">
              <!-- <option value="0">Select Cheque Status</option> -->
              <option value="1" selected >Received</option>
              <!-- <option value="2">Deposited</option>
              <option value="3">Cleared</option> -->
            </select>
            </br>
             <label>Bank Name</label>
            <input type="text" name="bank_name" id="bank_name" style="text-transform:capitalize;" placeholder="Enter Bank's Name"/> 
            
          <label>Branch</label>
            <input type="text" name="bank_branch" id="bank_branch" style="text-transform:capitalize;" placeholder="Enter Bank's Branch"/>
          </div>
          <div>
          <label>Collected By</label>
            <input type="text" name="collectedby" id="collectedby" size="30" value="" autocomplete="on" placeholder="Enter CRM person Name" onkeypress="getCollectedBy();"/>
          <label>Status</label>
            <select name="status" id="status">
              <!-- <option value="0">Select Status</option> -->
              <option value="1" selected >Collected</option>
              <!-- <option value="2">Received</option>
              <option value="3">Realized</option> -->
            </select>
          <label>Payment Date</label>
          <input type="text" name="payment_date" id="payment_date" value="<?php echo $today; ?>"/>
          <label>Remark</label>
           <input type="text" name="remark" id="remark" value="" autocomplete="off" placeholder="Enter Remark"/>
</div>
         
        <input type="hidden" name="customerno" id="customerno" value=""/>
        <input type="hidden" name="collectedby_id" id="collectedby_id" value=""/>
         <input type="hidden" name="inv_amnt" id="inv_amnt" value=""/>
        <input type="button" name="submit" id="submit" value="Submit" onclick="submitPaymentCollection();" style="margin-left:40%;"/>
           
      </form>
    </div>
  </div>
</div>
</br>
<div>
     <div style="background-color: #000000;color: #ffffff;padding: 8px;font-weight: bold;" align="center">Payment Collection List
      <span style="float: center; margin: 20px">  </span>
        <span id="total_inv_amount" style="float: center;">Total Collection : Rs <?php echo($totalpayment); ?> /- </span>
    </div>
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
    var details = <?php echo json_encode($Display)?>;
    // console.log(details); return false;
    var gridOptions;
    columnDefs = [
    // {headerName: 'Customerno', cellRenderer:'editCellRenderer',width:70,suppressFilter:true},
    {headerName: 'Edit', cellRenderer:'editCellRenderer',width: 70,suppressFilter:true},
    {headerName:'Sr.No',field: 'ip_id',width:90,filter: 'agTextColumnFilter'},
    {headerName:'Customer',field: 'customercompany',filter: 'agTextColumnFilter'},
    {headerName:'Payment Mode',field: 'payment_mode',width:140,filter: 'agTextColumnFilter'},
    {headerName:'Paid Amount', field:'paid_amt',width:140,filter: 'agTextColumnFilter'},
    {headerName:'TDS Amount', field:'tds_amt',width:140,filter: 'agTextColumnFilter'},
    {headerName:'Cheque No',field: 'cheque_no',width:120,filter: 'agTextColumnFilter'},
    {headerName:'Bank Name',field: 'bank_name',width:120,filter: 'agTextColumnFilter'},
    {headerName:'Bank Branch',field: 'bank_branch',width:120,filter: 'agTextColumnFilter'},
    {headerName:'Bad Debts',field: 'bad_debts',width:120,filter: 'agNumberColumnFilter'},
    {headerName:'Cheque Status',field: 'cheque_status',width:140,filter: 'agTextColumnFilter'},
    {headerName:'Collected By', field:'collected_by',width:120,filter: 'agTextColumnFilter'},
    {headerName:'Status',field: 'status',width:120,filter: 'agTextColumnFilter'},
    {headerName:'Remark',field: 'remark',width:120,filter: 'agTextColumnFilter'},
    {headerName:'Payment Date',field: 'payment_date',width:140,filter: 'agTextColumnFilter'},
    {headerName:'Cheque Date',field: 'cheque_date',width:120,filter: 'agTextColumnFilter'},
    {headerName:'Created By', field:'created_by',width:120,filter: 'agTextColumnFilter'},
    {headerName:'Created On', field:'created_on',width:120,filter: 'agTextColumnFilter'}
    ];
    
    function editCellRenderer(params){
    return "<a href='payment_collection_edit.php?ip_id="+params.data.ip_id+"' alt='Edit' title='payment Collection Edit' target='_blank' ><img style='text-align:center; width:20px; height:20px;' src='../../images/edit.png'/></a>";
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
components: {editCellRenderer : editCellRenderer},
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

function getRowData() {
var rowData = [];
var total_payment = 0;
gridOptions.api.forEachNodeAfterFilter( function(node) {
rowData.push(node.data);
});
$.each(rowData,function(i,text){
console.log(text);
total_payment = text.paid_amt;
});

document.getElementById('total_inv_amount').innerHTML = 'Total Collection: '+total_payment+'/-';
}

</script>
<br/>
 <script src='../../scripts/team/payment_collection.js'></script>
