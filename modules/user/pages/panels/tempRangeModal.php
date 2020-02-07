
<!-- vehicle temp range modal starts-->
<div class="modal hide" id="tempRangeModal" style="width:800px;left:40%;" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" >
    <div class="modal-dialog" >
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close closetempRangeModal" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span>
                    <span class="sr-only"></span>
                </button>
                <h4 class="modal-title" id="exampleModalLabel"><span id="popTemptitle">Advance Temperature Range</span></h4>
            </div>
            <div class="modal-body" style="min-height: 300px; max-height: 500px;">
                <form id="popTempTableForm">
                    <span class='add-on' style='color:#000000'><?php echo $veh; ?></span>&nbsp;<input  type="text" name="vehicleText" id="vehicleText" size="20" value="" autocomplete="off" placeholder="Enter <?php echo $veh; ?>" onkeyup="refreshFun(<?php echo $get_uid; ?>)" />
                    <input type='hidden' name='vehicle_ids' id="vehicle_ids" required/>
                    <input type='hidden' name='userids' id="userids" value="<?php echo $get_uid; ?>" required/>
                    <input type='hidden' name='customernos' id="customernos" value="<?php echo $customerno; ?>" required/>
                    <input type='hidden' name='temp_sensors' id="temp_sensors" value="<?php echo $user->temp_sensors; ?>" required/>
                    <br><span id="error1" style="display: none;color:green;">Successfully Edited</span>
                    <span id="error2" style="display: none;color:red;">Failed</span>
                    <span id="error3" style="display: none;color:red;">Please Select <?php echo $veh; ?></span>
                    <table class="table table-condensed" id="popTempTable">
                        <thead>
                            <tr>
                                <th></th>
                                <th colspan="2">SMS</th>
                                <th colspan="2">Email</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <th>
                                <th>Min</th>
                                <th>Max</th>
                                <th>Min</th>
                                <th>Max</th>
                            </tr>
                            <?php
                            for ($i = 1; $i <= $user->temp_sensors; $i++) {
                                $min_sms = 'temp' . $i . '_min_sms';
                                $max_sms = 'temp' . $i . '_max_sms';
                                $min_email = 'temp' . $i . '_min_email';
                                $max_email = 'temp' . $i . '_max_email';
                                ?>
                                <tr>
                                    <td>Temperature <?php echo $i; ?></td>
                                    <td>
                                        <input type="number" style="width: 50px;" name='<?php echo $min_sms; ?>' id='<?php echo $min_sms; ?>' onfocus = "isVehicleSelected()" onblur="checkRange('min_sms',<?php echo $i; ?>)"/>
                                    </td>
                                    <td>
                                        <input type="number" style="width: 50px;" name='<?php echo $max_sms; ?>' id='<?php echo $max_sms; ?>' onfocus = "isVehicleSelected()" onblur="checkRange('max_sms',<?php echo $i; ?>)"/>
                                    </td>
                                    <td>
                                        <input type="number" style="width: 50px;"  name='<?php echo $min_email; ?>' id='<?php echo $min_email; ?>' onfocus = "isVehicleSelected()" onblur="checkRange('min_email',<?php echo $i; ?>)" />
                                    </td>
                                    <td>
                                        <input type="number" style="width: 50px;"  name='<?php echo $max_email; ?>' id='<?php echo $max_email; ?>' onfocus = "isVehicleSelected()" onblur="checkRange('max_email',<?php echo $i; ?>)"/>
                                    </td>
                                </tr>
                            <?php }
                            ?>
                        </tbody>
                    </table>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default closetempRangeModal" data-dismiss="modal" id='popClose'>Close</button>
                <button type="button" class="btn btn-default" onclick='clear_all_adv_temp();' >Clear All</button>
                <button type="button" class="btn btn-primary" onclick='add_advance_temp_range();'>Update <?php echo $vehs; ?></button>
            </div>
        </div>
    </div>
</div>

<script>

</script>
<!-- vehicle temp range modal ends -->

