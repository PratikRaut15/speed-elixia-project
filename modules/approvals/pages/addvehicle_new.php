<div class="tabbable tabs-left" style="width: 70%; clear: both">
    <div class="" style="float: right;">
    <input type="button" id='sendapproval' onclick="sendapproval();" value="Send For Approval" disabled="disabled" class="btn btn-success">
    <input type="hidden" id="vehicle_id" value=''>
    <input type="hidden" id="general_complete" value=''>
    <input type="hidden" id="desc_complete" value=''>
    <input type="hidden" id="tax_complete" value=''>
    <input type="hidden" id="insurance_complete" value=''>
    <input type="hidden" id="maintenance_complete" value=''>
    <input type="hidden" id="capitalization_complete" value=''>
    <input type="hidden" id="papers_complete" value=''>
    <input type="hidden" id="geo_complete" value=''> <br>
</div>
<br></br>
        <ul class="nav nav-tabs">
                <li class="active abc" style="display: none;"><a href="#tab_l1" data-toggle="tab" disabled>General</a></li>
                <li class="abc" style="display: none;"><a href="#tab_l2" data-toggle="tab">Description</a></li>
                <li class="abc" style="display: none;"><a href="#tab_l3" onclick="gettax();" data-toggle="tab">Tax / Registration</a></li>
                <li class="abc" style="display: none;"><a href="#tab_l4" data-toggle="tab">Insurance</a></li>
                <li class="abc" style="display: none;"><a href="#tab_l5" onclick='getbattery();' data-toggle="tab">Maintenance</a></li>
                <li class="abc" style="display: none;"><a href="#tab_l6" data-toggle="tab">Capitalization</a></li>
                <li class="abc" style="display: none;"><a href="#tab_l7" data-toggle="tab">Papers</a></li>
                <li class="abc" style="display: none;"><a href="#tab_l8" data-toggle="tab">Geo Tag</a></li>
        </ul>
        <div class="tab-content">
                <div class="tab-pane active" id="tab_l1">
                    <fieldset>
                        <div class="control-group">
                            <form method="POST" id="general">
            <span id="general_success" style="display:none;">General data added successfully, please fill up the vehicle description.</span>
            <span id="vehicle_error" style="display:none;">Please enter vehicle number.</span>
            <span id="make_error" style="display:none;">Please select make.</span>
                                <div class="input-prepend ">
                                <span class="add-on">Vehicle No. *</span><input type="text" name="vehicle_no" id="vehicle_no" placeholder="Vehicle No" autofocus maxlength="20">
                                <span class="add-on">Kind</span><select id="kind" name="kind">
                                                                        <option value="">Select Kind</option>
                                                                        <option value='Bus'>Bus</option>
                                                                        <option value='Truck'>Truck</option>
                                                                        <option value='Car'>Car</option>
                                                                </select>
                                <span class="add-on">Make</span><select id="make" name="make" onchange="getmodel()">
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
                                <span class="add-on">Model</span><select id="model" name="model">
                                                                        <option value="">Select Model</option>
                                              </select>
                                </div>
                                    <br>
                                <div class="input-prepend formSep">
                                <span class="add-on">Year of Manufacture</span><input type="text" name="yearofman" id="yearofman" placeholder="e.g. 2011" maxlength="10">
                                <span class="add-on">Purchase Date</span><input id="PDate" name="PDate" type="text" class="input-small" value="" required="">
                                </div>
                                <div class="input-prepend formSep ">
                                    <?php echo($_SESSION['group']); ?> Details
                                </div>	
                                <div class="input-prepend formSep ">
                                <span class="add-on"><?php echo($_SESSION['group']); ?></span><select id="branchid" name="branchid" onchange="showbranch()">
                                                                        <option value="">Select <?php echo($_SESSION['group']); ?></option>
                                                                        <?php
                                                                        $groups = getgroups();
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
                                <div class="input-prepend formSep ">
                                    Miscellaneous
                                </div>	
                                <div class="input-prepend formSep ">
                                <span class="add-on">Start Meter Reading</span><input type="text" name="start_meter" id="start_meter" placeholder="e.g. 12586" maxlength="10">
                                <span class="add-on">Overspeed Limit</span><input type="text" name="overspeed" id="overspeed" placeholder="Overspeed Limit" maxlength="20">
                                <span class="add-on">Fuel Type</span><select id="fueltype" name="fueltype">
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
            <span id="description_success" style="display:none;">Description added successfully, please fill up the vehicle tax details.</span>
                                <div class="input-prepend">
                                <span class="add-on">Engine No.</span><input type="text" name="engineno" id="engineno" placeholder="engine no." maxlength="10">
                                <span class="add-on">Chasis No.</span><input type="text" name="chasisno" id="chasisno" placeholder="chasis no." maxlength="10">
                                </div>
                                    <br>
                                <div class="input-prepend formSep ">
                                <span class="add-on">Vehicle Purpose</span><select id="veh_purpose" name="veh_purpose">
                                                                        <option value="">Select Purpose</option>
                                                                        <option value='1'>Employee CTC</option>
                                                                        <option value='2'>Branch Vehicle</option>
                                                                </select>
                                <span class="add-on">Vehicle Type</span><select id="veh_type" name="veh_type">
                                                                        <option value="">Select Vehicle Type</option>
                                                                        <option value='1'>New</option>
                                                                        <option value='2'>Reposed</option>
                                                                        <option value='3'>Employee</option>
                                              </select>
                                </div>
                                <div class="input-prepend formSep ">
                                    Registration Details
                                </div>	
                                <div class="input-prepend formSep ">
                                <span class="add-on">Registration No.</span><input type="text" name="registrationno" id="registrationno" placeholder="registration no." maxlength="10">
