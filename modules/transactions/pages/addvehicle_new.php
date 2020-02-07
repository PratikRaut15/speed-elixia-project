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
            <input type="text" name="yearofman" id="yearofman" placeholder="e.g. 2011" maxlength="4" onkeypress="">
            <span class="add-on">Purchase Date <span style="color:#FE2E2E; ">*</span></span>
            <input id="PDate" name="PDate" type="text" class="input-small" value="" required=""  />
          </div>
          <div class="input-prepend formSep "> <?php echo($_SESSION['group']); ?> Details </div>
          <div class="input-prepend formSep "> <span class="add-on"><?php echo($_SESSION['group']); ?><span style="color:#FE2E2E; ">*</span></span>
            <select id="branchid" name="branchid" onchange="showbranch()">
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
            <input type="text" name="start_meter" id="start_meter" placeholder="e.g. 12586" maxlength="10" onkeypress="">
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
                                                                        $dealers = getdealersbytype(5);
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
        <span id="insurance_success" style="display:none;">Insurance details added successfully, please fill up the maintenance detail.</span> <span id="insurance_value_error" style="display:none;">Please enter digits only in Value of Insurance.</span> <span id="premium_value_error" style="display:none;">Please enter digits only in Premium.</span>
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
              <input type="text" name="insurance_value" id="insurance_value" value="">
              <span class="add-on">Premium</span>
              <input type="text" name="premium_value" id="premium_value" value="">
              <span class="add-on">Start Date</span>
              <input id="StartDate" name="StartDate" type="text" class="input-small" value="24-04-2014" required="">
            </div>
            <br>
            <div class="input-prepend"> <span class="add-on">End Date</span>
              <input id="EndDate" name="EndDate" type="text" class="input-small" value="24-04-2014" required="">
