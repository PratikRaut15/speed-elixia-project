<div class="tabbable tabs-left" style="width: 70%; clear: both">
  <div class="" style="float: right;">
    <input type="button" id='sendapproval' onclick="sendapproval();" value="Send For Approval" disabled="disabled" class="btn btn-success"/>
    <input type="hidden" id="vehicle_id" value=''/>
    <input type="hidden" id="edit_vehicle_id" value=''/>
    <input type="hidden" id="general_complete" value=''/>
    <input type="hidden" id="desc_complete" value=''/>
    <input type="hidden" id="tax_complete" value=''/>
    <input type="hidden" id="insurance_complete" value=''/>
    <input type="hidden" id="maintenance_complete" value=''/>
    <input type="hidden" id="capitalization_complete" value=''/>
    <input type="hidden" id="papers_complete" value=''/>
    <input type="hidden" id="geo_complete" value=''/>
    <input type="hidden" name="customerno" id="customerno" value="<?php echo $_SESSION['customerno'] ?>">
    <input type="hidden" name="cheirarchy" id="cheirarchy" value="<?php echo $_SESSION['use_hierarchy'] ?>">    
    <br>
  </div>
  <br>
  </br>
  <ul class="nav nav-tabs">
    <li class="active abc" style="display: none;"><a href="#tab_l1" data-toggle="tab" disabled>General</a></li>
    <li class="abc" style="display: none;"><a href="#tab_l2" data-toggle="tab">Description</a></li>
    <li class="abc" style="display: none;"><a href="#tab_l3" onclick="gettax();" data-toggle="tab">Tax / Registration</a></li>
    <li class="abc" style="display: none;"><a href="#tab_l4" data-toggle="tab">Insurance</a></li>
    <li class="abc" style="display: none;"><a href="#tab_l5" onclick='getbattery();' data-toggle="tab">Transactions</a></li>
    <li class="abc" style="display: none;"><a href="#tab_l6" data-toggle="tab">Capitalization</a></li>
    <li class="abc" style="display: none;"><a href="#tab_l7" data-toggle="tab">Papers</a></li>
    <li class="abc" style="display: none;"><a href="#tab_l8" data-toggle="tab">Geo Tag</a></li>
  </ul>
  <div class="tab-content">
    <div class="tab-pane active" id="tab_l1">
      <fieldset>
      <div class="control-group">
        <form method="POST" id="general">
          <span id="general_success" style="display:none; color: #00FF00">General Data Added Successfully.</span> 
          <span id="vehicle_error" style="display:none; color: #FF0000">Please Enter Vehicle No.</span> 
          <span id="vehicle_input_error" style="display:none; color: #FF0000">Vehicle No. must be Capital and Numeric.</span> 
          <span id="meter_error" style="display:none; color: #FF0000">Start Meter Reading must be Numeric only.</span> 
          <span id="yearofman_error" style="display:none; color: #FF0000">Please Enter Year of Manufacture.</span> 
          <span id="yearofman_2000_error" style="display:none; color: #FF0000">Year of Manufacture cannot be less than 2000</span>           
          <span id="PDate_error" style="display:none; color: #FF0000">Please Enter Purchase Date.</span> 
          <span id="year_error" style="display:none; color: #FF0000">Purchase Year cannot be less than Year of Manufacture.</span> 
          <span id="make_error" style="display:none; color: #FF0000">Please Select Make.</span> 
          <span id="branch_error" style="display:none; color: #FF0000">Please Select Branch.</span>
          <div class="input-prepend "> <span class="add-on">Vehicle No. <span style="color:#FE2E2E; ">*</span></span>
            <input type="text" name="vehicle_no" id="vehicle_no" placeholder="Vehicle No" autofocus maxlength="10" OnKeyPress="return nospecialchars(event)">
            <span class="add-on">Kind</span>
            <select id="kind" name="kind">
              <option value="">Select Kind</option>
              <option value='Bus'>Bus</option>
              <option value='Truck'>Truck</option>
              <option value='Car'>Car</option>
              <option value='SUV'>SUV</option>              
            </select>
            <span class="add-on">Make</span>
            <select id="make" name="make" onchange="getmodel()">
              <option value="">Select Make</option>
              <?php
                    $makes = getmakes();
                    if(isset($makes))
                    {
                    foreach ($makes as $make)
                    {
                    echo "<option value='$make->id'>$make->name</option>";
                    }
                    }
                    ?>
            </select>
            <span class="add-on">Model</span>
            <select id="model" name="model">
              <option value="">Select Model</option>
            </select>
          </div>
          <br>
          <div class="input-prepend formSep"> <span class="add-on">Year of Manufacture <span style="color:#FE2E2E; ">*</span></span>
            <input type="text" name="yearofman" id="yearofman" placeholder="e.g. 2011" maxlength="4" >
            <span class="add-on">Purchase Date <span style="color:#FE2E2E; ">*</span></span>
            <input id="PDate" name="PDate" type="text" class="input-small" value="" required=""  />
          </div>
          <div class="input-prepend formSep "> <?php echo($_SESSION['group']); ?> Details </div>
          <div class="input-prepend formSep "> <span class="add-on"><?php echo($_SESSION['group']); ?> <?php if($_SESSION['use_hierarchy'] == '1') { ?><span style="color:#FE2E2E; ">*</span> <?php } ?></span>
            <select id="branchid" name="branchid" <?php if($_SESSION['use_hierarchy'] == '1') { ?> onchange="showbranch()" <?php } ?>>
              <option value="">Select <?php echo($_SESSION['group']); ?></option>
              <?php
                    $groups = getgroupss();
                    if(isset($groups))
                    {
                        foreach ($groups as $group)
                        {
                            echo "<option value='$group->groupid'>$group->groupname</option>";
                        }
                    }
                    ?>
            </select>
            <div id="branch_div"></div>
          </div>
          <br>
          <div class="input-prepend formSep "> Miscellaneous </div>
          <div class="input-prepend formSep "> <span class="add-on">Start Meter Reading</span>
            <input type="text" name="start_meter" id="start_meter" placeholder="e.g. 12586" maxlength="10" >
            <span class="add-on">Overspeed Limit</span>
            <input type="text" name="overspeed" id="overspeed" placeholder="Overspeed Limit" maxlength="20" value="80" readonly>
            <span class="add-on">Fuel Type</span>
            <select id="fueltype" name="fueltype">
              <option value="">Select Fuel Type</option>
              <option value='Petrol'>Petrol</option>
              <option value='Diesel'>Diesel</option>
              <option value='CNG'>CNG</option>
            </select>
            <!--                        <span class="add-on">GPS</span><input type="text" readonly name="gps" id="gps" placeholder="12586" maxlength="10">
                                <span class="add-on">Difference</span><input type="text" readonly name="difference" id="difference" placeholder="12586" maxlength="10">-->
          </div>
          <input type="button" onclick="addvehicles();" value="Save" class="btn btn-success">
        </form>
      </div>
      <!--			<div class="form-group">
			<div class="input-prepend ">
			<span class="add-on">Vehicle No <span class="mandatory">*</span></span><input type="text" name="vehicleno" id="vehicleno" placeholder="License Plate No" autofocus maxlength="20">
			</div>
			<div class="input-prepend ">
			<span class="add-on">Type </span><select name="type">
								<option value="Car">Car</option>
								<option value="Bus">Bus</option>
							</select>
			</div>
			</div>-->
      </fieldset>
    </div>
    <div class="tab-pane" id="tab_l2">
      <fieldset>
      <div class="control-group">
        <form method="POST" id="description">
          <span id="description_success" style="display:none; color: #00FF00">Description Added Successfully.</span> 
          <span id="engineno_error" style="display:none; color: #FF0000">Engine no. should be in Capital</span> 
          <span id="engineno_comp_error" style="display:none; color: #FF0000">Engine no. cannot be empty</span>           
          <span id="chasisno_comp_error" style="display:none; color: #FF0000">Chasis no. cannot be empty</span>                     
          <span id="chasisno_error" style="display:none; color: #FF0000">Chasis no. should be AlphaNumeric and Capital, a total of 20 characters </span>
          <span id="invoice_year_error" style="display:none; color: #FF0000">Invoice Year cannot be less than Year of Manufacture.</span>           
          <div class="input-prepend"> <span class="add-on">Engine No.</span>
            <input type="text" name="engineno" id="engineno" placeholder="engine no." maxlength="20" OnKeyPress="return nospecialchars(event)">
            <span class="add-on">Chasis No.</span>
            <input type="text" name="chasisno" id="chasisno" placeholder="chasis no." maxlength="20" OnKeyPress="return nospecialchars(event)">
          </div>
          <br/>
          <div class="input-prepend formSep "> <span class="add-on">Vehicle Purpose</span>
            <select id="veh_purpose" name="veh_purpose">
              <option value="">Select Purpose</option>
              <option value='1'>Employee CTC</option>
              <option value='2'>Branch Vehicle</option>
              <option value='3'>Zone Vehicle</option>
              <option value='4'>Regional Vehicle</option>
              <option value='5'>Head Office Vehicle</option>
            </select>
            <span class="add-on">Vehicle Type</span>
            <select id="veh_type" name="veh_type">
              <option value="">Select Vehicle Type</option>
              <option value='1'>New</option>
              <option value='2'>Repossesed</option>
              <option value='3'>Employee</option>
            </select>
          </div>
          <div class="input-prepend formSep "> Dealer Details </div>
          <div class="input-prepend formSep "> <span class="add-on">Dealer Name</span>
            <select id="dealerid" name="dealerid" onchange="showdealer()">
              <option value="">Select Dealer</option>
              <?php
                $dealers = getdealersbytype(5,$_SESSION['roleid'],$_SESSION['heirarchy_id']);
                if(isset($dealers))
                {
                    foreach ($dealers as $dealer)
                    {
                    echo "<option value='$dealer->dealerid'>$dealer->name</option>";
                    }
                }
                ?>
            </select>
            <span class="add-on">Code</span>
            <input type="text" name="code_dealer" readonly id="code_dealer">
            <br/><br/>
            <span class="add-on">Invoice No.</span>
            <input type="text" name="invoiceno" id="invoiceno" placeholder="12586" maxlength="10" OnKeyPress="return nospecialchars(event)">
            <span class="add-on">Invoice Date</span>
            <input id="invoice_date" name="invoice_date" type="text" class="input-small" value="24-04-2014" required="">
          </div>
          <input type="button" onclick="adddescription();" id="description_save" value="Save" class="btn btn-success">
        </form>
      </div>
      </fieldset>
    </div>
    <div class="tab-pane" id="tab_l3">
      <fieldset>
      <div class="control-group"> <span id="tax_success" style="display:none;">Tax details added successfully, please fill up the vehicle insurance details.</span>
        <div class="input-prepend formSep "> Vehicle Tax<a href="#addtax" data-toggle="modal" style="width: 16px; height:16px; vertical-align:middle; float: right;"><img class="tooltip-top" src="../../images/show.png" style="width: 16px; height:16px; vertical-align:middle; float: right;" original-title="Click To Add Tax"></a> </div>
        <div class="input-prepend">
          <table  class="table  table-bordered table-striped dTableR dataTable"  style=" width:90%">
            <thead>
              <tr>
                <th>Type</th>
                <th>From</th>
                <th>To</th>
                <th>Amount</th>
                <th>Last Modified By</th>
                <th>Last Modified At</th>
                <th colspan="2">Options</th>
              </tr>
            </thead>
            <tbody id="tax_body">
            </tbody>
          </table>
        </div>
      </div>
      </fieldset>
    </div>
    <div class="tab-pane" id="tab_l4">
      <fieldset>
      <form method="POST" id="insurance_form">
        <span id="insurance_success" style="display:none; color: #00FF00">Insurance details added successfully</span> 
        <span id="insurance_value_error" style="display:none; color: #FF0000">Please enter digits only in Value of Insurance.</span> 
        <span id="value_empty_error" style="display:none; color: #FF0000">Value of Insurance cannot be empty</span>         
        <span id="premium_empty_error" style="display:none; color: #FF0000">Premium cannot be empty</span>                 
        <span id="company_error" style="display:none; color: #FF0000">Please select Insurance Company</span>                         
        <span id="date_conf_error" style="display:none; color: #FF0000">Start date cannot be greater than End date</span> 
        <span id="man_date_error" style="display:none; color: #FF0000">Start Date & End Date must not be in the range preceeding the manufacturing date of the vehicle</span>         
        <span id="premium_value_error" style="display:none; color: #FF0000">Please enter digits only in Premium.</span>
        <div class="control-group">
          <div class="input-prepend formSep"> <span class="add-on">Do you have insurance?</span>
            <input type="radio" name="insurance" id="insurance1" value="yes">
            Yes
            <input type="radio" name="insurance" id="insurance2" checked value="no">
            No </div>
        </div>
        <div class="control-group">
          <div id="radio_show" style="display: none;">
            <div class="input-prepend"> <span class="add-on">Value of Insurance</span>
              <input type="text" name="insurance_value" id="insurance_value" maxlength="10" >
              <span class="add-on">Premium</span>
              <input type="text" name="premium_value" id="premium_value" maxlength="10" >
            </div>
            <br>
            <div class="input-prepend"> 
              <span class="add-on">Start Date</span>
              <input id="StartDate" name="StartDate" type="text" class="input-small" value="24-04-2014" required>                
                <span class="add-on">End Date</span>
              <input id="EndDate" name="EndDate" type="text" class="input-small" value="24-04-2014" required>
