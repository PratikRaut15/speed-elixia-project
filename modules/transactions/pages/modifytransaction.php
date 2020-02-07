<?php
$dealer = pulldealer_byvehicle($vehicleid, $type);
$battno = getbatteryno_byvehicle($vehicleid);
$cities = get_all_cities($_SESSION['heirarchy_id']);
$vehno = getVehicleName($vehicleid);
$cityopt = "";
$ans = '';
$today = date('d-m-Y');
$getpart = getpart();
$gettask = gettask();

if (isset($cities)) {
    foreach ($cities as $thiscity) {
        $cityopt .= "<option value = '$thiscity->cityid'>$thiscity->name</option>";
    }
}
?>
<style>
    .selectwidth{
        width:200px;
    }
</style>
<?php
if ($type == 3) {
    ?>
    <div class="table" id="accidentview_approval" style="width: 51%;">
        <form method="POST" id="getaccident_approval">        
            <fieldset>
                <div class="modal-header">
                    <input type="hidden" id="vehicle_id1" value='<?php echo $vehicleid; ?>'>
                    <input type="hidden" id="category_id1" value='<?php echo $type; ?>'>
                    <input type="hidden" id="customerno" value='<?php echo $_SESSION['customerno']; ?>'>
                    <h4 style="color:#0679c0" id="accident_head_fortransac">Accident Event Entry / Insurance Claim</h4>
                </div>
                <div>
                    <ul class="nav nav-tabs" id="myTab">
                        <li class="active"><a href="#acc_form" data-toggle="tab">Event Entry</a></li>
                        <li><a href="#file_upload" data-toggle="tab">File Upload</a></li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane active" id="acc_form">
                            <div align="center">    
                                <span id="al_error" style="display: none;">Please Enter Accident Location</span>
                                <span id="ad_error" style="display: none;">Please Enter Accident Description</span>        
                                <span id="dn_error" style="display: none;">Please Enter Driver Name</span>                
                                <span id="email_error" style="display: none;">Please Enter Correct Email Address</span>                        
                                <span id="date_conflict_error" style="display: none;">License Start Date cannot be less than License End Date</span>                                
                            </div>
                            <div style="overflow-x: auto; height: 500px;">
                                <?php
                                $currentdate = getcurrentdate();
                                $today = date('d-m-Y', $currentdate);
                                ?>                        
                                <table class="table table-bordered table-striped" >
                                    <th colspan="2">Required Details</th>
                                    <tr>
                                        <td width="50%">Vehicle No</td>
                                        <td><input type="text" name="vno" id="vno" value="<?php echo $vehno; ?>" readonly></td>
                                    </tr>
                                    <tr>
                                        <td width="50%">Accident Date</td>
                                        <td><input id="acc_Date" name="acc_Date" type="text" class="input-small" value="<?php echo $today; ?>" /></td>
                                    </tr>                  
                                    <tr>
                                        <td width="50%">Accident Time</td>
                                        <td><input id="STime" name="STime" type="text" class="input-mini" data-date="00:00" /></td>
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
                                        <td width="50%">Amount Spent by <?php echo $_SESSION['customercompany'] ?> (INR)</td>
                                        <td><input type="text" name="mahindra_amount" class="input-small" id="mahindra_amount" readonly value="0" /></td>
                                    </tr>                                                                                                                                                                                                                  

                                </table>

                            </div>

                            <div class="control-group">
                                <div class="input-prepend " align="center">
                                    <input type="button" onclick="push_transaction_accident();" value="Send for Approval" class="btn btn-success">
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane" id="file_upload">

                            <table class="table table-bordered table-striped">
                                <th colspan="2">Required Details</th>
                                <tr>
                                    <td width="50%">File 1</td>
                                    <td><input type="file" name="file1" id="file1" ></td>
                                </tr>                  

                                <tr>
                                    <td width="50%">File 2</td>
                                    <td><input type="file" name="file2" id="file2"></td>
                                </tr>                  

                                <tr>
                                    <td width="50%">File 3</td>
                                    <td><input type="file" name="file3" id="file3" ></td>
                                </tr>                  

                                <tr>
                                    <td width="50%">File 4</td>
                                    <td><input type="file" name="file4" id="file4" ></td>
                                </tr>                  

                                <tr>
                                    <td width="50%">File 5</td>
                                    <td><input type="file" name="file5" id="file5" ></td>
                                </tr>                  

                            </table>
                        </div>
                    </div>
            </fieldset>
        </form>
    </div>
    <?php
} else {
    ?>
    <div class="table" id="addview_approval" style="width: 51%;">
        <form method="POST" id="getbattery_approval">        
            <fieldset>

                <div class="modal-header">
                    <input type="hidden" id="vehicle_id" value='<?php echo $vehicleid; ?>'>
                    <input type="hidden" id="category_id" value='<?php echo $type; ?>'>
                    <h4 style="color:#0679c0" id="head_fortransac">
                        <?php
                        switch ($type) {
                            case '0':
                                echo "Quotation for Battery Replacement";
                                break;
                            case '1':
                                echo "Quotation for Tyre Replacement";
                                break;
                            case '2':
                                echo "Quotation for Repair / Service";
                                break;
                            case '5':
                                echo "Quotation for Accessories";
                                break;
                            case '6':
                                echo "Add Fuel";
                                break;
                        }
                        ?>
                    </h4>
                </div>
                <div>
                    <span id="dl_error" style="display: none;color: #FF0000;">Please Select Dealer</span>
                    <span id="mr_error" style="display: none;color: #FF0000;">Please Enter Meter Reading</span>            
                    <span id="notes_error" style="display: none;color: #FF0000;">Please Enter Notes</span>                        
                    <span id="quote_error" style="display: none;color: #FF0000;">Please Enter Quotation Amount</span>                                    
                    <span id="tyre_type_error" style="display: none;color: #FF0000;">Please Select Tyre Types</span>
                    <span id="parts_type_error" style="display: none;color: #FF0000;">Please Select Category</span>                                                            
                    <span id="max_perm_amount_error" style="display: none;color: #FF0000;">Cost cannot exceed maximum permissible amount</span>                                                                        
                    <span id="parts_error" style="display: none;color: #FF0000;">Please Enter complete details for parts</span>                                                                        
                    <span id="tasks_error" style="display: none;color: #FF0000;">Please Enter complete details for tasks</span>                                                                        
                    <span id="quote_exceed_error" style="display: none;color: #FF0000;">Quotation cannot exceed INR 22500/-</span>                                                                                    
                    <span id="quote_exceed_totalinv" style="display: none;color: #FF0000;">Parts & Tasks Total Amount Not Greater Than Quotation Amount</span>                                                                                   
                    <p id="transaction_msg" name="transaction_msg" style="display: none;"></p>

                    <div>            
                        <table class="table table-bordered table-striped">
                            <th colspan="2">Required Details</th>
                            <tr>
                                <td width="50%">Vehicle No</td>
                                <td><input type="text" name="vno" id="vno" value="<?php echo $vehno; ?>" readonly></td>
                            </tr>
                            <tr>
                                <td width="50%">Meter Reading</td>
                                <td><input type="text" name="meter_reading" id="meter_reading" placeholder="e.g. 12586" maxlength="10" ></td>
                            </tr>                  
                            <tr>
                                <td>Dealer</td>
                                <td><select id="dealerid" name="dealerid">
                                        <?php echo $dealer; ?>
                                    </select>
                                    <?php
                                    if (IsAuthTrigonDealer()) {
                                        ?>
                                        <a href='javascript:void(0);' onclick='clickdriver();'>Add Dealer</a>
                                        <?php
                                    }
                                    ?>
                                </td>
                            </tr>                        
                            <tr>
                                <td>Notes</td>
                                <td><input type="text" name="note_batt" id="note_batt" placeholder="e.g. 12586" maxlength="200" onkeypress="return nospecialcharsnotes(event)"></td>
                            </tr>                                                
                            <tr>
                                <td>Quotation File</td>
                                <td><input type="file" title="Browse File" id="file_for_quote" name="file_for_quote" class="file-inputs"></td>
                            </tr>                    
                            <tr>
                                <td>Quotation amount</td>
                                <td><input type="text" name="amount_quote" id="amount_quote" placeholder="e.g. 125" maxlength="10" ></td>
                            </tr>

                            <input type="hidden" name="customerno" id="customerno" value="<?php echo $_SESSION['customerno']; ?>" >

                        </table>
                        <?php
                        if ($_SESSION["customerno"] == 118 && $type == '0') {
                            $battdata = getbatteryno_byvehicle($vehicleid);
                            $battsrno = '';
                            if (!empty($battdata)) {
                                $battsrno = $battdata[0]->srno;
                            }
                            ?>
                            <table class="table table-bordered table-striped">
                                <tr>
                                    <td>Old Battery Serial No.</td>
                                    <td><input type="text" name="old_battsrno" id="old_battsrno" value="<?php echo $battsrno; ?>" readonly></td>
                                    <td>
                                        <input type="checkbox" name="newbatt_chkbox" id="newbatt_chkbox" onclick="activetextbox();"/>
                                    </td>
                                    <td>
                                        <input type="text" name="new_battsrno" id="new_battsrno" readonly>
                                    </td>
                                </tr> 
                            </table>

                            <table class="table table-bordered table-striped">
                                <th colspan="2">Tax</th>
                                <tr>
                                    <td width="15%"><b>Tax Amount</b></td>
                                    <td width="35%"><b><input type="text" name="p_tax" id="p_tax" placeholder="e.g. 5" maxlength="10" size="8" value="0.00" onkeyup="showInvoiceDetails()"></b></td>
                                </tr> 
                            </table>
                            <?php
                        }
                        ?>
                        <?php
                        if ($type == '1' && $_SESSION['customerno'] != 118) {
                            ?>
                            <div id="tyre_fields" >
                                <div class="control-group tyre_field"  >
                                    <div class="input-prepend">
                                        <span class="add-on" style="color:#000000">Tyre type</span>
                                        <select id="tyre_type" name="tyre_type">
                                            <option value="">Select type</option>
                                            <?php
                                            foreach ($tyre_arr as $tid => $tval) {
                                                echo "<option value='$tid'>$tval</option>";
                                            }
                                            ?>
                                        </select><input type="button" id="add_ttype" name="add_ttype" value="+" onclick="add_items_container('#tyrelist_container', 'tyre_type')">
                                        <div id="tyrelist_container"></div>
                                    </div>
                                </div>
                            </div>
                            <div id="expandable">
                            </div>
                            <?php
                        }
                        ?>
                        <?php
                        if ($type == '1' && $_SESSION['customerno'] == 118) {
                            $tyre_repair = getTyreRepairType();
                            $srno = '';
                            $ins = '';
                            $tyre = getTyreTypedata($vehicleid);
                            ?>
                            <div>
                                <table class="table table-bordered table-striped">
                                    <tr>
                                    <span id="trepair_error" style="display: none;color: #FF0000">Please Select Tyre Repair Type</span>
                                    </tr>
                                    <tbody>
                                        <tr>
                                            <td style="width: 50%">Tyre Repair Type</td>
                                            <td><select id="tyrerepair" name="tyrerepair" onchange="showtyresrno();">
                                                    <option value="0">Select Tyre Repair Type</option>
                                                    <?php
                                                    foreach ($tyre_repair as $datas) {
                                                        ?>
                                                        <option value="<?php echo $datas->tyrerepairid; ?>"><?php echo $datas->repairtype; ?></option>
                                                        <?php
                                                    }
                                                    ?>
                                                </select>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div id="tyre_type">
                                <table class="table table-bordered table-striped">
                                    <tr>
                                    <span id="rf_error" style="display: none;color: #FF0000">Please Enter New Right Front Sr No.</span>
                                    <span id="rbout_error" style="display: none;color: #FF0000;">Please Enter New Right Back Out Sr No.</span>
                                    <span id="rbin_error" style="display: none;color: #FF0000;">Please Enter New Right Back In Sr No.</span>
                                    <span id="lf_error" style="display: none;color: #FF0000;">Please Enter New Left Front Sr No.</span>
                                    <span id="lbout_error" style="display: none;color: #FF0000;">Please Enter New Left Back Out Sr No.</span>
                                    <span id="lbin_error" style="display: none;color: #FF0000;">Please Enter New Left Back In Sr No.</span>
                                    <span id="st_error" style="display: none;color: #FF0000;">Please Enter New Stepney Sr No.</span>
                                    <span id="chk_error" style="display: none;color: #FF0000;">Please Tick Checkbox</span>
                                    <tr>
                                        <th colspan="100%">Tyre Serial No. Details</th>

                                    <tbody>
                                        <tr>    
                                            <td>Right Front</td>
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
                                            <td><input name="oright_front" type="text"  id="oright_front" value="<?php echo $srno; ?>" readonly/></td>
                                            <td><input name="rf" type="checkbox" class="chk" id="rf" onclick="activetextbox();" ></td>

                                            <td><input name="nright_front" type="text" class="txtsrno" id="nright_front" readonly/></td>

                                        </tr>
                                        <tr>    
                                            <td>Left Front</td>
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
                                            <td><input name="oleft_front" type="text" id="oleft_front" value="<?php echo $srno; ?>" readonly/></td>
                                            <td><input name="lf" type="checkbox" class="chk" id="lf" onclick="activetextbox();"></td>

                                            <td><input name="nleft_front" type="text" class="txtsrno" id="nleft_front" readonly/></td>

                                        </tr>

                                        <tr>    
                                            <td>Right Back Out</td>
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
                                            <td><input name="oright_back_out" type="text" id="oright_back_out" value="<?php echo $srno; ?>" readonly/></td>
                                            <td><input name="rb_out" type="checkbox" class="chk" id="rb_out" onclick="activetextbox();"></td>

                                            <td><input name="nright_back_out" type="text" class="txtsrno" id="nright_back_out" readonly/></td>

                                        </tr>
                                        <tr>   
                                            <td>Left Back Out</td>
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
                                            <td><input name="oleft_back_out" type="text" id="oleft_back_out" value="<?php echo $srno; ?>" readonly/></td>
                                            <td><input name="lb_out" type="checkbox" class="chk" id="lb_out" onclick="activetextbox();"></td>

                                            <td><input name="nleft_back_out" type="text" class="txtsrno" id="nleft_back_out" readonly/></td>

                                        </tr>
                                        <tr>    
                                            <td>Right Back In</td>
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
                                            <td><input name="oright_back_in" type="text"  id="oright_back_in" value="<?php echo $srno; ?>" readonly/></td>
                                            <td><input name="rb_in" type="checkbox" class="chk" id="rb_in" onclick="activetextbox();"></td>

                                            <td><input name="nright_back_in" type="text" class="txtsrno" id="nright_back_in" readonly/></td>

                                        </tr>
                                        <tr>    
                                            <td>Left Back In</td>
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
                                            <td><input name="oleft_back_in" type="text" id="oleft_back_in" value="<?php echo $srno; ?>" readonly/></td>
                                            <td><input name="lb_in" type="checkbox" class="chk" id="lb_in" onclick="activetextbox();"></td>

                                            <td><input name="nleft_back_in" type="text" class="txtsrno" id="nleft_back_in" readonly/></td>

                                        </tr>
                                        <tr>
                                            <td>Stepney</td>
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
                                            <td><input name="ostepney" type="text" id="ostepney" value="<?php echo $srno; ?>" readonly/></td>
                                            <td><input name="st" type="checkbox" class="chk" id="st" onclick="activetextbox();"></td>
                                            <td><input name="nstepney" type="text" class="txtsrno" id="nstepney" readonly></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div id="tyre_type">
                                <table class="table table-bordered table-striped">
                                    <th colspan="2">Tax</th>
                                    <tr>
                                        <td width="15%"><b>Tax Amount</b></td>
                                        <td width="35%"><b><input type="text" name="p_tax" id="p_tax" placeholder="e.g. 5" maxlength="10" size="8" value="0.00" onkeyup="showInvoiceDetails()"></b></td>
                                    </tr> 
                                </table>

                            </div>
                            <?php
                        }

                        if ($type == '2') {
                            ?>
                            <div id="parts_service_category" >

                                <div class="control-group">
                                    <div class="input-prepend ">
                                        <!-- onchange="category_selector()" -->
                                        <span class="add-on" style="color:#000000">Category</span>
                                        <select id="category_type" name="category_type" >
                                            <option value="-1">Select Task</option>
                                            <option value="2">Repair</option>
                                            <option value="3">Service</option>
                                            <!--                                            <option value="4">Denting/Painting</option>
                                                                                        <option value="5">Electrical Work</option>
                                                                                        <option value="6">Seat Work</option>
                                                                                        <option value="7">Interior Work</option>-->
                                        </select>

                                    </div>
                                </div>
                                <div class="control-group">
                                    <div class="input-prepend">
                                        <input type="checkbox" name="partslist" id="partslist" value="1" onclick="clickparts();"/>Parts
                                        <a href='javascript:void(0)' onclick="jQuery('#PartPop').modal('show');
                                                        jQuery('#partStatus').html('');
                                                        jQuery('#partnameP').val('');" >Add Part</a><br/>
                                        <input type="checkbox" name="taskslist" id="taskslist" value="1" onclick="clicktasks();" />Tasks
                                        <a href='javascript:void(0)' onclick="jQuery('#TaskPop').modal('show');
                                                        jQuery('#taskStatus').html('');
                                                        jQuery('#tasknameP').val('');"  style="margin-top:5px;" >Add Task</a>

                                    </div>
                                </div>
                                <div class="control-group">
                                    <div class="input-prepend">
                                        <?php
                                        if ($type == '2') {
                                            ?>
                                            <div id="parts_category" style="display: none;">
                                                <table style="display: table; width: 100%" id="myTable">
                                                    <tr>
                                                        <th width="15%">Sr. No</th>
                                                        <th width="20%">Parts</th>
                                                        <th width="15%">Quantity</th>
                                                        <th width="15%">Cost Per Unit</th>
                                                        <th width="15%">Discount</th>
                                                        <th width="15%">Final Amount</th>
                                                        <th width="10%">
                                                            <span id='btnaddrow' style="float: right;" onclick="addrow();">
                                                                <a><img  src="../../images/show.png" alt="Add Row"/> </a>
                                                            </span>
                                                        </th>
                                                    </tr>
                                                </table>    
                                            </div>

                                            <div id="tasks_category" style="display: none;" >
                                                <table style="display: table; width: 100%" id="myTable1">
                                                    <tr>
                                                        <th width="15%">Sr. No</th>
                                                        <th width="20%">Task</th>
                                                        <th width="15%">Quantity</th>
                                                        <th width="15%">Cost Per Unit</th>
                                                        <th width="15%">Discount</th>
                                                        <th width="15%">Final Amount</th>
                                                        <th width="10%">
                                                            <span id='btnaddrow1' style="float: right;" onclick="addrow1();">
                                                                <a><img  src="../../images/show.png" alt="Add Row"/> </a>
                                                            </span>
                                                        </th>
                                                    </tr>
                                                </table> 
                                            </div>
                                            <div id="parts_tax" style="display: none;">
                                                <table class="table table-bordered table-striped">
                                                    <th colspan="2">Tax</th>
                                                    <tr>
                                                        <td width="15%"><b>Tax Amount</b></td>
                                                        <td width="35%"><b><input type="text" name="p_tax" id="p_tax" placeholder="e.g. 5" maxlength="10" size="8" value="0.00" onkeyup="showInvoiceDetails()"></b></td>

                                                    </tr> 
                                                </table>
                                            </div>
                                            <?php
                                        }
                                        ?>
                                    </div>
                                </div>
                                <div id="category_handler"></div>
                            </div>
                            <div id="parts_task" style="display: none;" >
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
                                <div class="control-group" >
                                    <div class="input-prepend ">
                                        <span class="add-on" style="color:#000000">Tasks</span><select id="task_select" name="task_select">
                                            <option value="-1">Select Task</option>
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

                        </div>


                        <?php
                    }
                    if ($type == '5') {
                        ?>
                        <div id="accessory_category" >

                            <table class="table table-bordered table-striped">
                                <th colspan="4">Accessory Details</th>
                                <tr>
                                    <td width="15%"><b>Sr. No</b></td>
                                    <td width="35%"><b>Accessory</b></td>
                                    <td width="25%"><b>Total Amount</b></td>
                                    <td width="25%"><b>Quantity</b></td>
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
                                                        echo "<option value='$accessory->id'>" . $accessory->name . "</option>";
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
                        <?php
                    }
                    ?>
                </div>
                <?php
                if ($_SESSION['customerno'] == 64) {
                    $role = $_SESSION['role']; 
                    $roleid = $_SESSION['roleid'];
                    $userid = $_SESSION['userid'];
                    $accessroles = array(1, 33, 35);
                    if (in_array($roleid, $accessroles)){
                        $displayzonemasters="";
                        $displayregionalmasters="";
                        if($roleid==1 || $roleid==33){ // headoffice and master 
                        $displayzonemasters = getBehalfMembersZoneMasters($roleid,$userid,$role);
                         //   echo"<pre>"; print_r($displayzonemasters);
                        }elseif ($roleid==35){  // zone manager
                            $displayregionalmasters = getBehalfMembersRegionalManager($roleid,$userid,$role);
                            //echo"<pre>"; print_r($displayregionalmasters); die();
                            
                        }
                        ?>
                        <table class="table table-bordered table-striped">                    
                            <tr>
                                <td style="width:50%">On Behalf of Request </td>
                                <td>
                                    <?php 
                                    if(isset($displayzonemasters) && !empty($displayzonemasters)){
                                     echo"<input type='radio' name='behalfradiobtn' value='1'>Zone Users<br>";
                                    ?>
                                    <select name="zonemaster" id="zonemaster">
                                        <option value='0'>Select Zone Manager</option>
                                        <?php 
                                        foreach($displayzonemasters as $row){ 
                                        ?>    
                                        <option value="<?php echo $row['userid'];?>"><?php echo $row['username'];?></option>   
                                        <?php
                                        }
                                        ?>
                                    </select>
                                    <div id='regionaldiv'> </div>
                                    <div id='branchdiv'> </div>
                                    <?php } ?>
                                    <?php 
                                    if(isset($displayregionalmasters)&& !empty($displayregionalmasters)){
                                        echo "<input type='radio' name='behalfradiobtn' value='2'>Regional Users<br><select name='regionalmaster' id='regionalmaster' onchange='getbranch()'>";
                                        echo "<option value='0'>Select Regional Manager</option>";
                                        foreach($displayregionalmasters as $row1){
                                            $userid = $row1['userid'];
                                            $username = $row1['username'];
                                            echo "<option value='".$userid."'>".$username."</option>";
                                        }  
                                        echo "</select>";
                                        ?>    
                                        <div id='branchdiv'> </div>   
                                        <?php 
                                    }
                                    ?>
                                </td>
                            </tr>                       
                        </table>
                    <?php }
                } ?>
                <table class="table table-bordered table-striped">                    
                    <tr>
                        <td style="width:50%">Send for Final Payment Approval</td>
                        <td><input type="checkbox" id="sent_pay" name="sent_pay" onclick="showInvoiceDetails();"/></td>
                    </tr>                       
                </table>
                <div id="inv_data" style="display: none;">
                    <table class="table table-bordered table-striped">
                        <th colspan="2">Invoice Details</th>
                        <tr>
                            <td>Vehicle In Date</td>
                            <td><input id="vehicle_in_date" name="vehicle_in_date" type="text" value="<?php echo $today; ?>"/></td>
                        </tr>
                        <tr>
                            <td>Vehicle Out Date</td>
                            <td><input id="vehicle_out_date" name="vehicle_out_date" type="text" value="<?php echo $today; ?>"/></td>
                        </tr>
                        <tr>
                            <td width="50%">Invoice Generation Date</td>
                            <td><input id="invoice_date" name="invoice_date" type="text" value="<?php echo $today; ?>"/></td>
                        </tr>
                        <tr>
                            <td>Invoice Number</td>
                            <td><input type="text" name="invoice_no" id="invoice_no" placeholder="e.g. 12586" maxlength="50" onkeypress="return nospecialchars(event)"></td>
                        </tr>
                        <!--<tr>
                                <td>Tax Amount</td>
                                <td> <input type="text" name="tax" id="tax" placeholder="e.g. 125" value="" maxlength="10" size="8"/></td>
                        </tr>-->
                        <tr>
                            <td>Invoice Amount (INR)</td>
                            <td><input type="text" name="amount_invoice" id="amount_invoice" placeholder="e.g. 125" maxlength="10" size="8" onkeyup="setQuotation();"/></td>
                        </tr>                            
                        <tr id="invoice_file">
                            <td>Invoice File</td>
                            <td>
                                <input type="file" title="Browse File" id="file_for_invoice" name="file_for_invoice" class="file-inputs">                                                      
                            </td>                                                          
                        </tr>                                                           
                    </table>
                </div>
                <div class="control-group">
                    <div class="input-prepend ">
                        <input type="hidden" name="customerno" id="customerno" value="<?php echo $_SESSION['customerno'];?>">
                        <input type="hidden" name="roleid" id="roleid" value="<?php echo $_SESSION['roleid'];?>">
                        <input type="button" onclick="push_transaction_by_category();" value="Send for Approval" class="btn btn-success">
                    </div>
                </div>             
            </fieldset>
        </form>
    </div>
    <?php
}
?>

