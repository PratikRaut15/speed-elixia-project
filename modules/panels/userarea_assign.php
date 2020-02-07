<li>
    <?php
    include_once "assign_function.php";
    include_once("../../lib/bo/VehicleManager.php");
    $vehiclemanager = new vehiclemanager($_SESSION['customerno']);
    $devices = $vehiclemanager->get_all_vehicles();
    $get_slots = get_slots();
    $get_zone = get_zones();


    if (isset($devices)) {
        ?>
        <ul>
            <div>
                <span class="tw_b">Date</span><input type="text" id='orderdate' value="<?php echo date('d-m-Y'); ?>" style='width:100px;margin-left:2px;'><br/>
                <span class="tw_b">Slot</span>

            <!--<select id='slot_val'>
                <?php
                /* foreach($get_slots as $slotid=>$slotval){
                  echo "<option value='$slotid'>$slotid</option>";
                  } */
                ?>
            </select>-->
                <br>
                <div style="float: left; display: inline-block; line-height: 20px;">

                    <?php
                    foreach ($get_slots as $slotid => $slotval) {
                        if ($slotid == 1) {
                            $checked = "checked='checked'";
                        } else {
                            $checked = '';
                        }
                        //echo "<input type='checkbox' value='$slotid' class='slot_val' $checked/>";
                        echo "<input type='checkbox' name='slots[]'  style='float:left;' value='$slotid'  $checked class='slot_val'/>&nbsp;&nbsp;<span style='float:left;margin:0px 2px 0px 2px; '>" . $slotid . "</span>&nbsp;&nbsp;";
                        //echo "<option value='$slotid'>$slotid</option>";
                    }
                    //echo "<br/><span style='margin-left:30px;'>1&nbsp;&nbsp;2&nbsp;&nbsp;3&nbsp;&nbsp;4&nbsp;5&nbsp;&nbsp;6</span>";
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
                    if ($zoneid == 1) {
                        $checked = "checked='checked'";
                    } else {
                        $checked = '';
                    }
                    echo "<div><input type='checkbox' class='zone_val' name='zone[]' id='zone' style='float:left;' value='$zoneid' $checked /><br><span style='float:left;margin:0px 2px 0px 2px; line-height:1px; '>" . $zname . "</span></div><br>";
                }
                ?>
            </div>
            <br>
            <div style="clear: both;"></div>
            <div class="scrollheader">
                <span class="tw_b">Delivery Boys</span>
                <div class="scroll_head_container">
                    <!--                <label class="all_select scroll_lable tc_blue " data-type="vehicles" title="Click here to show all" >All </label> 
                                    <label  class="scroll_lable  ">|</label>-->
                    <label class="all_clear scroll_lable tc_blue" data-type="vehicles" title="Click here to clear all" >Clear</label>
                </div>
            </div>

            <div class="scrollablediv">
                
                <?php foreach ($devices as $thisdevice) {  //onclick="plotRoute(this);"   ?>
                    <?php
                    $delboyname = $vehiclemanager->getdelboyname($thisdevice->vehicleid);

                    $delstr = "";
                    if (!empty($delboyname)) {
                        //$delstr = "<br>(" . $delboyname . ")";
                        $delstr = $delboyname;
                        ?>
                        <!-- <input type="radio" name="vehicle" class="veh_all vehCBox" id ="veh_<?php //echo $thisdevice->vehicleid;   ?>" value="<?php echo $thisdevice->vehicleid; ?>" /> <?php echo $thisdevice->vehicleno . $delstr; ?><br/>-->
                        <input type="radio" name="vehicle" class="veh_all vehCBox" id ="veh_<?php echo $thisdevice->vehicleid; ?>" value="<?php echo $thisdevice->vehicleid; ?>" /> <?php echo $delstr; ?><br/>
                    <?php
                    }
                }
                ?>
            </div>
            <br/>
            <div>
                <span class="tw_b">Accuracy</span><br/><br/>
                <span style='background-color: #009900;color:#FFF;padding:1px;'>Accuracy 1</span>
                <span style='background-color: #0099ff;color:#FFF;padding:1px;'>Accuracy 2</span><br/><br/>
                <span style='background-color: #009999;color:#FFF;padding:1px;'>Accuracy 3</span>
                <span style='background-color: #005387;color:#FFF;padding:1px;'>Accuracy 4</span><br/><br/>
                <span style='background-color: #FFCA4D;color:#FFF;padding:1px;'>Accuracy 5</span>
            </div>
        </ul>
        <?php
    }
    ?>
</li>    