<!--              <span class="add-on">Amount</span>
              <input type="text" name="ins_amount" id="ins_amount" value=""> -->
              <span class="add-on">Notes</span>
              <input type="text" name="ins_notes" id="ins_notes" maxlength="20" OnKeyPress="return nospecialchars(event)">
            </div>
            <br>
            <div class="input-prepend formSep"> <span class="add-on">Insurance Company</span>
              <select name="insurance_company" id="insurance_company">
                <option value="">Select Company</option>
                <?php
                                                                        $ins_comp = getinsurance_company();
                                                                        if(isset($ins_comp))
                                                                        {
                                                                            foreach ($ins_comp as $ins)
                                                                            {
                                                                            echo "<option value='$ins->id'>$ins->name</option>";
                                                                            }
                                                                        }
                                                                ?>
              </select>
              <span class="add-on">Nearest Place Of Claim</span>
              <input type="text" name="near_place" placeholder="e.g. mumbai, delhi" id="near_place" maxlength="20" OnKeyPress="return nospecialchars(event)">
            </div>
          </div>
        </div>
        <input type="button" onclick="addinsurance();" id="insurance_save" value="Save" class="btn btn-success">
      </form>
      </fieldset>
    </div>
    <div class="tab-pane" id="tab_l5"style="overflow-y: scroll;height: 295px;">
      <fieldset>
      <div class="control-group"> <span id="battery_success" style="display:none;">Battery History Details added successfully.</span> <span id="tyre_success" style="display:none;">Tyre History Details added successfully.</span>
        <div class="input-prepend formSep "> Battery Replacement History<a href="#addbattery" data-toggle="modal" style="width: 16px; height:16px; vertical-align:middle; float: right;"><img class="tooltip-top" src="../../images/show.png" style="width: 16px; height:16px; vertical-align:middle; float: right;" original-title="Click To Add Battery Replacement History"></a> </div>
        <div class="input-prepend formSep">
          <table  class="table  table-bordered table-striped dTableR dataTable"  style=" width:100%">
            <thead>
              <tr>
                <th>Transaction ID</th>                                                                                          
                <th>Replacement Date</th>
                <th>Last Modified By</th>
                <th>Last Modified At</th>
                <th>Status</th>
                <th colspan="2">Options</th>
              </tr>
            </thead>
            <tbody id="battery_body">
            </tbody>
          </table>
        </div>
        <div class="input-prepend formSep "> Tyre Replacement History<a href="#addtyre" data-toggle="modal" style="width: 16px; height:16px; vertical-align:middle; float: right;"><img class="tooltip-top" src="../../images/show.png" style="width: 16px; height:16px; vertical-align:middle; float: right;" original-title="Click To Add Tyre Replacement History"></a> </div>
        <div class="input-prepend">
          <table  class="table  table-bordered table-striped dTableR dataTable"  style=" width:100%">
            <thead>
              <tr>
                <th>Transaction ID</th>                                                                                          
                <th>Replacement Date</th>
                <th>Last Modified By</th>
                <th>Last Modified At</th>
                <th>Status</th>
                <th colspan="2">Options</th>
              </tr>
            </thead>
            <tbody id="tyre_body">
            </tbody>
          </table>
        </div>
        <div class="input-prepend formSep "> Repair / Service History<a href="#addrepair" data-toggle="modal" style="width: 16px; height:16px; vertical-align:middle; float: right;"><img class="tooltip-top" src="../../images/show.png" style="width: 16px; height:16px; vertical-align:middle; float: right;" original-title="Click To Add Repair / Service Replacement History"></a> </div>
        <div class="input-prepend ">
          <table  class="table  table-bordered table-striped dTableR dataTable"  style=" width:100%">
            <thead>
              <tr>
                <th>Transaction ID</th>                                                                                          
                <th>Replacement Date</th>
                <th>Last Modified By</th>
                <th>Last Modified At</th>
                <th>Status</th>
                <th colspan="2">Options</th>
              </tr>
            </thead>
            <tbody id="repair_body">
            </tbody>
          </table>
        </div>
			<div class="input-prepend formSep " id="add_accessory_new">
                            Accessory History<a href="#addaccessory" data-toggle="modal" style="width: 16px; height:16px; vertical-align:middle; float: right;"><img class="tooltip-top" src="../../images/show.png" style="width: 16px; height:16px; vertical-align:middle; float: right;" original-title="Click To Add Accessory History"></a>
                        </div>	
			<div class="input-prepend formSep">
                            <table  class="table  table-bordered table-striped dTableR dataTable"  style=" width:100%">
                                <thead>

                                <tr>
                                    <th>Transaction ID</th>                                                                        
                                    <th>Replacement Date</th>                                    
                                    <th>Last Modified By</th>                                            
                                    <th>Last Modified At</th>                                                                                
                                    <th>Status</th>
                                    <th colspan="2">Options</th>
                                </tr>
                                </thead>
                                <tbody id="accessory_body">
                                </tbody>
                            </table>
                        </div>                            
        <div class="input-prepend formSep "> Accident Event / Insurance Claim History<a href="#addaccident" data-toggle="modal" style="width: 16px; height:16px; vertical-align:middle; float: right;"><img class="tooltip-top" src="../../images/show.png" style="width: 16px; height:16px; vertical-align:middle; float: right;" original-title="Click To Add Accident History"></a> </div>
        <div class="input-prepend ">
          <table  class="table  table-bordered table-striped dTableR dataTable"  style=" width:100%">
            <thead>
              <tr>
                <th>Transaction ID</th>                                                                                          
                <th>Replacement Date</th>
                <th>Last Modified By</th>
                <th>Last Modified At</th>
                <th>Status</th>
                <th colspan="2">Options</th>
              </tr>
            </thead>
            <tbody id="accident_body">
            </tbody>
          </table>
        </div>
      </div>
      </fieldset>
    </div>
    <div class="tab-pane" id="tab_l6"style="overflow-y: scroll;height: 295px;">
      <fieldset>
      <span id="capitalization_success" style="display:none; color: #00FF00">Capitalization details added successfully</span>
      <span id="cap_amount_error" style="display:none; color: #FF0000">Amount cannot be empty</span>      
      <span id="cap_date_man_error" style="display:none; color: #FF0000">Date cannot preceed manufacturing date of the vehicle</span>
      <div class="control-group">
        <form method="POST" id="capitalization_form">
          <div class="input-prepend formSep "> Capitalization </div>
          <div class="input-prepend"> <span class="add-on">Date</span>
            <input id="cap_EDate" name="cap_EDate" type="text" class="input-small" value="24-04-2014" required="" />
            <span class="add-on">Cost</span>
            <input type="text" name="cap_cost" id="cap_cost" maxlength="10" value="0" />
            <!--                        <span class="add-on">% (Maintenance / Capitalization)</span> <input type="text" name="cap_code" readonly id="cap_code" />-->
          </div>
          <br>
          <div class="input-prepend"> <span class="add-on">Address</span>
            <textarea name="cap_address" id="cap_address" maxlength="200" OnKeyPress="return nospecialchars(event)"></textarea>
          </div>
          <input type="button" onclick="addcap();" id="capitalization_save" value="Save" class="btn btn-success">
        </form>
      </div>
      </fieldset>
    </div>
              <div class="tab-pane" id="tab_l7"  style="overflow-y: scroll;height: 295px;">
                    <fieldset>
                        <form method="POST" id="editpapers" enctype="multipart/form-data">
                        <span id="papers_success" style="display:none;">Papers uploaded successfully.</span>
						<div id="uploadpaper"><p class="alert alert-info">Note: Upload the Papers in pdf format only.</p></div>
                                                
                                <div class="input-prepend formSep ">
                                    <b>PUC <?php echo $_GET['vid'];?></b>
                                </div>	
                                                
			<div class="control-group" style="text-align: left; margin-left: 10px;">
			<div class="input-prepend formSep">
                        <input type="file" name="puc" id="puc">
                        <input type="button" id="upload_puc" onclick="uploadFilesPUC();" value="Upload" class="btn btn-success" />
                        <?php $uploaddir = "../../customer/".$_SESSION['customerno']."/vehicleid/".$_GET['vid']."/puc.pdf"; 
                        if(file_exists($uploaddir)){
                            ?>
                        <a href="<?php echo $uploaddir; ?>" target="blank">PUC.pdf</a>
                        &nbsp;&nbsp;<a href="download.php?download_file=puc.pdf&vid=<?php echo $_GET['vid']; ?>&customerno=<?php echo $_SESSION['customerno']; ?>">Download PUC here</a>
                        <?php
                        } ?>
                            
                        <br/><br/> 
                        
                        
			<div class="input-prepend">  
                        <span id="set_success_puc" style="display:none;" class="btn btn-success">Information Saved successfully.</span>
                        <span id="set_error_puc" style="display:none;" class="btn btn-warning">Error, Please try again.</span>    
                        <span class="add-on" id="add_puc">Expiry Date </span><input id="ExpiryDate_PUC" name="ExpiryDate_PUC" type="text" class="input-small"  value="<?php echo date("d-m-Y"); ?>" required=""  />                            
                        &nbsp;&nbsp;
                        <span class="add-on" id="add_puc1">Alert by Email / SMS</span>
                        <select id="ExpiryDate_PUC_rem">
                            <option value="-1">Never Remind me</option>
                            <option value="0">Remind me on the day of Expiry</option>
                            <option value="1">Remind me 1 day prior to Expiry</option>
                            <option value="2">Remind me 2 days prior to Expiry</option>
                            <option value="7">Remind me 1 week prior to Expiry</option>                            
                            <option value="30">Remind me 1 month prior to Expiry</option>                            
                        </select>
                        &nbsp;
                        
                        <input type="button" id="set_poc"  onclick="pucset();" value="Set"  class="btn btn-success" /> 
                        <input type="hidden" id="customernno" name="customerno" value="<?php echo $_SESSION['customerno']?>" />
                        <input type="hidden" id="userid" name="userid" value="<?php echo $_SESSION['userid']?>" />
                       </div>
                        </div>
                        </div>

                        
                        <div class="input-prepend formSep ">
                            <b> Registration </b>
                        </div>	
                        <div class="control-group" style="text-align: left; margin-left: 10px;">
			<div class="input-prepend formSep">                                                
                            
                            <input type="file" name="reg" id="reg">
                        <input type="button" id="upload_reg" onclick="uploadFilesREG();" value="Upload" class="btn btn-success" />
                        <?php $uploaddir_reg = "../../customer/".$_SESSION['customerno']."/vehicleid/".$_GET['vid']."/registration.pdf"; 
                        if(file_exists($uploaddir_reg)){
                            ?>
                        &nbsp;&nbsp;<a href="download.php?download_file=registration.pdf&vid=<?php echo $_GET['vid']; ?>&customerno=<?php echo $_SESSION['customerno']; ?>">Download Registration Document here</a>
                        <?php
                        } ?>
                        <br/>  <br/>  
			<div class="input-prepend">
                            <span id="set_success_reg" style="display:none;" class="btn btn-success disabled">Information Saved successfully.</span>
                        <span id="set_error_reg" style="display:none;" class="btn btn-warning disabled">Error, Please try again.</span>   
                        <span class="add-on" id="add_reg">Expiry Date </span><input id="ExpiryDate_REG" name="ExpiryDate_REG" type="text" class="input-small"  value="<?php echo date("d-m-Y"); ?>" required=""  />                            
                        &nbsp;&nbsp;
                        <span class="add-on" id="add_reg1">Alert by Email / SMS</span>
                        <select id="ExpiryDate_REG_rem">
                            <option value="-1">Never Remind me</option>
                            <option value="0">Remind me on the day of Expiry</option>
                            <option value="1">Remind me 1 day prior to Expiry</option>
                            <option value="2">Remind me 2 days prior to Expiry</option>
                            <option value="7">Remind me 1 week prior to Expiry</option> 
                            <option value="30">Remind me 1 month prior to Expiry</option>  
                        </select>
                        &nbsp;
                        <input type="button" id="set_regi" onclick="regset();" value="Set" class="btn btn-success" />                                                
                       </div>
                        </div>
                        </div>	                            
                            
                                <div class="input-prepend formSep ">
                                  <b> Insurance </b>
                                </div>	

                        <div class="control-group" style="text-align: left; margin-left: 10px;">
			<div class="input-prepend formSep">
                                                                        
                            <input type="file" name="insurance" id="insurance">
                        <input type="button" id="upload_ins" onclick="uploadFilesINS();" value="Upload" class="btn btn-success" />
                        <?php $uploaddir_ins = "../../customer/".$_SESSION['customerno']."/vehicleid/".$_GET['vid']."/insurance.pdf"; 
                        if(file_exists($uploaddir_ins)){
                            ?>
                        &nbsp;&nbsp;<a href="download.php?download_file=insurance.pdf&vid=<?php echo $_GET['vid']; ?>&customerno=<?php echo $_SESSION['customerno']; ?>">Download Insurance Document here</a>
                        <?php
                        } ?>

                        <br/>  <br/>  
			<div class="input-prepend"> 
                            <span id="set_success_ins" style="display:none;" class="btn btn-success disabled">Information Saved successfully.</span>
                        <span id="set_error_ins" style="display:none;" class="btn btn-warning disabled">Error, Please try again.</span>  
                        <span class="add-on" id="add_ins">Expiry Date </span><input id="ExpiryDate_INS" name="ExpiryDate_INS" type="text" class="input-small"  value="<?php echo date("d-m-Y"); ?>" required=""  />                            
                        &nbsp;&nbsp;
                        <span class="add-on" id="add_ins1">Alert by Email / SMS</span>
                        <select id="ExpiryDate_INS_rem">
                            <option value="-1">Never Remind me</option>
                            <option value="0">Remind me on the day of Expiry</option>
                            <option value="1">Remind me 1 day prior to Expiry</option>
                            <option value="2">Remind me 2 days prior to Expiry</option>
                            <option value="7">Remind me 1 week prior to Expiry</option>
                            <option value="30">Remind me 1 month prior to Expiry</option>  
                        </select>
                        &nbsp;
                        <input type="button" id="set_INS" onclick="insset();" value="Set" class="btn btn-success" />                                                
                       </div>
                        </div>
                        </div>                            
                                                    
                                <div class="input-prepend formSep ">
                                    <b> Others </b>
                                </div>	
                            
                        <div class="control-group" style="text-align: left; margin-left: 10px;">
			<div class="input-prepend formSep">
                            
                        <span class="add-on">Name</span><input type="text" name="other" id="other"> <input type="file" name="other_file" id="other_file">
                        <input type="button" id="upload_other" onclick="uploadFilesOTHER();" value="Upload" class="btn btn-success" />
                        <?php 
                        $filename = get_uploaded_filename($_GET['vid']);
                        $uploaddir_other = "../../customer/".$_SESSION['customerno']."/vehicleid/".$_GET['vid']."/".$filename->other1.".pdf"; 
                        if(file_exists($uploaddir_other)){
                            ?>
                        &nbsp;&nbsp;<a href="download.php?download_file=<?php echo $filename->other1; ?>.pdf&vid=<?php echo $_GET['vid']; ?>&customerno=<?php echo $_SESSION['customerno']; ?>">Download <?php echo $filename->other1; ?> Document here</a>
                        <?php
                        } ?>
                            
                        <br/> <br/>   
			<div class="input-prepend"> 
                            <span id="set_success_oth1" style="display:none;" class="btn btn-success disabled">Information Saved successfully.</span>
                        <span id="set_error_oth1" style="display:none;" class="btn btn-warning disabled">Error, Please try again.</span>  
                        <span class="add-on" id="add_oth1">Expiry Date </span><input id="ExpiryDate_OTH1" name="ExpiryDate_OTH1" type="text" class="input-small"  value="<?php echo date("d-m-Y"); ?>" required=""  />                            
                        &nbsp;&nbsp;
                        <span class="add-on" id="add_oth11">Alert by Email / SMS</span>
                        <select id="ExpiryDate_OTH1_rem">
                            <option value="-1">Never Remind me</option>
                            <option value="0">Remind me on the day of Expiry</option>
                            <option value="1">Remind me 1 day prior to Expiry</option>
                            <option value="2">Remind me 2 days prior to Expiry</option>
                            <option value="7">Remind me 1 week prior to Expiry</option>
                            <option value="30">Remind me 1 month prior to Expiry</option>  
                        </select>
                        &nbsp;
                        <input type="button" id="set_OTH1" onclick="oth1set();" value="Set" class="btn btn-success" />                                                
                       </div>
                        </div>
                        </div>                            
                                <div class="input-prepend formSep ">
                                   <b> Others </b>
                                </div>	
                            
                        <div class="control-group" style="text-align: left; margin-left: 10px;">
			<div class="input-prepend formSep">
                                                                        
                        <span class="add-on">Name</span><input type="text" name="other1" id="other1"> <input type="file" name="other1_file" id="other1_file">
                        <input type="button" id="upload_other1" onclick="uploadFilesOTHER1();" value="Upload" class="btn btn-success" />
                        <?php $uploaddir_other = "../../customer/".$_SESSION['customerno']."/vehicleid/".$_GET['vid']."/".$filename->other2.".pdf"; 
                        if(file_exists($uploaddir_other)){
                            ?>
                        &nbsp;&nbsp;<a href="download.php?download_file=<?php echo $filename->other2; ?>.pdf&vid=<?php echo $_GET['vid']; ?>&customerno=<?php echo $_SESSION['customerno']; ?>">Download <?php echo $filename->other2; ?> Document here</a>
                        <?php
                        } ?>
                            
                        <br/>   <br/> 
			<div class="input-prepend"> 
                            <span id="set_success_oth2" style="display:none;" class="btn btn-success disabled">Information Saved successfully.</span>
                        <span id="set_error_oth2" style="display:none;" class="btn btn-warning disabled">Error, Please try again.</span>  
                        <span class="add-on" id="add_oth2">Expiry Date </span><input id="ExpiryDate_OTH2" name="ExpiryDate_OTH2" type="text" class="input-small"  value="<?php echo date("d-m-Y"); ?>" required=""  />                            
                        &nbsp;&nbsp;
                        <span class="add-on" id="add_oth22">Alert by Email / SMS</span>
                        <select id="ExpiryDate_OTH2_rem">
                            <option value="-1">Never Remind me</option>
                            <option value="0">Remind me on the day of Expiry</option>
                            <option value="1">Remind me 1 day prior to Expiry</option>
                            <option value="2">Remind me 2 days prior to Expiry</option>
                            <option value="7">Remind me 1 week prior to Expiry</option>
                            <option value="30">Remind me 1 month prior to Expiry</option>  
                        </select>
                        &nbsp;
                        <input type="button" id="set_OTH2" onclick="oth2set();" value="Set" class="btn btn-success" />                                                
                        </div>
                        </div>
                        </div>
                                <div class="input-prepend formSep ">
                                   <b> Others </b>
                                </div>	
                            
                        <div class="control-group" style="text-align: left; margin-left: 10px;">
			<div class="input-prepend formSep">
                                                                        
                        <span class="add-on">Name</span><input type="text" name="other2" id="other2"> <input type="file" name="other2_file" id="other2_file">
                        <input type="button" id="upload_other2" onclick="uploadFilesOTHER2();" value="Upload" class="btn btn-success" />
                        <?php $uploaddir_other = "../../customer/".$_SESSION['customerno']."/vehicleid/".$_GET['vid']."/".$filename->other3.".pdf"; 
                        if(file_exists($uploaddir_other)){
                            ?>
                        &nbsp;&nbsp;<a href="download.php?download_file=<?php echo $filename->other3; ?>.pdf&vid=<?php echo $_GET['vid']; ?>&customerno=<?php echo $_SESSION['customerno']; ?>">Download <?php echo $filename->other3; ?> Document here</a>
                        <?php
                        } ?>
                            
                        <br/>    <br/>
			<div class="input-prepend">
                            <span id="set_success_oth3" style="display:none;" class="btn btn-success disabled">Information Saved successfully.</span>
                        <span id="set_error_oth3" style="display:none;" class="btn btn-warning disabled">Error, Please try again.</span>  
                        <span class="add-on" id="add_oth3">Expiry Date </span><input id="ExpiryDate_OTH3" name="ExpiryDate_OTH3" type="text" class="input-small"  value="<?php echo date("d-m-Y"); ?>" required=""  />                            
                        &nbsp;&nbsp;
                        <span class="add-on" id="add_oth33">Alert by Email / SMS</span>
                        <select id="ExpiryDate_OTH3_rem">
                            <option value="-1">Never Remind me</option>
                            <option value="0">Remind me on the day of Expiry</option>
                            <option value="1">Remind me 1 day prior to Expiry</option>
                            <option value="2">Remind me 2 days prior to Expiry</option>
                            <option value="7">Remind me 1 week prior to Expiry</option> 
                            <option value="30">Remind me 1 month prior to Expiry</option>  
                        </select>
                        &nbsp;
                        <input type="button" id="set_OTH3" onclick="oth3set();" value="Set" class="btn btn-success" />                                                
                       </div>
                        </div>
                        </div>                            
                                                
