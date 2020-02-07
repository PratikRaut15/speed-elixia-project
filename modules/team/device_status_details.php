<!DOCTYPE html>
<html lang="en">
<head>
  <script src="https://unpkg.com/ag-grid-enterprise@17.0.0/dist/ag-grid-enterprise.min.js"></script>
</head>

<?php

  include_once("session.php");
  include_once("loginorelse.php");
  include_once("../../constants/constants.php");
  include_once("db.php");
  include_once("../../lib/system/DatabaseManager.php");
  date_default_timezone_set("Asia/Calcutta");

  $customerno = $_GET['cno'];
  $deviceStatusType = $_GET['statusId'];  //1 =Active Device 2 = Expired Device
                                          // 3= Expring 
  if($customerno==0){
    header( "Location: index.php");
  }
  $statusTitle = '';
  if($deviceStatusType==1){
   $statusTitle ="ACTIVE";
 
  }else if($deviceStatusType==2){
    $statusTitle ="EXPIRED";
  
  }else if($deviceStatusType==3){
    $statusTitle ="EXPIRING";
  }
    include("header.php");
?>
<body>
<form name="ANALYSIS" id="ANALYSIS" method="POST">
<div id="Unmapped_Vehicle_Div" style="text-align: center;margin:50px auto;font-size: 20px">
<p style="font-size: 20px;"><?php echo $statusTitle ?> DEVICES STATUS</p>
<div id="unmappedVehicleGrid" class="ag-theme-blue" style="height:500px;width:100%;margin:0 auto;">
  
</div>
</div>
</form>
</body>
<script>
  var customerNo = <?php echo $customerno ?>;
  var deviceStatusType = <?php echo $deviceStatusType ?>;
  
  
  jQuery(document).ready(function () {  
     jQuery.ajax({
        type: "POST",
        url: "device_status_ajax.php",
        data: "fetch_devices_infoDetails=1&custNo="+customerNo+"&devType="+deviceStatusType,
          success: function(data){
            var data=JSON.parse(data);
            customerGridOptions.api.setRowData(data);
          }
      });

  });

</script>
<script>
var um_vehicleCols =[
    {headerName:'Unit No',field: 'unitno',width: 150,filter:'agTextColumnFilter'},
    {headerName:'Vehicle No',field: 'vehicleno',width:180,filter:'agTextColumnFilter'},
    {headerName:'Unit Status',field: 'status',width:180,filter:'agTextColumnFilter'},
    {headerName:'Sim Status',field: 'status',width:180,filter:'agTextColumnFilter'},
    {headerName:'Start Date',field: 'start_date',width:120,filter:'agTextColumnFilter'},
    {headerName:'End Date',field:'end_date',width: 120,filter:'agTextColumnFilter'},
    {headerName:'Expiry date',field: 'expirydate',width: 120,filter:'agTextColumnFilter'},
    {headerName:'Invoice No',field: 'invoiceno',width:100,filter:'agTextColumnFilter'},
    {headerName:'Dev Invoice No',field: 'device_invoiceno',width:100,filter:'agTextColumnFilter'}
];



  //console.log(um_vehicleArray);
  customerGridOptions = {
    enableFilter:true,
    enableSorting: true,
    groupIncludeFooter: true,
    floatingFilter:true,
    rowData:[],
    animateRows: true,
    columnDefs: um_vehicleCols,
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
