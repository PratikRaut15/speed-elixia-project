<?php
    require 'panels/addtransaction.php';
    $vehicles = getvehicles();
    $x = 1;
    if(isset($vehicles))
    {
        foreach($vehicles as $vehicle)
        {
            echo "<tr>";
            echo "<td>".$x++."</td>";
            echo "<td>$vehicle->vehicleno</td>";
            if($_SESSION['groupid'] != null)
            {
                echo "<td>$vehicle->groupname</td>";
            }?>
        <td style='text-align: center;'><a href='modifytransaction.php?vehicleid=<?php echo $vehicle->vehicleid;?>&type=0'><img src='../../images/battery.png'></img><br/>Battery</td>
        <td style='text-align: center;'><a href='modifytransaction.php?vehicleid=<?php echo $vehicle->vehicleid;?>&type=1'><img src='../../images/tyre.png'></img><br/>Tyre</td>
        <td style='text-align: center;'><a href='modifytransaction.php?vehicleid=<?php echo $vehicle->vehicleid;?>&type=2'><img src='../../images/repair.png'></img><br/>Repair / Service</td>
        <td style='text-align: center;'><a href='modifytransaction.php?vehicleid=<?php echo $vehicle->vehicleid;?>&type=5'><img src='../../images/usb.png'></img><br/>Accesories</td>
        <td style='text-align: center;'><a href='modifytransaction.php?vehicleid=<?php echo $vehicle->vehicleid;?>&type=3'><img src='../../images/insurance.png'></img><br/>Accident Claim</td>
        <td style='text-align: center;'><a href='modifytransaction.php?vehicleid=<?php echo $vehicle->vehicleid;?>&type=7'><img src='../../images/insurance.png'></img><br/>Fuel</td>
   <?php
        }
    }
    else
        echo
        "<tr>
            <td colspan=100%>No Approved Vehicles</td>
        </tr>";
?>
    </tbody>
</table>
(Note: List of all Approved Vehicles)
<div class="modal hide" id="addview_approval">
    <form method="POST" id="getbattery_approval">
<fieldset>
    <div class="modal-header">
         <input type="hidden" id="vehicle_id" value=''>
         <input type="hidden" id="category_id" value=''>
        <button class="close" data-dismiss="modal">×</button>
        <h4 style="color:#0679c0" id="head_fortransac"></h4>
    </div>
    <div class="modal-body">
            <span id="dl_error" style="display: none;">Please Select Dealer</span>
            <span id="mr_error" style="display: none;">Please Enter Meter Reading</span>
            <span id="notes_error" style="display: none;">Please Enter Notes</span>
            <span id="quote_error" style="display: none;">Please Enter Quotation Amount</span>
            <span id="tyre_type_error" style="display: none;">Please Select Tyre Types</span>
            <span id="parts_type_error" style="display: none;">Please Select Parts or Tasks</span>
            <span id="max_perm_amount_error" style="display: none;">Cost cannot exceed maximum permissible amount</span>
            <span id="quote_exceed_error" style="display: none;">Quotation cannot exceed INR 22500/-</span>
            <p id="transaction_msg" name="transaction_msg" style="display: none;"></p>
            <div  style="overflow-y:scroll; height:400px;">
                    <table class="table table-bordered table-striped">
                        <th colspan="2">Required Details</th>
                        <tr>
                        <td width="50%">Meter Reading</td>
                        <td><input type="text" name="meter_reading" id="meter_readimng" placeholder="e.g. 12586" maxlength="10" ></td>
                        </tr>
                        <tr>
                        <td>Dealer</td>
                        <td><select id="dealerid" name="dealerid">
                            </select>
                        </td>
                        </tr>
                        <tr>
                        <td>Notes</td>
                        <td><input type="text" name="note_batt" id="note_batt" placeholder="e.g. 12586" maxlength="30" onkeypress="return nospecialchars(event)"></td>
                        </tr>
                        <tr>
                        <td>Quotation File</td>
                        <td><input type="file" title="Browse File" id="file_for_quote" name="file_for_quote" class="file-inputs"></td>
                        </tr>
                        <tr>
                        <td>Quotation amount</td>
                        <td><input type="text" name="amount_quote" id="amount_quote" placeholder="e.g. 125" maxlength="10" ></td>
                        </tr>
                    </table>
            <div id="expandable">
               </div>
                </div>
                <div class="control-group">
                <div class="input-prepend ">
                <input type="button" onclick="push_transaction_by_category();" value="Send for Approval" class="btn btn-success">
                </div>
                </div>
	</fieldset>
    </form>
    </div>
    <div id="batt">
    </div>
    <div id="tyre_fields" style="display: none;" >
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
                    </select><input type="button" id="add_task" name="add_task" value="+" onclick="add_items_container('#tasklist_container','task_select')">
                    <div id="tasklist_container"></div>
                </div>
                </div>
    </div>
    <div id="parts_service_category" style=" display: none;">
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
    </div>
    <div id="accessory_category" style=" display: none;">
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
<div class="modal hide" id="viewnotes">
    <form class="form-horizontal" id="notes_view">
        <fieldset>
            <div class="modal-header">
                <button class="close" data-dismiss="modal">×</button>
                <h4 style="color:#0679c0">Notes</h4>
            </div>
            <div class="modal-body">
			<div  style="overflow-y:scroll; height:400px;">
                    <fieldset>
                        <div class="control-group">
                        <div class="input-prepend">
                                <table  class="table  table-bordered table-striped dTableR dataTable"  style=" width:90%">
                                    <thead>
                                    <tr>
                                        <th>Notes</th>
                                        <th>Status</th>
                                        <th>Modified by</th>
                                        <th>Timestamp</th>
                                    </tr>
                                    </thead>
                                    <tbody id="notes_body">
                                    </tbody>
                                </table>
                            </div>
                        </div>
                </fieldset>
            </div>
        </fieldset>
    </form>
</div>
<div class="modal hide" id="accidentview_approval" style="width: 41%;">
    <form method="POST" id="getaccident_approval">
<fieldset>
    <div class="modal-header">
         <input type="hidden" id="vehicle_id1" value=''>
         <input type="hidden" id="category_id1" value=''>
        <button class="close" data-dismiss="modal">×</button>
        <h4 style="color:#0679c0" id="accident_head_fortransac"></h4>
    </div>
	 <div class="modal-body">
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
	 <div  style="overflow-y:scroll; height:400px;">
                        <?php
                            $currentdate = getcurrentdate();
                            $today = date('d-m-Y',$currentdate);
                        ?>
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
