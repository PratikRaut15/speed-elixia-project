<?php
  include_once("session.php");
  include_once("loginorelse.php");
  include_once("../../constants/constants.php");
  include_once("db.php");
  include_once("../../lib/system/DatabaseManager.php");
  date_default_timezone_set("Asia/Calcutta");
  $teamid = GetLoggedInUserId();
  include("header.php");
?>
<style>
  .panel{
    width: 1100px;
  }
  .paneltitle{
    width: 1085px;
  }
</style>
<div class="panel">
  <div class="paneltitle" align="center">Team Attendance Logs</div> 
  <div class="panelcontents">
    <label for="status" href="#" title="Device Status" data-toggle="popover" data-trigger="hover" data-placement="bottom" data-content="Active:- Expiry date beyond 15 days.<br/>Expired:- Expiry date less than today.<br/>Expiring :-Expiry date in next 15 days." style="float: right;"><i class="material-icons">info</i></label>

    <form name="ANALYSIS" id="ANALYSIS" method="POST">
    <div id="Unmapped_Vehicle_Div" style="text-align: center;margin:50px auto;font-size: 20px">
      <p style="font-size: 20px;">DEVICES STATUS</p>
      <div id="unmappedVehicleGrid" class="ag-theme-blue" style="height:500px;width:100%;margin:0 auto;"></div>
    </div>
  </div>
</div>
</form>
<script src="https://unpkg.com/ag-grid-enterprise@18.0.0/dist/ag-grid-enterprise.min.js"></script>
<script>
  var teamId = <?php echo $teamid ?>;

  jQuery(document).ready(function () {  
     jQuery.ajax({
        type: "POST",
        url: "device_status_ajax.php",
        data: "fetch_devices_info=1&teamId="+teamId,
          success: function(data){
            var data=JSON.parse(data);
            customerGridOptions.api.setRowData(data);
          }
      });

      $('[data-toggle="popover"]').popover(); 
  });

</script>
<script>
var um_vehicleCols =[
    {headerName:'Customer No',field: 'customerno',width: 100,filter:'agTextColumnFilter'},
    {headerName:'Customer Company',field: 'customercompany',width: 100,filter:'agTextColumnFilter'},
    {headerName:'CRM',field: 'crm_name',width:80,filter:'agTextColumnFilter'},
    {headerName:'Total',field: 'total',width: 70,filter:'agTextColumnFilter'},
    {headerName:'Active Devices',field: 'activeCount',cellRenderer:'activeCellRenderer',width: 90,filter:'agTextColumnFilter'},
    {headerName:'Expired Devices',field: 'expiredCount',cellRenderer:'expiredCellRenderer',width: 90,filter:'agTextColumnFilter'},
    {headerName:'Expiring Devices',field: 'expiredCount_soon',cellRenderer:'expiringCellRenderer',width: 90,filter:'agTextColumnFilter'}
];


      function activeCellRenderer(params){
        return "<a href='device_status_details.php?cno="+params.data.customerno+"&statusId=1' alt='Edit Mode' title='click to view' target='_blank' >"+params.data.activeCount+"</a>"
      }
      function expiredCellRenderer(params){
        return "<a href='device_status_details.php?cno="+params.data.customerno+"&statusId=2' alt='Edit Mode' title='click to view' target='_blank' >"+params.data.expiredCount+"</a>"
      }
      function expiringCellRenderer(params){
        return "<a href='device_status_details.php?cno="+params.data.customerno+"&statusId=3' alt='Edit Mode' title='click to view' target='_blank' >"+params.data.expiredCount_soon+"</a>"
      }

  //console.log(um_vehicleArray);
  customerGridOptions = {
    enableFilter:true,
    enableSorting: true,
    groupIncludeFooter: true,
    floatingFilter:true,
    rowData:[],
    animateRows: true,
    columnDefs: um_vehicleCols,
    components: {activeCellRenderer : activeCellRenderer,expiredCellRenderer:expiredCellRenderer,expiringCellRenderer:expiringCellRenderer},
    onGridReady: function(params) { 
        params.api.sizeColumnsToFit();
    }
  };


agGrid.LicenseManager.setLicenseKey("Elixia_Tech_Solutions_Ltd_MultiApp_1Devs16_March_2019__MTU1MjY5NDQwMDAwMA==c4c29e5702e62789dbdb799433c73545");


</script>
<script>

  document.addEventListener('DOMContentLoaded', function() {
    gridDiv = document.querySelector('#unmappedVehicleGrid');
    new agGrid.Grid(gridDiv, customerGridOptions);
    
});

</script>

</html>
<?php

include_once("footer.php");
?>