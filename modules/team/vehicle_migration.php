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
<style>
    .panel{
    width:1224px !important;
    }
    .paneltitle{
    width:1208px !important;
    }
    .close{
      font-size: 30px !important;    
    }

    </style>
<link rel="stylesheet" href="../../css/vehicleMigrate.css">
<div class="panel">
  <div class="paneltitle" align="center">Vehicle Migration</div> 
  <div class="panelcontents">
    <div class="center">  
      <form name="vehicle_migrate" id="vehicle_migrate">
        <label>From Customer</label>
        <input type="text" name="customerno" id="customerno" size="30" value="" autocomplete="off" placeholder="Enter Customer Name or number" onkeypress="getCustomer();"/>
        <label>To Customer</label>
        <input type="text" name="to_customerno" id="to_customerno" size="30" value="" autocomplete="off" placeholder="Enter Customer Name or number" onkeypress="getCustomer();"/>
        <input type='hidden' id="customerno_1" name="customerno_1">
        <input type='hidden' id="customerno_2" name="customerno_2">
        <input type='hidden' id="vehicleList" name="vehicleList">
        <label>Vehicle Number</label>
        <input  type='text' name="vehicleno" id="vehicleno" size='20' placeholder='Enter Vehicle No' onkeyup='getVehicle();'>
        <br>
        <input type='button' id='migrateVehicle' name='migrateVehicle' value='Migrate Vehicle' onclick="migrateVehicles();">
         <input type='button' style="float:right;" id='reset_form' name='reset_form' value='Reset Form' onclick="resetForm();">
      </form>
          <div name="new_vehicles" id="new_vehicles" style="display:none;float:left;">
            <fieldset>
              <legend style="color:#1dbc1d;">Migrated Vehicles</legend>
                <div name="new_table" id="new_table"></div>
            </fieldset>
          </div>
          <div name="existing_vehicles" id="existing_vehicles" style="display:none;float:right;">
            <fieldset>
              <legend style="color:#F00;">Existing Vehicles</legend>
                <div name="existing_table" id="existing_table"></div>
            </fieldset>
          </div> 
    </div> 
  </div>
</div>

<script src="../../scripts/team/vehicle_migration.js"></script>
