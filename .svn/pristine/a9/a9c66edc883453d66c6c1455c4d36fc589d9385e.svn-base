<?php
include_once("session.php");
include("loginorelse.php");
include_once("db.php");
include_once("../../constants/constants.php");
include_once("../../lib/system/Sanitise.php");
include_once("../../lib/system/DatabaseManager.php");
include("../../lib/bo/TeamManager.php");
include_once("../../lib/system/Date.php");

date_default_timezone_set("Asia/Calcutta");
$today = date("d-m-Y");
$time= date("h:i");
$created_on=date("Y-m-d H:i:s");


include("header.php");
?>
<link rel="stylesheet" href="../../css/distributorDetails.css">
<div class="panel">
  <div class="paneltitle" align="center">Customer Creation</div> 
  <div class="panelcontents">
    <div class="center">  
      <form name="distributor_form" id="distributor_form">
               
                  <label>Customer Name</label>
                    <input type="text" name="c_name" id="c_name" placeholder="Enter Customer Name" required/> 
                  <label>Company Name</label>
                    <input type="text" name="comp_name" id="comp_name" placeholder="Enter Company Name" required/>
                  <label>Address</label>
                  <textarea name="address" id="address" placeholder="Enter Address" required></textarea>
                  <br>
                  <label>Phone</label>
                  <input type="text" name="phone" id="phone" placeholder="Enter Phone" required maxlength="12" /> 
                  
                  <label>Email</label>
                  <input type="text" name="email" id="email" placeholder="Enter Email" required/> 
                  <br>
                  <label>Address Proof</label>
                  <input type="file" name="file_address" id="file_address" value=''/>
                  <label>Photo Proof</label>
                  <input type="file" name="file_photo" id="file_photo" value=''/>
                <input type="button" name="submit_distributor_form" id="submit_distributor_form" value="Submit" onclick="submitDistributorInfo();" style="margin-left:40%;"/>       
      </form>
    </div>
  </div>
</div> 

<script src='../../scripts/team/distCustDetails.js'></script>
