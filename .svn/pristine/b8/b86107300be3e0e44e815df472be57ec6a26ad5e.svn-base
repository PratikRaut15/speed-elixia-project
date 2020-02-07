<?php
  include_once("session.php");
  include("loginorelse.php");
  include_once("db.php");
  include_once("../../lib/system/Date.php");
  date_default_timezone_set("Asia/Calcutta");
  $today_date = date("d-m-Y");
  include_once ("header.php");
?>
<style>
  .loginTrend{
    text-align: center;
    margin: 10px 10px 0;
    float: right;
  }
  .login_trend_table{
    float: right;
  }
</style>
<br>
<div class="panel">
  <div class="paneltitle" align="center">Team Attendance Logs</div> 
    <div class="panelcontents">
      <label>Date</label>
      <label for="status" href="#" title="Attendance Logs" data-toggle="popover" data-trigger="hover" data-placement="bottom" data-content="Select date to fetch respective logs." style="float: right;"><i class="material-icons">info</i></label>
      <input type="text" name="attendance_date" id="attendance_date"  value="<?php echo $today_date ?>">
      <div id="attendance_logsGrid" class="ag-theme-fresh" style="height:500px;width:800px;margin:0 auto;"></div>
  </div>
</div>
<script>

  jQuery(document).ready(function () {
    var date = '<?php echo $today_date;?>';

    $('#attendance_date').datepicker({
          dateFormat: "dd-mm-yy",
          language: 'en',
          autoclose: 1,
          startDate: Date()
    });
    
    $('[data-toggle="popover"]').popover();   
    
    attendance_logs(date);
  });

  $("#attendance_date").change(function(){
    var new_date = $("#attendance_date").val();
    attendance_logs(new_date);
  });

  function attendance_logs(date){
            jQuery.ajax({
                type: "POST",
                url: "route_team.php",
                data: "fetch_attendance_logs=1&date="+date,
                success: function(result){
                    var response = JSON.parse(result);
                    gridOptions_Login.api.setRowData(response);
                }
            });
  }
</script>
<script src="https://unpkg.com/ag-grid-enterprise@18.0.0/dist/ag-grid-enterprise.min.js"></script>
<script>

    agGrid.LicenseManager.setLicenseKey("Elixia_Tech_Solutions_Ltd_MultiApp_1Devs16_March_2019__MTU1MjY5NDQwMDAwMA==c4c29e5702e62789dbdb799433c73545");
       
                          columnDefs = [
                            {headerName:'Name',field: 'name',width:200,filter:'agTextColumnFilter',rowGroup:true},
                            {headerName:'Record',field: 'checkValue',width:200,filter:'agTextColumnFilter'},
                            {headerName:'Date & Time',field: 'createdOn',width:200,filter:'agTextColumnFilter'}
                        ];
              
                        
                                  var gridOptions_Login;
                                  gridOptions_Login = {
                                    enableFilter:true,
                                    enableSorting: true,
                                    floatingFilter:true,
                                    animateRows:true,
                                    columnDefs: columnDefs,
                                    masterDetail: true,                                
                                    onGridReady: function(params) {
                                      gridOptions_Login.columnApi.autoSizeColumns();
                                    }
                                  
      
                                  };
                             
                             //gridOptions.rowHeight = 20;
                             var gridDiv = document.getElementById('attendance_logsGrid');
                             new agGrid.Grid(gridDiv,gridOptions_Login);                    
</script>
<?php

include_once("footer.php");
?>