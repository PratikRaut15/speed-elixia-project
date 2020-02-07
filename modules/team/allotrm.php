<?php
include_once("session.php");
include("loginorelse.php");
include_once("../../constants/constants.php");
include_once("db.php");
include_once("../../lib/components/gui/datagrid.php");
include_once("../../lib/components/gui/objectdatagrid.php");
include_once("../../lib/system/DatabaseManager.php");
class customers {
    
}
//Datagtrid
$db = new DatabaseManager();
 $SQL = sprintf("SELECT totalsms, customerno,customercompany,customername, smsleft,renewal,customercompany, rel_manager, manager_name, manager_mobile, manager_email FROM ".DB_PARENT.".customer left outer join ".DB_PARENT.".relationship_manager on relationship_manager.rid = customer.rel_manager order by customerno");
$db->executeQuery($SQL);
if ($db->get_rowCount() > 0) {
    while ($row = $db->get_nextRow()) {
        $customer = new customers();
        $srno++;
        $customer->srno = $srno;
        $customer->customerno = $row["customerno"];
        $customer->customername = $row["customername"];
        $customer->customercompany = $row["customercompany"];
        $customer->renewal = $row["renewal"];
        $customer->manager_name = $row["manager_name"];
        $customer->manager_mobile = $row["manager_mobile"];
        $customer->manager_email = $row["manager_email"];

        if ($row['isoffline'] == '1') {
            $lock = 'checked';
        } elseif ($row['isoffline'] == '0') {
            $lock = '';
        }
        $companyname = "'" . $row["customercompany"] . "'";
        $customer->lock = '<input type="checkbox" id="lock' . $row["customerno"] . '" onclick="lockCustomer(' . $row["customerno"] . ',' . $companyname . ');" ' . $lock . '/>
                            <input type="hidden" id="lockStatus' . $row["customerno"] . '" value="' . $row["isoffline"] . '"/>';
        $customer->smsleft = $row["smsleft"];
        $customer->customercompany = $row["customercompany"];
        $totalunits+=$row["cunit"];
        $customer->cunit = $row["cunit"];
        if ($row["renewal"] == 0) {
            $customer->crenewal = "Not Assigned";
        }
        if ($row["renewal"] == -2) {
            $customer->crenewal = "Closed";
        }
        if ($row["renewal"] == -3) {
            $customer->crenewal = "Lease";
        }
        if ($row["renewal"] == -1) {
            $customer->crenewal = "Demo";
        }
        if ($row["renewal"] == 1) {
            $customer->crenewal = "Monthly";
        }
        if ($row["renewal"] == 3) {
            $customer->crenewal = "Quarterly";
        }
        if ($row["renewal"] == 6) {
            $customer->crenewal = "Six Monthly";
        }
        if ($row["renewal"] == 12) {
            $customer->crenewal = "Yearly";
        }
        $customer->pending_amt = '0';
        $customers[] = $customer;
    }
}
$dg = new objectdatagrid($customers);

//$dg->AddAction("View/Edit", "../../images/edit.png", "modifycustomer.php?cid=%d");
// $dg->AddColumn("Customer #", "customerno");
// $dg->AddColumn("Name", "customername");
// $dg->AddColumn("Company", "customercompany");
// $dg->AddColumn("Subscription", "crenewal");
// $dg->AddColumn("Manager", "manager_name");
// $dg->AddColumn("Manager Mobile", "manager_mobile");
// $dg->AddColumn("Manager Email", "manager_email");
// //$dg->AddColumn("Total SMS", "totalsms");
// $dg->AddRightAction("View", "../../images/history.png", "allotmanager.php?cid=%d");
// $dg->SetNoDataMessage("No Customer");
// $dg->AddIdColumn("customerno");

// $_scripts[] = "../../scripts/trash/prototype.js";
 
include("header.php");
?>
<style>
.ag-theme-blue .ag-header-cell-label {
    display: unset !important;
    }
.ag-theme-blue .ag-header-cell-label > span {
   float:unset !important;
}
</style>
<br/>
<div id="Bucket_Div" style="text-align: center;margin:30px auto;font-size: 20px">
<p style="font-size: 20px;">RELATIONSHIP MANAGER</p>
<br>
<div id="myGrid" class="ag-theme-blue" style="height:500px;width:83%;margin:0 auto;border: 1px solid gray"></div>
</div>
<br/>

<?php
include("footer.php");
?>   
  <script src="https://unpkg.com/ag-grid-enterprise@17.0.0/dist/ag-grid-enterprise.min.js"></script>
<script>

    agGrid.LicenseManager.setLicenseKey("Elixia_Tech_Solutions_Ltd_MultiApp_1Devs16_March_2019__MTU1MjY5NDQwMDAwMA==c4c29e5702e62789dbdb799433c73545");
    var details = <?php echo json_encode($customers)?>; 
    var gridOptions;
    columnDefs = [
        {headerName: 'Edit', cellRenderer:'editCellRenderer',width: 70,suppressFilter:true},
        // {headerName: 'Delete', cellRenderer:'deleteCellRenderer',width: 80},
        {headerName:'Customer',field: 'customerno',width:100,filter: 'agTextColumnFilter'},
        {headerName:'Customer Company',field: 'customercompany',width:220,filter: 'agTextColumnFilter'},
        {headerName:'Customer Name',field: 'customername',width:200,filter: 'agTextColumnFilter'},
        {headerName:'Renewal',field: 'renewal',width:100,filter: 'agTextColumnFilter'},
        {headerName:'CRM',field: 'manager_name',width:200,filter: 'agTextColumnFilter'},
        {headerName:'CRM Mobile No.',field: 'manager_mobile',width:200,filter: 'agTextColumnFilter'}
    ];
    // function deleteCellRenderer(params){
    //     return "<a href='javascript:void(0);' alt='Delete Mode' title='Mode' onclick='deletepipeline(" +params.data.pipelineid+ ");'><img style='text-align:center; width:20px; height:20px;' src='../../images/delete.png'/></a>"
    // }
    function editCellRenderer(params){
        return "<a href='allotmanager.php?cid="+params.data.customerno+"' alt='Edit Mode' target='_blank' title='Mode' ><img style='text-align:center; width:20px; height:20px;' src='../../images/edit.png'/></a>"
    }
    gridOptions = {
        enableFilter:true,
        enableSorting: true,
        floatingFilter:true,
        rowData:details,
        animateRows:true,
        columnDefs: columnDefs,

        components: {editCellRenderer : editCellRenderer
        }
    };
    var gridDiv = document.getElementById('myGrid');
    new agGrid.Grid(gridDiv,gridOptions);
</script>