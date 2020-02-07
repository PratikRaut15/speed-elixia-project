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
<body>
<div class="panel">
  <div class="paneltitle" align="center">Customer Sanity</div> 
    <div id="customer_sanity_div">
      <div class="panelcontents">
        <div class="center">  
          <label>Customer</label>
            <input type="text" name="customername" id="customername" size="30" value="" autocomplete="off" placeholder="Enter Customer Name or number" onkeypress="getCustomer();"/> 
          <img src="../../images/success.png" width="40" height="48" id="final_success" style="display: none;"><img src="../../images/fail.png" width="40" height="48" id="final_failure" style="display: none;">  
          <div id="ledger" name="ledger" style="display: none;">
            <?php 
                include('ledger_list_verification.php');
                ?>
          </div>
          <div class="rows">
            <div class="column">
              <div name="unmapped_Vehicles" id="unmapped_Vehicles" style="display:none;float:left;">
                <fieldset>
                  <legend>2) Vehicles Unmapped
                  <img src="../../images/success.png" width="30" height="34" id="vehicle_success" style="display: none;"><img src="../../images/fail.png" width="30" height="34" id="vehicle_failure" style="display: none;">
                  <span style="float:right;">
                  <i class="far fa-window-maximize" title="maximize" id="maximize_vehicle" onclick="maximize_vehicle();" style="display:none;"></i>
                    <i class="fas fa-times" title="close" id="minimize_vehicle" onclick="minimize_vehicle();" style="display: none;"></i>
                  </span>
                  </legend>
                  <div name="unmapped_vehicle_div" id="unmapped_vehicle_div"></div>
                </fieldset>
              </div>
            </div>
            <div class="column">
              <div name="customer_Details" id="customer_Details" style="display:none;float:right;">
                <fieldset>
                  <legend>3) Customer Details
                  <img src="../../images/success.png" width="30" height="34" id="customer_success" style="display: none;"><img src="../../images/fail.png" width="30" height="34" id="customer_failure" style="display: none;">
                  <span style="float:right;">
                    <i class="far fa-window-maximize" title="maximize" id="maximize_customer" onclick="maximize_customer();"></i>
                    <i class="fas fa-times" title="close" id="minimize_customer" onclick="minimize_customer();"></i>
                  </span>
                  </legend>
                  <div name="customer_details_div" id="customer_details_div"></div>
                </fieldset>
              </div>
            </div>
          </div>
          <div class="rows">
            <div class="column">
              <div name="additional_customer_Details" id="additional_customer_Details" style="display:none;float:left;">
               
                <fieldset>
                  <legend>4)Additional Customer Details  <a alt='Edit Mode' title='Edit' target='_blank'id="additional_link" name="additional_link"><img style='text-align:center;display: none; width:20px; height:20px;' src='../../images/edit.png' id="additional_link_image" name="additional_link_image"/></a>
                    <br>
                    <span><h4>Owner</h4>
                    <img src="../../images/success.png" width="30" height="34" id="owner_success" style="display: none;">
                    <img src="../../images/fail.png" width="30" height="34" id="owner_failure" style="display: none;">
                    </span>
                    <br>
                    <span><h4>Account</h4>
                    <img src="../../images/success.png" width="30" height="34" id="account_success" style="display: none;">
                    <img src="../../images/fail.png" width="30" height="34" id="account_failure" style="display: none;">
                    </span>
                    <br>
                    <span><h4>Co-ordinator</h4>
                    <img src="../../images/success.png" width="30" height="34" id="coord_success" style="display: none;">
                    <img src="../../images/fail.png" width="30" height="34" id="coord_failure" style="display: none;">
                    </span>
                  </legend>
                </fieldset>
              </div>
            </div>
            <div class="column">
              <div name="devices_info" id="devices_info" style="display:none;float:right;">
                <fieldset>
                  <legend>5) Device Details
                  <img src="../../images/success.png" width="30" height="34" id="device_success" style="display: none;"><img src="../../images/fail.png" width="30" height="34" id="devices_failure" style="display: none;">
                  <span style="float:right;">
                    <i class="far fa-window-maximize" title="maximize" id="maximize_device" onclick="maximize_device();"></i>
                    <i class="fas fa-times" title="close" id="minimize_device" onclick="minimize_device();"></i>
                  </span>
                  </legend>
                  <div name="devices_info_div" id="devices_info_div"></div>
                </fieldset>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
</div> 
</body>
<script src="../../scripts/team/customer_verification.js"></script>
<script>
  function maximize_customer_sanity(){
    $("#customer_sanity_div").show(500);
  }
  function minimize_customer_sanity(){
    $("#customer_sanity_div").hide(500);
  }
</script>
<?php
    include "footer.php";
?>