<!--                        <input type="button" onclick="uploadFiles();" value="Save" class="btn btn-success" />-->
			
			
                        </form>
                    </fieldset>
                </div>
    <div class="tab-pane" id="tab_l8"style="overflow-y: scroll;height: 295px;">
      <fieldset>
      <span id="geo_success" style="display:none;">Geo Tag added successfully.</span>
      <form method="POST" id="geo_form">
        <div class="control-group">
          <div class="input-prepend formSep "> <span class="add-on">Checkpoint</span>
            <select id="chkid" name="chkid" onchange="addchk()">
              <option value="-1">Select Checkpoint</option>
              <?php
						$checkpoints = getchks();
						if(isset($checkpoints))
						{
							foreach ($checkpoints as $checkpoint)
							{
								echo "<option value='$checkpoint->checkpointid'>$checkpoint->cname</option>";
							}
						}
						?>
            </select>
            <input type="button" value="Add All Checkpoints" class="btn  btn-primary" onclick="addallchk()">
          </div>
          <div class="input-prepend formSep" style="height: 25px;" >
            <div id="checkpoint_list"> </div>
          </div>
          <br>
          <div class="input-prepend formSep " > <span class="add-on">Fence </span>
            <select id="fenceid" name="fenceid" onchange="addfence()">
              <option value="-1">Select Fence</option>
              <?php
								$fences = getfences();
								if(isset($fences))
								{
								foreach ($fences as $fence)
								{
								echo "<option value='$fence->fenceid'>$fence->fencename</option>";
								}
								}
								?>
            </select>
            <input type="button" value="Add All Fences" class="btn  btn-primary" onclick="addallfence()">
          </div>
          <div class="input-prepend formSep" style="height: 25px;" >
            <div id="fence_list"> </div>
          </div>
          <input type="button" onclick="addgeo();" id="geo_save" value="Save" class="btn btn-success">
        </div>
      </form>
      </fieldset>
    </div>
  </div>
</div>
<div class="modal hide" id="addtax">
  <form class="form-horizontal" id="add_tax">
    <fieldset>
    <div class="modal-header">
      <button class="close" data-dismiss="modal">×</button>
      <h4 style="color:#0679c0">Add Tax</h4>
    </div>
    <div class="modal-body">
      <div  style="overflow-y:scroll; height:400px;">
	   <span id="amount" name="amount" style="display: none;">Please Check The Amount</span> 
           <span id="from_error" name="from_error" style="display: none;">Please Select Start Date</span> 
           <span id="to_error" name="to_error" style="display: none;">Please Select End Date</span> 
           <span id="tax_datediff_error" name="tax_year_error" style="display: none;">To Date cannot be less than From Date</span> 
           <span id="tax_year_error" name="tax_year_error" style="display: none;">From Date & To Date must not be in the range preceeding the manufacturing date of the vehicle</span> 
           <span id="tax_amount_error" name="edit_amount_error" style="display: none;">Amount must be numeric must not exceed 7 digits</span> 
           <span id="tax_amount_comp_error" name="edit_amount_comp_error" style="display: none;">Please Enter Amount</span>            
           <span id="tax_type_error" name="tax_type_error" style="display: none;">Please Select Tax Type</span>                       
           <span id="registrationno_comp_error" name="registrationno_comp_error" style="display: none;">Please Enter Registration No.</span>                       
           <span id="registrationno_error" name="registrationno_error" style="display: none;">Registration No. must be caps alphabet. It must be maximum 14 Characters.</span>
        <fieldset>
            <?php 
		function getcurrentdate()
			{
				$currentdate = strtotime(date("Y-m-d H:i:s")); 
				$currentdate = substr($currentdate, '0',11);
				return $currentdate;
			}
		$currentdate = getcurrentdate();?>            
            
        <div class="control-group">
          <div class="input-prepend "> <span class="add-on" style="color:#000000">Type</span>
            <select name="tax_type" id="tax_type">
              <option value="">Select Tax Type</option>
              <option value="1">Road Tax</option>
              <option value="2">Registration Tax</option>
            </select>
          </div>
        </div>
        <div class="control-group">
          <div class="input-prepend "> <span class="add-on" id="reg_name" style="color:#000000; display: none;">Registration No.</span>
            <input type="text" name="registrationno" id="registrationno" style="display: none;" placeholder="registration no." maxlength="14" OnKeyPress="return nospecialchars(event)">
          </div>
        </div>
            
    <table class="table table-bordered table-striped">
        <th colspan="2">Tax Details</th>
        <tr>
        <td width="50%">From Date</td>
        <td><input id="from_date" name="from_date" type="text" value="<?php echo date('d-m-Y',$currentdate);?>" required/></td>
        </tr>                  
        <tr>
        <td width="50%">To Date</td>
        <td><input id="to_date" name="to_date" type="text" value="<?php echo date('d-m-Y',$currentdate);?>" required/></td>
        </tr>                          
        <tr>
        <td width="50%">Amount</td>
        <td><input type="text" name="tax_amount" id="tax_amount" maxlength="10" ></td>
        </tr>                                  
        </table>
            
        <div class="control-group">
          <div class="input-prepend ">
            <input type="button" onclick="addtax();" value="Save" class="btn btn-success">
          </div>
        </div>
        </fieldset>
      </div>
    </div>
    </fieldset>
  </form>
</div>

<div class="modal hide" id="addbattery">
<form class="form-horizontal" id="getbattery">
<fieldset>
    <div class="modal-header">
        <button class="close" data-dismiss="modal">×</button>
        <h4 style="color:#0679c0">Battery Replacement History</h4>
    </div>
    <div class="modal-body">
        <?php
            $currentdate = getcurrentdate();
            $today = date('d-m-Y',$currentdate);
        ?>        
            <span id="mr_error" style="display: none;">Please Enter Meter Reading</span>                    
            <span id="dn_error" style="display: none;">Please Select Dealer</span>                                
            <span id="notes_error" style="display: none;">Please Enter Notes</span>                                            

            <span id="qa_error" style="display: none;">Please Enter Quotation Amount</span>                                                                        
            
            <span id="in_error" style="display: none;">Please Enter Invoice No</span>                                                            
            <span id="ia_error" style="display: none;">Please Enter Invoice Amount</span>                                                                        
            <?php if($_SESSION['use_hierarchy'] == '1') { ?>
            <span id="ofas_error" style="display: none;">Please Enter OFAS Number</span>                                                                        
            <?php } else{ ?>
            <span id="ofas_error" style="display: none;">Please Enter Transaction Reference Number</span>                                                                        
            <?php } ?>
            <span id="ia_q_error" style="display: none;">Invoice Amount cannot be greater than Quotation Amount</span>                                                                                    
            <span id="datediff_error" style="display: none;">Vehicle Out Date cannot be less than Vehicle In Date</span>
            
            <div  style="overflow-y:scroll; height:320px;">            
            <fieldset>
            
                <table class="table table-bordered table-striped">
                    <th colspan="2">Transaction Details</th>
                    <tr>
                        <td width="50%">Meter Reading</td>
                        <td><input type="text" name="batt_meter_reading" id="batt_meter_reading" placeholder="e.g. 12586" maxlength="10" ></td>
                    </tr>                                          
                    <tr>
                        <td>Dealer name </td>
                        <td>
                            <select id="batt_dealerid" name="batt_dealerid">
                            <option value="-1">Select Dealer</option>
                            <?php
                            $dealers = getdealersbytype(1,$_SESSION['roleid'],$_SESSION['heirarchy_id']);
                            if(isset($dealers))
                            {
                                foreach ($dealers as $dealer)
                                {
                                    echo "<option value='$dealer->dealerid'>$dealer->name</option>";
                                }
                            }
                            ?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td>Notes</td>
                        <td><input type="text" name="note_batt" id="note_batt" placeholder="e.g. 12586" maxlength="30" onkeypress="return nospecialchars(event)"></td>
                    </tr>                        
                    <tr>
                        <td>Vehicle In Date</td>
                        <td><input id="batt_vehicle_in_date" name="batt_vehicle_in_date" type="text" value="<?php echo date('d-m-Y',$currentdate);?>" required/></td>
                    </tr>
                    <tr>
                        <td>Vehicle Out Date</td>
                        <td><input id="batt_vehicle_out_date" name="batt_vehicle_out_date" type="text" value="<?php echo date('d-m-Y',$currentdate);?>" required/></td>
                    </tr>                        
                </table>
                
                <table class="table table-bordered table-striped">
                    <th colspan="2">Quotation Details</th>                            
                    <tr>
                        <td width="50%">Quotation Amount (INR)</td>
                        <td><input type="text" name="batt_amount_quote" id="batt_amount_quote" placeholder="e.g. 125" maxlength="10" ></td>
                    </tr>
                    <tr>
                        <td>Quotation File</td>
                        <td><input type="file" title="Browse File" id="batt_file_for_quote" name="batt_file_for_quote" class="file-inputs"></td>                              
                    </tr>               
                    <tr>
                        <td>Quotation Submission Date</td>
                        <td><input id="batt_submission_date" name="batt_submission_date" type="text" value="<?php echo date('d-m-Y',$currentdate);?>" required/></td>
                    </tr>                    
                    <tr>
                        <td>Quotation Approval Date</td>
                        <td><input id="batt_approval_date" name="batt_approval_date" type="text" value="<?php echo date('d-m-Y',$currentdate);?>" required/></td>
                    </tr>                                        
                </table>
                
                <table class="table table-bordered table-striped">
                    <th colspan="2">Invoice Details</th>                            
                    <tr>
                        <td width="50%">Invoice Generation Date</td>
                        <td><input id="batt_invoice_date" name="batt_invoice_date" type="text" value="<?php echo date('d-m-Y',$currentdate);?>" required/></td>
                    </tr>
                    <tr>
                        <td>Invoice Number</td>
                        <td><input type="text" name="batt_invoice_no" id="batt_invoice_no" placeholder="e.g. 12586" maxlength="50" onkeypress="return nospecialchars(event)"></td>
                    </tr>
                    <tr>
                        <td>Invoice Amount (INR)</td>
                        <td><input type="text" name="batt_amount_invoice" id="batt_amount_invoice" placeholder="e.g. 125" maxlength="10" size="8" ></td>
                    </tr>                            
                    <tr id="invoice_file">
                         <td>Invoice File</td>
                        <td><input type="file" title="Browse File" id="batt_file_for_invoice" name="batt_file_for_invoice" class="file-inputs">                            </td>  
                    </tr>
                    <tr>
                        <td>Payment Submission Date</td>
                        <td><input id="batt_payment_submission_date" name="batt_payment_submission_date" type="text" value="<?php echo date('d-m-Y',$currentdate);?>" required/></td>
                    </tr>                                        
                    <tr>
                        <td>Payment Approval Date</td>
                        <td><input id="batt_payment_approval_date" name="batt_payment_approval_date" type="text" value="<?php echo date('d-m-Y',$currentdate);?>" required/></td>
                    </tr>                                                            
                    <tr>
                        <?php if($_SESSION['use_hierarchy'] == '1') { ?>
                        <td>OFAS Number</td>
                        <?php } else { ?>
                        <td>Transaction Reference Number</td>
                        <?php } ?>
                        <td><input type="text" name="batt_ofasnumber" id="batt_ofasnumber" placeholder="e.g. 12586" maxlength="20" onkeypress="return nospecialchars(event)"></td>
                    </tr>
                </table>
                </div>                                
            <br/>
            <div class="control-group">
            <div class="input-prepend ">
                  <input type="button" onclick="addbattery_history();" value="Save as History" class="btn btn-success">
            </div>
            </div>
        </div>	