<div class="container">
    <h3>History</h3>
    <table class="table newTable">
        <?php
//0=battery history, 1=tyre history, 2=repair/service history, 5=Accesories history
        $hist = false;
        if ($type == 0 || $type == 1 || $type == 2 || $type == 5) {
            $hist = get_mnt_history($vehicleid, $type);
            echo "<thead><tr><th>#</th><th>Transaction ID</th><th>Meter Reading</th><th>Dealer Name</th><th>Notes</th><th>Invoice No.</th><th>Invoice Amt</th><th>Invoice Date</th><th>Quotation amount</th>";
            echo "<th>Modified Date</th><th>Status</th><th>Battery srno</th>";
            if ($type == 1) {
                echo "<th>Tyre Type</th>";
                echo "<th>Tyre Serial No.</th>";
            } elseif ($type == 2) {
                echo "<th>Parts</th><th>Tasks</th>";
            } elseif ($type == 5) {
                echo "<th>Accessories</th>";
            }
            echo "</tr></thead><tbody>";
            if ($type == 2) {
                echo "<tr><td colspan ='100%' style = 'text-align:center;font-weight:bold'>Lables : U - Unit Price , Q - Quantity , T - Total Amount</td></tr>";
            }
            if ($hist) {
                $i = 1;
                foreach ($hist as $record) {
                    $mr = date('d-M-Y H:i', strtotime($record->mdate));
                    echo "<tr><td>$i</td>"
                    . "<td>{$record->transid}</td>"
                    . "<td>{$record->meter_reading}</td>"
                    . "<td>{$record->dname}</td>"
                    . "<td>{$record->notes}</td>"
                    . "<td>{$record->invno}</td>"
                    . "<td>{$record->invamt}</td>"
                    . "<td>{$record->invdate}</td>";
                    echo "<td>{$record->amount_quote}</td>"
                    . "<td>$mr</td>"
                    . "<td>{$record->msname}</td>";
                    if ($type == 1) {
                        echo "<td>{$record->repairtype}</td>";
                        echo "<td>{$record->tyre}</td>";
                    } elseif ($type == 2) {
                        //to check the parts and task in maintenance_parts/task table
                        $record_parts = getpartsby_maintenanceid($record->mid);
                        $record_tasks = gettaskby_maintenanceid($record->mid);

                        if (!empty($record_parts)) {
                            echo"<td>";
                            $j = 1;
                            foreach ($record_parts as $parts) {
                                echo $j . ") ";
                                echo $parts;
                                echo "<br />";
                                $j++;
                            }
                            echo"</td>";
                        } else {
                            echo"<td> </td>";
                        }
                        if (!empty($record_tasks)) {
                            echo"<td>";
                            $k = 1;
                            foreach ($record_tasks as $tasks) {
                                echo $k . ") ";
                                echo $tasks;
                                echo "<br />";
                                $k++;
                            }
                            echo"</td>";
                        } else {
                            echo"<td> </td>";
                        }
                        //echo "<td>".get_parts_name($parts, $record->parts)."</td><td>".get_task_name($tasks, $record->tasks)."</td>";
                    } elseif ($type == 5) {
                        echo "<td>{$record->access}</td>";
                    }
                    echo "<td>{$record->battery_srno}</td>";
                    echo "</tr>";
                    $i++;
                }
            }
        } elseif ($type == 3) { //Accident claim history
            $hist = get_mnt_accident_history($vehicleid);
            echo "<thead><tr><th>#</th><th>Transaction ID</th><th>Accident Date</th><th>Location</th><th>Injury/Damage</th><th>Accident Description</th>";
            echo "<th>Driver Name</th><th>License Validity</th><th>License Type</th><th>Workshop Location</th>";
            echo "<th>Loss amount</th><th>Settlement Amount</th><th>Repair Amount</th><th>Amount Spent</th>";
            echo "</tr></thead><tbody>";
            if ($hist) {
                $i = 1;
                foreach ($hist as $record) {
                    $adate = date('d-M-Y H:i', strtotime($record->accident_datetime));
                    $lv = date('d-M-Y', strtotime($record->lvfrom)) . ' to ' . date('d-M-Y', strtotime($record->lvto));
                    echo "<tr><td>$i</td><td>{$record->transid}</td><td>$adate</td><td>{$record->accident_location}</td><td>{$record->tpi_pd}</td><td>{$record->description}</td>";
                    echo "<td>{$record->drivername}</td><td>$lv</td>";
                    echo "<td>{$record->licence_type}</td>";
                    echo "<td>{$record->workshop_location}</td>";
                    echo "<td>{$record->loss_amount}</td>";
                    echo "<td>{$record->sett_amount}</td>";
                    echo "<td>{$record->actual_amount}</td>";
                    echo "<td>{$record->mahindra_amount}</td>";
                    echo "</tr>";
                    $i++;
                }
            }
        }
        if (!$hist) {
            echo "<tr><td colspan='100%' style='text-align:center;'>No Data found</td></tr>";
        }
        ?>
        </tbody>
    </table>
    <input type="hidden" name="typeid" id="typeid" value="<?php echo $typeid; ?>">