<!--              <span class="add-on">Amount</span>
              <input type="text" name="ins_amount" id="ins_amount" value=""> -->
              <span class="add-on">Notes</span>
              <input type="text" name="ins_notes" id="ins_notes" value="">
            </div>
            <br>
            <div class="input-prepend formSep"> <span class="add-on">Insurance Company</span>
              <select name="insurance_company">
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
              <input type="text" name="near_place" placeholder="e.g. mumbai, delhi" id="near_place" value="">
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
      <span id="capitalization_success" style="display:none;">Capitalization details added successfully, please upload the papers.</span>
      <div class="control-group">
        <form method="POST" id="capitalization_form">
          <div class="input-prepend formSep "> Capitalization </div>
          <div class="input-prepend"> <span class="add-on">Date</span>
            <input id="cap_EDate" name="cap_EDate" type="text" class="input-small" value="24-04-2014" required="" />
            <span class="add-on">Cost</span>
            <input type="text" name="cap_cost" id="cap_cost" />
            <!--                        <span class="add-on">% (Maintenance / Capitalization)</span> <input type="text" name="cap_code" readonly id="cap_code" />-->
          </div>
          <br>
          <div class="input-prepend"> <span class="add-on">Address</span>
            <textarea name="cap_address" id="cap_address"></textarea>
          </div>
          <input type="button" onclick="addcap();" id="capitalization_save" value="Save" class="btn btn-success">
        </form>
      </div>
      </fieldset>
    </div>
    <div class="tab-pane" id="tab_l7">
      <fieldset>
      <form method="POST" id="papers_form" enctype="multipart/form-data">
        <span id="papers_success" style="display:none;">Papers uploaded successfully, please add geotag.</span>
        <div class="control-group" style="text-align: left; margin-left: 10px;">
          <div class="input-prepend"> <span class="add-on">PUC</span>
            <input type="file" name="puc" id="puc">
            <input type="button" id="upload_puc" onclick="uploadFilesPUC();" value="Upload" class="btn btn-success" />
          </div>
        </div>
        <div class="control-group" style="text-align: left; margin-left: 10px;">
          <div class="input-prepend"> <span class="add-on">Registration</span>
            <input type="file" name="reg" id="reg">
            <input type="button" id="upload_reg" onclick="uploadFilesREG();" value="Upload" class="btn btn-success" />
          </div>
        </div>
        <div class="control-group" style="text-align: left; margin-left: 10px;">
          <div class="input-prepend"> <span class="add-on">Insurance</span>
            <input type="file" name="insurance" id="insurance">
            <input type="button" id="upload_ins" onclick="uploadFilesINS();" value="Upload" class="btn btn-success" />
          </div>
        </div>
        <div class="control-group" style="text-align: left; margin-left: 10px;">
          <div class="input-prepend"> <span class="add-on">Others</span>
            <input type="text" name="other" id="other">
            <input type="file" name="other_file" id="other_file">
            <input type="button" id="upload_other" onclick="uploadFilesOTHER();" value="Upload" class="btn btn-success" />
          </div>
        </div>
        <div class="control-group" style="text-align: left; margin-left: 10px;">
          <div class="input-prepend"> <span class="add-on">Others</span>
            <input type="text" name="other1" id="other1">
            <input type="file" name="other1_file" id="other1_file">
            <input type="button" id="upload_other1" onclick="uploadFilesOTHER1();" value="Upload" class="btn btn-success" />
          </div>
        </div>
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
	   <span id="amount" name="amount" style="display: none;">Please Check The Amount</span> <span id="from_error" name="from_error" style="display: none;">Please Select Start Date</span> <span id="to_error" name="to_error" style="display: none;">Please Select End Date</span> <span id="tax_datediff_error" name="tax_year_error" style="display: none;">To Date cannot be less than From Date</span> <span id="tax_year_error" name="tax_year_error" style="display: none;">From Date & To Date must not be in the range preceeding the manufacturing date of the vehicle</span> <span id="tax_amount_error" name="edit_amount_error" style="display: none;">Amount must be numeric must not exceed 7 digits</span> <span id="registrationno_error" name="registrationno_error" style="display: none;">Registration No. must be caps alphabet. It must be maximum 14 Characters.</span>
        <fieldset>
        <div class="control-group">
          <div class="input-prepend "> <span class="add-on" style="color:#000000">From Date</span>
            <?php 
		function getcurrentdate()
			{
				$currentdate = strtotime(date("Y-m-d H:i:s")); 
				$currentdate = substr($currentdate, '0',11);
				return $currentdate;
			}
		$currentdate = getcurrentdate();?>
            <input id="from_date" name="from_date" type="text" value="<?php echo date('d-m-Y',$currentdate);?>" required/>
            <span class="add-on" style="color:#000000">To Date</span>
            <input id="to_date" name="to_date" type="text" value="<?php echo date('d-m-Y',$currentdate);?>" required/>
          </div>
        </div>
        <div class="control-group">
          <div class="input-prepend "> <span class="add-on" style="color:#000000">Type</span>
            <select name="tax_type" id="tax_type">
              <option value="">Select Tax Type</option>
              <option value="1">Road Tax</option>
              <option value="2">Registration Tax</option>
            </select>
            <span class="add-on" style="color:#000000">Amount</span>
            <input type="text" name="tax_amount" id="tax_amount">
          </div>
        </div>
        <div class="control-group">
          <div class="input-prepend "> <span class="add-on" id="reg_name" style="color:#000000; display: none;">Registration No.</span>
            <input type="text" name="registrationno" id="registrationno" style="display: none;" placeholder="registration no." maxlength="14">
          </div>
        </div>
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
      <div  style="overflow-y:scroll; height:400px;">
      <fieldset>
      <div class="control-group">
        <div class="input-prepend "> <span class="add-on" style="color:#000000">Replacement Date</span>
          <input id="batt_replacement_date" name="batt_replacement_date" type="text" value="<?php echo date('d-m-Y',$currentdate);?>" required/>
          <span class="add-on" style="color:#000000">Meter Reading</span>
          <input type="text" name="batt_meter_reading" id="batt_meter_reading" placeholder="e.g. 12586" maxlength="10">
        </div>
      </div>
      <div class="control-group">
        <div class="input-prepend "> <span class="add-on" style="color:#000000">Vehicle In Date</span>
          <input id="batt_vehicle_in_date" name="batt_vehicle_in_date" type="text" value="<?php echo date('d-m-Y',$currentdate);?>" required/>
          <span class="add-on" style="color:#000000">Vehicle Out Date</span>
          <input id="batt_vehicle_out_date" name="batt_vehicle_out_date" type="text" value="<?php echo date('d-m-Y',$currentdate);?>" required/>
        </div>
      </div>
      <div class="control-group">
        <div class="input-prepend "> <span class="add-on" style="color:#000000">Quotation amount</span>
          <input type="text" name="batt_amount_quote" id="batt_amount_quote" placeholder="e.g. 125" maxlength="10" size="4" value="0">
          <span class="add-on" style="color:#000000">Quotation File</span>
          <input type="file" title="browse file" id="batt_file_for_quote" name="batt_file_for_quote" class="file-inputs">
        </div>
      </div>
      <div class="control-group">
        <div class="input-prepend "> <span class="add-on" style="color:#000000">Invoice Date</span>
          <input id="batt_invoice_date" name="batt_invoice_date" type="text" value="<?php echo date('d-m-Y',$currentdate);?>" required/>
          <span class="add-on" style="color:#000000">Invoice No.</span>
          <input type="text" name="batt_invoice_no" id="batt_invoice_no" placeholder="e.g. 12586" maxlength="50">
        </div>
      </div>
      <div class="control-group">
        <div class="input-prepend "> <span class="add-on" style="color:#000000">Invoice amount</span>
          <input type="text" name="batt_amount_invoice" id="batt_amount_invoice" placeholder="e.g. 125" maxlength="10" size="4" value="0">
          <span class="add-on" style="color:#000000">Invoice File</span>
          <input type="file" title="browse file" id="batt_file_for_invoice" name="batt_file_for_invoice" class="file-inputs">
        </div>
      </div>
      <div class="control-group">
        <div class="input-prepend "> <span class="add-on" style="color:#000000">Payment Mode</span>
          <select id="batt_payment_mode" name="batt_payment_mode">
            <option value="-1">Select Mode</option>
            <option value="1">Cash</option>
            <option value="2">Cheque</option>
            <option value="3">Soft Payment</option>
          </select>
          <span class="add-on" me="batt_amount_label" id="batt_amount_label" style="color:#000000">Amount</span>
          <input type="text" name="batt_amount" id="batt_amount" value="0" placeholder="e.g. 12586" maxlength="10" size="4">
        </div>
      </div>
      <div class="control-group" name="batt_div_payment_cg" id="batt_div_payment_cg" style=" display: none;">
        <div class="input-prepend "> <span class="add-on cheque" name="batt_cheque_label" id="batt_cheque_label" style="color:#000000;">Cheque No.</span>
          <input type="text" name="batt_cheque" class="cheque" id="batt_cheque" value="" placeholder="e.g. 12586" maxlength="10" style="color:#000000;">
          <span class="add-on soft-payment-bank" name="batt_bank_label" id="batt_bank_label" style="color:#000000;">Bank Name</span>
          <input type="text" name="batt_bank" class="soft-payment-bank" id="batt_bank" value="" placeholder="e.g. 12586" maxlength="10" style="color:#000000;">
          <span class="add-on soft-payment" name="batt_ifsc_label" id="batt_ifsc_label" style="color:#000000;">IFSC Code</span>
          <input type="text" name="batt_ifsc" class="soft-payment" id="batt_ifsc" value="" placeholder="e.g. 12586" maxlength="10" style="color:#000000;">
        </div>
      </div>
      <div class="control-group">
        <div class="input-prepend "> <span class="add-on" style="color:#000000">Dealer</span>
          <select id="batt_dealerid" name="batt_dealerid">
            <option value="">Select Dealer</option>
            <?php
                            $dealers = getdealersbytype(1);
                            if(isset($dealers))
                            {
                            foreach ($dealers as $dealer)
                            {
                            echo "<option value='$dealer->dealerid'>$dealer->name</option>";
                            }
                            }
                            ?>
          </select>
          <span class="add-on" style="color:#000000">Notes</span>
          <input type="text" name="batt_note" id="batt_note" placeholder="e.g. 12586" maxlength="10">
        </div>
      </div>
      </div>
      <div class="control-group">
        <div class="input-prepend ">
          <input type="button" onclick="addbattery();" value="Save" class="btn btn-success">
        </div>
      </div>
      </fieldset>
    </div>
  </form>