</fieldset>
</form>
</div>

<div class="modal hide" id="addaccessory">
<form class="form-horizontal" id="getaccessory">
<fieldset>
    <div class="modal-header">
        <button class="close" data-dismiss="modal">×</button>
        <h4 style="color:#0679c0">Accessory History</h4>
    </div>
    <div class="modal-body">
        <?php
            $currentdate = getcurrentdate();
            $today = date('d-m-Y',$currentdate);
        ?>        
            <span id="mr_error" style="display: none;">Please Enter Meter Reading</span>                    
            <span id="dn_error" style="display: none;">Please Select Dealer</span>                                
            <span id="notes_error" style="display: none;">Please Enter Notes</span>                                            
            <span id="vid_error" style="display: none;">Please Enter Vehicle In Date</span>            
            <span id="vod_error" style="display: none;">Please Enter Vehicle Out Date</span>                        

            <span id="qa_error" style="display: none;">Please Enter Quotation Amount</span>                                                                        
            <span id="max_perm_amount_error" style="display: none;">Cost cannot exceed maximum permissible amount</span>                                                                        
            <span id="quote_exceed_error" style="display: none;">Quotation cannot exceed INR 22500/-</span>                                                                                    
            
            <span id="id_error" style="display: none;">Please Enter Invoice Generation Date</span>                                                
            <span id="in_error" style="display: none;">Please Enter Invoice No</span>                                                            
            <span id="ia_error" style="display: none;">Please Enter Invoice Amount</span>                                                                        
            <?php if($_SESSION['use_hierarchy'] == '1') { ?>
            <span id="ofas_error" style="display: none;">Please Enter OFAS Number</span>                                                                        
            <?php } else{ ?>
            <span id="ofas_error" style="display: none;">Please Enter Transaction Reference Number</span>                                                                        
            <?php } ?>
            <span id="ia_q_error" style="display: none;">Invoice Amount cannot be greater than Quotation Amount</span>                                                                                    
            <span id="datediff_error" style="display: none;">Vehicle Out Date cannot be less than Vehicle In Date</span>
            
            <div  style="overflow-y:scroll; height:320px;">            
            <fieldset>
            
                <table class="table table-bordered table-striped">
                    <th colspan="2">Transaction Details</th>
                    <tr>
                        <td width="50%">Meter Reading</td>
                        <td><input type="text" name="accessory_meter_reading" id="accessory_meter_reading" placeholder="e.g. 12586" maxlength="10" ></td>
                    </tr>                                          
                    <tr>
                        <td>Dealer name </td>
                        <td>
                            <select id="accessory_dealerid" name="accessory_dealerid">
                            <option value="-1">Select Dealer</option>
                            <?php
                            $dealers = getdealersbytype(1,$_SESSION['roleid'],$_SESSION['heirarchy_id']);
                            if(isset($dealers))
                            {
                                foreach ($dealers as $dealer)
                                {
                                    echo "<option value='$dealer->dealerid'>$dealer->name</option>";
                                }
                            }
                            ?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td>Notes</td>
                        <td><input type="text" name="note_accessory" id="note_accessory" placeholder="e.g. 12586" maxlength="30" onkeypress="return nospecialchars(event)"></td>
                    </tr>                        
                    <tr>
                        <td>Vehicle In Date</td>
                        <td><input id="accessory_vehicle_in_date" name="accessory_vehicle_in_date" type="text" value="<?php echo date('d-m-Y',$currentdate);?>" required/></td>
                    </tr>
                    <tr>
                        <td>Vehicle Out Date</td>
                        <td><input id="accessory_vehicle_out_date" name="accessory_vehicle_out_date" type="text" value="<?php echo date('d-m-Y',$currentdate);?>" required/></td>
                    </tr>                        
                </table>
                
                <table class="table table-bordered table-striped">
                    <th colspan="2">Quotation Details</th>                            
                    <tr>
                        <td width="50%">Quotation Amount (INR)</td>
                        <td><input type="text" name="accessory_amount_quote" id="accessory_amount_quote" placeholder="e.g. 125" maxlength="10" ></td>
                    </tr>
                    <tr>
                        <td>Quotation File</td>
                        <td><input type="file" title="Browse File" id="accessory_file_for_quote" name="accessory_file_for_quote" class="file-inputs"></td>                              
                    </tr>               
                    <tr>
                        <td>Quotation Submission Date</td>
                        <td><input id="accessory_submission_date" name="accessory_submission_date" type="text" value="<?php echo date('d-m-Y',$currentdate);?>" required/></td>
                    </tr>                    
                    <tr>
                        <td>Quotation Approval Date</td>
                        <td><input id="accessory_approval_date" name="accessory_approval_date" type="text" value="<?php echo date('d-m-Y',$currentdate);?>" required/></td>
                    </tr>                                        
                </table>
                
                <table class="table table-bordered table-striped">
                    <th colspan="2">Invoice Details</th>                            
                    <tr>
                        <td width="50%">Invoice Generation Date</td>
                        <td><input id="accessory_invoice_date" name="accessory_invoice_date" type="text" value="<?php echo date('d-m-Y',$currentdate);?>" required/></td>
                    </tr>
                    <tr>
                        <td>Invoice Number</td>
                        <td><input type="text" name="accessory_invoice_no" id="accessory_invoice_no" placeholder="e.g. 12586" maxlength="50" onkeypress="return nospecialchars(event)"></td>
                    </tr>
                    <tr>
                        <td>Invoice Amount (INR)</td>
                        <td><input type="text" name="accessory_amount_invoice" id="accessory_amount_invoice" placeholder="e.g. 125" maxlength="10" size="8" ></td>
                    </tr>                            
                    <tr id="invoice_file">
                         <td>Invoice File</td>
                        <td><input type="file" title="Browse File" id="accessory_file_for_invoice" name="accessory_file_for_invoice" class="file-inputs">                            </td>  
                    </tr>
                    <tr>
                        <td>Payment Submission Date</td>
                        <td><input id="accessory_payment_submission_date" name="accessory_payment_submission_date" type="text" value="<?php echo date('d-m-Y',$currentdate);?>" required/></td>
                    </tr>                                        
                    <tr>
                        <td>Payment Approval Date</td>
                        <td><input id="accessory_payment_approval_date" name="accessory_payment_approval_date" type="text" value="<?php echo date('d-m-Y',$currentdate);?>" required/></td>
                    </tr>                                                            
                    <tr>
                        <?php if($_SESSION['use_hierarchy'] == '1') { ?>
                        <td>OFAS Number</td>
                        <?php } else { ?>
                        <td>Transaction Reference Number</td>
                        <?php } ?>
                        <td><input type="text" name="accessory_ofasnumber" id="accessory_ofasnumber" placeholder="e.g. 12586" maxlength="20" onkeypress="return nospecialchars(event)"></td>
                    </tr>
                </table>
                
                    <table class="table table-bordered table-striped">
                        <th colspan="4">Accessory Details</th>
                        <tr>
                        <td width="15%"><b>Sr. No</b></td>
                        <td width="35%"><b>Accessory</b></td>
                        <td width="25%"><b>Cost</b></td>
                        <td width="25%"><b>Max. Perm. Amount</b></td>
                        </tr>                  
                        <?php
                        for($i=1;$i<8;$i++)
                        {
                        ?>
                            <tr>
                            <td><?php echo($i); ?></td>
                            <td>
                            <select id="accessory_select_<?php echo($i); ?>" name="accessory_select_<?php echo($i); ?>" onChange="sel_acc(<?php echo($i); ?>);">
                                <option value="-1">Select Accessory</option>
                                <?php
                                $accessories = getaccessories();
                                if(isset($accessories))
                                {
                                foreach ($accessories as $accessory)
                                {
                                echo "<option value='$accessory->id'>$accessory->name</option>";
                                }
                                }
                                ?>
                            </select>                            
                            </td>
                            <td><input type="text" name="amount<?php echo($i); ?>" id="amount<?php echo($i); ?>" placeholder="e.g. 125" maxlength="10" size="8" value="0" ></td>
                            <td><div id="max_amount_<?php echo($i); ?>"></div>
                            <input type="hidden" id="max_amount_hid_<?php echo($i); ?>" name="max_amount_hid_<?php echo($i); ?>" value="0">
                            </td>
                            </tr>                  
                        <?php
                        }
                        ?>
                    </table>
                
                </div>                                
            <br/>
            <div class="control-group">
            <div class="input-prepend ">
                  <input type="button" onclick="addaccessory_history();" value="Save as History" class="btn btn-success">
            </div>
            </div>
        </div>	
</fieldset>
</form>
</div>

