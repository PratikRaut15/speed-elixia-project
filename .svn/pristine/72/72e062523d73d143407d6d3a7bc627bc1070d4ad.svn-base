<style>
    input[type=range],
    -moz-range-track,
    -ms-track {
        -webkit-appearance: none;
        background-color: #ddd;
        width: 200px;
        height:20px;
    }
    ::-moz-range-thumb {
        background:orange;
    }
    ::-ms-fill-lower {
        outline: none;
        background: blueviolet;
        border-radius: 10px 0 0 10px;
    }
    .table td {
        text-align: center;
    }
</style>
<?php
(isset($_GET['uid'])) ? $get_uid = (int) $_GET['uid'] : $get_uid = $_SESSION['userid'];
include_once '../../lib/bo/NewAlertsManager.php';
include_once '../../lib/comman_function/reports_func.php';
$AlertManager = new NewAlertsManager();
$vehicleManager = new VehicleManager($customerno);
$start_def = "00:00";
$end_def = "23:59";
if (!isset($ajax_url)) {
    $ajax_url = "ajax_alert.php";
}
if (!isset($exception_url)) {
    $exception_url = "exception_ajax.php";
}
$user = getuser($get_uid);
$stoppage_user = get_stoppage_alerts($get_uid);
$checkpointopt = get_checkpoints();
$vehicle_count = $vehicleManager->get_all_vehicles_count($_SESSION['switch_to']);
$new_alerts = extract($AlertManager->get_all_alert_time($get_uid)); //get preset details of alert
$customname = getcustombyid(1);
$is_disabled = ($user->use_advanced_alert == 0) ? ' disabled="disabled" ' : '';
$disable_advanced_modify = ($user->use_advanced_alert == 0 && $user->use_immobiliser == 0 && $user->use_panic == 0) ? ' disabled="disabled" ' : '';
$vehicles = getvehicles_all();
$adv_temp_veh = $vehicleManager->getAdvanceTempRangeVeh($get_uid);
$veh_dropdown = '';
if (isset($_SESSION['switch_to']) && $_SESSION['switch_to'] == 3) {
    if (isset($_SESSION['Warehouse']) && !empty($_SESSION['Warehouse'])) {
        $veh = $_SESSION['Warehouse'];
        $vehs = $_SESSION['Warehouse'] . "s";
    } else {
        $veh = "Warehouse";
        $vehs = "Warehouses";
    }
} else {
    $veh = "Vehicle";
    $vehs = "Vehicles";
}
if (isset($vehicles)) {
    foreach ($vehicles as $vehicleid => $veh_no) {
        $veh_dropdown .= "<option value='$vehicleid'>{$veh_no['vehicleno']}</option>";
    }
}
$objExAlert = new stdClass();
$objExAlert->userid = $get_uid;
$objExAlert->customerno = $_SESSION['customerno'];
$chkExAlert = getUserAlertMapping($objExAlert);
$chkException = getUserExceptionMapping($objExAlert);
$prevException = isset($chkException) ? implode(',', array_map(function($element) {
                    return $element->chkExId;
                }, $chkException)) : '';
