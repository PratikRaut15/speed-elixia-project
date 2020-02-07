<?php
include_once("session.php");
include("loginorelse.php");
include_once("db.php");
include_once("../../constants/constants.php");
include_once("../../lib/system/Sanitise.php");
include_once("../../lib/system/DatabaseManager.php");
include_once("../../lib/system/DatabaseSalesManager.php");
include_once("../../lib/system/Date.php");

date_default_timezone_set("Asia/Calcutta");
$today = date("d-m-Y");
$time= date("h:i");
$created_on=date("Y-m-d H:i:s");
include("header.php");
?>
  
<div class="panel">
    <div class="paneltitle" align="center">Daily Sales Report</div>
    <div class="panelcontents">
        <form name="salesreport" id="salesreport" method="POST">
            <table>
                <tbody>
                    <tr><td>                        
                        <span id="error" style="display: none;font-size: 15px;color: red;"></span>
                        <span id="error1" style="display: none;font-size: 15px;color: red;">Enter Correct Sales Person Name.</span></td>
                    </tr>
                    <tr>
                    <td>Select Sales Person</td>
                    <td><input type="text" name="salesuser" id="salesuser" size="20" value="" autocomplete="off" placeholder="Enter Sales Person Name" onkeypress="getSalesUser();">
                        <input type="hidden" id="salesuserid" name="salesuserid" value="">
                        <input type="hidden" id="salesusername" name="salesusername" value="">
                    </td>
                    </tr>
                    <tr>
                    <td>Start Date</td>
                    <td><input type="text" name="startdate" id="startdate" size="10" value="<?php echo $today;?>"></td>
                    <td>End Date</td>
                    <td><input type="text" name="enddate" id="enddate" size="10" value="<?php echo $today;?>"></td>
                    <td class="space"></td>
                    </tr>
                    <tr>
                        <td><input type="button" id="query" name="query" value="Generate" onclick="submitForm();"/></td>
                    </tr>
                </tbody>
            </table>
        </form>
            </div>
</div> 
<div id="result" class="ag-theme-balham"  style="height:300px;width:75%;margin:0 auto;margin-top:30px;" >
</div>
  <script src="../../scripts/ag-grid-enterprise/dist/ag-grid-enterprise.min.js"></script>
<script src='../../scripts/team/dailySalesReport.js'></script>
<script>
          agGrid.LicenseManager.setLicenseKey("Elixia_Tech_Solutions_Ltd_MultiApp_1Devs16_March_2019__MTU1MjY5NDQwMDAwMA==c4c29e5702e62789dbdb799433c73545");
</script>
