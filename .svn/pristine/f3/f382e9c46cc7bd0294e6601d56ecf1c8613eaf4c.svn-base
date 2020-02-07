<?php
include_once("session.php");
include("loginorelse.php");
include_once("db.php");
include_once("../../constants/constants.php");
include_once("../../lib/system/DatabaseManager.php"); 
include_once("../../lib/system/Sanitise.php");
?>
<br><br>
<div id="myGrid" class="ag-theme-fresh" style="height:500px;width:100%;margin:0 auto;border: 1px solid gray"></div>
<?php
    $db = new DatabaseManager();
    $pdo = $db->CreatePDOConn();
    $ledger = array();

        $sp_params1 = "''"
                . ",''"
        ;
        $QUERY1 = $db->PrepareSP('get_ledger', $sp_params1);
        $ledgerList = $pdo->query($QUERY1);
        if ($ledgerList->rowCount() > 0) {
            $x = 1;
            while ($row = $ledgerList->fetch(PDO::FETCH_ASSOC)) {
                $data = new stdClass();
                $data->x = $x++;
                $data->ledgerid = utf8_encode($row['ledgerid']);
                $data->ledgername = utf8_encode($row['ledgername']);
                $data->add1 = utf8_encode($row['address1']);
                $data->add2 = utf8_encode($row['address2']);
                $data->add3 = utf8_encode($row['address3']);
                $data->pan = utf8_encode($row['pan_no']);
                $data->gst = utf8_encode($row['gst_no']);
                $data->state = utf8_encode($row['state']);
                $data->customercompany = utf8_encode($row['customercompany']);
                $data->phone = utf8_encode($row['phone']);
                $data->email = utf8_encode($row['email']);
                $ledger[] = $data;
            }
        }
        $db->ClosePDOConn($pdo);
    ?>
    <script src="https://unpkg.com/ag-grid-enterprise@17.0.0/dist/ag-grid-enterprise.min.js"></script>
<script>

    agGrid.LicenseManager.setLicenseKey("Elixia_Tech_Solutions_Ltd_MultiApp_1Devs16_March_2019__MTU1MjY5NDQwMDAwMA==c4c29e5702e62789dbdb799433c73545");
    var details = <?php echo json_encode($ledger)?>;
    var gridOptions;
    columnDefs = [
        {headerName: 'Edit', cellRenderer:'editCellRenderer',width: 70,suppressFilter:true},
        {headerName:'Ledger ID',field: 'ledgerid',width:100,filter: 'agTextColumnFilter'},
        {headerName:'Ledger Name',field: 'ledgername',filter: 'agTextColumnFilter'},
        {headerName:'Address Line 1',field: 'add1',suppressFilter:true},
        {headerName:'Address Line 2',field: 'add2',suppressFilter:true},
        {headerName:'Address Line 3',field: 'add3',suppressFilter:true},
        {headerName:'State',field: 'state',filter: 'agTextColumnFilter'},
        {headerName:'GST No.',field: 'gst',filter: 'agTextColumnFilter'},     
        {headerName:'Pan No.', field:'pan',filter: 'agTextColumnFilter'},
        {headerName:'Email ID',field: 'email',filter: 'agTextColumnFilter'}
    ];
    // function deleteCellRenderer(params){
    //     return "<a href='javascript:void(0);' alt='Delete Mode' title='Mode' onclick='deletepipeline(" +params.data.pipelineid+ ");'><img style='text-align:center; width:20px; height:20px;' src='../../images/delete.png'/></a>"
    // }
    function editCellRenderer(params){
        return "<a href='ledger_edit.php?ledid="+params.data.ledgerid+"' alt='Edit Mode' title='Mode' ><img style='text-align:center; width:20px; height:20px;' src='../../images/edit.png'/></a>"
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