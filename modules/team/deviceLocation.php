 <?php
include_once("session.php");
include("loginorelse.php");
include_once("db.php");
include_once("../../constants/constants.php");
include_once("../../lib/system/DatabaseManager.php"); 
include_once("../../lib/system/Sanitise.php");

 $loctn_count = unit_office_location();

?>
<div id="myGrid" class="ag-theme-fresh" style="height:200px;width:100%;margin:0 auto;border: 1px solid gray"></div>

<script src="https://unpkg.com/ag-grid-enterprise@17.0.0/dist/ag-grid-enterprise.min.js"></script>
<script>

    agGrid.LicenseManager.setLicenseKey("Elixia_Tech_Solutions_Ltd_MultiApp_1Devs16_March_2019__MTU1MjY5NDQwMDAwMA==c4c29e5702e62789dbdb799433c73545");
    var details = <?php echo json_encode($loctn_count)?>;
    var gridOptions;
    columnDefs = [
        // {headerName: 'Delete', cellRenderer:'deleteCellRenderer',width: 80},
        {headerName:'Location',field: 'location',width:100,filter: 'agTextColumnFilter'},
        {headerName:'UnitNo',field: 'unitno',width:170,filter: 'agTextColumnFilter'},
        {headerName:'Device Name',field:'name',width:200,filter: 'agTextColumnFilter'},
        {headerName:'Count',field: 'count',width:100,filter: 'agTextColumnFilter'}
    ];
    gridOptions = {
        enableFilter:true,
        enableSorting: true,
        floatingFilter:true,
        rowData:details,
        animateRows:true,
        columnDefs: columnDefs
    };
    var gridDiv = document.getElementById('myGrid');
    new agGrid.Grid(gridDiv,gridOptions);
</script>