</div>
<div class="modal hide" id="addbattery_approval">
  <form method="POST" id="getbattery_approval">
    <fieldset>
    <div class="modal-header">
      <button class="close" data-dismiss="modal">×</button>
      <h4 style="color:#0679c0">Quotation for Battery Replacement</h4>
    </div>
    <div class="modal-body">
      <div  style="overflow-y:scroll; height:400px;"> 
	  <span id="amount" name="dealer_check" style="display: none;">Please Select Dealer</span>
        <div class="control-group">
          <div class="input-prepend "> <span class="add-on" style="color:#000000">Meter Reading</span>
            <input type="text" name="meter_reading" id="meter_reading" placeholder="e.g. 12586" maxlength="10">
          </div>
        </div>
        <div class="control-group">
          <div class="input-prepend "> <span class="add-on" style="color:#000000">Dealer</span>
            <select id="dealerid" name="dealerid">
              <option value="">Select Dealer</option>
              <?php
                            $dealers = getdealersbytype(1);
                            if(isset($dealers))
                            {
                            foreach ($dealers as $dealer)
                            {
                            echo "<option value='$dealer->dealerid'>$dealer->name</option>";
                            }
                            }
                            ?>
            </select>
          </div>
        </div>
        <div class="control-group">
          <div class="input-prepend "> <span class="add-on" style="color:#000000">Notes</span>
            <input type="text" name="note_batt" id="note_batt" placeholder="e.g. 12586" maxlength="10">
          </div>
        </div>
        <div class="control-group">
          <div class="input-prepend ">
            <input type="button" onclick="addbattery_approval();" value="Save" class="btn btn-success">
          </div>
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
    <div  style="overflow-y:scroll; height:400px;">
    <fieldset>
    <div class="control-group">
      <div class="input-prepend "> <span class="add-on" style="color:#000000">Replacement Date</span>
        <input id="tyre_replacement_date" name="tyre_replacement_date" type="text" value="<?php echo date('d-m-Y',$currentdate);?>" required/>
        <span class="add-on" style="color:#000000">Meter Reading</span>
        <input type="text" name="tyre_meter_reading" id="tyre_meter_reading" placeholder="e.g. 12586" maxlength="10">
      </div>
    </div>
    <div class="control-group">
      <div class="input-prepend "> <span class="add-on" style="color:#000000">Vehicle In Date</span>
        <input id="tyre_vehicle_in_date" name="tyre_vehicle_in_date" type="text" value="<?php echo date('d-m-Y',$currentdate);?>" required/>
        <span class="add-on" style="color:#000000">Vehicle Out Date</span>
        <input id="tyre_vehicle_out_date" name="tyre_vehicle_out_date" type="text" value="<?php echo date('d-m-Y',$currentdate);?>" required/>
      </div>
    </div>
    <div class="control-group">
      <div class="input-prepend "> <span class="add-on" style="color:#000000">Quotation amount</span>
        <input type="text" name="tyre_amount_quote" id="tyre_amount_quote" placeholder="e.g. 125" maxlength="10" size="4" value="0">
        <span class="add-on" style="color:#000000">Quotation File</span>
        <input type="file" title="browse file" id="tyre_file_for_quote" name="tyre_file_for_quote" class="file-inputs">
      </div>
    </div>
    <div class="control-group">
      <div class="input-prepend "> <span class="add-on" style="color:#000000">Invoice Date</span>
        <input id="tyre_invoice_date" name="tyre_invoice_date" type="text" value="<?php echo date('d-m-Y',$currentdate);?>" required/>
        <span class="add-on" style="color:#000000">Invoice No.</span>
        <input type="text" name="tyre_invoice_no" id="tyre_invoice_no" placeholder="e.g. 12586" maxlength="50">
      </div>
    </div>
    <div class="control-group">
      <div class="input-prepend "> <span class="add-on" style="color:#000000">Invoice amount</span>
        <input type="text" name="tyre_amount_invoice" id="tyre_amount_invoice" placeholder="e.g. 125" maxlength="10" size="4" value="0">
        <span class="add-on" style="color:#000000">Invoice File</span>
        <input type="file" title="browse file" id="tyre_file_for_invoice" name="tyre_file_for_invoice" class="file-inputs">
      </div>
    </div>
    <div class="control-group">
      <div class="input-prepend "> <span class="add-on" style="color:#000000">Payment Mode</span>
        <select id="tyre_payment_mode" name="tyre_payment_mode">
          <option value="-1">Select Mode</option>
          <option value="1">Cash</option>
          <option value="2">Cheque</option>
          <option value="3">Soft Payment</option>
        </select>
        <span class="add-on" me="tyre_amount_label" id="tyre_amount_label" style="color:#000000">Amount</span>
        <input type="text" name="tyre_amount" id="tyre_amount" value="0" placeholder="e.g. 12586" maxlength="10" size="4">
      </div>
    </div>
    <div class="control-group" name="tyre_div_payment_cg" id="tyre_div_payment_cg" style=" display: none;">
      <div class="input-prepend "> <span class="add-on cheque" name="tyre_cheque_label" id="tyre_cheque_label" style="color:#000000;">Cheque No.</span>
        <input type="text" name="tyre_cheque" id="tyre_cheque" class="cheque" value="" placeholder="e.g. 12586" maxlength="10" style="color:#000000;">
        <span class="add-on soft-payment-bank" name="tyre_bank_label" id="tyre_bank_label" style="color:#000000;">Bank Name</span>
        <input type="text" name="tyre_bank" id="tyre_bank" class="soft-payment-bank" value="" placeholder="e.g. 12586" maxlength="10" style="color:#000000;">
        <span class="add-on soft-payment" name="tyre_ifsc_label" id="tyre_ifsc_label" style="color:#000000;">IFSC Code</span>
        <input type="text" name="tyre_ifsc" id="tyre_ifsc" class="soft-payment" value="" placeholder="e.g. 12586" maxlength="10" style="color:#000000;">
      </div>
    </div>
    <div class="control-group">
      <div class="input-prepend "> <span class="add-on" style="color:#000000">Dealer</span>
        <select id="tyre_dealerid" name="tyre_dealerid">
          <option value="">Select Dealer</option>
          <?php
                            $dealers = getdealersbytype(2);
                            if(isset($dealers))
                            {
                            foreach ($dealers as $dealer)
                            {
                            echo "<option value='$dealer->dealerid'>$dealer->name</option>";
                            }
                            }
                            ?>
        </select>
        <span class="add-on" style="color:#000000">Notes</span>
        <input type="text" name="tyre_note" id="tyre_note" placeholder="e.g. 12586" maxlength="10">
      </div>
    </div>
    <div id="tyre_fields">
      <div class="control-group tyre_field"  >
        <div class="input-prepend "> <span class="add-on" style="color:#000000" >Tyre type</span>
          <select id="tyre_type" name="tyre_type">
            <option value="">Select type</option>
            <option value="1">Right front</option>
            <option value="2">Right Back</option>
            <option value="3">Left front</option>
            <option value="4">Left Back</option>
            <option value="5">Stepney</option>
          </select>
          <input type="button" id="add_ttype" name="add_ttype" value="+" onclick="add_items_container('#tyrelist_container','tyre_type')">
          <br/>
          <div id="tyrelist_container"></div>
        </div>
      </div>
    </div>
    </div>
    <div class="control-group">
      <div class="input-prepend ">
        <input type="button" onclick="addtyre();" value="Save" class="btn btn-success">
      </div>
    </div>
    </div>
    </fieldset>
  </form>
