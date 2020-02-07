<?php
include_once("session.php");
include("loginorelse.php");
include_once("../../constants/constants.php");
include_once("db.php");
include_once("../../lib/components/gui/objectdatagrid.php");
include_once("../../lib/system/DatabaseManager.php");

class customers {
    
}

$db = new DatabaseManager();
$srno = 0;
$totalpayment = 0;
$customers = Array();
$prev_date = date('Y-m-d');

$queryCust='';
    if(isset($_POST['date_value']) && $_POST['date_value']!=''||$_POST['date_value']!=0){
          $days_value = $_POST['date_value'];
          $prev_date = date('Y-m-d', strtotime('-'.$days_value.' days'));
          $queryCust = sprintf("AND i.inv_expiry <="."'".$prev_date."'");
    }
    else{
         $prev_date = date('Y-m-d');
    }  

    $SQL = sprintf("SELECT l.ledgername, l.ledgerid, c.renewal, c.totalsms, c.customerno, c.customername
                    , c.smsleft, c.customercompany, c.lease_duration, c.lease_price, c.renewal, c.unit_msp
                    , rel.manager_name
                    FROM    ledger_cust_mapping lcm 
                    INNER JOIN " . DB_PARENT . ".customer c ON c.customerno = lcm.customerno 
                    LEFT OUTER JOIN " . DB_PARENT . ".ledger l ON lcm.ledgerid = l.ledgerid
                    LEFT OUTER JOIN  " . DB_PARENT . ".relationship_manager rel ON rel.rid = c.rel_manager
                    WHERE   lcm.isdeleted = 0 AND c.renewal NOT IN (-1,-2) 
                    GROUP BY l.ledgerid 
                    ORDER BY c.customerno ASC");
    
    $db->executeQuery($SQL);
    
    if ($db->get_rowCount() > 0) {
        while ($row = $db->get_nextRow()) {
            $customer = new customers();
            $customer->totalsms = $row["totalsms"];
            $customer->customerno = $row["customerno"];
            $customer->ledgerid = intval($row["ledgerid"]);
            $customer->ledgername = $row["ledgername"];
            $customer->customername = $row["customername"];
            $customer->smsleft = $row["smsleft"];
            $customer->customercompany = $row["customercompany"];
            $customer->manager_name = $row["manager_name"];        
            $customers[] = $customer;
        }
    }


    if (isset($customers)) {
        $totalpayment = 0;
        foreach ($customers as $thiscustomerno) {
            $total = 0;
            //----------------------------------to find pending amt------------------------------------------------------------------------

            $SQL2 = sprintf("   SELECT  sum(inv_amt) as proforma_amt 
                                FROM    " . DB_PARENT . ".proforma_invoice 
                                WHERE   isdeleted = 0 
                                AND     ledgerid = %d 
                                AND     is_taxed = 0", $thiscustomerno->ledgerid);
            
            $db->executeQuery($SQL2);
            
            if ($db->get_rowCount() > 0) {
                while ($row = $db->get_nextRow()) {
                    $total+=$row["proforma_amt"];
                    $thiscustomerno->proforma_amt = $row["proforma_amt"];
                    if ($row["proforma_amt"] == "" || $row["proforma_amt"] == "0") {
                        $thiscustomerno->proforma_amt = 0;
                    }
                }
            }
            
            $SQL2 = sprintf("SELECT sum(i.pending_amt) as invoice_amt
                            FROM invoice i
                            WHERE i.ledgerid = '%d' %s AND i.invoiceid NOT IN(SELECT ip.invoiceid from
                            invoice_payment_mapping ip where ip.invoiceid = i.invoiceid)",$thiscustomerno->ledgerid,$queryCust);
                     
            $db->executeQuery($SQL2);
            if ($db->get_rowCount() > 0) {
                while ($row = $db->get_nextRow()) {
                    $total+=$row["invoice_amt"];
                    $thiscustomerno->invoice_amt = round($row["invoice_amt"],2);
                    if ($row["invoice_amt"] == "" || $row["invoice_amt"] == "0") {
                        $thiscustomerno->invoice_amt = 0;
                    }
                }
            }

            $SQL2 = sprintf(" SELECT SUM(unpaid_amt) as unpaid_amt FROM
                                (
                                 SELECT  sum(ip.bad_debts) as unpaid_amt
                                  FROM invoice_payment_mapping ip 
                                  LEFT JOIN invoice i on i.invoiceid = ip.invoiceid %s
                                  WHERE i.ledgerid = '%d'
                                  UNION
                                  SELECT sum(i.unpaid_amt) as unpaid_amt
                                  from invoice i
                                  WHERE i.ledgerid = '%d' %s AND
                                  i.invoiceid NOT IN(SELECT ip.invoiceid from invoice_payment_mapping ip where ip.invoiceid = i.invoiceid) 
                                ) 
                                invoice",$queryCust,$thiscustomerno->ledgerid,$thiscustomerno->ledgerid,$queryCust);
            
            $db->executeQuery($SQL2);
            
            if ($db->get_rowCount() > 0) {
                while ($row = $db->get_nextRow()) {
                    
                    if ($row["unpaid_amt"] == "" || $row["unpaid_amt"] == "0") {
                        $thiscustomerno->unpaid_amt = 0;
                    }
                    else if($row["unpaid_amt"]<0){
                        $thiscustomerno->unpaid_amt = -1*(round($row["unpaid_amt"],2));
                    }
                    else{
                        $thiscustomerno->unpaid_amt = round($row["unpaid_amt"],2);
                    }
                }
            }
            
            // $SQL2 = sprintf("SELECT sum(i.pending_amt) as cashmemo_amt FROM " . DB_PARENT . ".cash_memo i  WHERE i.ledgerid = %d", $thiscustomerno->ledgerid);
            
            // $db->executeQuery($SQL2);
            // if ($db->get_rowCount() > 0) {
            //     while ($row = $db->get_nextRow()) {
            //         $total+=$row["cashmemo_amt"];
            //         $thiscustomerno->cashmemo_amt = $row["cashmemo_amt"];
            //         if ($row["cashmemo_amt"] == "" || $row["cashmemo_amt"] == "0") {
            //             $thiscustomerno->cashmemo_amt = 0;
            //         }
            //     }
            // }
            
            $SQL2 = sprintf("   SELECT  sum(i.inv_amt) as cred_amt 
                                FROM    " . DB_PARENT . ".credit_note i  
                                WHERE   i.status LIKE 'pending' 
                                AND     i.ledgerid <> 0
                                AND     i.ledgerid = %d", $thiscustomerno->ledgerid);
            
            $db->executeQuery($SQL2);
            if ($db->get_rowCount() > 0) {
                while ($row = $db->get_nextRow()) {
                    $total+=$row["cred_amt"];
                    $thiscustomerno->cred_amt = -1 * $row["cred_amt"];
                    if ($row["cred_amt"] == "" || $row["cred_amt"] == "0") {
                        $thiscustomerno->cred_amt = 0;
                    }
                }
            }

            $thiscustomerno->total = ($total-$thiscustomerno->unpaid_amt);
            $thiscustomerno->total = ($thiscustomerno->total-$thiscustomerno->cred_amt);
            $totalpayment+=round($thiscustomerno->total,2);
        }
    }

if(isset($_POST['filtered_date'])){
    unset($customers);
    $queryCust='';
    if(isset($_POST['date_value']) && $_POST['date_value']!=''||$_POST['date_value']!=0){
          $days_value = $_POST['date_value'];
          $prev_date = date('Y-m-d', strtotime('-'.$days_value.' days'));
           $queryCust = sprintf("AND i.inv_expiry <="."'".$prev_date."'");
    }
    else{
         $prev_date = date('Y-m-d');
    }  

    $SQL = sprintf("SELECT l.ledgername, l.ledgerid, c.renewal, c.totalsms, c.customerno, c.customername
                    , c.smsleft, c.customercompany, c.lease_duration, c.lease_price, c.renewal, c.unit_msp
                    , rel.manager_name
                    FROM    ledger_cust_mapping lcm 
                    INNER JOIN " . DB_PARENT . ".customer c ON c.customerno = lcm.customerno 
                    LEFT OUTER JOIN " . DB_PARENT . ".ledger l ON lcm.ledgerid = l.ledgerid
                    LEFT OUTER JOIN  " . DB_PARENT . ".relationship_manager rel ON rel.rid = c.rel_manager
                    WHERE   lcm.isdeleted = 0 AND c.renewal NOT IN (-1,-2) 
                    GROUP BY l.ledgerid 
                    ORDER BY c.customerno ASC");
    
    $db->executeQuery($SQL);
    
    if ($db->get_rowCount() > 0) {
        while ($row = $db->get_nextRow()) {
            $customer = new customers();
            $customer->totalsms = $row["totalsms"];
            $customer->customerno = $row["customerno"];
            $customer->ledgerid = intval($row["ledgerid"]);
            $customer->ledgername = $row["ledgername"];
            $customer->customername = $row["customername"];
            $customer->smsleft = $row["smsleft"];
            $customer->customercompany = $row["customercompany"];
            $customer->manager_name = $row["manager_name"];        
            $customers[] = $customer;
        }
    }


    if (isset($customers)) {
        $totalpayment = 0;
        foreach ($customers as $thiscustomerno) {
            $total = 0;
            //----------------------------------to find pending amt------------------------------------------------------------------------

            $SQL2 = sprintf("   SELECT  sum(inv_amt) as proforma_amt 
                                FROM    " . DB_PARENT . ".proforma_invoice 
                                WHERE   isdeleted = 0 
                                AND     ledgerid = %d 
                                AND     is_taxed = 0", $thiscustomerno->ledgerid);
            
            $db->executeQuery($SQL2);
            
            if ($db->get_rowCount() > 0) {
                while ($row = $db->get_nextRow()) {
                    $total+=$row["proforma_amt"];
                    $thiscustomerno->proforma_amt = $row["proforma_amt"];
                    if ($row["proforma_amt"] == "" || $row["proforma_amt"] == "0") {
                        $thiscustomerno->proforma_amt = 0;
                    }
                }
            }
            
            $SQL2 = sprintf("SELECT sum(i.pending_amt) as invoice_amt
                            from invoice i
                            WHERE i.ledgerid = '%d' %s AND i.invoiceid NOT IN(SELECT ip.invoiceid from
                            invoice_payment_mapping ip where ip.invoiceid = i.invoiceid)",$thiscustomerno->ledgerid,$queryCust);
                     
            $db->executeQuery($SQL2);
            if ($db->get_rowCount() > 0) {
                while ($row = $db->get_nextRow()) {
                    $total+=$row["invoice_amt"];
                    $thiscustomerno->invoice_amt = round($row["invoice_amt"],2);
                    if ($row["invoice_amt"] == "" || $row["invoice_amt"] == "0") {
                        $thiscustomerno->invoice_amt = 0;
                    }
                }
            }

            $SQL2 = sprintf(" SELECT SUM(unpaid_amt) as unpaid_amt FROM
                                (
                                 SELECT  sum(ip.bad_debts) as unpaid_amt
                                  from invoice_payment_mapping ip 
                                  LEFT JOIN invoice i on i.invoiceid = ip.invoiceid %s
                                  WHERE i.ledgerid = '%d'
                                  UNION
                                  SELECT sum(i.unpaid_amt) as unpaid_amt
                                  from invoice i
                                  WHERE i.ledgerid = '%d' %s AND
                                  i.invoiceid NOT IN(SELECT ip.invoiceid from invoice_payment_mapping ip where ip.invoiceid = i.invoiceid) 
                                ) 
                                invoice",$queryCust,$thiscustomerno->ledgerid,$thiscustomerno->ledgerid,$queryCust);
            
            $db->executeQuery($SQL2);
            
            if ($db->get_rowCount() > 0) {
                while ($row = $db->get_nextRow()) {
                    $thiscustomerno->unpaid_amt = round($row["unpaid_amt"],2);
                    if ($row["unpaid_amt"] == "" || $row["unpaid_amt"] == "0") {
                        $thiscustomerno->unpaid_amt = 0;
                    }
                }
            }
            
            $SQL2 = sprintf("SELECT sum(i.pending_amt) as cashmemo_amt FROM " . DB_PARENT . ".cash_memo i  WHERE i.ledgerid = %d", $thiscustomerno->ledgerid);
            
            $db->executeQuery($SQL2);
            if ($db->get_rowCount() > 0) {
                while ($row = $db->get_nextRow()) {
                    $total+=$row["cashmemo_amt"];
                    $thiscustomerno->cashmemo_amt = $row["cashmemo_amt"];
                    if ($row["cashmemo_amt"] == "" || $row["cashmemo_amt"] == "0") {
                        $thiscustomerno->cashmemo_amt = 0;
                    }
                }
            }
            
            $SQL2 = sprintf("   SELECT  sum(i.inv_amt) as cred_amt 
                                FROM    " . DB_PARENT . ".credit_note i  
                                WHERE   i.status LIKE 'pending' 
                                AND     i.ledgerid <> 0
                                AND     i.ledgerid = %d", $thiscustomerno->ledgerid);
            
            $db->executeQuery($SQL2);
            if ($db->get_rowCount() > 0) {
                while ($row = $db->get_nextRow()) {
                    $total+=$row["cred_amt"];
                    $thiscustomerno->cred_amt = -1 * $row["cred_amt"];
                    if ($row["cred_amt"] == "" || $row["cred_amt"] == "0") {
                        $thiscustomerno->cred_amt = 0;
                    }
                }
            }

            $thiscustomerno->total = $total;
            $totalpayment+=round($total,2);
        }
    }
}


if (isset($customers)) {
    foreach ($customers as $thiscustomerno) {
        $thiscustomerno->invoice_amt = $thiscustomerno->invoice_amt - $thiscustomerno->cred_amt;
    }
}

function cmp($a, $b){

    if ($a->total==$b->total) return 0;
    return ($a->total<$b->total)?1:-1;
}

usort($customers, "cmp");


include("header.php");
?>
<link rel="stylesheet" href="../../css/customer_verification.css">
<style>
#dataQuery{
        background: url(../../images/xls.gif);
        /*border: 1px solid black;*/

        height: 33px;
        width: 33px;
    }
.panel{
    width:1324px !important;
}
.paneltitle{
    width: 1308px !important;
}
</style>
<br/>
<div class="panel">
    <?php
    ?>
    <div class="paneltitle" align="center">Customer Receivables
        <span id="total_inv_amount" style="float: right;">Pending Collection : Rs <?php echo($totalpayment); ?> /- </span>
    </div>
    <div>

        <br>
        <div id="option">
            <form id="filterDates" name="filterDates" method="POST">
                <label>Payment Not Received Since</label>
                <input type="text" name="date_value" id="date_value" placeholder="Enter Days in Number">
                <input type="submit" name="filtered_date" id="filtered_date">
                <span name="date_display" id="date_display" style="display: none;float: right;"><b>Payment as on :<?php echo(date('d-m-Y', strtotime($prev_date))); ?></b></span>
            </form>
        </div>
        <div class="panelcontents">
            <div id="myGrid" class="ag-theme-fresh" style="height:500px;width:100%;margin:0 auto;border: 1px solid gray">
            </div>
        </div>
    </div>
</div>

<?php
include("footer.php");
?>
<script src="https://unpkg.com/ag-grid-enterprise@17.0.0/dist/ag-grid-enterprise.min.js"></script>
<script>

agGrid.LicenseManager.setLicenseKey("Elixia_Tech_Solutions_Ltd_MultiApp_1Devs16_March_2019__MTU1MjY5NDQwMDAwMA==c4c29e5702e62789dbdb799433c73545");
var details = <?php echo json_encode($customers)?>;
var customer_array_length= details.length;

$("#myGrid").css('height','120px');

if(customer_array_length>0){
$("#date_display").show();
$("#myGrid").css('height','500px');
}


var gridOptions;
columnDefs = [
{headerName: 'Edit', cellRenderer:'editCellRenderer',width: 70,suppressFilter:true},
// {headerName: 'Delete', cellRenderer:'deleteCellRenderer',width: 80},
{headerName:'CRM',field: 'manager_name',width:100,filter: 'agTextColumnFilter'},
{headerName:'Customer No',field: 'customerno',width:120,filter: 'agTextColumnFilter'},
{headerName:'Customer Name',field: 'customername',width: 150,filter: 'agTextColumnFilter'},
{headerName:'Ledger ID', field:'ledgerid',width: 120,filter: 'agTextColumnFilter'},
{headerName:'Ledger Name',field: 'ledgername',width: 150,filter: 'agTextColumnFilter'},
{headerName:'Proforma',field: 'proforma_amt',width: 120,filter: 'agTextColumnFilter'},
{headerName:'Pending Amount',field: 'invoice_amt',width: 150,filter: 'agNumberColumnFilter'},     
{headerName:'Bad Debts', field:'unpaid_amt',width: 120,filter: 'agTextColumnFilter'},
{headerName:'Cash Memo',field: 'cashmemo_amt',width: 120,filter: 'agTextColumnFilter',hide:true},

{headerName:'Total',field: 'total',width: 120,filter: 'agTextColumnFilter'},
{headerName: 'View', cellRenderer:'editCellRenderer1',width: 70,suppressFilter:true}
];


function editCellRenderer(params){
return "<a href='acc_modifycustomer.php?cid="+params.data.customerno+"' alt='Edit Mode' title='Customer Modification' target='_blank' ><img style='text-align:center; width:20px; height:20px;' src='../../images/edit.png'/></a>"+"<a href='ledger_hist.php?lid="+params.data.ledgerid+"' alt='Edit Mode' title='Invoice List' target='_blank'><img style='text-align:center; width:20px; height:20px;padding-left:5px;' src='../../images/history.png'/></a>";
}
function editCellRenderer1(params){
return "<a href='ledger_hist.php?lid="+params.data.ledgerid+"' alt='Edit Mode' target='_blank'><img style='text-align:center; width:20px; height:20px;' src='../../images/history.png'/></a>";
}




function getRowData() {
var rowData = [];
var total_invoice_amount = 0;
gridOptions.api.forEachNodeAfterFilter( function(node) {
rowData.push(node.data);
});
$.each(rowData,function(i,text){
console.log(text);
total_invoice_amount += (text.invoice_amt-text.cred_amt-text.unpaid_amt);

});

document.getElementById('total_inv_amount').innerHTML = 'Pending Collection: '+total_invoice_amount+'/-';
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
components: {editCellRenderer : editCellRenderer,
editCellRenderer1 : editCellRenderer1
},

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
</script>