<!--                                <span class="add-on">Registration Date</span><input id="registrationdate" name="registrationdate" type="text" class="input-small" value="24-04-2014" required="">-->
                                </div>
                                <div class="input-prepend formSep ">
                                    Dealer Details
                                </div>	
                                <div class="input-prepend formSep ">
                                <span class="add-on">Dealer Name</span><select id="dealerid" name="dealerid" onchange="showdealer()">
                                                                        <option value="">Select Dealer</option>
                                                                        <?php
                                                                        $dealers = getdealers();
                                                                        if(isset($dealers))
                                                                        {
                                                                            foreach ($dealers as $dealer)
                                                                            {
                                                                            echo "<option value='$dealer->dealerid'>$dealer->name</option>";
                                                                            }
                                                                        }
                                                                        ?>
                                                                </select>
                                <span class="add-on">Code</span><input type="text" name="code_dealer" readonly id="code_dealer">
                                <span class="add-on">Invoice No.</span><input type="text" name="invoiceno" id="invoiceno" placeholder="12586" maxlength="10">
                                <span class="add-on">Invoice Date</span><input id="invoice_date" name="invoice_date" type="text" class="input-small" value="24-04-2014" required="">
                                </div>	

                                <input type="button" onclick="adddescription();" value="Save" class="btn btn-success">
                            </form>
			</div>
                    </fieldset>
                </div>
                <div class="tab-pane" id="tab_l3">
                    <fieldset>
                        <div class="control-group">
                    <span id="tax_success" style="display:none;">Tax details added successfully, please fill up the vehicle insurance details.</span>
                                <div class="input-prepend formSep ">
                                    Vehicle Tax<a href="#addtax" data-toggle="modal" style="width: 16px; height:16px; vertical-align:middle; float: right;"><img class="tooltip-top" src="../../images/show.png" style="width: 16px; height:16px; vertical-align:middle; float: right;" original-title="Click To Add Tax"></a>
                                </div>	
                                <div class="input-prepend">
                                    <table  class="table  table-bordered table-striped dTableR dataTable"  style=" width:70%">
                                        <thead>

                                        <tr>

                                            <th>From Date</th>
                                            <th>To Date</th>
                                            <th>Amount</th>
                                            <th>Type</th>
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
                        <span id="insurance_success" style="display:none;">Insurance details added successfully, please fill up the maintenance detail.</span>
                        <div class="control-group">
			<div class="input-prepend formSep">
                           <span class="add-on">Do you have insurance?</span>
                           <input type="radio" name="insurance" id="insurance1" value="yes"> Yes
                           <input type="radio" name="insurance" id="insurance2" checked value="no"> No
                        </div>	
                        </div>
                        <div class="control-group">
                        <div id="radio_show" style="display: none;">
			<div class="input-prepend">
                           <span class="add-on">Value of Insurance</span><input type="text" name="insurance_value" id="insurance_value" value="">
                           <span class="add-on">Premium</span><input type="text" name="premium_value" id="premium_value" value="">
                           <span class="add-on">Start Date</span><input id="StartDate" name="StartDate" type="text" class="input-small" value="24-04-2014" required="">
                           </div>	
                            <br>
			<div class="input-prepend">
                           <span class="add-on">End Date</span><input id="EndDate" name="EndDate" type="text" class="input-small" value="24-04-2014" required="">
                            <span class="add-on">Amount</span><input type="text" name="ins_amount" id="ins_amount" value="">
                           <span class="add-on">Notes</span><input type="text" name="ins_notes" id="ins_notes" value="">
                           
                        </div><br>
			<div class="input-prepend formSep">
                            <span class="add-on">Insurance Company</span> <select name="insurance_company">
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
                           <span class="add-on">Nearest Place Of Claim</span> <input type="text" name="near_place" placeholder="e.g. mumbai, delhi" id="near_place" value="">
                        </div>
			
                        </div>
			</div>
                        <input type="button" onclick="addinsurance();" value="Save" class="btn btn-success">
                        </form>
                    </fieldset>
                </div>
                <div class="tab-pane" id="tab_l5"style="overflow-y: scroll;height: 295px;">
                    <fieldset>
                        <div class="control-group">
			<div class="input-prepend formSep ">
                            Battery Replacement<a href="#addbattery_approval" data-toggle="modal" style="width: 16px; height:16px; vertical-align:middle; float: right;"><img class="tooltip-top" src="../../images/show.png" style="width: 16px; height:16px; vertical-align:middle; float: right;" original-title="Click To Add Tax"></a>
                        </div>	
			<div class="input-prepend formSep">
                            <table  class="table  table-bordered table-striped dTableR dataTable"  style=" width:70%">
                                <thead>

                                <tr>

                                    <th>Meter Reading</th>
                                    <th>Dealer</th>
                                    <th>Status</th>
                                    <th colspan="2">Options</th>
                                </tr>
                                </thead>
                                <tbody id="battery_body">
                                </tbody>
                            </table>
                        </div>
			<div class="input-prepend formSep ">
                            Tyre<a href="#addtyre" data-toggle="modal" style="width: 16px; height:16px; vertical-align:middle; float: right;"><img class="tooltip-top" src="../../images/show.png" style="width: 16px; height:16px; vertical-align:middle; float: right;" original-title="Click To Add Tax"></a>
                        </div>	
			<div class="input-prepend">
                            <table  class="table  table-bordered table-striped dTableR dataTable"  style=" width:70%">
                                <thead>

                                <tr>

                                    <th>Replacement Date</th>
                                    <th>Meter Reading</th>
                                    <th>Dealer</th>
                                    <th colspan="2">Options</th>
                                </tr>
                                </thead>
                                <tbody id="tyre_body">
                                </tbody>
                            </table>
                        </div>
			<div class="input-prepend formSep ">
                            Service / Repair<a href="#addrepair" data-toggle="modal" style="width: 16px; height:16px; vertical-align:middle; float: right;"><img class="tooltip-top" src="../../images/show.png" style="width: 16px; height:16px; vertical-align:middle; float: right;" original-title="Click To Add Tax"></a>
                        </div>	
			<div class="input-prepend ">
                            <table  class="table  table-bordered table-striped dTableR dataTable"  style=" width:70%">
                                <thead>

                                <tr>

                                    <th>Replacement Date</th>
                                    <th>Meter Reading</th>
                                    <th>Dealer</th>
                                    <th colspan="2">Options</th>
                                </tr>
                                </thead>
                                <tbody id="service_body">
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
			<div class="input-prepend formSep ">
                            Capitalization
                        </div>	
                        <div class="input-prepend">
                        <span class="add-on">Address</span> <input type="text" name="cap_address" id="cap_address">
                        <span class="add-on">Cost</span> <input type="text" name="cap_cost" id="cap_cost">
                        </div>	
                            <br>
			<div class="input-prepend"><span class="add-on">Date</span><input id="cap_EDate" name="cap_EDate" type="text" class="input-small" value="24-04-2014" required="">
                        <span class="add-on">% (Maintenance / Capitalization)</span> <input type="text" name="cap_code" readonly id="cap_code">
                        </div>
                        <input type="button" onclick="addcap();" value="Save" class="btn btn-success">	
                        </form>
			</div>
                    </fieldset>
                </div>
                <div class="tab-pane" id="tab_l7">
                    <fieldset>
                        <form method="POST" id="papers_form" enctype="multipart/form-data">
                            <span id="papers_success" style="display:none;">Papers uploaded successfully, please add geotag.</span>
                            <div class="control-group" style="text-align: left; margin-left: 10px;">
                            <div class="input-prepend">
                            <span class="add-on">PUC</span> <input type="file" name="puc" id="puc">
                            <input type="button" id="upload_puc" onclick="uploadFilesPUC();" value="Upload" class="btn btn-success" />
                            </div>
                            </div>
                            <div class="control-group" style="text-align: left; margin-left: 10px;">
                            <div class="input-prepend">
                            <span class="add-on">Registration</span> <input type="file" name="reg" id="reg">
                            <input type="button" id="upload_reg" onclick="uploadFilesREG();" value="Upload" class="btn btn-success" />
                            </div>	
                            </div>
                            <div class="control-group" style="text-align: left; margin-left: 10px;">
                            <div class="input-prepend">
                            <span class="add-on">Insurance</span> <input type="file" name="insurance" id="insurance">
                            <input type="button" id="upload_ins" onclick="uploadFilesINS();" value="Upload" class="btn btn-success" />
                            </div>
                            </div>
                            <div class="control-group" style="text-align: left; margin-left: 10px;">
                            <div class="input-prepend">
                            <span class="add-on">Others</span> <input type="text" name="other" id="other"> <input type="file" name="other_file" id="other_file">
                            <input type="button" id="upload_other" onclick="uploadFilesOTHER();" value="Upload" class="btn btn-success" />
                            </div>
                            </div>
                            <div class="control-group" style="text-align: left; margin-left: 10px;">
                            <div class="input-prepend">
                            <span class="add-on">Others</span> <input type="text" name="other1" id="other1"> <input type="file" name="other1_file" id="other1_file">
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
			<div class="input-prepend formSep ">
                            <span class="add-on">Checkpoint</span><select id="chkid" name="chkid" onchange="addchk()">
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
                        <div class="input-prepend formSep" style="height: 70px;" >
                            <div id="checkpoint_list">
                                
                            </div>
                        </div>	
                            <br>
			<div class="input-prepend formSep " >
                            <span class="add-on">Fence </span><select id="fenceid" name="fenceid" onchange="addfence()">
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
                        <input type="button" value="Add All Fences" class="btn  btn-primary" onclick="addallfence()"></div>	
                        <div class="input-prepend formSep" style="height: 70px;" >
                            <div id="fence_list">
                                
                            </div>
                        </div>
                        <input type="button" onclick="addgeo();" value="Save" class="btn btn-success">
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
            <span id="amount" name="amount" style="display: none;">Please Check The Amount</span>
                <span id="from_error" name="from_error" style="display: none;">Please Select Start Date</span>
                <span id="to_error" name="to_error" style="display: none;">Please Select End Date</span>
            <fieldset>
                <div class="control-group">
                <div class="input-prepend ">
                <span class="add-on" style="color:#000000">From Date</span>
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
                <div class="input-prepend ">
                <span class="add-on" style="color:#000000">Type</span> <select name="tax_type" id="tax_type">
                                                            <option value="">Select Tax Type</option>
                                                            <option value="1">Road Tax</option>
                                                            <option value="2">Registration Tax</option>
                                                    </select>
                <span class="add-on" style="color:#000000">Amount</span>
                <input type="text" name="amount" id="amount">
                </div>
                </div>
                <div class="control-group">
                <div class="input-prepend ">
                <input type="button" onclick="addtax();" value="Save" class="btn btn-success">
                </div>
                </div>
	</fieldset>
    </div>
