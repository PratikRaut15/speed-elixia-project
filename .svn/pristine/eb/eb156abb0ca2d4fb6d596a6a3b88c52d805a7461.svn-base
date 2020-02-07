<?php $alert = getAlert($_GET['vid']); ?>
<div class="table" style="width: 70%; clear: both">
    <div class="" style="float: right;">
        <input type="hidden" id="edit_vehicle_id" name="edit_vehicle_id" value='<?php echo $_GET['vid']; ?>'>
        <input type="hidden" id="edit_veh_readonly" name="edit_veh_readonly" value='<?php echo $_GET['vidread']; ?>'>        
        <input type="hidden" id="edit_veh_readonly" name="general_complete" value=''>        
        <input type="hidden" name="customerno" id="customerno" value="<?php echo $_SESSION['customerno'] ?>"><br>
        <input type="hidden" name="cheirarchy" id="cheirarchy" value="<?php echo $_SESSION['use_hierarchy'] ?>">        
    </div>
    <br/><br/>
    <span id="general_success" style="display:none; color: #00FF00">Data Added Successfully.</span> 
    <span id="vehicle_error" style="display:none; color: #FF0000">Please Enter Vehicle No.</span> 
    <span id="vehicle_input_error" style="display:none; color: #FF0000">Vehicle No. must be Capital and Numeric.</span> 
    <span id="meter_error" style="display:none; color: #FF0000">Start Meter Reading must be Numeric only.</span> 
    <span id="yearofman_error" style="display:none; color: #FF0000">Please Enter Year of Manufacture.</span> 
    <span id="yearofman_2000_error" style="display:none; color: #FF0000">Year of Manufacture cannot be less than 2000</span>           
    <span id="PDate_error" style="display:none; color: #FF0000">Please Enter Purchase Date.</span> 
    <span id="year_error" style="display:none; color: #FF0000">Purchase Year cannot be less than Year of Manufacture.</span> 
    <span id="make_error" style="display:none; color: #FF0000">Please Select Make.</span> 
    <span id="branch_error" style="display:none; color: #FF0000">Please Select Branch.</span>
    <span id="description_success" style="display:none; color: #00FF00">Description Added Successfully.</span> 
    <span id="engineno_error" style="display:none; color: #FF0000">Engine no. should be in Capital</span> 
    <span id="engineno_comp_error" style="display:none; color: #FF0000">Engine no. cannot be empty</span>           
    <span id="chasisno_comp_error" style="display:none; color: #FF0000">Chasis no. cannot be empty</span>                     
    <span id="chasisno_error" style="display:none; color: #FF0000">Chasis no. should be AlphaNumeric and Capital, a total of 20 characters </span>
    <span id="invoice_year_error" style="display:none; color: #FF0000">Invoice Year cannot be less than Year of Manufacture.</span>
    <span id="invoice_no_error" style="display:none; color: #FF0000">Please Enter Invoice No.</span> 
    <span id="invoiceamt_error" style="display:none; color: #FF0000">Please Enter Invoice Amount.</span> 
    <span id="invoice_date_error" style="display:none; color: #FF0000">Please Enter Invoice Date.</span>
    <span id="tax_success" style="display:none;">Tax details added successfully.</span>
    <span id="insurance_success" style="display:none; color: #00FF00">Insurance details added successfully</span> 
    <span id="insurance_value_error" style="display:none; color: #FF0000">Please enter digits only in Value of Insurance.</span> 
    <span id="value_empty_error" style="display:none; color: #FF0000">Value of Insurance cannot be empty</span>         
    <span id="premium_empty_error" style="display:none; color: #FF0000">Premium cannot be empty</span>                 
    <span id="company_error" style="display:none; color: #FF0000">Please select Insurance Company</span>                         
    <span id="date_conf_error" style="display:none; color: #FF0000">Start date cannot be greater than End date</span> 
    <span id="man_date_error" style="display:none; color: #FF0000">Start Date & End Date must not be in the range preceeding the manufacturing date of the vehicle</span>         
    <span id="margin_error" style="display:none; color: #FF0000">Please Enter Margin Amount</span>
    <span id="loan_error" style="display:none; color: #FF0000">Please Enter Loan Amount</span>
    <span id="emi_error" style="display:none; color: #FF0000">Please Enter EMI Amount</span>
    <span id="financier_error" style="display:none; color: #FF0000">Please Enter Financier Name</span>
    <span id="loantenure_error" style="display:none; color: #FF0000">Please Enter Loan Tenure</span>
    <span id="startloan_error" style="display:none; color: #FF0000">Start Loan Date Can not be empty</span>
    <span id="endloan_error" style="display:none; color: #FF0000">End Loan Date Can not be empty</span>
    <span id="premium_value_error" style="display:none; color: #FF0000">Please enter digits only in Premium.</span>
    <span id="battery_success" style="display:none;">Battery History Details added successfully.</span>
    <span id="capitalization_success" style="display:none; color: #00FF00">Capitalization details added successfully</span>
    <span id="cap_amount_error" style="display:none; color: #FF0000">Capitalization cost cannot be empty</span>      
    <span id="cap_date_man_error" style="display:none; color: #FF0000">Capitalization Date cannot preceed manufacturing date of the vehicle</span>
    <span id="papers_success" style="display:none;">Papers uploaded successfully.</span>
    <script>
        var vehicle_id = <?php echo $_GET['vid']; ?>;
        jQuery(document).ready(function () {
            //alert(vehicle_id);
            gettax();
            getbattery();
        });
    </script>
    <style>
        .table1 td{ border: none; padding: 0;}
    </style>
    <form method="POST" id="general" enctype="multipart/formdata" >
        <fieldset>
            <?php $general = getgeneral($_GET['vid']); ?>
            <?php $description = getdescription($_GET['vid']); ?>
            <?php $insurance = getinsurance($_GET['vid']); ?>
            <?php $amc = getamc($_GET['vid']); ?>
            <?php $loan = getloan($_GET['vid']); ?>
            <?php $capitalization = getcapitalization($_GET['vid']); ?>
            <div style="height: 400px; overflow-x: auto;"> 
                <div class="input-prepend formSep "> General Details </div>
                   <table class="table1" style="width:90%; border: none;">
                    <tbody>
                        <tr>
                            <td width='20%'>Vehicle No. <span style="color:#FE2E2E; ">*</span></td>
                            <td width='30%'>
                                <input type="text" name="edit_vehicle_no" id="edit_vehicle_no" placeholder="Vehicle No" value="<?php echo $general->vehicleno; ?>" autofocus maxlength="10" OnKeyPress="return nospecialchars(event)"></td>
                            <td width='20%'>Kind</td>
                            <td width='30%'>
                                <select id="kind" name="kind">
                                    <option value="">Select Kind</option>
                                    <option value='Bus' <?php
                                    if ($general->kind == 'Bus'){
                                        echo 'selected="selected"';
                                    }
                                    ?> >Bus</option>
                                    <option value='Truck' <?php
                                    if ($general->kind == 'Truck') {
                                        echo 'selected="selected"';
                                    }
                                    ?> >Truck</option>
                                    <option value='Car' <?php
                                    if ($general->kind == 'Car') {
                                        echo 'selected="selected"';
                                    }
                                    ?> >Car</option>
                                    <option value='SUV' <?php
                                    if ($general->kind == 'SUV') {
                                        echo 'selected="selected"';
                                    }
                                    ?> >SUV</option>
                                </select>
<!--                                <input type="hidden" value="<?php echo $general->kind; ?>" id="old_kind" name="old_kind">-->
                            </td>
                        </tr>
                            <?php
                                if ($general->taxDate != '0000-00-00') {
                                    $taxDate = date('d-m-Y', strtotime($general->taxDate));
                                } else {
                                    $taxDate = "";
                                }
                                
                                if ($general->permitDate != '0000-00-00') {
                                    $permitDate = date('d-m-Y', strtotime($general->permitDate));
                                } else {
                                    $permitDate = "";
                                }
                                
                                if ($general->fitnessDate != '0000-00-00') {
                                    $fitDate = date('d-m-Y', strtotime($general->fitnessDate));
                                } else {
                                    $fitDate = "";
                                }
                            ?>  
                            <?php 
                            if ($general->kind == 'Car') {
                            ?>
                            <tr class="kindcar">
                                <td>Tax Date <span style="color:#FE2E2E; ">*</span></td>
                                <td>
                                    <input id="taxDate" name="taxDate" type="text" class="input-small" value="<?php echo $taxDate; ?>"  placeholder="e.g. <?php echo date('d-m-Y'); ?>" required=""  />
                                </td>
                                <td>Permit <span style="color:#FE2E2E; ">*</span></td>
                                <td>
                                    <input id="permitDate" name="permitDate" type="text" class="input-small" value="<?php echo $permitDate; ?>" placeholder="e.g. <?php echo date('d-m-Y'); ?>" required=""  />
                                </td>
                            </tr>
                            <tr class="kindcar">
                                <td>Fitness Renewals <span style="color:#FE2E2E; ">*</span></td>
                                <td>
                                    <input id="fitDate" name="fitDate" type="text" class="input-small" value="<?php echo $fitDate; ?>" placeholder="e.g. <?php echo date('d-m-Y'); ?>" required=""  />
                                </td>
                            </tr>
                             <?php } ?>
                            
                        <tr>
                            <td width="20%">Owner Name</td>
                            <td width="30%">
                                <input type="text" name="owner_name" value='<?php echo $general->owner_name; ?>' placeholder="Owner Name" autofocus >
<!--                                <input type="hidden" name="old_owner_name" id="old_owner_name" value="<?php echo $general->owner_name; ?>">-->
                            </td>
                            <td width="20%">RTO Location</td>
                            <td width="30%"><input type="text" name="rto_location" value='<?php echo $general->rto_location; ?>' placeholder="RTO Location" autofocus >
<!--                            <input type="hidden" name="old_rto_location" id="old_rto_location" value="<?php echo $general->rto_location; ?>">-->
                            </td>
                        </tr>
                         <tr>
                            <td width="20%">Current Location</td>
                            <td width="30%"><input type="text" name="current_location" placeholder="Current Location" value="<?php echo $general->current_location; ?>" maxlength="50" autofocus ></td>
                            <td width="20%">Authorized Signatory</td>
                            <td width="30%"><input type="text" name="auth_signatory" placeholder="Authorized Signatory" maxlength="50" value="<?php echo $general->authorized_signatory; ?>" autofocus ></td>
                        </tr>
                        <tr>
                            <?php 
                            $checked =  "checked='unchecked'";
                            if($general->hypothecation!=" "){
                               $checked =  "checked='checked'";
                            }
                            
                            
                            
                            ?>
                            
                            
                            <td width="20%">Hypothecation </td>
                            <td width="30%">
                                <input type="radio" name="hypothecationrd" class="hrd" <?php echo $checked;?> value='1'/> Yes 
                                <input type="radio" name="hypothecationrd" class="hrd" value='0'/> No
                            </td>
                            <td width="20%"></td>
                            <td width="30%"><input type="text" name="hypothecation" id="hypothecation" placeholder="Hypothecation" value="<?php echo $general->hypothecation; ?>" maxlength="50" autofocus ></td>
                        </tr>
                        <tr>
                            <td>Make</td>
                            <td>
                                <select id="make" name="make" onchange="getmodel()">
                                    <option value="">Select Make</option>
                                    <?php
                                    $makes = getmakes();
                                    if (isset($makes)) {
                                        foreach ($makes as $make) {
                                            if ($make->id == $general->makeid) {
                                                echo "<option value='$make->id' selected='selected'>$make->name</option>";
                                            } else {
                                                echo "<option value='$make->id'>$make->name</option>";
                                            }
                                        }
                                    }
                                    ?>
                                </select>
<!--                                <input type="hidden" name="old_make" id="old_make" value="<?php echo $general->makeid; ?>">-->
                            </td>
                            <td>Model</td>
                            <td>
                                <select id="model" name="model">
                                    <option value="">Select Model</option>
                                    <?php
                                    $models = getmodels($general->makeid);
                                    if (isset($models)) {
                                        foreach ($models as $model) {
                                            if ($model->model_id == $general->modelid) {
                                                echo "<option value='$model->model_id' selected='selected'>$model->name</option>";
                                            } else {
                                                echo "<option value='$model->model_id'>$model->name</option>";
                                            }
                                        }
                                    }
                                    ?>
                                </select>
<!--                                <input type="hidden" name="old_model" id="old_model" value="<?php echo $general->modelid; ?>">-->
                            </td>

                        </tr>
                        <tr>
                            <td>Month Of Manufacture</td>
                            <td>
                                <select name="monthofman" id="monthofman">
                                    <option value="0" <?php
                                    if ($general->manufacturing_month == 0) {
                                        echo "selected";
                                    }
                                    ?>>Select Month</option>
                                    <option value="1" <?php
                                    if ($general->manufacturing_month == 1) {
                                        echo "selected";
                                    }
                                    ?>>Jan</option>
                                    <option value="2" <?php
                                    if ($general->manufacturing_month == 2) {
                                        echo "selected";
                                    }
                                    ?>>Feb</option>
                                    <option value="3" <?php
                                    if ($general->manufacturing_month == 3) {
                                        echo "selected";
                                    }
                                    ?>>March</option>
                                    <option value="4" <?php
                                    if ($general->manufacturing_month == 4) {
                                        echo "selected";
                                    }
                                    ?>>April</option>
                                    <option value="5" <?php
                                    if ($general->manufacturing_month == 5) {
                                        echo "selected";
                                    }
                                    ?>>May</option>
                                    <option value="6" <?php
                                    if ($general->manufacturing_month == 6) {
                                        echo "selected";
                                    }
                                    ?>>Jun</option>
                                    <option value="7" <?php
                                    if ($general->manufacturing_month == 7) {
                                        echo "selected";
                                    }
                                    ?>>July</option>
                                    <option value="8" <?php
                                    if ($general->manufacturing_month == 8) {
                                        echo "selected";
                                    }
                                    ?>>Aug</option>
                                    <option value="9" <?php
                                    if ($general->manufacturing_month == 9) {
                                        echo "selected";
                                    }
                                    ?>>Sept</option>
                                    <option value="10" <?php
                                    if ($general->manufacturing_month == 10) {
                                        echo "selected";
                                    }
                                    ?>>Oct</option>
                                    <option value="11" <?php
                                    if ($general->manufacturing_month == 11) {
                                        echo "selected";
                                    }
                                    ?>>Nov</option>
                                    <option value="12" <?php
                                    if ($general->manufacturing_month == 12) {
                                        echo "selected";
                                    }
                                    ?>>Dec</option>

                                </select>
<!--                                <input type="hidden" name="old_monthofman" id="old_monthofman" value="<?php echo $general->manufacturing_month; ?>"/>-->
                            </td>
                            <td>Year of Manufacture <span style="color:#FE2E2E; ">*</span></td>
                            <td><input type="text" value="<?php echo $general->manufacturing_year; ?>" name="yearofman" id="yearofman" placeholder="e.g. 2011" maxlength="4" >
<!--                            <input type="hidden" name="old_yearofman" id="old_yearofman" value="<?php echo $general->manufacturing_year; ?>"/>-->
                            </td>

                        </tr>
                        <tr>
                            <td><?php echo($_SESSION['group']); ?> <?php if ($_SESSION['use_hierarchy'] == '1') { ?><span style="color:#FE2E2E; ">*</span> <?php } ?></td>
                            <td >
                                <select id="branchid" name="branchid" <?php if ($_SESSION['use_hierarchy'] == '1') { ?> onchange="showbranch()" <?php } ?>>
                                    <option value="">Select <?php echo($_SESSION['group']); ?></option>
                                    <?php
                                    $groups = getgroupss();
                                    if (isset($groups)) {
                                        foreach ($groups as $group) {
                                            if ($group->groupid == $general->branchid) {
                                                echo "<option value='$group->groupid' selected='selected'>$group->groupname</option>";
                                            } else {
                                                echo "<option value='$group->groupid'>$group->groupname</option>";
                                            }
                                        }
                                    }
                                    ?>
                                </select>
