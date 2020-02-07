<?php
include_once '../../lib/comman_function/reports_func.php';
$startTime = "00:00";
$endTime = "23:59";
/* Check For Maintenanace Session */
if ($_SESSION['switch_to'] == '1') {
    $mid = 2;
} else {
    $mid = 1;
}
if (isset($_SESSION["switch_to"]) && $_SESSION["switch_to"] == 3) {
    if (isset($_SESSION["Warehouse"]) && !empty($_SESSION["Warehouse"])) {
        $veh = $_SESSION["Warehouse"];
        $vehs = $_SESSION["Warehouse"] . "s";
    } else {
        $veh = "Warehouse";
        $vehs = "Warehouses";
    }
} else {
    $veh = "Vehicle";
    $vehs = "Vehicles";
}
$vehicle_option = "";
$user = getuser($_SESSION["userid"]);

echo "<script src='" . $_SESSION['subdir'] . "/scripts/jquery-1.12.4.min.js'></script>";
echo "<script src = '" . $_SESSION['subdir'] . "/scripts/jstree-master/dist/jstree.min.js' type='text/javascript'></script>";

$is_disabled = ($user->use_advanced_alert == 0) ? 'disabled="disabled"' : '';
$checkpointopt  = get_checkpoints();
$customname     = getcustombyid(1);
$vehicle_option = get_vehicles_option();
/* GET Hierarchy Roles */
$objRole                = new Hierarchy();
$objRole->roleid        = '';
$objRole->parentroleid  = '';
$objRole->moduleid      = $mid;
$objRole->customerno    = $_SESSION['customerno'];
$roles                  = getRolesByCustomer($objRole);

?>
<style>
    .table td {
        text-align: center;
    }

    .mainmenu{
        float:left;
        font-weight: bold;
        height: 25px;
        cursor: pointer;
    }
    .mainmenu-child{
        float:left;
        font-weight: bold;
        margin: 0px 0px 0px 100px;
        height: 25px;
        cursor: pointer;
    }
    .menuleft{
        margin: 0px 0px 0px 100px;
        float:left;
        height: 25px;
        cursor: pointer;
    }

    .mainmenu-subchild{
        margin: 0px 0px 0px 200px;
        float:left;
        height: 25px;
        cursor: pointer;
    }

    .checkbox-inline{
        float:left;
    }

    .menucheckbox {
        width: 13px;
        height: 13px;
        margin:0;
        vertical-align: bottom;
        position: relative;
        top: -5px;
        *overflow: hidden;
    }
    .add{ cursor: pointer }
    .edit{ cursor: pointer }
    .delete{ cursor: pointer }