<div class="modal hide" id="addtyre">
<form class="form-horizontal" id="gettyre">
<fieldset>
    <div class="modal-header">
        <button class="close" data-dismiss="modal">×</button>
        <h4 style="color:#0679c0">Tyre Replacement History</h4>
    </div>
    <div class="modal-body">
        <?php
            $currentdate = getcurrentdate();
            $today = date('d-m-Y',$currentdate);
        ?>        
            <span id="mr_error" style="display: none;">Please Enter Meter Reading</span>                    
            <span id="dn_error" style="display: none;">Please Select Dealer</span>                                
            <span id="notes_error" style="display: none;">Please Enter Notes</span>                                            
            <span id="vid_error" style="display: none;">Please Enter Vehicle In Date</span>            
            <span id="vod_error" style="display: none;">Please Enter Vehicle Out Date</span>                        

            <span id="qa_error" style="display: none;">Please Enter Quotation Amount</span>                                                                        
            <span id="tyre_type_error" style="display: none;">Please Select Tyre Type</span>                                                                                    
            
            <span id="id_error" style="display: none;">Please Enter Invoice Generation Date</span>                                                
            <span id="in_error" style="display: none;">Please Enter Invoice No</span>                                                            
            <span id="ia_error" style="display: none;">Please Enter Invoice Amount</span>                                                                        
            <?php if($_SESSION['use_hierarchy'] == '1') { ?>
            <span id="ofas_error" style="display: none;">Please Enter OFAS Number</span>                                                                        
            <?php } else{ ?>
            <span id="ofas_error" style="display: none;">Please Enter Transaction Reference Number</span>                                                                        
            <?php } ?>
            
            <span id="ia_q_error" style="display: none;">Invoice Amount cannot be greater than Quotation Amount</span>                                                                                    
            <span id="datediff_error" style="display: none;">Vehicle Out Date cannot be less than Vehicle In Date</span>
            
            <div  style="overflow-y:scroll; height:320px;">            
            <fieldset>
            
                <table class="table table-bordered table-striped">
                    <th colspan="2">Transaction Details</th>
                    <tr>
                        <td width="50%">Meter Reading</td>
                        <td><input type="text" name="tyre_meter_reading" id="tyre_meter_reading" placeholder="e.g. 12586" maxlength="10" ></td>
                    </tr>                                          
                    <tr>
                        <td>Dealer name </td>
                        <td>
                            <select id="tyre_dealerid" name="tyre_dealerid">
                            <option value="-1">Select Dealer</option>
                            <?php
                            $dealers = getdealersbytype(1,$_SESSION['roleid'],$_SESSION['heirarchy_id']);
                            if(isset($dealers))
                            {
                                foreach ($dealers as $dealer)
                                {
                                    echo "<option value='$dealer->dealerid'>$dealer->name</option>";
                                }
                            }
                            ?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td>Notes</td>
                        <td><input type="text" name="note_tyre" id="note_tyre" placeholder="e.g. 12586" maxlength="30" onkeypress="return nospecialchars(event)"></td>
                    </tr>                        
                    <tr>
                        <td>Vehicle In Date</td>
                        <td><input id="tyre_vehicle_in_date" name="tyre_vehicle_in_date" type="text" value="<?php echo date('d-m-Y',$currentdate);?>" required/></td>
                    </tr>
                    <tr>
                        <td>Vehicle Out Date</td>
                        <td><input id="tyre_vehicle_out_date" name="tyre_vehicle_out_date" type="text" value="<?php echo date('d-m-Y',$currentdate);?>" required/></td>
                    </tr>                        
                </table>
                
                <table class="table table-bordered table-striped">
                    <th colspan="2">Quotation Details</th>                            
                    <tr>
                        <td width="50%">Quotation Amount (INR)</td>
                        <td><input type="text" name="tyre_amount_quote" id="tyre_amount_quote" placeholder="e.g. 125" maxlength="10" ></td>
                    </tr>
                    <tr>
                        <td>Quotation File</td>
                        <td><input type="file" title="Browse File" id="tyre_file_for_quote" name="tyre_file_for_quote" class="file-inputs"></td>                              
                    </tr>               
                    <tr>
                        <td>Quotation Submission Date</td>
                        <td><input id="tyre_submission_date" name="tyre_submission_date" type="text" value="<?php echo date('d-m-Y',$currentdate);?>" required/></td>
                    </tr>                    
                    <tr>
                        <td>Quotation Approval Date</td>
                        <td><input id="tyre_approval_date" name="tyre_approval_date" type="text" value="<?php echo date('d-m-Y',$currentdate);?>" required/></td>
                    </tr>                                        
                </table>
                
                <table class="table table-bordered table-striped">
                    <th colspan="2">Invoice Details</th>                            
                    <tr>
                        <td width="50%">Invoice Generation Date</td>
                        <td><input id="tyre_invoice_date" name="tyre_invoice_date" type="text" value="<?php echo date('d-m-Y',$currentdate);?>" required/></td>
                    </tr>
                    <tr>
                        <td>Invoice Number</td>
                        <td><input type="text" name="tyre_invoice_no" id="tyre_invoice_no" placeholder="e.g. 12586" maxlength="50" onkeypress="return nospecialchars(event)"></td>
                    </tr>
                    <tr>
                        <td>Invoice Amount (INR)</td>
                        <td><input type="text" name="tyre_amount_invoice" id="tyre_amount_invoice" placeholder="e.g. 125" maxlength="10" size="8" ></td>
                    </tr>                            
                    <tr id="invoice_file">
                         <td>Invoice File</td>
                        <td><input type="file" title="Browse File" id="tyre_file_for_invoice" name="tyre_file_for_invoice" class="file-inputs">                            </td>  
                    </tr>
                    <tr>
                        <td>Payment Submission Date</td>
                        <td><input id="tyre_payment_submission_date" name="tyre_payment_submission_date" type="text" value="<?php echo date('d-m-Y',$currentdate);?>" required/></td>
                    </tr>                                        
                    <tr>
                        <td>Payment Approval Date</td>
                        <td><input id="tyre_payment_approval_date" name="tyre_payment_approval_date" type="text" value="<?php echo date('d-m-Y',$currentdate);?>" required/></td>
                    </tr>                                                            
                    <tr>
                        <?php if($_SESSION['use_hierarchy'] == '1') { ?>
                        <td>OFAS Number</td>
                        <?php } else { ?>
                        <td>Transaction Reference Number</td>
                        <?php } ?>
                        <td><input type="text" name="tyre_ofasnumber" id="tyre_ofasnumber" placeholder="e.g. 12586" maxlength="20" onkeypress="return nospecialchars(event)"></td>
                    </tr>
                </table>
                
                <div id="tyre_fields" >
                    <div class="control-group tyre_field"  >
                        <div class="input-prepend">
                        <span class="add-on" style="color:#000000">Tyre type</span>
                            <select id="tyre_type" name="tyre_type">
                                    <option value="">Select type</option>
                                    <option value="1">Right front</option>
                                    <option value="2">Right Back</option>
                                    <option value="3">Left front</option>
                                    <option value="4">Left Back</option>
                                    <option value="5">Stepney</option>

                            </select><input type="button" id="add_ttype" name="add_ttype" value="+" onclick="add_items_container('#tyrelist_container','tyre_type')">
                            <div id="tyrelist_container"></div>
                        </div>
                        </div>
                </div>
                
                </div>                                
            <br/>
            <div class="control-group">
            <div class="input-prepend ">
                  <input type="button" onclick="addtyre_history();" value="Save as History" class="btn btn-success">
            </div>
            </div>
        </div>	
    </fieldset>
    </form>
</div>

<div class="modal hide" id="addrepair">
<form class="form-horizontal" id="getrepair">
<fieldset>
    <div class="modal-header">
        <button class="close" data-dismiss="modal">×</button>
        <h4 style="color:#0679c0">Repair / Service History</h4>
    </div>
    
    <div class="modal-body">
        <?php
            $currentdate = getcurrentdate();
            $today = date('d-m-Y',$currentdate);
        ?>        
            <span id="mr_error" style="display: none;">Please Enter Meter Reading</span>                    
            <span id="dn_error" style="display: none;">Please Select Dealer</span>                                
            <span id="notes_error" style="display: none;">Please Enter Notes</span>                                            
            <span id="vid_error" style="display: none;">Please Enter Vehicle In Date</span>            
            <span id="vod_error" style="display: none;">Please Enter Vehicle Out Date</span>                        

            <span id="qa_error" style="display: none;">Please Enter Quotation Amount</span>                                                                        
            
            <span id="id_error" style="display: none;">Please Enter Invoice Generation Date</span>                                                
            <span id="in_error" style="display: none;">Please Enter Invoice No</span>                                                            
            <span id="ia_error" style="display: none;">Please Enter Invoice Amount</span>                                                                        
            <?php if($_SESSION['use_hierarchy'] == '1') { ?>
            <span id="ofas_error" style="display: none;">Please Enter OFAS Number</span>                                                                        
            <?php } else{ ?>
            <span id="ofas_error" style="display: none;">Please Enter Transaction Reference Number</span>                                                                        
            <?php } ?>
            <span id="parts_tasks_error" style="display: none;">Please Select Parts / Tasks</span>                                                                        
            
            <span id="ia_q_error" style="display: none;">Invoice Amount cannot be greater than Quotation Amount</span>                                                                                    
            <span id="datediff_error" style="display: none;">Vehicle Out Date cannot be less than Vehicle In Date</span>
            
            <div  style="overflow-y:scroll; height:320px;">            
            <fieldset>
            
                <table class="table table-bordered table-striped">
                    <th colspan="2">Transaction Details</th>
                    <tr>
                        <td width="50%">Meter Reading</td>
                        <td><input type="text" name="repair_meter_reading" id="repair_meter_reading" placeholder="e.g. 12586" maxlength="10" ></td>
                    </tr>                                          
                    <tr>
                        <td>Dealer name </td>
                        <td>
                            <select id="repair_dealerid" name="repair_dealerid">
                            <option value="-1">Select Dealer</option>
                            <?php
                            $dealers = getdealersbytype(1,$_SESSION['roleid'],$_SESSION['heirarchy_id']);
                            if(isset($dealers))
                            {
                                foreach ($dealers as $dealer)
                                {
                                    echo "<option value='$dealer->dealerid'>$dealer->name</option>";
                                }
                            }
                            ?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td>Notes</td>
                        <td><input type="text" name="note_repair" id="note_repair" placeholder="e.g. 12586" maxlength="30" onkeypress="return nospecialchars(event)"></td>
                    </tr>                        
                    <tr>
                        <td>Vehicle In Date</td>
                        <td><input id="repair_vehicle_in_date" name="repair_vehicle_in_date" type="text" value="<?php echo date('d-m-Y',$currentdate);?>" required/></td>
                    </tr>
                    <tr>
                        <td>Vehicle Out Date</td>
                        <td><input id="repair_vehicle_out_date" name="repair_vehicle_out_date" type="text" value="<?php echo date('d-m-Y',$currentdate);?>" required/></td>
                    </tr>                        
                </table>
                
                <table class="table table-bordered table-striped">
                    <th colspan="2">Quotation Details</th>                            
                    <tr>
                        <td width="50%">Quotation Amount (INR)</td>
                        <td><input type="text" name="repair_amount_quote" id="repair_amount_quote" placeholder="e.g. 125" maxlength="10" ></td>
                    </tr>
                    <tr>
                        <td>Quotation File</td>
                        <td><input type="file" title="Browse File" id="repair_file_for_quote" name="repair_file_for_quote" class="file-inputs"></td>                              
                    </tr>               
                    <tr>
                        <td>Quotation Submission Date</td>
                        <td><input id="repair_submission_date" name="repair_submission_date" type="text" value="<?php echo date('d-m-Y',$currentdate);?>" required/></td>
                    </tr>                    
                    <tr>
                        <td>Quotation Approval Date</td>
                        <td><input id="repair_approval_date" name="repair_approval_date" type="text" value="<?php echo date('d-m-Y',$currentdate);?>" required/></td>
                    </tr>                                        
                </table>
                
                <table class="table table-bordered table-striped">
                    <th colspan="2">Invoice Details</th>                            
                    <tr>
                        <td width="50%">Invoice Generation Date</td>
                        <td><input id="repair_invoice_date" name="repair_invoice_date" type="text" value="<?php echo date('d-m-Y',$currentdate);?>" required/></td>
                    </tr>
                    <tr>
                        <td>Invoice Number</td>
                        <td><input type="text" name="repair_invoice_no" id="repair_invoice_no" placeholder="e.g. 12586" maxlength="50" onkeypress="return nospecialchars(event)"></td>
                    </tr>
                    <tr>
                        <td>Invoice Amount (INR)</td>
                        <td><input type="text" name="repair_amount_invoice" id="repair_amount_invoice" placeholder="e.g. 125" maxlength="10" size="8" ></td>
                    </tr>                            
                    <tr id="invoice_file">
                         <td>Invoice File</td>
                        <td><input type="file" title="Browse File" id="repair_file_for_invoice" name="repair_file_for_invoice" class="file-inputs">                            </td>  
                    </tr>
                    <tr>
                        <td>Payment Submission Date</td>
                        <td><input id="repair_payment_submission_date" name="repair_payment_submission_date" type="text" value="<?php echo date('d-m-Y',$currentdate);?>" required/></td>
                    </tr>                                        
                    <tr>
                        <td>Payment Approval Date</td>
                        <td><input id="repair_payment_approval_date" name="repair_payment_approval_date" type="text" value="<?php echo date('d-m-Y',$currentdate);?>" required/></td>
                    </tr>                                                            
                    <tr>
                        <?php if($_SESSION['use_hierarchy'] == '1') { ?>
                        <td>OFAS Number</td>
                        <?php } else { ?>
                        <td>Transaction Reference Number</td>
                        <?php } ?>
                        <td><input type="text" name="repair_ofasnumber" id="repair_ofasnumber" placeholder="e.g. 12586" maxlength="20" onkeypress="return nospecialchars(event)"></td>
                    </tr>
                </table>
                
    <div id="parts_service_category">
        
       <div class="control-group">
                <div class="input-prepend ">
                <span class="add-on" style="color:#000000">Category</span><select id="category_type" name="category_type" onchange="category_selector()">
                            <option value="-1">Select Task</option>
                            <option value="2">Repair</option>
                            <option value="3">Service</option>
                            
                    </select>
                    
                </div>
                </div>
        <div id="category_handler"></div>
		</div>
                
    <div id="parts_task" style=" display: none;">
       <div class="control-group">
                <div class="input-prepend ">
                <span class="add-on" style="color:#000000">Parts</span>
                    <select id="parts_select" name="parts_select">
                            <option value="-1">Select Parts</option>
                            <?php
                            $parts = getpart();
                            if(isset($parts))
                            {
                            foreach ($parts as $part)
                            {
                            echo "<option value='$part->id'>$part->part_name</option>";
                            }
                            }
                            ?>
                    </select><input type="button" id="add_parts" name="add_parts" value="+" onclick="add_items_container('#partlist_container','parts_select')">
                    <div id="partlist_container"></div>
                </div>
                </div>
        <div class="control-group">
                <div class="input-prepend ">
                <span class="add-on" style="color:#000000">Tasks</span><select id="task_select" name="task_select">
                            <option value="-1">Select Tasks</option>
                            <?php
                            $tasks = gettask();
                            if(isset($tasks))
                            {
                            foreach ($tasks as $task)
                            {
                            echo "<option value='$task->id'>$task->task_name</option>";
                            }
                            }
                            ?>
                    </select><input type="button" id="add_task" name="add_task" value="+" onclick="add_items_container('#tasklist_container','task_select')">
                    <div id="tasklist_container"></div>
                </div>
                </div>
                
                </div>                                
            <br/>
            <div class="control-group">
            <div class="input-prepend ">
                  <input type="button" onclick="addrepair_history();" value="Save as History" class="btn btn-success">
            </div>
            </div>
        </div>	
	</fieldset>
                </form>
</div>

<div class="modal hide" id="edittax">
  <form class="form-horizontal" id="edit_tax">
    <fieldset>
    <div class="modal-header">
      <button class="close" data-dismiss="modal">×</button>
      <h4 style="color:#0679c0">Edit Tax</h4>
    </div>
    <div class="modal-body"> 
	
	<div  style="overflow-y:scroll; height:400px;">
	
	<span id="edit_from_error" name="edit_from_error" style="display: none;">Please Select Start Date</span> <span id="edit_to_error" name="edit_to_error" style="display: none;">Please Select End Date</span> <span id="edit_date_error" name="edit_date_error" style="display: none;">From Date cannot be greater than To Date</span> <span id="edit_date_purchase_error" name="edit_date_purchase_error" style="display: none;">From Date cannot be less than Purchase Date</span> <span id="edit_tax_datediff_error" name="edit_tax_year_error" style="display: none;">To Date cannot be less than From Date</span> <span id="edit_tax_year_error" name="edit_tax_year_error" style="display: none;">From Date & To Date must not be in the range preceeding the manufacturing date of the vehicle</span> <span id="edit_amount_error" name="edit_amount_error" style="display: none;">Amount must be numeric must not exceed 7 digits</span> <span id="edit_registrationno_error" name="edit_registrationno_error" style="display: none;">Registration No. must be caps alphabet. It must be maximum 14 Characters.</span>
      <fieldset>
      <div class="control-group">
        <div class="input-prepend "> <span class="add-on" style="color:#000000">From Date</span>
          <input id="edit_from_date" name="edit_from_date" type="text" value=""/>
          <span class="add-on" style="color:#000000">To Date</span>
          <input id="edit_to_date" name="edit_to_date" type="text" value=""/>
        </div>
      </div>
      <div class="control-group">
        <div class="input-prepend "> <span class="add-on" style="color:#000000">Type</span>
          <select name="edit_tax_type" id="edit_tax_type">
            <option value="">Select Tax Type</option>
            <option value="1">Road Tax</option>
            <option value="2">Registration Tax</option>
          </select>
          <span class="add-on" style="color:#000000">Amount</span>
          <input type="text" name="edit_amount" id="edit_amount">
        </div>
      </div>
      <div class="control-group">
        <div class="input-prepend "> <span class="add-on" id="edit_reg_name" style="display: none; color:#000000">Registration No.</span>
          <input type="text" name="edit_registrationno" id="edit_registrationno" placeholder="registration no." style="display: none;" maxlength="14">
        </div>
      </div>
      <div class="control-group">
        <div class="input-prepend ">
          <input type="button" id="edit_save_tax" value="Save" class="btn btn-success" />
        </div>
      </div>
      </fieldset>
    </div>
	</div>
    </fieldset>
  </form>
