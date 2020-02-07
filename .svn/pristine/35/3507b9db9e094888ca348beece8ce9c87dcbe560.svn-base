<?php
    include_once "session.php";
    include "loginorelse.php";
    include_once "db.php";
    include_once "../../constants/constants.php";
    include_once "../../lib/system/Sanitise.php";
    include_once "../../lib/components/gui/objectdatagrid.php";
    include_once "../../lib/system/DatabaseManager.php";
    include "header.php";
    $db = new DatabaseManager();
 ?>
<!--  background-color:#db3236;color:#fff; RED -->
<!--  background-color:#3cba54;color:#fff; GREEN -->
<!--  background-color:#f4c20d;color:#000; YELLOW -->
<!--  background-color:#4885ed;color:#fff; BLUE -->
<html>
<header>
  <link rel="stylesheet" href="../../css/customer_verification.css">
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.2.0/css/all.css" integrity="sha384-hWVjflwFxL6sNzntih27bfxkr27PmbbK/iSvJ+a4+0owXq79v+lsFkW54bOGbiDQ" crossorigin="anonymous">
</header>
<style>
th{
  padding: 1.25rem 2rem !important;
  font-size:14px;
}
</style>
<body>
<div class="panel">
  <div class="paneltitle" align="center">Troubleshooting</div> 
  <div class="panelcontents">
    <div class="center">  
      <label>Customer</label>
        <input type="text" name="customername" id="customername" size="30" value="" autocomplete="off" placeholder="Enter Customer Name or number" onkeypress="getCustomer();"/> 
       <label>Unit No.</label>

        <select name="unit_no" id="unit_no">
          <option value="0">Select Unit</option>
        </select> 

        <img src="../../images/success.png" width="40" height="48" id="final_success" style="display: none;"><img src="../../images/fail.png" width="40" height="48" id="final_failure" style="display: none;"> 
        <div id="unit_details_div" name="unit_details_div" style="display: none;">
          
          <table border="1" align='center' class="unitDetailsTable" id="unitDetailsTable" name="unitDetailsTable" style="margin: 0 20% !important;text-align: center;font-size: 12px;">
          <tr><th>Data Tranmission Delay (More Than 5 min)</th><th>Location Captured</th>
            <th>GPS Active</th><th>Power Cut </th><th>Tamper </th></tr>
          <tr>
            <td><img src="../../images/success.png" width="30" height="34" id="delay_success" style="display: none;"><img src="../../images/fail.png" width="30" height="34" id="delay_failure" style="display: none;"></td>
            <td><img src="../../images/success.png" width="30" height="34" id="location_success" style="display: none;"><img src="../../images/fail.png" width="30" height="34" id="location_failure" style="display: none;"></td>
            <td><img src="../../images/success.png" width="30" height="34" id="gps_success" style="display: none;"><img src="../../images/fail.png" width="30" height="34" id="gps_failure" style="display: none;"></td>
            <td><img src="../../images/success.png" width="30" height="34" id="powercut_success" style="display: none;"><img src="../../images/fail.png" width="30" height="34" id="powercut_failure" style="display: none;"></td>
            <td><img src="../../images/success.png" width="30" height="34" id="tamper_success" style="display: none;"><img src="../../images/fail.png" width="30" height="34" id="tamper_failure" style="display: none;"></td>
          </tr>
          </table>
        </div>
    </div>
  </div>
</div>
</body>
<script src="../../scripts/team/troubleshooting.js"></script>
<?php
    include "footer.php";
?>