<!DOCTYPE html>
<html lang="en">
<head>
  <script src="https://unpkg.com/ag-grid-enterprise@17.0.0/dist/ag-grid-enterprise.min.js"></script>
</head>

<?php
include_once("session.php");
include_once("loginorelse.php");
include_once("../../lib/bo/DocketManager.php");
include_once("../../constants/constants.php");
include_once("db.php");
include_once("../../lib/system/DatabaseManager.php");
date_default_timezone_set("Asia/Calcutta");
$teamid = GetLoggedInUserId();
include("header.php");
$tm=new TeamManager();
$db = new DatabaseManager();

//$unmappedVehiclesArray=$tm->getUnmappedvehicles(); //Unmapped Vehicles

$SQL1 = sprintf("SELECT customerno from customer where rel_manager = (SELECT rid from team WHERE teamid='%d') AND renewal NOT IN (-1,-2) AND customerno NOT IN (1,2)",$teamid);

  $db->executeQuery($SQL1);
   if ($db->get_rowCount() > 0) {
          while ($row1 = $db->get_nextRow()) {
              $cust[]= $row1['customerno'];
          }
      }

$unmappedVehiclesArray = array();
foreach($cust as $customerno){
        $details = $tm->getUnmappedvehicles($customerno);
        foreach($details as $record){
          $record['count'] = $record['count'];
          $record['customerno'] = $record['customerno'];
          $record['customercompany'] = $record['customercompany'];
          $unmappedVehiclesArray[]= $record;
        }
      }

?>


<body>
<form name="ANALYSIS" id="ANALYSIS" method="POST">
<div id="Unmapped_Vehicle_Div" style="text-align: center;margin:50px auto;font-size: 20px">
<p style="font-size: 20px;">LEDGER TO VEHICLE MAPPING</p>
<div id="unmappedVehicleGrid" class="ag-theme-blue" style="height:200px;width:60%;margin:0 auto;">
  
</div>
</div>
</form>
</body>

<script>
var um_vehicleCols =[
    {headerName: 'Edit', cellRenderer:'editCellRenderer',width:20},
    {headerName:'Customer No',field: 'customerno',width:40},
    {headerName:'Customer Company',field: 'customercompany',width: 70},
    {headerName:'Total Unmapped Vehicles',field: 'count',width: 70}
];
var row = [];

      function editCellRenderer(params){
        return "<a href='ledger_mapvehicle.php?cno="+params.data.customerno+"' alt='Edit Mode' title='Edit' target='_blank' ><img style='text-align:center; width:20px; height:20px;' src='../../images/edit.png'/></a>"
    }

   var um_vehicleArray = <?php echo json_encode($unmappedVehiclesArray);?> ;
  //console.log(um_vehicleArray);
  um_vehicleGridOptions = {
    enableFilter:true,
    enableSorting: true,
    groupIncludeFooter: true,
    rowData:um_vehicleArray,
    animateRows: true,
    columnDefs: um_vehicleCols,
    components: {editCellRenderer : editCellRenderer
        },
    onGridReady: function(params) { 
        params.api.sizeColumnsToFit();
    }
  };


agGrid.LicenseManager.setLicenseKey("Elixia_Tech_Solutions_Ltd_MultiApp_1Devs16_March_2019__MTU1MjY5NDQwMDAwMA==c4c29e5702e62789dbdb799433c73545");


</script>
<script>

  document.addEventListener('DOMContentLoaded', function() {
    gridDiv = document.querySelector('#unmappedVehicleGrid');
    new agGrid.Grid(gridDiv, um_vehicleGridOptions);
    
});
</script>

</html>