</div>

<div class="modal hide" id="editbattery" style="top:41%;">
<form class="form-horizontal" id="getbattery_edit">
<fieldset>
    <div class="modal-header">
        <button class="close" data-dismiss="modal">×</button>
        <h4 style="color:#0679c0">Battery Details</h4>
    </div>
    <div class="modal-body">
        <?php
            $currentdate = getcurrentdate();
            $today = date('d-m-Y',$currentdate);
        ?>        
            <span id="mr_error" style="display: none;">Please Enter Meter Reading</span>                    
            <span id="dn_error" style="display: none;">Please Select Dealer</span>                                
            <span id="notes_error" style="display: none;">Please Enter Notes</span>                                            
            <span id="vid_error" style="display: none;">Please Enter Vehicle In Date</span>            
            <span id="vod_error" style="display: none;">Please Enter Vehicle Out Date</span>                        

            <span id="qa_error" style="display: none;">Please Enter Quotation Amount</span>                                                                        
            
            <span id="id_error" style="display: none;">Please Enter Invoice Generation Date</span>                                                
            <span id="in_error" style="display: none;">Please Enter Invoice No</span>                                                            
            <span id="ia_error" style="display: none;">Please Enter Invoice Amount</span>                                                                        
            <?php if($_SESSION['use_hierarchy'] == '1') { ?>
            <span id="ofas_error" style="display: none;">Please Enter OFAS Number</span>                                                                        
            <?php } else{ ?>
            <span id="ofas_error" style="display: none;">Please Enter Transaction Reference Number</span>                                                                        
            <?php } ?>
            
            <span id="ia_q_error" style="display: none;">Invoice Amount cannot be greater than Quotation Amount</span>                                                                                    
            <span id="datediff_error" style="display: none;">Vehicle Out Date cannot be less than Vehicle In Date</span>
            
            <div  style="overflow-y:scroll; height:320px;">            
            <fieldset>
            
                <table class="table table-bordered table-striped">
                    <th colspan="2">Transaction Details</th>
                    <tr>
                        <td width="50%">Meter Reading</td>
                        <td><input type="text" name="batt_meter_reading" id="batt_meter_reading" placeholder="e.g. 12586" maxlength="10" ></td>
                    </tr>                                          
                    <tr>
                        <td>Dealer name </td>
                        <td>
                            <select id="batt_dealerid" name="batt_dealerid">
                            <option value="-1">Select Dealer</option>
                            <?php
                            $dealers = getdealersbytype(1,$_SESSION['roleid'],$_SESSION['heirarchy_id']);
                            if(isset($dealers))
                            {
                                foreach ($dealers as $dealer)
                                {
                                    echo "<option value='$dealer->dealerid'>$dealer->name</option>";
                                }
                            }
                            ?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td>Notes</td>
                        <td><input type="text" name="note_batt" id="note_batt" placeholder="e.g. 12586" maxlength="30" onkeypress="return nospecialchars(event)"></td>
                    </tr>                        
                    <tr>
                        <td>Vehicle In Date</td>
                        <td><input id="batt_vehicle_in_date" name="batt_vehicle_in_date" type="text" value="<?php echo date('d-m-Y',$currentdate);?>" required/></td>
                    </tr>
                    <tr>
                        <td>Vehicle Out Date</td>
                        <td><input id="batt_vehicle_out_date" name="batt_vehicle_out_date" type="text" value="<?php echo date('d-m-Y',$currentdate);?>" required/></td>
                    </tr>                        
                </table>
                
                <table class="table table-bordered table-striped">
                    <th colspan="2">Quotation Details</th>                            
                    <tr>
                        <td width="50%">Quotation Amount (INR)</td>
                        <td><input type="text" name="batt_amount_quote" id="batt_amount_quote" placeholder="e.g. 125" maxlength="10" ></td>
                    </tr>
                    <tr>
                        <td>Quotation File</td>
                        <td>
                        <div class="input-prepend" id="battery_quotefile_view"></td>                              
                    </tr>               
                    <tr>
                        <td>Quotation Submission Date</td>
                        <td><input id="batt_submission_date" name="batt_submission_date" type="text" value="<?php echo date('d-m-Y',$currentdate);?>" required/></td>
                    </tr>                    
                    <tr>
                        <td>Quotation Approval Date</td>
                        <td><input id="batt_approval_date" name="batt_approval_date" type="text" value="<?php echo date('d-m-Y',$currentdate);?>" required/></td>
                    </tr>                                        
                </table>
                
                <table class="table table-bordered table-striped">
                    <th colspan="2">Invoice Details</th>                            
                    <tr>
                        <td width="50%">Invoice Generation Date</td>
                        <td><input id="batt_invoice_date" name="batt_invoice_date" type="text" value="<?php echo date('d-m-Y',$currentdate);?>" required/></td>
                    </tr>
                    <tr>
                        <td>Invoice Number</td>
                        <td><input type="text" name="batt_invoice_no" id="batt_invoice_no" placeholder="e.g. 12586" maxlength="50" onkeypress="return nospecialchars(event)"></td>
                    </tr>
                    <tr>
                        <td>Invoice Amount (INR)</td>
                        <td><input type="text" name="batt_amount_invoice" id="batt_amount_invoice" placeholder="e.g. 125" maxlength="10" size="8" ></td>
                    </tr>                            
                    <tr id="invoice_file">
                         <td>Invoice File</td>
                        <td>
                        <div class="input-prepend" id="battery_invoicefile_view"></td>                                                          
                        </td>  
                        
                    </tr>
                    <tr>
                        <td>Payment Submission Date</td>
                        <td><input id="batt_payment_submission_date" name="batt_payment_submission_date" type="text" value="<?php echo date('d-m-Y',$currentdate);?>" required/></td>
                    </tr>                                        
                    <tr>
                        <td>Payment Approval Date</td>
                        <td><input id="batt_payment_approval_date" name="batt_payment_approval_date" type="text" value="<?php echo date('d-m-Y',$currentdate);?>" required/></td>
                    </tr>                                                            
                    <tr>
                        <?php if($_SESSION['use_hierarchy'] == '1') { ?>
                        <td>OFAS Number</td>
                        <?php } else { ?>
                        <td>Transaction Reference Number</td>
                        <?php } ?>
                        <td><input type="text" name="batt_ofasnumber" id="batt_ofasnumber" placeholder="e.g. 12586" maxlength="20" onkeypress="return nospecialchars(event)">
                                <input type="hidden" name="maintenance_edit_id" id="maintenance_edit_id"></td>
                    </tr>
                </table>
                </div>                                
            <br/>
            <div class="control-group">
            <div class="input-prepend ">
                <input type="button" id="edit_save_battery" value="Save as History" class="btn btn-success">
            </div>
            </div>
        </div>
</fieldset>
</form>
</div>

<div class="modal hide" id="editaccessory" style="top:41%;">
<form class="form-horizontal" id="getaccessory_edit">
<fieldset>
    <div class="modal-header">
        <button class="close" data-dismiss="modal">×</button>
        <h4 style="color:#0679c0">Accessory Details</h4>
    </div>
    <div class="modal-body">
        <?php
            $currentdate = getcurrentdate();
            $today = date('d-m-Y',$currentdate);
        ?>        
            <span id="mr_error" style="display: none;">Please Enter Meter Reading</span>                    
            <span id="dn_error" style="display: none;">Please Select Dealer</span>                                
            <span id="notes_error" style="display: none;">Please Enter Notes</span>                                            
            <span id="vid_error" style="display: none;">Please Enter Vehicle In Date</span>            
            <span id="vod_error" style="display: none;">Please Enter Vehicle Out Date</span>                        

            <span id="qa_error" style="display: none;">Please Enter Quotation Amount</span>                                                                        
            
            <span id="id_error" style="display: none;">Please Enter Invoice Generation Date</span>                                                
            <span id="in_error" style="display: none;">Please Enter Invoice No</span>                                                            
            <span id="ia_error" style="display: none;">Please Enter Invoice Amount</span>                                                                        
            <?php if($_SESSION['use_hierarchy'] == '1') { ?>
            <span id="ofas_error" style="display: none;">Please Enter OFAS Number</span>                                                                        
            <?php } else{ ?>
            <span id="ofas_error" style="display: none;">Please Enter Transaction Reference Number</span>                                                                        
            <?php } ?>
            
            <span id="ia_q_error" style="display: none;">Invoice Amount cannot be greater than Quotation Amount</span>                                                                                    
            <span id="datediff_error" style="display: none;">Vehicle Out Date cannot be less than Vehicle In Date</span>
            
            <div  style="overflow-y:scroll; height:320px;">            
            <fieldset>
            
                <table class="table table-bordered table-striped">
                    <th colspan="2">Transaction Details</th>
                    <tr>
                        <td width="50%">Meter Reading</td>
                        <td><input type="text" name="accessory_meter_reading" id="accessory_meter_reading" placeholder="e.g. 12586" maxlength="10" ></td>
                    </tr>                                          
                    <tr>
                        <td>Dealer name </td>
                        <td>
                            <select id="accessory_dealerid" name="accessory_dealerid">
                            <option value="-1">Select Dealer</option>
                            <?php
                            $dealers = getdealersbytype(1,$_SESSION['roleid'],$_SESSION['heirarchy_id']);
                            if(isset($dealers))
                            {
                                foreach ($dealers as $dealer)
                                {
                                    echo "<option value='$dealer->dealerid'>$dealer->name</option>";
                                }
                            }
                            ?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td>Notes</td>
                        <td><input type="text" name="note_accessory" id="note_accessory" placeholder="e.g. 12586" maxlength="30" onkeypress="return nospecialchars(event)"></td>
                    </tr>                        
                    <tr>
                        <td>Vehicle In Date</td>
                        <td><input id="accessory_vehicle_in_date" name="accessory_vehicle_in_date" type="text" value="<?php echo date('d-m-Y',$currentdate);?>" required/></td>
                    </tr>
                    <tr>
                        <td>Vehicle Out Date</td>
                        <td><input id="accessory_vehicle_out_date" name="accessory_vehicle_out_date" type="text" value="<?php echo date('d-m-Y',$currentdate);?>" required/></td>
                    </tr>                        
                </table>
                
                <table class="table table-bordered table-striped">
                    <th colspan="2">Quotation Details</th>                            
                    <tr>
                        <td width="50%">Quotation Amount (INR)</td>
                        <td><input type="text" name="accessory_amount_quote" id="accessory_amount_quote" placeholder="e.g. 125" maxlength="10" ></td>
                    </tr>
                    <tr>
                        <td>Quotation File</td>
                        <td>
                        <div class="input-prepend" id="accessory_quotefile_view"></td>                              
                    </tr>               
                    <tr>
                        <td>Quotation Submission Date</td>
                        <td><input id="accessory_submission_date" name="accessory_submission_date" type="text" value="<?php echo date('d-m-Y',$currentdate);?>" required/></td>
                    </tr>                    
                    <tr>
                        <td>Quotation Approval Date</td>
                        <td><input id="accessory_approval_date" name="accessory_approval_date" type="text" value="<?php echo date('d-m-Y',$currentdate);?>" required/></td>
                    </tr>                                        
                </table>
                
                <table class="table table-bordered table-striped">
                    <th colspan="2">Invoice Details</th>                            
                    <tr>
                        <td width="50%">Invoice Generation Date</td>
                        <td><input id="accessory_invoice_date" name="accessory_invoice_date" type="text" value="<?php echo date('d-m-Y',$currentdate);?>" required/></td>
                    </tr>
                    <tr>
                        <td>Invoice Number</td>
                        <td><input type="text" name="accessory_invoice_no" id="accessory_invoice_no" placeholder="e.g. 12586" maxlength="50" onkeypress="return nospecialchars(event)"></td>
                    </tr>
                    <tr>
                        <td>Invoice Amount (INR)</td>
                        <td><input type="text" name="accessory_amount_invoice" id="accessory_amount_invoice" placeholder="e.g. 125" maxlength="10" size="8" ></td>
                    </tr>                            
                    <tr id="invoice_file">
                         <td>Invoice File</td>
                        <td>
                        <div class="input-prepend" id="accessory_invoicefile_view"></td>                                                          
                        </td>  
                        
                    </tr>
                    <tr>
                        <td>Payment Submission Date</td>
                        <td><input id="accessory_payment_submission_date" name="accessory_payment_submission_date" type="text" value="<?php echo date('d-m-Y',$currentdate);?>" required/></td>
                    </tr>                                        
                    <tr>
                        <td>Payment Approval Date</td>
                        <td><input id="accessory_payment_approval_date" name="accessory_payment_approval_date" type="text" value="<?php echo date('d-m-Y',$currentdate);?>" required/></td>
                    </tr>                                                            
                    <tr>
                        <?php if($_SESSION['use_hierarchy'] == '1') { ?>
                        <td>OFAS Number</td>
                        <?php } else { ?>
                        <td>Transaction Reference Number</td>
                        <?php } ?>
                        <td><input type="text" name="accessory_ofasnumber" id="accessory_ofasnumber" placeholder="e.g. 12586" maxlength="20" onkeypress="return nospecialchars(event)">
                                <input type="hidden" name="maintenance_edit_id" id="maintenance_edit_id"></td>
                    </tr>
                </table>
                
                        <div id="get_accessories_div"></div>
                        
                </div>                                
            <br/>
            <div class="control-group">
            <div class="input-prepend ">
                <input type="button" id="edit_save_accessory" value="Save as History" class="btn btn-success">
            </div>
            </div>
        </div>
