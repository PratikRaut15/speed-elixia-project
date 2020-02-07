<?php
$get_uid = $_SESSION['userid'];
if (isset($_GET['uid'])) {
    $get_uid = (int) $_GET['uid'];
}
$user = getuser($get_uid);
if ($_SESSION["use_maintenance"] == '1' && $_SESSION["use_hierarchy"] == '1' && ($user->roleid != '5' && $user->roleid != '7' && $user->roleid != '9' && $user->roleid != '11')) {
    $heirdetails = getheirdetails($user->id, $user->roleid);
}

$hrole = '';
$huserid = '';
if($_SESSION['use_secondary_sales'] == 1){
    if (isset($user->heirarchy_id) && !empty($user->heirarchy_id)) {
        $heirarchy_details = getuser($user->heirarchy_id);
        $hrole = $heirarchy_details->role;
        $huserid = $heirarchy_details->id;
    }
}

if ($_SESSION['use_routing']) {
    $vehicle_option = get_vehicles_option($user->delivery_vehicleid);
}
$mid = '';
$objRole = new Hierarchy();
$objRole->roleid = '';
$objRole->parentroleid = '';
$objRole->moduleid = $mid;
$objRole->customerno = $_SESSION['customerno'];
$roles = getRolesByCustomer($objRole);
$userReports = getUserReports($get_uid, $_SESSION['customerno']);
//prettyPrint($userReports);
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


