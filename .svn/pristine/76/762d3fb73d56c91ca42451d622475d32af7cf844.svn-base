<div class="table" style="width: 70%; clear: both">
    <div class="" style="float: right;">
        <input type="hidden" id="vehicle_id" value=''/>
        <input type="hidden" id="general_complete" value=''/>
        <input type="hidden" name="customerno" id="customerno" value="<?php echo $_SESSION['customerno'] ?>">
        <input type="hidden" name="cheirarchy" id="cheirarchy" value="<?php echo $_SESSION['use_hierarchy'] ?>">    
        <br>
    </div>
    <br>
    </br>
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
    <span id="invoice_no_error" style="display:none; color: #FF0000">Please Enter Invoice No.</span> 
    <span id="invoiceamt_error" style="display:none; color: #FF0000">Please Enter Invoice Amount.</span> 
    <span id="invoice_date_error" style="display:none; color: #FF0000">Please Enter Invoice Date.</span> 
    <span id="invoice_year_error" style="display:none; color: #FF0000">Invoice Year cannot be less than Year of Manufacture.</span> 
    <span id="insurance_success" style="display:none; color: #00FF00">Insurance details added successfully</span> 
    <span id="insurance_value_error" style="display:none; color: #FF0000">Please enter digits only in Value of Insurance.</span> 
    <span id="value_empty_error" style="display:none; color: #FF0000">Value of Insurance cannot be empty</span>         
    <span id="premium_empty_error" style="display:none; color: #FF0000">Premium cannot be empty</span>                 
    <span id="company_error" style="display:none; color: #FF0000">Please select Insurance Company</span>                         
    <span id="date_conf_error" style="display:none; color: #FF0000">Start date cannot be greater than End date</span> 
    <span id="man_date_error" style="display:none; color: #FF0000">Start Date & End Date must not be in the range preceeding the manufacturing date of the vehicle</span>         
    <span id="premium_value_error" style="display:none; color: #FF0000">Please enter digits only in Premium.</span>
    <span id="capitalization_success" style="display:none; color: #00FF00">Capitalization details added successfully</span>
    <span id="cap_amount_error" style="display:none; color: #FF0000">Capitalization cost cannot be empty</span>      
    <span id="cap_date_man_error" style="display:none; color: #FF0000">Capitalization Date cannot preceed manufacturing date of the vehicle</span>
    <span id="margin_error" style="display:none; color: #FF0000">Please Enter Margin Amount</span>
    <span id="loan_error" style="display:none; color: #FF0000">Please Enter Loan Amount</span>
    <span id="emi_error" style="display:none; color: #FF0000">Please Enter EMI Amount</span>
    <span id="financier_error" style="display:none; color: #FF0000">Please Enter Financier Name</span>
    <span id="loantenure_error" style="display:none; color: #FF0000">Please Enter Loan Tenure</span>
    <span id="startloan_error" style="display:none; color: #FF0000">Start Loan Date Can not be empty</span>
    <span id="endloan_error" style="display:none; color: #FF0000">End Loan Date Can not be empty</span>

    <form method="POST" id="general">
        <fieldset>



            <style>
                .table td{ border: none; padding: 0;}
            </style>

            <div style="height: 400px; overflow-x: auto;">
                <div class="input-prepend formSep "> General Details </div>
                <table class="table" style="width: 90%; border:none;">
                    <tbody>
                        <tr>
                            <td width="20%">Vehicle No. <span style="color:#FE2E2E; ">*</span></td>
                            <td width="30%"><input type="text" name="vehicle_no" id="vehicle_no" placeholder="Vehicle No" autofocus maxlength="10" OnKeyPress="return nospecialchars(event)"></td>
                            <td width="20%">Kind</td>
                            <td width="30%">
                                <select id="kind" name="kind" onchange="kindtype();">
                                    <option value="">Select Kind</option>
                                    <option value='Bus'>Bus</option>
                                    <option value='Truck'>Truck</option>
                                    <option value='Car'>Car</option>
                                    <option value='SUV'>SUV</option>              
                                </select>
                            </td>
                        </tr>
                        <tr class="kindcar">
                            <td>Tax Date <span style="color:#FE2E2E; ">*</span></td>
                            <td><input id="taxDate" name="taxDate" type="text" class="input-small" value="<?php echo date('d-m-Y'); ?>" placeholder="e.g. <?php echo date('d-m-Y'); ?>" required=""  /></td>
                            <td>Permit <span style="color:#FE2E2E; ">*</span></td>
                            <td><input id="permitDate" name="permitDate" type="text" class="input-small" value="<?php echo date('d-m-Y'); ?>" placeholder="e.g. <?php echo date('d-m-Y'); ?>" required=""  /></td>
                        </tr>
                        <tr class="kindcar">
                            <td>Fitness Renewals <span style="color:#FE2E2E; ">*</span></td>
                            <td><input id="fitDate" name="fitDate" type="text" class="input-small" value="<?php echo date('d-m-Y'); ?>" placeholder="e.g. <?php echo date('d-m-Y'); ?>" required=""  /></td>
                        </tr>

                        <tr>
                            <td width="20%">Owner Name</td>
                            <td width="30%"><input type="text" name="owner_name" placeholder="Owner Name" autofocus ></td>
                            <td width="20%">RTO Location</td>
                            <td width="30%"><input type="text" name="rto_location" placeholder="RTO Location" autofocus ></td>
                        </tr>
                        <tr>
                            <td width="20%">Current Location</td>
                            <td width="30%"><input type="text" name="current_location" placeholder="Current Location" maxlength="50" autofocus ></td>
                            <td width="20%">Authorized Signatory</td>
                            <td width="30%"><input type="text" name="auth_signatory" placeholder="Authorized Signatory" maxlength="50" autofocus ></td>
                        </tr>
                        <tr>
                            <td width="20%">Hypothecation </td>
                            <td width="30%">
                                <input type="radio" name="hypothecationrd" class="hrd" value='1'/> Yes 
                                <input type="radio" name="hypothecationrd" class="hrd" value='0'/> No
                            </td>
                            <td width="20%"></td>
                            <td width="30%"><input type="text" name="hypothecation" id="hypothecation" placeholder="Hypothecation" maxlength="50" autofocus ></td>
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
                                            echo "<option value='$make->id'>$make->name</option>";
                                        }
                                    }
                                    ?>
                                </select>
                            </td>
                            <td>Model</td>
                            <td>
                                <select id="model" name="model">
                                    <option value="">Select Model</option>
                                </select>
                            </td>
                        </tr>

                        <tr>
                            <td>Month Of Manufacture</td>
                            <td>
                                <select name="monthofman" id="monthofman">
                                    <option value="0">Select Month</option>
                                    <option value="1">Jan</option>
                                    <option value="2">Feb</option>
                                    <option value="3">March</option>
                                    <option value="4">April</option>
                                    <option value="5">May</option>
                                    <option value="6">Jun</option>
                                    <option value="7">July</option>
                                    <option value="8">Aug</option>
                                    <option value="9">Sept</option>
                                    <option value="10">Oct</option>
                                    <option value="11">Nov</option>
                                    <option value="12">Dec</option>

                                </select>
                            </td>
                            <td>Year Of Manufacture <span style="color:#FE2E2E; ">*</span> </td>
                            <td><input type="text" name="yearofman" id="yearofman" placeholder="e.g. 2011" maxlength="4" ></td>

                        </tr>
                        <tr>
                            <td><?php echo($_SESSION['group']); ?> <?php if ($_SESSION['use_hierarchy'] == '1') { ?><span style="color:#FE2E2E; ">*</span> <?php } ?></td>
                            <td>
                                <select id="branchid" name="branchid" <?php if ($_SESSION['use_hierarchy'] == '1') { ?> onchange="showbranch()" <?php } ?>>
                                    <option value="">Select <?php echo($_SESSION['group']); ?></option>
                                    <?php
                                    $groups = getgroupss();
                                    if (isset($groups)) {
                                        foreach ($groups as $group) {
                                            echo "<option value='$group->groupid'>$group->groupname</option>";
                                        }
                                    }
                                    ?>
                                </select>

                            </td>
                            <td>Purchase Date <span style="color:#FE2E2E; ">*</span></td>
                            <td><input id="PDate" name="PDate" type="text" class="input-small" value="<?php echo date('d-m-Y'); ?>" placeholder="e.g. <?php echo date('d-m-Y'); ?>" required=""  /></td>
                        </tr>