</fieldset>
</form>
</div>
<div class="modal hide" id="addbattery">
<form class="form-horizontal" id="getbattery">
<fieldset>
    <div class="modal-header">
        <button class="close" data-dismiss="modal">×</button>
        <h4 style="color:#0679c0">Add Battery</h4>
    </div>
    <div class="modal-body">
            <span id="amount" name="amount" style="display: none;">Please Check The Amount</span>
                <span id="sdate" name="sdate" style="display: none;">Please Select Start Date</span>
                <span id="edate" name="edate" style="display: none;">Please Select End Date</span>
            <fieldset>
                <div class="control-group">
                <div class="input-prepend ">
                <span class="add-on" style="color:#000000">Replacement Date</span>
            <input id="SDate" name="date" type="text" value="<?php echo date('d-m-Y',$currentdate);?>" required/>
                </div>
                </div>
                <div class="control-group">
                <div class="input-prepend ">
            <span class="add-on" style="color:#000000">Meter Reading</span><input type="text" name="year" id="year" placeholder="e.g. 12586" maxlength="10">
                </div>
                </div>
                <div class="control-group">
                <div class="input-prepend ">
                <span class="add-on" style="color:#000000">Vehicle In Date</span>
            <input id="EDate" name="date" type="text" value="<?php echo date('d-m-Y',$currentdate);?>" required/>
                </div>
                </div>
                <div class="control-group">
                <div class="input-prepend ">
                <span class="add-on" style="color:#000000">Vehicle Out Date</span>
            <input id="EDate" name="date" type="text" value="<?php echo date('d-m-Y',$currentdate);?>" required/>
                </div>
                </div>
                <div class="control-group">
                <div class="input-prepend ">
                <span class="add-on" style="color:#000000">Dealer</span>
                    <select id="dealer" name="dealer">
                            <option value="">Select Dealer</option>
                            <?php
                            $groups = getgroups();
                            if(isset($groups))
                            {
                            foreach ($groups as $group)
                            {
                            echo "<option value='$group->groupid'>$group->groupname</option>";
                            }
                            }
                            ?>
                    </select>
                </div>
                </div>
                <div class="control-group">
                <div class="input-prepend ">
                <span class="add-on" style="color:#000000">Invoice Date</span>
                <input id="EDate" name="date" type="text" value="<?php echo date('d-m-Y',$currentdate);?>" required/>
                </div>
                </div>
                <div class="control-group">
                <div class="input-prepend ">
                <span class="add-on" style="color:#000000">Invoice No.</span>
                <input type="text" name="invoice_no" id="year" placeholder="e.g. 12586" maxlength="50">
                </div>
                </div>
                <div class="control-group">
                <div class="input-prepend ">
                <span class="add-on" style="color:#000000">Payment Mode</span>
                <select id="dealer" name="dealer">
                            <option value="">Select Mode</option>
                            <option value="1">Cash</option>
                            <option value="2">Cheque</option>
                            <option value="3">Credit Card</option>
                    </select>
                </div>
                </div>
                <div class="control-group">
                <div class="input-prepend ">
                <span class="add-on" style="color:#000000">Amount</span>
                <input type="text" name="note_main" id="note_main" placeholder="e.g. 12586" maxlength="10">
                <span class="add-on" style="color:#000000">Cheque No.</span>
                <input type="text" name="note_main" id="note_main" placeholder="e.g. 12586" maxlength="10">
                </div>
                </div>
                <div class="control-group">
                <div class="input-prepend ">
                <span class="add-on" style="color:#000000">Notes</span>
                <input type="text" name="note_main" id="note_main" placeholder="e.g. 12586" maxlength="10">
                </div>
                </div>
                </div>
                <div class="control-group">
                <div class="input-prepend ">
                <button onclick="addtax();" class="btn btn-success">Save</button>
                </div>
                </div>
	</fieldset>
                </form>
    </div>
