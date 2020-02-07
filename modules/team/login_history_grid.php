<?php
include_once("session.php");
include("loginorelse.php");
include_once("db.php");
include_once("../../constants/constants.php");
include_once("../../lib/bo/TeamManager.php"); 
include_once("../../lib/system/DatabaseManager.php"); 
include_once("../../lib/system/Sanitise.php");

$today_date = date("d-m-Y");
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
   <div class="paneltitle" align="center">Login History</div>
    <br>
    <label>Date</label> <input type="text" id="login_history_date" name="login_history_date" value="<?php echo $today_date; ?>">
    <input type="button" name="loginKaButton" id="loginKaButton" value="Submit" onclick="login_history();">
  <div id="myGrid" class="ag-theme-fresh" style="height:200px;width:450px;margin:0 auto;"></div>
<script src="https://unpkg.com/ag-grid-enterprise@17.0.0/dist/ag-grid-enterprise.min.js"></script>
<script src="../../bootstrap/js/bootstrap-datepicker.js"></script>
<script>
    var result = [];
    agGrid.LicenseManager.setLicenseKey("Elixia_Tech_Solutions_Ltd_MultiApp_1Devs16_March_2019__MTU1MjY5NDQwMDAwMA==c4c29e5702e62789dbdb799433c73545");
       
                          columnDefs = [
                        
                           {headerName:'Login Date',field: 'date',width:150},
                           {headerName:'Login Time',field: 'time',width:150},
                           {headerName:'User Name',field: 'user',width:150}
                        ];
                        
                                  var gridOptions_Login;
                                  gridOptions_Login = {
                                    enableFilter:true,
                                    enableSorting: true,
                                    animateRows:true,
                                    rowData:result,
                                    columnDefs: columnDefs,
                                    masterDetail: true,
                                  };
                             
                             //gridOptions.rowHeight = 20;
                             var gridDiv = document.getElementById('myGrid');
                             new agGrid.Grid(gridDiv,gridOptions_Login); 

                       Calendar.setup({
                        inputField: "login_history_date", // ID of the input field
                        ifFormat: "%d-%m-%Y", // the date format
                        button: "login_history_date" // ID of the button
                      });

</script>
