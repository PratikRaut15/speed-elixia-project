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
  $taskId = 0;
if(checkUserType('',speedConstants::TEAM_ROLE_HEAD)){
  $teamId = 0;
}else{
  $teamId = GetLoggedInUserId();
}
  $teamId = GetLoggedInUserId();
$time= date("h:i");
$created_on=date("Y-m-d H:i:s");
$tm = new TimeSheetManager();
$tasks = $tm->getTeamTasks();

?>
<link rel="stylesheet" href="../../css/timesheet.css">
<div class="panel"> 
  <div class="paneltitle" align="center">Timesheet</div> 
    <div class="panelcontents" >
      <form id="timesheetList">
        <div id="selectDate" name="selectDate">
          <label>Select Date : </label>
          <input type="text" name="date" id="date" value="<?php echo date('Y-m-d')?>">
        </div>
      </form>
        <label id='status' name='status' style="font-size: 20px;margin:0 auto;">Status : <span id="statusText"></span></label>
      <div class="center">     
        <div id="timeSheetGrid" class="ag-theme-balham" style="height:400px;margin:0 auto;">
        </div>
      </div>
  </div>
</div>
<script src="../../scripts/ag-grid-enterprise/dist/ag-grid-enterprise.min.js"></script>
<script>
  var teamId = <?php echo $teamId; ?>;
  var taskId = <?php echo $taskId; ?>;
  refreshTimesheetGrid();
  var duration;
  var date;

  jQuery('#date').datepicker({
    dateFormat: "yy-mm-dd",
    language: 'en',
    autoclose: 1,
    startDate: Date()
  });
  var departmentId = <?php echo $departmentId; ?>;
  var details = [];
  var date = $("#date").val();
  $("#date").on('change',function(){
    refreshTimesheetGrid();
  });
    function refreshTimesheetGrid(){
          var date = $("#date").val();
          $.ajax({
          type: "POST",
          url: "timesheet_functions.php",
          data: {
            fetch_timeSheet:1,
            teamId : teamId,
            departmentId : departmentId,
            taskId : taskId,
            date : date,
          },
          success: function(data){
            data = JSON.parse(data);
            TSdetails = data.data; 
            duration = data.total;
            var submitTimesheet = $("#sumbit_task");
            if(data.status=='Locked'){
              $("#lock_timesheet").hide();
              $("#statusText").css("color","red");
              if(submitTimesheet.length>0){
                submitTimesheet.hide();
              }
              $(".switch-field").hide();
            }else{
              if(duration == 0){
                //console.log("found 0");
                $(".switch-field").show();
              }else{
                // console.log("duration>0");
                // console.log($(".switch-field"));
                $(".switch-field").hide();
              }
              if(submitTimesheet.length>0){
                submitTimesheet.show();
              }
              $("#lock_timesheet").show();
              $("#statusText").css("color","green");
            }
            $("#statusText").html(data.status);
            TSgridOptions.api.setRowData(TSdetails);
          }
        });
    }

    TScolumnDefs = [              
      {headerName:'Timesheet ID',field: 'tsId',width:200,filter: 'agTextColumnFilter', hide:true},
      {headerName :'Edit',cellRenderer:'editCellRenderer'},
      {headerName:'Task Name',field: 'taskName',width:200,filter: 'agTextColumnFilter',},
      {headerName:'Time',field: 'time',width:200,filter: 'agTextColumnFilter',aggFunc: addTimes},
      {headerName:'Customer(s)',field: 'customers',width:200,filter: 'agTextColumnFilter'},
      {headerName:'Product(s)',field: 'products',width:200, filter: 'agTextColumnFilter'},
      {headerName:'Name',field:'name',width:100,filter: 'agTextColumnFilter', rowGroupIndex: 2},
      {headerName:'Date',field:'date',width:100,filter: 'agTextColumnFilter',rowGroupIndex: 1},
    ];
    function editCellRenderer(params){
      //console.log(params);
      if(!params.node.group){
        //console.log(params);
        return "<a href='edit_timesheet.php?tsId="+params.data.tsId+"' target='_blank' alt='Edit Mode' title='Mode' ><img style='text-align:center; width:20px; height:20px;' src='../../images/edit_icon_black.png'/></a>"
      }
    }
function timestrToSec(timestr) {
  var parts = timestr.split(":");
  var result = (parts[0] * 3600) +
         (parts[1] * 60);
   return result;
}

function pad(num) {
  if(num < 10) {
    return "0" + num;
  } else {
    return "" + num;
  }
}

function formatTime(seconds) {
  var result =  [pad(Math.floor(seconds/3600)),
          pad(Math.floor(seconds/60)%60)
          ].join(":");
  return result;
}

    function addTimes(values){
      var sum='00:00';
      $.each(values ,function(k,v){
        sum = formatTime(timestrToSec(sum) + timestrToSec(v));
      });
      return sum;
    }
  var TSgridOptions;

    TSgridOptions = { 
      enableFilter:true,
      enableSorting: true,
      animateRows:true,
      rowData:details,
      suppressAggAtRootLevel:true,
      floatingFilter:true,
      columnDefs: TScolumnDefs,
      groupIncludeFooter: true,
      rowGroupPanelShow: 'always',
      groupDefaultExpanded : 1,
      onRowDataChanged:autoSizeCols,
      suppressAggFuncInHeader:true,
      components:{
        editCellRenderer: editCellRenderer
      },
    };
    function autoSizeCols(params){
        var allColumnIds = [];
        TSgridOptions.columnApi.getAllColumns().forEach(function(column) {
            allColumnIds.push(column.colId);
        });
        TSgridOptions.columnApi.autoSizeColumns(allColumnIds);  
    }             
    var timeSheetGrid = document.getElementById('timeSheetGrid');
    //agGrid.LicenseManager.setLicenseKey("Elixia_Tech_Solutions_Ltd_MultiApp_1Devs16_March_2019__MTU1MjY5NDQwMDAwMA==c4c29e5702e62789dbdb799433c73545");
    agGrid.LicenseManager.setLicenseKey("Elixia_Tech_Solutions_Ltd_MultiApp_1Devs16_March_2019__MTU1MjY5NDQwMDAwMA==c4c29e5702e62789dbdb799433c73545");
    new agGrid.Grid(timeSheetGrid,TSgridOptions); 

</script>