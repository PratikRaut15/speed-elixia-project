<?php
include_once("session.php");
include("loginorelse.php");
include_once("db.php");
include_once("../../constants/constants.php");
include_once("../../lib/system/Sanitise.php");
include_once("../../lib/system/DatabaseManager.php");
include("../../lib/bo/TimeSheetManager.php");
include_once("../../lib/system/Date.php");

date_default_timezone_set("Asia/Calcutta");
$today = date("d-m-Y");
$time= date("h:i");
$created_on=date("Y-m-d H:i:s");

include("header.php");
$teamId = GetLoggedInUserId();  



?>
<link rel="stylesheet" href="../../css/timesheet.css">
<div class="panel">
  <div class="paneltitle" align="center">Task Assignment</div> 
    <div class="panelcontents" >
      <div class="center">
      <form name="invoice_links" id="invoice_links">
        <input type="text" id="invoice_date" name="invoice_date" placeholder="Select Date" value="<?php echo $today; ?>">
        <input type="button" id="submitTask" name="submmitTask" style="margin:5% 45%;" value="Submit" onclick="generate_links();">
      </form>
        <div id="myGrid" class="ag-theme-fresh" style="height:500px;width:80%;margin:0 auto;"></div>
    </div>
  </div>
</div>
<script>
  jQuery(document).ready(function () {  
      jQuery('#invoice_date').datepicker({
          dateFormat: "dd-mm-yy",
          language: 'en',
          autoclose: 1,
          startDate: Date()
      });
  });  

function generate_links(){
  var date=$("#invoice_date").val();
  jQuery.ajax({
    type: "POST",
    url: "route_ajax.php",
    data: "&generate_invoice_link=1&invoice_date="+date,
    success: function(data){
      var result = JSON.parse(data);
      gridOptions.api.setRowData(result);
    }
  });
}
</script>
<script src="../../scripts/team/timesheet.js"></script>
<script src="https://unpkg.com/ag-grid-enterprise@17.0.0/dist/ag-grid-enterprise.min.js"></script>
<script>
    agGrid.LicenseManager.setLicenseKey("Elixia_Tech_Solutions_Ltd_MultiApp_1Devs16_March_2019__MTU1MjY5NDQwMDAwMA==c4c29e5702e62789dbdb799433c73545");
  
    columnDefs = [              
      {headerName:'Invoice ID',field: 'invoiceid',width:150,filter: 'agTextColumnFilter'},
      {headerName:'Customerno',field: 'customerno',width:150,filter: 'agTextColumnFilter'}, 
      {headerName:'Link',field: 'link',width:700,suppressFilter:true}
    ];

    var gridOptions;
      gridOptions = { 
        enableFilter:true,
        enableSorting: true,
        animateRows:true,
        rowData:[],
        floatingFilter:true, 
        columnDefs: columnDefs,
      };
 
    var gridDiv = document.getElementById('myGrid');
    new agGrid.Grid(gridDiv,gridOptions); 
</script>

