<?php $user = getuser();?>
<table id="floatingpanel11" >
    <thead>
    <tr>
        <th id="formheader" colspan="4">UserName</th>
    </tr>
    </thead>
    <tr>
        <td colspan="2" id="perfectinfo" style="display: none">User Information Modified</td>
        <td colspan="2" id="problem" style="display: none">Please Retry</td>        
    </tr>
    <tr>
        <td>Email</td>
        <td><input type="email" name="email" id="email" value="<?php echo $user->email;?>"></td>
        <td>Phone</td>
        <td><input type="text" name="phoneno" id="phoneno" value="<?php echo $user->phone;?>"></td>
    </tr>
</table>
<table id="floatingpanel12">
    <thead>
    <tr>
        <th id="formheader" colspan="6">Event Alerts</th>
    </tr>
    <tr>
    <td id="saved" style="display: none" colspan="6">Changes Saved</td>
    <td id="error" style="display: none" colspan="6">Error</td>
   </tr>
    <tr>
        <td>Type</td>
        <td>SMS</td>
        <td>Email</td>
        <td>Type</td>
        <td>SMS</td>
        <td>Email</td>
    </tr>
    </thead>
    <tbody>
    <tr>
            <td>Fence Conflict</td>
            <td><input type="checkbox" id="geosms" name="messsms" <?php if($user->mess_sms == 1) echo("checked"); ?>></td>
            <td><input type="checkbox" id="geoemail" name="messemail" <?php if($user->mess_email == 1) echo("checked"); ?>></td>
            <td>Over Speeding</td>
            <td><input type="checkbox" id="ospeedsms" name="speedsms" <?php if($user->speed_sms == 1) echo("checked"); ?>></td>
            <td><input type="checkbox" id="ospeedemail" name="speedemail" <?php if($user->speed_email == 1) echo("checked"); ?>></td>
        </tr>
        <tr>
            <td>Power Cut</td>
            <td><input type="checkbox" id="powercsms" name="powersms" <?php if($user->power_sms == 1) echo("checked"); ?>></td>
            <td><input type="checkbox" id="powercemail" name="poweremail" <?php if($user->power_email == 1) echo("checked"); ?>></td>
            <td>Device Tamper</td>
            <td><input type="checkbox" id="tampersms" name="tampersms" <?php if($user->tamper_sms == 1) echo("checked"); ?>></td>
            <td><input type="checkbox" id="tamperemail" name="tamperemail" <?php if($user->tamper_email == 1) echo("checked"); ?>></td>
        </tr>
        <tr>
            <td>Checkpoint</td>
            <td><input type="checkbox" id="chksms" name="chksms" <?php if($user->chk_sms == 1) echo("checked"); ?>></td>
            <td><input type="checkbox" id="chkemail" name="chkemail" <?php if($user->chk_email == 1) echo("checked"); ?>></td>
            <td>Air Conditioner</td>
            <td><input type="checkbox" id="acsms" name="acsms" <?php if($user->ac_sms == 1) echo("checked"); ?>></td>
            <td><input type="checkbox" id="acemail" name="acemail" <?php if($user->ac_email == 1) echo("checked"); ?>></td>
        </tr>  
        <tr>
            <td>Ignition</td>
            <td><input type="checkbox" id="igsms" name="igsms" <?php if($user->ignition_sms == 1) echo("checked"); ?>></td>
            <td><input type="checkbox" id="igemail" name="igemail" <?php if($user->ignition_email == 1) echo("checked"); ?>></td>
            <td>Temperature Sensor</td>
            <td><input type="checkbox" id="tempsms" name="tempsms" <?php if($user->temp_sms == 1) echo("checked"); ?>></td>
            <td><input type="checkbox" id="tempemail" name="tempemail" <?php if($user->temp_email == 1) echo("checked"); ?>></td>
        </tr>
        <tr>
            <td colspan="3"><input type="button" class="g-button g-button-submit" name="userdetails" value="Modify" onclick="dosaveuserdet_modal();"></td>
            <td colspan="3" align="center"><input type="button" id="close_popup1" class="g-button g-button-submit" name="close_popup1" onClick="updategroupid()" value="Skip"></td>
        </tr>
    </tbody>
</table>