</fieldset>
</form>
</div>
            
<div class="modal hide" id="edittyre" style="top:34%;">
<form class="form-horizontal" id="gettyre_edit">
<fieldset>
    <div class="modal-header">
        <button class="close" data-dismiss="modal">×</button>
        <h4 style="color:#0679c0">Tyre Replacement History</h4>
    </div>
    <div class="modal-body">
        <?php
            $currentdate = getcurrentdate();
            $today = date('d-m-Y',$currentdate);
        ?>        
            <span id="mr_error" style="display: none;">Please Enter Meter Reading</span>                    
            <span id="dn_error" style="display: none;">Please Select Dealer</span>                                
            <span id="notes_error" style="display: none;">Please Enter Notes</span>                                            
            <span id="vid_error" style="display: none;">Please Enter Vehicle In Date</span>            
            <span id="vod_error" style="display: none;">Please Enter Vehicle Out Date</span>                        

            <span id="qa_error" style="display: none;">Please Enter Quotation Amount</span>                                                                        
            
            <span id="id_error" style="display: none;">Please Enter Invoice Generation Date</span>                                                
            <span id="in_error" style="display: none;">Please Enter Invoice No</span>                                                            
            <span id="ia_error" style="display: none;">Please Enter Invoice Amount</span>                                                                        
            <?php if($_SESSION['use_hierarchy'] == '1') { ?>
            <span id="ofas_error" style="display: none;">Please Enter OFAS Number</span>                                                                        
            <?php } else{ ?>
            <span id="ofas_error" style="display: none;">Please Enter Transaction Reference Number</span>                                                                        
            <?php } ?>
            
            <span id="ia_q_error" style="display: none;">Invoice Amount cannot be greater than Quotation Amount</span>                                                                                    
            <span id="datediff_error" style="display: none;">Vehicle Out Date cannot be less than Vehicle In Date</span>
            
            <div  style="overflow-y:scroll; height:320px;">            
            <fieldset>
            
                <table class="table table-bordered table-striped">
                    <th colspan="2">Transaction Details</th>
                    <tr>
                        <td width="50%">Meter Reading</td>
                        <td><input type="text" name="tyre_meter_reading" id="tyre_meter_reading" placeholder="e.g. 12586" maxlength="10" ></td>
                    </tr>                                          
                    <tr>
                        <td>Dealer name </td>
                        <td>
                            <select id="tyre_dealerid" name="tyre_dealerid">
                            <option value="-1">Select Dealer</option>
                            <?php
                            $dealers = getdealersbytype(1,$_SESSION['roleid'],$_SESSION['heirarchy_id']);
                            if(isset($dealers))
                            {
                                foreach ($dealers as $dealer)
                                {
                                    echo "<option value='$dealer->dealerid'>$dealer->name</option>";
                                }
                            }
                            ?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td>Notes</td>
                        <td><input type="text" name="note_tyre" id="note_tyre" placeholder="e.g. 12586" maxlength="30" onkeypress="return nospecialchars(event)"></td>
                    </tr>                        
                    <tr>
                        <td>Vehicle In Date</td>
                        <td><input id="tyre_vehicle_in_date" name="tyre_vehicle_in_date" type="text" value="<?php echo date('d-m-Y',$currentdate);?>" required/></td>
                    </tr>
                    <tr>
                        <td>Vehicle Out Date</td>
                        <td><input id="tyre_vehicle_out_date" name="tyre_vehicle_out_date" type="text" value="<?php echo date('d-m-Y',$currentdate);?>" required/></td>
                    </tr>                        
                </table>
                
                <table class="table table-bordered table-striped">
                    <th colspan="2">Quotation Details</th>                            
                    <tr>
                        <td width="50%">Quotation Amount (INR)</td>
                        <td><input type="text" name="tyre_amount_quote" id="tyre_amount_quote" placeholder="e.g. 125" maxlength="10" ></td>
                    </tr>
                    <tr>
                        <td>Quotation File</td>
                        <td><div class="input-prepend" id="tyre_quotefile_view"></td>                                                          
                    </tr>               
                    <tr>
                        <td>Quotation Submission Date</td>
                        <td><input id="tyre_submission_date" name="tyre_submission_date" type="text" value="<?php echo date('d-m-Y',$currentdate);?>" required/></td>
                    </tr>                    
                    <tr>
                        <td>Quotation Approval Date</td>
                        <td><input id="tyre_approval_date" name="tyre_approval_date" type="text" value="<?php echo date('d-m-Y',$currentdate);?>" required/></td>
                    </tr>                                        
                </table>
                
                <table class="table table-bordered table-striped">
                    <th colspan="2">Invoice Details</th>                            
                    <tr>
                        <td width="50%">Invoice Generation Date</td>
                        <td><input id="tyre_invoice_date" name="tyre_invoice_date" type="text" value="<?php echo date('d-m-Y',$currentdate);?>" required/></td>
                    </tr>
                    <tr>
                        <td>Invoice Number</td>
                        <td><input type="text" name="tyre_invoice_no" id="tyre_invoice_no" placeholder="e.g. 12586" maxlength="50" onkeypress="return nospecialchars(event)"></td>
                    </tr>
                    <tr>
                        <td>Invoice Amount (INR)</td>
                        <td><input type="text" name="tyre_amount_invoice" id="tyre_amount_invoice" placeholder="e.g. 125" maxlength="10" size="8" ></td>
                    </tr>                            
                    <tr id="invoice_file">
                         <td>Invoice File</td>
                        <td><div class="input-prepend" id="tyre_invoicefile_view"></td>                                                          
                    </tr>
                    <tr>
                        <td>Payment Submission Date</td>
                        <td><input id="tyre_payment_submission_date" name="tyre_payment_submission_date" type="text" value="<?php echo date('d-m-Y',$currentdate);?>" required/></td>
                    </tr>                                        
                    <tr>
                        <td>Payment Approval Date</td>
                        <td><input id="tyre_payment_approval_date" name="tyre_payment_approval_date" type="text" value="<?php echo date('d-m-Y',$currentdate);?>" required/></td>
                    </tr>                                                            
                    <tr>
                        <?php if($_SESSION['use_hierarchy'] == '1') { ?>
                        <td>OFAS Number</td>
                        <?php } else { ?>
                        <td>Transaction Reference Number</td>
                        <?php } ?>
                        <td><input type="text" name="tyre_ofasnumber" id="tyre_ofasnumber" placeholder="e.g. 12586" maxlength="20" onkeypress="return nospecialchars(event)"></td>
                    </tr>
                    <tr>
                        <td>Tyre Type</td>
                        <td><div id="tyre_type_view"></div></td>
                    </tr>                    
                </table>
                </div>                                
            <br/>
            <div class="control-group">
            <div class="input-prepend ">
                <input type="button" id="edit_save_tyre" value="Save as History" class="btn btn-success">
            </div>
            </div>
        </div>
	</fieldset>
                </form>
    </div>

<div class="modal hide" id="editrepair" style="top:34%;">
<form class="form-horizontal" id="getrepair_edit">
<fieldset>
    <div class="modal-header">
        <button class="close" data-dismiss="modal">×</button>
        <h4 style="color:#0679c0">Repair / Service Replacement History</h4>
    </div>
    <div class="modal-body">
        <?php
            $currentdate = getcurrentdate();
            $today = date('d-m-Y',$currentdate);
        ?>        
            <span id="mr_error" style="display: none;">Please Enter Meter Reading</span>                    
            <span id="dn_error" style="display: none;">Please Select Dealer</span>                                
            <span id="notes_error" style="display: none;">Please Enter Notes</span>                                            
            <span id="vid_error" style="display: none;">Please Enter Vehicle In Date</span>            
            <span id="vod_error" style="display: none;">Please Enter Vehicle Out Date</span>                        

            <span id="qa_error" style="display: none;">Please Enter Quotation Amount</span>                                                                        
            
            <span id="id_error" style="display: none;">Please Enter Invoice Generation Date</span>                                                
            <span id="in_error" style="display: none;">Please Enter Invoice No</span>                                                            
            <span id="ia_error" style="display: none;">Please Enter Invoice Amount</span>                                                                        
            <?php if($_SESSION['use_hierarchy'] == '1') { ?>
            <span id="ofas_error" style="display: none;">Please Enter OFAS Number</span>                                                                        
            <?php } else{ ?>
            <span id="ofas_error" style="display: none;">Please Enter Transaction Reference Number</span>                                                                        
            <?php } ?>
            
            <span id="ia_q_error" style="display: none;">Invoice Amount cannot be greater than Quotation Amount</span>                                                                                    
            <span id="datediff_error" style="display: none;">Vehicle Out Date cannot be less than Vehicle In Date</span>
            
            <div  style="overflow-y:scroll; height:320px;">            
            <fieldset>
            
                <table class="table table-bordered table-striped">
                    <th colspan="2">Transaction Details</th>
                    <tr>
                        <td width="50%">Meter Reading</td>
                        <td><input type="text" name="repair_meter_reading" id="repair_meter_reading" placeholder="e.g. 12586" maxlength="10" ></td>
                    </tr>                                          
                    <tr>
                        <td>Dealer name </td>
                        <td>
                            <select id="repair_dealerid" name="repair_dealerid">
                            <option value="-1">Select Dealer</option>
                            <?php
                            $dealers = getdealersbytype(1,$_SESSION['roleid'],$_SESSION['heirarchy_id']);
                            if(isset($dealers))
                            {
                                foreach ($dealers as $dealer)
                                {
                                    echo "<option value='$dealer->dealerid'>$dealer->name</option>";
                                }
                            }
                            ?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td>Notes</td>
                        <td><input type="text" name="note_repair" id="note_repair" placeholder="e.g. 12586" maxlength="30" onkeypress="return nospecialchars(event)"></td>
                    </tr>                        
                    <tr>
                        <td>Vehicle In Date</td>
                        <td><input id="repair_vehicle_in_date" name="repair_vehicle_in_date" type="text" value="<?php echo date('d-m-Y',$currentdate);?>" required/></td>
                    </tr>
                    <tr>
                        <td>Vehicle Out Date</td>
                        <td><input id="repair_vehicle_out_date" name="repair_vehicle_out_date" type="text" value="<?php echo date('d-m-Y',$currentdate);?>" required/></td>
                    </tr>                        
                </table>
                
                <table class="table table-bordered table-striped">
                    <th colspan="2">Quotation Details</th>                            
                    <tr>
                        <td width="50%">Quotation Amount (INR)</td>
                        <td><input type="text" name="repair_amount_quote" id="repair_amount_quote" placeholder="e.g. 125" maxlength="10" ></td>
                    </tr>
                    <tr>
                        <td>Quotation File</td>
                        <td><div class="input-prepend" id="repair_quotefile_view"></td>                                                          
                    </tr>               
                    <tr>
                        <td>Quotation Submission Date</td>
                        <td><input id="repair_submission_date" name="repair_submission_date" type="text" value="<?php echo date('d-m-Y',$currentdate);?>" required/></td>
                    </tr>                    
                    <tr>
                        <td>Quotation Approval Date</td>
                        <td><input id="repair_approval_date" name="repair_approval_date" type="text" value="<?php echo date('d-m-Y',$currentdate);?>" required/></td>
                    </tr>                                        
                </table>
                
                <table class="table table-bordered table-striped">
                    <th colspan="2">Invoice Details</th>                            
                    <tr>
                        <td width="50%">Invoice Generation Date</td>
                        <td><input id="repair_invoice_date" name="repair_invoice_date" type="text" value="<?php echo date('d-m-Y',$currentdate);?>" required/></td>
                    </tr>
                    <tr>
                        <td>Invoice Number</td>
                        <td><input type="text" name="repair_invoice_no" id="repair_invoice_no" placeholder="e.g. 12586" maxlength="50" onkeypress="return nospecialchars(event)"></td>
                    </tr>
                    <tr>
                        <td>Invoice Amount (INR)</td>
                        <td><input type="text" name="repair_amount_invoice" id="repair_amount_invoice" placeholder="e.g. 125" maxlength="10" size="8" ></td>
                    </tr>                            
                    <tr id="invoice_file">
                         <td>Invoice File</td>
                        <td><div class="input-prepend" id="repair_invoicefile_view"></td>                                                          
                    </tr>
                    <tr>
                        <td>Payment Submission Date</td>
                        <td><input id="repair_payment_submission_date" name="repair_payment_submission_date" type="text" value="<?php echo date('d-m-Y',$currentdate);?>" required/></td>
                    </tr>                                        
                    <tr>
                        <td>Payment Approval Date</td>
                        <td><input id="repair_payment_approval_date" name="repair_payment_approval_date" type="text" value="<?php echo date('d-m-Y',$currentdate);?>" required/></td>
                    </tr>                                                            
                    <tr>
                        <?php if($_SESSION['use_hierarchy'] == '1') { ?>
                        <td>OFAS Number</td>
                        <?php } else { ?>
                        <td>Transaction Reference Number</td>
                        <?php } ?>
                        <td><input type="text" name="repair_ofasnumber" id="repair_ofasnumber" placeholder="e.g. 12586" maxlength="20" onkeypress="return nospecialchars(event)"></td>
                    </tr>
                    <tr>
                        <td>Parts Consumed</td>
                        <td><div id="parts_view"></div></td>
                    </tr>                    
                    <tr>
                        <td>Tasks Performed</td>
                        <td><div id="tasks_view"></div></td>
                    </tr>                                        
                </table>
                </div>                                
            <br/>
            <div class="control-group">
            <div class="input-prepend ">
                <input type="button" id="edit_save_repair" value="Save as History" class="btn btn-success">
            </div>
            </div>
        </div>
	</fieldset>
                </form>
</div>


<div class="modal hide" id="addaccident" style="width: 41%;">
    <form method="POST" id="getaccident_approval"> 
