<li>
<?php
include_once "pickup_functions.php";
include_once("../../lib/bo/VehicleManager.php");
$vehiclemanager = new vehiclemanager($_SESSION['customerno']);
$devices = get_vehicles_arr();
$get_slots = get_slots();

if(isset($devices)){
    ?>
    <ul>
        <div>
            <span class="tw_b">Date From </span><input type="text" id='orderdate' value="<?php echo date('d-m-Y');?>" style='width:100px;margin-left:2px;'><br/>
            <span class="tw_b">Slot</span>
            
            <!--<select id='slot_val'>
                <?php
                /*foreach($get_slots as $slotid=>$slotval){
                    echo "<option value='$slotid'>$slotid</option>";
                }*/
                ?>
            </select>-->
            <?php
            foreach($get_slots as $slotid=>$slotval){
                if($slotid==1){$checked="checked='checked'";}else{$checked='';}
                echo "<input type='checkbox' value='$slotid' class='slot_val' $checked/>";
                //echo "<option value='$slotid'>$slotid</option>";
            }
            echo "<br/><span style='margin-left:30px;'>1&nbsp;</span>";
            //echo "<br/><span style='margin-left:30px;'>1&nbsp;&nbsp;2&nbsp;&nbsp;3&nbsp;&nbsp;4&nbsp;5&nbsp;&nbsp;6</span>";
            ?>
                
        </div><br/>
        <div class="scrollheader">
            <span class="tw_b">Vehicles</span>
            <div class="scroll_head_container" >
                <label class="all_select scroll_lable tc_blue " data-type="vehicles" title="Click here to show all" >All </label> 
                <label  class="scroll_lable  ">|</label>
                <label class="all_clear scroll_lable tc_blue " data-type="vehicles" title="Click here to clear all" >Clear</label>
            </div>
        </div>
		
	<div class="scrollablediv" >
        <?php foreach($devices as $thisdevice=>$val) {  //onclick="plotRoute(this);"  ?>
        <input type="checkbox" class="veh_all vehCBox" id ="veh_<?php echo $thisdevice; ?>" /> <?php echo $val['username']; ?><br/>
        <?php } ?>
        </div>
        
        <br/>
        <!--
        <div>
            <span class="tw_b">Accuracy</span><br/><br/>
            <span style='background-color: #009900;color:#FFF;padding:1px;'>Accuracy 1</span>
            <span style='background-color: #0099ff;color:#FFF;padding:1px;'>Accuracy 2</span><br/><br/>
            <span style='background-color: #009999;color:#FFF;padding:1px;'>Accuracy 3</span>
            <span style='background-color: #005387;color:#FFF;padding:1px;'>Accuracy 4</span><br/><br/>
            <span style='background-color: #FFCA4D;color:#FFF;padding:1px;'>Accuracy 5</span>
        </div>
        -->
    </ul>
    <?php 
}
?>
</li>    