</div>
<script type="text/javascript">
    jQuery(document).ready(function () {
        jQuery(".txtsrno").hide();
    });
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
            jQuery("#tasks_category").show();
            jQuery("#parts_tax").show();
        } else {
            jQuery("#tasks_category").hide();
        }
    }
</script>

<div id="Dealer" class="modal hide in" style="width:800px; height:490px; display: none;left: 40%;">
    <center>
        <form class="form-horizontal" id="adddealer" method="post" action="modifytransaction.php">
            <fieldset>
                <div class="modal-header" >
                    <button class="close" data-dismiss="modal">×</button>
                    <h4 id="header-4" style="color:#0679c0"></h4>
                </div>
                <div class="modal-body">
                    <span id="name_error" style="display:none;">Please enter a name.</span>
                    <span id="phoneno_error" style="display:none;">Please enter a phoneno.</span>
                    <span id="cellphone_error" style="display:none;">Please enter a cell phone no.</span>
                    <span id="note_error" style="display:none;">Please enter a note.</span>
                    <span id="address_error" style="display:none;">Please enter an address.</span>
                    <span id="vendor_error" style="display:none;">Please select a vendor.</span>
                    <span id="branch_error" style="display:none;">Please select a branch.</span>
                    <span id="city_error" style="display:none;">Please select a <?php echo $_SESSION["city"]; ?>.</span>
                    <span id="district_error" style="display:none;">Please select a district.</span>
                    <span id="state_error" style="display:none;">Please select a state.</span>
                    <span id="dealer_success" style="display:none;">Dealer added successfully</span>
                    <span id="edit_amount_error" style="display:none;">Phone No. should contain numbers only</span>
                    <span id="other_error" style="display:none;">Please enter a name for upload document.</span>
                    <span id="upload1_error" style="display:none;">Please enter a name for upload document 1.</span>
                    <span id="upload2_error" style="display:none;">Please enter a name for upload document 2.</span>
                    <div class="input-prepend">
                        <span class="add-on" style="color: #000;">Name <span class="mandatory">*</span></span>
                        <span><input type="text" name="name" id="name" value="" placeholder="Name"/></span>
                        <span class="add-on" style="color: #000;">Phone No <span class="mandatory">*</span></span>
                        <span><input type="text" name="phoneno" id="phoneno" value="" placeholder="Phone No" maxlength="10"/></span>
                        <span class="add-on" style="color: #000;">Cell phone <span class="mandatory">*</span></span>
                        <span><input type="text" name="cellphone" id="cellphone" value="" placeholder="Cell Phone No" maxlength="10"/></span>
                    </div>
                    <br/>
                    <div class="input-prepend" style="color: #000;">
                        <span class="add-on" style="color: #000;">Type </span>
                        <input type="checkbox" name="battery" value="Battery" <?php
                        if ($type == 0) {
                            echo "checked";
                        }
                        ?>>Battery 
                        <input type="checkbox" name="tyre" value="Tyre"  <?php
                        if ($type == 1) {
                            echo "checked";
                        }
                        ?>>Tyre 
                        <input type="checkbox" name="service" value="Service"  <?php
                        if ($type == 2) {
                            echo "checked";
                        }
                        ?>>Service 
                        <input type="checkbox" name="repair" value="Repair"  <?php
                        if ($type == 2) {
                            echo "checked";
                        }
                        ?>>Repair 
                        <input type="checkbox" name="vehicle" value="Vehicle" >Vehicle 
                        <input type="checkbox" name="accessory" value="Accessory"  <?php
                        if ($type == 5) {
                            echo "checked";
                        }
                        ?>>Accessory                 
                        <input type="checkbox" name="fuel" value="Fuel">Fuel                 
                    </div>
                    <br/>
                    <br/>
                    <div class="input-prepend ">
                        <span class="add-on" style="color: #000;">Code</span> <input type="text" name="code" id="code" placeholder="Code">
                    </div>
                    <br/>
                    <br/>
                    <div class="input-prepend ">
                        <span class="add-on" style="color: #000;">Notes <span class="mandatory">*</span></span>   <textarea type="text" name="notes" id="notes" placeholder="Notes"></textarea>
                    </div>
                    <div class="input-prepend ">
                        <span class="add-on" style="color: #000;">Address <span class="mandatory">*</span></span> <textarea type="text" name="address" id="address" placeholder="Address"></textarea>
                    </div>
                    <br/>
                    <br/>
                    <div class="input-prepend">
                        <span style="color: #000;">Upload 1 </span> <span class="add-on" style="color: #000;">Name</span><input type="text" name="other1" id="other1"> <input type="file" name="file1" id="file1">
                    </div>
                    <div class="input-prepend">
                        <span style="color: #000;">Upload 2 </span> <span class="add-on" style="color: #000;">Name</span><input type="text" name="other2" id="other2"> <input type="file" name="file2" id="file2">
                    </div>
                    <br/><br/>
                    <div class="control-group pull-right">
                        <input type="button" value="Add Dealer" id="adddealerbtn" class="btn btn-primary" onclick="adddealer();">
                    </div>
                </div>
            </fieldset>
        </form>
    </center>