<!--                                <input type="hidden" name="old_branchid" id="old_branchid" value="<?php echo $general->branchid; ?>">-->
                            </td>
                            <?php
                            if ($general->purchase_date != '0000-00-00') {
                                $PDate = date('d-m-Y', strtotime($general->purchase_date));
                            } else {
                                $PDate = "";
                            }
                            ?>                  
                            <td>Purchase Date <span style="color:#FE2E2E; ">*</span></td>
                            <td>
                                <input id="PDate" name="PDate" type="text" class="input-small"  value="<?php echo $PDate; ?>" required=""  />
<!--                                <input type="hidden" name="old_PDate" id="old_PDate" value="<?php echo $PDate; ?>">-->
                            </td>
                        </tr>
<!--                        <tr>
                            <td colspan="4">
                                <div id="branch_div">
                        <?php
                        $branch = getbranch($general->branchid);
                        echo $branch;
                        ?></div>
                            </td>
                        </tr>-->
                        <tr>
                            <td>Start Meter Reading</td>
                            <td>
                                <input type="text" name="start_meter" value="<?php echo $general->start_meter_reading; ?>" id="start_meter" placeholder="e.g. 12586" maxlength="10" >
<!--                                <input type="hidden" name="old_start_meter" value="<?php echo $general->start_meter_reading; ?>" id="old_start_meter" >-->
                            </td>
                            <td>Overspeed Limit</td>
                            <td>
                                <input type="text" name="overspeed" value="<?php echo $general->overspeed_limit; ?>" id="overspeed" placeholder="Overspeed Limit" maxlength="20" value="80"/>
<!--                                <input type="hidden" name="old_overspeed" value="<?php echo $general->overspeed_limit; ?>" id="old_overspeed"/>-->
                            </td>
                        </tr>
                        <tr>
                            <td>Fuel Type</td>
                            <td>
                                <select id="fueltype" name="fueltype">
                                    <option value="">Select Fuel Type</option>
                                    <option value='Petrol' <?php
                                    if ($general->fueltype == 'Petrol') {
                                        echo 'selected="selected"';
                                    }
                                    ?> >Petrol</option>
                                    <option value='Diesel' <?php
                                    if ($general->fueltype == 'Diesel') {
                                        echo 'selected="selected"';
                                    }
                                    ?> >Diesel</option>
                                    <option value='CNG' <?php
                                    if ($general->fueltype == 'CNG') {
                                        echo 'selected="selected"';
                                    }
                                    ?> >CNG</option>
                                </select>
<!--                                <input type="hidden" name="old_fueltype" id="old_fueltype" value="<?php echo $general->fueltype; ?>">-->
                            </td>
                            <td>Fuel Tank Capacity</td>
                            <td>
                                <input type="text" name="ftcap" id="ftcap" value="<?php echo $general->fuelcapacity; ?>" />
<!--                                <input type="hidden" name="oldftcap" id="oldftcap" value="<?php echo $general->fuelcapacity; ?>" />-->
                            </td>
                        </tr>
                        <tr>
                            </td>
                            <?php
                            if ($general->registration_date != '0000-00-00 00:00:00' && $general->registration_date != '1970-01-01 00:00:00') {
                                $RDate = date('d-m-Y', strtotime($general->registration_date));
                            } else {
                                $RDate = "";
                            }
                            /* if($RDate == "01-01-1970"){ 
                              $RDate = "";
                              }
                             * 
                             */
                            ?>                  
                            <td>Registration Date </td>
                            <td>
                                <input id="RDate" name="RDate" type="text" class="input-small"  value="<?php echo $RDate; ?>" required=""  />
<!--                                <input id="old_RDate" name="old_RDate" type="hidden" value="<?php echo $RDate; ?>"/>-->
                            </td>
                        </tr>

                    </tbody>
                </table>

                <div class="input-prepend formSep ">Fire Extinguisher</div>
                <table class="table1" style="width:90%; border: none;">
                    <tbody>
                        <tr>
                            <td>Serial Number</td>
                            <td>
                                <input name="serial_number" type="text" value='<?php echo $general->serial_number; ?>' placeholder="e.g. 23"/>
<!--                                <input name="old_serial_number" id="old_serial_number" type="hidden" value='<?php echo $general->serial_number; ?>' />-->
                            </td>
                            <td>Expiry Date</td>
                            <td><input id="ExpDate" name="expiry_date" type="text" value='<?php
                                if (isset($general->expiry_date) && $general->expiry_date != '0000-00-00') {
                                    echo date('d-m-Y', strtotime($general->expiry_date));
                                };
                                ?>' placeholder="e.g. <?php echo date('d-m-Y'); ?>" /></td>


<!--                    <input name="old_expiry_date" id="old_expiry_date" type="hidden" value="<?php echo $general->expiry_date; ?>">-->
                        </tr>
                    </tbody> 
                </table>

                <div class="input-prepend formSep "> Description </div>         
                <table class="table1" style="width: 90%;">
                    <tbody>
                        <tr>
                            <td>Engine No.</td>
                            <td>
                                <input type="text" name="edit_engineno" id="edit_engineno" value="<?php echo $description->engineno; ?>" placeholder="engine no." maxlength="20" OnKeyPress="return nospecialchars(event)">
<!--                                <input type="hidden" name="old_edit_engineno" id="old_edit_engineno" value="<?php echo $description->engineno; ?>"> -->
                            </td>
                            <td>Chasis No.</td>
                            <td>
                                <input type="text" name="chasisno" id="chasisno" value="<?php echo $description->chasisno; ?>" placeholder="chasis no." maxlength="20" OnKeyPress="return nospecialchars(event)">
<!--                                <input type="hidden" name="old_chasisno" id="old_chasisno" value="<?php echo $description->chasisno; ?>">-->
                            </td>
                        </tr>
                        <tr>
                            <td>Vehicle Purpose</td>
                            <td>
                                <select id="veh_purpose" name="veh_purpose">
                                    <option value="">Select Purpose</option>
                                    <option value='1' <?php
                                    if ($description->vehicle_purpose == '1') {
                                        echo 'selected="selected"';
                                    }
                                    ?> >Employee CTC</option>
                                    <option value='2' <?php
                                    if ($description->vehicle_purpose == '2') {
                                        echo 'selected="selected"';
                                    }
                                    ?> >Branch Vehicle</option>
                                    <option value='3' <?php
                                    if ($description->vehicle_purpose == '3') {
                                        echo 'selected="selected"';
                                    }
                                    ?> >Zone Vehicle</option>
                                    <option value='4' <?php
                                    if ($description->vehicle_purpose == '4') {
                                        echo 'selected="selected"';
                                    }
                                    ?> >Regional Vehicle</option>
                                    <option value='5' <?php
                                    if ($description->vehicle_purpose == '5') {
                                        echo 'selected="selected"';
                                    }
                                    ?> >Head Office Vehicle</option>
                                </select>
                                <input type="hidden" name="old_veh_purpose" id="old_veh_purpose" value="<?php echo $description->vehicle_purpose; ?>">
                            </td>
                            <td>Vehicle Type</td>
                            <td>
                                <select id="veh_type" name="veh_type">
                                    <option value="">Select Vehicle Type</option>
                                    <option value='1' <?php
                                    if ($description->vehicle_type == '1') {
                                        echo 'selected="selected"';
                                    }
                                    ?> >New</option>
                                    <option value='2' <?php
                                    if ($description->vehicle_type == '2') {
                                        echo 'selected="selected"';
                                    }
                                    ?> >Repossesed</option>
                                    <option value='3' <?php
                                    if ($description->vehicle_type == '3') {
                                        echo 'selected="selected"';
                                    }
                                    ?> >Employee</option>
                                </select>
<!--                                <input type="hidden" name="old_veh_type" id="old_veh_type" value="<?php echo $description->vehicle_type; ?>">-->
                            </td>
                        </tr>
                        <tr>
                            <td>Dealer Name</td>
                            <td>
                                <select id="dealerid" name="dealerid" onchange="showdealer()">
                                    <option value="">Select Dealer</option>
                                    <?php
                                    $dealers = getdealersbytype('1', $_SESSION['roleid'], $_SESSION['heirarchy_id']);
                                    if (isset($dealers)) {
                                        foreach ($dealers as $dealer) {
                                            if ($dealer->dealerid == $description->dealerid) {
                                                echo "<option value='$dealer->dealerid' selected='selected'>$dealer->name</option>";
                                            } else {
                                                echo "<option value='$dealer->dealerid'>$dealer->name</option>";
                                            }
                                        }
                                    }
                                    ?>
                                </select>
<!--                                <input type="hidden" name="old_dealerid" id="old_dealerid" value="<?php echo $description->dealerid; ?>">-->
                                <?php
                                if ($description->invoicedate != '0000-00-00' && $description->invoicedate != '1970-01-01') {
                                    $invoicedate = date('d-m-Y', strtotime($description->invoicedate));
                                } else {
                                    $invoicedate = '';
                                }

                                $dealer_data = getdealer($description->dealerid);
                                ?>
                            </td>
                            <td>Code</td>
                            <td>
                                <input type="text" name="code_dealer" value="<?php echo isset($dealer_data->code) ? $dealer_data->code : ''; ?>" readonly id="code_dealer">
<!--                                <input type="hidden" name="old_code_dealer" id="old_code_dealer" value="<?php echo isset($dealer_data->code) ? $dealer_data->code : ''; ?>">-->
                            </td>
                        </tr>
                        <tr>
                            <td>Invoice No.</td>
                            <td>
                                <input type="text" value="<?php echo $description->invoiceno; ?>" name="invoiceno" id="invoiceno" placeholder="12586" maxlength="10" OnKeyPress="return nospecialchars(event)">
<!--                                <input type="hidden" name="old_invoiceno" id="old_invoiceno" value="<?php echo $description->invoiceno; ?>">-->
                            </td>
                            <td>Invoice Date</td>
                            <td>
                                <input id="invoice_date" value="<?php echo $invoicedate; ?>" name="invoice_date" type="text" class="input-small"  required="">
<!--                                <input type="hidden" name="old_invoice_date" id="old_invoice_date" value="<?php echo $invoicedate; ?>">-->
                            </td>
                        </tr>
                        <tr>
                            <td>Invoice Amount <span style="color: red;">*</span></td>
                            <td>
                                <input type="text" id="invoiceamt" name="invoiceamt"value="<?php echo $description->invoiceamt; ?>" OnKeyPress="return nonspecialchars(event)"/>
<!--                                <input type="hidden" name="old_invoiceamt" id="old_invoiceamt" value="<?php echo $description->invoiceamt; ?>">-->
                            </td>
                            <td>Seat Capacity</td>
                            <td>
                                <input type="text" id="seatcapacity" name="seatcapacity" value="<?php echo $description->seatcapacity; ?>" />
<!--                                <input type="hidden" id="old_seatcapacity" name="old_seatcapacity" value="<?php echo $description->seatcapacity; ?>" />-->
                            </td>
                        </tr>
                    </tbody>
                </table>

                <div class="control-group">
                    <span id="tax_success" style="display:none;">Tax details added successfully.</span>
                    <div class="input-prepend formSep ">
                        Renewals<a href="#addtax" data-toggle="modal" style="width: 16px; height:16px; vertical-align:middle; float: right;"><img class="tooltip-top" src="../../images/show.png" style="width: 16px; height:16px; vertical-align:middle; float: right;" original-title="Click To Add Tax"></a>
                    </div>	
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
                <br/>
                <?php
                if ($_SESSION['customerno'] == 118) {
                    $srno = '';
                    $ins = '';
                    $tyre = getTyreTypedata($_GET['vid']);
                    //echo "<pre>";
                    //print_r($tyre);die();                         
                    ?>      
                    <!---tyres srno---->
                    <div class="input-prepend formSep "> Tyres Serial No Details </div>
                    <div class="control-group">
                        <table class="table" style="width: 90%; border:none;">
                            <thead>         
                            <th>
                                Type
                            </th>
                            <th>
                                Serial No.
                            </th>
                            <th>
                                Installation Date
                            </th>
                            </thead>
                            <tbody>

                                <tr>  
                                    <?php
                                    if (!empty($tyre)) {
                                        $key = searchForId("Right Front", $tyre, "type");
                                        if ($key !== null) {
                                            $srno = $tyre[$key]['serialno'];
                                            $ins = $tyre[$key]['installedon'];
                                        } else {
                                            $srno = '';
                                            $ins = '';
                                        }
                                    } else {
                                        $srno = '';
                                        $ins = '';
                                    }
                                    ?>
                                    <td>Right Front</td>
                                    <td><input name="right_front" type="text"  id="right_front" value="<?php echo $srno; ?>"readonly/></td>
                                    <td><input name="rf_insdate" type="text"  id="rf_insdate" value="<?php echo $ins; ?>" readonly/></td>
                                </tr>
                                <tr>
                                    <?php
                                    if (!empty($tyre)) {
                                        $key = searchForId("Left Front", $tyre, "type");
                                        if ($key !== null) {
                                            $srno = $tyre[$key]['serialno'];
                                            $ins = $tyre[$key]['installedon'];
                                        } else {
                                            $srno = '';
                                            $ins = '';
                                        }
                                    } else {
                                        $srno = '';
                                        $ins = '';
                                    }
                                    ?>
                                    <td>Left Front</td>
                                    <td><input name="left_front" type="text" id="left_front" value="<?php echo $srno; ?>" readonly/></td>
                                    <td><input name="lf_insdate" type="text"  id="rf_insdate" value="<?php echo $ins; ?>" readonly/></td>
                                </tr>
                                <tr>   
                                    <?php
                                    if (!empty($tyre)) {
                                        $key = searchForId("Right Back Out", $tyre, "type");
                                        if ($key !== null) {
                                            $srno = $tyre[$key]['serialno'];
                                            $ins = $tyre[$key]['installedon'];
                                        } else {
                                            $srno = '';
                                            $ins = '';
                                        }
                                    } else {
                                        $srno = '';
                                        $ins = '';
                                    }
                                    ?>
                                    <td>Right Back Out</td>
                                    <td><input name="right_back_out" type="text" id="right_back_out" value="<?php echo $srno; ?>" readonly/></td>
                                    <td><input name="rbout_insdate" type="text"  id="rbout_insdate" value="<?php echo $ins; ?>" readonly/></td>
                                </tr>
                                <tr>
                                    <?php
                                    if (!empty($tyre)) {
                                        $key = searchForId("Left Back Out", $tyre, "type");
                                        if ($key !== null) {
                                            $srno = $tyre[$key]['serialno'];
                                            $ins = $tyre[$key]['installedon'];
                                        } else {
                                            $srno = '';
                                            $ins = '';
                                        }
                                    } else {
                                        $srno = '';
                                        $ins = '';
                                    }
                                    ?>
                                    <td>Left Back Out</td>
                                    <td><input name="left_back_out" type="text" id="left_back_out" value="<?php echo $srno; ?>" readonly/></td>
                                    <td><input name="lbout_insdate" type="text"  id="lbout_insdate" value="<?php echo $ins; ?>" readonly/></td>
                                </tr>
                                <tr>
                                    <?php
                                    if (!empty($tyre)) {
                                        $key = searchForId("Right Back In", $tyre, "type");
                                        if ($key !== null) {
                                            $srno = $tyre[$key]['serialno'];
                                            $ins = $tyre[$key]['installedon'];
                                        } else {
                                            $srno = '';
                                            $ins = '';
                                        }
                                    } else {
                                        $srno = '';
                                        $ins = '';
                                    }
                                    ?>
                                    <td>Right Back In</td>
                                    <td><input name="right_back_in" type="text" id="right_back_in" value="<?php echo $srno; ?>" readonly/></td>
                                    <td><input name="lbout_insdate" type="text"  id="lbout_insdate" value="<?php echo $ins; ?>" readonly/></td>
                                </tr>
                                <tr>
                                    <?php
                                    if (!empty($tyre)) {
                                        $key = searchForId("Left Back In", $tyre, "type");
                                        if ($key !== null) {
                                            $srno = $tyre[$key]['serialno'];
                                            $ins = $tyre[$key]['installedon'];
                                        } else {
                                            $srno = '';
                                            $ins = '';
                                        }
                                    } else {
                                        $srno = '';
                                        $ins = '';
                                    }
                                    ?>
                                    <td>Left Back In</td>
                                    <td><input name="left_back_in" type="text" id="left_back_in" value="<?php echo $srno; ?>" readonly/></td>
                                    <td><input name="lbout_insdate" type="text"  id="lbout_insdate" value="<?php echo $ins; ?>" readonly/></td>
                                </tr>
                                <tr>
                                    <?php
                                    if (!empty($tyre)) {
                                        $key = searchForId("Stepney", $tyre, "type");
                                        if ($key !== null) {
                                            $srno = $tyre[$key]['serialno'];
                                            $ins = $tyre[$key]['installedon'];
                                        } else {
                                            $srno = '';
                                            $ins = '';
                                        }
                                    } else {
                                        $srno = '';
                                        $ins = '';
                                    }
                                    ?>
                                    <td>Stepney</td>
                                    <td><input name="stepney" type="text" id="stepney" value="<?php echo $srno; ?>" readonly/></td>
                                    <td><input name="st_insdate" type="text"  id="st_insdate" value="<?php echo $ins; ?>" readonly/></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <!---battery srno---->
                    <div class="input-prepend formSep "> Battery Serial No</div>
                    <div class="control-group">
                        <table>
                            <tbody>
                                <tr>
                                    <?php
                                    $battdata = getbatteryno_byvehicle($_GET['vid']);
                                    //print_r($battdata);
                                    //echo $battdata[0]->srno;
                                    $battsrno = '';
                                    $battinsdate = '';
                                    if (!empty($battdata)) {
                                        $battsrno = $battdata[0]->srno;
                                        if ($battdata[0]->ins == '0000-00-00' || $battdata[0]->ins == '1970-01-01') {
                                            $battinsdate = '';
                                        } else {
                                            $battinsdate = date("d-m-Y", strtotime($battdata[0]->ins));
                                        }
                                    }
                                    ?>

                                    <td>Serial No.</td>&nbsp;&nbsp;
                                    <td><input type="text" name="battsrno" id="battsrno" value="<?php echo $battsrno; ?>" readonly></td>
                                    <td>Installation Date</td>&nbsp;&nbsp;
                                    <td><input name="battsrno_insdate" type="text"  id="battsrno_insdate" value="<?php echo $battinsdate; ?>"readonly/></td>
                                </tr>
                            </tbody>
                        </table>


                    </div>
                    <?php
                }
                ?>

                <div class="input-prepend formSep "> Insurance </div>
                <div class="control-group">
                    <div class="input-prepend formSep">
                        <span class="add-on">Do you have insurance?</span>
                        <input type="radio" name="insurance" id="insurance1" <?php if (isset($insurance)) { ?>checked<?php } ?> value="yes"> Yes
                        <input type="radio" name="insurance" id="insurance2" <?php if (!isset($insurance)) { ?>checked<?php } ?> value="no"> No
                    </div>	
                </div>
                <div class="control-group" id="radio_show" style="<?php if (!isset($insurance)) { ?> display: none; <?php } ?>">
                    <table class="table1" style="width: 90%;">
                        <tr>
                            <td>Value of Insurance / IDV</td>
                            <td>
                                <input type="text" name="insurance_value" id="insurance_value" value="<?php echo isset($insurance->value) ? $insurance->value : null; ?>" maxlength="10" onkeypress="return isNumberKey(event);"/>