?>
<?php /* dt: 19th dec 14, ak edited, added vehicle and start-end time input in BOOTSTRAP Model */ ?>
<div class="container-fluid">
    <?php if ($_SESSION['portable'] != '1') { ?>
        <script language="JavaScript" type="text/javascript">
            var def_url = "<?php echo $ajax_url; ?>";
            var exception_url = "<?php echo $exception_url; ?>";
            var uid = <?php echo $get_uid; ?>;
            var defStartTime = '<?php echo $start_def; ?>';
            var defEndTime = '<?php echo $end_def; ?>';
            var allVehCount = '<?php echo $vehicle_count; ?>';
            var allText = allVehCount + ' <?php echo $vehs; ?> selected';
            var dateIdArray = ['STimeTemp', 'ETimeTemp', 'STimeHum', 'ETimeHum', 'STimeIg', 'ETimeIg', 'STimeOs', 'ETimeOs', 'STimeGn', 'ETimeGn', 'STimePowerc', 'ETimePowerc', 'STimeTamper', 'ETimeTamper', 'STimeharsh_break', 'ETimeharsh_break', 'STimehigh_acce', 'ETimehigh_acce', 'STimetowing', 'ETimetowing', 'popStartTime', 'popEndTime', 'STimePanic', 'ETimePanic', 'STimeImmob', 'ETimeImmob', 'STimeDoor', 'ETimeDoor', 'STimeAlert', 'ETimeAlert'];
        </script>
        <form id='vehicleAlertForm'>
            <table class="table table-condensed">
                <thead>
                    <tr>
                        <th>Type</th>
                        <th>SMS</th>
                        <th>Email</th>
                        <th>Telephone</th>
                        <th>Mobile Notification</th>
                        <th>Start Time</th>
                        <th>End Time</th>
                        <th>Interval(In minutes)</th>
                        <th width="25%"><?php echo $veh; ?></th>
                    </tr>
                    <tr><th colspan='100%'><?php echo $veh; ?> Alerts</th></tr>
                </thead>
                <tbody>
                    <tr>
                        <td></td>
                        <td colspan="4">Alert Start From</td>
                        <td>
                            <input type="text" name='alertSTime' id='STimeAlert' class='input-mini'  data-date="<?php echo_issetor($start_alert, $start_def); ?>" />
                        </td>
                        <td>To</td>
                        <td>
                            <input type="text" name='alertETime' id='ETimeAlert' class='input-mini'  data-date="<?php echo_issetor($stop_alert, $end_def); ?>" />
                        </td>
                        <td></td>
                    </tr>

                    <?php if ($user->temp_sensors > 0) { ?>
                        <tr>
                            <td>Temperature Sensor</td>
                            <td>
                                <input type='checkbox' name='temp[sms]' id="tempsms" <?php if ($user->temp_sms == 1) echo("checked"); ?> />
                            </td>
                            <td>
                                <input type='checkbox' name='temp[email]' id="tempemail" <?php if ($user->temp_email == 1) echo("checked"); ?> />
                            </td>
                            <td>
                                <input type='checkbox' name='temp[telephone]' id="temptelephone" <?php if ($user->temp_telephone == 1) echo("checked"); ?> />
                            </td>
                            <td>
                                <input type='checkbox' name='temp[mobile]' id="tempmobile" <?php if ($user->temp_mobile == 1) echo("checked"); ?> />
                            </td>
                            <td>
                                <input type="text" name='temp[stTime]' id='STimeTemp' class='input-mini'  data-date="<?php echo_issetor($temp_starttime, $start_def); ?>"   <?php check_readonly($temp['checkSpec']); ?> />
                            </td>
                            <td>
                                <input type="text" name='temp[edTime]' id='ETimeTemp' class='input-mini' data-date="<?php echo_issetor($temp_endtime, $end_def); ?>" <?php check_readonly($temp['checkSpec']); ?> />
                            </td>
                            <td>
                                <input type="text" name='temp[interval]' id='tempinterval' class='input-mini'  value='<?php echo $user->tempinterval; ?>' />
                            </td>
                            <td>
                                All <?php echo $vehs; ?>: <input type="radio" name='temp[veh]'  class='allRadio' value='all' <?php echo_issetor($temp['checkAll'], 'checked'); ?> />
                                Specific <?php echo $vehs; ?>: <input type="radio" name='temp[veh]' id='vehicleAddTypeTempId' onclick='assign_radio_id(this.id, "temp");' value='spec' class='specRadio' data-toggle="modal" data-target="#exampleModal"  <?php echo_issetor($temp['checkSpec']); ?> /><br/>
                                <span><?php echo_issetor($temp['vehText']); ?></span>
                                <input type="hidden" name="temp[vehlist]" id='vehicleAddTypeTempIdVal' values=''/>
                            </td>
                        </tr>
                    <?php } ?>
                    <tr>
                        <td>Humidity Sensor</td>
                        <td>
                            <input type='checkbox' name='hum[sms]' id="humsms" <?php if ($user->hum_sms == 1) echo("checked"); ?> />
                        </td>
                        <td>
                            <input type='checkbox' name='hum[email]' id="humemail" <?php if ($user->hum_email == 1) echo("checked"); ?> />
                        </td>
                        <td>
                            <input type='checkbox' name='hum[telephone]' id="humtelephone" <?php if ($user->hum_telephone == 1) echo("checked"); ?> />
                        </td>
                        <td>
                            <input type='checkbox' name='hum[mobile]' id="hummobile" <?php if ($user->hum_mobile == 1) echo("checked"); ?> />
                        </td>
                        <td>
                            <input type="text" name='hum[stTime]' id='STimeHum' class='input-mini'  data-date="<?php echo_issetor($hum_starttime, $start_def); ?>"   <?php check_readonly($hum['checkSpec']); ?> />
                        </td>
                        <td>
                            <input type="text" name='hum[edTime]' id='ETimeHum' class='input-mini' data-date="<?php echo_issetor($hum_endtime, $end_def); ?>" <?php check_readonly($hum['checkSpec']); ?> />
                        </td>
                        <td>
                            <input type="text" name='hum[interval]' id='huminterval' class='input-mini'  value='<?php echo $user->huminterval; ?>' />
                        </td>
                        <td>
                            All <?php echo $vehs; ?>: <input type="radio" name='hum[veh]'  class='allRadio' value='all' <?php echo_issetor($hum['checkAll'], 'checked'); ?> />
                            Specific <?php echo $vehs; ?>: <input type="radio" name='hum[veh]' id='vehicleAddTypeHumId' onclick='assign_radio_id(this.id, "hum");' value='spec' class='specRadio' data-toggle="modal" data-target="#exampleModal"  <?php echo_issetor($hum['checkSpec']); ?> /><br/>
                            <span><?php echo_issetor($hum['vehText']); ?></span>
                            <input type="hidden" name="hum[vehlist]" id='vehicleAddTypeHumIdVal' values=''/>
                        </td>
                    </tr>
                    <?php if ($user->use_warehouse != 1 || ($user->use_warehouse == 1 && $_SESSION['switch_to'] != 3)) { ?>
                        <tr>
                            <td>Ignition</td>
                            <td><input type='checkbox' name='ignition[sms]' id="ignitionsms" <?php if ($user->ignition_sms == 1) echo("checked"); ?>  /></td>
                            <td><input type='checkbox' name='ignition[email]' id="ignitionemail" <?php if ($user->ignition_email == 1) echo("checked"); ?>  /></td>
                            <td><input type='checkbox' name='ignition[telephone]' id="ignitiontelephone" <?php if ($user->ignition_telephone == 1) echo("checked"); ?>  /></td>
                            <td><input type='checkbox' name='ignition[mobile]' id="ignitionmobile" <?php if ($user->ignition_mobile == 1) echo("checked"); ?>  /></td>
                            <td><input type="text" name='ignition[stTime]' id='STimeIg'  class='input-mini' data-date="<?php echo_issetor($ignition_starttime, $start_def); ?>" <?php check_readonly($ignition['checkSpec']); ?> /></td>
                            <td><input type="text" name='ignition[edTime]' id='ETimeIg' class='input-mini' data-date="<?php echo_issetor($ignition_endtime, $end_def); ?>" <?php check_readonly($ignition['checkSpec']); ?>/></td>
                            <td><input type="text" name='ignition[interval]' id='igninterval' class='input-mini'  value='<?php echo $user->igninterval; ?>' /></td>
                            <td>
                                All <?php echo $vehs; ?>: <input type="radio" name='ignition[veh]' class='allRadio' value='all' <?php echo_issetor($ignition['checkAll'], 'checked'); ?> />
                                Specific <?php echo $vehs; ?>: <input type="radio" name='ignition[veh]' id='vehicleAddTypeIg' onclick='assign_radio_id(this.id, "ignition");' value='spec' class='specRadio' data-toggle="modal" data-target="#exampleModal" <?php echo_issetor($ignition['checkSpec']); ?> /><br/>
                                <span><?php echo_issetor($ignition['vehText']); ?></span>
                                <input type="hidden" name="ignition[vehlist]" id='vehicleAddTypeIgVal' values=''/>
                            </td>
                        </tr>
                        <tr>
                            <td>Over Speeding</td>
                            <td><input type='checkbox' name='speed[sms]' id="speedsms" <?php if ($user->speed_sms == 1) echo("checked"); ?>  /></td>
                            <td><input type='checkbox' name='speed[email]' id="speedemail" <?php if ($user->speed_email == 1) echo("checked"); ?> /></td>
                            <td><input type='checkbox' name='speed[telephone]' id='speedtelephone' <?php if ($user->speed_telephone == 1) echo("checked"); ?> /></td>
                            <td><input type='checkbox' name='speed[mobile]' id='speedmobile' <?php if ($user->speed_mobile == 1) echo("checked"); ?> /></td>
                            <td><input type="text" name='speed[stTime]' id='STimeOs' class='input-mini' data-date="<?php echo_issetor($speed_starttime, $start_def); ?>" <?php check_readonly($speed['checkSpec']); ?> /></td>
                            <td><input type="text" name='speed[edTime]' id='ETimeOs'class='input-mini' data-date="<?php echo_issetor($speed_endtime, $end_def); ?>" <?php check_readonly($speed['checkSpec']); ?>/></td>
                            <td><input type="text" name='speed[interval]' id='overinterval' class='input-mini'  value='<?php echo $user->speedinterval; ?>' /></td>
                            <td>
                                All <?php echo $vehs; ?>: <input type="radio" name='speed[veh]'  class='allRadio' value='all' <?php echo_issetor($speed['checkAll'], 'checked'); ?> />
                                Specific <?php echo $vehs; ?>: <input type="radio" name='speed[veh]' id='vehicleAddTypeOS' onclick='assign_radio_id(this.id, "speed");' value='spec' class='specRadio' data-toggle="modal" data-target="#exampleModal" <?php echo_issetor($speed['checkSpec']); ?>/><br/>
                                <span><?php echo_issetor($speed['vehText']); ?></span>
                                <input type="hidden" name="speed[vehlist]" id='vehicleAddTypeOSVal' values=''/>
                            </td>
                        </tr>
                    <?php } ?>
                    <tr>
                        <td><?php echo $customname; ?></td>
                        <td><input type='checkbox' name='ac[sms]' id="acsms" <?php if ($user->ac_sms == 1) echo("checked"); ?> /></td>
                        <td><input type='checkbox' name='ac[email]' id="acemail" <?php if ($user->ac_email == 1) echo("checked"); ?> /></td>
                        <td><input type='checkbox' name='ac[telephone]' id="actelephone" <?php if ($user->ac_telephone == 1) echo("checked"); ?> /></td>
                        <td><input type='checkbox' name='ac[mobile]' id="acmobile" <?php if ($user->ac_mobile == 1) echo("checked"); ?> /></td>
                        <td><input type="text" name='ac[stTime]' id='STimeGn' class='input-mini' data-date="<?php echo_issetor($ac_starttime, $start_def); ?>" <?php check_readonly($ac['checkSpec']); ?> /></td>
                        <td><input type="text" name='ac[edTime]' id='ETimeGn' class='input-mini' data-date="<?php echo_issetor($ac_endtime, $end_def); ?>" <?php check_readonly($ac['checkSpec']); ?> /></td>
                        <td><input type="text" name='ac[interval]' id='acinterval' class='input-mini'  value='<?php echo $user->acinterval; ?>' /></td>
                        <td>
                            All <?php echo $vehs; ?>: <input type="radio" name='ac[veh]'  class='allRadio' value='all' <?php echo_issetor($ac['checkAll'], 'checked'); ?> />
                            Specific <?php echo $vehs; ?>: <input type="radio" name='ac[veh]' id='vehicleAddTypeGen' onclick='assign_radio_id(this.id, "ac");' value='spec' class='specRadio'  data-toggle="modal" data-target="#exampleModal" <?php echo_issetor($ac['checkSpec']); ?>/><br/>
                            <span><?php echo_issetor($ac['vehText']); ?></span>
                            <input type="hidden" name="ac[vehlist]" id='vehicleAddTypeGenVal' values=''/>
                        </td>
                    </tr>
                    <tr>
                        <td>Door Sensor</td>
                        <td><input type='checkbox' name='door[sms]' id="doorsms" <?php if ($user->door_sms == 1) echo("checked"); ?> /></td>
                        <td><input type='checkbox' name='door[email]' id="dooremail" <?php if ($user->door_email == 1) echo("checked"); ?> /></td>
                        <td><input type='checkbox' name='door[telephone]' id="doortelephone" <?php if ($user->door_telephone == 1) echo("checked"); ?> /></td>
                        <td><input type='checkbox' name='door[mobile]' id="doormobile" <?php if ($user->door_mobile == 1) echo("checked"); ?> /></td>
                        <td><input type="text" name='door[stTime]' id='STimeDoor' class='input-mini' data-date="<?php echo_issetor($door_starttime, $start_def); ?>" <?php check_readonly($door['checkSpec']); ?> /></td>
                        <td><input type="text" name='door[edTime]' id='ETimeDoor' class='input-mini' data-date="<?php echo_issetor($door_endtime, $end_def); ?>" <?php check_readonly($door['checkSpec']); ?> /></td>
                        <td><input type="text" name='door[interval]' id='doorinterval' class='input-mini'  value='<?php echo $user->doorinterval; ?>' /></td>
                        <td>
                            All <?php echo $vehs; ?>: <input type="radio" name='door[veh]'  class='allRadio' value='all' <?php echo_issetor($ac['checkAll'], 'checked'); ?> />
                            Specific <?php echo $vehs; ?>: <input type="radio" name='door[veh]' id='vehicleAddTypeDoor' onclick='assign_radio_id(this.id, "door");' value='spec' class='specRadio'  data-toggle="modal" data-target="#exampleModal" <?php echo_issetor($door['checkSpec']); ?>/><br/>
                            <span><?php echo_issetor($door['vehText']); ?></span>
                            <input type="hidden" name="door[vehlist]" id='vehicleAddTypeDoorVal' values=''/>
                        </td>
                    </tr>
                    <?php if ($user->use_warehouse != 1 || ($user->use_warehouse == 1 && $_SESSION['switch_to'] != 3)) { ?>
                        <tr>
                            <td>Checkpoint Exception</td>
                            <td><input type='checkbox' name='chkptExSms' id="chkptExSms" <?php echo isset($chkExAlert[0]->status) ? $chkExAlert[0]->status : ""; ?> /></td>
                            <td><input type='checkbox' name='chkptExEmail' id="chkptExEmail"  <?php echo isset($chkExAlert[1]->status) ? $chkExAlert[1]->status : ""; ?>/></td>
                            <td><input type='checkbox' name='chkptExtelephone' id="chkptExTelephone"  <?php echo isset($chkExAlert[2]->status) ? $chkExAlert[2]->status : ""; ?>/></td>
                            <td><input type='checkbox' name='chkptExMobile' id="chkptExMobile"  <?php echo isset($chkExAlert[3]->status) ? $chkExAlert[3]->status : ""; ?>/></td>
                            <td><input type="text" name='' id='' class='input-mini'  readonly="" /></td>
                            <td><input type="text" name='' id='' class='input-mini'  readonly="" /></td>
                            <td><input type="text" name='chkptInterval' id='chkptInterval' class='input-mini'  value='0' readonly="" /></td>
                            <td>
                                <a href='javascript:void(0);' data-toggle="modal" data-target="#chkptExModal">Select Exception</a>
                                <input type="hidden" name="chkExAlertMapping" id="chkExAlertMapping" value="<?php echo $prevException; ?>"/>
                                <input type="hidden" name="prevChkExAlertMapping" id="prevChkExAlertMapping" value="<?php echo $prevException; ?>"/>
                            </td>
                        </tr>
                    <?php } if ($user->temp_sensors > 0) { ?>
                        <tr>
                            <td colspan="5">Alert when temperature comes in range after conflict</td>
                            <td colspan="2">
                                Yes&nbsp;<input type='radio' name='isTempInrangeAlertRequired' value="1" <?php if ($user->isTempInrangeAlertRequired == 1) echo("checked"); ?> />
                                &nbsp;&nbsp;&nbsp;No&nbsp;<input type='radio' name='isTempInrangeAlertRequired' value="0" <?php if ($user->isTempInrangeAlertRequired == 0) echo("checked"); ?> />
                            </td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td colspan="5">Advance temperature conflict range for <?php echo $vehs; ?></td>
                            <td colspan="2">
                                Yes&nbsp;<input type='radio' name='isAdvTempConfRange' value="1" <?php if ($user->isAdvTempConfRange == 1) echo("checked"); ?> />
                                &nbsp;&nbsp;&nbsp;No&nbsp;<input type='radio' name='isAdvTempConfRange' value="0" <?php if ($user->isAdvTempConfRange == 0) echo("checked"); ?> />
                            </td>
                            <td></td>
                            <td></td>

                        </tr>
                        <tr style="display: none;" id="AdvTempConfRange">
                            <td colspan="5"></td>
                            <td colspan="2">
                                <input type="button" class="btn  btn-default" value="Add New <?php echo $veh; ?>" onclick="addNewAdvTempRange();" data-toggle="modal" data-target="#tempRangeModal">
                            </td>
                            <td colspan="2">
                                <?php
                                if (!empty($adv_temp_veh)) {
                                    echo '<table width="100%">';
                                    foreach ($adv_temp_veh as $vehl) {
                                        echo '<tr>';
                                        echo '<td style="text-align:left;padding-left:5px"><span style="">' . $vehl["vehicleno"] . '</span></td>
                                              <td><input type="button" class="btn  btn-default" name="edittemprange" onclick="edit_advance_temp(' . $vehl["vehicleid"] . ',' . $get_uid . ',' . $user->temp_sensors . ');" value="Edit"  data-toggle="modal" data-target="#tempRangeModal"/></td>
                                              <td><input type="button" class="btn  btn-default" value="Delete" onclick="delete_advance_temp(' . $vehl["vehicleid"] . ')" /></td>';
                                        echo '</tr>';
                                    }
                                    echo '</table>';
                                }
                                ?>
                            </td> 
                        </tr>
                    <?php } ?>
                    <tr><th colspan='100%'>Device Alerts</th></tr>
                    <tr>
                        <td>Power Cut</td>
                        <td><input type='checkbox' name='powerc[sms]' id="powercsms" <?php if ($user->power_sms == 1) echo("checked"); ?> /></td>
                        <td><input type='checkbox' name='powerc[email]' id="powercemail" <?php if ($user->power_email == 1) echo("checked"); ?> /></td>
                        <td><input type='checkbox' name='powerc[telephone]' id="powerctelephone" <?php if ($user->power_telephone == 1) echo("checked"); ?> /></td>
                        <td><input type='checkbox' name='powerc[mobile]' id="powercmobile" <?php if ($user->power_mobile == 1) echo("checked"); ?> /></td>
                        <td><input type="text" name='powerc[stTime]' id='STimePowerc' class='input-mini' data-date="<?php echo_issetor($powerc_starttime, $start_def); ?>" <?php check_readonly($powerc['checkSpec']); ?>/></td>
                        <td><input type="text" name='powerc[edTime]' id='ETimePowerc' class='input-mini' data-date="<?php echo_issetor($powerc_endtime, $end_def); ?>" <?php check_readonly($powerc['checkSpec']); ?>/></td>
                        <td width="25%">
                            All <?php echo $vehs; ?>: <input type="radio" name='powerc[veh]'  class='allRadio' value='all' <?php echo_issetor($powerc['checkAll'], 'checked'); ?> />
                            Specific <?php echo $vehs; ?>: <input type="radio" name='powerc[veh]' id='vehicleAddTypePower' onclick='assign_radio_id(this.id, "powerc");' value='spec' class='specRadio'  data-toggle="modal" data-target="#exampleModal" <?php echo_issetor($powerc['checkSpec']); ?>/><br/>
                            <span><?php echo_issetor($powerc['vehText']); ?></span>
                            <input type="hidden" name="powerc[vehlist]" id='vehicleAddTypePowercVal' values=''/>
                        </td>
                    </tr>
                    <tr>
                        <td>Device Tamper</td>
                        <td><input type='checkbox' name='tamper[sms]' id="tampersms" <?php if ($user->tamper_sms == 1) echo("checked"); ?> /></td>
                        <td><input type='checkbox' name='tamper[email]' id="tamperemail" <?php if ($user->tamper_email == 1) echo("checked"); ?> /></td>
                        <td><input type='checkbox' name='tamper[telephone]' id="tampertelephone" <?php if ($user->tamper_telephone == 1) echo("checked"); ?> /></td>
                        <td><input type='checkbox' name='tamper[mobile]' id="tampermobile" <?php if ($user->tamper_mobile == 1) echo("checked"); ?> /></td>
                        <td><input type="text" name='tamper[stTime]' id='STimeTamper' class='input-mini' data-date="<?php echo_issetor($tamper_starttime, $start_def); ?>" <?php check_readonly($tamper['checkSpec']); ?>/></td>
                        <td><input type="text" name='tamper[edTime]' id='ETimeTamper' class='input-mini' data-date="<?php echo_issetor($tamper_endtime, $end_def); ?>" <?php check_readonly($tamper['checkSpec']); ?> /></td>
                        <td width="25%">
                            All <?php echo $vehs; ?>: <input type="radio" name='tamper[veh]'  class='allRadio' value='all' <?php echo_issetor($tamper['checkAll'], 'checked'); ?> />
                            Specific <?php echo $vehs; ?>: <input type="radio" name='tamper[veh]' id='vehicleAddTypeTamper' onclick='assign_radio_id(this.id, "tamper");' value='spec' class='specRadio'  data-toggle="modal" data-target="#exampleModal" <?php echo_issetor($tamper['checkSpec']); ?>/><br/>
                            <span><?php echo_issetor($tamper['vehText']); ?></span>
                            <input type="hidden" name="tamper[vehlist]" id='vehicleAddTypeTampercVal' values=''/>
                        </td>
                    </tr>
                    <tr>
                        <td colspan='100%'>
                            <input type="button" class="btn  btn-primary" value="Modify" onclick="submitVehicleAlert(); return false;">
                        </td>
                    </tr>
                </tbody>
            </table>
            <input type='hidden' name='toDo' value='addVehAlert'/>
        </form>
        <?php if ($user->use_warehouse != 1 || ($user->use_warehouse == 1 && $_SESSION['switch_to'] != 3)) { ?>
            <form id="advancedalerts">
                <table class="table table-condensed">
                    <thead>
                        <tr>
                            <th colspan="100%" id="formheader">&nbsp;&nbsp;Advanced Alerts(To activate, contact an Elixir)&nbsp;&nbsp;</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Harsh Break</td>
                            <td>
                                <input type='checkbox' name='harsh_break[sms]' id="harsh_break_sms" <?php if ($user->harsh_break_sms == 1) echo("checked");echo $is_disabled; ?> />
                            </td>
                            <td><input type='checkbox' name='harsh_break[email]' id="harsh_break_email" <?php if ($user->harsh_break_mail == 1) echo("checked");echo $is_disabled; ?> />
                            </td>
                            <td>
                                <input type='checkbox' name='harsh_break[telephone]' id="harsh_break_telephone" <?php if ($user->harsh_break_telephone == 1) echo("checked");echo $is_disabled; ?> />
                            </td>
                            <td>
                                <input type='checkbox' name='harsh_break[mobile]' id="harsh_break_mobile" <?php if ($user->harsh_break_mobile == 1) echo("checked");echo $is_disabled; ?> />
                            </td>
                            <td><input type="text" name='harsh_break[stTime]' id='STimeharsh_break' class='input-mini' data-date="<?php echo_issetor($harsh_break_starttime, $start_def); ?>" <?php check_readonly($harsh_break['checkSpec']); echo $is_disabled; ?>/>
                            </td>
                            <td><input type="text" name='harsh_break[edTime]' id='ETimeharsh_break' class='input-mini' data-date="<?php echo_issetor($harsh_break_endtime, $end_def); ?>" <?php check_readonly($harsh_break['checkSpec']); echo $is_disabled; ?>/>
                            </td>
                            <td>
                                All <?php echo $vehs; ?>:
                                <input type="radio" name='harsh_break[veh]'  class='allRadio' value='all' <?php echo_issetor($harsh_break['checkAll'], 'checked'); echo $is_disabled; ?> />
                                Specific <?php echo $vehs; ?>:
                                <input type="radio" name='harsh_break[veh]' id='vehicleAddTypeHarsh_break' onclick='assign_radio_id(this.id, "harsh_break");' value='spec' class='specRadio'  data-toggle="modal" data-target="#exampleModal" <?php echo_issetor($harsh_break['checkSpec']); echo $is_disabled; ?>/><br/>
                                <span><?php echo_issetor($harsh_break['vehText']); ?></span>
                                <input type="hidden" name="harsh_break[vehlist]" id='vehicleAddTypeHarsh_breakVal' values=''/>
                            </td>
                        </tr>
                        <tr>
                            <td>Sudden Acceleration</td>
                            <td>
                                <input type='checkbox' name='high_acce[sms]' id="high_acce_sms" <?php if ($user->high_acce_sms == 1) echo("checked");echo $is_disabled; ?> />
                            </td>
                            <td>
                                <input type='checkbox' name='high_acce[email]' id="high_acce_email" <?php if ($user->high_acce_mail == 1) echo("checked");echo $is_disabled; ?> />
                            </td>
                            <td>
                                <input type='checkbox' name='high_acce[telephone]' id="high_acce_telephone" <?php if ($user->high_acce_telephone == 1) echo("checked");echo $is_disabled; ?> />
                            </td>
                            <td>
                                <input type='checkbox' name='high_acce[mobile]' id="high_acce_mobile" <?php if ($user->high_acce_mobile == 1) echo("checked");echo $is_disabled; ?> />
                            </td>
                            <td>
                                <input type="text" name='high_acce[stTime]' id='STimehigh_acce' class='input-mini' data-date="<?php echo_issetor($high_acce_starttime, $start_def); ?>" <?php check_readonly($high_acce['checkSpec']); echo $is_disabled; ?>/>
                            </td>
                            <td>
                                <input type="text" name='high_acce[edTime]' id='ETimehigh_acce' class='input-mini' data-date="<?php echo_issetor($high_acce_endtime, $end_def); ?>" <?php check_readonly($high_acce['checkSpec']); echo $is_disabled; ?>/>
                            </td>
                            <td>
                                All <?php echo $vehs; ?>:
                                <input type="radio" name='high_acce[veh]'  class='allRadio' value='all' <?php echo_issetor($high_acce['checkAll'], 'checked'); echo $is_disabled; ?> />
                                Specific <?php echo $vehs; ?>:
                                <input type="radio" name='high_acce[veh]' id='vehicleAddTypeHarsh_break' onclick='assign_radio_id(this.id, "high_acce");' value='spec' class='specRadio'  data-toggle="modal" data-target="#exampleModal" <?php echo_issetor($high_acce['checkSpec']); echo $is_disabled; ?>/><br/>
                                <span><?php echo_issetor($high_acce['vehText']); ?></span>
                                <input type="hidden" name="high_acce[vehlist]" id='vehicleAddTypeHigh_acceVal' values=''/>
                            </td>
                        </tr>
                        <tr>
                            <td>Towing</td>
                            <td>
                                <input type='checkbox' name='towing[sms]' id="towingsms" <?php if ($user->towing_sms == 1) echo("checked ");echo $is_disabled; ?> >
                            </td>
                            <td>
                                <input type='checkbox' name='towing[email]' id="towingemail" <?php if ($user->towing_mail == 1) echo("checked ");echo $is_disabled; ?> ></td>
                            <td>
                                <input type='checkbox' name='towing[telephone]' id="towingtelephone" <?php if ($user->towing_telephone == 1) echo("checked ");echo $is_disabled; ?> >
                            </td>
                            <td>
                                <input type='checkbox' name='towing[mobile]' id="towingmobile" <?php if ($user->towing_mobile == 1) echo("checked ");echo $is_disabled; ?> >
                            </td>
                            <td>
                                <input type="text" name='towing[stTime]' id='STimetowing' class='input-mini' data-date="<?php echo_issetor($towing_starttime, $start_def); ?>" <?php
                               check_readonly($towing['checkSpec']);
                               echo $is_disabled;
                               ?>/>
                            </td>
                            <td><input type="text" name='towing[edTime]' id='ETimetowing' class='input-mini' data-date="<?php echo_issetor($towing_endtime, $end_def); ?>" <?php
                                check_readonly($towing['checkSpec']);
                                echo $is_disabled;
                                ?>/>
                            </td>
                            <td>
                                All <?php echo $vehs; ?>:
                                <input type="radio" name='towing[veh]'  class='allRadio' value='all' <?php
                        echo_issetor($towing['checkAll'], 'checked');
                        echo $is_disabled;
                        ?> />
                                Specific <?php echo $vehs; ?>:
                                <input type="radio" name='towing[veh]' id='vehicleAddTypeTowing' onclick='assign_radio_id(this.id, "towing");' value='spec' class='specRadio'  data-toggle="modal" data-target="#exampleModal" <?php
                        echo_issetor($towing['checkSpec']);
                        echo $is_disabled;
                        ?>/><br/>
                                <span><?php echo_issetor($towing['vehText']); ?></span>
                                <input type="hidden" name="towing[vehlist]" id='vehicleAddTypeTowingVal' values=''/>
                            </td>
                        </tr>
        <?php if ($user->use_panic == 1) { ?>
                            <tr>
                                <td>Panic</td>
                                <td><input type='checkbox' name='panic[sms]' id="panicsms" <?php if ($user->panic_sms == 1) echo("checked"); ?> /></td>
                                <td><input type='checkbox' name='panic[email]' id="panicemail" <?php if ($user->panic_email == 1) echo("checked"); ?> /></td>
                                <td><input type='checkbox' name='panic[telephone]' id="panictelephone" <?php if ($user->panic_telephone == 1) echo("checked"); ?> /></td>
                                <td><input type='checkbox' name='panic[mobile]' id="panicmobile" <?php if ($user->panic_mobile == 1) echo("checked"); ?> /></td>
                                <td><input type="text" name='panic[stTime]' id='STimePanic' class='input-mini' data-date="<?php echo_issetor($panic_starttime, $start_def); ?>" <?php check_readonly($panic['checkSpec']); ?>/></td>
                                <td><input type="text" name='panic[edTime]' id='ETimePanic' class='input-mini' data-date="<?php echo_issetor($panic_endtime, $end_def); ?>" <?php check_readonly($panic['checkSpec']); ?> /></td>
                                <td>
                                    All <?php echo $vehs; ?>: <input type="radio" name='panic[veh]'  class='allRadio' value='all' <?php echo_issetor($panic['checkAll'], 'checked'); ?> />
                                    Specific <?php echo $vehs; ?>: <input type="radio" name='panic[veh]' id='vehicleAddTypePanic' onclick='assign_radio_id(this.id, "panic");' value='spec' class='specRadio'  data-toggle="modal" data-target="#exampleModal" <?php echo_issetor($panic['checkSpec']); ?>/><br/>
                                    <span><?php echo_issetor($panic['vehText']); ?></span>
                                    <input type="hidden" name="panic[vehlist]" id='vehicleAddTypePanicVal' values=''/>
                                </td>
                            </tr>
        <?php } if ($user->use_immobiliser == 1) { ?>
                            <tr>
                                <td>Immobilizer</td>
                                <td><input type='checkbox' name='immob[sms]' id="immobsms" <?php if ($user->immob_sms == 1) echo("checked"); ?> /></td>
                                <td><input type='checkbox' name='immob[email]' id="immobemail" <?php if ($user->immob_email == 1) echo("checked"); ?> /></td>
                                <td><input type='checkbox' name='immob[telephone]' id="immobtelephone" <?php if ($user->immob_telephone == 1) echo("checked"); ?> /></td>
                                <td><input type='checkbox' name='immob[mobile]' id="immobmobile" <?php if ($user->immob_mobile == 1) echo("checked"); ?> /></td>
                                <td><input type="text" name='immob[stTime]' id='STimeImmob' class='input-mini' data-date="<?php echo_issetor($immob_starttime, $start_def); ?>" <?php check_readonly($immob['checkSpec']); ?>/></td>
                                <td><input type="text" name='immob[edTime]' id='ETimeImmob' class='input-mini' data-date="<?php echo_issetor($immob_endtime, $end_def); ?>" <?php check_readonly($immob['checkSpec']); ?> /></td>
                                <td>
                                    All <?php echo $vehs; ?>: <input type="radio" name='immob[veh]'  class='allRadio' value='all' <?php echo_issetor($immob['checkAll'], 'checked'); ?> />
                                    Specific <?php echo $vehs; ?>: <input type="radio" name='immob[veh]' id='vehicleAddTypeImmob' onclick='assign_radio_id(this.id, "immob");' value='spec' class='specRadio'  data-toggle="modal" data-target="#exampleModal" <?php echo_issetor($immob['checkSpec']); ?>/><br/>
                                    <span><?php echo_issetor($immob['vehText']); ?></span>
                                    <input type="hidden" name="immob[vehlist]" id='vehicleAddTypeImmobVal' values=''/>
                                </td>
                            </tr>
            <?php } ?>
                        <tr>
                            <td colspan="100%"><input type="button" class="btn  btn-primary" value="Modify" onclick="submitAdvancedAlert();
                                            return false;" <?php echo $disable_advanced_modify; ?> ></td>
                        </tr>
                    </tbody>
                </table>
                <input type='hidden' name='toDo' value='addAdvancedAlert'/>
            </form>
        <?php
    }
}
?>
</div>
<?php /* ak changes ends */ ?>
<div  class='container' >
<?php if ($user->use_warehouse != 1 || ($user->use_warehouse == 1 && $_SESSION['switch_to'] != 3)) { ?>
        <table id="floatingpanel">
            <form id="alerts" method="POST" action="route.php">
                <thead>
                    <tr>
                        <th colspan="5" id="formheader">Event Alerts</th>
                    </tr>
                    <tr>
                        <td id="saved" style="display: none" colspan="3">Changes Saved</td>
                        <td id="error" style="display: none" colspan="3">Error</td>
                    </tr>
                    <tr>
                        <td>Type</td>
                        <td>SMS</td>
                        <td>Email</td>
                        <td>Telephone</td>
                        <td>Mobile Notifications</td>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Checkpoint</td>
                        <td><input type="checkbox" id="chksms" name="chksms"  <?php if ($user->chk_sms == 1) echo("checked"); ?>></td>
                        <td><input type="checkbox" id="chkemail" name="chkemail" <?php if ($user->chk_email == 1) echo("checked"); ?>></td>
                        <td><input type="checkbox" id="chktelephone" name="chktelephone" <?php if ($user->chk_telephone == 1) echo("checked"); ?>></td>
                        <td><input type="checkbox" id="chkmobile" name="chkmobile" <?php if ($user->chk_mobile == 1) echo("checked"); ?>></td>
                    </tr>
                    <tr>
                        <td>Fence Conflict</td>
                        <td><input type="checkbox" id="geosms" name="messsms" <?php if ($user->mess_sms == 1) echo("checked"); ?>></td>
                        <td><input type="checkbox" id="geoemail" name="geoemail" <?php if ($user->mess_email == 1) echo("checked"); ?>></td>
                        <td><input type="checkbox" id="geotelephone" name="geotelephone" <?php if ($user->mess_telephone == 1) echo("checked"); ?>></td>
                        <td><input type="checkbox" id="geomobile" name="geomobile" <?php if ($user->mess_mobile == 1) echo("checked"); ?>></td>
                    </tr>
                    <tr>
                        <td colspan="100%"><input type="button" name="EModify" id="EModify" class="btn  btn-primary" value="Modify" onclick="dosave_alerts(<?php echo $get_uid; ?>);">
                        </td>
                    </tr>
                </tbody>
            </form>
        </table>
    <?php
}if ($_SESSION['portable'] != '1') {
    if ($user->use_warehouse != 1 || ($user->use_warehouse == 1 && $_SESSION['switch_to'] != 3)) {
        ?>
            <table id="floatingpanel">
                <form id="Stopalerts" name="Stopalerts" method="POST" >
                    <thead>
                        <tr>
                            <th colspan="6" id="formheader">Stoppage Alerts</th>
                        </tr>
                        <tr>
                            <td id="saved_s" style="display: none" colspan="4">Changes Saved</td>
                            <td id="error_s" style="display: none" colspan="4">Error</td>
                        </tr>
                        <tr>
                            <td>Type</td>
                            <td>SMS</td>
                            <td>Email</td>
                            <td>Telephone</td>
                            <td>Max. Idle Time</td>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>In Checkpoints</td>
                            <td><input type="checkbox" id="safcsms" name="safcsms" <?php if (isset($stoppage_user) && isset($stoppage_user->safcsms) && $stoppage_user->safcsms == 1) echo ("checked"); ?>></td>
                            <td><input type="checkbox" id="safcemail" name="safcemail" <?php if (isset($stoppage_user) && isset($stoppage_user->safcemail) && $stoppage_user->safcemail == 1) echo ("checked"); ?>></td>
                            <td><input type="checkbox" id="safctelephone" name="safctelephone" <?php if (isset($stoppage_user) && isset($stoppage_user->safctelephone) && $stoppage_user->safctelephone == 1) echo ("checked"); ?>></td>
                            <td><input type="checkbox" id="safcmobile" name="safcmobile" <?php if (isset($stoppage_user) && isset($stoppage_user->safctelephone) && $stoppage_user->safcmobile == 1) echo ("checked"); ?>></td>
                            <td><input type="text" size="4" maxlength="4" id="safcmin" name="safcmin" value="<?php
                               if (isset($stoppage_user) && isset($stoppage_user->safcmin)) {
                                   echo ($stoppage_user->safcmin);
                               } else {
                                   echo ("0");
                               }
                               ?>"> mins
                            </td>
                        </tr>
                        <tr>
                            <td>In Transit</td>
                            <td><input type="checkbox" id="saftsms" name="saftsms" <?php if (isset($stoppage_user) && isset($stoppage_user->saftsms) && $stoppage_user->saftsms == 1) echo ("checked"); ?>></td>
                            <td><input type="checkbox" id="saftemail" name="saftemail" <?php if (isset($stoppage_user) && isset($stoppage_user->saftemail) && $stoppage_user->saftemail == 1) echo ("checked"); ?>></td>
                            <td><input type="checkbox" id="safttelephone" name="safttelephone" <?php if (isset($stoppage_user) && isset($stoppage_user->safttelephone) && $stoppage_user->safttelephone == 1) echo ("checked"); ?>></td>
                            <td><input type="checkbox" id="saftmobile" name="saftmobile" <?php if (isset($stoppage_user) && isset($stoppage_user->safttelephone) && $stoppage_user->saftmobile == 1) echo ("checked"); ?>></td>
                            <td><input type="text" size="4" maxlength="4" id="saftmin" name="saftmin" value="<?php
                               if (isset($stoppage_user) && isset($stoppage_user->saftmin)) {
                                   echo ($stoppage_user->saftmin);
                               } else {
                                   echo ("0");
                               }
                               ?>"> mins
                            </td>
                        </tr>
                        <tr>
                            <td colspan="100%"><input type="button" name="samodify" id="samodify" class="btn  btn-primary" value="Modify" onclick="dosave_stoppage_alerts(<?php echo $get_uid; ?>);" >
                            </td>
                        </tr>
                    </tbody>
                </form>
            </table>
            <table id="floatingpanel">
                <form id="Cronalerts" name="Cronalerts" method="POST" >
                    <thead>
                        <tr>
                            <th colspan="3" id="formheader">Standard Reports</th>
                        </tr>
                        <tr>
                            <td id="saved_t" style="display: none" colspan="4">Changes Saved</td>
                            <td id="error_t" style="display: none" colspan="4">Error</td>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Type</td>
                            <td>PDF(Password Protected)</td>
                            <td>CSV</td>
                        </tr>
                        <tr>
                            <td>Standard Reports</td>
                            <td><input type="checkbox" id="dailyemail" name="dailyemail" <?php if ($user->dailyemail == 1) echo ("checked"); ?>></td>
                            <td><input type="checkbox" id="dailyemail_csv" name="dailyemail_csv" <?php if ($user->dailyemail_csv == 1) echo ("checked"); ?>></td>
                        </tr>
                        <tr>
                            <td colspan="100%"><input type="button" name="THistModify" class="btn  btn-primary" value="Modify" onclick="Save_Cron_Emails(<?php echo $get_uid; ?>);" >
                            </td>
                        </tr>
                    </tbody>
                </form>
            </table>
            <table id="floatingpanel">
                <form id="vehmovalerts" name="vehmovalerts" method="POST" >
                    <thead>
                        <tr>
                            <th colspan="3" id="formheader"><?php echo $veh; ?> Movement Alert</th>
                        </tr>
                        <tr>
                            <td id="saved_vma" style="display: none" colspan="4">Changes Saved</td>
                            <td id="error_vma" style="display: none" colspan="4">Error</td>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Type</td>
                            <td>&nbsp;</td>
                        </tr>
                        <tr>
                            <td><?php echo $veh; ?> Movement Alert</td>
                            <td><input type="checkbox" id="vehmovalert" name="vehmovalert" <?php if ($user->vehicle_movement_alert == 1) echo ("checked"); ?>></td>
                        </tr>
                        <tr>
                            <td colspan="100%"><input type="button" name="THistModify" class="btn  btn-primary" value="Modify" onclick="Save_Veh_movement_status(<?php echo $get_uid; ?>);" >
                            </td>
                        </tr>
                    </tbody>
                </form>
            </table>
            <br style='clear:both'>
            <br style='clear:both'>
            <table id="floatingpanel">
                <form id="CronalertsTime" name="CronalertsTime" method="POST" >
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
                            <td><input id="STime" name="SDate" type="text" data-date="<?php echo(date("H:i", strtotime($user->start_alert_time))); ?>"/></td>
                            <td><input id="ETime" name="EDate" type="text" data-date2="<?php echo(date("H:i", strtotime($user->stop_alert_time))); ?>"/></td>
                        </tr>
                        <tr>
                            <td colspan="100%"><input type="button" name="TimebaseModify" class="btn  btn-primary" value="Modify" onclick="Save_Alert_Timebased(<?php echo $get_uid; ?>);" >
                            </td>
                        </tr>
                    </tbody>
                </form>
            </table>
            <table>
                <form id="Stopalerts" name="Stopalerts" method="POST" >
                    <thead>
                        <tr>
                            <th colspan="7" id="formheader">Fuel Alerts</th>
                        </tr>
                        <tr>
                            <td id="saved_s1" style="display: none" colspan="4">Changes Saved</td>
                            <td id="error_s1" style="display: none" colspan="4">Error</td>
                        </tr>
                        <tr>
                            <td>Type</td>
                            <td>SMS</td>
                            <td>Email</td>
                            <td>Telephone</td>
                            <td>Mobile Notifications</td>
                            <td colspan="2">Percentage(%)</td>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Fuel Alert</td>
                            <td><input type="checkbox" id="fuelsms" name="fuelsms" <?php if ($user->fuel_alert_sms == 1) echo ("checked"); ?>></td>
                            <td><input type="checkbox" id="fuelemail" name="fuelemail" <?php if ($user->fuel_alert_email == 1) echo ("checked"); ?>></td>
                            <td><input type="checkbox" id="fueltelephone" name="fueltelephone" <?php if ($user->fuel_alert_telephone == 1) echo ("checked"); ?>></td>
                            <td><input type="checkbox" id="fuelmobile" name="fuelmobile" <?php if ($user->fuel_alert_mobile == 1) echo ("checked"); ?>></td>
                            <td><input type="range" id="filler" min="0" max="100" value="<?php echo $user->fuel_alert_percentage; ?>" step="5" onchange="showValue(this.value)"/>
                            </td>
                            <td><input type="text" name="alert_per" id="range" maxlength="2" size="2" readonly="" value="<?php echo $user->fuel_alert_percentage; ?>"/>% </td>
                        </tr>
                        <tr>
                            <td colspan="100%"><input type="button" name="fuelmodify" id="fuelmodify" class="btn  btn-primary" value="Modify" onclick="dosave_fuel_alerts(<?php echo $get_uid; ?>);" ></td>
                        </tr>
                    </tbody>
                </form>
            </table>
              <?php } ?>
    </div>
    <?php if (!empty($checkpointopt)) { ?>
        <br style='clear:both'>
        <br style='clear:both'>
        <?php if ($user->use_warehouse != 1 || ($user->use_warehouse == 1 && $_SESSION['switch_to'] != 3)) { ?>
            <div class='container'>
                <form name="exceptionAdd" method="POST" id="exceptionAdd" onsubmit="addExceptionAlert(<?php echo $get_uid; ?>);
                                    return false;" style="margin:0 0 0px">
                    <table class="table table-condensed">
                        <thead>
                            <tr>
                                <th>SMS</th>
                                <th>Email</th>
                                <th>Telephone</th>
                                <th>Mobile Notifications</th>
                                <th><?php echo $vehs; ?></th>
                                <th>Start Checkpoint</th>
                                <th>End Checkpoint</th>
                                <th>Report Type</th>
                            </tr>
                            <tr><th colspan="100%" id="formheader">Trip Exception Alerts</th></tr>
                        </thead>
                        <tbody>
                            <tr><td colspan="100%"><span id="excepResult" ></span></td></tr>
                            <tr>
                                <td colspan="100%">
                                    <a href='javascript:void(0);' data-toggle="modal" data-target="#tripModal" onclick='fill_exception_data(<?php echo $get_uid; ?>);' >Click here to check existing Trip Exception Alerts</a>
                                </td>
                            </tr>
                            <tr>
                                <td colspan='100%'><div id='vehicle_list_route'></div></td>
                                <td style="display: none;">
                                    <select id="vehicleroute" name="vehicleroute" onChange="VehicleForRoute()" style="display: none;"><?php echo $veh_dropdown; ?></select>
                                </td>
                            </tr>
                            <tr>
                                <td><input type="checkbox" id="exceptionSms" name="exceptionSms[]" ></td>
                                <td><input type="checkbox" id="exceptionEmail" name="exceptionEmail[]" ></td>
                                <td><input type="checkbox" id="exceptionTelephone" name="exceptionTelephone[]" ></td>
                                <td><input type="checkbox" id="exceptionMobile" name="exceptionMobile[]" ></td>
                                <td>
                                    <input  type="text" name="vehicleno" id="vehicleno" size="17" value="" autocomplete="off" placeholder="Enter <?php echo $veh; ?> No" >
                                    <input type="hidden" name="vehicleid" id="vehicleid" size="20" value=""/>
                                    <div id="display" class="listvehicle"></div><br/>
                                    <input type="button" class="g-button g-button-submit" style='float:right;' onclick="addallvehicleForRoute()" value="Add All">
                                </td>
                                <td><select id="checkpoint_start" name="checkpoint_start[]" style="width: 150px;"  required><?php echo $checkpointopt; ?></select></td>
                                <td><select id="checkpoint_end" name="checkpoint_end[]" style="width: 150px;"  required><?php echo $checkpointopt; ?></select></td>
                                <td>
                                    <select id="report_type" name="report_type[]" onchange='generate_report_type(this.value);' required >
                                        <option value="distance">Distance</option>
                                        <option value="avg_speed">Avg Speed</option>
                                        <option value="idle_time">Idle Time</option>
                                        <option value="genset_avg">Genset Average</option>
                                    </select>
                                    <select name='condition[]'  required>
                                        <option value='gt'>></option>
                                        <option value='lt_eq'><=</option>
                                    </select>
                                    <input type='text' class='input-mini' name='report_type_input[]' required/> <span id='reportUnit'>KM</span>
                                    <input type='hidden' name='uid' value='<?php echo $get_uid; ?>'/>
                                    <input type="submit"  name="exceptionAdd" class="btn  btn-primary" value="Add"  >
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </form>
            </div>
            <?php
        }
    }
}
?>
<!-- vehicle modal starts-->
<div class="modal hide" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" >
    <div class="modal-dialog" >
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span>
                    <span class="sr-only"></span>
                </button>
                <h4 class="modal-title" id="exampleModalLabel"><?php echo $vehs; ?><span id="poptitle"></span></h4>
            </div>
            <div class="modal-body" style="min-height: 200px; max-height: 400px; width:500px;">
                <form id="popTableForm">
                    <table class="table table-condensed" id="popTable">
                        <thead>
                            <tr>
                                <th>Start Time</th>
                                <th>End Time</th>
                                <th><?php echo $veh; ?></th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td colspan='100%'><span id='messageSpan'></span></td>
                            </tr>
                            <tr>
                                <td><input type="text" class='input-mini' name='popStartTimeI' id='popStartTime' data-date="<?php echo $start_def; ?>" value="<?php echo $start_def; ?>" /></td>
                                <td><input type="text" class='input-mini' name='popEndTimeI' id='popEndTime' data-date="<?php echo $end_def; ?>" /></td>
                                <td>
                                    <input  type="text" id="vehiclenoA" size="20" value="" autocomplete="off" placeholder="Enter <?php echo $veh; ?> No" >
                                    <input type="hidden" name="vehicleidI" id="vehicleidA" size="20" value=""/>
                                    <div id="displayA" class="listvehicle"></div>
                                </td>
                                <td><button type="button" class="btn btn-primary" onclick="add_row();">Add</button></td>
                            </tr>
                        </tbody>
                    </table>
                    <input type='hidden' name='popAlertType' value='' id='popAlertType'/>
                </form>
            </div>
            <div class="modal-footer">
                <input type='hidden' id='radioButtonId' value='' />
                <button type="button" class="btn btn-default" data-dismiss="modal" id='popClose'>Close</button>
                <button type="button" class="btn btn-default" onclick='clear_all();' >Clear All</button>
                <button type="button" class="btn btn-primary" onclick='add_specific_vehicles();'>Update <?php echo $vehs; ?></button>
            </div>
        </div>
    </div>