<!--                        <tr>
    <td colspan="4">
        <div id="branch_div"></div>
    </td>
</tr>-->
                        <tr>
                            <td>Start Meter Reading</td>
                            <td><input type="text" name="start_meter" id="start_meter" placeholder="e.g. 12586" maxlength="10" ></td>
                            <td>Overspeed Limit</td>
                            <td><input type="text" name="overspeed" id="overspeed" placeholder="Overspeed Limit" maxlength="20" value="80"/></td>
                        </tr>
                        <tr>
                            <td>Fuel Type</td>
                            <td>
                                <select id="fueltype" name="fueltype">
                                    <option value="">Select Fuel Type</option>
                                    <option value='Petrol'>Petrol</option>
                                    <option value='Diesel'>Diesel</option>
                                    <option value='CNG'>CNG</option>
                                </select>
                            </td>
                            <td>Fuel Tank Capacity</td>
                            <td><input type="text" name="ftcap" id="ftcap" value="" /></td>
                        </tr>
                        <tr>
                            <td>Registration Date </td>
                            <td><input id="RDate" name="RDate" type="text" class="input-small" value="<?php echo date('d-m-Y'); ?>" placeholder="e.g. <?php echo date('d-m-Y'); ?>" required=""  /></td>
                        </tr>

                    </tbody>
                </table>

                <div class="input-prepend formSep ">Fire Extinguisher</div>
                <table class="table" style="width: 90%; border:none;">
                    <tbody>
                        <tr>
                            <td>Serial Number</td>
                            <td><input name="serial_number" type="text"  placeholder="e.g. 23"/></td>
                            <td>Expiry Date</td>
                            <td><input id="ExpDate" name="expiry_date" type="text" placeholder="e.g. <?php echo date('d-m-Y'); ?>" /></td>
                        </tr>
                    </tbody>
                </table>

                <div class="input-prepend formSep "> Description </div>

                <table class="table" style="width: 90%; border:none;">
                    <tbody>
                        <tr>
                            <td width="20%">Engine No.<span style="color:#FE2E2E; ">*</span></td>
                            <td width="30%"><input type="text" name="engineno" id="engineno" placeholder="engine no." maxlength="20" OnKeyPress="return nospecialchars(event)"></td>
                            <td width="20%">Chasis No.<span style="color:#FE2E2E; ">*</span></td>
                            <td width="30%"><input type="text" name="chasisno" id="chasisno" placeholder="chasis no." maxlength="20" OnKeyPress="return nospecialchars(event)"></td>
                        </tr>
                        <tr>
                            <td>Vehicle Purpose</td>
                            <td>
                                <select id="veh_purpose" name="veh_purpose">
                                    <option value="">Select Purpose</option>
                                    <option value='1'>Employee CTC</option>
                                    <option value='2'>Branch Vehicle</option>
                                    <option value='3'>Zone Vehicle</option>
                                    <option value='4'>Regional Vehicle</option>
                                    <option value='5'>Head Office Vehicle</option>
                                    <option value='6'>School Vehicle</option>
                                    <option value='7'>Staff Vehicle</option>
                                </select>
                            </td>
                            <td>Vehicle Type</td>
                            <td>
                                <select id="veh_type" name="veh_type">
                                    <option value="">Select Vehicle Type</option>
                                    <option value='1'>New</option>
                                    <option value='2'>Repossesed</option>
                                    <option value='3'>Employee</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td>Dealer Name</td>
                            <td>
                                <select id="dealerid" name="dealerid" onchange="showdealer()">
                                    <option value="">Select Dealer</option>
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
                            <td>Code</td>
                            <td><input type="text" name="code_dealer" readonly id="code_dealer"></td>
                        </tr>
                        <tr>
                            <td>Invoice No.<span style="color:#FE2E2E; ">*</span></td>
                            <td><input type="text" name="invoiceno" id="invoiceno" placeholder="12586" maxlength="50" OnKeyPress="return nospecialchars(event)"></td>
                            <td>Invoice Date <span style="color:#FE2E2E; ">*</span></td>
                            <td><input id="invoice_date" name="invoice_date" type="text" class="input-small" value="<?php echo date('d-m-Y'); ?>" placeholder="e.g. <?php echo date('d-                                 m-Y'); ?>" required=""></td>
                        </tr>
                        <tr>
                            <td>Invoice Amount<span style="color: #FE2E2E;">*</span></td>
                            <td><input type="text" name="invoiceamt" id="invoiceamt" placeholder="12586" maxlength="10" OnKeyPress="return nospecialchars(event)"></td>
                            <td>Seating Capacity</td>
                            <td><input type="text" name="seatcapacity" id="seatcapacity" value="" placeholder="e.g. 50" /></td>
                        </tr>
                    </tbody>
                </table>
                <!---tyres srno---->
                <?php
                if ($_SESSION['customerno'] == 118) {
                    ?>
                    <div class="input-prepend formSep "> Tyres Serial No </div>
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
                                Installation  Date
                            </th>
                            </thead>
                            <tbody>

                                <tr>  
                                    <td>Right Front</td>
                                    <td><input name="right_front" type="text"  id="right_front" value=""/></td>
                                    <td><input name="rf_insdate" type="text"  id="rf_insdate" value=""/></td>
                                </tr>
                                <tr>
                                    <td>Left Front</td>
                                    <td><input name="left_front" type="text" id="left_front" value=""/></td>
                                    <td><input name="lf_insdate" type="text"  id="lf_insdate" value=""/></td>
                                </tr>
                                <tr>
                                    <td>Right Back Out</td>
                                    <td><input name="right_back_out" type="text" id="right_back_out" value=""/></td>
                                    <td><input name="rbout_insdate" type="text"  id="rbout_insdate" value=""/></td>
                                </tr>
                                <tr>
                                    <td>Left Back Out</td>
                                    <td><input name="left_back_out" type="text" id="left_back_out" value=""/></td>
                                    <td><input name="lbout_insdate" type="text"  id="lbout_insdate" value=""/></td>
                                </tr>
                                <tr>
                                    <td>Right Back In</td>
                                    <td><input name="right_back_in" type="text" id="right_back_in" value=""/></td>
                                    <td><input name="rbin_insdate" type="text"  id="rbin_insdate" value=""/></td>
                                </tr>
                                <tr>
                                    <td>Left Back In</td>
                                    <td><input name="left_back_in" type="text" id="left_back_in" value=""/></td>
                                    <td><input name="lbin_insdate" type="text"  id="lbin_insdate" value=""/></td>
                                </tr>
                                <tr>
                                    <td>Stepney</td>
                                    <td><input name="stepney" type="text" id="stepney" value=""/></td>
                                    <td><input name="st_insdate" type="text"  id="st_insdate" value=""/></td>
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
                                    <td>Serial No.</td>&nbsp;&nbsp;
                                    <td><input type="text" name="battsrno" id="battsrno" value=""></td>
                                    <td>Installation Date</td>&nbsp;&nbsp;
                                    <td><input name="battsrno_insdate" type="text"  id="battsrno_insdate" value=""/></td>
                                </tr>
                            </tbody>
                        </table>


                    </div>
                    <?php
                }
                ?>
                <div class="input-prepend formSep "> Insurance </div>
                <div class="control-group">
                    <div class="input-prepend formSep"> <span class="add-on">Do you have insurance?</span>
                        <input type="radio" name="insurance" id="insurance1" value="yes">
                        Yes
                        <input type="radio" name="insurance" id="insurance2" checked value="no">
                        No </div>
                </div>
                <div id="radio_show" style="display: none;">
                    <table class="table" style="border:none; width: 90%;">
                        <tr>
                            <td>Value of Insurance / IDV <span style="color:#FE2E2E; ">*</span></td>
                            <td><input type="text" name="insurance_value" id="insurance_value" maxlength="10" onkeypress="return isNumberKey(event);"/></td>
                            <td>Premium <span style="color:#FE2E2E; ">*</span></td>
                            <td><input type="text" name="premium_value" id="premium_value" maxlength="10" onkeypress="return isNumberKey(event);"/></td>
                        </tr>
                        <tr>
                            <td>Start Date </td>
                            <td><input id="StartDate" name="StartDate" type="text" class="input-small" placeholder="e.g. <?php echo date('d-m-Y'); ?>" value="<?php echo date('d-m-Y'); ?>" readonly></td>
                            <td>End Date </td>
                            <td><input id="EndDate" name="EndDate" type="text" class="input-small" placeholder="e.g. <?php echo date('d-m-Y'); ?>" value="<?php echo date('d-m-Y'); ?>" required onblur="insurance_date();"></td>
                        </tr>
                        <tr>
                            <td>Insurance Company <span style="color:#FE2E2E; ">*</span></td>
                            <td>
                                <select name="insurance_company" id="insurance_company">
                                    <option value="">Select Company</option>
                                    <?php
                                    $ins_comp = getinsurance_company();
                                    if (isset($ins_comp)) {
                                        foreach ($ins_comp as $ins) {
                                            echo "<option value='$ins->id'>$ins->name</option>";
                                        }
                                    }
                                    ?>
                                </select>
                            </td>
                            <td>Nearest Place Of Claim</td>
                            <td><input type="text" name="near_place" placeholder="e.g. mumbai, delhi" id="near_place" maxlength="20" OnKeyPress="return nospecialchars(event)"></td>
                        </tr>
                        <tr>
                           <!-- <td>IDV</td>
                            <td><input id="idv" name="ide" type="text" value="" required></td>-->
                            <td>NCB </td>
                            <td><span><input id="ncb" name="enb" type="text" value="" required></span><span>%</span></td>
                            <td>Notes</td>
                            <td colspan="3"><input type="text" name="ins_notes" id="ins_notes" maxlength="20" OnKeyPress="return nospecialchars(event)"></td>
                        </tr>
                       <!-- <tr>
                            <td>Notes</td>
                            <td colspan="3"><input type="text" name="ins_notes" id="ins_notes" maxlength="20" OnKeyPress="return nospecialchars(event)"></td>
                        </tr>-->
                        <tr>
                            <td>Dealer Name</td>
                            <td>
                                <select id="insdealerid" name="insdealerid">
                                    <option value="0">Select Dealer</option>
                                    <?php
                                    $dealers = getAllInsdealer();
                                    if (isset($dealers)) {
                                        foreach ($dealers as $dealer) {
                                            echo "<option value='$dealer->ins_dealerid'>$dealer->ins_dealername</option>";
                                        }
                                    }
                                    ?>
                                </select>
                            </td>
                            <td>Policy Number</td>
                            <td><input type="text" name="polno" id="polno" maxlength="25"></td>
                        </tr>
                    </table>
                </div>


                <div class="input-prepend formSep "> Loan </div>
                <div class="control-group">
                    <div class="input-prepend formSep"> <span class="add-on">Have you taken Loan?</span>
                        <input type="radio" name="loan" id="loan1" value="yes">
                        Yes
                        <input type="radio" name="loan" id="loan2" checked value="no">
                        No </div>
                </div>
                <div id="loan_show" style="display: none;">
                    <table class="table" style="border:none; width: 90%;">
                        <tr>
                            <td>Margin Amount <span style="color:#FE2E2E; ">*</span></td>
                            <td><input type="text" name="margin_amt" id="margin_amt" maxlength="10" ></td>
                            <td>Loan Amount <span style="color:#FE2E2E; ">*</span></td>
                            <td><input type="text" name="loan_amt" id="loan_amt" maxlength="10" onkeypress="return isNumberKey(event);"/></td>
                        </tr>

                        <tr>
                            <td>EMI Amount <span style="color:#FE2E2E; ">*</span></td>
                            <td><input type="text" name="emi_amt" id="emi_amt" maxlength="10" onkeypress="return isNumberKey(event);"/></td>
                            <td>Loan Tenure (In Months)<span style="color:#FE2E2E; ">*</span></td>
                            <td><input type="text" name="loan_tenure" id="loan_tenure" maxlength="10" onkeypress="return isNumberKey(event);"/></td>
                        </tr>

                        <tr>
                            <td>Financier <span style="color:#FE2E2E; ">*</span></td>
                            <td><input type="text" name="financier" id="financier" value=""></td>
                            <td></td>
                            <td></td>
                        </tr>

                        <tr>
                            <td>Start Date </td>
                            <td><input id="StartDateloan" name="StartDateloan" type="text" class="input-small" placeholder="e.g. <?php echo date('d-m-Y'); ?>" value="<?php echo date('d-m-Y'); ?>" required></td>
                            <td>End Date </td>
                            <td><input id="EndDateloan" name="EndDateloan" type="text" class="input-small" placeholder="e.g. <?php echo date('d-m-Y'); ?>" value="<?php echo date('d-m-Y'); ?>" required></td>
                        </tr>
                        <tr>
                            <td>Loan Account Number</td>
                            <td>
                                <input type="text" name="loan_accno" id="loan_accno" maxlength="25"/>
                            </td>
                            <td>Loan EMI Date</td>
                            <td><input type="text" name="emidate" id="emidate" /></td>
                        </tr>

                    </table>
                </div>



                <div class="input-prepend formSep "> Capitalization </div>
                <table class="table" style="border:none; width: 90%;">
                    <tbody>
                        <tr>
                            <td>Date <span style="color:#FE2E2E; ">*</span></td>
                            <td><input id="cap_EDate" name="cap_EDate" type="text" class="input-small" value="<?php echo date('d-m-Y'); ?>" placeholder="e.g. <?php echo date('d-m-Y'); ?>" required="" /></td>
                            <td>Cost <span style="color:#FE2E2E; ">*</span></td>
                            <td> <input type="text" name="cap_cost" id="cap_cost" maxlength="10" value="0" /></td>
                            <td>Address</td>
                            <td><textarea name="cap_address" id="cap_address" maxlength="200" OnKeyPress="return nospecialchars(event)"></textarea></td>
                        </tr>
                    </tbody>
                </table>

                <?php if ($_SESSION['use_tracking'] == '1') { ?>
                    <div class="input-prepend formSep "> Geo Tag </div>
                    <table class="table" style="border:none; width: 90%;">
                        <tbody>
                            <tr>
                                <td colspan="2">Checkpoint</td>
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
                                <td colspan="3" style="text-align: left;"><input type="button" value="Add All Checkpoints" class="btn  btn-primary" onclick="addallchk()"></td>
                            </tr>
                            <tr>
                                <td colspan="6" style="padding: 5px;"><div id="checkpoint_list"> </div></td>
                            </tr>

                            <tr>
                                <td colspan="2">Fence</td>
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
                                <td colspan="3" style="text-align: left;"><input type="button" value="Add All Fences" class="btn  btn-primary" onclick="addallfence()"></td>
                            </tr>
                            <tr>
                                <td colspan="6" style="padding: 5px;"><div id="fence_list"> </div></td>
                            </tr>
                        </tbody>
                    </table>

                <?php } ?>


            </div>
            <br/><br/>
            <div class="control-group">

                <?php
                if ($_SESSION['customerno'] == 118) {
                    echo '<input type="button" onclick="addvehicles_trigon();" id="save_vehicle" value="Save" class="btn btn-success">';
                    echo '<input type="button" id="sendapproval" onclick="sendapproval_new_trigon();" value="Send For Approval" disabled=""  class="btn btn-success"/>';
                } else {
                    echo '<input type="button" onclick="addvehicles();" id="save_vehicle" value="Save" class="btn btn-success">';
                    echo '<input type="button" id="sendapproval" onclick="sendapproval_new();" value="Send For Approval" disabled=""  class="btn btn-success"/>';
                }
                ?>

            </div>

        </fieldset>

    </form>


</div>

<script>
    $(document).ready(function () {
        $("#hypothecation").hide();
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