<div class="modal hide" id="addbattery_approval">
    <form method="POST" id="getbattery_approval">        
<fieldset>
    <div class="modal-header">
        <button class="close" data-dismiss="modal">×</button>
        <h4 style="color:#0679c0">Add Battery</h4>
    </div>
    <div class="modal-body">
            <span id="amount" name="dealer_check" style="display: none;">Please Select Dealer</span>
                <div class="control-group">
                <div class="input-prepend ">
            <span class="add-on" style="color:#000000">Meter Reading</span><input type="text" name="meter_reading" id="meter_reading" placeholder="e.g. 12586" maxlength="10">
                </div>
                </div>
                <div class="control-group">
                <div class="input-prepend ">
                <span class="add-on" style="color:#000000">Dealer</span>
                    <select id="dealerid" name="dealerid">
                            <option value="">Select Dealer</option>
                            <?php
                            $dealers = getdealers();
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
                <div class="input-prepend ">
                <span class="add-on" style="color:#000000">Notes</span>
                <input type="text" name="note_batt" id="note_batt" placeholder="e.g. 12586" maxlength="10">
                </div>
                </div>
                <div class="control-group">
                <div class="input-prepend ">
                <input type="button" onclick="addbattery_approval();" value="Save" class="btn btn-success">
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
        <h4 style="color:#0679c0">Add Tyre</h4>
    </div>
    <div class="modal-body">
            <span id="amount" name="amount" style="display: none;">Please Check The Amount</span>
                <span id="sdate" name="sdate" style="display: none;">Please Select Start Date</span>
                <span id="edate" name="edate" style="display: none;">Please Select End Date</span>
            <fieldset>
                <div class="control-group">
                <div class="input-prepend ">
                <span class="add-on" style="color:#000000">Replacement Date</span>
            <input id="SDate" name="date" type="text" value="<?php echo date('d-m-Y',$currentdate);?>" required/>
                </div>
                </div>
                <div class="control-group">
                <div class="input-prepend ">
            <span class="add-on" style="color:#000000">Meter Reading</span><input type="text" name="year" id="year" placeholder="e.g. 12586" maxlength="10">
                </div>
                </div>
                <div class="control-group">
                <div class="input-prepend ">
                <span class="add-on" style="color:#000000">Vehicle In Date</span>
            <input id="EDate" name="date" type="text" value="<?php echo date('d-m-Y',$currentdate);?>" required/>
                </div>
                </div>
                <div class="control-group">
                <div class="input-prepend ">
                <span class="add-on" style="color:#000000">Vehicle Out Date</span>
            <input id="EDate" name="date" type="text" value="<?php echo date('d-m-Y',$currentdate);?>" required/>
                </div>
                </div>
                <div class="control-group">
                <div class="input-prepend ">
                <span class="add-on" style="color:#000000">Dealer</span>
                    <select id="dealer" name="dealer">
                            <option value="">Select Dealer</option>
                            <?php
                            $groups = getgroups();
                            if(isset($groups))
                            {
                            foreach ($groups as $group)
                            {
                            echo "<option value='$group->groupid'>$group->groupname</option>";
                            }
                            }
                            ?>
                    </select>
                </div>
                </div>
                <div class="control-group">
                <div class="input-prepend ">
                <span class="add-on" style="color:#000000">Invoice Date</span>
                <input id="EDate" name="date" type="text" value="<?php echo date('d-m-Y',$currentdate);?>" required/>
                </div>
                </div>
                <div class="control-group">
                <div class="input-prepend ">
                <span class="add-on" style="color:#000000">Invoice No.</span>
                <input type="text" name="invoice_no" id="year" placeholder="e.g. 12586" maxlength="50">
                </div>
                </div>
                <div class="control-group">
                <div class="input-prepend ">
                <span class="add-on" style="color:#000000">Payment Mode</span>
                <select id="dealer" name="dealer">
                            <option value="">Select Mode</option>
                            <option value="1">Cash</option>
                            <option value="2">Cheque</option>
                            <option value="3">Credit Card</option>
                    </select>
                </div>
                </div>
                <div class="control-group">
                <div class="input-prepend ">
                <span class="add-on" style="color:#000000">Amount</span>
                <input type="text" name="note_main" id="note_main" placeholder="e.g. 12586" maxlength="10">
                <span class="add-on" style="color:#000000">Cheque No.</span>
                <input type="text" name="note_main" id="note_main" placeholder="e.g. 12586" maxlength="10">
                </div>
                </div>
                <div class="control-group">
                <div class="input-prepend ">
                <span class="add-on" style="color:#000000">Notes</span>
                <input type="text" name="note_main" id="note_main" placeholder="e.g. 12586" maxlength="10">
                </div>
                </div>
                <div class="control-group">
                <div class="input-prepend ">
                <span class="add-on" style="color:#000000">Tyre Type</span>
                <select id="dealer" name="dealer">
                            <option value="">Select Tyre</option>
                            <option value="1">Front Left</option>
                            <option value="2">Front Right</option>
                            <option value="3">Rear Left</option>
                            <option value="4">Rear Right</option>
                            <option value="5">Stepney</option>
                </select>
                </div>
                </div>
                </div>
                <div class="control-group">
                <div class="input-prepend ">
                <input type="button" onclick="addtyre();" value="Save" class="btn btn-success">
                </div>
                </div>
	</fieldset>
    </div>