<!--                                <input type="hidden" name="old_insurance_value" id="old_insurance_value" value="<?php echo isset($insurance->value) ? $insurance->value : null; ?>" />-->
                            </td>
                            <td>Premium</td>
                            <td>
                                <input type="text" name="premium_value" id="premium_value" value="<?php echo isset($insurance->premium) ? $insurance->premium : null; ?>" maxlength="10" onkeypress="return isNumberKey(event);"/>
<!--                                <input type="hidden" name="old_premium_value" id="old_premium_value" value="<?php echo isset($insurance->premium) ? $insurance->premium : null; ?>"/>-->
                            </td>
                        </tr>
                        <tr>
                            <td>Start Date</td>
                            <?php
                            if (isset($insurance->start_date) && $insurance->start_date != '0000-00-00') {
                                $insurance->start_date = $insurance->start_date;
                            } else {
                                $insurance->start_date = '';
                            }
                            ?>
                            <td>
                                <input id="StartDate" name="StartDate" type="text" class="input-small" value="<?php echo isset($insurance->start_date) ? $insurance->start_date : null; ?>" readonly>
<!--                                <input id="old_StartDate" name="old_StartDate" type="hidden" value="<?php echo isset($insurance->start_date) ? $insurance->start_date : null; ?>">-->
                            </td>
                            <?php
                            if (isset($insurance->end_date) && $insurance->end_date != '0000-00-00') {
                                $insurance->end_date = $insurance->end_date;
                            } else {
                                $insurance->end_date = '';
                            }
                            ?>
                            <td>End Date</td>
                            <td>
                                <input id="EndDate" name="EndDate" type="text" class="input-small" value="<?php echo isset($insurance->end_date) ? $insurance->end_date : null; ?>" required onblur="insurance_date();">
<!--                                <input id="old_EndDate" name="old_EndDate" type="hidden" value="<?php echo isset($insurance->end_date) ? $insurance->end_date : null; ?>">-->
                            </td>
                        </tr>
                        <tr>
                            <td>Insurance Company</td>
                            <td>
                                <select name="edit_insurance_company" id="edit_insurance_company">
                                    <option value="">Select Company</option>
                                    <?php
                                    $ins_comp = getinsurance_company();
                                    if (isset($ins_comp)) {
                                        foreach ($ins_comp as $ins) {
                                            if ($ins->id == $insurance->companyid) {
                                                echo "<option value='$ins->id' selected='selected'>$ins->name</option>";
                                            } else {
                                                echo "<option value='$ins->id'>$ins->name</option>";
                                            }
                                        }
                                    }
                                    ?>
                                </select>
<!--                                <input type="hidden" name="old_edit_insurance_company" id="old_edit_insurance_company" value="<?php echo $insurance->companyid; ?>">-->
                            </td>
                            <td>Nearest Place Of Claim</td>
                            <td>
                                <input type="text" name="near_place" placeholder="e.g. mumbai, delhi" id="near_place" value="<?php echo isset($insurance->claim_place) ? $insurance->claim_place : null; ?>" maxlength="20" OnKeyPress="return nospecialchars(event)">
<!--                                <input type="hidden" name="old_near_place" id="old_near_place" value="<?php echo isset($insurance->claim_place) ? $insurance->claim_place : null; ?>">-->
                            </td>
                        </tr>
                        <tr>
                           <!-- <td>IDV</td>
                            <td><input id="idv" name="ide" type="text" value="<?php //echo $insurance->idv;      ?>" required></td>-->
                            <td>NCB</td>
                            <td><span>
                                    <input id="ncb" name="enb" type="text" value="<?php echo isset($insurance->ncb) ? $insurance->ncb : null; ?>" required>
<!--                                    <input id="old_ncb" name="old_enb" type="hidden" value="<?php echo isset($insurance->ncb) ? $insurance->ncb : null; ?>">-->
                                </span><span>%</span></td>
                            <td>Notes</td>
                            <td colspan="3">
                                <input type="text" name="ins_notes" id="ins_notes" value ="<?php echo isset($insurance->notes) ? $insurance->notes : null; ?>" maxlength="20" OnKeyPress="return nospecialchars(event)">
<!--                                <input type="hidden" name="old_ins_notes" id="old_ins_notes" value ="<?php echo isset($insurance->notes) ? $insurance->notes : null; ?>">-->
                            </td>
                        </tr>
                        <tr>
                            <td>Dealer Name</td>
                            <td>
                                <select id="insdealerid" name="insdealerid">
                                    <option value="0">Select Dealer</option>
                                    <?php
                                    $dealers = getAllInsdealer();
                                    foreach ($dealers as $dealer) {
                                        if ($dealer->ins_dealerid == $insurance->ins_dealerid) {
                                            echo "<option value='$dealer->ins_dealerid' selected='selected'>$dealer->ins_dealername</option>";
                                        } else {
                                            echo "<option value='$dealer->ins_dealerid'>$dealer->ins_dealername</option>";
                                        }
                                    }
                                    ?>
                                </select>
<!--                                <input type="hidden" id="insdealerid" name="insdealerid" value="<?php echo $insurance->ins_dealerid; ?>">-->
                            </td>
                            <td>Policy Number</td>
                            <td><input type="text" name="polno" id="polno" maxlength="25" value="<?php
                                if (isset($insurance->polno)) {
                                    echo $insurance->polno;
                                }
                                ?>">
<!--                                <input type="hidden" name="oldpolno" id="oldpolno" value="<?php echo $insurance->polno; ?>">-->
                            </td>
                        </tr>
                    </table>    

                </div>
                <br/>
                
                <div class="input-prepend formSep "> Loan </div>
                <div class="control-group">
                    <div class="input-prepend formSep"> <span class="add-on">Have you taken Loan?</span>
                        <input type="radio" name="loan" id="loan1" value="yes" <?php
                        if (isset($loan)) {
                            echo "checked";
                        }
                        ?>> Yes
                        <input type="radio" name="loan" id="loan2" value="no" <?php
                        if (!isset($loan)) {
                            echo "checked";
                        }
                        ?>> No </div>
                </div>
                <div id="loan_show" style="<?php if (!isset($loan)) { ?> display: none; <?php } ?>">
                    <table class="table" style="border:none; width: 90%;">
                        <tr>
                            <td>Margin Amount <span style="color:#FE2E2E; ">*</span></td>
                            <td>
                                <input type="text" name="margin_amt" id="margin_amt" maxlength="10" value="<?php echo $loan->margin_amt; ?>" onkeypress="return isNumberKey(event);"/>
<!--                                <input type="hidden" name="old_margin_amt" id="old_margin_amt" value="<?php echo $loan->margin_amt; ?>"/>-->
                            </td>
                            <td>Loan Amount <span style="color:#FE2E2E; ">*</span></td>
                            <td>
                                <input type="text" name="loan_amt" id="loan_amt" maxlength="10" value="<?php echo $loan->loan_amt ?>" onkeypress="return isNumberKey(event);"/>
<!--                                <input type="hidden" name="old_loan_amt" id="old_loan_amt" value="<?php echo $loan->loan_amt ?>"/>-->
                            </td>
                        </tr>

                        <tr>
                            <td>EMI Amount <span style="color:#FE2E2E; ">*</span></td>
                            <td>
                                <input type="text" name="emi_amt" id="emi_amt" maxlength="10" value="<?php echo $loan->emi_amt; ?>" >
                                <input type="hidden" name="old_emi_amt" id="old_emi_amt" value="<?php echo $loan->emi_amt; ?>" >
                            </td>
                            <td>Loan Tenure (In Months)<span style="color:#FE2E2E; ">*</span></td>
                            <td>
                                <input type="text" name="loan_tenure" id="loan_tenure" maxlength="10" value="<?php echo $loan->loan_tenure; ?>" onkeypress="return isNumberKey(event);"/>
<!--                                <input type="hidden" name="old_loan_tenure" id="old_loan_tenure" value="<?php echo $loan->loan_tenure; ?>"/>-->
                            </td>
                        </tr>

                        <tr>
                            <td>Financier <span style="color:#FE2E2E; ">*</span></td>
                            <td>
                                <input type="text" name="financier" id="financier" value="<?php echo isset($loan->financier) ? $loan->financier : ""; ?>">
<!--                                <input type="hidden" name="old_financier" id="old_financier" value="<?php echo isset($loan->financier) ? $loan->financier : ""; ?>">-->
                            </td>
                            <td></td>
                            <td></td>
                        </tr>

                        <tr>
                            <td>Start Date </td>
                            <?php
                            if (@$loan->startdate == '') {
                                @$loan->startdate = date('Y-m-d H:i:s');
                            }
                            ?>
                            <td>
                                <input id="StartDateloan" name="StartDateloan" type="text" class="input-small" value="<?php echo @date('d-m-Y', strtotime($loan->startdate)); ?>" required>
<!--                                <input id="old_StartDateloan" name="old_StartDateloan" type="hidden" value="<?php echo @date('d-m-Y', strtotime($loan->startdate)); ?>" >-->
                            </td>
                            <td>End Date </td>
                            <?php
                            if ($loan->enddate == '') {
                                $loan->enddate = date('Y-m-d H:i:s');
                            }
                            ?>
                            <td>
                                <input id="EndDateloan" name="EndDateloan" type="text" class="input-small"  value="<?php echo date('d-m-Y', strtotime($loan->enddate)); ?>" required>
<!--                                <input id="old_EndDateloan" name="old_EndDateloan" type="hidden" value="<?php echo date('d-m-Y', strtotime($loan->enddate)); ?>">-->
                            </td>
                        </tr>
                        <tr>
                            <td>Loan Account Number</td>
                            <td>
                                <input type="text" name="loan_accno" id="loan_accno" maxlength="25" value="<?php echo $loan->loan_accno; ?>"/>
<!--                                <input type="hidden" name="old_loan_accno" id="old_loan_accno" value="<?php echo $loan->loan_accno; ?>"/>-->
                            </td>
                            <td>Loan EMI Date</td>
                            <td>
                                <input type="text" name="emidate" id="emidate" value="<?php echo $loan->emidate; ?>"/>