<form method="POST" id="edituser" class="form-horizontal well "  style="width:80%;">
    <?php include 'panels/edituser.php'; ?>
    <fieldset>
        <div class="control-group">
            <div class="input-prepend ">
                <span class="add-on">Name <span class="mandatory">*</span></span><input type="text" name="name" id="nameid" placeholder="Name" value="<?php echo $user->realname; ?>" autofocus>
                <input type="hidden" name="userid" id="userid" value="<?php echo $user->id; ?>" autofocus>
                <input type="hidden" id="heirid" name="heirid" value="<?php echo ($_SESSION["heirarchy_id"]); ?>">
                <input type="hidden" id="heirarchy_id" name="heirarchy_id" value="<?php echo $user->heirarchy_id; ?>">
            </div>
            <div class="input-prepend ">
                <?php 
                    if($customerno == 64 || $customerno == 756){
                ?>
                <span class="add-on">User Name <span class="mandatory">*</span></span><input type="text" name="username" id="username" value="<?php echo $user->username; ?>" placeholder="User Name">
                <input type="hidden" name="hiddenusername" id="hiddenusername" value="<?php echo $user->username; ?>" placeholder="User Name" >
                 <?php }else{
                    ?>
                <span class="add-on">User Name <span class="mandatory">*</span></span><input type="text" name="username" id="username" value="<?php echo $user->username; ?>" placeholder="User Name" readonly="">
                <input type="hidden" name="hiddenusername" id="hiddenusername" value="<?php echo $user->username; ?>" placeholder="User Name" readonly="">
                 <?php
                    }?>
            </div>
        </div>
    </fieldset>
    <fieldset>
        <div class="control-group">
         <div class="input-prepend ">
                <?php 
             if($customerno == 64 || $customerno == 756){
                ?>
                <span class="add-on">Email <span class="mandatory">*</span></span><input type="email" name="email" id="email1" placeholder="Email" value ="<?php echo $user->email; ?>">
                <?php }
                else{
                    ?>
                      <span class="add-on">Email <span class="mandatory">*</span></span><input type="email" name="email" onKeyUp="copyText()" id="email1" placeholder="Email" value ="<?php echo $user->email; ?>">
                    <?php
                }
                ?>
                </div>
            <div class="input-prepend ">
                <span class="add-on">Password <span class="mandatory">*</span></span><input type="password" name="password" id="password" placeholder="Password">
            </div>
        </div>
    </fieldset>
    <fieldset>

        <div class="control-group">
            <div class="input-prepend ">
                <span class="add-on">Phone No <span class="mandatory">*</span></span><span class="add-on">+91</span><input type="text" name="phoneno" id="phoneno" placeholder="Phone No" value ="<?php echo $user->phone; ?>">
            </div>
            <div class="input-prepend ">
                <span class="add-on">Role <span class="mandatory">*</span></span>
                <?php
                if ($_SESSION['use_maintenance'] == '1' && $_SESSION["use_hierarchy"] == '1' && $_SESSION['switch_to'] == '1') {
                    echo '<select id="role" name="role" onchange="getParentUser()">';
                    echo '<option id="SelectUser" value="0" rel="0">Select Role</option>';
                    if (isset($roles) && !empty($roles)) {
                        foreach ($roles as $role) {
                            if ($role[id] == $user->roleid) {
                                echo "<option id='" . $role[role] . "' value='" . $role[role] . "' rel='" . $role[id] . "' selected=''>" . $role[role] . "</option>";
                            } else {
                                echo "<option id='" . $role[role] . "' value='" . $role[role] . "' rel='" . $role[id] . "'>" . $role[role] . "</option>";
                            }
                        }
                    }
                }
                else if($_SESSION['customerno'] == 64 || $_SESSION['customerno'] == 756){
                     echo '<select id="role" name="role" onchange="getParentUser()">';
                      echo '<option id="SelectUser" value="0" rel="0">Select Role</option>';
                         if (isset($roles) && !empty($roles)) {
                        foreach($roles as $key=>$role) {
                                if ($user->roleid == $role['id']) {
                                    echo "<option id='" . $role['role'] . "' value='" . $role['role'] . "' rel='" . $role['id'] . "' selected=''>" . $role['role'] . "</option>";
                                }
                           else  {
                                echo "<option id='" . $role['role'] . "' value='" . $role['role'] . "' rel='" . $role['id'] . "'>" . $role['role'] . "</option>";
                           }
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
                    if ($user->role == "Administrator") {
                        echo '<option id="Administrator" value="Administrator" rel="5" selected>Administrator</option>';
                        if ($_SESSION['switch_to'] != '6') {
                            echo '<option id="Tracker" value="Tracker" rel="7">Tracker</option>';
                            echo '<option id="Viewer" value="Viewer" rel="9">Viewer</option>';
                            echo '<option id="Custom" value="Custom" rel="43">Custom</option>';
                        }
                        if ($switch_to == '6') {
                            if ($_SESSION['use_secondary_sales']) {
                                echo "<option id='sales_representative' value='sales_representative' rel='14'>sales Representative</option>";
                                echo "<option id='ASM' value='ASM' rel='13'>ASM</option>";
                                echo "<option id='Supervisor' value='Supervisor' rel='40'>Supervisor</option>";
                                echo "<option id='Distributor' value='Distributor' rel='41'>Distributor</option>";
                            }
                        }
                        if ($_SESSION['use_routing']) {
                            echo "<option id='delivery_boy' value='delivery_boy' rel='11'>Delivery Boy</option>";
                        }
                        if ($switch_to == '8') {
                            if ($_SESSION['use_sales']) {
                                echo "<option id='sales_manager' value='sales_manager' rel='12'>Sales Manager</option>";
                            }
                        }
                    } elseif ($user->role == "Tracker") {
                        ?>
                        <option id="Administrator" value="Administrator" rel="5">Administrator</option>
                        <option id="Tracker" value="Tracker" rel="7" selected>Tracker</option>
                        <option id="Viewer" value="Viewer" rel="9">Viewer</option>
                        <option id="Custom" value="Custom" rel="43">Custom</option>
                        <?php
                        if ($_SESSION['use_routing']) {
                            echo "<option id='delivery_boy' value='delivery_boy' rel='11'>Delivery Boy</option>";
                        }
                        if ($switch_to == '8') {
                            if ($_SESSION['use_sales']) {
                                echo "<option id='sales_manager' value='sales_manager' rel='12'>Sales Manager</option>";
                            }
                        }
                    } elseif ($user->role == "Viewer") {
                        ?>
                        <option id="Administrator" value="Administrator" rel="5">Administrator</option>
                        <option id="Tracker" value="Tracker" rel="7">Tracker</option>
                        <option id="Viewer" value="Viewer" rel="9" selected>Viewer</option>
                        <option id="Custom" value="Custom" rel="43">Custom</option>
                        <?php
                        if ($_SESSION['use_routing']) {
                            echo "<option id='delivery_boy' value='delivery_boy' rel='11'>Delivery Boy</option>";
                        }
                        if ($switch_to == '8') {
                            if ($_SESSION['use_sales']) {
                                echo "<option id='sales_manager' value='sales_manager' rel='12'>Sales Manager</option>";
                            }
                        }
                    } elseif ($user->role == "Custom") {
                        ?>
                        <option id="Administrator" value="Administrator" rel="5">Administrator</option>
                        <option id="Tracker" value="Tracker" rel="7">Tracker</option>
                        <option id="Viewer" value="Viewer" rel="9" >Viewer</option>
                        <option id="Custom" value="Custom" rel="43" selected>Custom</option>
                        <?php
                    } elseif ($user->role == "delivery_boy") {
                        ?>
                        <option id="Administrator" value="Administrator" rel="5">Administrator</option>
                        <option id="Tracker" value="Tracker" rel="7">Tracker</option>
                        <option id="Viewer" value="Viewer" rel="9" >Viewer</option>
                        <?php
                        if ($_SESSION['use_routing']) {
                            echo "<option id='delivery_boy' value='delivery_boy' rel='11' >Delivery Boy</option>";
                        }
                    } elseif ($user->role == "sales_manager") {
                        ?>
                        <option id="Administrator" value="Administrator" rel="5">Administrator</option>
                        <option id="Tracker" value="Tracker" rel="7">Tracker</option>
                        <option id="Viewer" value="Viewer" rel="9" >Viewer</option>
                        <option id="sales_manager" value="sales_manager" rel="12" >Sales Manager</option>
                        <?php
                    } elseif ($user->role == "sales_representative") {
                        ?>
                        <option id="Administrator" value="Administrator" rel="5">Administrator</option>
                        <option id="sales_representative" value="sales_representative" selected="selected" rel="14" >Sales Representative</option>
                        <option id="ASM" value="ASM" rel="13" >ASM</option>
                        <option id='Supervisor' value='Supervisor' rel='40'>Supervisor</option>
                        <option id='Distributor' value='Distributor' rel='41'>Distributor</option>
                        <?php
                    } elseif ($user->role == "ASM") {
                        ?>
                        <option id="Administrator" value="Administrator" rel="5">Administrator</option>
                        <option id="sales_representative" value="sales_representative" rel="14" >Sales Representative</option>
                        <option id="ASM" value="ASM" rel="13" selected="selected" >ASM</option>
                        <option id='Supervisor' value='Supervisor' rel='40'>Supervisor</option>
                        <option id='Distributor' value='Distributor' rel='41'>Distributor</option>
                        <?php
                    } elseif ($user->role == "Distributor") {
                        ?>
                        <option id="Administrator" value="Administrator" rel="5">Administrator</option>
                        <option id="sales_representative" value="sales_representative" rel="14" >Sales Representative</option>
                        <option id="ASM" value="ASM" rel="13"  >ASM</option>
                        <option id='Supervisor' value='Supervisor' rel='40'>Supervisor</option>
                        <option id='Distributor' value='Distributor' rel='41' selected="selected">Distributor</option>
                        <?php
                    } elseif ($user->role == "Supervisor") {
                        ?>
                        <option id="Administrator" value="Administrator" rel="5">Administrator</option>
                        <option id="sales_representative" value="sales_representative" rel="14" >Sales Representative</option>
                        <option id="ASM" value="ASM" rel="13"  >ASM</option>
                        <option id='Supervisor' value='Supervisor' rel='40' selected="selected">Supervisor</option>
                        <option id='Distributor' value='Distributor' rel='41'>Distributor</option>
                        <?php
                    } elseif ($user->role == "Data Entry") {
                        ?>
                        <option id="Administrator" value="Administrator" rel="5">Administrator</option>
                        <option id="Tracker" value="Tracker" rel="7">Tracker</option>
                        <option id="Viewer" value="Viewer" rel="9" selected>Viewer</option>
                        <?php
                        if ($_SESSION['use_maintenance'] == '1' && $_SESSION["use_hierarchy"] == '0') {
                            ?>
                            <option id="Data Entry" value="Data Entry" rel="10">Data Entry</option>
                        <?php } ?>
                        <?php
                    }
                }
                ?>
                </select>
            </div>
            <?php if ($_SESSION['use_routing']) {
                ?>
                <div class="input-prepend" id='deliveryBoyVN' style='<?php
                if ($user->delivery_vehicleid == null || $user->delivery_vehicleid == 0) {
                    echo "display:none";
                }
                ?>'>
                    <span class="add-on">Vehicle No.</span>
                    <?php echo $vehicle_option; ?>
                </div>
            <?php } ?>
        </div>
    </fieldset>
    <?php
    if ($_SESSION['use_maintenance'] == '1' && $_SESSION['use_hierarchy'] == '1' && $_SESSION['switch_to'] == '1') {
        ?>
        <fieldset id="ParentRole">
            <div class="control-group">
                <div class="input-prepend ">
                    <span class="add-on">Parent User</span>
                    <select id="parentuser" name="parentuser" onchange="getParentGroups()" >
                        <option value="0">Select Parent</option>
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
        $srselectid = "";
        $supervisorid = "";
        $srdisplay = "none";
        $display = "none";
        $asmdisplay = "none";
        if ($_SESSION['use_secondary_sales'] == 1) {
            $asms = getallasm();
            $srs = getallsr();
            $supervisors = getallsupervisor();
            if ($user->role == "sales_representative") {
                $display = 'block';
                $supervisorid = $huserid;
            } else if ($user->role == "Distributor") {
                $srdisplay = 'block';
                $srselectid = $huserid;
            } else if ($user->role == "Supervisor") {
                $asmdisplay = 'block';
                $asmselectid = $huserid;
            } else {
                $display = 'none';
                $srdisplay = 'none';
            }
            ?>





            <div class="input-prepend " id="salerep" style="display: <?php echo $srdisplay; ?>">
                <span class="add-on">Sales Representative <span class="mandatory">*</span></span>
                <select id="srid" name="srid" onchange="getasm()">
                    <option value=''>Select Sales Representative</option>
                    <?php
                    if (isset($srs)) {
                        foreach ($srs as $sr) {
                            $selected = '';
                            if ($sr->userid == $srselectid) {
                                $selected = "selected";
                            }
                            echo "<option value='$sr->userid' $selected >$sr->realname</option>";
                        }
                    }
                    ?>
                </select>
            </div>
            <div class="input-prepend " id="supervisor" style="display:<?php echo $display; ?>">
                <span class="add-on">Supervisor <span class="mandatory">*</span></span>
                <select id="supid" name="supid" onchange="getasm()">
                    <option value=''>Select Supervisor</option>
                    <?php
                    if (isset($asms)) {
                        foreach ($supervisors as $sup) {
                            $selected1 = '';
                            if ($sup->userid == $supervisorid) {
                                $selected1 = "selected";
                            }
                            echo "<option value='$sup->userid'  " . $selected1 . " >$sup->realname</option>";
                        }
                    }
                    ?>
                </select>
            </div>
            <div class="input-prepend " id="asm" style="display: <?php echo $asmdisplay; ?>;">
                <span class="add-on">ASM <span class="mandatory">*</span></span>
                <select id="asmid" name="asmid" onchange="getasm()">
                    <option value=''>Select ASM</option>
                    <?php
                    if (isset($asms)) {
                        foreach ($asms as $asm) {
                            $selected2 = '';
                            if ($asm->userid == $asmselectid) {
                                $selected2 = "selected";
                            }

                            echo "<option value='$asm->userid'  $selected2 >$asm->realname</option>";
                        }
                    }
                    ?>
                </select>
            </div>
        <?php } ?>
    </fieldlist>
    <fieldset>
        <div class="control-group">
            <?php if ($_SESSION['use_maintenance'] == '1' && $_SESSION["use_hierarchy"] == '1' && $_SESSION['switch_to'] == '1') {
                ?>
                <div class="input-prepend ">
                    <span class="add-on"><?php echo ($_SESSION['group']); ?> <span class="mandatory">*</span></span><select id="group" name="group" onChange="addgrouptouser()"><option>Select<?php echo ($_SESSION['group']); ?></option>
                    </select>
                </div>
                <div id="group_list">
                    <?php
                    $groupsg = getmappedgroup($user->id);
                    if (isset($groupsg)) {
                        foreach ($groupsg as $group) {
                            ?>
                            <input type="hidden" class="mappedgroups" id="hid_g<?php echo ($group->groupid); ?>" rel="<?php echo ($group->groupid); ?>" value="<?php echo ($group->groupname); ?>">
                            <?php
                        }
                    } else {
                        ?>
                        <input type="hidden" class="mappedgroups" id="hid_g0" rel="0" value="All">
                    <?php } ?>
                </div>
                <?php
            } else {
                if ($_SESSION['switch_to'] != '9' && $_SESSION['switch_to'] != '6') {
                    ?>
                    <div class="input-prepend ">
                        <span class="add-on"><?php echo ($_SESSION['group']); ?> <span class="mandatory">*</span></span><select id="group" name="group" onChange="addgrouptouser()"><option>Select<?php echo ($_SESSION['group']); ?></option>
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
                    </div>
                    <div id="group_list">
                        <?php 
                        $groupsg = getmappedgroup($user->id);
                        if (isset($groupsg)) {
                            foreach ($groupsg as $group) {
                                ?>
                                <input type="hidden" class="mappedgroups" id="hid_g<?php echo ($group->groupid); ?>" rel="<?php echo ($group->groupid); ?>" value="<?php echo ($group->groupname); ?>">
                                <?php
                            }
                        } else {
                            ?>
                            <input type="hidden" class="mappedgroups" id="hid_g0" rel="0" value="All">
                        <?php } ?>
                    </div>
                    <?php
                }
            }
            ?>
        </div>
        <input type="hidden" name="is_maintenance" id="is_maintenance" value="<?php echo $_SESSION['use_maintenance'] ?>" />
                <input type="hidden" name="is_hierarchy" id="is_hierarchy" value="<?php echo $_SESSION['use_hierarchy'] ?>" />
                <input type="hidden" name="session_roleid" id="session_roleid" value="<?php echo $_SESSION['roleid'] ?>" />
    </fieldset>
    <fieldset>
        <div class="control-group" id="vehicle_for_user" style="display: none;">
            <div class="input-prepend " >
                <span class="add-on"><?php echo $vehicles; ?> <span class="mandatory">*</span></span>
                <select id="vehicleids" name="vehicleids" onChange="addVehicleByGroup()">
                    <option>Select                                   <?php echo ($_SESSION['group']); ?></option>
                </select>
            </div>
            <div id="vehicle_list">
                <?php
                $vehicles = getmappedvehicles($user->id);
                if (isset($vehicles)) {
                    foreach ($vehicles as $v) {
                        echo '<div class="vehiclebox" id="to_vehicle_div_' . $v->vehicleid . '"><span>' . $v->vehicleno . '</span>
                                <input type="hidden" value="' . $v->vehicleid . '" name="to_vehicle_' . $v->vehicleid . '">
                                <img class="clickimage" src="../../images/boxdelete.png" onclick="removeVehicleByGroup(' . $v->vehicleid . ')" ></div>';
                    }
                }
                ?>
            </div>
        </div>
    </fieldset>

    <?php if ($_SESSION['switch_to'] == 1) {
        ?>
        <?php
        $allmenulist = getmenus();
        $allmenulistuser1 = getcustomerdetailmenu($_GET['uid']);
        $getmenuids = $allmenulistuser1['getids'];
        $menuconfig = $allmenulistuser1['menuconfig'];
        $viewp = 'unchecked';
        $editp = 'unchecked';
        $deletep = 'unchecked';
        $subcheckedmainmenu = 'unchecked';
        $checkedmainmenu = 'unchecked';
        ?>
        <table class="table table-condensed" style="width:70%;">
            <tr><th colspan="100%"><h4>Select Menus</h4></th></tr>
            <tr><td colspan="100%">&nbsp;</td></tr>
            <tr><td><h5>Menus</h5></td><td colspan="3"><h5>Permissions</h5></td></tr>
            <tr><td>&nbsp;</td><td><i class="icon-plus" alt="Add" title="Add"></i></td><td><i class="icon-pencil" alt="Edit" title="Edit"></i></td><td><i class="icon-trash" alt="Delete" title="Delete"></i></td></tr>
            <?php
            for ($i = 0; $i < count($allmenulist['children']); $i++) {
                $tri = 0;
                $mainmenuid = $allmenulist['children'][$i]['id'];
                if (empty($allmenulist['children'][$i]['children'])) {
                    echo"<tr>";
                    ?>
                    <td colspan="100%;">
                        <?php
                        if (isset($getmenuids)) {
                            if (in_array($mainmenuid, $getmenuids)) {
                                $checkedmainmenu = "checked";
                            } else {
                                $checkedmainmenu = '';
                                $checkedmainmenu = "unchecked";
                            }
                        }
                        echo "<label class='mainmenu'><input type='checkbox' class='menucheckbox' " . $checkedmainmenu . " id='mainmenu_" . $i . "'  name='menu'  value='" . $mainmenuid . "'>&nbsp;&nbsp;" . $allmenulist['children'][$i]['text'] . "</label>";
                        ?>
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
                            if (isset($getmenuids)) {
                                if (in_array($mainmenuid, $getmenuids)) {
                                    $checkedmainmenu = "checked";
                                } else {
                                    $checkedmainmenu = 'unchecked';
                                }
                            }

                            echo"<label class='mainmenu' for='mainmenu_" . $i . "'>"
                            . " <input type='hidden' id='mainmenu_count" . $i . "' value='" . $childrencount . "'>"
                            . " <input type='checkbox' class='menucheckbox childrenmenu' id='mainmenu_" . $i . "' name='menu' " . $checkedmainmenu . " onclick='mainmenu_list(" . $i . ");'  value='" . $mainmenuid . "'> &nbsp;&nbsp;" . $allmenulist['children'][$i]['text'] . "</label>";
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
                                    if (isset($getmenuids)) {
                                        if (in_array($idch1, $getmenuids)) {
                                            $checkedmainmenu = "checked";
                                        } else {
                                            $checkedmainmenu = 'unchecked';
                                        }
                                    }
                                    echo"<tr><td><label class='mainmenu-child' for='childmenu_" . $i . "-" . $j . "' ><input type='hidden' name='subchildcount' id='subchildcount" . $j . "'  value='" . count($subchild) . "' >"
                                    . "<input type='checkbox' class='menucheckbox subchildrenmenu ' id='childmenu_" . $i . "-" . $j . "' " . $checkedmainmenu . " onclick='subchildrenmenu_list();'  name='menu'  value='" . $idch1 . "'>&nbsp;&nbsp; " . $textch1 . "</label></td>
                                            </tr>";
                                    for ($kk = 0; $kk < count($subchild); $kk++) {
                                        $textsubchild = $subchild[$kk]['text'];
                                        $idsubchild = $subchild[$kk]['id'];
                                        $pagesubchild = $subchild[$kk]['page'];
                                        if (isset($getmenuids)) {
                                            if (in_array($idsubchild, $getmenuids)) {
                                                $subcheckedmainmenu = "checked";
                                                $viewp = $menuconfig[$idsubchild]['add_permission'] == 1 ? "checked" : "unchecked";
                                                $editp = $menuconfig[$idsubchild]['edit_permission'] == 1 ? "checked" : "unchecked";
                                                $deletep = $menuconfig[$idsubchild]['delete_permission'] == 1 ? "checked" : "unchecked";
                                            } else {
                                                $subcheckedmainmenu = 'unchecked';
                                                $viewp = 'unchecked';
                                                $editp = 'unchecked';
                                                $deletep = 'unchecked';
                                            }
                                        }
                                        echo"<tr><td><label class='mainmenu-subchild' " . $checkedmainmenu . " onclick='checkparentselect(" . $i . "," . $j . "," . $kk . ");'  for='subchildmenu_" . $i . "-" . $j . "-" . $kk . "' >"
                                        . "<input type='checkbox' class='menucheckbox' name='menu' id='subchildmenu_" . $i . "-" . $j . "-" . $kk . "' " . $subcheckedmainmenu . " value='" . $idsubchild . "'>&nbsp;&nbsp;" . $textsubchild . "</label></td>
                                                     <td><input type='checkbox' class='add' title='Add' alt='Add' id='add_" . $idsubchild . "' " . $viewp . " name='add_" . $idsubchild . "' onclick='checkmenuselected(" . $i . "," . $j . "," . $kk . "," . $idsubchild . ");' value='1'></td>
                                                    <td><input type='checkbox' class='edit' title='Edit' alt='Edit' id='edit_" . $idsubchild . "' " . $editp . " name='edit_" . $idsubchild . "' onclick='checkmenuselected(" . $i . "," . $j . "," . $kk . "," . $idsubchild . ");'  value='2'></td>
                                                    <td><input type='checkbox' class='delete' title='Delete' alt='Delete' id='delete_" . $idsubchild . "' " . $deletep . " name='delete_" . $idsubchild . "'  onclick='checkmenuselected(" . $i . "," . $j . "," . $kk . "," . $idsubchild . ");' value='3'></td>
                                            </tr>";
                                    }
                                } else {
                                    if (isset($getmenuids)) {
                                        if (in_array($idch1, $getmenuids)) {
                                            $checkedmainmenu = "checked";
                                            $viewp = $menuconfig[$idch1]['add_permission'] == 1 ? "checked" : "unchecked";
                                            $editp = $menuconfig[$idch1]['edit_permission'] == 1 ? "checked" : "unchecked";
                                            $deletep = $menuconfig[$idch1]['delete_permission'] == 1 ? "checked" : "unchecked";
                                        } else {
                                            $checkedmainmenu = 'unchecked';
                                            $viewp = 'unchecked';
                                            $editp = 'unchecked';
                                            $deletep = 'unchecked';
                                        }
                                    }
                                    echo"<tr><td><label class='menuleft' onclick='checkparentselect(" . $i . "," . $j . ",-1);'>"
                                    . " <input type='checkbox' class='menucheckbox' id='childmenu_" . $i . "-" . $j . "' name='menu' id='menu'" . $checkedmainmenu . " value='" . $idch1 . "'>&nbsp;&nbsp;" . $textch1 . "</label></td>"
                                    . " <td>
                                            <input type='checkbox' class='add' title='Add' id='add_" . $idch1 . "' name='add_" . $idch1 . "' " . $viewp . " onclick='checkmenuselected(" . $i . "," . $j . ",-1," . $idch1 . ");'  value='1'></td>
                                            <td><input type='checkbox' class='edit' title='Edit' id='edit_" . $idch1 . "' name='edit_" . $idch1 . "' " . $editp . " onclick='checkmenuselected(" . $i . "," . $j . ",-1," . $idch1 . ");' value='2'></td>
                                            <td><input type='checkbox' class='delete' title='Delete' id='delete_" . $idch1 . "' name='delete_" . $idch1 . "' " . $deletep . " onclick='checkmenuselected(" . $i . "," . $j . ",-1," . $idch1 . ");' value='3'></td></tr>";
                                }
                            }
                        }
                        ?>
                    </tr>
            <?php
        }
    }
    ?>
        </table>
<?php } ?>
    <fieldset>
        <div class="control-group pull-right">
            <input type="hidden" name="customerno" id="customerno" value="<?php echo $_SESSION['customerno']; ?>">
            <input type="button" value="Modify User" class="btn btn-primary" onclick="edituser_hierarchy();">
        </div>
    </fieldset>
