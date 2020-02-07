<?php
include_once("session.php");
include("loginorelse.php");
include_once("db.php");
include_once("../../constants/constants.php");
include_once("../../lib/system/DatabaseManager.php"); 
include_once("../../lib/system/Sanitise.php");
?>
<br><br>
<fieldset>

  <legend>1) Ledger  
    <img src="../../images/success.png" width="30" height="34" id="ledger_success" style="display:none;">
     <img src="../../images/fail.png" width="30" height="34" id="ledger_failure" style="display:none;">
    <input type="button" id="create_ledger"  name="create_ledger" value="Create Ledger" onclick="window.open('ledger.php', '_blank');" target="_blank">
    <span style="float:right;">
      <i class="far fa-window-maximize" id="maximize_ledger" onclick="maximize_ledger();"></i>
      <i class="fas fa-times" id="minimize_ledger" onclick="minimize_ledger();"></i>
    </span>
    <div id="LedgerGrid" class="ag-theme-bootstrap" style="height:200px;width:100%;margin:0 auto;border: 1px solid gray">
    </div>
  </legend>
</fieldset>


    <script src="https://unpkg.com/ag-grid-enterprise@17.0.0/dist/ag-grid-enterprise.min.js"></script>
<script>

    agGrid.LicenseManager.setLicenseKey("Elixia_Tech_Solutions_Ltd_MultiApp_1Devs16_March_2019__MTU1MjY5NDQwMDAwMA==c4c29e5702e62789dbdb799433c73545");
    var details = <?php echo json_encode($ledger)?>;
    var gridOptions;
    columnDefs = [
        {headerName: 'Edit', cellRenderer:'editCellRenderer',width: 70,suppressFilter:true},
        {headerName:'Ledger ID',field: 'ledgerid',width:100,filter: 'agTextColumnFilter'},
        {headerName:'Ledger Name',field: 'ledgername',filter: 'agTextColumnFilter'},
        {headerName:'Address Line 1',field: 'add1',suppressFilter:true,cellStyle: function(params) {
        if (params.data.add1=='') {
            return {color: '#fff', backgroundColor: '#db3236'};
        }
        }},
        {headerName:'Address Line 2',field: 'add2',suppressFilter:true},
        {headerName:'Address Line 3',field: 'add3',suppressFilter:true},
        {headerName:'GST No.',field: 'gstno',filter: 'agTextColumnFilter',cellStyle: function(params) {
        if (params.data.gstno=='') {
            return {color: '#fff', backgroundColor: '#db3236'};
        }
        }},     
    ];

    function editCellRenderer(params){
        return "<a href='ledger_edit.php?ledid="+params.data.ledgerid+"' alt='Edit Mode' title='Mode' ><img style='text-align:center; width:20px; height:20px;' src='../../images/edit.png'/></a>"
    }

    gridOptions = {
        enableFilter:true,
        enableSorting: true,
        floatingFilter:true,
        animateRows:true,
        columnDefs: columnDefs,
        components: {editCellRenderer : editCellRenderer
        },
        onGridReady: function(params) {
        params.api.sizeColumnsToFit();
        }
    };
    var gridDiv = document.getElementById('LedgerGrid');
    new agGrid.Grid(gridDiv,gridOptions);
</script>