<div class="modal hide" id="addtyre_approval">
<form class="form-horizontal" id="gettyre_approval">
<fieldset>
    <div class="modal-header">
        <button class="close" data-dismiss="modal">×</button>
        <h4 style="color:#0679c0">Add Tyre</h4>
    </div>
    <div class="modal-body">
            <span id="amount" name="amount" style="display: none;">Please Check The Amount</span>
                <span id="sdate" name="sdate" style="display: none;">Please Select Start Date</span>
                <span id="edate" name="edate" style="display: none;">Please Select End Date</span>
            <fieldset>
                <div class="control-group">
                <div class="input-prepend ">
            <span class="add-on" style="color:#000000">Meter Reading</span><input type="text" name="year" id="year" placeholder="e.g. 12586" maxlength="10">
                </div>
                </div>
                <div class="control-group">
                <div class="input-prepend ">
                <span class="add-on" style="color:#000000">Dealer</span>
                    <select id="dealer" name="dealer">
                            <option value="">Select Dealer</option>
                            <?php
                            $dealers = getdealers();
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
                <div class="input-prepend ">
                <span class="add-on" style="color:#000000">Notes</span>
                <input type="text" name="note_main" id="note_main" placeholder="e.g. 12586" maxlength="10">
                </div>
                </div>
                </div>
                <div class="control-group">
                <div class="input-prepend ">
                <input type="button" onclick="addtyre_approval();" value="Save" class="btn btn-success">
                </div>
                </div>
	</fieldset>
    </div>
