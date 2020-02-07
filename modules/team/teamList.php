<?php
include_once("session.php");
include("loginorelse.php");
include_once("db.php");
include_once("../../constants/constants.php");
include_once("../../lib/system/Sanitise.php");
include_once("../../lib/system/DatabaseManager.php");
include_once("../../lib/system/Date.php");
include_once("../../lib/bo/TimeSheetManager.php");
include_once("header.php");
date_default_timezone_set("Asia/Calcutta");
$today = date("d-m-Y");

$departmentId = $_SESSION['department'];

if(checkUserType(speedConstants::TEAM_DEPARTMENT_SOFTWARE,speedConstants::TEAM_ROLE_HEAD)){
  $teamId = 0;
}elseif(checkUserType(speedConstants::TEAM_DEPARTMENT_MANAGEMENT)){
  $teamId = 0;
  $departmentId = 0;
}else{
	$teamId = GetLoggedInUserId();
}
$time= date("h:i");
$created_on=date("Y-m-d H:i:s");
$tm = new TimeSheetManager();
$tasks = $tm->getTeamTasks();

?>
<link rel="stylesheet" href="../../css/timesheet.css">
<div class="panel"> 
  <div class="paneltitle" align="center">Team List</div> 
    <div class="panelcontents" >
      <div class="center">      
        <div id="teamGrid" class="ag-theme-balham" style="height:400px;margin:0 auto;">
        </div> 
      </div>
  </div>
</div>
<script src="../../scripts/ag-grid-enterprise/dist/ag-grid-enterprise.min.js"></script>
<script>
	var teamId = <?php echo $teamId; ?>;
	var departmentId = <?php echo $departmentId; ?>;
	var details = [];
      $.ajax({
      type: "POST",
      url: "timesheet_functions.php",
      data: {
        fetchTeam:1,
        department : departmentId,
      },
      success: function(data){
        details=JSON.parse(data);
        //console.log(result);
        teamListGridOptions.api.setRowData(details);
      }
    });
  function refreshGrid(){
        $.ajax({
      type: "POST",
      url: "timesheet_functions.php",
      data: {
        fetchTeam:1,
        department : departmentId,
      },
      success: function(data){
        details=JSON.parse(data);
        //console.log(result);
        teamListGridOptions.api.setRowData(details);
      }
    });
  }

  	columnDefs = [              
  	  {headerName: 'Department',field:'department'},
  	  {headerName:'Role',field: 'roleName',filter: 'agTextColumnFilter'},
  	  {headerName:'Team Member',field: 'name',filter: 'agTextColumnFilter',}, 
  	];
                        
	var teamListGridOptions;

  	teamListGridOptions = { 
	    enableFilter:true,
	    enableSorting: true,
	    animateRows:true,
	    rowData:details,
	    floatingFilter:true,
	    columnDefs: columnDefs,
      onRowDataChanged:function(params){
        var allColumnIds = [];
        teamListGridOptions.columnApi.getAllColumns().forEach(function(column) {
            allColumnIds.push(column.colId);
        });
        teamListGridOptions.columnApi.autoSizeColumns(allColumnIds);  
      }
  	};
                             
    var gridDiv = document.getElementById('teamGrid');
    agGrid.LicenseManager.setLicenseKey("Elixia_Tech_Solutions_Ltd_MultiApp_1Devs16_March_2019__MTU1MjY5NDQwMDAwMA==c4c29e5702e62789dbdb799433c73545");
    new agGrid.Grid(gridDiv,teamListGridOptions); 
    
</script>