</div>
<div class="modal hide" id="addtyre_approval">
  <form class="form-horizontal" id="gettyre_approval">
    <fieldset>
    <div class="modal-header">
      <button class="close" data-dismiss="modal">×</button>
      <h4 style="color:#0679c0">Add Tyre</h4>
    </div>
    <div class="modal-body">
    <div  style="overflow-y:scroll; height:400px;">
    <span id="amount" name="amount" style="display: none;">Please Check The Amount</span> <span id="sdate" name="sdate" style="display: none;">Please Select Start Date</span> <span id="edate" name="edate" style="display: none;">Please Select End Date</span>
    <fieldset>
    <div class="control-group">
      <div class="input-prepend "> <span class="add-on" style="color:#000000">Meter Reading</span>
        <input type="text" name="year" id="year" placeholder="e.g. 12586" maxlength="10">
      </div>
    </div>
    <div class="control-group">
      <div class="input-prepend "> <span class="add-on" style="color:#000000">Dealer</span>
        <select id="dealer" name="dealer">
          <option value="">Select Dealer</option>
          <?php
                            $dealers = getdealersbytype(2);
                            if(isset($dealers))
                            {
                            foreach ($dealers as $dealer)
                            {
                            echo "<option value='$dealer->dealerid'>$dealer->name</option>";
                            }
                            }
                            ?>
        </select>
      </div>
    </div>
    <div class="control-group">
      <div class="input-prepend "> <span class="add-on" style="color:#000000">Notes</span>
        <input type="text" name="note_main" id="note_main" placeholder="e.g. 12586" maxlength="10">
      </div>
    </div>
    </div>
    <div id="tyre_fields" style=" display: none;" >
      <div class="control-group tyre_field"  >
        <div class="input-prepend "> <span class="add-on" >Tyre type</span>
          <select id="tyre_type" name="tyre_type">
            <option value="">Select type</option>
            <option value="1">Right front</option>
            <option value="2">Right Back</option>
            <option value="3">Left front</option>
            <option value="4">Left Back</option>
            <option value="5">Stepney</option>
          </select>
          <input type="button" id="add_ttype" name="add_ttype" value="+" onclick="add_items_container('#tyrelist_container','tyre_type')">
          <div id="tyrelist_container"></div>
        </div>
      </div>
    </div>
    <div class="control-group">
      <div class="input-prepend ">
        <input type="button" onclick="addtyre_approval();" value="Save" class="btn btn-success">
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
	<div  style="overflow-y:scroll; height:400px;">
    <fieldset>
    <div class="control-group">
      <div class="input-prepend "> <span class="add-on" style="color:#000000">Replacement Date</span>
        <input id="repair_replacement_date" name="repair_replacement_date" type="text" value="<?php echo date('d-m-Y',$currentdate);?>" required/>
        <span class="add-on" style="color:#000000">Meter Reading</span>
        <input type="text" name="repair_meter_reading" id="repair_meter_reading" placeholder="e.g. 12586" maxlength="10">
      </div>
    </div>
    <div class="control-group">
      <div class="input-prepend "> <span class="add-on" style="color:#000000">Vehicle In Date</span>
        <input id="repair_vehicle_in_date" name="repair_vehicle_in_date" type="text" value="<?php echo date('d-m-Y',$currentdate);?>" required/>
        <span class="add-on" style="color:#000000">Vehicle Out Date</span>
        <input id="repair_vehicle_out_date" name="repair_vehicle_out_date" type="text" value="<?php echo date('d-m-Y',$currentdate);?>" required/>
      </div>
    </div>
    <div class="control-group">
      <div class="input-prepend "> <span class="add-on" style="color:#000000">Quotation amount</span>
        <input type="text" name="repair_amount_quote" id="repair_amount_quote" placeholder="e.g. 125" maxlength="10" size="4" value="0">
        <span class="add-on" style="color:#000000">Quotation File</span>
        <input type="file" title="browse file" id="repair_file_for_quote" name="repair_file_for_quote" class="file-inputs">
      </div>
    </div>
    <div class="control-group">
      <div class="input-prepend "> <span class="add-on" style="color:#000000">Invoice Date</span>
        <input id="repair_invoice_date" name="repair_invoice_date" type="text" value="<?php echo date('d-m-Y',$currentdate);?>" required/>
        <span class="add-on" style="color:#000000">Invoice No.</span>
        <input type="text" name="repair_invoice_no" id="repair_invoice_no" placeholder="e.g. 12586" maxlength="50">
      </div>
    </div>
    <div class="control-group">
      <div class="input-prepend "> <span class="add-on" style="color:#000000">Invoice amount</span>
        <input type="text" name="repair_amount_invoice" id="repair_amount_invoice" placeholder="e.g. 125" maxlength="10" size="4" value="0">
        <span class="add-on" style="color:#000000">Invoice File</span>
        <input type="file" title="browse file" id="repair_file_for_invoice" name="repair_file_for_invoice" class="file-inputs">
      </div>
    </div>
    <div class="control-group">
      <div class="input-prepend "> <span class="add-on" style="color:#000000">Payment Mode</span>
        <select id="repair_payment_mode" name="repair_payment_mode">
          <option value="-1">Select Mode</option>
          <option value="1">Cash</option>
          <option value="2">Cheque</option>
          <option value="3">Soft Payment</option>
        </select>
        <span class="add-on" me="repair_amount_label" id="repair_amount_label" style="color:#000000">Amount</span>
        <input type="text" name="repair_amount" id="repair_amount" value="0" placeholder="e.g. 12586" maxlength="10" size="4">
      </div>
    </div>
    <div class="control-group" name="repair_div_payment_cg" id="repair_div_payment_cg" style=" display: none;">
      <div class="input-prepend "> <span class="add-on cheque" name="repair_cheque_label" id="repair_cheque_label" style="color:#000000">Cheque No.</span>
        <input type="text" name="repair_cheque" class="cheque" id="repair_cheque" value="" placeholder="e.g. 12586" maxlength="10" style="color:#000000;">
        <span class="add-on soft-payment-bank" name="repair_bank_label" id="repair_bank_label" style="color:#000000;">Bank Name</span>
        <input type="text" name="repair_bank" class="soft-payment-bank" id="repair_bank" value="" placeholder="e.g. 12586" maxlength="10" style="color:#000000;">
        <span class="add-on soft-payment" name="repair_ifsc_label" id="repair_ifsc_label" style="color:#000000;">IFSC Code</span>
        <input type="text" name="repair_ifsc" class="soft-payment" id="repair_ifsc" value="" placeholder="e.g. 12586" maxlength="10" style="color:#000000;">
      </div>
    </div>
    <div class="control-group">
      <div class="input-prepend "> <span class="add-on" style="color:#000000">Dealer</span>
        <select id="repair_dealerid" name="repair_dealerid">
          <option value="">Select Dealer</option>
          <?php
                            $dealers = getdealersbytype(4);
                            if(isset($dealers))
                            {
                            foreach ($dealers as $dealer)
                            {
                            echo "<option value='$dealer->dealerid'>$dealer->name</option>";
                            }
                            }
                            ?>
        </select>
        <span class="add-on" style="color:#000000">Notes</span>
        <input type="text" name="repair_note" id="repair_note" placeholder="e.g. 12586" maxlength="10">
      </div>
    </div>
    <div id="parts_service_category">
      <div class="control-group">
        <div class="input-prepend "> <span class="add-on" style="color:#000000">Category</span>
          <select id="category_type" name="category_type" onchange="category_selector()">
            <option value="-1">Select Task</option>
            <option value="2">Repair</option>
            <option value="3">service</option>
          </select>
        </div>
      </div>
      <div id="category_handler"></div>
    </div>
    <div id="parts_task" style=" display: none;">
      <div class="control-group">
        <div class="input-prepend "> <span class="add-on" style="color:#000000" >parts</span>
          <select id="parts_select" name="parts_select">
            <option value="-1">Select parts</option>
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
          </select>
          <input type="button" id="add_parts" name="add_parts" value="+" onclick="add_items_container('#partlist_container','parts_select')">
          <div id="partlist_container"></div>
        </div>
      </div>
      <div class="control-group">
        <div class="input-prepend "> <span class="add-on" style="color:#000000">task</span>
          <select id="task_select" name="task_select">
            <option value="-1">Select Task</option>
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
          </select>
          <input type="button" id="add_task" name="add_task" value="+" onclick="add_items_container('#tasklist_container','task_select')">
          <div id="tasklist_container"></div>
        </div>
      </div>
    </div>
    </div>
    <div class="control-group">
      <div class="input-prepend ">
        <input type="button" onclick="addrepair();" value="Save" class="btn btn-success">
      </div>
    </div> </div>
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
<div class="modal hide" id="editbattery" style="top:34%;">
  <form class="form-horizontal" id="getbattery_edit">
    <fieldset>
    <div class="modal-header">
      <button class="close" data-dismiss="modal">×</button>
      <h4 style="color:#0679c0">Battery Replacement History</h4>
    </div>
    <div class="modal-body">
	<div  style="overflow-y:scroll; height:400px;">
    <fieldset>
    <div class="control-group">
      <div class="input-prepend "> <span class="add-on" style="color:#000000">Replacement Date</span>
        <input id="batt_replacement_date" name="batt_replacement_date" id="batt_replacement_date" type="text" value="<?php echo date('d-m-Y',$currentdate);?>" required/>
        <span class="add-on" style="color:#000000">Meter Reading</span>
        <input type="text" name="batt_meter_reading" id="batt_meter_reading" placeholder="e.g. 12586" maxlength="10">
      </div>
    </div>
    <div class="control-group">
      <div class="input-prepend "> <span class="add-on" style="color:#000000">Vehicle In Date</span>
        <input id="batt_vehicle_in_date" name="batt_vehicle_in_date" type="text" value="<?php echo date('d-m-Y',$currentdate);?>" required/>
        <span class="add-on" style="color:#000000">Vehicle Out Date</span>
        <input id="batt_vehicle_out_date" name="batt_vehicle_out_date" type="text" value="<?php echo date('d-m-Y',$currentdate);?>" required/>
      </div>
    </div>
    <div class="control-group">
      <div class="input-prepend "> <span class="add-on" style="color:#000000">Quotation amount</span>
        <input type="text" name="batt_amount_quote" id="batt_amount_quote" placeholder="e.g. 125" maxlength="10" size="4" value="0">
        <span class="add-on" style="color:#000000">Quotation File</span>
        <input type="file" title="browse file" id="batt_file_for_quote" name="batt_file_for_quote" class="file-inputs">
      </div>
    </div>
    <div class="control-group">
      <div class="input-prepend "> <span class="add-on" style="color:#000000">Invoice Date</span>
        <input id="batt_invoice_date" name="batt_invoice_date" type="text" value="<?php echo date('d-m-Y',$currentdate);?>" required/>
        <span class="add-on" style="color:#000000">Invoice No.</span>
        <input type="text" name="batt_invoice_no" id="batt_invoice_no" placeholder="e.g. 12586" maxlength="50">
        <input type="hidden" name="maintenance_edit_id" id="maintenance_edit_id">
      </div>
    </div>
    <div class="control-group">
      <div class="input-prepend "> <span class="add-on" style="color:#000000">Invoice amount</span>
        <input type="text" name="batt_amount_invoice" id="batt_amount_invoice" placeholder="e.g. 125" maxlength="10" size="4" value="0">
        <span class="add-on" style="color:#000000">Invoice File</span>
        <input type="file" title="browse file" id="batt_file_for_invoice" name="batt_file_for_invoice" class="file-inputs">
      </div>
    </div>
    <div class="control-group">
      <div class="input-prepend "> <span class="add-on" style="color:#000000">Payment Mode</span>
        <select id="batt_payment_mode" name="batt_payment_mode">
          <option value="-1">Select Mode</option>
          <option value="1">Cash</option>
          <option value="2">Cheque</option>
          <option value="3">Soft Payment</option>
        </select>
        <span class="add-on" me="batt_amount_label" id="batt_amount_label" style="color:#000000">Amount</span>
        <input type="text" name="batt_amount" id="batt_amount" value="0" placeholder="e.g. 12586" maxlength="10" size="4">
      </div>
    </div>
    <div class="control-group" name="batt_div_payment_cg" id="batt_div_payment_cg" style=" display: none;">
      <div class="input-prepend "> <span class="add-on cheque" name="batt_cheque_label" id="batt_cheque_label" style="color:#000000;">Cheque No.</span>
        <input type="text" name="batt_cheque" class="cheque" id="batt_cheque" value="" placeholder="e.g. 12586" maxlength="10" style="color:#000000;">
        <span class="add-on soft-payment-bank" name="batt_bank_label" id="batt_bank_label" style="color:#000000;">Bank Name</span>
        <input type="text" name="batt_bank" id="batt_bank" class="soft-payment-bank" value="" placeholder="e.g. 12586" maxlength="10" style="color:#000000;">
        <span class="add-on soft-payment" name="batt_ifsc_label" id="batt_ifsc_label" style="color:#000000;">IFSC Code</span>
        <input type="text" name="batt_ifsc" class="soft-payment" id="batt_ifsc" value="" placeholder="e.g. 12586" maxlength="10" style="color:#000000;">
      </div>
    </div>
    <div class="control-group">
      <div class="input-prepend "> <span class="add-on" style="color:#000000">Dealer</span>
        <select id="batt_dealerid" name="batt_dealerid">
          <option value="">Select Dealer</option>
          <?php
                            $dealers = getdealersbytype(1);
                            if(isset($dealers))
                            {
                            foreach ($dealers as $dealer)
                            {
                            echo "<option value='$dealer->dealerid'>$dealer->name</option>";
                            }
                            }
                            ?>
        </select>
        <span class="add-on" style="color:#000000">Notes</span>
        <input type="text" name="batt_note" id="batt_note" placeholder="e.g. 12586" maxlength="10">
      </div>
    </div>
    </div>
	</fieldset>
    <div class="control-group">
      <div class="input-prepend  formSep " style="color:#000000;"> View Battery Quotation </div>
      <br/>
      <br/>
      <div class="input-prepend" id="battery_quotefile_view"> </div>
      <br/>
      <div class="input-prepend" id="battery_invoicefile_view"> </div>
    </div>
    <div class="control-group">
      <div class="input-prepend ">
        <input type="button" id="edit_save_battery" onclick="" value="Save" class="btn btn-success">
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
	<div  style="overflow-y:scroll; height:400px;">
    <fieldset>
    <div class="control-group">
      <div class="input-prepend "> <span class="add-on" style="color:#000000">Replacement Date</span>
        <input id="tyre_replacement_date" name="tyre_replacement_date" type="text" value="<?php echo date('d-m-Y',$currentdate);?>" required/>
        <span class="add-on" style="color:#000000">Meter Reading</span>
        <input type="text" name="tyre_meter_reading" id="tyre_meter_reading" placeholder="e.g. 12586" maxlength="10">
      </div>
    </div>
    <div class="control-group">
      <div class="input-prepend "> <span class="add-on" style="color:#000000">Vehicle In Date</span>
        <input id="tyre_vehicle_in_date" name="tyre_vehicle_in_date" type="text" value="<?php echo date('d-m-Y',$currentdate);?>" required/>
        <span class="add-on" style="color:#000000">Vehicle Out Date</span>
        <input id="tyre_vehicle_out_date" name="tyre_vehicle_out_date" type="text" value="<?php echo date('d-m-Y',$currentdate);?>" required/>
      </div>
    </div>
    <div class="control-group">
      <div class="input-prepend "> <span class="add-on" style="color:#000000">Quotation amount</span>
        <input type="text" name="tyre_amount_quote" id="tyre_amount_quote" placeholder="e.g. 125" maxlength="10" size="4" value="0">
        <span class="add-on" style="color:#000000">Quotation File</span>
        <input type="file" title="browse file" id="tyre_file_for_quote" name="tyre_file_for_quote" class="file-inputs">
      </div>
    </div>
    <div class="control-group">
      <div class="input-prepend "> <span class="add-on" style="color:#000000">Invoice Date</span>
        <input id="tyre_invoice_date" name="tyre_invoice_date" type="text" value="<?php echo date('d-m-Y',$currentdate);?>" required/>
        <span class="add-on" style="color:#000000">Invoice No.</span>
        <input type="text" name="tyre_invoice_no" id="tyre_invoice_no" placeholder="e.g. 12586" maxlength="50">
      </div>
    </div>
    <div class="control-group">
      <div class="input-prepend "> <span class="add-on" style="color:#000000">Invoice amount</span>
        <input type="text" name="tyre_amount_invoice" id="tyre_amount_invoice" placeholder="e.g. 125" maxlength="10" size="4" value="0">
        <span class="add-on" style="color:#000000">Invoice File</span>
        <input type="file" title="browse file" id="tyre_file_for_invoice" name="tyre_file_for_invoice" class="file-inputs">
      </div>
    </div>
    <div class="control-group">
      <div class="input-prepend "> <span class="add-on" style="color:#000000">Payment Mode</span>
        <select id="tyre_payment_mode" name="tyre_payment_mode">
          <option value="-1">Select Mode</option>
          <option value="1">Cash</option>
          <option value="2">Cheque</option>
          <option value="3">Soft Payment</option>
        </select>
        <span class="add-on" me="tyre_amount_label" id="tyre_amount_label" style="color:#000000">Amount</span>
        <input type="text" name="tyre_amount" id="tyre_amount" value="0" placeholder="e.g. 12586" maxlength="10" size="4">
      </div>
    </div>
    <div class="control-group" name="tyre_div_payment_cg" id="tyre_div_payment_cg" style=" display: none;">
      <div class="input-prepend "> <span class="add-on cheque" name="tyre_cheque_label" id="tyre_cheque_label" style="color:#000000;">Cheque No.</span>
        <input type="text" name="tyre_cheque" class="cheque" id="tyre_cheque" value="" placeholder="e.g. 12586" maxlength="10" style="color:#000000;">
        <span class="add-on soft-payment-bank" name="tyre_bank_label" id="tyre_bank_label" style="color:#000000;">Bank Name</span>
        <input type="text" name="tyre_bank" id="tyre_bank" class="soft-payment-bank" value="" placeholder="e.g. 12586" maxlength="10" style="color:#000000;">
        <span class="add-on soft-payment" name="tyre_ifsc_label" id="tyre_ifsc_label" style="color:#000000;">IFSC Code</span>
        <input type="text" name="tyre_ifsc" class="soft-payment" id="tyre_ifsc" value="" placeholder="e.g. 12586" maxlength="10" style="color:#000000;">
      </div>
    </div>
    <div class="control-group">
      <div class="input-prepend "> <span class="add-on" style="color:#000000">Dealer</span>
        <select id="tyre_dealerid" name="tyre_dealerid">
          <option value="">Select Dealer</option>
          <?php
                            $dealers = getdealersbytype(2);
                            if(isset($dealers))
                            {
                            foreach ($dealers as $dealer)
                            {
                            echo "<option value='$dealer->dealerid'>$dealer->name</option>";
                            }
                            }
                            ?>
        </select>
        <span class="add-on" style="color:#000000">Notes</span>
        <input type="text" name="tyre_note" id="tyre_note" placeholder="e.g. 12586" maxlength="10">
      </div>
    </div>
    <div id="tyre_fields">
      <div class="control-group tyre_field"  >
        <div class="input-prepend "> <span class="add-on" style="color:#000000" >Tyre type</span>
          <select id="edit_tyre_type" name="edit_tyre_type">
            <option value="">Select type</option>
            <option value="1">Right front</option>
            <option value="2">Right Back</option>
            <option value="3">Left front</option>
            <option value="4">Left Back</option>
            <option value="5">Stepney</option>
          </select>
          <input type="button" id="add_ttype" name="add_ttype" value="+" onclick="add_items_container('#edit_tyrelist_container','edit_tyre_type')">
          <br/>
          <div id="edit_tyrelist_container"></div>
        </div>
      </div>
    </div>
    </div>
    <div class="control-group">
      <div class="input-prepend  formSep " style="color:#000000;"> View Tyre Quotation </div>
      <br/>
      <br/>
      <div class="input-prepend" id="tyre_quotefile_view"> </div>
      <br/>
      <div class="input-prepend" id="tyre_invoicefile_view"> </div>
    </div>
    <div class="control-group">
      <div class="input-prepend ">
        <input type="button" id="edit_save_tyre" onclick="" value="Save" class="btn btn-success">
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
	<div  style="overflow-y:scroll; height:400px;">
    <fieldset>
    <div class="control-group">
      <div class="input-prepend "> <span class="add-on" style="color:#000000">Replacement Date</span>
        <input id="repair_replacement_date" name="repair_replacement_date" type="text" value="<?php echo date('d-m-Y',$currentdate);?>" required/>
        <span class="add-on" style="color:#000000">Meter Reading</span>
        <input type="text" name="repair_meter_reading" id="repair_meter_reading" placeholder="e.g. 12586" maxlength="10">
      </div>
    </div>
    <div class="control-group">
      <div class="input-prepend "> <span class="add-on" style="color:#000000">Vehicle In Date</span>
        <input id="repair_vehicle_in_date" name="repair_vehicle_in_date" type="text" value="<?php echo date('d-m-Y',$currentdate);?>" required/>
        <span class="add-on" style="color:#000000">Vehicle Out Date</span>
        <input id="repair_vehicle_out_date" name="repair_vehicle_out_date" type="text" value="<?php echo date('d-m-Y',$currentdate);?>" required/>
      </div>
    </div>
    <div class="control-group">
      <div class="input-prepend "> <span class="add-on" style="color:#000000">Quotation amount</span>
        <input type="text" name="repair_amount_quote" id="repair_amount_quote" placeholder="e.g. 125" maxlength="10" size="4" value="0">
        <span class="add-on" style="color:#000000">Quotation File</span>
        <input type="file" title="browse file" id="repair_file_for_quote" name="repair_file_for_quote" class="file-inputs">
      </div>
    </div>
    <div class="control-group">
      <div class="input-prepend "> <span class="add-on" style="color:#000000">Invoice Date</span>
        <input id="repair_invoice_date" name="repair_invoice_date" type="text" value="<?php echo date('d-m-Y',$currentdate);?>" required/>
        <span class="add-on" style="color:#000000">Invoice No.</span>
        <input type="text" name="repair_invoice_no" id="repair_invoice_no" placeholder="e.g. 12586" maxlength="50">
      </div>
    </div>
    <div class="control-group">
      <div class="input-prepend "> <span class="add-on" style="color:#000000">Invoice amount</span>
        <input type="text" name="repair_amount_invoice" id="repair_amount_invoice" placeholder="e.g. 125" maxlength="10" size="4" value="0">
        <span class="add-on" style="color:#000000">Invoice File</span>
        <input type="file" title="browse file" id="repair_file_for_invoice" name="repair_file_for_invoice" class="file-inputs">
      </div>
    </div>
    <div class="control-group">
      <div class="input-prepend "> <span class="add-on" style="color:#000000">Payment Mode</span>
        <select id="repair_payment_mode" name="repair_payment_mode">
          <option value="-1">Select Mode</option>
          <option value="1">Cash</option>
          <option value="2">Cheque</option>
          <option value="3">Soft Payment</option>
        </select>
        <span class="add-on" me="repair_amount_label" id="repair_amount_label" style="color:#000000">Amount</span>
        <input type="text" name="repair_amount" id="repair_amount" value="0" placeholder="e.g. 12586" maxlength="10" size="4">
      </div>
    </div>
    <div class="control-group" name="repair_div_payment_cg" id="repair_div_payment_cg" style=" display: none;">
      <div class="input-prepend "> <span class="add-on cheque" name="repair_cheque_label" id="repair_cheque_label" style="color:#000000;">Cheque No.</span>
        <input type="text" name="repair_cheque" class="cheque" id="repair_cheque" value="" placeholder="e.g. 12586" maxlength="10" style="color:#000000;">
        <span class="add-on soft-payment-bank" name="repair_bank_label" id="repair_bank_label" style="color:#000000;">Bank Name</span>
        <input type="text" name="repair_bank" id="repair_bank" class="soft-payment-bank" value="" placeholder="e.g. 12586" maxlength="10" style="color:#000000;">
        <span class="add-on soft-payment" name="repair_ifsc_label" id="repair_ifsc_label" style="color:#000000;">IFSC Code</span>
        <input type="text" name="repair_ifsc" class="soft-payment" id="repair_ifsc" value="" placeholder="e.g. 12586" maxlength="10" style="color:#000000;">
      </div>
    </div>
    <div class="control-group">
      <div class="input-prepend "> <span class="add-on" style="color:#000000">Dealer</span>
        <select id="tyre_dealerid" name="repair_dealerid">
          <option value="">Select Dealer</option>
          <?php
                            $dealers = getdealersbytype(4);
                            if(isset($dealers))
                            {
                            foreach ($dealers as $dealer)
                            {
                            echo "<option value='$dealer->dealerid'>$dealer->name</option>";
                            }
                            }
                            ?>
        </select>
        <span class="add-on" style="color:#000000">Notes</span>
        <input type="text" name="repair_note" id="repair_note" placeholder="e.g. 12586" maxlength="10">
      </div>
    </div>
    <div id="parts_service_category">
      <div class="control-group">
        <div class="input-prepend "> <span class="add-on" style="color:#000000">Category</span>
          <select id="category_type_edit" name="category_type_edit" onchange="category_selector_edit()">
            <option value="-1">Select Task</option>
            <option value="2">Repair</option>
            <option value="3">service</option>
          </select>
        </div>
      </div>
      <div id="category_handler_edit"></div>
    </div>
    </div>
	</div>
	
    <div class="control-group">
      <div class="input-prepend  formSep " style="color:#000000;"> View Service / Repair Quotation </div>
      <br/>
      <br/>
      <div class="input-prepend" id="repair_quotefile_view"> </div>
      <br/>
      <div class="input-prepend" id="repair_invoicefile_view"> </div>
    </div>
    <div class="control-group">
      <div class="input-prepend ">
        <input type="button" id="edit_save_repair" onclick="" value="Save" class="btn btn-success">
      </div>
    </div>
    </fieldset>
  </form>
