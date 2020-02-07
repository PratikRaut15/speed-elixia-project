<script type="text/javascript">
jQuery(document).ready(function () {
jQuery('#myModal').modal('show');
});
</script>

<div class="modal hide" id="myModal">
<form class="form-horizontal">
<fieldset>
    <div class="modal-header">
        <button class="close" data-dismiss="modal">×</button>
        <h4 style="color:#0679c0">Change Password</h4>
    </div>
    <div class="modal-body">
            <span colspan="2" id="incorrect" style="display: none">Password Does Not Match</span>
            <span colspan="2" id="perfect" style="display: none">Password Changed</span>        
            <span colspan="2" id="newempty" style="display: none">Please Enter New Password</span>                
            <span colspan="2" id="confirmempty" style="display: none">Please Enter Confirm Password</span>
        <div class="control-group">
          <label class="control-label" for="passwordinput">New Password</label>
          <div class="controls">
            <input id="newpasswd" name="newpasswd" type="password" placeholder="New Password" class="input-xlarge">

          </div>
        </div>

        <div class="control-group">
          <label class="control-label" for="passwordinput">Confirm Password</label>
          <div class="controls">
            <input id="confirm_newpasswd" name="confirm_newpasswd" type="password" placeholder="Confirm New Password" class="input-xlarge">

          </div>
        </div>
    </div>
</fieldset>
</form>
    <div class="modal-footer">
        <button id="close_popup" data-dismiss="modal" onClick="showmodal()" name="password" class="btn btn-danger">Skip</button>
        <button id="button1id" name="password" onclick="dosave_modalnew();" class="btn btn-success">Modify</button>
    </div>
</div>