</div>   

<div id="PartPop" class="modal hide in" style="display: none;">
    <center>
        <form class="form-horizontal" id="addpartF" method="post" action="" onsubmit="addPart();
                return false;">
            <fieldset>
                <div class="modal-header">
                    <button class="close" data-dismiss="modal">×</button>
                    <h4 style="color:#0679c0">Add Part</h4>
                </div>
                <div class="modal-body">
                    <span id="partStatus" style="color:#000; display: none;"></span><br/>
                    <div class="input-prepend">
                        <span class="add-on" style="color: #000;">Name <span class="mandatory">*</span></span>
                        <span><input type="text" name="partnameP" id='partnameP' placeholder="Name" required/></span>
                    </div>
                    <br><br>
                    <div class="input-prepend">
                        <span class="add-on" style="color: #000;">Unit Amount </span>
                        <span><input type="text" name="partamountP" id='partamountP' placeholder="0.00" onkeypress="return isNumber(event)" required/></span>
                    </div>
                    <br><br>
                    <div class="input-prepend">
                        <span class="add-on" style="color: #000;">Unit Discount </span>
                        <span><input type="text" name="partdiscountP" id='partdiscountP' placeholder="0.00" onkeypress="return isNumber(event)" required/></span>
                    </div>
                    <br><br>
                    <div class="input-prepend">
                        <input type="submit" value="Add Part" class="btn btn-primary" />
                    </div>
                </div>
            </fieldset>
        </form>
    </center>