<!--                                <input type="hidden" name="old_emidate" id="old_emidate" value="<?php echo $loan->emidate; ?>"/>-->
                            </td>
                        </tr>
                    </table>
                </div>
                <br/>
                
                <div class="input-prepend formSep "> AMC </div>
                <div class="control-group">
                    <div class="input-prepend formSep">
                        <span class="add-on">Do you have AMC?</span>
                        <input type="radio" name="amc" id="amc1" <?php if (isset($amc)) { ?>checked<?php } ?> value="yes"> Yes
                        <input type="radio" name="amc" id="amc2" <?php if (!isset($amc)) { ?>checked<?php } ?> value="no"> No
                    </div>	
                </div>
                <div class="control-group" id="amc_show" style="<?php if (!isset($amc)) { ?> display: none; <?php } ?>">
                    <table class="table1" style="width: 90%;">
                        <tr>
                            <td>Agreement Start Date</td>
                            <td>
                                <input id="AgrStartDate" name="AgrStartDate" type="text" class="input-small" value="<?php echo isset($amc->agree_start_date)?date('d-m-Y',strtotime($amc->agree_start_date)):'';?>">
                            </td>
                            <td>Agreement End Date</td>
                            <td>
                                <input id="AgrEndDate" name="AgrEndDate" type="text" class="input-small" value="<?php echo isset($amc->agree_end_date)?date('d-m-Y',strtotime($amc->agree_end_date)):'';?>">
                            </td>
                        </tr>
                        <tr>
                            <td>Total insured KM </td>
                            <td>
                                <input id="totalinsuredkm" name="totalinsuredkm" type="text" class="input-small" onkeypress="return nospecialchars(event)" value="<?php echo isset($amc->total_insured_km)?$amc->total_insured_km:'';?>">
                            </td>
                            <td>Total insured Month</td>
                            <td>
                                <input id="totalinsuredmonth" name="totalinsuredmonth" type="text"  onkeypress="return nospecialchars(event)" class="input-small" value="<?php echo isset($amc->insured_month)?$amc->insured_month:'';?>">
                            </td>
                        </tr>
                        <tr>
                            <td>Start KM </td>
                            <td>
                                <input id="amcstartkm" name="amcstartkm" type="text" class="input-small"  onkeypress="return nospecialchars(event)" value="<?php echo isset($amc->startkm)?$amc->startkm:'';?>">
                            </td>
                            <td>End KM</td>
                            <td>
                                <input id="amcendkm" name="amcendkm" type="text" class="input-small" onkeypress="return nospecialchars(event)" value="<?php echo isset($amc->endkm)?$amc->endkm:'';?>">
                            </td>
                        </tr>
                        <tr>
                            <td>Paid Amount </td>
                            <td>
                                <input id="amcpaidamt" name="amcpaidamt" type="text" class="input-small"  onkeypress="return nospecialchars(event)" value="<?php echo isset($amc->paidamt)?$amc->paidamt:'';?>">
                            </td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                        </tr>
                    </table>    
                </div>

                <div class="input-prepend formSep "> Transactions </div>
                <div class="input-prepend formSep " id="add_battery_new">
                    Battery Replacement History<a href="#addbattery" data-toggle="modal" style="width: 16px; height:16px; vertical-align:middle; float: right;"><img class="tooltip-top" src="../../images/show.png" style="width: 16px; height:16px; vertical-align:middle; float: right;" original-title="Click To Add Battery Replacement History"></a>
                </div>	
                <div class="input-prepend">
                    <table  class="table  table-bordered table-striped dTableR dataTable"  style=" width:90%">
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
                <div class="input-prepend formSep " id="add_tyre_new">
                    Tyre Replacement History<a href="#addtyre" data-toggle="modal" style="width: 16px; height:16px; vertical-align:middle; float: right;"><img class="tooltip-top" src="../../images/show.png" style="width: 16px; height:16px; vertical-align:middle; float: right;" original-title="Click To Add Tyre Replacement History"></a>
                </div>	
                <div class="input-prepend">
                    <table  class="table  table-bordered table-striped dTableR dataTable"  style=" width:90%">
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
                <div class="input-prepend formSep " id="add_repair_new">
                    Repair / Service History<a href="#addrepair" data-toggle="modal" style="width: 16px; height:16px; vertical-align:middle; float: right;"><img class="tooltip-top" src="../../images/show.png" style="width: 16px; height:16px; vertical-align:middle; float: right;" original-title="Click To Add Repair / Service Replacement History"></a>
                </div>	
                <div class="input-prepend ">
                    <table  class="table  table-bordered table-striped dTableR dataTable"  style=" width:90%">
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
                <div class="input-prepend formSep" id="add_accessory_new">
                    Accessory History<a href="#addaccessory" data-toggle="modal" style="width: 16px; height:16px; vertical-align:middle; float: right;"><img class="tooltip-top" src="../../images/show.png" style="width: 16px; height:16px; vertical-align:middle; float: right;" original-title="Click To Add Accessory History"></a>
                </div>	
                <div class="input-prepend formSep">
                    <table  class="table  table-bordered table-striped dTableR dataTable"  style=" width:90%">
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
                <div class="input-prepend formSep " id="add_acc_new">
                    Accident Event / Insurance Claim History<a href="#addaccident" data-toggle="modal" style="width: 16px; height:16px; vertical-align:middle; float: right;"><img class="tooltip-top" src="../../images/show.png" style="width: 16px; height:16px; vertical-align:middle; float: right;" original-title="Click To Add Accident History" /></a>
                </div>	
                <div class="input-prepend ">
                    <table  class="table  table-bordered table-striped dTableR dataTable"  style=" width:90%">
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
                <br/>
                <div class="input-prepend formSep "> Capitalization </div>
                <table class="table1" style="width: 90%;">
                    <tbody>
                        <tr>
                            <td>Date</td>
                            <?php
                            if (@$capitalization->date == "00-00-0000") {
                                //@$capitalization->date = date('d-m-Y');
                                @$capitalization->date = '';
                            }
                            /*
                              else{
                              @$capitalization->date = '';
                              }
                             * 
                             */
                            ?>
                            <td>
                                <input id="cap_EDate" name="cap_EDate" type="text" class="input-small" value="<?php echo $capitalization->date; ?>" required="" />
<!--                                <input id="old_cap_EDate" name="old_cap_EDate" type="hidden" value="<?php echo $capitalization->date; ?>" />-->
                            </td>
                            <td>Cost</td>
                            <td>
                                <input type="text" name="edit_cap_cost" id="edit_cap_cost" value="<?php echo $capitalization->cost; ?>" value="0" />
<!--                                <input type="hidden" name="old_edit_cap_cost" id="old_edit_cap_cost" value="<?php echo $capitalization->cost; ?>" value="0" />-->
                            </td>
                        </tr>
                        <tr>
                            <td>Current Ratio (Maintenance / Capitalization)</td>
                            <td>
                                <input type="text" name="cap_code" value="<?php echo $capitalization->maintenance_percent; ?>" readonly id="cap_code" />
