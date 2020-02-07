<?php
$fuelid = (int) exit_issetor($_REQUEST['fuelid'], 'Fuel id not found');

$maintanace_obj = new MaintananceManager($_SESSION['customerno']);
$fuel_obj = $maintanace_obj->get_fuel_details($fuelid);
if (is_null($fuel_obj)) {
    exit("Fuel entry not found");
}
$dealer_arr = pulldealer_byvehicle_arr($fuel_obj->vehicleid, 7);
?>
<div class="table" style="width: 50%;">
    <form method="POST" id="Editfuel_f">   
        <fieldset>
            <input type="hidden" name="fuelid" value='<?php echo $fuelid; ?>' />
            <div class="modal-header">
                <h4 style="color:#0679c0" >Edit Fuel</h4>
            </div>
            <div>
                <span id="dl_error" style="display: none;">Please Select Dealer</span>
                <span id="fuel_error" style="display: none;">Please Enter Fuel</span>            
                <span id="fuel_error1" style="display: none;">Please Check Fuel Value (e.g 123.25)</span>            
                <span id="amt_error" style="display: none;">Please Enter Amount</span>                        
                <span id="opening_error" style="display: none;">Please Enter Opening Km</span>                                    
                <span id="date_error" style="display: none;">Please Select Date</span>                                    
                <span id="time_error" style="display: none;">Please Select Time</span>                                    
                <span id="tyre_type_error" style="display: none;">Please Select Tyre Types</span>                                                
                <span id="parts_type_error" style="display: none;">Please Select Parts or Tasks</span>                                                            
                <span id="max_perm_amount_error" style="display: none;">Cost cannot exceed maximum permissible amount</span>                                                                        
                <span id="quote_exceed_error" style="display: none;">Quotation cannot exceed INR 22500/-</span>                                                                                    
                <p id="transaction_msg" name="transaction_msg" style="display: none;"></p>
            </div>

            <div>            
                <table class="table table-bordered table-striped">
                    <th colspan="2">Required Details</th>
                    <tr>
                        <td width="50%">Fuel (In Lt.)</td>
                        <td><input type="text" name="fuel" id="fuel" value='<?php echo $fuel_obj->fuel; ?>' id="fuelE" placeholder="e.g. 125" maxlength="7" ></td>
                    </tr>  

                    <tr>
                        <td>Date & Time</td>
                        <td>
                            <input id="SDate" name="SDate1" type="text" value="<?php echo $fuel_obj->added_date; ?>" required/>
                            <input id="STime" name="STime1" type="text" class="input-mini" data-date="<?php echo $fuel_obj->added_time; ?>" value="<?php echo $fuel_obj->added_time; ?>"/>
                        </td>
                    </tr>                        
                    <tr>
                        <td>Amount</td>
                        <td><input type="text" name="amount" value='<?php echo $fuel_obj->amount; ?>' id="amount" placeholder="e.g. 12586" maxlength="30" readonly="" ></td>
                    </tr> 
                    
                     <tr>
                        <td>Additional Amount</td>
                        <td><input type="text" name="addamount" value='<?php echo $fuel_obj->additional_amount; ?>' id="addamount" placeholder="e.g. 12586" maxlength="30"  ></td>
                    </tr> 
                    <tr>
                        <td>Rate</td>
                        <td><input type="text" name="rate" value='<?php echo $fuel_obj->rate; ?>' id="rate" placeholder="e.g. 125" maxlength="30" onblur="getAmount();"></td>
                    </tr>                    
                    <tr>
                        <td>Ref.No</td>
                        <td><input type="text" name="refno" id="refno" value='<?php echo $fuel_obj->refno; ?>' placeholder="e.g. 125" maxlength="10" ></td>
                    </tr>
                    <tr>
                        <td>Opening Km</td>
                        <td><input type="text" name="openingkm" id="openingkm" value="<?php echo $fuel_obj->openingkm; ?>"  placeholder="e.g. 125" maxlength="10" ></td>
                    </tr>
                    <tr>
                        <td>Ending Km</td>
                        <td><input type="text" name="endingkm" id="endingkm" value="<?php echo $fuel_obj->endingkm; ?>" placeholder="e.g. 125" maxlength="10" onblur="getAVG();"></td>
                    </tr>
                    <tr>
                        <td>Average</td>
                        <td><input type="text" name="avg" id="avg" value='<?php echo $fuel_obj->average; ?>' maxlength="10" ></td>
                    </tr>
                    <tr>
                        <?php
                        if ($_SESSION['customerno'] != 118) {
                            ?>
                        <tr>
                            <td>Per Day Km</td>
                            <td><input type="text" name="perday" id="perday" placeholder="e.g. 125" value="<?php echo $fuel_obj->perdaykm; ?>"onkeypress="return nospecialchars(event)" onblur="getRefilldate();" required /></td>
                        </tr>
    <?php
    if ($fuel_obj->refilldate == '01-01-1970' || $fuel_obj->refilldate == '30-11--0001') {
        $refdate = "";
    } else {
        $refdate = $fuel_obj->refilldate;
    }
    ?>
                        <tr>
                            <td>Date To Refill</td>
                            <td><input type="text" name="refilldate" id="refilldate" value="<?php echo $refdate; ?>" onkeypress="return nospecialchars(event)"></td>
                        </tr>
                        <?php
                    }
                    ?>
                    <td>Vendor</td>
                    <td>
                        <select id="dealerid" name="dealerid">
                            <option value='-1'>Select Dealer</option>
                    <?php
                    if (!empty($dealer_arr)) {
                        foreach ($dealer_arr as $dealid => $dealval) {
                            if ($dealid == $fuel_obj->dealerid) {
                                echo "<option value='$dealid' selected>$dealval</option>";
                            } else {
                                echo "<option value='$dealid'>$dealval</option>";
                            }
                        }
                    }
                    ?>
                        </select>
                        <a href='javascript:void(0);' onclick='clickdriver();'>Add Dealer</a>
                    </td>
                    </tr>
                    <tr>
                        <td>Notes</td>
                        <td>
                            <input type="text" name="notes" id="notes" value="<?php echo $fuel_obj->notes; ?>">
                        </td>
                    </tr>
                </table>