</div>

<div id="TaskPop" class="modal hide in" style="display: none;">
    <center>
        <form class="form-horizontal" id="addtaskF" method="post" action="" onsubmit="addTask();
                return false;">
            <fieldset>
                <div class="modal-header" >
                    <button class="close" data-dismiss="modal">×</button>
                    <h4 style="color:#0679c0">Add Task</h4>
                </div>
                <div class="modal-body">
                    <span id="taskStatus" style="color:#000;"></span><br/>
                    <div class="input-prepend">
                        <span class="add-on" style="color: #000;">Name <span class="mandatory">*</span></span>
                        <span><input type="text" name="tasknameP" id='tasknameP' placeholder="Name" required/></span>

                    </div>
                    <br><br>
                    <div class="input-prepend">
                        <span class="add-on" style="color: #000;">Unit Amount </span>
                        <span><input type="text" name="taskamountP" id='taskamountP' placeholder="0.00" onkeypress="return isNumber(event)" required/></span>
                    </div>
                    <br><br>
                    <div class="input-prepend">
                        <span class="add-on" style="color: #000;">Unit Discount </span>
                        <span><input type="text" name="taskdiscountP" id='taskdiscountP' placeholder="0.00" onkeypress="return isNumber(event)" required/></span>
                    </div>
                    <br><br>
                    <div class="input-prepend">
                        <input type="submit" value="Add Task" class="btn btn-primary" />
                    </div>

                </div>
            </fieldset>
        </form>
    </center>