<div class="modal hide" id="addrepair">
<form class="form-horizontal" id="getrepair">
<fieldset>
    <div class="modal-header">
        <button class="close" data-dismiss="modal">×</button>
        <h4 style="color:#0679c0">Add Tyre</h4>
    </div>
    <div class="modal-body">
            <span id="amount" name="amount" style="display: none;">Please Check The Amount</span>
                <span id="sdate" name="sdate" style="display: none;">Please Select Start Date</span>
                <span id="edate" name="edate" style="display: none;">Please Select End Date</span>
            <fieldset>
                <div class="control-group">
                <div class="input-prepend ">
                <span class="add-on" style="color:#000000">Repair Date</span>
            <input id="SDate" name="date" type="text" value="<?php echo date('d-m-Y',$currentdate);?>" required/>
                </div>
                </div>
                <div class="control-group">
                <div class="input-prepend ">
            <span class="add-on" style="color:#000000">Meter Reading</span><input type="text" name="year" id="year" placeholder="e.g. 12586" maxlength="10">
                </div>
                </div>
                <div class="control-group">
                <div class="input-prepend ">
                <span class="add-on" style="color:#000000">Vehicle In Date</span>
            <input id="EDate" name="date" type="text" value="<?php echo date('d-m-Y',$currentdate);?>" required/>
                </div>
                </div>
                <div class="control-group">
                <div class="input-prepend ">
                <span class="add-on" style="color:#000000">Vehicle Out Date</span>
            <input id="EDate" name="date" type="text" value="<?php echo date('d-m-Y',$currentdate);?>" required/>
                </div>
                </div>
                <div class="control-group">
                <div class="input-prepend ">
                <span class="add-on" style="color:#000000">Dealer</span>
                    <select id="dealer" name="dealer">
                            <option value="">Select Dealer</option>
                            <?php
                            $groups = getgroups();
                            if(isset($groups))
                            {
                            foreach ($groups as $group)
                            {
                            echo "<option value='$group->groupid'>$group->groupname</option>";
                            }
                            }
                            ?>
                    </select>
                </div>
                </div>
                <div class="control-group">
                <div class="input-prepend ">
                <span class="add-on" style="color:#000000">Invoice Date</span>
                <input id="EDate" name="date" type="text" value="<?php echo date('d-m-Y',$currentdate);?>" required/>
                </div>
                </div>
                <div class="control-group">
                <div class="input-prepend ">
                <span class="add-on" style="color:#000000">Invoice No.</span>
                <input type="text" name="invoice_no" id="year" placeholder="e.g. 12586" maxlength="50">
                </div>
                </div>
                <div class="control-group">
                <div class="input-prepend ">
                <span class="add-on" style="color:#000000">Payment Mode</span>
                <select id="dealer" name="dealer">
                            <option value="">Select Mode</option>
                            <option value="1">Cash</option>
                            <option value="2">Cheque</option>
                            <option value="3">Credit Card</option>
                    </select>
                </div>
                </div>
                <div class="control-group">
                <div class="input-prepend ">
                <span class="add-on" style="color:#000000">Amount</span>
                <input type="text" name="note_main" id="note_main" placeholder="e.g. 12586" maxlength="10">
                <span class="add-on" style="color:#000000">Cheque No.</span>
                <input type="text" name="note_main" id="note_main" placeholder="e.g. 12586" maxlength="10">
                </div>
                </div>
                <div class="control-group">
                <div class="input-prepend ">
                <span class="add-on" style="color:#000000">Notes</span>
                <input type="text" name="note_main" id="note_main" placeholder="e.g. 12586" maxlength="10">
                </div>
                </div>
                <div class="control-group">
                <div class="input-prepend ">
                <span class="add-on" style="color:#000000">Category</span>
                <select id="dealer" name="dealer">
                            <option value="">Select Category</option>
                            <option value="Regular Service">Regular Service</option>
                            <option value="Major Maintenance">Major Maintenance</option>
                    </select>
                </div>
                </div>
                </div>
                <div class="control-group">
                <div class="input-prepend ">
                <button onclick="addrepair();" class="btn btn-success">Save</button>
                </div>
                </div>
	</fieldset>
    </div>
</fieldset>
</form>
</div>