<!--                                <input type="hidden" name="old_cap_code" value="<?php echo $capitalization->maintenance_percent; ?>" readonly id="cap_code" />-->
                                <input type="hidden" name="additional_main_amount" value="0" id="additional_main_amount" />
                            </td>
                            <td>Address</td>
                            <td>
                                <textarea name="cap_address" id="cap_address" maxlength="200" OnKeyPress="return nospecialchars(event)"><?php echo $capitalization->address; ?></textarea>
                            </td>
                        </tr>
                    </tbody>
                </table>

                <div class="input-prepend formSep "> Papers </div>

                <div id="uploadpaper"><p class="alert alert-info">Note: Upload the Papers in pdf format only.</p></div>

                <div class="input-prepend formSep">
                    <b>PUC</b>
                </div>
                <?php $filename = get_uploaded_filename($_GET['vid']); ?>

                <div class="control-group" style="text-align: left; margin-left: 10px; width: 90%;">
                    <div class="input-prepend formSep">
                        <?php
                        if (isset($filename->puc_filename) && !empty($filename->puc_filename)) {
                            $uploaddir = "../../customer/" . $_SESSION['customerno'] . "/vehicleid/" . $_GET['vid'] . "/" . $filename->puc_filename;
                        } else {
                            $uploaddir = "../../customer/" . $_SESSION['customerno'] . "/vehicleid/" . $_GET['vid'] . "/puc.pdf";
                        }
                        ?>
                        <input type="file" name="puc" id="puc" <?php
                        if (isset($uploaddir)) {
                            if (file_exists($uploaddir)) {
                                ?>onclick="return confirm('Do you want to replace your existing file ?');" <?php
                                   }
                               }
                               ?> onchange="checkname(this);">
                        <input type="button" id="upload_puc" onclick="uploadFilesPUCx();" value="Upload" class="btn btn-success" />
                        <?php
                        if (isset($uploaddir) && file_exists($uploaddir) && isset($filename->puc_filename)) {
                            ?>
                            <a target="_blank" href="<?php echo $uploaddir; ?>">Download PUC here</a>
                            <?php
                        } else {
                            ?>    
                            &nbsp;&nbsp;<a href="download.php?download_file=puc.pdf&vid=<?php echo $_GET['vid']; ?>&customerno=<?php echo $_SESSION['customerno']; ?>">Download PUC here</a>
                            <?php
                        }
                        ?>
                        <br/><br/> 
                        <div class="input-prepend">  
                            <span id="set_success_puc" style="display:none;" class="btn btn-success">Information Saved successfully.</span>
                            <span id="set_error_puc" style="display:none;" class="btn btn-warning">Error, Please try again.</span>    
                            <?php
                            if (isset($alert) && $alert['puc_expiry'] != '0000-00-00 00:00:00') {
                                //$alert['puc_expiry']= date('Y-m-d H:i:s');
                                ?>
                                <span class="add-on" id="add_puc">Expiry Date </span><input id="ExpiryDate_PUC" name="ExpiryDate_PUC" type="text" class="input-small"  value="<?php echo date("d-m-Y", strtotime($alert['puc_expiry'])); ?>" required=""  />
                                <?php
                            } else {
                                ?>
                                <span class="add-on" id="add_puc">Expiry Date </span><input id="ExpiryDate_PUC" name="ExpiryDate_PUC" type="text" class="input-small"  value="<?php echo date("d-m-Y"); ?>" required=""  />
                                <?php
                            }
                            ?>
                            &nbsp;&nbsp;
                            <span class="add-on" id="add_puc1">Alert by Email / SMS</span>
                            <select id="ExpiryDate_PUC_rem" onchange='setPUC();'>
                                <option value="-1"<?php
                                if ($alert['puc_sms_email'] == -1 || $alert['puc_sms_email'] == '') {
                                    echo "Selected";
                                }
                                ?>>Never Remind me</option>
                                <option value="0" <?php
                                if ($alert['puc_sms_email'] == 0) {
                                    echo "Selected";
                                }
                                ?>>Remind me on the day of Expiry</option>
                                <option value="1" <?php
                                if ($alert['puc_sms_email'] == 1) {
                                    echo "Selected";
                                }
                                ?>>Remind me 1 day prior to Expiry</option>
                                <option value="2" <?php
                                if ($alert['puc_sms_email'] == 2) {
                                    echo "Selected";
                                }
                                ?>>Remind me 2 days prior to Expiry</option>
                                <option value="7" <?php
                                if ($alert['puc_sms_email'] == 7) {
                                    echo "Selected";
                                }
                                ?>>Remind me 1 week prior to Expiry</option>                            
                                <option value="30" <?php
                                if ($alert['puc_sms_email'] == 30) {
                                    echo "Selected";
                                }
                                ?>>Remind me 1 month prior to Expiry</option>                            
                            </select>
                            &nbsp;
                            <input type="button" id="set_poc" name="set_PUC" value="Set" onclick="pucset(<?php echo $_GET['vid']; ?>);" class="btn btn-success" /> 
                            <input type="hidden" id="customernno" name="customerno" value="<?php echo $_SESSION['customerno'] ?>" />
                            <input type="hidden" id="userid" name="userid" value="<?php echo $_SESSION['userid'] ?>" />
                        </div>
                    </div>
                </div>
                
                
                <div class="input-prepend formSep ">
                    <b> Registration </b>
                </div>	
                <div class="control-group" style="text-align: left; margin-left: 10px;width: 90%;">
                    <div class="input-prepend formSep">
                        <?php
                        if (isset($filename->reg_filename) && !empty($filename->reg_filename)) {
                            $uploaddir_reg = "../../customer/" . $_SESSION['customerno'] . "/vehicleid/" . $_GET['vid'] . "/" . $filename->reg_filename;
                        } else {
                            $uploaddir_reg = "../../customer/" . $_SESSION['customerno'] . "/vehicleid/" . $_GET['vid'] . "/registration.pdf";
                        }
                        ?>
                        <input type="file" name="reg" id="reg" onchange="checkname(this);" <?php if (file_exists($uploaddir_reg)) { ?>onclick="return confirm('Do you want to replace your existing file ?');" <?php } ?>>
                        <input type="button" id="upload_reg" onclick="uploadFilesREG();" value="Upload" class="btn btn-success" />
                        <?php
                        if (file_exists($uploaddir_reg) && empty($filename->reg_filename)) {
                            ?>
                            &nbsp;&nbsp;<a href="download.php?download_file=registration.pdf&vid=<?php echo $_GET['vid']; ?>&customerno=<?php echo $_SESSION['customerno']; ?>">Download Registration Document here</a>
                        <?php } else { ?>
                            <a href="<?php echo $uploaddir_reg; ?>" target="_blank">Download Registration Document here</a>  
                        <?php } ?>
                        <br/>  <br/>  
                        <div class="input-prepend">
                            <span id="set_success_reg" style="display:none;" class="btn btn-success">Information Saved successfully.</span>
                            <span id="set_error_reg" style="display:none;" class="btn btn-warning">Error, Please try again.</span>   
                            <?php
                            if (isset($alert) && $alert['reg_expiry'] != '0000-00-00 00:00:00') {
                                //$alert['reg_expiry'] = date('Y-m-d H:i:s');
                                ?>
                                <span class="add-on" id="add_puc">Expiry Date </span><input id="ExpiryDate_REG" name="ExpiryDate_REG" type="text" class="input-small"  value="<?php echo date("d-m-Y", strtotime($alert['reg_expiry'])); ?>" required=""  />
                                <?php
                            } else {
                                ?>
                                <span class="add-on" id="add_puc">Expiry Date </span><input id="ExpiryDate_REG" name="ExpiryDate_REG" type="text" class="input-small"  value="<?php echo date("d-m-Y"); ?>" required=""  />
                                <?php
                            }
                            ?>
                            &nbsp;&nbsp;
                            <span class="add-on" id="add_reg1">Alert by Email / SMS</span>
                            <select id="ExpiryDate_REG_rem" onchange='setREG();'>
                                <option value="-1" <?php
                                if ($alert['reg_sms_email'] == -1 || $alert['reg_sms_email'] == '') {
                                    echo "Selected";
                                }
                                ?>>Never Remind me</option>
                                <option value="0" <?php
                                if ($alert['reg_sms_email'] == 0) {
                                    echo "Selected";
                                }
                                ?>>Remind me on the day of Expiry</option>
                                <option value="1" <?php
                                if ($alert['reg_sms_email'] == 1) {
                                    echo "Selected";
                                }
                                ?>>Remind me 1 day prior to Expiry</option>
                                <option value="2" <?php
                                if ($alert['reg_sms_email'] == 2) {
                                    echo "Selected";
                                }
                                ?>>Remind me 2 days prior to Expiry</option>
                                <option value="7" <?php
                                if ($alert['reg_sms_email'] == 7) {
                                    echo "Selected";
                                }
                                ?>>Remind me 1 week prior to Expiry</option>
                                <option value="30" <?php
                                if ($alert['reg_sms_email'] == 30) {
                                    echo "Selected";
                                }
                                ?>>Remind me 1 month prior to Expiry</option>
                            </select>
                            &nbsp;
                            <input type="button" id="set_regi" onclick="regset(<?php echo $_GET['vid']; ?>);" value="Set" class="btn btn-success" />                                                
                        </div>
                    </div>
                </div>	                            

                <div class="input-prepend formSep ">
                    <b> Insurance </b>
                </div>	

                <div class="control-group" style="text-align: left; margin-left: 10px;width: 90%;">
                    <div class="input-prepend formSep">
                        <?php
                        if (isset($filename->ins_filename) && !empty($filename->ins_filename)) {
                            $uploaddir_ins = "../../customer/" . $_SESSION['customerno'] . "/vehicleid/" . $_GET['vid'] . "/" . trim($filename->ins_filename);
                        } else {
                            $uploaddir_ins = "../../customer/" . $_SESSION['customerno'] . "/vehicleid/" . $_GET['vid'] . "/insurance.pdf";
                        }
                        ?>
                        <input type="file" name="insurance" id="insurance" onchange="checkname(this);" <?php if (file_exists($uploaddir_ins)) { ?>onclick="return confirm('Do you want to replace your existing file ?');" <?php } ?>>
                        <input type="button" id="upload_ins" onclick="uploadFilesINS();" value="Upload" class="btn btn-success" />
                        <?php
                        if (file_exists($uploaddir_ins) && !empty($filename->ins_filename)) {
                            ?>
                            <a href="<?php echo $uploaddir_ins; ?>" target="_blank">Download Insurance Document here</a>  
                        <?php } else { ?>
                            &nbsp;&nbsp;<a href="download.php?download_file=insurance.pdf&vid=<?php echo $_GET['vid']; ?>&customerno=<?php echo $_SESSION['customerno']; ?>">Download Insurance Document here</a>
                        <?php } ?>

                        <br/>  <br/>  
                        <div class="input-prepend"> 
                            <span id="set_success_ins" style="display:none;" class="btn btn-success">Information Saved successfully.</span>
                            <span id="set_error_ins" style="display:none;" class="btn btn-warning">Error, Please try again.</span>  
                            <?php
                            if (isset($alert) && $alert['insurance_expiry'] != '0000-00-00 00:00:00') {
                                //$alert['insurance_expiry']  = date('Y-m-d H:i:s');
                                ?>
                                <span class="add-on" id="add_puc">Expiry Date </span><input id="ExpiryDate_INS" name="ExpiryDate_INS" type="text" class="input-small"  value="<?php echo date("d-m-Y", strtotime($alert['insurance_expiry'])); ?>" required=""  />
                                <?php
                            } else {
                                ?>
                                <span class="add-on" id="add_puc">Expiry Date </span><input id="ExpiryDate_INS" name="ExpiryDate_INS" type="text" class="input-small"  value="<?php echo date("d-m-Y"); ?>" required=""  />
                                <?php
                            }
                            ?>
                            &nbsp;&nbsp;
                            <span class="add-on" id="add_ins1">Alert by Email / SMS</span>
                            <select id="ExpiryDate_INS_rem" onchange='setINS();'>
                                <option value="-1" <?php
                                if ($alert['insurance_sms_email'] == -1 || $alert['insurance_sms_email'] == '') {
                                    echo "Selected";
                                }
                                ?>>Never Remind me</option>
                                <option value="0" <?php
                                if ($alert['insurance_sms_email'] == 0) {
                                    echo "Selected";
                                }
                                ?>>Remind me on the day of Expiry</option>
                                <option value="1" <?php
                                if ($alert['insurance_sms_email'] == 1) {
                                    echo "Selected";
                                }
                                ?>>Remind me 1 day prior to Expiry</option>
                                <option value="2" <?php
                                if ($alert['insurance_sms_email'] == 2) {
                                    echo "Selected";
                                }
                                ?>>Remind me 2 days prior to Expiry</option>
                                <option value="7" <?php
                                if ($alert['insurance_sms_email'] == 7) {
                                    echo "Selected";
                                }
                                ?>>Remind me 1 week prior to Expiry</option> 
                                <option value="30" <?php
                                if ($alert['insurance_sms_email'] == 30) {
                                    echo "Selected";
                                }
                                ?>>Remind me 1 month prior to Expiry</option>
                            </select>
                            &nbsp;
                            <input type="button" id="set_INS" onclick="insset(<?php echo $_GET['vid']; ?>);" value="Set" class="btn btn-success" />                                                
                        </div>
                    </div>
                </div>                            
                <div class="input-prepend formSep ">
                    <b> Other1 </b>
                </div>	

                <div class="control-group" style="text-align: left; margin-left: 10px;width: 90%;">
                    <div class="input-prepend formSep">

                        <?php $uploaddir_other = "../../customer/" . $_SESSION['customerno'] . "/vehicleid/" . $_GET['vid'] . "/" . $filename->other1 . ".pdf"; ?>
                        <span class="add-on">Name</span><input type="text" name="other" id="other" value="<?php echo $filename->other1; ?>">
                        <input type="file" name="other_file" id="other_file" onchange="checkname(this);" <?php if (file_exists($uploaddir_other)) { ?>onclick="return confirm('Do you want to replace your existing file ?');" <?php } ?>>
                        <input type="button" id="upload_other" onclick="uploadFilesOTHER();" value="Upload" class="btn btn-success" />
                        <?php
                        if (file_exists($uploaddir_other)) {
                            ?>
                            &nbsp;&nbsp;<a href="download.php?download_file=<?php echo $filename->other1; ?>.pdf&vid=<?php echo $_GET['vid']; ?>&customerno=<?php echo $_SESSION['customerno']; ?>">Download <?php echo $filename->other1; ?> Document here</a>
                        <?php }
                        ?>

                        <br/> <br/>   
                        <div class="input-prepend"> 
                            <span id="set_success_oth1" style="display:none;" class="btn btn-success">Information Saved successfully.</span>
                            <span id="set_error_oth1" style="display:none;" class="btn btn-warning">Error, Please try again.</span>  
                            <?php
                            if (isset($alert) && $alert['other1_expiry'] != '0000-00-00 00:00:00') {
                                //$alert['other1_expiry'] = date('Y-m-d H:i:s');
                                ?>
                                <span class="add-on" id="add_puc">Expiry Date </span><input id="ExpiryDate_OTH1" name="ExpiryDate_OTH1" type="text" class="input-small"  value="<?php echo date("d-m-Y", strtotime($alert['other1_expiry'])); ?>" required=""  />
                                <?php
                            } else {
                                ?>
                                <span class="add-on" id="add_puc">Expiry Date </span><input id="ExpiryDate_OTH1" name="ExpiryDate_OTH1" type="text" class="input-small"  value="<?php echo date("d-m-Y"); ?>" required=""  />
                                <?php
                            }
                            ?>
                            &nbsp;&nbsp;
                            <span class="add-on" id="add_oth11">Alert by Email / SMS</span>
                            <select id="ExpiryDate_OTH1_rem" onchange='setOTH1();'>
                                <option value="-1" <?php
                                if ($alert['other1_sms_email'] == -1 || $alert['other1_sms_email'] == '') {
                                    echo "Selected";
                                }
                                ?>>Never Remind me</option>
                                <option value="0" <?php
                                if ($alert['other1_sms_email'] == 0) {
                                    echo "Selected";
                                }
                                ?>>Remind me on the day of Expiry</option>
                                <option value="1" <?php
                                if ($alert['other1_sms_email'] == 1) {
                                    echo "Selected";
                                }
                                ?>>Remind me 1 day prior to Expiry</option>
                                <option value="2" <?php
                                if ($alert['other1_sms_email'] == 2) {
                                    echo "Selected";
                                }
                                ?>>Remind me 2 days prior to Expiry</option>
                                <option value="7" <?php
                                if ($alert['other1_sms_email'] == 7) {
                                    echo "Selected";
                                }
                                ?>>Remind me 1 week prior to Expiry</option>
                                <option value="30" <?php
                                if ($alert['other1_sms_email'] == 30) {
                                    echo "Selected";
                                }
                                ?>>Remind me 1 month prior to Expiry</option>
                            </select>
                            &nbsp;
                            <input type="button" id="set_OTH1" onclick="oth1set(<?php echo $_GET['vid']; ?>);" value="Set" class="btn btn-success" />                                                
                        </div>
                    </div>
                </div>                            
                <div class="input-prepend formSep ">
                    <b> Other2 </b>
                </div>	

                <div class="control-group" style="text-align: left; margin-left: 10px;width: 90%;">
                    <div class="input-prepend formSep">
                        <?php $uploaddir_other2 = "../../customer/" . $_SESSION['customerno'] . "/vehicleid/" . $_GET['vid'] . "/" . $filename->other2 . ".pdf"; ?>
                        <span class="add-on">Name</span><input type="text" name="other1" id="other1" value="<?php echo $filename->other2; ?>">
                        <input type="file" name="other1_file" id="other1_file" onchange="checkname(this);" <?php if (file_exists($uploaddir_other2)) { ?>onclick="return confirm('Do you want to replace your existing file ?');" <?php } ?>>
                        <input type="button" id="upload_other1" onclick="uploadFilesOTHER1();" value="Upload" class="btn btn-success" />
                        <?php
                        if (file_exists($uploaddir_other2)) {
                            ?>
                            &nbsp;&nbsp;<a href="download.php?download_file=<?php echo $filename->other2; ?>.pdf&vid=<?php echo $_GET['vid']; ?>&customerno=<?php echo $_SESSION['customerno']; ?>">Download <?php echo $filename->other2; ?> Document here</a>
                        <?php }
                        ?>

                        <br/>   <br/> 
                        <div class="input-prepend"> 
                            <span id="set_success_oth2" style="display:none;" class="btn btn-success">Information Saved successfully.</span>
                            <span id="set_error_oth2" style="display:none;" class="btn btn-warning">Error, Please try again.</span>  
                            <?php
                            if (isset($alert) && $alert['other2_expiry'] != '0000-00-00 00:00:00') {
                                // $alert['other2_expiry'] = date('y-m-d H:i:s');
                                ?>
                                <span class="add-on" id="add_puc">Expiry Date </span>
                                <input id="ExpiryDate_OTH2" name="ExpiryDate_OTH2" type="text" class="input-small"  value="<?php echo date("d-m-Y", strtotime($alert['other2_expiry'])); ?>" required=""  />
                                <?php
                            } else {
                                ?>
                                <span class="add-on" id="add_puc">Expiry Date </span><input id="ExpiryDate_OTH2" name="ExpiryDate_OTH2" type="text" class="input-small"  value="<?php echo date("d-m-Y"); ?>" required=""  />
                                <?php
                            }
                            ?>
                            &nbsp;&nbsp;
                            <span class="add-on" id="add_oth22">Alert by Email / SMS</span>
                            <select id="ExpiryDate_OTH2_rem" onchange='setOTH2();'>
                                <option value="-1" <?php
                                if ($alert['other2_sms_email'] == -1 || $alert['other2_sms_email'] == '') {
                                    echo "Selected";
                                }
                                ?>>Never Remind me</option>
                                <option value="0" <?php
                                if ($alert['other2_sms_email'] == 0) {
                                    echo "Selected";
                                }
                                ?>>Remind me on the day of Expiry</option>
                                <option value="1" <?php
                                if ($alert['other2_sms_email'] == 1) {
                                    echo "Selected";
                                }
                                ?>>Remind me 1 day prior to Expiry</option>
                                <option value="2" <?php
                                if ($alert['other2_sms_email'] == 2) {
                                    echo "Selected";
                                }
                                ?>>Remind me 2 days prior to Expiry</option>
                                <option value="7" <?php
                                if ($alert['other2_sms_email'] == 7) {
                                    echo "Selected";
                                }
                                ?>>Remind me 1 week prior to Expiry</option>
                                <option value="30" <?php
                                if ($alert['other2_sms_email'] == 30) {
                                    echo "Selected";
                                }
                                ?>>Remind me 1 month prior to Expiry</option>
                            </select>
                            &nbsp;
                            <input type="button" id="set_OTH2" onclick="oth2set(<?php echo $_GET['vid']; ?>);" value="Set" class="btn btn-success" />                                                
                        </div>
                    </div>
                </div>
                <div class="input-prepend formSep ">
                    <b> Other3 </b>
                </div>	

                <div class="control-group" style="text-align: left; margin-left: 10px;width: 90%;">
                    <div class="input-prepend formSep">
                        <?php $uploaddir_other3 = "../../customer/" . $_SESSION['customerno'] . "/vehicleid/" . $_GET['vid'] . "/" . $filename->other3 . ".pdf"; ?>
                        <span class="add-on">Name</span><input type="text" name="other2" id="other2" value="<?php echo $filename->other3; ?>">
                        <input type="file" name="other2_file" id="other2_file" onchange="checkname(this);" <?php if (file_exists($uploaddir_other3)) { ?>onclick="return confirm('Do you want to replace your existing file ?');" <?php } ?>>
                        <input type="button" id="upload_other2" onclick="uploadFilesOTHER2();" value="Upload" class="btn btn-success" />
                        <?php
                        if (file_exists($uploaddir_other3)) {
                            ?>
                            &nbsp;&nbsp;<a href="download.php?download_file=<?php echo $filename->other3; ?>.pdf&vid=<?php echo $_GET['vid']; ?>&customerno=<?php echo $_SESSION['customerno']; ?>">Download <?php echo $filename->other3; ?> Document here</a>
                        <?php }
                        ?>

                        <br/>    <br/>
                        <div class="input-prepend">
                            <span id="set_success_oth3" style="display:none;" class="btn btn-success">Information Saved successfully.</span>
                            <span id="set_error_oth3" style="display:none;" class="btn btn-warning">Error, Please try again.</span>  
                            <?php
                            if (isset($alert) && $alert['other3_expiry'] != '0000-00-00 00:00:00') {
                                //$alert['other3_expiry']=date('Y-m-d H:i:s');
                                ?>
                                <span class="add-on" id="add_puc">Expiry Date </span><input id="ExpiryDate_OTH3" name="ExpiryDate_OTH3" type="text" class="input-small"  value="<?php echo date("d-m-Y", strtotime($alert['other3_expiry'])); ?>" required=""  />
                                <?php
                            } else {
                                ?>
                                <span class="add-on" id="add_puc">Expiry Date </span><input id="ExpiryDate_OTH3" name="ExpiryDate_OTH3" type="text" class="input-small"  value="<?php echo date("d-m-Y"); ?>" required=""  />
                                <?php
                            }
                            ?>
                            &nbsp;&nbsp;
                            <span class="add-on" id="add_oth33">Alert by Email / SMS</span>
                            <select id="ExpiryDate_OTH3_rem" onchange='setOTH3();'>
                                <option value="-1" <?php
                                if ($alert['other3_sms_email'] == -1 || $alert['other3_sms_email'] == '') {
                                    echo "Selected";
                                }
                                ?>>Never Remind me</option>
                                <option value="0" <?php
                                if ($alert['other3_sms_email'] == 0) {
                                    echo "Selected";
                                }
                                ?>>Remind me on the day of Expiry</option>
                                <option value="1" <?php
                                if ($alert['other3_sms_email'] == 1) {
                                    echo "Selected";
                                }
                                ?>>Remind me 1 day prior to Expiry</option>
                                <option value="2" <?php
                                if ($alert['other3_sms_email'] == 2) {
                                    echo "Selected";
                                }
                                ?>>Remind me 2 days prior to Expiry</option>
                                <option value="7" <?php
                                if ($alert['other3_sms_email'] == 7) {
                                    echo "Selected";
                                }
                                ?>>Remind me 1 week prior to Expiry</option>
                                <option value="30" <?php
                                if ($alert['other3_sms_email'] == 30) {
                                    echo "Selected";
                                }
                                ?>>Remind me 1 month prior to Expiry</option>
                            </select>
                            &nbsp;
                            <input type="button" id="set_OTH3" onclick="oth3set(<?php echo $_GET['vid']; ?>);" value="Set" class="btn btn-success" />                                                
                        </div>
                    </div>
                </div> 

                <!--invoice upload start -->

                <div class="input-prepend formSep ">
                    <b> Other4 </b>
                </div>	

                <div class="control-group" style="text-align: left; margin-left: 10px;width: 90%;">
                    <div class="input-prepend formSep">
                        <?php $uploaddir_other4 = "../../customer/" . $_SESSION['customerno'] . "/vehicleid/" . $_GET['vid'] . "/" . $filename->other4 . ".pdf"; ?>
                        <span class="add-on">Name</span><input type="text" name="other3" id="other3" value="<?php echo $filename->other4; ?>">
                        <input type="file" name="other3_file" id="other3_file" onchange="checkname(this);" <?php if (file_exists($uploaddir_other4)) { ?>onclick="return confirm('Do you want to replace your existing file ?');" <?php } ?>>
                        <input type="button" id="upload_other3" onclick="uploadFilesOTHER3();" value="Upload" class="btn btn-success" />
                        <?php
                        if (file_exists($uploaddir_other4)) {
                            ?>
                            &nbsp;&nbsp;<a href="download.php?download_file=<?php echo $filename->other4; ?>.pdf&vid=<?php echo $_GET['vid']; ?>&customerno=<?php echo $_SESSION['customerno']; ?>">Download <?php echo $filename->other4; ?> Document here</a>
                        <?php } ?>
                        <br/>    <br/>
                        <div class="input-prepend">
                            <span id="set_success_oth4" style="display:none;" class="btn btn-success">Information Saved successfully.</span>
                            <span id="set_error_oth4" style="display:none;" class="btn btn-warning">Error, Please try again.</span>  
                            <?php
                            if (isset($alert) && $alert['other4_expiry'] != '0000-00-00 00:00:00') {
                                //$alert['other3_expiry']=date('Y-m-d H:i:s');
                                ?>
                                <span class="add-on" id="add_puc">Expiry Date </span><input id="ExpiryDate_OTH4" name="ExpiryDate_OTH4" type="text" class="input-small"  value="<?php echo date("d-m-Y", strtotime($alert['other4_expiry'])); ?>" required=""  />
                                <?php
                            } else {
                                ?>
                                <span class="add-on" id="add_puc">Expiry Date </span><input id="ExpiryDate_OTH4" name="ExpiryDate_OTH4" type="text" class="input-small"  value="<?php echo date("d-m-Y"); ?>" required=""  />
                                <?php
                            }
                            ?>
                            &nbsp;&nbsp;
                            <span class="add-on" id="add_oth33">Alert by Email / SMS</span>
                            <select id="ExpiryDate_OTH4_rem" onchange='setOTH4();'>
                                <option value="-1" <?php
                                if ($alert['other4_sms_email'] == -1 || $alert['other4_sms_email'] == '') {
                                    echo "Selected";
                                }
                                ?>>Never Remind me</option>
                                <option value="0" <?php
                                if ($alert['other4_sms_email'] == 0) {
                                    echo "Selected";
                                }
                                ?>>Remind me on the day of Expiry</option>
                                <option value="1" <?php
                                if ($alert['other4_sms_email'] == 1) {
                                    echo "Selected";
                                }
                                ?>>Remind me 1 day prior to Expiry</option>
                                <option value="2" <?php
                                if ($alert['other4_sms_email'] == 2) {
                                    echo "Selected";
                                }
                                ?>>Remind me 2 days prior to Expiry</option>
                                <option value="7" <?php
                                if ($alert['other4_sms_email'] == 7) {
                                    echo "Selected";
                                }
                                ?>>Remind me 1 week prior to Expiry</option>
                                <option value="30" <?php
                                if ($alert['other4_sms_email'] == 30) {
                                    echo "Selected";
                                }
                                ?>>Remind me 1 month prior to Expiry</option>
                            </select>
                            &nbsp;
                            <input type="button" id="set_OTH4" onclick="oth4set(<?php echo $_GET['vid']; ?>);" value="Set" class="btn btn-success" />                                                
                        </div>
                    </div>
                </div>
                <!-- invoice upload end-->
                
                <!--other 5 or 6 start -->
                <div class="input-prepend formSep ">
                    <b> Other5 </b>
                </div>	

                <div class="control-group" style="text-align: left; margin-left: 10px;width: 90%;">
                    <div class="input-prepend formSep">
                        <?php $uploaddir_other5 = "../../customer/" . $_SESSION['customerno'] . "/vehicleid/" . $_GET['vid'] . "/" . $filename->other5 . ".pdf"; ?>
                        <span class="add-on">Name</span><input type="text" name="other4" id="other4" value="<?php echo $filename->other5; ?>">
                        <input type="file" name="other4_file" id="other4_file" onchange="checkname(this);" <?php if (file_exists($uploaddir_other5)) { ?>onclick="return confirm('Do you want to replace your existing file ?');" <?php } ?>>
                        <input type="button" id="upload_other4" onclick="uploadFilesOTHER4();" value="Upload" class="btn btn-success" />
                        <?php
                        if (file_exists($uploaddir_other5)) {
                            ?>
                            &nbsp;&nbsp;<a href="download.php?download_file=<?php echo $filename->other5; ?>.pdf&vid=<?php echo $_GET['vid']; ?>&customerno=<?php echo $_SESSION['customerno']; ?>">Download <?php echo $filename->other5; ?> Document here</a>
                        <?php } ?>
                        <br/>    <br/>
                        <div class="input-prepend">
                            <span id="set_success_oth5" style="display:none;" class="btn btn-success">Information Saved successfully.</span>
                            <span id="set_error_oth5" style="display:none;" class="btn btn-warning">Error, Please try again.</span>  
                            <?php
                            if (isset($alert) && $alert['other5_expiry'] != '0000-00-00 00:00:00') {
                                //$alert['other3_expiry']=date('Y-m-d H:i:s');
                                ?>
                                <span class="add-on" id="add_puc">Expiry Date </span><input id="ExpiryDate_OTH5" name="ExpiryDate_OTH5" type="text" class="input-small"  value="<?php echo date("d-m-Y", strtotime($alert['other5_expiry'])); ?>" required=""  />
                                <?php
                            } else {
                                ?>
                                <span class="add-on" id="add_puc">Expiry Date </span><input id="ExpiryDate_OTH5" name="ExpiryDate_OTH5" type="text" class="input-small"  value="<?php echo date("d-m-Y"); ?>" required=""  />
                                <?php
                            }
                            ?>
                            &nbsp;&nbsp;
                            <span class="add-on" id="add_oth33">Alert by Email / SMS</span>
                            <select id="ExpiryDate_OTH5_rem" onchange='setOTH5();'>
                                <option value="-1" <?php
                                if ($alert['other5_sms_email'] == -1 || $alert['other5_sms_email'] == '') {
                                    echo "Selected";
                                }
                                ?>>Never Remind me</option>
                                <option value="0" <?php
                                if ($alert['other5_sms_email'] == 0) {
                                    echo "Selected";
                                }
                                ?>>Remind me on the day of Expiry</option>
                                <option value="1" <?php
                                if ($alert['other5_sms_email'] == 1) {
                                    echo "Selected";
                                }
                                ?>>Remind me 1 day prior to Expiry</option>
                                <option value="2" <?php
                                if ($alert['other5_sms_email'] == 2) {
                                    echo "Selected";
                                }
                                ?>>Remind me 2 days prior to Expiry</option>
                                <option value="7" <?php
                                if ($alert['other5_sms_email'] == 7) {
                                    echo "Selected";
                                }
                                ?>>Remind me 1 week prior to Expiry</option>
                                <option value="30" <?php
                                if ($alert['other5_sms_email'] == 30) {
                                    echo "Selected";
                                }
                                ?>>Remind me 1 month prior to Expiry</option>
                            </select>
                            &nbsp;
                            <input type="button" id="set_OTH5" onclick="oth5set(<?php echo $_GET['vid']; ?>);" value="Set" class="btn btn-success" />                                                
                        </div>
                    </div>
                </div>
                
                
                <div class="input-prepend formSep ">
                    <b> Other6 </b>
                </div>	

                <div class="control-group" style="text-align: left; margin-left: 10px;width: 90%;">
                    <div class="input-prepend formSep">
                        <?php $uploaddir_other6 = "../../customer/" . $_SESSION['customerno'] . "/vehicleid/" . $_GET['vid'] . "/" . $filename->other6 . ".pdf"; ?>
                        <span class="add-on">Name</span><input type="text" name="other6" id="other6" value="<?php echo $filename->other4; ?>">
                        <input type="file" name="other5_file" id="other5_file" onchange="checkname(this);" <?php if (file_exists($uploaddir_other5)) { ?>onclick="return confirm('Do you want to replace your existing file ?');" <?php } ?>>
                        <input type="button" id="upload_other4" onclick="uploadFilesOTHER5();" value="Upload" class="btn btn-success" />
                        <?php
                        if (file_exists($uploaddir_other6)){
                            ?>
                            &nbsp;&nbsp;<a href="download.php?download_file=<?php echo $filename->other6; ?>.pdf&vid=<?php echo $_GET['vid']; ?>&customerno=<?php echo $_SESSION['customerno']; ?>">Download <?php echo $filename->other6; ?> Document here</a>
                        <?php } ?>
                        <br/>    <br/>
                        <div class="input-prepend">
                            <span id="set_success_oth6" style="display:none;" class="btn btn-success">Information Saved successfully.</span>
                            <span id="set_error_oth6" style="display:none;" class="btn btn-warning">Error, Please try again.</span>  
                            <?php
                            if (isset($alert) && $alert['other6_expiry'] != '0000-00-00 00:00:00') {
                                //$alert['other3_expiry']=date('Y-m-d H:i:s');
                                ?>
                                <span class="add-on" id="add_puc">Expiry Date </span><input id="ExpiryDate_OTH6" name="ExpiryDate_OTH6" type="text" class="input-small"  value="<?php echo date("d-m-Y", strtotime($alert['other6_expiry'])); ?>" required=""  />
                                <?php
                            }else{
                                ?>
                                <span class="add-on" id="add_puc">Expiry Date </span><input id="ExpiryDate_OTH6" name="ExpiryDate_OTH6" type="text" class="input-small"  value="<?php echo date("d-m-Y"); ?>" required=""  />
                                <?php
                            }
                            ?>
                            &nbsp;&nbsp;
                            <span class="add-on" id="add_oth33">Alert by Email / SMS</span>
                            <select id="ExpiryDate_OTH6_rem" onchange='setOTH6();'>
                                <option value="-1" <?php
                                if ($alert['other6_sms_email'] == -1 || $alert['other6_sms_email'] == '') {
                                    echo "Selected";
                                }
                                ?>>Never Remind me</option>
                                <option value="0" <?php
                                if ($alert['other6_sms_email'] == 0) {
                                    echo "Selected";
                                }
                                ?>>Remind me on the day of Expiry</option>
                                <option value="1" <?php
                                if ($alert['other6_sms_email'] == 1) {
                                    echo "Selected";
                                }
                                ?>>Remind me 1 day prior to Expiry</option>
                                <option value="2" <?php
                                if ($alert['other6_sms_email'] == 2) {
                                    echo "Selected";
                                }
                                ?>>Remind me 2 days prior to Expiry</option>
                                <option value="7" <?php
                                if ($alert['other6_sms_email'] == 7) {
                                    echo "Selected";
                                }
                                ?>>Remind me 1 week prior to Expiry</option>
                                <option value="30" <?php
                                if ($alert['other6_sms_email'] == 30) {
                                    echo "Selected";
                                }
                                ?>>Remind me 1 month prior to Expiry</option>
                            </select>
                            &nbsp;
                            <input type="button" id="set_OTH6" onclick="oth6set(<?php echo $_GET['vid']; ?>);" value="Set" class="btn btn-success" />                                                
                        </div>
                    </div>
                </div>
                
                
                
                
                
                <!--other 5 or 6  end-->
                
                
                
                
                
                
                
                
                
                

                <div class="input-prepend formSep ">
                    <b> Notes </b>
                </div>	
                <span id="set_success_oth4" style="display:none;" class="btn btn-success">Information Saved successfully.</span>
                <span id="set_error_oth4" style="display:none;" class="btn btn-warning">Error, Please try again.</span>  
                <div class="control-group" style="text-align: left; margin-left: 10px;width: 90%;">
                    <div class="input-prepend formSep">
                        <div class="input-prepend">
                            <textarea name="notes" id="notes" style="width:500px;"><?php echo $alert['notes']; ?></textarea>
                            &nbsp;
                        </div>
                    </div>
                </div> 
                <?php if ($_SESSION['use_tracking'] == '1') { ?>
                    <br/>
                    <div class="input-prepend formSep">Geo Tags</div>
                    <table class="table1" style="width:90%;">
                        <tr>
                            <td>Checkpoint</td>
                            <td>
                                <select id="chkid" name="chkid" onchange="addchk()">
                                    <option value="-1">Select Checkpoint</option>
                                    <?php
                                    $checkpoints = getchks();
                                    if (isset($checkpoints)) {
                                        foreach ($checkpoints as $checkpoint) {
                                            echo "<option value='$checkpoint->checkpointid'>$checkpoint->cname</option>";
                                        }
                                    }
                                    ?>
                                </select>
                            </td>
                            <td><input type="button" value="Add All Checkpoints" class="btn  btn-primary" onclick="addallchk()"></td>
                        </tr>
                        <tr>
                            <td colspan="3" style="padding: 5px;">
                                <div id="checkpoint_list">
                                    <?php
                                    $mapedchks = getmappedchks($_GET['vid']);
                                    if (isset($mapedchks)) {
                                        foreach ($mapedchks as $thischeckpoint) {
                                            ?>
                                            <input type="hidden" class="mappedchkpt" id="hid_c<?php echo($thischeckpoint->checkpointid); ?>" rel="<?php echo($thischeckpoint->checkpointid); ?>" value="<?php echo($thischeckpoint->cname); ?>">
                                            <?php
                                        }
                                    }
                                    ?>
                                </div>
                            </td>
                        </tr>

                        <tr>
                            <td>Fence</td>
                            <td>
                                <select id="fenceid" name="fenceid" onchange="addfence()">
                                    <option value="-1">Select Fence</option>
                                    <?php
                                    $fences = getfences();
                                    if (isset($fences)) {
                                        foreach ($fences as $fence) {
                                            echo "<option value='$fence->fenceid'>$fence->fencename</option>";
                                        }
                                    }
                                    ?>
                                </select>
                            </td>
                            <td><input type="button" value="Add All Fences" class="btn  btn-primary" onclick="addallfence()"></td>
                        </tr>
                        <tr>
                            <td style="padding: 5px;" colspan="3">
                                <div id="fence_list">
                                    <?php
                                    $mapedfences = getmappedfences($_GET['vid']);
                                    if (isset($mapedfences)) {
                                        foreach ($mapedfences as $thisfence) {
                                            ?>
                                            <input type="hidden" class="mappedfence" id="hid_f<?php echo($thisfence->fenceid); ?>" rel="<?php echo($thisfence->fenceid); ?>" value="<?php echo($thisfence->fencename); ?>">
                                            <?php
                                        }
                                    }
                                    ?>
                                </div>
                            </td>
                        </tr>

                    </table>

                <?php } ?>
                <?php
                $formdatarr = array(
                    "old_kind" => $general->kind,
                    "old_owner_name" => $general->owner_name,
                    "old_rto_location" => $general->rto_location,
                    "old_make" => $general->makeid,
                    "old_model" => $general->modelid,
                    "old_monthofman" => $general->manufacturing_month,
                    "old_yearofman" => $general->manufacturing_year,
                    "old_branchid" => $general->branchid,
                    "old_PDate" => $PDate,
                    "old_start_meter" => $general->start_meter_reading,
                    "old_overspeed" => $general->overspeed_limit,
                    "old_fueltype" => $general->fueltype,
                    "oldftcap" => $general->fuelcapacity,
                    "old_RDate" => $RDate,
                    "old_serial_number" => $general->serial_number,
                    "old_expiry_date" => $general->expiry_date,
                    "old_edit_engineno" => $description->engineno,
                    "old_chasisno" => $description->chasisno,
                    "old_veh_purpose" => $description->vehicle_purpose,
                    "old_veh_type" => $description->vehicle_type,
                    "old_dealerid" => isset($description->dealerid)?$description->dealerid:"0",
                    "old_code_dealer" => isset($dealer_data->code) ? $dealer_data->code : "",
                    "old_invoiceno" => $description->invoiceno,
                    "old_invoice_date" => $invoicedate,
                    "old_invoiceamt" => $description->invoiceamt,
                    "old_seatcapacity" => $description->seatcapacity,
                    "old_insurance_value" => isset($insurance->value) ? $insurance->value : null,
                    "old_premium_value" => isset($insurance->premium) ? $insurance->premium : null,
                    "old_StartDate" => isset($insurance->start_date) ? $insurance->start_date : null,
                    "old_EndDate" => isset($insurance->end_date) ? $insurance->end_date : null,
                    "old_edit_insurance_company" => $insurance->companyid,
                    "old_near_place" => isset($insurance->claim_place) ? $insurance->claim_place : null,
                    "old_ncb" => isset($insurance->ncb) ? $insurance->ncb : null,
                    "old_ins_notes" => isset($insurance->notes) ? $insurance->notes : null,
                    "old_insdealerid" => $insurance->ins_dealerid,
                    "oldpolno" => $insurance->polno,
                    "old_margin_amt" => isset($loan->margin_amt)?$loan->margin_amt:'',
                    "old_loan_amt" => isset($loan->loan_amt)?$loan->loan_amt:"",
                    "old_emi_amt" => isset($loan->emi_amt)?$loan->emi_amt:'',
                    "old_loan_tenure" => isset($loan->loan_tenure)?$loan->loan_tenure:'',
                    "old_financier" => isset($loan->financier) ? $loan->financier : "",
                    "old_StartDateloan" => isset($loan->startdate)?date("d-m-Y", strtotime($loan->startdate)):"",
                    "old_EndDateloan" => isset($loan->enddate)?date("d-m-Y", strtotime($loan->enddate)):"",
                    "old_loan_accno" => isset($loan->loan_accno)?$loan->loan_accno:"",
                    "old_emidate" => isset($loan->emidate)?$loan->emidate:"",
                    "old_cap_EDate" => isset($capitalization->date)?$capitalization->date:"",
                    "old_edit_cap_cost" => isset($capitalization->cost)?$capitalization->cost:"",
                    "old_cap_code" => isset($capitalization->maintenance_percent)?$capitalization->maintenance_percent:"",
                    "old_cap_address" => $capitalization->address,
                    "old_taxDate" => isset($taxDate)?$taxDate:"",
                    "old_permitDate" => isset($permitDate)?$permitDate:"",
                    "old_fitDate" => isset($fitDate)?$fitDate:"",
                    
                );
                
                 $formdata = json_encode($formdatarr); 
                 $datat = htmlspecialchars($formdata,ENT_QUOTES);
                ?>

            </div><br/><br/>
            <?php if ($_SESSION['customerno'] == 118) { ?>
                <input type="button" onclick="editvehicles_trigon();" id="save_vehicle" value="Save" class="btn btn-success" />
                <input type="button" id='sendapproval' onclick="sendapproval_new_trigon(<?php echo $datat;?>);" value="Send For Approval" disabled="" class="btn btn-success">
            <?php } else { ?>
                <input type="button" onclick="editvehicles();" id="save_vehicle" value="Save" class="btn btn-success" />
                <input type="button" id='sendapproval' onclick="sendapproval_new();" value="Send For Approval" disabled="" class="btn btn-success">
            <?php } ?>

        </fieldset>


    </form>