</div>
<!-- vehicle modal ends -->
<!-- trip exception modal starts-->
<div class="modal hide" id="tripModal" tabindex="-1" role="dialog" aria-labelledby="tripModalLabel" aria-hidden="true"  style="width:1200px;left:30%;">
    <div class="modal-dialog" >
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only"></span></button>
                <h4 class="modal-title" >Trip Exception Alerts</h4>
            </div>
            <div class="modal-body" style="min-height: 300px; max-height: 450px;">
                <table class="table table-condensed"  id="ExceptionpopTable">
                    <thead>
                        <tr>
                            <th>SMS</th>
                            <th>Email</th>
                            <th>Telephone</th>
                            <th>Select <?php echo $vehs; ?></th>
                            <th>Start Checkpoint</th>
                            <th>End Checkpoint</th>
                            <th colspan='4'>Report Type</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr><td colspan='100%'><span id='messageSpanExceptionPop'></span></td></tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<!-- trip exception modal ends -->
<?php
if ($user->temp_sensors > 0) {
    include_once 'panels/tempRangeModal.php';
}
?>
<!-- Checkpoint Exception Modal -->
<div class="modal hide" id="chkptExModal" tabindex="-1" role="dialog"   style="width:650px;">
    <div class="modal-dialog" >
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only"></span></button>
                <h4 class="modal-title" >Checkpoint Exceptions Alerts</h4>
            </div>
            <div class="modal-body" style="height: 350px; overflow: scroll;" >