</style>
<div id="container-fluid">
    <form method="POST" id="adduser" class="form-horizontal well "  style="width:90%;">
        <?php include 'panels/adduser.php'; ?>
        <fieldset>
            <input type="hidden" name="customerno" id="customerno" value="<?php echo $_SESSION['customerno']; ?>">
			 <div class="control-group">
                <div class="input-prepend">
                    <span class="add-on">Name <span class="mandatory">*</span></span> <input type="text" name="name" id="nameid" placeholder="Name" autofocus>
                    <input type="hidden" id="heirid" name="heirid" value="<?php echo ($_SESSION["heirarchy_id"]); ?>">
                </div>
                <div class="input-prepend ">
                 <?php
                    if($customerno == 64){
                ?>
                    <span class="add-on">Username <span class="mandatory">*</span></span> <input type="text" name="username" id="username" placeholder="Enter your username">
                    <?php }
                    else{
                        ?>
                    <span class="add-on">Username <span class="mandatory">*</span></span> <input type="text" name="username" id="username" placeholder="Émail ID is your username" readonly="">
                    <?php
                        } ?>
                </div>
            </div>
        </fieldset>
        <fieldset>
            <div class="control-group">
                <div class="input-prepend ">
                <?php
                    if($customerno == 64){
                ?>
                    <span class="add-on">Email<span class="mandatory">*</span> </span><input type="email" name="email" id="email1" placeholder="Email">
                    <?php }
                    else{
                        ?>
    <span class="add-on">Email<span class="mandatory">*</span> </span><input type="email" name="email" onKeyUp="copyText()" id="email1" placeholder="Email">
                        <?php
                    }
                    ?>
                </div>
                <div class="input-prepend ">
                    <span class="add-on">Password <span class="mandatory">*</span></span>  <input type="password" name="password" id="password" placeholder="Password">
                </div>
            </div>
        </fieldset>
        <fieldset>
            <div class="control-group">
                <div class="input-prepend ">
                    <span class="add-on">Phone No <span class="mandatory">*</span></span><span class="add-on">+91</span> <input type="text" name="phoneno" id="phoneno" placeholder="Phone No" />
                </div>
                <div class="input-prepend ">
                    <span class="add-on">Role </span>
                    <?php
                    if ($_SESSION['use_maintenance'] == '1' && $_SESSION['use_hierarchy'] == '1' && $_SESSION['switch_to'] == '1') {
                        echo '<select id="role" name="role" onchange="getParentUser()">';
                        echo '<option id="SelectUser" value="0" rel="0">Select Role</option>';
                        if (isset($roles) && !empty($roles)) {
                            foreach ($roles as $role) {
                                $roleid = $role['id'];
                                $rolename = $role['role'];
                                echo "<option id='" . $roleid . "' value='" . $rolename . "' rel='" . $roleid . "'>" . $rolename . "</option>";
                            }
                        }
                    }
                  //else if($_SESSION['customerno'] == constants::MAHINDRA_CUSTOMERNO){
                      else if($_SESSION['customerno'] == 64 || $_SESSION['customerno'] == 756){
                     echo '<select id="role" name="role" onchange="getParentUser()">';
                      echo '<option id="SelectUser" value="0" rel="0">Select Role</option>';
                        if (isset($roles) && !empty($roles)) {
                            foreach ($roles as $role) {
                                $roleid = $role['id'];
                                $rolename = $role['role'];
                                echo "<option id='" . $roleid . "' value='" . $rolename . "' rel='" . $roleid . "'>" . $rolename . "</option>";
                            }
                        }
                      /* echo '<option id="Head Office" value="Head Office" rel="33">Head Office</option>';
                      echo '<option id="Zone Manager" value="Zone Manager" rel="35">Zone Manager</option> ';
                           echo '<option id="Reginal Manager" value="Reginal Manager" rel="36">Reginal Manager</option>';
                           echo '<option id="Branch Manager" value="Branch Manager" rel="37">Branch Manager</option>';
                        echo "</select>";*/
                  }
                     else {
                     echo '<select id="role" name="role">';
                        echo '<option id="Administrator" value="Administrator" rel="5">Administrator</option>';
                        if ($_SESSION['switch_to'] != '6') {
                            echo '<option id="Tracker" value="Tracker" rel="7">Tracker</option> ';
                            echo '<option id="Viewer" value="Viewer" rel="9">Viewer</option>';
                            echo '<option id="Custom" value="Custom" rel="43">Custom</option>';
                        }
                        if ($switch_to == '8' && $_SESSION['use_sales']) {
                            echo "<option id='sales_manager' value='sales_manager' rel='12'>Sales Manager</option>";
                        }
                        if (isset($_SESSION['use_routing'])) {
                            echo "<option id='delivery_boy' value='delivery_boy' rel='11'>Delivery Boy</option>";
                        }
                        if ($switch_to == '6' && $_SESSION['use_secondary_sales']) {
                            echo "<option id='sales_representative' value='sales_representative' rel='14'>sales Representative</option>";
                            echo "<option id='ASM' value='ASM' rel='13'>ASM</option>";
                            echo "<option id='Supervisor' value='Supervisor' rel='40'>Supervisor</option>";
                            echo "<option id='Distributor' value='Distributor' rel='41'>Distributor</option>";
                        }
                        if ($_SESSION['use_maintenance'] == '1' && $_SESSION["use_hierarchy"] == '0') {
                            echo '<option id="Data Entry" value="Data Entry" rel="10">Data Entry</option>';
                        }
                        echo "</select>";
                    }
                    echo "</select>";
                    ?>
                </div>
                <?php
                if (isset($_SESSION['use_routing']) && $_SESSION['use_routing'] == 1) {
                    ?>  <div class="input-prepend" id='deliveryBoyVN' style='display:none;'>
                        <span class="add-on"><?php echo $veh; ?> No.</span>
                        <?php echo $vehicle_option; ?>
                    </div>
                <?php } ?>
            </div>
        </fieldset>
        <?php
        if ($_SESSION['use_maintenance'] == '1' && $_SESSION['use_hierarchy'] == '1' && $_SESSION['switch_to'] == '1') {
            ?>
            <fieldset id="ParentRole" style='display: none;'>
                <div class="control-group">
                    <div class="input-prepend">
                        <span class="add-on">Parent User</span>
                        <select id="parentuser" name="parentuser" onchange="getParentGroups()">
                        </select>
                    </div>
                </div>
                <div class="control-group">
                    <div class="input-prepend ">
                        <span><input type="checkbox" name="chk_highuser" id="chk_highuser" onclick="getHigherUser();"/>&nbsp;Select Higher Hierarchy User</span>
                        <br/>
                        <div id ="div_higheruser" style="display:none;">
                            <span class="add-on">Higher User</span>
                            <select id="higheruser" name="higheruser" onchange="getParentGroups()">
                            </select>
                        </div>
                    </div>
                </div>
            </fieldset>
            <?php
        }
        else if($_SESSION['customerno'] == "64" || $_SESSION['customerno'] == "756"){
             ?>
            <fieldset id="ParentRole" style='display: none;'>
                <div class="control-group">
                    <div class="input-prepend">
                        <span class="add-on">Parent User</span>
                        <select id="parentuser" name="parentuser" onchange="getParentGroups()">
                        </select>
                    </div>
                </div>
                <div class="control-group">
                    <div class="input-prepend ">
                        <span><input type="checkbox" name="chk_highuser" id="chk_highuser" onclick="getHigherUser();"/>&nbsp;Select Higher Hierarchy User</span>
                        <br/>
                        <div id ="div_higheruser" style="display:none;">
                            <span class="add-on">Higher User</span>
                            <select id="higheruser" name="higheruser" onchange="getParentGroups()">
                            </select>
                        </div>
                    </div>
                </div>
            </fieldset>
            <?php
        }
        ?>
        <fieldlist>
            <?php
            if ($_SESSION['use_secondary_sales'] == 1) {
                $asms = getallasm();
                $srs = getallsr();
                $supervisors = getallsupervisor();
                ?>
                <div class="input-prepend " id="salerep" style="display: none;">
                    <span class="add-on">Sales Representative <span class="mandatory">*</span></span>
                    <select id="srid" name="srid" onchange="getasm()">
                        <option value=''>Select Sales Representative</option>
                        <?php
                        if (isset($srs)) {
                            foreach ($srs as $sr) {
                                echo "<option value='$sr->userid'>$sr->realname</option>";
                            }
                        }
                        ?>
                    </select>
                </div>
                <div class="input-prepend " id="supervisor" style="display: none;">
                    <span class="add-on">Supervisor <span class="mandatory">*</span></span>
                    <select id="supid" name="supid" onchange="getasm()">
                        <option value=''>Select Supervisor</option>
                        <?php
                        if (isset($asms)) {
                            foreach ($supervisors as $sup) {
                                echo "<option value='$sup->userid'>$sup->realname</option>";
                            }
                        }
                        ?>
                    </select>
                </div>
                <div class="input-prepend " id="asm" style="display: none;">
                    <span class="add-on">ASM <span class="mandatory">*</span></span>
                    <select id="asmid" name="asmid" onchange="getasm()">
                        <option value=''>Select ASM</option>
                        <?php
                        if (isset($asms)) {
                            foreach ($asms as $asm) {
                                echo "<option value='$asm->userid'>$asm->realname</option>";
                            }
                        }
                        ?>
                    </select>
                </div>
            <?php } ?>
        </fieldlist>
        <fieldset>
            <div class="control-group">
                <?php if ($_SESSION["realname"] == 'Elixir') {
                    ?>
                    <div class="input-prepend " id="nation" style="display: none;">
                        <span class="add-on"><?php echo ($_SESSION['nation']); ?> <span class="mandatory">*</span></span>
                        <select id="nationid" name="nationid" onchange="getstate()">
                            <option value=''>Select<?php echo ($_SESSION['nation']); ?></option>
                            <?php
                            $nations = getnations($_SESSION['userid']);
                            if (isset($nations)) {
                                foreach ($nations as $nation) {
                                    if ($city->nationid == $nation->nationid) {
                                        echo "<option value='$nation->nationid' selected='selected'>$nation->name</option>";
                                    } else {
                                        echo "<option value='$nation->nationid'>$nation->name</option>";
                                    }
                                }
                            }
                            ?>
                        </select>
                    </div>
                    <?php
                }
                if ($_SESSION["roleid"] == '1' || $_SESSION["realname"] == 'Elixir') {
                    ?>
                    <div class="input-prepend " id="state" style="display: none;">
                        <span class="add-on"><?php echo ($_SESSION['state']); ?> <span class="mandatory">*</span></span>
                        <select id="stateid" name="stateid" onchange="getdistrict()">
                            <option value=''>Select<?php echo ($_SESSION['state']); ?></option>
                            <?php
                            $states = getstates($_SESSION['userid']);
                            if (isset($states)) {
                                foreach ($states as $state) {
                                    if ($city->stateid == $state->stateid) {
                                        echo "<option value='$state->stateid' selected='selected'>$state->statename</option>";
                                    } else {
                                        echo "<option value='$state->stateid'>$state->statename</option>";
                                    }
                                }
                            }
                            ?>
                        </select>
                    </div>
                    <?php
                }
                if ($_SESSION["roleid"] == '1' || $_SESSION["roleid"] == '2' || $_SESSION["realname"] == 'Elixir') {
                    ?>
                    <div class="input-prepend " id="district" style="display: none;">
                        <span class="add-on"><?php echo ($_SESSION['district']); ?> <span class="mandatory">*</span></span>
                        <select id="districtid" name="districtid" onchange="getcity()">
                            <option value=''>Select<?php echo ($_SESSION['district']); ?></option>
                            <?php
                            $districts = getdistricts($_SESSION['userid']);
                            if (isset($districts)) {
                                foreach ($districts as $district) {
                                    if ($city->districtid == $district->districtid) {
                                        echo "<option value='$district->districtid' selected='selected'>$district->districtname</option>";
                                    } else {
                                        echo "<option value='$district->districtid'>$district->districtname</option>";
                                    }
                                }
                            }
                            ?>
                        </select>
                    </div>
                    <?php
                }
                if ($_SESSION["roleid"] == '1' || $_SESSION["roleid"] == '2' || $_SESSION["roleid"] == '3' || $_SESSION["realname"] == 'Elixir') {
                    ?>
                    <div class="input-prepend " id="city" style="display: none;">
                        <span class="add-on"><?php echo ($_SESSION['city']); ?> <span class="mandatory">*</span></span>
                        <select id="cityid" name="cityid" onchange="getbranch()">
                            <option value=''>Select<?php echo ($_SESSION['city']); ?></option>
                            <?php
                            $cities = getcities($_SESSION['userid']);
                            if (isset($cities)) {
                                foreach ($cities as $city) {
                                    echo "<option value='$city->cityid'>$city->cityname</option>";
                                }
                            }
                            ?>
                        </select>
                    </div>
                <?php } ?>
                <input type="hidden" name="is_maintenance" id="is_maintenance" value="<?php echo $_SESSION['use_maintenance'] ?>" />
                <input type="hidden" name="is_hierarchy" id="is_hierarchy" value="<?php echo $_SESSION['use_hierarchy'] ?>" />
                <input type="hidden" name="session_roleid" id="session_roleid" value="<?php echo $_SESSION['roleid'] ?>" />
            </div>
        </fieldset>
        <fieldset>
            <div class="control-group">
                <div class="input-prepend " id="group_div">
                    <?php if ($_SESSION['use_maintenance'] == '1' && $_SESSION['use_hierarchy'] == '1' && $_SESSION['switch_to'] == '1') { ?>

                        <span class="add-on"><?php echo ($_SESSION['group']); ?> <span class="mandatory">*</span></span>
                        <select id="group" name="group" onChange="addgrouptouser()">
                            <option value='' selected="">Select                                                                                                                       <?php echo ($_SESSION['group']); ?></option>
                        </select>

                        <div id="group_list"></div>
                    <?php } elseif ($_SESSION['switch_to'] != '9' && $_SESSION['switch_to'] != '6') {
                        ?>

                        <span class="add-on"><?php echo ($_SESSION['group']); ?> </span>
                        <select id="group" name="group" onChange="addgrouptouser()">
                            <option selected="">Select<?php echo ($_SESSION['group']); ?></option>
                            <option value='0'>All</option>
                            <?php
                            $groups = getgroups();
                            if (isset($groups)) {
                                foreach ($groups as $group) {
                                    echo "<option value='$group->groupid'>$group->groupname</option>";
                                }
                            }
                            ?>
                        </select>

                        <div id="group_list"></div>
                    </div>
                <?php } ?>
        </fieldset>
        <fieldset>
            <?php if ($_SESSION['use_maintenance'] == '1' && $_SESSION['use_hierarchy'] == '1' && $_SESSION['switch_to'] == '1') { ?>
                <div class="control-group" id="vehicle_for_user" style="display: none;">
                    <div class="input-prepend">
                        <span class="add-on"><?php echo $vehicles; ?> </span>
                        <select id="vehicleids" name="vehicleids" onChange="addVehicleByGroup()">
                        </select>
                    </div>
                    <div id="vehicle_list"></div>
                </div>
            <?php } elseif ($_SESSION['switch_to'] != '9' && $_SESSION['switch_to'] != '6') { ?>
                <div class="control-group" id="vehicle_for_user" style="display: none;">
                    <div class="input-prepend">
                        <span class="add-on"><?php echo $vehicles; ?> </span>
                        <select id="vehicleids" name="vehicleids" onChange="addVehicleByGroup()">
                        </select>
                    </div>
                    <div id="vehicle_list"></div>
                </div>
            <?php }
            else if($_SESSION['customerno'] == "64" || $_SESSION['customerno'] == "756"){

                ?>
                  <div class="control-group" id="vehicle_for_user" style="display: none;">
                    <div class="input-prepend">
                        <span class="add-on"><?php echo $vehicles; ?> </span>
                        <select id="vehicleids" name="vehicleids" onChange="addVehicleByGroup()">
                        </select>
                    </div>
                    <div id="vehicle_list"></div>
                </div>
            <?php } elseif ($_SESSION['switch_to'] != '9' && $_SESSION['switch_to'] != '6') { ?>
                <div class="control-group" id="vehicle_for_user" style="display: none;">
                    <div class="input-prepend">
                        <span class="add-on"><?php echo $vehicles; ?> </span>
                        <select id="vehicleids" name="vehicleids" onChange="addVehicleByGroup()">
                        </select>
                    </div>
                    <div id="vehicle_list"></div>
                </div>
              <?php  } ?>
        </fieldset>
        <?php if ($_SESSION['switch_to'] != '8' && $_SESSION['switch_to'] != '9' && $_SESSION['switch_to'] != '6' && $_SESSION['switch_to'] != '1') { ?>
            <div class="well form-inline">
                <span id="saved" style="display: none" colspan="6">Changes Saved</span>
                <span id="error" style="display: none" colspan="6">Error</span>
                <legend>Alerts</legend>
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
                            <th><?php echo $veh; ?></th>
                        </tr>
                        <tr>
                            <th colspan='100%'><?php echo $veh; ?> Alerts</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Temperature Sensor</td>
                            <td><input type='checkbox' name='tempsms' id='tempsms'/></td>
                            <td><input type='checkbox' name='tempemail' id='tempemail' /></td>
                            <td><input type='checkbox' name='temptelephone' id='temptelephone'  /></td>
                            <td><input type='checkbox' name='tempmobile' id='tempmobile'  /></td>
                            <td><input type="text" name='temp[stTime]' id='STimeTemp' class='input-mini'  data-date="<?php echo $startTime; ?>" /></td>
                            <td><input type="text" name='temp[edTime]' id='ETimeTemp' class='input-mini' data-date="<?php echo $endTime; ?>" /></td>
                            <td><input type="text" name='tempinterval' id='tempinterval' class='input-mini'  value='0' /></td>
                            <td>
                                All <?php echo $vehs; ?>: <input type="radio" name='temp[veh]'  value='all' checked/>
                            </td>
                        </tr>
                        <?php if (isset($_SESSION["switch_to"]) && $_SESSION["switch_to"] != 3) { ?>
                            <tr>
                                <td>Ignition</td>
                                <td><input type='checkbox' name='igsms' id='igsms'/></td>
                                <td><input type='checkbox' name='igemail' id='igemail' /></td>
                                <td><input type='checkbox' name='igtelephone' id='igtelephone'  /></td>
                                <td><input type='checkbox' name='igmobile' id='igmobile' /></td>
                                <td><input type="text" name='ignition[stTime]' id='STimeIg'  class='input-mini' data-date="<?php echo $startTime; ?>"/></td>
                                <td><input type="text" name='ignition[edTime]' id='ETimeIg' class='input-mini' data-date="<?php echo $endTime; ?>" /></td>
                                <td></td>
                                <td>
                                    All <?php echo $vehs; ?>: <input type="radio" name='ignition[veh]' value='all'  checked/>
                                </td>
                            </tr>
                            <tr>
                                <td>Over Speeding</td>
                                <td><input type='checkbox' name='speedsms' id='ospeedsms' /></td>
                                <td><input type='checkbox' name='speedemail' id='ospeedemail' /></td>
                                <td><input type='checkbox' name='speedtelephone' id='ospeedtelephone'  /></td>
                                <td><input type='checkbox' name='speedmobile' id='ospeedmobile'  /></td>
                                <td><input type="text" name='speed[stTime]' id='STimeOs' class='input-mini' data-date="<?php echo $startTime; ?>" /></td>
                                <td><input type="text" name='speed[edTime]' id='ETimeOs'class='input-mini' data-date="<?php echo $endTime; ?>" /></td>
                                <td>    </td>
                                <td>
                                    All <?php echo $vehs; ?>: <input type="radio" name='speed[veh]'  value='all' checked/>
                                </td>
                            </tr>
                        <?php } ?>
                        <tr>
                            <td><?php echo $customname; ?></td>
                            <td><input type='checkbox' name='acsms'  id='acsms'/></td>
                            <td><input type='checkbox' name='acemail'  id='acemail' /></td>
                            <td><input type='checkbox' name='actelephone'  id='actelephone'  /></td>
                            <td><input type='checkbox' name='acmobile'  id='acmobile'  /></td>
                            <td><input type="text" name='ac[stTime]' id='STimeGn' class='input-mini' data-date="<?php echo $startTime; ?>"  /></td>
                            <td><input type="text" name='ac[edTime]' id='ETimeGn' class='input-mini' data-date="<?php echo $endTime; ?>" /></td>
                            <td></td>
                            <td>
                                All <?php echo $vehs; ?>: <input type="radio" name='ac[veh]'  value='all'  checked/>
                            </td>
                        </tr>
                        <tr>
                            <td>Door Sensor</td>
                            <td><input type='checkbox' name='doorsms' id="doorsms"/></td>
                            <td><input type='checkbox' name='dooremail' id="dooremail"/></td>
                            <td><input type='checkbox' name='doortelephone' id="doortelephone"  /></td>
                            <td><input type='checkbox' name='doormobile' id="doormobile"  /></td>
                            <td><input type="text" name='door[stTime]' id='STimeDoor' class='input-mini' data-date="<?php echo $startTime; ?>" /></td>
                            <td><input type="text" name='door[edTime]' id='ETimeDoor' class='input-mini' data-date="<?php echo $endTime; ?>"  /></td>
                            <td></td>
                            <td>
                                All <?php echo $vehs; ?>: <input type="radio" name='door[veh]'  value='all' checked/>
                            </td>
                        </tr>
                        <?php if (isset($_SESSION["switch_to"]) && $_SESSION["switch_to"] != 3) { ?>
                            <tr>
                                <td>Checkpoint Exception</td>
                                <td><input type='checkbox' name='chkptExSms' id="chkptExSms"/></td>
                                <td><input type='checkbox' name='chkptExEmail' id="chkptExEmail"/></td>
                                <td><input type='checkbox' name='chkptExtelephone' id="chkptExtelephone"/></td>
                                <td><input type='checkbox' name='chkptExMobile' id="chkptExMobile"  /></td>
                                <td><input type="text" name='' id='' class='input-mini' readonly="" /></td>
                                <td><input type="text" name='' id='' class='input-mini' readonly="" /></td>
                                <td></td>
                                <td>
                                    <a href='javascript:void(0);' data-toggle="modal" data-target="#chkptExModal">Select Exception</a>
                                </td>
                            </tr>
                        <?php } ?>
                        <tr><th colspan='100%'>Device Alerts</th></tr>
                        <tr>
                            <td>Power Cut</td>
                            <td><input type='checkbox' name='powersms' id='powercsms' /></td>
                            <td><input type='checkbox' name='poweremail' id='powercemail' /></td>
                            <td><input type='checkbox' name='powertelephone' id='powerctelephone'  /></td>
                            <td><input type='checkbox' name='powermobile' id='powermobile'  /></td>
                            <td><input type="text" name='powerc[stTime]' id='STimePowerc' class='input-mini' data-date="<?php echo $startTime; ?>" /></td>
                            <td><input type="text" name='powerc[edTime]' id='ETimePowerc' class='input-mini' data-date="<?php echo $endTime; ?>"  /></td>
                            <td>
                                All <?php echo $vehs; ?>: <input type="radio" name='powerc[veh]'  value='all' checked/>
                            </td>
                        </tr>
                        <tr>
                            <td>Device Tamper</td>
                            <td><input type='checkbox' name='tampersms' id='tampersms' /></td>
                            <td><input type='checkbox' name='tamperemail' id='tamperemail' /></td>
                            <td><input type='checkbox' name='tampertelephone' id='tampertelephone'  /></td>
                            <td><input type='checkbox' name='tampermobile' id='tampermobile'  /></td>
                            <td><input type="text" name='tamper[stTime]' id='STimeTamper' class='input-mini' data-date="<?php echo $startTime; ?>" /></td>
                            <td><input type="text" name='tamper[edTime]' id='ETimeTamper' class='input-mini' data-date="<?php echo $endTime; ?>" /></td>
                            <td>
                                All <?php echo $vehs; ?>: <input type="radio" name='tamper[veh]'  value='all' checked/>
                            </td>
                        </tr>
                        <?php if (isset($_SESSION["switch_to"]) && $_SESSION["switch_to"] != 3) { ?>
                            <tr>
                                <th colspan="100%" >&nbsp;&nbsp;Advanced Alerts(To activate, contact an Elixir)&nbsp;&nbsp;</th>
                            </tr>
                            <tr>
                                <td>Harsh Break</td>
                                <td><input type='checkbox' name='harsh_break_sms' id='harsh_break_sms'<?php echo $is_disabled; ?>/></td>
                                <td><input type='checkbox' name='harsh_break_mail' id='harsh_break_mail'<?php echo $is_disabled; ?>/></td>
                                <td><input type='checkbox' name='harsh_break_telephone' id='harsh_break_telephone'<?php echo $is_disabled; ?>/></td>
                                <td><input type='checkbox' name='harsh_break_mobile' id='harsh_break_mobile'<?php echo $is_disabled; ?>/></td>
                                <td><input type="text" name='harsh_break[stTime]' id='STimeharsh_break' class='input-mini' data-date="<?php echo $startTime; ?>"<?php echo $is_disabled; ?>/></td>
                                <td><input type="text" name='harsh_break[edTime]' id='ETimeharsh_break' class='input-mini' data-date="<?php echo $endTime; ?>" /></td>
                                <td>
                                    All <?php echo $vehs; ?>: <input type="radio" name='harsh_break[veh]'  value='all' checked                                                                                                                                                                                                                             <?php echo $is_disabled; ?>/>
                                </td>
                            </tr>
                            <tr>
                                <td>Sudden Acceleration</td>
                                <td><input type='checkbox' name='high_acce_sms' id='high_acce_sms'<?php echo $is_disabled; ?>/></td>
                                <td><input type='checkbox' name='high_acce_mail' id='high_acce_mail'<?php echo $is_disabled; ?>/></td>
                                <td><input type='checkbox' name='high_acce_telephone' id='high_acce_telephone'<?php echo $is_disabled; ?>/></td>
                                <td><input type='checkbox' name='high_acce_mobile' id='high_acce_mobile'<?php echo $is_disabled; ?>/></td>
                                <td><input type="text" name='high_acce[stTime]' id='STimehigh_acce' class='input-mini' data-date="<?php echo $startTime; ?>"<?php echo $is_disabled; ?>/></td>
                                <td><input type="text" name='high_acce[edTime]' id='ETimehigh_acce' class='input-mini' data-date="<?php echo $endTime; ?>"<?php echo $is_disabled; ?>/></td>
                                <td>
                                    All <?php echo $vehs; ?>: <input type="radio" name='high_acce[veh]'  value='all' checked                                                                                                                                                                                                                         <?php echo $is_disabled; ?>/>
                                </td>
                            </tr>
                            <tr>
                                <td>Towing</td>
                                <td><input type='checkbox' name='towing_sms' id="towing_sms"<?php echo $is_disabled; ?>></td>
                                <td><input type='checkbox' name='towing_mail' id="towing_email"<?php echo $is_disabled; ?>></td>
                                <td><input type='checkbox' name='towing_telephone' id="towing_telephone"<?php echo $is_disabled; ?>></td>
                                <td><input type='checkbox' name='towing_mobile' id="towing_mobile"<?php echo $is_disabled; ?>></td>
                                <td><input type="text" name='towing[stTime]' id='STimetowing' class='input-mini' data-date="<?php echo $startTime; ?>"<?php echo $is_disabled; ?>/></td>
                                <td><input type="text" name='towing[edTime]' id='ETimetowing' class='input-mini' data-date="<?php echo $endTime; ?>"<?php echo $is_disabled; ?>/></td>
                                <td>
                                    All <?php echo $vehs; ?>: <input type="radio" name='towing[veh]'  value='all' checked                                                                                                                                                                                                                   <?php echo $is_disabled; ?>/>
                                </td>
                            </tr>
                            <?php if ($user->use_panic == 1) { ?>
                                <tr>
                                    <td>Panic</td>
                                    <td><input type='checkbox' name='panic_sms' id="panic_sms" /></td>
                                    <td><input type='checkbox' name='panic_email' id="panic_email" /></td>
                                    <td><input type='checkbox' name='panic_telephone' id="panic_telephone"  /></td>
                                    <td><input type='checkbox' name='panic_mobile' id="panic_mobile"  /></td>
                                    <td><input type="text" name='panic[stTime]' id='STimePanic' class='input-mini' data-date="<?php echo $startTime; ?>" /></td>
                                    <td><input type="text" name='panic[edTime]' id='ETimePanic' class='input-mini' data-date="<?php echo $endTime; ?>" /></td>
                                    <td>
                                        All <?php echo $vehs; ?>: <input type="radio" name='panic[veh]'  class='allRadio' value='all' checked/>
                                    </td>
                                </tr>
                            <?php }if ($user->use_immobiliser == 1) { ?>
                                <tr>
                                    <td>Immobilizer</td>
                                    <td><input type='checkbox' name='immob_sms' id="immob_sms" /></td>
                                    <td><input type='checkbox' name='immob_email' id="immob_email" /></td>
                                    <td><input type='checkbox' name='immob_telephone' id="immob_telephone"  /></td>
                                    <td><input type='checkbox' name='immob_mobile' id="immob_mobile"  /></td>
                                    <td><input type="text" name='immob[stTime]' id='STimeImmob' class='input-mini' data-date="<?php echo $startTime; ?>" /></td>
                                    <td><input type="text" name='immob[edTime]' id='ETimeImmob' class='input-mini' data-date="<?php echo $endTime; ?>" /></td>
                                    <td>
                                        All <?php echo $vehs; ?>: <input type="radio" name='immob[veh]'  class='allRadio' value='all' checked/>
                                    </td>
                                </tr>
                                <?php
                            }
                        }
                        ?>
                    </tbody>
                </table>
                <?php if (isset($_SESSION["switch_to"]) && $_SESSION["switch_to"] != 3) { ?>
                    <table id="floatingpanel">
                        <thead>
                            <tr>
                                <th colspan="5" id="formheader">Event Alerts</th>
                            </tr>
                            <tr>
                                <td>Type</td>
                                <td>SMS</td>
                                <td>Email</td>
                                <td>Telephone</td>
                                <td>Mobile Notification</td>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Checkpoint</td>
                                <td><input type="checkbox" id="chksms" name="chksms"  ></td>
                                <td><input type="checkbox" id="chkemail" name="chkemail" ></td>
                                <td><input type="checkbox" id="chktelephone" name="chktelephone"  ></td>
                                <td><input type="checkbox" id="chkmobile" name="chkmobile"  ></td>
                            </tr>
                            <tr>
                                <td>Fence Conflict</td>
                                <td><input type="checkbox" id="geosms" name="messsms" ></td>
                                <td><input type="checkbox" id="geoemail" name="messemail" ></td>
                                <td><input type="checkbox" id="geotelephone" name="messtelephone"  ></td>
                                <td><input type="checkbox" id="geomobile" name="messmobile"  ></td>
                            </tr>
                        </tbody>
                    </table>
                    <table id="floatingpanel">
                        <thead>
                            <tr>
                                <th colspan="6" id="formheader">Stoppage Alerts</th>
                            </tr>
                            <tr>
                                <td>Type</td>
                                <td>SMS</td>
                                <td>Email</td>
                                <td>Telephone</td>
                                <td>Mobile Notification</td>
                                <td>Max. Idle Time</td>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>In Checkpoints</td>
                                <td><input type="checkbox" id="safcsms" name="safcsms" ></td>
                                <td><input type="checkbox" id="safcemail" name="safcemail" ></td>
                                <td><input type="checkbox" id="safctelephone" name="safctelephone"  ></td>
                                <td><input type="checkbox" id="safcmobile" name="safcmobile"  ></td>
                                <td><input type="text" size="4" maxlength="4" id="safcmin" name="safcmin" value="0"> mins</td>
                            </tr>
                        </tbody>
                    </table>
                    <table id="floatingpanel">
                        <thead>
                            <tr><th colspan="3" id="formheader">Daily Email</th></tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Type</td>
                                <td>PDF(Password Protected)</td>
                                <td>CSV</td>
                            </tr>
                            <tr>
                                <td>Daily Email Reports</td>
                                <td><input type="checkbox" id="dailyemail" name="dailyemail" ></td>
                                <td><input type="checkbox" id="dailyemail_csv" name="dailyemail_csv" ></td>
                            </tr>
                        </tbody>
                    </table>
                    <br style='clear:both'>
                    <br style='clear:both'>
                    <table id='floatingpanel'>
                        <thead>
                            <tr><th colspan="3" id="formheader">Time Based Alert</th></tr>
                            <tr><td>Start Time</td><td>End Time</td></tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><input id="STime" name="SDate" type="text" data-date="<?php echo $startTime; ?>"/></td>
                                <td><input id="ETime" name="EDate" type="text" data-date2="<?php echo $endTime; ?>"/></td>
                            </tr>
                        </tbody>
                    </table>
                    <br style='clear:both'>
                <?php } ?>
            </div>

            <fieldset>
                <div class="well form-inline">
                    <table class="table table-condensed" style="width:50%;">
                        <thead>
                            <tr>
                                <th colspan="4">Reports By Email</th>
                            </tr>
                            <tr>
                                <th></th>
                                <th>Report</th>
                                <th>Report Time[HH]</th>
                                <th>Report Interval[MM]</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if (isset($reportsMaster) && !empty($reportsMaster)) {
                                foreach ($reportsMaster as $report) {
                                    $checked = '';
                                    $disable = 'disabled';
                                    if (isset($_SESSION["switch_to"]) && $_SESSION["switch_to"] == 3) {
                                        if ($report->is_warehouse != 1) {
                                            ?>
                                            <tr id="reports_<?php echo $report->reportId; ?>">
                                                <td><input class='reportCheck' type="checkbox" onclick="enableReportTime(<?php echo $report->reportId; ?>)" id="activated_<?php echo $report->reportId; ?>" name="activated_<?php echo $report->reportId; ?>"<?php echo $checked; ?>  /></td>
                                                <td>
                                                    <?php
                                                    $concatString = "";
                                                    if (isset($_SESSION['use_humidity']) && $_SESSION['use_humidity'] == 1 && $report->reportId == 5) {
                                                        $concatString = "Humidity & ";
                                                    }
                                                    echo $concatString . "" . $report->reportName;
                                                    ?>
                                                </td>
                                                <td >
                                                    <select class="reportOn"<?php echo $disable; ?> id="reportTime_<?php echo $report->reportId; ?>" name="reportTime_<?php echo $report->reportId; ?>" >
                                                        <option value='-1'>Select Time</option>
                                                        <?php
                                                        for ($i = 1; $i <= 23; $i++) {
                                                            $time = sprintf("%02d:00", $i);
                                                            echo "<option value='" . $i . "'>" . $time . "</option>";
                                                        }
                                                        ?>
                                                    </select>
                                                </td>
                                                <?php 
                                                if($report->reportId == 5){
                                                    ?>
                                                <td>
                                                        <select id="temprepinterval" <?php echo $disable; ?> name="temprepinterval">
                                                            <option value="0">Select Interval</option>
                                                            <option value="120">120</option>
                                                            <option value="60">60</option>
                                                            <option value="30">30</option>
                                                            <option value="15">15</option>
                                                            <option value="10">10</option>
                                                            <option value="5">5</option>
                                                            <option value="1" >1</option>
                                                        </select>
                                                </td>
                                                        <?php
                                                    
                                                    
                                                }
                                              else if($report->reportId == 19){ ?> 
                                        <td>
                                            <select id="vehrepinterval" <?php echo $disable; ?> name="vehrepinterval">
                                                        <option value="0">Inactive since(In days)</option>
                                                        <?php for($i=1;$i<=15;$i++){
                                                            ?>
                                                            <option value="<?php echo $i;?>"><?php echo $i;?></option>
                                                            <?php
                                                            }?>
                                            </select> 
                                        </td>
                                       <?php }else{
                                                    echo '<td></td>';
                                                }
                                                ?>
                                            </tr>
                                            <?php
                                        }
                                    } else {
                                        ?>
                                        <tr id="reports_<?php echo $report->reportId; ?>">
                                            <td><input class='reportCheck' type="checkbox" onclick="enableReportTime(<?php echo $report->reportId; ?>)" id="activated_<?php echo $report->reportId; ?>" name="activated_<?php echo $report->reportId; ?>"<?php echo $checked; ?>  /></td>
                                            <td>
                                                <?php
                                                $concatString = "";
                                                if (isset($_SESSION['use_humidity']) && $_SESSION['use_humidity'] == 1 && $report->reportId == 5) {
                                                    $concatString = "Humidity & ";
                                                }
                                                echo $concatString . "" . $report->reportName;
                                                ?>
                                            </td>
                                            <td >
                                                <select class="reportOn"<?php echo $disable; ?> id="reportTime_<?php echo $report->reportId; ?>" name="reportTime_<?php echo $report->reportId; ?>" >
                                                    <option value='-1'>Select Time</option>
                                                    <?php
                                                    for ($i = 1; $i <= 23; $i++) {
                                                        $time = sprintf("%02d:00", $i);
                                                        echo "<option value='" . $i . "'>" . $time . "</option>";
                                                    }
                                                    ?>
                                                </select>
                                            </td>
                                            <?php 
                                                if($report->reportId == 5){
                                                    ?>
                                            <td>
                                                        <select id="temprepinterval" <?php echo $disable; ?> name="temprepinterval">
                                                            <option value="0">Select Interval</option>
                                                            <option value="120">120</option>
                                                            <option value="60">60</option>
                                                            <option value="30">30</option>
                                                            <option value="15">15</option>
                                                            <option value="10">10</option>
                                                            <option value="5">5</option>
                                                            <option value="1" >1</option>
                                                        </select>
                                            </td>
                                                        <?php
                                                }
                                               else if($report->reportId == 19){ ?> 
                                        <td>
                                            <select id="vehrepinterval" <?php echo $disable; ?> name="vehrepinterval">
                                                        <option value="0">Inactive since(In days)</option>
                                                        <?php for($i=1;$i<=15;$i++){
                                                            ?>
                                                            <option value="<?php echo $i;?>"><?php echo $i;?></option>
                                                            <?php
                                                            }?>
                                            </select> 
                                        </td>
                                       <?php }
                                       else{
                                                    echo '<td></td>';
                                                }
                                                ?>
                                        </tr>
                                        <?php
                                    }
                                }
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </fieldset>
        <?php } ?>
        <?php if ($_SESSION['switch_to'] == 1) {
            ?>
            <style>
                .menu ul{color:#FFF;} /* Main container, includes the background of the static portion of the menu */
                .menu ul li{color:#FFF;} /* This is the style for the main menu items */
                .menu ul ul{color:#FFF;} /* This is the container for the first submenu */
                .menu ul ul li{color:#FFF;} /* This is the style for the submenus */
            </style>

            <?php
            //$getnewmenuformat = display_children(0, 1);
           // $allmenulist = getmenus();
            ?>
<!--            <table class="table table-condensed" style="width:70%;">
                <tr><th colspan="100%"><h4>Select Menus</h4></th></tr>
                <tr><td colspan="100%">&nbsp;</td></tr>
                <tr><td><h5>Menus</h5></td><td colspan="3"><h5>Permissions</h5></td></tr>
                <tr><td>&nbsp;</td><td><i class="icon-plus" alt="Add" title="Add" style="cursor: pointer;"></i></td><td><i class="icon-pencil" alt="Edit" title="Edit" style="cursor: pointer;"></i></td><td><i class="icon-trash" alt="Delete" title="Delete" style="cursor: pointer;"></i></td></tr>
                <?php
                for ($i = 0; $i < count($allmenulist['children']); $i++) {
                    $tri = 0;
                    $mainmenuid = $allmenulist['children'][$i]['id'];
                    if (empty($allmenulist['children'][$i]['children'])) {
                        echo"<tr>";
                        ?>
                        <td colspan="100%;">
                            <?php echo "<label class='mainmenu'><input type='checkbox' class='menucheckbox'  id='mainmenu_" . $i . "'  name='menu'  value='" . $mainmenuid . "'>&nbsp;&nbsp;" . $allmenulist['children'][$i]['text'] . "</label>"; ?>
                        </td>
                        <?php
                        echo"</tr>";
                    } else {
                        ?>
                        <tr>
                            <td colspan="100%;">
                                <?php
                                $children = $allmenulist['children'][$i]['children'];
                                $childrencount = count($children);
                                echo"<label class='mainmenu' for='mainmenu_" . $i . "'>"
                                . " <input type='hidden' id='mainmenu_count" . $i . "' value='" . $childrencount . "'>"
                                . " <input type='checkbox' class='menucheckbox childrenmenu' id='mainmenu_" . $i . "' name='menu'  onclick='mainmenu_list(" . $i . ");'  value='" . $mainmenuid . "'> &nbsp;&nbsp;" . $allmenulist['children'][$i]['text'] . "</label>";
                                ?>
                            </td>
                            <?php
                            if (isset($children) && !empty($children)) {
                                echo"</tr><tr>";
                                $subchild = "";
                                for ($j = 0; $j < count($children); $j++) {
                                    $pagech1 = $children[$j]['page'];
                                    $textch1 = $children[$j]['text'];
                                    $idch1 = $children[$j]['id'];
                                    $subchild = $children[$j]['children'];
                                    if (isset($subchild) && !empty($subchild)) {
                                        echo"<tr><td><label class='mainmenu-child' for='childmenu_" . $i . "-" . $j . "' ><input type='hidden' name='subchildcount' id='subchildcount" . $j . "'  value='" . count($subchild) . "' >"
                                        . "<input type='checkbox' class='menucheckbox subchildrenmenu ' id='childmenu_" . $i . "-" . $j . "' onclick='subchildrenmenu_list();'  name='menu'  value='" . $idch1 . "'>&nbsp;&nbsp; " . $textch1 . "</label></td>
                                            </tr>";
                                        for ($kk = 0; $kk < count($subchild); $kk++) {
                                            $textsubchild = $subchild[$kk]['text'];
                                            $idsubchild = $subchild[$kk]['id'];
                                            $pagesubchild = $subchild[$kk]['page'];
                                            echo"<tr><td><label class='mainmenu-subchild' onclick='checkparentselect(" . $i . "," . $j . "," . $kk . ");'  for='subchildmenu_" . $i . "-" . $j . "-" . $kk . "' >"
                                            . "<input type='checkbox' class='menucheckbox' name='menu' id='subchildmenu_" . $i . "-" . $j . "-" . $kk . "' value='" . $idsubchild . "'>&nbsp;&nbsp;" . $textsubchild . "</label></td>
                                                     <td><input type='checkbox' class='add' alt='Add' title='Add' id='add_" . $idsubchild . "' name='add_" . $idsubchild . "' onclick='checkmenuselected(" . $i . "," . $j . "," . $kk . "," . $idsubchild . ");' value='1'></td>
                                                    <td><input type='checkbox' class='edit' alt='Edit' title='Edit' id='edit_" . $idsubchild . "' name='edit_" . $idsubchild . "' onclick='checkmenuselected(" . $i . "," . $j . "," . $kk . "," . $idsubchild . ");'  value='1'></td>
                                                    <td><input type='checkbox' class='delete' alt='Delete' title='Delete' id='delete_" . $idsubchild . "' name='delete_" . $idsubchild . "'  onclick='checkmenuselected(" . $i . "," . $j . "," . $kk . "," . $idsubchild . ");' value='1'></td>
                                            </tr>";
                                        }
                                    } else {
                                        echo"<tr><td><label class='menuleft' onclick='checkparentselect(" . $i . "," . $j . ",-1);'>"
                                        . " <input type='checkbox' class='menucheckbox' id='childmenu_" . $i . "-" . $j . "' name='menu' id='menu' value='" . $idch1 . "'>&nbsp;&nbsp;" . $textch1 . "</label></td>"
                                        . " <td><input type='checkbox' class='add' alt='Add' title='Add' id='add_" . $idch1 . "' name='add_" . $idch1 . "' onclick='checkmenuselected(" . $i . "," . $j . ",-1," . $idch1 . ");'  value='1'></td>
                                            <td><input type='checkbox' class='edit' alt='Edit' title='Edit' id='edit_" . $idch1 . "' name='edit_" . $idch1 . "' onclick='checkmenuselected(" . $i . "," . $j . ",-1," . $idch1 . ");' value='1'></td>
                                            <td><input type='checkbox' class='delete' alt='Delete' title='Delete' id='delete_" . $idch1 . "' name='delete_" . $idch1 . "' onclick='checkmenuselected(" . $i . "," . $j . ",-1," . $idch1 . ");' value='1'></td></tr>";
                                    }
                                }
                            }
                            ?>
                        </tr>
                        <?php
                    }
                }
                ?>
            </table> -->
        <?php } ?>


        <fieldset>
            <div class="control-group pull-right">
                <input type="hidden" name="chkExAlertMapping" id="chkExAlertMapping" value=""/>
                <?php
                if ($_SESSION['customerno'] == 118) {
                    echo '<input type="button" value="Create User" class="btn btn-primary" onclick="addnewuser_hierarchy();">';
                } else {
                    echo '<input type="button" value="Create User" class="btn btn-primary" onclick="addnewuser();">';
                }
                ?>
            </div>
        </fieldset>
    </form>
</div>
<div class="modal hide" id="chkptExModal" tabindex="-1" role="dialog"   style="width:650px;">
    <div class="modal-dialog" >
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only"></span></button>
                <h4 class="modal-title" >Checkpoint Exceptions Alerts</h4>
            </div>
            <div class="modal-body" style="height: 350px; overflow: scroll;">
                <?php
                if (isset($exceptions)) {
                    ?>
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Sr. No.</th>
                                <th>Exception</th>
                                <th>Checkpoint</th>
                                <th>Vehicle</th>
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
                                    $tableRow = "<tr>";
                                    $tableRow .= "<td>" . $i++ . "</td>";
                                    $tableRow .= "<td>" . $exception->exceptionName . "</td>";
                                    $tableRow .= "<td>" . implode(',<br />', $exception->checkpointList) . "</td>";
                                    $tableRow .= "<td>" . implode(',<br />', $exception->vehicleList) . "</td>";
                                    $tableRow .= "<td>" . $exception->startTime . "</td>";
                                    $tableRow .= "<td>" . $exception->endTime . "</td>";
                                    $tableRow .= "<td>" . $exception->exceptionTypeName . "</td>";
                                    $tableRow .= "<td><input type='checkbox' class='chkexception' name='exceptionId" . $exception->exceptionId . "' id='exceptionId" . $exception->exceptionId . "' value='" . $exception->exceptionId . "'/></td>";
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
</div>
<script type='text/javascript' src='../../scripts/exception.js'></script>
<script>
            function copyText() {
                var emailsrc = jQuery("#email1").val();
                jQuery("#username").val(emailsrc);
            }

                    function mainmenu_list(id){
                        jQuery(".childrenmenu").each(function () {
                            if (jQuery('#' + this.id).prop('checked') == true){
                                return true;
                            } else {
                                var childrenid = jQuery(this).attr('id');
                                var str = this.id;
                                var concatid = str.split("_");
                                var child_count = jQuery("#mainmenu_count" + concatid[1]).val();
                                if (jQuery('#' + this.id).prop('checked') == true) {
                                    for (i = 0; i < child_count; i++) {
                                        jQuery("#childmenu_" + concatid[1] + '-' + i).attr('checked', true);
                                        //concatid[1] - mainid //add_
                                        if (jQuery("#childmenu_" + concatid[1] + '-' + i).prop('checked') == true) {
                                            var operationid = jQuery('#childmenu_' + concatid[1] + '-' + i).val();
                                            jQuery('#add_' + operationid).attr('checked', true);
                                            jQuery('#edit_' + operationid).attr('checked', true);
                                            jQuery('#delete_' + operationid).attr('checked', true);
                                            jQuery('#mainmenu_' + concatid[1]).attr('checked', true);
                                            var subchild_count = jQuery("#subchildcount" + i).val();
                                            for (j = 0; j < subchild_count; j++) {
                                                jQuery("#subchildmenu_" + concatid[1] + '-' + i + '-' + j).attr('checked', true);
                                                var operationid = jQuery("#subchildmenu_" + concatid[1] + '-' + i + '-' + j).val();
                                                jQuery('#add_' + operationid).attr('checked', true);
                                                jQuery('#edit_' + operationid).attr('checked', true);
                                                jQuery('#delete_' + operationid).attr('checked', true);
                                            }
                                        } else if (jQuery("#childmenu_" + concatid[1] + '-' + i).prop('checked') == false) {
                                            var subchild_count = jQuery("#subchildcount" + i).val();
                                            var operationid = jQuery('#childmenu_' + concatid[1] + '-' + i).val();
                                            jQuery('#add_' + operationid).attr('checked', false);
                                            jQuery('#edit_' + operationid).attr('checked', false);
                                            jQuery('#delete_' + operationid).attr('checked', false);
                                            for (j = 0; j < subchild_count; j++) {
                                                jQuery("#subchildmenu_" + concatid[1] + '-' + i + '-' + j).attr('checked', false);
                                                var operationid = jQuery("#subchildmenu_" + concatid[1] + '-' + i + '-' + j).val();
                                                jQuery('#add_' + operationid).attr('checked', false);
                                                jQuery('#edit_' + operationid).attr('checked', false);
                                                jQuery('#delete_' + operationid).attr('checked', false);
                                            }
                                        }
                                    }
                                } else if (jQuery('#' + this.id).prop('checked') == false) {
                                    for (i = 0; i < child_count; i++) {
                                        jQuery("#childmenu_" + concatid[1] + '-' + i).attr('checked', false);
                                        if (jQuery("#childmenu_" + concatid[1] + '-' + i).prop('checked') == false) {
                                            var operationid = jQuery('#childmenu_' + concatid[1] + '-' + i).val();
                                            jQuery('#add_' + operationid).attr('checked', false);
                                            jQuery('#edit_' + operationid).attr('checked', false);
                                            jQuery('#delete_' + operationid).attr('checked', false);
                                            var subchild_count = jQuery("#subchildcount" + i).val();
                                            for (j = 0; j < subchild_count; j++) {
                                                jQuery("#subchildmenu_" + concatid[1] + '-' + i + '-' + j).attr('checked', false);
                                                var operationid = jQuery("#subchildmenu_" + concatid[1] + '-' + i + '-' + j).val();
                                                jQuery('#add_' + operationid).attr('checked', false);
                                                jQuery('#edit_' + operationid).attr('checked', false);
                                                jQuery('#delete_' + operationid).attr('checked', false);
                                            }
                                        }
                                    }
                                }
                            }
                        });
                    }

                    function subchildrenmenu_list() {
                        jQuery(".subchildrenmenu").each(function (){
                            var subchildrenid = jQuery(this).attr('id');
                            var str = this.id;
                            var concatid = str.split("_");
                            var concatid1 = concatid[1].split("-");
                            var subchild_count = jQuery("#subchildcount" + concatid1[1]).val();
                            if (jQuery('#' + this.id).prop('checked') == true) {
                                jQuery('#mainmenu_' + concatid1[0]).attr('checked', true);

                                var operationid = jQuery('#' + this.id).val();
                                jQuery('#add_' + operationid).attr('checked', true);
                                jQuery('#edit_' + operationid).attr('checked', true);
                                jQuery('#delete_' + operationid).attr('checked', true);


                                for (i = 0; i < subchild_count; i++) {
                                    jQuery("#subchildmenu_" + concatid1[0] + '-' + concatid1[1] + '-' + i).attr('checked', true);
                                    var operationid = jQuery("#subchildmenu_" + concatid1[0] + '-' + concatid1[1] + '-' + i).val();
                                    jQuery('#add_' + operationid).attr('checked', true);
                                    jQuery('#edit_' + operationid).attr('checked', true);
                                    jQuery('#delete_' + operationid).attr('checked', true);

                                }
                            } else if (jQuery('#' + this.id).prop('checked') == false) {
                                var operationid = jQuery('#' + this.id).val();
                                jQuery('#add_' + operationid).attr('checked', false);
                                jQuery('#edit_' + operationid).attr('checked', false);
                                jQuery('#delete_' + operationid).attr('checked', false);

                                for (i = 0; i < subchild_count; i++) {
                                    jQuery("#subchildmenu_" + concatid1[0] + '-' + concatid1[1] + '-' + i).attr('checked', false);
                                    var operationid = jQuery("#subchildmenu_" + concatid1[0] + '-' + concatid1[1] + '-' + i).val();
                                    jQuery('#add_' + operationid).attr('checked', false);
                                    jQuery('#edit_' + operationid).attr('checked', false);
                                    jQuery('#delete_' + operationid).attr('checked', false);
                                }
                            }

                        });
                    }

                    function checkmenuselected(mainid, childid, subchildid, menuid){
                        if (subchildid == '-1'){
                            var viewstatus = 0;
                            var editstatus = 0;
                            var delstatus = 0;
                            if (jQuery('#add_' + menuid).prop('checked') == true) {
                                jQuery('#childmenu_' + mainid + '-' + childid).attr('checked', true);
                            } else {
                                viewstatus = 1;
                            }
                            if (jQuery('#edit_' + menuid).prop('checked') == true) {
                                jQuery('#childmenu_' + mainid + '-' + childid).attr('checked', true);
                            } else {
                                editstatus = 1;
                            }

                            if (jQuery('#delete_' + menuid).prop('checked') == true) {
                                jQuery('#childmenu_' + mainid + '-' + childid).attr('checked', true);
                            } else {
                                delstatus = 1;
                            }
                            var statuscount = viewstatus + editstatus + delstatus;

                            if (statuscount == 3) {
                                jQuery('#childmenu_' + mainid + '-' + childid).attr('checked', false);
                            }
                        } else {
                            //alert('with subchild');
                        }
                    }

                    function checkparentselect(id, childid, subchild) {
                        if (subchild == '-1') {
                            //mainmenu_0 //childmenu_0-1
                            if (jQuery('#childmenu_' + id + '-' + childid).prop('checked') == true) {
                                var operationid = jQuery('#childmenu_' + id + '-' + childid).val();
                                jQuery('#add_' + operationid).attr('checked', true);
                                jQuery('#edit_' + operationid).attr('checked', true);
                                jQuery('#delete_' + operationid).attr('checked', true);

                                if (jQuery('#mainmenu_' + id).prop('checked') == true) {
                                    return true;
                                } else {
                                    jQuery('#mainmenu_' + id).attr('checked', true);
                                }
                            } else {
                                var operationid = jQuery('#childmenu_' + id + '-' + childid).val();
                                jQuery('#add_' + operationid).attr('checked', false);
                                jQuery('#edit_' + operationid).attr('checked', false);
                                jQuery('#delete_' + operationid).attr('checked', false);
                            }
                        } else {
                            //mainmenu_0 //childmenu_0-1 //subchildmenu_0-11-0
                            if (jQuery('#subchildmenu_' + id + '-' + childid + '-' + subchild).prop('checked') == true) {

                                var operationid = jQuery('#subchildmenu_' + id + '-' + childid + '-' + subchild).val();
                                jQuery('#add_' + operationid).attr('checked', true);
                                jQuery('#edit_' + operationid).attr('checked', true);
                                jQuery('#delete_' + operationid).attr('checked', true);

                                if (jQuery('#childmenu_' + id + '-' + childid).prop('checked') == true) {
                                    if (jQuery('#mainmenu_' + id).prop('checked') == true) {
                                        return true;
                                    } else {
                                        jQuery('#mainmenu_' + id).attr('checked', true);
                                    }
                                } else {
                                    jQuery('#childmenu_' + id + '-' + childid).attr('checked', true);
                                    jQuery('#mainmenu_' + id).attr('checked', true);
                                }
                            } else {
                                var operationid = jQuery('#subchildmenu_' + id + '-' + childid + '-' + subchild).val();
                                jQuery('#add_' + operationid).attr('checked', false);
                                jQuery('#edit_' + operationid).attr('checked', false);
                                jQuery('#delete_' + operationid).attr('checked', false);
                            }
                        }
                    }


                    function getallmenusstatus() {
                        var totalArray = [];
                        jQuery(".menucheckbox").each(function () {
                            if (jQuery('#' + this.id).prop('checked') == true) {
                                var menuid = jQuery('#' + this.id).val();
                                var viewstatus = 0;
                                var editstatus = 0;
                                var delstatus = 0;
                                var viewval = 0;
                                var editval = 0;
                                var delval = 0;
                                if (jQuery('#add_' + menuid).prop('checked') == true) {
                                    var viewval = jQuery('#add_' + menuid).val();
                                } else {
                                    viewstatus = 1;
                                }
                                if (jQuery('#edit_' + menuid).prop('checked') == true) {
                                    var editval = jQuery('#edit_' + menuid).val();
                                } else {
                                    editstatus = 1;
                                }
                                if (jQuery('#delete_' + menuid).prop('checked') == true) {
                                    var delval = jQuery('#delete_' + menuid).val();
                                } else {
                                    delstatus = 1;
                                }
                                var statuscount = viewstatus + editstatus + delstatus;
                                //alert(statuscount);
                                if (statuscount != 3) {
                                    var obj = {
                                        "menuid": menuid,
                                        "viewval": viewval,
                                        "editval": editval,
                                        "delval": delval
                                    }
                                } else {
                                    var obj = {
                                        "menuid": menuid,
                                        "viewval": 0,
                                        "editval": 0,
                                        "delval": 0
                                    }
                                }
                                totalArray.push(obj);

                                jQuery("#allobjects").val(totalArray);
                            }
                        });
                    }

                    var dateIdArray = ['STimeTemp', 'ETimeTemp', 'STimeIg', 'ETimeIg', 'STimeOs',
                        'ETimeOs', 'STimeGn', 'ETimeGn', 'STimePowerc', 'ETimePowerc', 'STimeTamper',
                        'ETimeTamper', 'STimeharsh_break', 'ETimeharsh_break', 'STimehigh_acce', 'ETimehigh_acce',
                        'STimetowing', 'ETimetowing', 'popStartTime', 'popEndTime', 'STimePanic', 'ETimePanic',
                        'STimeImmob', 'ETimeImmob', 'STimeDoor', 'ETimeDoor'];
                    jQuery(function () {
                        jQuery.each(dateIdArray, function (i) {
                            jQuery('#' + dateIdArray[i]).timepicker({
                                minuteStep: 1,
                                showMeridian: false,
                                defaultTime: jQuery('#' + dateIdArray[i]).data('date')
                            });
                            jQuery(document).click(function () {
                                jQuery('#' + dateIdArray[i]).timepicker('hide');
                            });
                        });
                        jQuery('.input-mini').click(function () {
                            var inputId = this.id;
                            jQuery.each(dateIdArray, function (i) {
                                if (dateIdArray[i] != inputId) {
                                    jQuery('#' + dateIdArray[i]).timepicker('hide');
                                }
                            });
                        });

                        /*jQuery('#role').change(function () {
                            if (this.value == 'delivery_boy') {
                                jQuery('#deliveryBoyVN').show();
                            } else {
                                jQuery('#deliveryBoyVN').hide();
                            }
                        });*/

                        jQuery('#role').change(function () {
                                    if (this.value == 'delivery_boy') {
                                        jQuery('#deliveryBoyVN').show();
                                    }
                                    else {
                                        jQuery('#deliveryBoyVN').hide();
                                        jQuery("#vehicleno_db").val('');
                                    }
                                    if (this.value == 'Custom') {
                                        jQuery('#vehicle_for_user').show();
                                    }
                                    else {
                                        jQuery('#vehicle_for_user').hide();
                                        jQuery('#vehicleids').val('');
                                        //jQuery('.vehiclebox').hide();
                                        //$("#vehicle_list :input").attr("disabled", true);
                                        $("#vehicle_list").html('');

                                    }
                                });



                    });
</script>