</div>


<script type="text/javascript">
    var rowCount = 1;
    var rowCount1 = 1;
    function addrow() {
        if (rowCount > 49) {
            $("#btnaddrow").css('display', 'none');
        } else {
            $("#btnaddrow").css('display', 'block');
        }
        var table = document.getElementById("myTable");
        var row = table.insertRow(-1);
        row.id = rowCount + "trid";
        var cell1 = row.insertCell(0);
        var cell2 = row.insertCell(1);
        var cell3 = row.insertCell(2);
        var cell4 = row.insertCell(3);
        var cell5 = row.insertCell(4);
        var cell6 = row.insertCell(5);
        var cell7 = row.insertCell(6);
        var hidden_text = "<input type=\"hidden\" id=\"parts_max_amount_hid_" + rowCount + "\" name=\"parts_max_amount_hid_" + rowCount + "\" value=\"0\">";

        cell1.innerHTML = rowCount;
        cell2.innerHTML = "<select id=\"parts_select_" + rowCount + "\" onchange=\"addrow_select(" + rowCount + ")\" class=\"partsSelect  selectwidth \" name=\"parts_select_" + rowCount + "\"><option value=\"-1\">Select Parts</option><?php
                        if (isset($getpart)) {
                            foreach ($getpart as $accessory) {
                                ?> <option value=\"<?php echo $accessory->id; ?>\"><?php echo addslashes($accessory->part_name); ?></option><?php
                            }
                        }
                        ?> </select>";
        cell3.innerHTML = "<input type=\"text\" name=\"parts_qty" + rowCount + "\" onkeypress=\"return isNumber(event)\"  onblur=\"calculatetotal_parts(" + rowCount + ")\"  id=\"parts_qty" + rowCount + "\" placeholder=\"e.g. 5\" maxlength=\"10\" size=\"8\" value=\"1\">";
        cell4.innerHTML = "<input type=\"text\" name=\"parts_amount" + rowCount + "\" onkeypress=\"return isNumber(event)\"  id=\"parts_amount" + rowCount + "\" placeholder=\"e.g. 5\" maxlength=\"10\" size=\"8\" value=\"\">";
        cell5.innerHTML = "<input type=\"text\" name=\"parts_discs" + rowCount + "\" onkeypress=\"return isNumber(event)\"  id=\"parts_discs" + rowCount + "\" onblur=\"calculatetotal_parts(" + rowCount + ")\" placeholder=\"e.g. 10\" maxlength=\"10\" size=\"8\" value=\"\">";
        cell6.innerHTML = "<input type=\"text\" name=\"parts_tot" + rowCount + "\" onkeypress=\"return isNumber(event)\"  id=\"parts_tot" + rowCount + "\" onblur=\"calculatetotal_parts(" + rowCount + ")\" placeholder=\"e.g. 10\" maxlength=\"10\" size=\"8\" value=\"\">";
        cell7.innerHTML = "<a href=\"javascript:void(0);\" onclick=\"myDeleteFunction(" + rowCount + ");\"><img src=\"../../images/hide.gif\" alt=\"Delete\"/></a>" + hidden_text + "<input type='hidden' id='countrow" + rowCount + "' value='" + rowCount + "' />";
        rowCount++;
    }

    function myDeleteFunction(a) {
        var trid = '#' + a + 'trid';
        jQuery(trid).remove();
        rowCount--;
        if (rowCount > 49) {
            $("#btnaddrow").css('display', 'none');
        } else {
            $("#btnaddrow").css('display', 'block');
        }
    }

    function addrow1() {
        if (rowCount1 > 49) {
            $("#btnaddrow1").css('display', 'none');
        } else {
            $("#btnaddrow1").css('display', 'block');
        }
        var table = document.getElementById("myTable1");
        var row = table.insertRow(-1);
        row.id = rowCount1 + "trid1";
        var cell1 = row.insertCell(0);
        var cell2 = row.insertCell(1);
        var cell3 = row.insertCell(2);
        var cell4 = row.insertCell(3);
        var cell5 = row.insertCell(4);
        var cell6 = row.insertCell(5);
        var cell7 = row.insertCell(6);
        var hidden_text = "<input type=\"hidden\" id=\"tasks_max_amount_hid_" + rowCount1 + "\" name=\"tasks_max_amount_hid_" + rowCount1 + "\" value=\"0\">";

        cell1.innerHTML = rowCount1;
        cell2.innerHTML = "<select id=\"tasks_select_" + rowCount1 + "\" onchange=\"addrow_select1(" + rowCount1 + ")\" class=\"tasksSelect selectwidth\" name=\"tasks_select_" + rowCount1 + "\"><option value=\"-1\">Select Tasks</option><?php
                        if (isset($gettask)) {
                            foreach ($gettask as $accessory) {
                                ?> <option value=\"<?php echo $accessory->id; ?>\"><?php echo addslashes($accessory->task_name); ?></option><?php
                            }
                        }
                        ?> </select>";
        cell3.innerHTML = "<input type=\"text\" name=\"tasks_qty" + rowCount1 + "\" onkeypress=\"return isNumber(event)\"  onblur=\"calculatetotal_tasks(" + rowCount1 + ")\"  id=\"tasks_qty" + rowCount1 + "\" placeholder=\"e.g. 5\" maxlength=\"10\" size=\"8\" value=\"1\">";
        cell4.innerHTML = "<input type=\"text\" name=\"tasks_amount" + rowCount1 + "\" onkeypress=\"return isNumber(event)\"  id=\"tasks_amount" + rowCount1 + "\" placeholder=\"e.g. 5\" maxlength=\"10\" size=\"8\" value=\"\">";
        cell5.innerHTML = "<input type=\"text\" name=\"tasks_discs" + rowCount1 + "\" onkeypress=\"return isNumber(event)\"  id=\"tasks_discs" + rowCount1 + "\" onblur=\"calculatetotal_tasks(" + rowCount1 + ")\" placeholder=\"e.g. 10\" maxlength=\"10\" size=\"8\" value=\"\">";
        cell6.innerHTML = "<input type=\"text\" name=\"tasks_tot" + rowCount1 + "\" id=\"tasks_tot" + rowCount1 + "\" placeholder=\"e.g. 10\" onblur=\"calculatetotal_tasks(" + rowCount1 + ")\" maxlength=\"10\" size=\"8\" value=\"\">";
        cell7.innerHTML = "<a href=\"javascript:void(0);\" onclick=\"myDeleteFunction1(" + rowCount1 + ");\"><img src=\"../../images/hide.gif\" alt=\"Delete\"/></a>" + hidden_text + "<input type='hidden' id='countrow" + rowCount1 + "' value='" + rowCount1 + "' />";
        rowCount1++;
    }

    function myDeleteFunction1(a) {
        var trid1 = '#' + a + 'trid1';
        jQuery(trid1).remove();
        rowCount1--;
        if (rowCount1 > 49) {
            $("#btnaddrow").css('display', 'none');
        } else {
            $("#btnaddrow").css('display', 'block');
        }
    }

    function isNumber(evt) {
        evt = (evt) ? evt : window.event;
        var charCode = (evt.which) ? evt.which : evt.keyCode;
        if (charCode != 46 && charCode > 31 && (charCode < 48 || charCode > 57)) {
            return false;
        }
        return true;
    }
    function showtyresrno() {
        var tyrecat = $("#tyrerepair").val();
    }

    function addrow_select(id) {
        var selectid = $("#parts_select_" + id).find(":selected").val();
        if (selectid != -1) {
            var dataresult = "work=getpartsDetail&partid=" + selectid;
            jQuery.ajax({
                url: "route_ajax.php",
                type: 'POST',
                cache: false,
                data: dataresult,
                success: function (result) {
                    var obj = JSON.parse(result);
                    var unitamount1 = obj.unitamount;
                    var unitdiscount1 = obj.unitdiscount;
                    jQuery("#parts_amount" + id).val(unitamount1);
                    jQuery("#parts_discs" + id).val(unitdiscount1);
                }
            });
        }
    }

    function addrow_select1(id) {
        var selectid = $("#tasks_select_" + id).find(":selected").val();
        if (selectid != -1) {
            var dataresult = "work=gettaskDetail&taskid=" + selectid;
            jQuery.ajax({
                url: "route_ajax.php",
                type: 'POST',
                cache: false,
                data: dataresult,
                success: function (result) {
                    var obj = JSON.parse(result);
                    var unitamount1 = obj.unitamount;
                    var unitdiscount1 = obj.unitdiscount;
                    jQuery("#tasks_amount" + id).val(unitamount1);
                    jQuery("#tasks_discs" + id).val(unitdiscount1);
                }
            });
        }
    }
    
    jQuery('#zonemaster').on('change', function() {
    var zonemasterid = jQuery('#zonemaster').val();   
    if (zonemasterid != 0){
            var dataresult = "work=getregionalmanagerlist&zoneuserid=" + zonemasterid;
            jQuery.ajax({
                url: "route_ajax.php",
                type: 'POST',
                cache: false,
                data: dataresult,
                success: function (result){
                    jQuery("#regionaldiv").show();
                   jQuery("#regionaldiv").html(result);                    
                }
            });
        }else{
            jQuery("#regionaldiv").hide();
        }
    });
    
    
    function getbranch(){
        var regionalmasterid = jQuery('#regionalmaster').val();   
        if(regionalmasterid != 0){
            var dataresult = "work=getbranchmanagerlist&regionalid="+regionalmasterid;
            jQuery.ajax({
                url: "route_ajax.php",
                type: 'POST',
                cache: false,
                data: dataresult,
                success: function (result){
                    jQuery("#branchdiv").show();
                   jQuery("#branchdiv").html(result);                    
                }
            });
        }else{
            jQuery("#branchdiv").hide();
        }
    }
</script>