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

if(checkUserType(speedConstants::TEAM_DEPARTMENT_SOFTWARE,speedConstants::TEAM_ROLE_HEAD)){
  $teamId = 0;
  $departmentId = speedConstants::TEAM_DEPARTMENT_SOFTWARE;
  $taskId = 0;
}else{
	$teamId = GetLoggedInUserId();
	$departmentId = $_SESSION['department'];
	$taskId = 0;
}

$time= date("h:i");
$created_on=date("Y-m-d H:i:s");
$tm = new TimeSheetManager();
$tasks = $tm->getTeamTasks();

?>
<link rel="stylesheet" href="../../css/timesheet.css">
<div class="panel"> 
  <div class="paneltitle" align="center">Tasks</div> 
    <div class="panelcontents" >
      <div class="center">      
        <div id="taskGrid" class="ag-theme-balham" style="height:400px;margin:0 auto;">
        </div> 
      </div>
  </div>
</div>
<script src="../../scripts/ag-grid-enterprise/dist/ag-grid-enterprise.min.js"></script>
<script>
	var teamId = <?php echo $teamId; ?>;
	var taskId = <?php echo $taskId; ?>;
	var departmentId = <?php echo $departmentId; ?>;
	var details = [];
  refreshTaskGrid();
  function refreshTaskGrid(){
    $.ajax({
      type: "POST",
      url: "timesheet_functions.php",
      data: {
        fetch_task:1,
        teamId : teamId,
        departmentId : departmentId,
        taskId : taskId
      },
      success: function(data){
        details=JSON.parse(data);
        //console.log(result);
      gridOptions.api.setRowData(details);
      }
    });
  }
  	columnDefs = [              
	  {headerName: 'Task Id',field:'taskId',},
	  {headerName:'Task Name',field: 'taskName',filter: 'agTextColumnFilter', rowGroupIndex:0},
	  {headerName:'Customer(s)',field: 'customers',filter: 'agTextColumnFilter',}, 
	  {headerName:'Product(s)',field: 'products',filter: 'agTextColumnFilter'},
    {headerName:'Name',field: 'name',filter: 'agTextColumnFilter'},
    {headerName:'Role',field: 'roleName',filter: 'agTextColumnFilter'},
	  {headerName:'Status',field: 'statusName', filter: 'agTextColumnFilter'},
  	];
                        
	var gridOptions;

  	gridOptions = { 
	    enableFilter:true,
	    enableSorting: true,
	    animateRows:true,
	    rowData:details,
	    floatingFilter:true,
	    columnDefs: columnDefs,
      onRowDataChanged:function(params){
        var allColumnIds = [];
        gridOptions.columnApi.getAllColumns().forEach(function(column) {
            allColumnIds.push(column.colId);
        });
        gridOptions.columnApi.autoSizeColumns(allColumnIds);  
      }
  	};
                             
    var gridDiv = document.getElementById('taskGrid');
    agGrid.LicenseManager.setLicenseKey("Elixia_Tech_Solutions_Ltd_MultiApp_1Devs16_March_2019__MTU1MjY5NDQwMDAwMA==c4c29e5702e62789dbdb799433c73545");
    new agGrid.Grid(gridDiv,gridOptions); 
    
</script>