</div>
<div class="modal hide" id="addtax">
    <form class="form-horizontal" id="add_tax">
        <fieldset>
            <div class="modal-header">
                <button class="close" data-dismiss="modal">×</button>
                <h4 style="color:#0679c0">Add Renewal</h4>
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

                        function getcurrentdate() {
                            $currentdate = strtotime(date("Y-m-d H:i:s"));
                            $currentdate = substr($currentdate, '0', 11);
                            return $currentdate;
                        }

                        $currentdate = getcurrentdate();
                        ?>            

                        <div class="control-group">
                            <div class="input-prepend "> <span class="add-on" style="color:#000000">Type</span>
                                <select name="tax_type" id="tax_type">
                                    <option value="">Select Renewal Type</option>
                                    <option value="1">Road Tax</option>
                                    <option value="2">Registration Tax</option>
                                    <?php if ($_SESSION['customerno'] == 64) { ?>
                                        <option value="3">Tax Exemption Certificate</option>
                                        <option value="4">Fire Extinguisher Refill</option>
                                        <option value="5">First AID Kit Renewal</option>
                                        <option value="6">Speed Governer Renewal</option>
                                    <?php } ?>
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
                                <td><input id="from_date" name="from_date" type="text" value="<?php echo date('d-m-Y', $currentdate); ?>" required/></td>
                            </tr>                  
                            <tr>
                                <td width="50%">To Date</td>
                                <td><input id="to_date" name="to_date" type="text" value="<?php echo date('d-m-Y', $currentdate); ?>" required/></td>
                            </tr>                          
                            <tr>
                                <td width="50%">Amount</td>
                                <td><input type="text" name="tax_amount" id="tax_amount" maxlength="10" ></td>
                            </tr>
                            <tr>
                                <td>Alert By Email</td>
                                <td>
                                    <select id="alert_by_email" name="alert_by_email">
                                        <option value="-1">Never Remind me</option>
                                        <option value="0">Remind me on the day of Expiry</option>
                                        <option value="1">Remind me 1 day prior to Expiry</option>
                                        <option value="2">Remind me 2 days prior to Expiry</option>
                                        <option value="7">Remind me 1 week prior to Expiry</option>                            
                                        <option value="30">Remind me 1 month prior to Expiry</option>                            
                                    </select>

                                </td>
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
                $today = date('d-m-Y', $currentdate);
                ?>        
                <span id="mr_error" style="display: none;">Please Enter Meter Reading</span>                    
                <span id="dn_error" style="display: none;">Please Select Dealer</span>                                
                <span id="notes_error" style="display: none;">Please Enter Notes</span>                                            

                <span id="qa_error" style="display: none;">Please Enter Quotation Amount</span>                                                                        

                <span id="in_error" style="display: none;">Please Enter Invoice No</span>                                                            
                <span id="ia_error" style="display: none;">Please Enter Invoice Amount</span>                                                                        

                <span id="ofas_error" style="display: none;">Please Enter <?php echo $_SESSION['ref_number']; ?></span>                                                                        


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
                                        $dealers = getdealersbytype(1, $_SESSION['roleid'], $_SESSION['heirarchy_id']);
                                        if (isset($dealers)) {
                                            foreach ($dealers as $dealer) {
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
                                <td><input id="batt_vehicle_in_date" name="batt_vehicle_in_date" type="text" value="<?php echo date('d-m-Y', $currentdate); ?>" required/></td>
                            </tr>
                            <tr>
                                <td>Vehicle Out Date</td>
                                <td><input id="batt_vehicle_out_date" name="batt_vehicle_out_date" type="text" value="<?php echo date('d-m-Y', $currentdate); ?>" required/></td>
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
                                <td><input id="batt_submission_date" name="batt_submission_date" type="text" value="<?php echo date('d-m-Y', $currentdate); ?>" required/></td>
                            </tr>                    
                            <tr>
                                <td>Quotation Approval Date</td>
                                <td><input id="batt_approval_date" name="batt_approval_date" type="text" value="<?php echo date('d-m-Y', $currentdate); ?>" required/></td>
                            </tr>                                        
                        </table>

                        <table class="table table-bordered table-striped">
                            <th colspan="2">Invoice Details</th>                            
                            <tr>
                                <td width="50%">Invoice Generation Date</td>
                                <td><input id="batt_invoice_date" name="batt_invoice_date" type="text" value="<?php echo date('d-m-Y', $currentdate); ?>" required/></td>
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
                                <td><input id="batt_payment_submission_date" name="batt_payment_submission_date" type="text" value="<?php echo date('d-m-Y', $currentdate); ?>" required/></td>
                            </tr>                                        
                            <tr>
                                <td>Payment Approval Date</td>
                                <td><input id="batt_payment_approval_date" name="batt_payment_approval_date" type="text" value="<?php echo date('d-m-Y', $currentdate); ?>" required/></td>
                            </tr>                                                            
                            <tr>

                                <td><?php echo $_SESSION['ref_number']; ?></td>

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
                $today = date('d-m-Y', $currentdate);
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

                <span id="ofas_error" style="display: none;">Please Enter <?php echo $_SESSION['ref_number']; ?></span>                                                                        

                <span id="ofas_error" style="display: none;">Please Enter Transaction Reference Number</span>                                                                        

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
                                        $dealers = getdealersbytype(1, $_SESSION['roleid'], $_SESSION['heirarchy_id']);
                                        if (isset($dealers)) {
                                            foreach ($dealers as $dealer) {
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
                                <td><input id="accessory_vehicle_in_date" name="accessory_vehicle_in_date" type="text" value="<?php echo date('d-m-Y', $currentdate); ?>" required/></td>
                            </tr>
                            <tr>
                                <td>Vehicle Out Date</td>
                                <td><input id="accessory_vehicle_out_date" name="accessory_vehicle_out_date" type="text" value="<?php echo date('d-m-Y', $currentdate); ?>" required/></td>
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
                                <td><input id="accessory_submission_date" name="accessory_submission_date" type="text" value="<?php echo date('d-m-Y', $currentdate); ?>" required/></td>
                            </tr>                    
                            <tr>
                                <td>Quotation Approval Date</td>
                                <td><input id="accessory_approval_date" name="accessory_approval_date" type="text" value="<?php echo date('d-m-Y', $currentdate); ?>" required/></td>
                            </tr>                                        
                        </table>

                        <table class="table table-bordered table-striped">
                            <th colspan="2">Invoice Details</th>                            
                            <tr>
                                <td width="50%">Invoice Generation Date</td>
                                <td><input id="accessory_invoice_date" name="accessory_invoice_date" type="text" value="<?php echo date('d-m-Y', $currentdate); ?>" required/></td>
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
                                <td><input id="accessory_payment_submission_date" name="accessory_payment_submission_date" type="text" value="<?php echo date('d-m-Y', $currentdate); ?>" required/></td>
                            </tr>                                        
                            <tr>
                                <td>Payment Approval Date</td>
                                <td><input id="accessory_payment_approval_date" name="accessory_payment_approval_date" type="text" value="<?php echo date('d-m-Y', $currentdate); ?>" required/></td>
                            </tr>                                                            
                            <tr>

                                <td><?php echo $_SESSION['ref_number']; ?></td>

                                <td><input type="text" name="accessory_ofasnumber" id="accessory_ofasnumber" placeholder="e.g. 12586" maxlength="20" onkeypress="return nospecialchars(event)"></td>
                            </tr>
                        </table>

                        <table class="table table-bordered table-striped">
                            <th colspan="4">Accessory Details</th>
                            <tr>
                                <td width="15%"><b>Sr. No</b></td>
                                <td width="35%"><b>Accessory</b></td>
                                <td width="25%"><b>Total Amount</b></td>
                                <td width="25%"><b>Qauntity</b></td>
                            </tr>                  
                            <?php
                            for ($i = 1; $i < 8; $i++) {
                                ?>
                                <tr>
                                    <td><?php echo($i); ?></td>
                                    <td>
                                        <select id="accessory_select_<?php echo($i); ?>" name="accessory_select_<?php echo($i); ?>" >
                                            <option value="-1">Select Accessory</option>
                                            <?php
                                            $accessories = getaccessories();
                                            if (isset($accessories)) {
                                                foreach ($accessories as $accessory) {
                                                    echo "<option value='$accessory->id'>$accessory->name</option>";
                                                }
                                            }
                                            ?>
                                        </select>                            
                                    </td>
                                    <td><input type="text" name="amount<?php echo($i); ?>" id="amount<?php echo($i); ?>" placeholder="e.g. 125" maxlength="10" size="8" value="0" ></td>
                                    <td>
                                        <!--
                                        <div id="max_amount_<?php echo($i); ?>"></div> -->
                                        <input type="text" id="max_amount_hid_<?php echo($i); ?>" name="max_amount_hid_<?php echo($i); ?>" value="0">
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
                $today = date('d-m-Y', $currentdate);
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

                <span id="ofas_error" style="display: none;">Please Enter <?php echo $_SESSION['ref_number']; ?></span>                                                                        


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
                                        $dealers = getdealersbytype(1, $_SESSION['roleid'], $_SESSION['heirarchy_id']);
                                        if (isset($dealers)) {
                                            foreach ($dealers as $dealer) {
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
                                <td><input id="tyre_vehicle_in_date" name="tyre_vehicle_in_date" type="text" value="<?php echo date('d-m-Y', $currentdate); ?>" required/></td>
                            </tr>
                            <tr>
                                <td>Vehicle Out Date</td>
                                <td><input id="tyre_vehicle_out_date" name="tyre_vehicle_out_date" type="text" value="<?php echo date('d-m-Y', $currentdate); ?>" required/></td>
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
                                <td><input id="tyre_submission_date" name="tyre_submission_date" type="text" value="<?php echo date('d-m-Y', $currentdate); ?>" required/></td>
                            </tr>                    
                            <tr>
                                <td>Quotation Approval Date</td>
                                <td><input id="tyre_approval_date" name="tyre_approval_date" type="text" value="<?php echo date('d-m-Y', $currentdate); ?>" required/></td>
                            </tr>                                        
                        </table>

                        <table class="table table-bordered table-striped">
                            <th colspan="2">Invoice Details</th>                            
                            <tr>
                                <td width="50%">Invoice Generation Date</td>
                                <td><input id="tyre_invoice_date" name="tyre_invoice_date" type="text" value="<?php echo date('d-m-Y', $currentdate); ?>" required/></td>
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
                                <td><input id="tyre_payment_submission_date" name="tyre_payment_submission_date" type="text" value="<?php echo date('d-m-Y', $currentdate); ?>" required/></td>
                            </tr>                                        
                            <tr>
                                <td>Payment Approval Date</td>
                                <td><input id="tyre_payment_approval_date" name="tyre_payment_approval_date" type="text" value="<?php echo date('d-m-Y', $currentdate); ?>" required/></td>
                            </tr>                                                            
                            <tr>

                                <td><?php echo $_SESSION['ref_number']; ?></td>

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

                                    </select><input type="button" id="add_ttype" name="add_ttype" value="+" onclick="add_items_container('#tyrelist_container', 'tyre_type')">
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

<div class="modal hide" id="addrepair" style="width:800px;">
    <form class="form-horizontal" id="getrepair">
        <fieldset>
            <div class="modal-header">
                <button class="close" data-dismiss="modal">×</button>
                <h4 style="color:#0679c0">Repair / Service History</h4>
            </div>

            <div class="modal-body">
                <?php
                $currentdate = getcurrentdate();
                $today = date('d-m-Y', $currentdate);
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

                <span id="ofas_error" style="display: none;">Please Enter <?php echo $_SESSION['ref_number']; ?></span>                                                                        

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
                                        $dealers1 = getdealersbytype(4, $_SESSION['roleid'], $_SESSION['heirarchy_id']);
                                        if (isset($dealers1)) {
                                            foreach ($dealers1 as $dealer) {
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
                                <td><input id="repair_vehicle_in_date" name="repair_vehicle_in_date" type="text" value="<?php echo date('d-m-Y', $currentdate); ?>" required/></td>
                            </tr>
                            <tr>
                                <td>Vehicle Out Date</td>
                                <td><input id="repair_vehicle_out_date" name="repair_vehicle_out_date" type="text" value="<?php echo date('d-m-Y', $currentdate); ?>" required/></td>
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
                                <td><input id="repair_submission_date" name="repair_submission_date" type="text" value="<?php echo date('d-m-Y', $currentdate); ?>" required/></td>
                            </tr>                    
                            <tr>
                                <td>Quotation Approval Date</td>
                                <td><input id="repair_approval_date" name="repair_approval_date" type="text" value="<?php echo date('d-m-Y', $currentdate); ?>" required/></td>
                            </tr>                                        
                        </table>

                        <table class="table table-bordered table-striped">
                            <th colspan="2">Invoice Details</th>                            
                            <tr>
                                <td width="50%">Invoice Generation Date</td>
                                <td><input id="repair_invoice_date" name="repair_invoice_date" type="text" value="<?php echo date('d-m-Y', $currentdate); ?>" required/></td>
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
                                <td><input id="repair_payment_submission_date" name="repair_payment_submission_date" type="text" value="<?php echo date('d-m-Y', $currentdate); ?>" required/></td>
                            </tr>                                        
                            <tr>
                                <td>Payment Approval Date</td>
                                <td><input id="repair_payment_approval_date" name="repair_payment_approval_date" type="text" value="<?php echo date('d-m-Y', $currentdate); ?>" required/></td>
                            </tr>                                                            
                            <tr>

                                <td><?php echo $_SESSION['ref_number']; ?></td>

                                <td><input type="text" name="repair_ofasnumber" id="repair_ofasnumber" placeholder="e.g. 12586" maxlength="20" onkeypress="return nospecialchars(event)"></td>
                            </tr>
                        </table>

                        <div id="parts_service_category">
                            <div class="control-group">
                                <div class="input-prepend ">
                                    <!-- onchange="category_selector()" -->
                                    <span class="add-on" style="color:#000000">Category</span>
                                    <select id="category_type" name="category_type" >
                                        <option value="-1">Select Task</option>
                                        <option value="2">Repair</option>
                                        <option value="3">Service</option>
                                    </select>

                                </div>
                            </div>
                            <div class="control-group">
                                <div class="input-prepend">
                                    <input type="checkbox" name="partslist" id="partslist" value="1" onclick="clickparts();"/>Parts
                                    <input type="checkbox" name="taskslist" id="taskslist" value="1" onclick="clicktasks();"/>Tasks
                                </div>
                            </div>
                            <div class="control-group">
                                <div class="input-prepend">

                                    <div id="parts_category" style="display: none;" >

                                        <table class="table table-bordered table-striped">
                                            <th colspan="4">Parts List</th>
                                            <tr>
                                                <td width="15%"><b>Sr. No</b></td>
                                                <td width="35%"><b>Parts</b></td>
                                                <td width="25%"><b>Quantity </b></td>
                                                <td width="25%"><b>Cost Per Unit</b></td>
                                            </tr>                  
                                            <?php
                                            for ($i = 1; $i < 16; $i++) {
                                                ?>
                                                <tr>
                                                    <td><?php echo($i); ?></td>
                                                    <td>
                                                        <select id="parts_select_<?php echo($i); ?>" name="parts_select_<?php echo($i); ?>" >
                                                            <option value="-1">Select Parts</option>
                                                            <?php
                                                            $accessories = getpart();
                                                            if (isset($accessories)) {
                                                                foreach ($accessories as $accessory) {
                                                                    echo "<option value='$accessory->id'>$accessory->part_name</option>";
                                                                }
                                                            }
                                                            ?>
                                                        </select>                            
                                                    </td>
                                                    <td><input type="text" name="parts_qty<?php echo($i); ?>" id="parts_qty<?php echo($i); ?>" placeholder="e.g. 5" maxlength="10" size="8" value="" ></td>
                                                    <td><input type="text" name="parts_amount<?php echo($i); ?>" id="parts_amount<?php echo($i); ?>" placeholder="e.g. 125" maxlength="10" size="8" value="" ></td>
                                                <input type="hidden" id="parts_max_amount_hid_<?php echo($i); ?>" name="parts_max_amount_hid_<?php echo($i); ?>" value="0">
                                                </td>
                                                </tr>                  
                                                <?php
                                            }
                                            ?>
                                        </table>
                                    </div>

                                    <div id="tasks_category" style="display: none;" >

                                        <table class="table table-bordered table-striped">
                                            <th colspan="4">Task List</th>
                                            <tr>
                                                <td width="15%"><b>Sr. No</b></td>
                                                <td width="35%"><b>Task</b></td>
                                                <td width="25%"><b>Quantity</b></td>
                                                <td width="25%"><b>Cost Per Unit</b></td>
                                            </tr>                  
                                            <?php
                                            for ($i = 1; $i < 16; $i++) {
                                                ?>
                                                <tr>
                                                    <td><?php echo($i); ?></td>
                                                    <td>
                                                        <select id="tasks_select_<?php echo($i); ?>" name="tasks_select_<?php echo($i); ?>" >
                                                            <option value="-1">Select Tasks</option>
                                                            <?php
                                                            $accessories = gettask();
                                                            if (isset($accessories)) {
                                                                foreach ($accessories as $accessory) {
                                                                    echo "<option value='$accessory->id'>$accessory->task_name</option>";
                                                                }
                                                            }
                                                            ?>
                                                        </select>                            
                                                    </td>
                                                    <td><input type="text" name="tasks_qty<?php echo($i); ?>" id="tasks_qty<?php echo($i); ?>" placeholder="e.g. 5" maxlength="10" size="8" value="" ></td>
                                                    <td><input type="text" name="tasks_amount<?php echo($i); ?>" id="tasks_amount<?php echo($i); ?>" placeholder="e.g. 125" maxlength="10" size="8" value="" ></td>
                                                <input type="hidden" id="tasks_max_amount_hid_<?php echo($i); ?>" name="tasks_max_amount_hid_<?php echo($i); ?>" value="0">
                                                </td>
                                                </tr>                  
                                                <?php
                                            }
                                            ?>
                                        </table>
                                    </div>

                                    <div id="parts_tax" style="display: none;" >
                                        <table class="table table-bordered table-striped">
                                            <th colspan="2">Tax</th>
                                            <tr>
                                                <td width="15%"><b>Tax Amount</b></td>
                                                <td width="35%"><b><input type="text" name="p_tax" id="p_tax" placeholder="e.g. 5" maxlength="10" size="8" value="0" ></b></td>

                                            </tr> 
                                        </table>
                                    </div>
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
                                        if (isset($parts)) {
                                            foreach ($parts as $part) {
                                                echo "<option value='$part->id'>$part->part_name</option>";
                                            }
                                        }
                                        ?>
                                    </select><input type="button" id="add_parts" name="add_parts" value="+" onclick="add_items_container('#partlist_container', 'parts_select')">
                                    <div id="partlist_container"></div>
                                </div>
                            </div>
                            <div class="control-group">
                                <div class="input-prepend ">
                                    <span class="add-on" style="color:#000000">Tasks</span><select id="task_select" name="task_select">
                                        <option value="-1">Select Tasks</option>
                                        <?php
                                        $tasks = gettask();
                                        if (isset($tasks)) {
                                            foreach ($tasks as $task) {
                                                echo "<option value='$task->id'>$task->task_name</option>";
                                            }
                                        }
                                        ?>
                                    </select><input type="button" id="add_task" name="add_task" value="+" onclick="add_items_container('#tasklist_container', 'task_select')">
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
                    <span id="edit_from_error" name="edit_from_error" style="display: none;">Please Select Start Date</span>
                    <span id="edit_to_error" name="edit_to_error" style="display: none;">Please Select End Date</span>
                    <span id="edit_date_error" name="edit_date_error" style="display: none;">From Date cannot be greater than To Date</span>
                    <span id="edit_date_purchase_error" name="edit_date_purchase_error" style="display: none;">From Date cannot be less than Purchase Date</span>
                    <span id="edit_tax_datediff_error" name="edit_tax_year_error" style="display: none;">To Date cannot be less than From Date</span>
                    <span id="edit_tax_year_error" name="edit_tax_year_error" style="display: none;">From Date & To Date must not be in the range preceeding the manufacturing date of the vehicle</span>
                    <span id="edit_amount_error" name="edit_amount_error" style="display: none;">Amount must be numeric must not exceed 7 digits</span>
                    <span id="edit_registrationno_error" name="registrationno_error" style="display: none;">Registration No. must be caps alphabet. It must be maximum 14 Characters.</span>
                    <fieldset>
                        <div class="control-group">
                            <div class="input-prepend ">
                                <span class="add-on" style="color:#000000">From Date</span>
                                <input id="edit_from_date" name="edit_from_date" type="text" value=""/>
                                <span class="add-on" style="color:#000000">To Date</span>
                                <input id="edit_to_date" name="edit_to_date" type="text" value=""/>
                            </div>
                        </div>
                        <div class="control-group">
                            <div class="input-prepend ">
                                <span class="add-on" style="color:#000000">Type</span> <select name="edit_tax_type" id="edit_tax_type">
                                    <option value="">Select Tax Type</option>
                                    <option value="1">Road Tax</option>
                                    <option value="2">Registration Tax</option>
                                </select>
                                <span class="add-on" style="color:#000000">Amount</span>
                                <input type="text" name="edit_amount" id="edit_amount">
                            </div>
                        </div>
                        <div class="control-group">
                            <div class="input-prepend ">
                                <span class="add-on" id="edit_reg_name" style="display: none; color:#000000">Registration No.</span><input type="text" name="edit_registrationno" id="edit_registrationno" placeholder="registration no." style="display: none;" maxlength="14">
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
                $today = date('d-m-Y', $currentdate);
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

                <span id="ofas_error" style="display: none;">Please Enter <?php echo $_SESSION['ref_number']; ?></span>                                                                        


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
                                        $dealers = getdealersbytype(1, $_SESSION['roleid'], $_SESSION['heirarchy_id']);
                                        if (isset($dealers)) {
                                            foreach ($dealers as $dealer) {
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
                                <td><input id="batt_vehicle_in_date" name="batt_vehicle_in_date" type="text" value="<?php echo date('d-m-Y', $currentdate); ?>" required/></td>
                            </tr>
                            <tr>
                                <td>Vehicle Out Date</td>
                                <td><input id="batt_vehicle_out_date" name="batt_vehicle_out_date" type="text" value="<?php echo date('d-m-Y', $currentdate); ?>" required/></td>
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
                                <td><input id="batt_submission_date" name="batt_submission_date" type="text" value="<?php echo date('d-m-Y', $currentdate); ?>" required/></td>
                            </tr>                    
                            <tr>
                                <td>Quotation Approval Date</td>
                                <td><input id="batt_approval_date" name="batt_approval_date" type="text" value="<?php echo date('d-m-Y', $currentdate); ?>" required/></td>
                            </tr>                                        
                        </table>

                        <table class="table table-bordered table-striped">
                            <th colspan="2">Invoice Details</th>                            
                            <tr>
                                <td width="50%">Invoice Generation Date</td>
                                <td><input id="batt_invoice_date" name="batt_invoice_date" type="text" value="<?php echo date('d-m-Y', $currentdate); ?>" required/></td>
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
                                <td><input id="batt_payment_submission_date" name="batt_payment_submission_date" type="text" value="<?php echo date('d-m-Y', $currentdate); ?>" required/></td>
                            </tr>                                        
                            <tr>
                                <td>Payment Approval Date</td>
                                <td><input id="batt_payment_approval_date" name="batt_payment_approval_date" type="text" value="<?php echo date('d-m-Y', $currentdate); ?>" required/></td>
                            </tr>                                                            
                            <tr>

                                <td><?php echo $_SESSION['ref_number']; ?></td>

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
                $today = date('d-m-Y', $currentdate);
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
                <span id="ofas_error" style="display: none;">Please Enter <?php echo $_SESSION['ref_number']; ?></span>                                                                        


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
                                        $dealers = getdealersbytype(1, $_SESSION['roleid'], $_SESSION['heirarchy_id']);
                                        if (isset($dealers)) {
                                            foreach ($dealers as $dealer) {
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
                                <td><input id="accessory_vehicle_in_date" name="accessory_vehicle_in_date" type="text" value="<?php echo date('d-m-Y', $currentdate); ?>" required/></td>
                            </tr>
                            <tr>
                                <td>Vehicle Out Date</td>
                                <td><input id="accessory_vehicle_out_date" name="accessory_vehicle_out_date" type="text" value="<?php echo date('d-m-Y', $currentdate); ?>" required/></td>
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
                                <td><input id="accessory_submission_date" name="accessory_submission_date" type="text" value="<?php echo date('d-m-Y', $currentdate); ?>" required/></td>
                            </tr>                    
                            <tr>
                                <td>Quotation Approval Date</td>
                                <td><input id="accessory_approval_date" name="accessory_approval_date" type="text" value="<?php echo date('d-m-Y', $currentdate); ?>" required/></td>
                            </tr>                                        
                        </table>

                        <table class="table table-bordered table-striped">
                            <th colspan="2">Invoice Details</th>                            
                            <tr>
                                <td width="50%">Invoice Generation Date</td>
                                <td><input id="accessory_invoice_date" name="accessory_invoice_date" type="text" value="<?php echo date('d-m-Y', $currentdate); ?>" required/></td>
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
                                <td><input id="accessory_payment_submission_date" name="accessory_payment_submission_date" type="text" value="<?php echo date('d-m-Y', $currentdate); ?>" required/></td>
                            </tr>                                        
                            <tr>
                                <td>Payment Approval Date</td>
                                <td><input id="accessory_payment_approval_date" name="accessory_payment_approval_date" type="text" value="<?php echo date('d-m-Y', $currentdate); ?>" required/></td>
                            </tr>                                                            
                            <tr>

                                <td><?php echo $_SESSION['ref_number']; ?></td>
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
                $today = date('d-m-Y', $currentdate);
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

                <span id="ofas_error" style="display: none;">Please Enter <?php echo $_SESSION['ref_number']; ?></span>                                                                        


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
                                        $dealers = getdealersbytype(1, $_SESSION['roleid'], $_SESSION['heirarchy_id']);
                                        if (isset($dealers)) {
                                            foreach ($dealers as $dealer) {
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
                                <td><input id="tyre_vehicle_in_date" name="tyre_vehicle_in_date" type="text" value="<?php echo date('d-m-Y', $currentdate); ?>" required/></td>
                            </tr>
                            <tr>
                                <td>Vehicle Out Date</td>
                                <td><input id="tyre_vehicle_out_date" name="tyre_vehicle_out_date" type="text" value="<?php echo date('d-m-Y', $currentdate); ?>" required/></td>
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
                                <td><input id="tyre_submission_date" name="tyre_submission_date" type="text" value="<?php echo date('d-m-Y', $currentdate); ?>" required/></td>
                            </tr>                    
                            <tr>
                                <td>Quotation Approval Date</td>
                                <td><input id="tyre_approval_date" name="tyre_approval_date" type="text" value="<?php echo date('d-m-Y', $currentdate); ?>" required/></td>
                            </tr>                                        
                        </table>

                        <table class="table table-bordered table-striped">
                            <th colspan="2">Invoice Details</th>                            
                            <tr>
                                <td width="50%">Invoice Generation Date</td>
                                <td><input id="tyre_invoice_date" name="tyre_invoice_date" type="text" value="<?php echo date('d-m-Y', $currentdate); ?>" required/></td>
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
                                <td><input id="tyre_payment_submission_date" name="tyre_payment_submission_date" type="text" value="<?php echo date('d-m-Y', $currentdate); ?>" required/></td>
                            </tr>                                        
                            <tr>
                                <td>Payment Approval Date</td>
                                <td><input id="tyre_payment_approval_date" name="tyre_payment_approval_date" type="text" value="<?php echo date('d-m-Y', $currentdate); ?>" required/></td>
                            </tr>                                                            
                            <tr>

                                <td><?php echo $_SESSION['ref_number']; ?></td>

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
                $today = date('d-m-Y', $currentdate);
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

                <span id="ofas_error" style="display: none;">Please Enter <?php echo $_SESSION['ref_number']; ?></span>                                                                        


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
                                        $dealers = getdealersbytype(1, $_SESSION['roleid'], $_SESSION['heirarchy_id']);
                                        if (isset($dealers)) {
                                            foreach ($dealers as $dealer) {
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
                                <td><input id="repair_vehicle_in_date" name="repair_vehicle_in_date" type="text" value="<?php echo date('d-m-Y', $currentdate); ?>" required/></td>
                            </tr>
                            <tr>
                                <td>Vehicle Out Date</td>
                                <td><input id="repair_vehicle_out_date" name="repair_vehicle_out_date" type="text" value="<?php echo date('d-m-Y', $currentdate); ?>" required/></td>
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
                                <td><input id="repair_submission_date" name="repair_submission_date" type="text" value="<?php echo date('d-m-Y', $currentdate); ?>" required/></td>
                            </tr>                    
                            <tr>
                                <td>Quotation Approval Date</td>
                                <td><input id="repair_approval_date" name="repair_approval_date" type="text" value="<?php echo date('d-m-Y', $currentdate); ?>" required/></td>
                            </tr>                                        
                        </table>

                        <table class="table table-bordered table-striped">
                            <th colspan="2">Invoice Details</th>                            
                            <tr>
                                <td width="50%">Invoice Generation Date</td>
                                <td><input id="repair_invoice_date" name="repair_invoice_date" type="text" value="<?php echo date('d-m-Y', $currentdate); ?>" required/></td>
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
                                <td><input id="repair_payment_submission_date" name="repair_payment_submission_date" type="text" value="<?php echo date('d-m-Y', $currentdate); ?>" required/></td>
                            </tr>                                        
                            <tr>
                                <td>Payment Approval Date</td>
                                <td><input id="repair_payment_approval_date" name="repair_payment_approval_date" type="text" value="<?php echo date('d-m-Y', $currentdate); ?>" required/></td>
                            </tr>                                                            
                            <tr>

                                <td><?php echo $_SESSION['ref_number']; ?></td>

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
                                    <td><input id="STime" name="STime" type="text" class="input-mini" data-date="00:00"/></td>
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
                                    <td width="50%">Amount Spent by <?php echo $_SESSION['customercompany'] ?>(INR)</td>
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

                                    <td width="50%"><?php echo $_SESSION['ref_number']; ?></td>

                                    <td><input type="text" name="acc_ofasnumber" class="input-small" id="acc_ofasnumber" onkeypress="return nospecialchars(event)"></td>
                                </tr>                                                                                                                                                                                                                  

                            </table>

                        </div>

                        <div class="control-group">
                            <div class="input-prepend ">
                                <input type="button" onclick="push_transaction_accident();" value="Save As History" class="btn btn-success">
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
                                    <td width="50%"><?php echo $_SESSION['ref_number']; ?></td>
                                    <td><input type="text" name="acc_ofasnumber" class="input-small" id="acc_ofasnumber" onkeypress="return nospecialchars(event)"></td>
                                </tr>                                                                                                                                                                                                                  
                            </table>

                            <div class="control-group">
                                <div class="input-prepend ">
                                    <input type="button" onclick="editacc();" value="Save As History" class="btn btn-success">
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
<script type="text/javascript">
    function clickparts() {
        var parts = $('input:checkbox[name=partslist]:checked').val();
        if (parts == 1) {
            jQuery("#parts_category").show();
            jQuery("#parts_tax").show();
        } else {
            jQuery("#parts_category").hide();
        }
    }

    function clicktasks() {
        var tasks = $('input:checkbox[name=taskslist]:checked').val();
        if (tasks == 1) {
            jQuery("#tasks_category").show()
            jQuery("#parts_tax").show();
        } else {
            jQuery("#tasks_category").hide()
        }
    }


    $(document).ready(function () {
        //$("#hypothecation").hide();
        $('.hrd').on('change', function (){
            var test = $('input[name=hypothecationrd]:radio:checked').val();
            if (test == 1) {
                $("#hypothecation").show();
            } else {
                $("#hypothecation").hide();
            }
        });
    });

</script>   