<!--                <span class="add-on" style="color:#000000">Slip No. </span>
                <input id="ofasnumber" type="text" onkeypress="return nospecialchars(event)" maxlength="50" placeholder="e.g. 12586" name="ofasnumber" value="<?php echo $fuel_obj->ofasnumber;?>">-->
            </div>
            <div class="control-group">
                <div class="input-prepend ">
                    <input type="button" onclick="edit_fuel();" value="Submit" class="btn btn-success">
                </div>
            </div>
        </fieldset>
    </form>
</div>

<?php
$cities = get_all_cities($_SESSION['heirarchy_id']);
$cityopt = "";
if (isset($cities)) {
    foreach ($cities as $thiscity) {
        $cityopt .= "<option value = '$thiscity->cityid'>$thiscity->name</option>";
    }
}
?>           
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
                        <span><input type="text" name="phoneno" id="phoneno" value="" placeholder="Phone No"/></span>
                        <span class="add-on" style="color: #000;">Cell phone <span class="mandatory">*</span></span>
                        <span><input type="text" name="cellphone" id="cellphone" value="" placeholder="Cell Phone No"/></span>
                    </div>
                    <br/>
                    <br/>
                    <div class="input-prepend">
<?php if ($_SESSION['use_hierarchy'] == '1') { ?>
                            <div class="input-prepend ">
                                <span class="add-on" style="color: #000;"><?php echo $_SESSION["city"]; ?></span>
                                <select id="cityid" name="cityid">
                                    <option value="">Select <?php echo $_SESSION["city"]; ?></option>
    <?php echo $cityopt; ?>
                                </select>
                            </div>
                        <?php } ?>
                    </div>
                    <br/>
                    <br/>
                    <div class="input-prepend" style="color: #000;">
                        <span class="add-on" style="color: #000;">Type </span>
                        <input type="checkbox" name="battery" value="Battery">Battery 
                        <input type="checkbox" name="tyre" value="Tyre">Tyre 
                        <input type="checkbox" name="service" value="Service">Service 
                        <input type="checkbox" name="repair" value="Repair">Repair 
                        <input type="checkbox" name="vehicle" value="Vehicle">Vehicle 
                        <input type="checkbox" name="accessory" value="Accessory">Accessory                 
                        <input type="checkbox" name="fuel" value="Fuel" checked="">Fuel                 
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