<div class="modal hide" id="myModal1" style="width:800px; margin: -350px 0 0 -400px;"><!-- note the use of "hide" class -->
<fieldset>
    <?php
    $user = getuser();
    $stoppage_user = get_stoppage_alerts();
    $start_alert_time = date("H:i",strtotime($user->start_alert_time));
    $stop_alert_time = date("H:i",strtotime($user->stop_alert_time)); 
    $custom = getcustom();
    $customname = (isset($custom->customname)=='') ? 'Air Conditioner' : $custom->customname;
    $is_disabled = ($user->use_advanced_alert==0) ? ' disabled="disabled" ' : '';
    ?>
  <div class="modal-header">
    <button class="close" data-dismiss="modal">×</button>
    <h4 style="color:#0679c0">My Account</h4>
  </div>
  <div class="modal-body">
        <span colspan="2" id="perfectinfo" style="display: none">User Information Modified</span>
        <span colspan="2" id="problem" style="display: none">Please Retry</span>
        <span id="emailerror" name="emailerror" style="display: none;">Not a valid e-mail address</span>
        <span id="smserror" name="smserror" style="display: none;">Not a valid phone number</span> 
        
        <div class="well form-inline" style="text-align: center;">
            <span class="add-on" style="color:#000000;">Email</span>
            <input id="email" name="email" type="email" placeholder="Email Address" value="<?php echo $user->email;?>" class="input-medium">
            <span class="add-on" style="color:#000000;">Phone</span>
            <input id="phoneno" name="phoneno" type="text" placeholder="Phone Number" value="<?php echo $user->phone;?>" class="input-medium">
        </div>
        <div class="modal-header">
            <h4 style="color:#0679c0">Alerts</h4>
        </div>
        <div class="well form-inline">
            <span id="saved" style="display: none" colspan="6">Changes Saved</span>
            <span id="error" style="display: none" colspan="6">Error</span>       
            <table id="floatingpanel12" class="zebra-striped">
                    <thead>
                    <tr>
                    <th colspan="3">Event Alerts</th>
                    <th colspan="3">Advanced Alerts(To activate, contact an Elixir)</th>
                    <th colspan="3">Daily Email</th>
                    </tr>
                <tr>
                    <td>Type</td>
                    <td>SMS</td>
                    <td>Email</td>
                    <td>Type</td>
                    <td>SMS</td>
                    <td>Email</td>
                    <td>Reports</td>
                    <td>PDF(Password Protected)</td>
                    <td>CSV</td>
                </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Checkpoint</td>
                        <td><input type="checkbox" id="chksms" name="chksms" <?php if($user->chk_sms == 1) echo("checked"); ?>></td>
                        <td><input type="checkbox" id="chkemail" name="chkemail" <?php if($user->chk_email == 1) echo("checked"); ?>></td>
                        <td>Harsh Break</td>
                        <td><input type="checkbox" id="harsh_break_sms" name="harsh_break_sms" <?php if($user->harsh_break_sms == 1) echo("checked"); echo $is_disabled; ?> ></td>
                        <td><input type="checkbox" id="harsh_break_mail" name="harsh_break_mail" <?php if($user->harsh_break_mail == 1) echo("checked"); echo $is_disabled; ?> ></td>
                        <td>Daily Email Reports</td>
                        <td><input type="checkbox" id="dailyemail" name="dailyemail" <?php if($user->dailyemail == 1) echo ("checked");?>></td>
                        <td><input type="checkbox" id="dailyemail_csv" name="dailyemail_csv" <?php if($user->dailyemail_csv == 1) echo ("checked");?>></td>
                    </tr>
                    <tr>
                        <td>Fence Conflict</td>
                        <td><input type="checkbox" id="geosms" name="messsms" <?php if($user->mess_sms == 1) echo("checked"); ?>></td>
                        <td><input type="checkbox" id="geoemail" name="messemail" <?php if($user->mess_email == 1) echo("checked"); ?>></td>
                        <td>Sudden Acceleration</td>
                        <td><input type="checkbox" id="high_acce_sms" name="high_acce_sms" <?php if($user->high_acce_sms == 1) echo("checked"); echo $is_disabled; ?> ></td>
                        <td><input type="checkbox" id="high_acce_mail" name="high_acce_mail" <?php if($user->high_acce_mail == 1) echo("checked"); echo $is_disabled; ?> ></td>
                        <td rowspan="3" colspan="3">
                            <table width="100%">
                                <thead>
                                    <tr>
                                        <th colspan="3" id="formheader">Time Based Alert</th>
                                    </tr>
                                    <tr>
                                        <td id="saved_time" style="display: none" colspan="2">Changes Saved</td>
                                        <td id="error_time" style="display: none" colspan="2">Error</td>
                                    </tr>
                                    <tr>
                                        <td>Start Time</td>
                                        <td>End Time</td>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td><input id="STime" name="SDate" type="text" data-date="<?php echo($start_alert_time); ?>"/></td>
                                        <td><input id="ETime" name="EDate" type="text" data-date2="<?php echo($stop_alert_time); ?>"/></td>
                                    </tr>
                                </tbody>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <th colspan="3">Vehicle Alerts</th>
                        <td>Towing</td>
                        <td><input type="checkbox" id="towing_sms" name="towing_sms" <?php if($user->towing_sms == 1) echo("checked"); echo $is_disabled;  ?> ></td>
                        <td><input type="checkbox" id="towing_mail" name="towing_mail" <?php if($user->towing_mail == 1) echo("checked"); echo $is_disabled; ?> ></td>
                        
                    </tr>  
                    <tr>
                        <td>Temperature Sensor</td>
                        <td><input type="checkbox" id="tempsms" name="tempsms" <?php if($user->temp_sms == 1) echo("checked"); ?>></td>
                        <td><input type="checkbox" id="tempemail" name="tempemail" <?php if($user->temp_email == 1) echo("checked"); ?>></td>
                        <td colspan="3"></td>
                        <!--<td>Sharp Turn</td>
                        <td><input type="checkbox" id="sharp_turn_sms" name="sharp_turn_sms" <?php if($user->sharp_turn_sms == 1) echo("checked"); echo $is_disabled; ?> ></td>
                        <td><input type="checkbox" id="sharp_turn_mail" name="sharp_turn_mail" <?php if($user->sharp_turn_mail == 1) echo("checked"); echo $is_disabled;  ?> ></td>
                        -->
                    </tr>
                    <tr>
                        <td>Ignition</td>
                        <td><input type="checkbox" id="igsms" name="igsms" <?php if($user->ignition_sms == 1) echo("checked"); ?>></td>
                        <td><input type="checkbox" id="igemail" name="igemail" <?php if($user->ignition_email == 1) echo("checked"); ?>></td>
                    </tr>
                    <tr>
                        <td>Over Speeding</td>
                        <td><input type="checkbox" id="ospeedsms" name="speedsms" <?php if($user->speed_sms == 1) echo("checked"); ?>></td>
                        <td><input type="checkbox" id="ospeedemail" name="speedemail" <?php if($user->speed_email == 1) echo("checked"); ?>></td>
                        <td rowspan="4" colspan="4">
                            <table width="100%">
                                <thead>
                                    <tr>
                                        <th colspan="4" id="formheader">Stoppage Alerts</th>
                                    </tr>
                                    <tr>
                                        <td id="saved_stop" style="display: none" colspan="4">Changes Saved</td>
                                        <td id="error_stop" style="display: none" colspan="4">Error</td>
                                        <td style="display: none"></td>
                                        <td style="display: none"></td>                                        
                                    </tr>
                                    <tr>
                                        <td>Type</td>
                                        <td>SMS</td>
                                        <td>Email</td>
                                        <td>Max. Idle Time</td>                                        
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>In Checkpoints</td>
                                        <td><input id="safcsms" name="safcsms" type="checkbox" <?php if(isset($stoppage_user->safcsms) == 1) echo ("checked");?>/></td>
                                        <td><input id="safcemail" name="safcemail" type="checkbox" <?php if(isset($stoppage_user->safcemail) == 1) echo ("checked");?>/></td>
                                        <td><input id="safcmin" name="safcmin" type="text" value="<?php if(isset($stoppage_user->safcmin)) { echo ($stoppage_user->safcmin); } else { echo ("0"); } ?>" /> mins</td>                                        
                                    </tr>
                                    <tr>
                                        <td>In Transit</td>
                                        <td><input id="saftsms" name="saftsms" type="checkbox" <?php if(isset($stoppage_user->saftsms) == 1) echo ("checked");?>/></td>
                                        <td><input id="saftemail" name="saftemail" type="checkbox" <?php if(isset($stoppage_user->saftemail) == 1) echo ("checked");?>/></td>
                                        <td><input id="saftmin" name="saftmin" type="text" value="<?php if(isset($stoppage_user->saftmin)) { echo ($stoppage_user->saftmin); } else { echo ("0"); } ?>" /> mins</td>                                        
                                    </tr>                                    
                                </tbody>
                            </table>
                        </td>   
                    </tr>
                    <tr>
                        <td><?php echo $customname; ?></td>
                        <td><input type="checkbox" id="acsms" name="acsms" <?php if($user->ac_sms == 1) echo("checked"); ?>></td>
                        <td><input type="checkbox" id="acemail" name="acemail" <?php if($user->ac_email == 1) echo("checked"); ?>></td>
                    </tr>
                    <tr>
                        <td>Door Sensor</td>
                        <td><input type="checkbox" id="doorsms" name="doorsms" <?php if($user->door_sms == 1) echo("checked"); ?>></td>
                        <td><input type="checkbox" id="dooremail" name="dooremail" <?php if($user->door_email == 1) echo("checked"); ?>></td>
                    </tr>
                    <tr>
                        <th colspan="3">Device Alerts</th>
                    </tr>
                    <tr>
                        <td>Power Cut</td>
                        <td><input type="checkbox" id="powercsms" name="powersms" <?php if($user->power_sms == 1) echo("checked"); ?>></td>
                        <td><input type="checkbox" id="powercemail" name="poweremail" <?php if($user->power_email == 1) echo("checked"); ?>></td>
                    </tr>
                    <tr>
                        <td>Device Tamper</td>
                        <td><input type="checkbox" id="tampersms" name="tampersms" <?php if($user->tamper_sms == 1) echo("checked"); ?>></td>
                        <td><input type="checkbox" id="tamperemail" name="tamperemail" <?php if($user->tamper_email == 1) echo("checked"); ?>></td>
                    </tr>
                    <tr></tr>
                    <tr></tr>                    
                </tbody>
            </table>
        </div>
</fieldset>
  <div class="modal-footer">
      <button id="close_popup1" data-dismiss="modal" onClick="updategroupid()" name="close_popup1" class="btn btn-danger">Skip</button>
    <button id="button1id" name="userdetails" onclick="dosaveuserdet_modal();" class="btn btn-success">Modify</button>
  </div>
</div>​