</div>
<div class="modal hide" id="addaccident" style="width: 41%;">
    <form method="POST" id="getaccident_approval">        
<fieldset>
    <div class="modal-header">
         <input type="hidden" id="vehicle_id1" value=''>
         <input type="hidden" id="category_id1" value=''>
        <button class="close" data-dismiss="modal">×</button>
        <h4 style="color:#0679c0" id="accident_head_fortransac"></h4>
    </div>
	 <div class="modal-body">
	 <div  style="overflow-y:scroll; height:400px;">
    <ul class="nav nav-tabs" id="myTab">
                <li class="active"><a href="#acc_form" data-toggle="tab">Event Entry</a></li>
                <li><a href="#file_upload" data-toggle="tab">File Upload</a></li>
                
                </ul>
    <div class="tab-content">
        <div class="tab-pane active" id="acc_form">
                            <div class="control-group">
                            <div class="input-prepend ">
                            <span class="add-on" style="color:#000000">Accident Date</span><input id="acc_Date" name="acc_Date" type="text" class="input-small" value="" />
                            <span class="add-on" style="color:#000000">Accident Time</span><input id="STime" name="STime" type="text" class="input-mini" />
                            </div>
                            </div>

                            <div class="control-group">
                            <div class="input-prepend ">
                            <span class="add-on" style="color:#000000">Accident Location</span><input type="text" name="acc_location" id="acc_location" placeholder="e.g. Mumbai" />
                            <span class="add-on" style="color:#000000">Third Party Injury / Property Damage</span>&nbsp;<input type="radio" name="thirdparty" id="thirdparty1" value="yes" /><span style="color:#000000">Yes</span>
                                       <input type="radio" name="thirdparty" id="thirdparty2" checked value="no" /> <span style="color:#000000">No</span>
                            </div>
                            </div>

                            <div class="control-group">
                            <div class="input-prepend ">
                            <span class="add-on" style="color:#000000">Accident Description</span><input type="text" name="acc_desc" id="acc_desc" placeholder="e.g. description" />
                            <span class="add-on" style="color:#000000">Driver Name</span><input type="text" name="driver_name" id="driver_name" placeholder="e.g. driver name" />
                            </div>
                            </div>

                            <div class="control-group">
                            <div class="input-prepend ">
                            <span class="add-on" style="color:#000000">Validity Period of Driver License</span> <span class="add-on" style="color:#000000">From :</span><input id="val_from_Date" name="val_from_Date" type="text" class="input-small" value="" />
                            <span class="add-on" style="color:#000000">To :</span><input id="val_to_Date" name="val_to_Date" type="text" class="input-small" value="" />
                            </div>
                            </div>

                            <div class="control-group formSep">
                            <div class="input-prepend ">
                            <span class="add-on" style="color:#000000">Type of License</span><input id="licence_type" name="licence_type" type="text" class="input-small" value="" />
                            <span class="add-on" style="color:#000000">Name and Location of Workshop</span><input id="add_workshop" name="add_workshop" type="text" class="input-small" value="" />
                            </div>
                            </div>
                                    <div class="control-group" id="insurance_div">
                                    <div class="input-prepend formSep">
                                        <span class="add-on" style="color:#000000" id="insurance_status"></span>
                                    </div>	
                                    </div>
                                    <div class="control-group" id="radio_show" style="display: none; ">
                                    <div class="input-prepend">
                                       <span class="add-on" style="color:#000000">Value of Insurance</span><input type="text" name="insurance_value" id="insurance_value" class="input-small" value="">
                                       <span class="add-on" style="color:#000000">Premium</span><input type="text" name="premium_value" id="premium_value" class="input-small" value="">
                                       <span class="add-on" style="color:#000000">Start Date</span><input id="StartDate" name="StartDate" type="text" class="input-small" value="">
                                       </div>	
                                        <br>
                                    <div class="input-prepend">
                                       <span class="add-on" style="color:#000000">End Date</span><input id="EndDate" name="EndDate" type="text" class="input-small" value="">
                                        <span class="add-on" style="color:#000000">Amount</span><input type="text" name="ins_amount" id="ins_amount" class="input-small" value="">
                                       <span class="add-on" style="color:#000000">Notes</span><input type="text" name="ins_notes" id="ins_notes" value="">

                                    </div><br>
                                    <div class="input-prepend formSep">
                                        <span class="add-on" style="color:#000000">Insurance Company</span> <select name="edit_insurance_company" id="edit_insurance_company" style="width: 25%;">
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
                                       <span class="add-on" style="color:#000000">Nearest Place Of Claim</span> <input type="text" name="near_place" placeholder="e.g. mumbai, delhi" id="near_place" value="">
                                    </div>
                                    </div>
                                    <div class="control-group">
                                    <div class="input-prepend ">
                                    <span class="add-on" style="color:#000000">Send report to</span>
                                    <input type="text" name="send_report" placeholder="email address" class="input-small" id="send_report" />
                                    <span class="add-on" style="color:#000000">Amount of loss</span>
                                    <input type="text" name="loss_amount" class="input-small" id="loss_amount" />
                                    <span class="add-on" style="color:#000000">Settlement Amount</span>
                                    <input type="text" name="sett_amount" class="input-small" id="sett_amount" />
                                    </div>
                                    </div>
                                    <div class="control-group">
                                    <div class="input-prepend ">
                                    <span class="add-on" style="color:#000000">Actual Amount</span>
                                    <input type="text" name="actual_amount" class="input-small" id="actual_amount" />
                                    <span class="add-on" style="color:#000000">Amount Spent by Mahindra</span>
                                    <input type="text" name="mahindra_amount" class="input-small" id="mahindra_amount" readonly />
                                    </div>
                                    </div>

                            <div class="control-group">
                            <div class="input-prepend ">
                            <input type="button" onclick="push_transaction_accident();" value="Save" class="btn btn-success">
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
		</div>
    </fieldset>
</form>
</div>