<?php
if (isset($exceptions)) {
    ?>
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Sr. No.</th>
                                <th>Exception</th>
                                <th>Checkpoint</th>
                                <th><?php echo $veh; ?></th>
                                <th>StartTime</th>
                                <th>EndTime</th>
                                <th>Exception Type</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if (isset($exceptions) && !empty($exceptions)) {
                                $i = 1;
                                foreach ($exceptions as $exception) {
                                    $checked = '';
                                    if (isset($chkException)) {
                                        foreach ($chkException as $ex) {
                                            if ($exception->exceptionId == $ex->chkExId) {
                                                $checked = "Checked";
                                            }
                                        }
                                    }
                                    $tableRow = "<tr>";
                                    $tableRow .= "<td>" . $i++ . "</td>";
                                    $tableRow .= "<td>" . $exception->exceptionName . "</td>";
                                    $tableRow .= "<td>" . implode(', ', $exception->checkpointList) . "</td>";
                                    $tableRow .= "<td>" . implode(', ', $exception->vehicleList) . "</td>";
                                    $tableRow .= "<td>" . $exception->startTime . "</td>";
                                    $tableRow .= "<td>" . $exception->endTime . "</td>";
                                    $tableRow .= "<td>" . $exception->exceptionTypeName . "</td>";
                                    $tableRow .= "<td><input type='checkbox' class='chkexception' name='exceptionId" . $exception->exceptionId . "' id='exceptionId" . $exception->exceptionId . "' value='" . $exception->exceptionId . "' $checked/></td>";
                                    $tableRow .= "</tr>";
                                    echo $tableRow;
                                }
                            } else {
                                echo "<tr><td colspan='8'>No Data</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
    <?php
}
?>
            </div>
            <div class="modal-footer">
                <button name="saveChkEx" id="saveChkEx" onclick="saveChkException();" class="btn btn-success">Save </button>
                <button class="btn btn-danger" data-dismiss="modal" >Cancel</button>
            </div>
        </div>
    </div>
    <script type='text/javascript' src='../../scripts/exception.js'></script>
