<li>
<?php
    include_once "busrouteFunctions.php";
    $vehiclemanager = new vehiclemanager($_SESSION['customerno']);
    $devices = $vehiclemanager->get_all_vehicles();
    $get_slots = getSlots();
    $get_zone = getZones();
    if (isset($devices)) {
    ?>
    <ul>
        <div>
            <span class="tw_b">Slot</span>
            <div style="float: left; display: inline-block; line-height: 20px;">
            <?php
                foreach ($get_slots as $slotid => $slotval) {
                        if ($slotid == 1) {$checked = "checked='checked'";} else { $checked = '';}
                        echo "<input type='checkbox' name='slots[]'  style='float:left;' value='$slotid'  $checked class='slot_val'/>&nbsp;&nbsp;<span style='float:left;margin:0px 2px 0px 2px; '>" . $slotid . "</span>&nbsp;&nbsp;";
                    }
                ?>
            </div>
            <div style="clear: both;"> </div>
        </div>
        <div class="scrollheader">
            <span class="tw_b">Zone</span>
        </div>
        <br>
        <div>
        <?php
            foreach ($get_zone as $row) {
                    $zoneid = $row['zoneid'];
                    $zname = $row['zname'];
                    if ($zoneid == 1) {$checked = "checked='checked'";} else { $checked = '';}
                ?>
                <div>
                    <input type='checkbox' class='zone_val' name='zone[]' id='zone' style='float:left;' value='<?php echo $zoneid; ?>' $checked /><br>
                    <span style='float:left; line-height:1px; '><?php echo $zname; ?></span>
                </div>
                <br>
                <br>
        <?php }?>
        </div>
        <br>
        <div style="clear: both;"></div>
        <div class="scrollheader">
            <span class="tw_b">Vehicles</span>
            <div class="scroll_head_container">
                <label class="all_clear scroll_lable tc_blue" data-type="vehicles" title="Click here to clear all" >Clear</label>
            </div>
        </div>
	    <div class="scrollablediv" >
        <?php foreach ($devices as $thisdevice) {?>
            <input type="radio" name="vehicle" class="veh_all vehCBox"  id ="veh_<?php echo $thisdevice->vehicleid; ?>" value="<?php echo $thisdevice->vehicleid; ?>" /><?php echo $thisdevice->vehicleno; ?><br/>

        <?php }?>
        </div>
    </ul>
    <?php }?>
</li>