<fieldset>
    <div class="modal-header">
         
        <button class="close" data-dismiss="modal">×</button>
        <h4 style="color:#0679c0" id="accident_head_fortransac">Accident Event Entry / Insurance Claim History</h4>
    </div>
	 <div class="modal-body">
    <ul class="nav nav-tabs" id="myTab">
                <li class="active"><a href="#acc_form" data-toggle="tab">Event Entry</a></li>
                <li><a href="#file_upload" data-toggle="tab">File Upload</a></li>
                
                </ul>
    <div class="tab-content">
        <div class="tab-pane active" id="acc_form">
            <span id="al_error" style="display: none;">Please Enter Accident Location</span>
            <span id="ad_error" style="display: none;">Please Enter Accident Description</span>        
            <span id="dn_error" style="display: none;">Please Enter Driver Name</span>                
            <span id="email_error" style="display: none;">Please Enter Correct Email Address</span>                        
            <span id="date_conflict_error" style="display: none;">License Start Date cannot be less than License End Date</span>                                            
                     <div  style="overflow-y:scroll; height:400px;">            
                    <table class="table table-bordered table-striped">
                        <th colspan="2">Required Details</th>
                        <tr>
                        <td width="50%">Accident Date</td>
                        <td><input id="acc_Date" name="acc_Date" type="text" class="input-small" value="<?php echo $today; ?>" /></td>
                        </tr>                  
                        <tr>
                        <td width="50%">Accident Time</td>
                        <td><input id="STime" name="STime" type="text" class="input-mini" /></td>
                        </tr>                  
                        <tr>
                        <td width="50%">Accident Location</td>
                        <td><input type="text" name="acc_location" id="acc_location" placeholder="e.g. Mumbai"  onkeypress="return nospecialchars(event)"/></td>
                        </tr>                  
                        <tr>
                        <td>Third Party Injury / Property Damage</td>
                        <td><input type="radio" name="thirdparty" id="thirdparty1" value="yes" /><span style="color:#000000">Yes</span>
                                       <input type="radio" name="thirdparty" id="thirdparty2" checked value="no" /> <span style="color:#000000">No</span></td>
                        </tr>                                                
                        <tr>
                        <td width="50%">Accident Description</td>
                        <td><input type="text" name="acc_desc" id="acc_desc" placeholder="e.g. description"  onkeypress="return nospecialchars(event)"/></td>
                        </tr>                                          
                        <tr>
                        <td width="50%">Driver Name</td>
                        <td><input type="text" name="driver_name" id="driver_name" placeholder="e.g. driver name" onkeypress="return nospecialchars(event)" /></td>
                        </tr>                                                                  
                        <tr>
                        <td width="50%">Driver License Validity From</td>
                        <td><input id="val_from_Date" name="val_from_Date" type="text" class="input-small" value="<?php echo $today; ?>" /></td>
                        </tr>                                                                                          
                        <tr>
                        <td width="50%">Driver License Validity To</td>
                        <td><input id="val_to_Date" name="val_to_Date" type="text" class="input-small" value="<?php echo $today; ?>" /></td>
                        </tr>                                                                                                                  
                        <tr>
                        <td width="50%">Type of License</td>
                        <td><input id="licence_type" name="licence_type" type="text"  onkeypress="return nospecialchars(event)"/></td>
                        </tr>                                                                                                                                          
                        <tr>
                        <td width="50%">Name and Location of Workshop</td>
                        <td><input id="add_workshop" name="add_workshop" type="text"  onkeypress="return nospecialchars(event)"/></td>
                        </tr>                                                                                                                                                                  
                        <tr>
                        <td width="50%">Send report to</td>
                        <td><input type="text" name="send_report" placeholder="email address" id="send_report" /></td>
                        </tr>                                                                                                                                                                                          
                        <tr>
                        <td width="50%">Estimated loss amount (INR)</td>
                        <td><input type="text" name="loss_amount" class="input-small " id="loss_amount" value="0" /></td>
                        </tr>                                                                                                                                                                                                                  
                        
                        <tr>
                        <td width="50%">Settlement Amount (INR)</td>
                        <td><input type="text" name="sett_amount" class="input-small change_val" id="sett_amount" value="0" /></td>
                        </tr>                                                                                                                                                                                                                  

                        <tr>
                        <td width="50%">Actual Repair Amount (INR)</td>
                        <td><input type="text" name="actual_amount" class="input-small change_val" id="actual_amount" value="0" /></td>
                        </tr>                                                                                                                                                                                                                  
                        
                        <tr>
                        <td width="50%">Amount Spent by Mahindra (INR)</td>
                        <td><input type="text" name="mahindra_amount" class="input-small" id="mahindra_amount" readonly value="0" /></td>
                        </tr>                                                                                                                                                                                                                  

                        <tr>
                        <td width="50%">Submission Date</td>
                        <td><input id="acc_submission_date" name="acc_submission_date" type="text" class="input-small" value="<?php echo $today; ?>" /></td>
                        </tr>                  
                        <tr>
                        <td width="50%">Approval Date</td>
                        <td><input id="acc_approval_date" name="acc_approval_date" type="text" class="input-small" value="<?php echo $today; ?>" /></td>
                        </tr>                  
                        
                        <tr>
                        <?php if($_SESSION['use_hierarchy'] == '1') { ?>
                        <td width="50%">OFAS Number</td>
                        <?php } else { ?>
                        <td width="50%">Transaction Reference Number</td>
                        <?php } ?>
                        <td><input type="text" name="acc_ofasnumber" class="input-small" id="acc_ofasnumber" onkeypress="return nospecialchars(event)"></td>
                        </tr>                                                                                                                                                                                                                  
                        
                    </table>

		</div>
                         
                            <div class="control-group">
                            <div class="input-prepend ">
                            <input type="button" onclick="push_transaction_accident();" value="Send for Approval" class="btn btn-success">
                            </div>
                            </div>
        </div>
        <div class="tab-pane" id="file_upload">
            <div class="control-group" style="text-align: left; margin-left: 10px;">
            <div class="input-prepend">
            <span class="add-on">File 1</span> <input type="file" name="file1" id="file1" >
			
            <!--<input type="button" id="upload_file1" onclick="upload('1');" value="Upload" class="btn btn-success" />-->
            </div>
            </div>
            <div class="control-group" style="text-align: left; margin-left: 10px;">
            <div class="input-prepend">
            <span class="add-on">File 2</span> <input type="file" name="file2" id="file2">
           <!-- <input type="button" id="upload_file2" onclick="upload('2');" value="Upload" class="btn btn-success" />-->
            </div>	
            </div>
            <div class="control-group" style="text-align: left; margin-left: 10px;">
            <div class="input-prepend">
            <span class="add-on">File 3</span> <input type="file" name="file3" id="file3">
            <!--<input type="button" id="upload_file3" onclick="upload('3');" value="Upload" class="btn btn-success" />-->
            </div>
            </div>
            <div class="control-group" style="text-align: left; margin-left: 10px;">
            <div class="input-prepend">
            <span class="add-on">File 4</span> <input type="file" name="file4" id="file4">
          <!--  <input type="button" id="upload_file4" onclick="upload('4');" value="Upload" class="btn btn-success" />-->
            </div>
            </div>
            <div class="control-group" style="text-align: left; margin-left: 10px;">
            <div class="input-prepend">
            <span class="add-on">File 5</span> <input type="file" name="file5" id="file5">
            <!--<input type="button" id="upload_file5" onclick="upload('5');" value="Upload" class="btn btn-success" />-->
            </div>
            </div>
        </div>
		</div>
    </fieldset>
</form>
</div>



<div class="modal hide" id="editaccident" style="width: 41%;">
    <form method="POST" id="editaccident_approval"> 
	   <input type="hidden" id="editacc_maintainanceid" name="editacc_maintainanceid" value=''>       
<fieldset>
    <div class="modal-header">
         
        <button class="close" data-dismiss="modal">×</button>
        <h4 style="color:#0679c0" id="accident_head_fortransac">Accident Event Entry / Insurance Claim History</h4>
    </div>
	 <div class="modal-body">
	 <div  style="overflow-y:scroll; height:400px;">
    <ul class="nav nav-tabs" id="myTab">
                <li class="active"><a href="#editacc_form" data-toggle="tab">Event Entry</a></li>
                <li><a href="#editfile_upload" data-toggle="tab">File Upload</a></li>
                
                </ul>
    <div class="tab-content">
        <div class="tab-pane active" id="editacc_form">
                    <table class="table table-bordered table-striped">
                        <th colspan="2">Required Details</th>
                        <tr>
                        <td width="50%">Accident Date</td>
                        <td><input id="acc_Date" name="acc_Date" type="text" class="input-small" value="<?php echo $today; ?>" /></td>
                        </tr>                  
                        <tr>
                        <td width="50%">Accident Time</td>
                        <td><input id="STime" name="STime" type="text" class="input-mini" /></td>
                        </tr>                  
                        <tr>
                        <td width="50%">Accident Location</td>
                        <td><input type="text" name="acc_location" id="acc_location" placeholder="e.g. Mumbai"  onkeypress="return nospecialchars(event)"/></td>
                        </tr>                  
                        <tr>
                        <td>Third Party Injury / Property Damage</td>
                        <td><input type="radio" name="thirdparty" id="thirdparty1" value="yes" /><span style="color:#000000">Yes</span>
                                       <input type="radio" name="thirdparty" id="thirdparty2" checked value="no" /> <span style="color:#000000">No</span></td>
                        </tr>                                                
                        <tr>
                        <td width="50%">Accident Description</td>
                        <td><input type="text" name="acc_desc" id="acc_desc" placeholder="e.g. description"  onkeypress="return nospecialchars(event)"/></td>
                        </tr>                                          
                        <tr>
                        <td width="50%">Driver Name</td>
                        <td><input type="text" name="driver_name" id="driver_name" placeholder="e.g. driver name" onkeypress="return nospecialchars(event)" /></td>
                        </tr>                                                                  
                        <tr>
                        <td width="50%">Driver License Validity From</td>
                        <td><input id="val_from_Date" name="val_from_Date" type="text" class="input-small" value="<?php echo $today; ?>" /></td>
                        </tr>                                                                                          
                        <tr>
                        <td width="50%">Driver License Validity To</td>
                        <td><input id="val_to_Date" name="val_to_Date" type="text" class="input-small" value="<?php echo $today; ?>" /></td>
                        </tr>                                                                                                                  
                        <tr>
                        <td width="50%">Type of License</td>
                        <td><input id="licence_type" name="licence_type" type="text"  onkeypress="return nospecialchars(event)"/></td>
                        </tr>                                                                                                                                          
                        <tr>
                        <td width="50%">Name and Location of Workshop</td>
                        <td><input id="add_workshop" name="add_workshop" type="text"  onkeypress="return nospecialchars(event)"/></td>
                        </tr>                                                                                                                                                                  
                        <tr>
                        <td width="50%">Send report to</td>
                        <td><input type="text" name="send_report" placeholder="email address" id="send_report" /></td>
                        </tr>                                                                                                                                                                                          
                        <tr>
                        <td width="50%">Estimated loss amount (INR)</td>
                        <td><input type="text" name="loss_amount" class="input-small " id="loss_amount" value="0" /></td>
                        </tr>                                                                                                                                                                                                                  
                        
                        <tr>
                        <td width="50%">Settlement Amount (INR)</td>
                        <td><input type="text" name="sett_amount" class="input-small change_val" id="sett_amount" value="0" /></td>
                        </tr>                                                                                                                                                                                                                  

                        <tr>
                        <td width="50%">Actual Repair Amount (INR)</td>
                        <td><input type="text" name="actual_amount" class="input-small change_val" id="actual_amount" value="0" /></td>
                        </tr>                                                                                                                                                                                                                  
                        
                        <tr>
                        <td width="50%">Amount Spent by Mahindra (INR)</td>
                        <td><input type="text" name="mahindra_amount" class="input-small" id="mahindra_amount" readonly value="0" /></td>
                        </tr>                                                                                                                                                                                                                  

                        <tr>
                        <td width="50%">Submission Date</td>
                        <td><input id="acc_submission_date" name="acc_submission_date" type="text" class="input-small" value="<?php echo $today; ?>" /></td>
                        </tr>                  
                        <tr>
                        <td width="50%">Approval Date</td>
                        <td><input id="acc_approval_date" name="acc_approval_date" type="text" class="input-small" value="<?php echo $today; ?>" /></td>
                        </tr>                  
                        
                        <tr>
                        <?php if($_SESSION['use_hierarchy'] == '1') { ?>
                        <td width="50%">OFAS Number</td>
                        <?php } else { ?>
                        <td width="50%">Transaction Reference Number</td>
                        <?php } ?>
                        <td><input type="text" name="acc_ofasnumber" class="input-small" id="acc_ofasnumber" onkeypress="return nospecialchars(event)"></td>
                        </tr>                                                                                                                                                                                                                  
                        
                    </table>

                            <div class="control-group">
                            <div class="input-prepend ">
                            <input type="button" onclick="editacc();" value="Send for Approval" class="btn btn-success">
                            </div>
                            </div>
        </div>
        <div class="tab-pane" id="editfile_upload">
            <div class="control-group" style="text-align: left; margin-left: 10px;">
            <div class="input-prepend">
            <span class="add-on">File 1</span> <input type="file" name="efile1" id="efile1" >
			
            <!--<input type="button" id="upload_file1" onclick="upload('1');" value="Upload" class="btn btn-success" />-->
            </div>
            </div>
            <div class="control-group" style="text-align: left; margin-left: 10px;">
            <div class="input-prepend">
            <span class="add-on">File 2</span> <input type="file" name="efile2" id="efile2">
           <!-- <input type="button" id="upload_file2" onclick="upload('2');" value="Upload" class="btn btn-success" />-->
            </div>	
            </div>
            <div class="control-group" style="text-align: left; margin-left: 10px;">
            <div class="input-prepend">
            <span class="add-on">File 3</span> <input type="file" name="efile3" id="efile3">
            <!--<input type="button" id="upload_file3" onclick="upload('3');" value="Upload" class="btn btn-success" />-->
            </div>
            </div>
            <div class="control-group" style="text-align: left; margin-left: 10px;">
            <div class="input-prepend">
            <span class="add-on">File 4</span> <input type="file" name="efile4" id="efile4">
          <!--  <input type="button" id="upload_file4" onclick="upload('4');" value="Upload" class="btn btn-success" />-->
            </div>
            </div>
            <div class="control-group" style="text-align: left; margin-left: 10px;">
            <div class="input-prepend">
            <span class="add-on">File 5</span> <input type="file" name="efile5" id="efile5">
            <!--<input type="button" id="upload_file5" onclick="upload('5');" value="Upload" class="btn btn-success" />-->
            </div>
            </div>
			<div class="control-group">
                <div class="input-prepend  formSep " style="color:#000000;">
                View uploaded Files
                </div><br/><br/>
                <div class="input-prepend" id="accident_files_view">
                
                </div><br/>
              
                </div>
			
			
        </div>
		</div>
		</div>
    </fieldset>
</form>
</div>