</form>
<?php
if ($_SESSION['switch_to'] != 8 && $_SESSION['switch_to'] != '9' && $_SESSION['switch_to'] != '6' && $_SESSION['switch_to'] != '1') {
    ?>
    <div class="well " style='min-height:900px;width:80%'>
        <legend>Alerts</legend>
                    <?php
                    $ajax_url = '../user/ajax_alert.php';
                    $exception_url = "../user/exception_ajax.php";
                    include_once "../user/pages/alerts.php";
                    ?>
    </div>


    <div class="well" style="width:80%">
        <form id="userReportMapping">
            <table class="table table-condensed" style="width:50%;" id="">
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
    if (isset($userReports) && !empty($userReports)) {
        foreach ($userReports as $report) {
            if($report->reportId != '6') {
                $checked = '';
                $disable = 'disabled';
                if (isset($report->isActivated) && $report->isActivated == '1') {
                    $checked = 'checked';
                    $disable = '';
                }
                if (isset($_SESSION["switch_to"]) && $_SESSION["switch_to"] == 3) {
                    if ($report->is_warehouse != 1) {
                        ?>
                                        <tr id="reports_<?php echo $report->reportId; ?>" >
                                            <td><input type="checkbox" onclick="enableReportTime(<?php echo $report->reportId; ?>)" id="activated_<?php echo $report->reportId; ?>" name="activated_<?php echo $report->reportId; ?>" <?php echo $checked; ?>  /></td>
                                            <td>
                                        <?php
                                        $concatString = "";
                                        if (isset($_SESSION['use_humidity']) && $_SESSION['use_humidity'] == 1 && $report->reportId == 5) {
                                            $concatString = "Humidity & ";
                                        }
                                        echo $concatString . "" . $report->reportName;
                                        ?>
                                            </td>
                                            <td>
                                                <select class="reportOn" <?php echo $disable; ?> id="reportTime_<?php echo $report->reportId; ?>" name="reportTime_<?php echo $report->reportId; ?>" >
                                                    <option value='-1'>Select Time</option>
                                                <?php
                                                for ($i = 1; $i <= 23; $i++) {
                                                    $selected = '';
                                                    if (isset($report->reportTime) && $i == $report->reportTime) {
                                                        $selected = 'selected';
                                                    }
                                                    $time = sprintf("%02d:00", $i);
                                                    echo "<option value='" . $i . "' $selected>" . $time . "</option>";
                                                }
                                                ?>
                                                </select>
                                            </td>
                                            <?php 
                                                    if($report->reportId == 5){
                                                        ?>
                                            <td>
                                                            <select id="temprepinterval" <?php echo $disable; ?>  name="temprepinterval">
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


                                                    }else if($report->reportId == 19){ ?> 
                                            <td>
                                                <select id="vehrepinterval" <?php echo $disable; ?> name="vehrepinterval">
                                                            <option value="0">Inactive Since(In Days)</option>
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
                                            } else {
                                                ?>
                                    <tr id="reports_<?php echo $report->reportId; ?>" >
                                        <td><input type="checkbox" onclick="enableReportTime(<?php echo $report->reportId; ?>)" id="activated_<?php echo $report->reportId; ?>" name="activated_<?php echo $report->reportId; ?>" <?php echo $checked; ?>  /></td>
                                        <td>
                                    <?php
                                    $concatString = "";
                                    if (isset($_SESSION['use_humidity']) && $_SESSION['use_humidity'] == 1 && $report->reportId == 5) {
                                        $concatString = "Humidity & ";
                                    }
                                    echo $concatString . "" . $report->reportName;
                                    ?>
                                        </td>
                                        <td>
                                            <select class="reportOn" <?php echo $disable; ?> id="reportTime_<?php echo $report->reportId; ?>" name="reportTime_<?php echo $report->reportId; ?>" >
                                                <option value='-1'>Select Time</option>
                    <?php
                    for ($i = 1; $i <= 23; $i++) {
                        $selected = '';
                        if (isset($report->reportTime) && $i == $report->reportTime) {
                            $selected = 'selected';
                        }
                        $time = sprintf("%02d:00", $i);
                        echo "<option value='" . $i . "' $selected>" . $time . "</option>";
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
                                            if($report->reportId == 19){ ?> 
                                            <td>
                                                <select id="vehrepinterval" <?php echo $disable; ?> name="vehrepinterval">
                                                            <option value="0">Inactive Since(In Days)</option>
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
        }}
    }
    ?>
                    <tr>
                        <td colspan="4">
                            <input id="userReports" class="btn btn-primary" type="button" onclick="modifyUserReports(<?php echo $get_uid; ?>);" value="Modify Reports" name="userReports">
                        </td>
                    </tr>
                </tbody>
            </table>
        </form>
    </div>
<?php } ?>
<script type='text/javascript' src='../../scripts/edituser.js'></script>
<script type='text/javascript' src='../../scripts/exception.js'></script>
<script type='text/javascript'>

                                function mainmenu_list(id) {
                                    jQuery(".childrenmenu").each(function () {
                                        if (jQuery('#' + this.id).prop('checked') == true) {
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
                                    jQuery(".subchildrenmenu").each(function () {
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

                                function checkmenuselected(mainid, childid, subchildid, menuid) {
                                    if (subchildid == '-1') {
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


                                jQuery(".menucheckbox").click(function () {
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

                                        }
                                    });
                                    console.log(JSON.stringify(totalArray));
                                    //console.log(totalArray);

                                });

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
                                        $("#vehicle_list").html('');
                                    }
                                });
                                var user = jQuery("#username").val();
                                var userhidden = jQuery("#hiddenusername").val();
                                jQuery("#useredit").html(user);
                                <?php if(isset($user->trinterval) && $user->trinterval > 0){ ?>
                                jQuery("#temprepinterval").val("<?php echo $user->trinterval; ?>");
                                <?php } ?>
                                  <?php if(isset($user->vehinterval) && $user->vehinterval > 0){ ?>
                                jQuery("#vehrepinterval").val("<?php echo $user->vehinterval; ?>");
                                <?php } ?>
                               
                                function copyText() {
                                    src = jQuery("#email1").val();
                                    jQuery("#useredit").html(src);
                                    jQuery("#username").val(src);
                                    if (jQuery("#email1").val() == '') {
                                        jQuery("#useredit").html(userhidden);
                                        jQuery("#username").val(userhidden);
                                    }
                                }
</script>
