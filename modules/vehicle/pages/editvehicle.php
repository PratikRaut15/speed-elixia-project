<?php
$vehicle = getvehicle($_GET['vid']);
$nomens = getNomensList();
$groups = getgroupss();
$masters = getworkmaster();
$checkpoints = getchks();
$fences = getfences();
$mapedchks = getmappedchks($_GET['vid']);
$mapedfences = getmappedfences($_GET['vid']);
$custom = "Vehicle";
if ($_SESSION['customerno'] == 15 || $_SESSION['customerno'] == 21 || $_SESSION['customerno'] == 48) {
    $batch = getbatch($_GET['vid']);
}
if (isset($_SESSION['switch_to']) && $_SESSION['switch_to'] == 3) {
    if (isset($_SESSION['Warehouse'])) {
        $custom = $_SESSION['Warehouse'];
    } else {
        $custom = "Warehouse";
    }
}
?>
<style>
  label{  display: inline-block !important;
  }
</style>
<form  class="form-horizontal well " id="editvehicle"  action="route.php" method="POST" style="width:70%;">
    <input  type="hidden" name="vehicleid" value="<?php echo $_GET['vid']; ?>"  />
<?php include 'panels/editvehicle.php';?>
    <fieldset>
        <div class="control-group">
            <div class="input-prepend ">
                <span class="add-on"><?php echo $vehicles_ses; ?><span class="mandatory">*</span></span><input type="text" name="vehicleno"  value="<?php echo $vehicle->vehicleno; ?>" id="vehicleno" placeholder="Enter <?php echo $vehicles_ses; ?>" autofocus maxlength="40">
            </div>
            <div class="input-prepend ">
                <span class="add-on">Kind </span><select name="type">
                    <option value="Car" <?php if ($vehicle->type == 'Car') {
    echo "selected=selected";
}
?>>Car</option>
                    <option value="Bus" <?php if ($vehicle->type == 'Bus') {
    echo "selected=selected";
}
?>>Bus</option>
                    <option value="Truck" <?php if ($vehicle->type == 'Truck') {
    echo "selected=selected";
}
?>>Truck</option>
                    <option value="Warehouse" <?php if ($vehicle->type == 'Warehouse') {
    echo "selected=selected";
}
?>><?php echo $custom; ?></option>
                </select>
            </div>
        </div>
    </fieldset>
    <fieldset>
        <div class="control-group">
            <div class="input-prepend ">
                <span class="add-on">Group </span><select id="groupid" name="groupid" >
                    <option value="0">Select Group</option>
                    <?php
if (isset($groups)) {
    foreach ($groups as $group) {
        ?><option value='<?php echo $group->groupid; ?>' <?php
if ($vehicle->groupid == $group->groupid) {
            echo "selected=selected";
        }
        ?> ><?php echo $group->groupname; ?></option>
                                    <?php
}
}
?>
                </select>
            </div>
<?php if ($vehicle->type != 'Warehouse') {?>
                <div class="input-prepend ">
                    <span class="add-on">Overspeed Limit</span>
                    <input type="text" name="overspeed_limit" value="<?php echo $vehicle->overspeed_limit; ?>" placeholder="Value" maxlength="3" size="5" /><span class="add-on">Km/Hr</span>
                </div>
<?php }?>
        </div>
    </fieldset>
<?php if ($vehicle->type != 'Warehouse') {?>
        <fieldset>
            <div class="control-group">
                <div class="input-prepend ">
                    <span class="add-on">Fuel Tank Capacity </span><input type="text" name="fuelcapacity" id="fuelcapacity" size="5" value="<?php echo $vehicle->fuelcapacity; ?>" placeholder="Fuel Tank Capacity" maxlength="3"><span class="add-on">Liters </span>
                </div>
                <div class="input-prepend ">
                    <span class="add-on">Average</span>
                    <input type="text" name="average" placeholder="Value" value="<?php echo $vehicle->average; ?>" maxlength="3" size="5" /><span class="add-on">Km/Lt</span>
                </div>
            </div>
        </fieldset>
    <?php }?>
<?php if ($_SESSION['customerno'] == 15 || $_SESSION['customerno'] == 21 || $_SESSION['customerno'] == 48) {
    ?>
        <fieldset>
            <div class="control-group">
                <div class="input-prepend ">
                    <span class="add-on">Batch </span><input type="text" name="batch" id="batch" value="<?php echo $batch->batchno; ?>" size="5" placeholder="Batch" maxlength="15">
                </div>
                <div class="input-prepend ">
                    <span class="add-on">Work Key</span>
                    <input type="text" name="work_key" id="workkey" placeholder="work Key" value="<?php echo $batch->workkey; ?>" maxlength="4" size="5" />
                </div>
            </div>
    <?php if ($_SESSION['customerno'] == 15 || $_SESSION['customerno'] == 21) {
        ?>
                <div class="control-group">
                    <div class="input-prepend">
                        <span class="add-on">Start Date</span>
                        <input id="SDate" name="SDate" type="text"  value="<?php
if ($batch->starttime != '') {
            echo date("d-m-Y", strtotime($batch->starttime));
        }
        ?>"/>
                        <span class="add-on">Start Time</span>
                        <input id="STime" name="STime" type="text" class="input-mini" data-date="<?php
if ($batch->starttime != '') {
            echo date("H:i", strtotime($batch->starttime));
        }
        ?>" value="<?php
if ($batch->starttime != '') {
            echo date("H:i", strtotime($batch->starttime));
        }
        ?>"/>
                        <span class="add-on">Batch </span><input type="text" name="dummybatch" id="dummybatch" value="<?php echo $batch->dummybatchno; ?>" size="5" placeholder="Batch" maxlength="15">
                        <br/><br/>
                        <span class="add-on">Select Master </span>
                        <select id="sel_master" name="sel_master">
                            <option value="0">Select Master</option>
                            <?php
if (isset($masters)) {
            foreach ($masters as $group) {
                ?>
                                    <option value='<?php echo $group->pmid ?>' <?php
if ($batch->pmid == $group->pmid) {
                    echo "selected";
                }
                ?> ><?php echo $group->workkey_name ?> - (<?php echo $group->workkey; ?>)</option>
                                            <?php
}
        }
        ?>
                        </select>
                    </div>
                </div>
        <?php }?>
        </fieldset>
<?php }?>
    <div class="formSep ">
    </div>
<?php if ($vehicle->type != 'Warehouse') {
    ?>
        <fieldset>
            <div class="control-group formSep span7 ">
                <div class="input-prepend ">
                    <span class="add-on">Checkpoint</span><select id="chkid" name="chkid" onchange="addchk()">
                        <option value="-1">Select Checkpoint</option>
                        <?php
if (isset($checkpoints)) {
        foreach ($checkpoints as $checkpoint) {
            echo "<option value='$checkpoint->checkpointid'>$checkpoint->cname</option>";
        }
    }
    ?>
                    </select>
                </div>
                <input type="button" value="Add All Checkpoints" class="btn  btn-primary" onclick="addallchk()">
                <div id="checkpoint_list" >
                    <?php
if (isset($mapedchks)) {
        foreach ($mapedchks as $thischeckpoint) {
            ?>
                            <input type="hidden" class="mappedcheckpoints" id="hid_c<?php echo ($thischeckpoint->checkpointid); ?>" rel= "<?php echo ($thischeckpoint->checkpointid); ?>" value="<?php echo ($thischeckpoint->cname); ?>">
                            <?php
}
    }
    ?>
                </div>
            </div>
            <div class="control-group formSep span5 pull-right">
                <div class="input-prepend ">
                    <span class="add-on">Fence </span><select id="fenceid" name="fenceid" onchange="addfence()">
                        <option value="-1">Select Fence</option>
                        <?php
if (isset($fences)) {
        foreach ($fences as $fence) {
            echo "<option value='$fence->fenceid'>$fence->fencename</option>";
        }
    }
    ?>
                    </select>
                </div>
                <input type="button" value="Add All Fences" class="btn  btn-primary" onclick="addallfence()">
                <div id="fence_list" >
                    <?php
if (isset($mapedfences)) {
        foreach ($mapedfences as $thisfence) {
            ?>
                            <input type="hidden" class="mappedfences" id="hid_f<?php echo ($thisfence->fenceid); ?>" rel="<?php echo ($thisfence->fenceid); ?>" value="<?php echo ($thisfence->fencename); ?>">
                            <?php
}
    }
    ?>
                </div>
            </div>
     <!--        <div class="control-group formSep span6 pull-right">
                <div class="input-prepend">
                    <span class="add-on">Nomenclature </span><select id="nomensid" name="nomensid">
                    <option value="-1">Select Nomens</option>
                    <?php
if (isset($nomens)) {
        foreach ($nomens as $nomen) {
            ?>
                            <option value='<?php echo $nomen->nid; ?>'><?php echo $nomen->nomenname; ?></option>
                        <?php }
    }
    ?>
                    </select>
                </div>
            </div> -->
        </fieldset>
    <?php }?>
    <?php
$tempSensName1 = "Temperature1";
$tempSensName2 = "Temperature2";
$tempSensName3 = "Temperature3";
$tempSensName4 = "Temperature4";
$staticTemp1 = (isset($vehicle->isStaticTemp1) && $vehicle->isStaticTemp1 == 1) ? "checked=true" : '';
$staticTemp2 = (isset($vehicle->isStaticTemp2) && $vehicle->isStaticTemp2 == 1) ? "checked=true" : '';
$staticTemp3 = (isset($vehicle->isStaticTemp3) && $vehicle->isStaticTemp3 == 1) ? "checked=true" : '';
$staticTemp4 = (isset($vehicle->isStaticTemp4) && $vehicle->isStaticTemp4 == 1) ? "checked=true" : '';
if ($_SESSION['temp_sensors'] == 4) {
    ?>
    <div class="input-prepend" style="width:85%;">
         <span class="f_legend"><?php if ($vehicle->n1 == 0) {?> Temperature 1 Limits - <?php
} else {
        echo getName_ByType($vehicle->n1);
    }
    ?>
        </span>
        <span class="add-on">Min.</span>
        <input type="text" name="min_temp1_limit" placeholder="Value" maxlength="5" size="5" value="<?php echo $vehicle->temp1_min; ?>"/><span class="add-on">&deg; C</span>
        <span class="add-on">Max.</span>
        <input type="text" name="max_temp1_limit" placeholder="Value" maxlength="5" size="5" value="<?php echo $vehicle->temp1_max; ?>"/><span class="add-on">&deg; C</span>
        <?php if (isset($_SESSION['switch_to']) && $_SESSION['switch_to'] == 3) {?>
            <span class="add-on">Allowance.</span>
            <input type="text" name="allowance_temp1" placeholder="Value" maxlength="5" size="5" value="<?php echo $vehicle->temp1_allowance; ?>"/><span class="add-on">&deg; C</span>
        <?php }?>
         <span class="add-on">Nomenclature </span>
            <select id="temp1nomensid" name="n1" class="nomens">
                <option value="0">Select Nomens</option>
                <?php
if (isset($nomens)) {
        foreach ($nomens as $nomen) {
            if ($nomen["nid"] == $vehicle->n1) {
                ?>
                    <option value='<?php echo $nomen["nid"]; ?>' selected><?php echo $nomen["name"]; ?></option>
                  <?php } else {
                ?>
                    <option value='<?php echo $nomen["nid"]; ?>'><?php echo $nomen["name"]; ?></option>
                  <?php }
        }
    }
    ?>
           </select>
        <span id="addElem_<?php echo $tempSensName1; ?>" style="cursor: pointer;"><i class="fa fa-plus-square fa-2" onclick="addElements(this,'<?php echo $tempSensName1; ?>')"></i></span>
        &nbsp;&nbsp;&nbsp;<span class="f_legend"> Static Temperature <input type="checkbox" name="chkStaticTemp1" value="1" <?php echo $staticTemp1; ?> ></span>
    </div>
       <br>
    <div class="MainRangeDiv_<?php echo $tempSensName1; ?>" style="display:block">
     <?php if (($vehicle->temp1_range1_start != '' && $vehicle->temp1_range1_start != '0.00') || ($vehicle->temp1_range1_end != '' && $vehicle->temp1_range1_end != '0.00') || ($vehicle->temp1_range1_color != '' && $vehicle->temp1_range1_color != '0.00')) {?>
    <div class="ranges"><br><label class="Slabel1">Start Range 1</label> &nbsp;<input type="number" min="" max="" value="<?php echo $vehicle->temp1_range1_start; ?>" id="str1" name="Temperature1[min_temp_start_1]" style="width:60px;" step=".10" onchange="checkwithMainMinVal(this)">&nbsp;<label class="Elabel1">End Range 1</label>&nbsp;<input type="number" min="" max="" value="<?php echo $vehicle->temp1_range1_end; ?>" id="edr1" name="Temperature1[max_temp_end_1]" style="width:60px;" step=".10" onchange="checkwithMainMaxVal(this)"> &nbsp;<input type="color" value="<?php echo $vehicle->temp1_range1_color; ?>" id="colorpallet1" name="Temperature1[colorpallet1]" style="width:40px;"><br></div>
    <?php }?>
     <?php if (($vehicle->temp1_range2_start != '' && $vehicle->temp1_range2_start != '0.00') || ($vehicle->temp1_range2_end != '' && $vehicle->temp1_range2_end != '0.00') || ($vehicle->temp1_range2_color != '' && $vehicle->temp1_range2_color != '0.00')) {?>
    <div class="ranges"><br><label class="Slabel2">Start Range 2</label> &nbsp;<input type="number" min="" max="" value="<?php echo $vehicle->temp1_range2_start; ?>" id="str2" name="Temperature1[min_temp_start_2]" style="width:60px;" step=".10" onchange="checkwithMainMinVal(this)">&nbsp;<label class="Elabel2">End Range 2</label>&nbsp;<input type="number" min="" max="" value="<?php echo $vehicle->temp1_range2_end; ?>" id="edr2" name="Temperature1[max_temp_end_2]" style="width:60px;" step=".10" onchange="checkwithMainMaxVal(this)"> &nbsp;<input type="color" value="<?php echo $vehicle->temp1_range2_color; ?>" id="colorpallet1" name="Temperature1[colorpallet2]" style="width:40px;"><br></div>
    <?php }?>
     <?php if (($vehicle->temp1_range3_start != '' && $vehicle->temp1_range3_start != '0.00') || ($vehicle->temp1_range3_end != '' && $vehicle->temp1_range3_end != '0.00') || ($vehicle->temp1_range3_color != '' && $vehicle->temp1_range3_color != '0.00')) {?>
    <div class="ranges"><br><label class="Slabel3">Start Range 3</label> &nbsp;<input type="number" min="" max="" value="<?php echo $vehicle->temp1_range3_start; ?>" id="str3" name="Temperature1[min_temp_start_3]" style="width:60px;" step=".10" onchange="checkwithMainMinVal(this)">&nbsp;<label class="Elabel3">End Range 3</label>&nbsp;<input type="number" min="" max="" value="<?php echo $vehicle->temp1_range3_end; ?>" id="edr3" name="Temperature1[max_temp_end_3]" style="width:60px;" step=".10" onchange="checkwithMainMaxVal(this)"> &nbsp;<input type="color" value="<?php echo $vehicle->temp1_range3_color; ?>" id="colorpallet3" name="Temperature1[colorpallet3]" style="width:40px;"><br></div>
    <?php }?>
 <?php if (($vehicle->temp1_range4_start != '' && $vehicle->temp1_range4_start != '0.00') || ($vehicle->temp4_range4_end != '' && $vehicle->temp4_range4_end != '0.00') || ($vehicle->temp4_range4_color != '' && $vehicle->temp4_range4_color != '0.00')) {?>
    <div class="ranges"><br><label class="Slabel4">Start Range 4</label> &nbsp;<input type="number" min="" max="" value="<?php echo $vehicle->temp1_range4_start; ?>" id="str4" name="Temperature1[min_temp_start_4]" style="width:60px;" step=".10" onchange="checkwithMainMinVal(this)">&nbsp;<label class="Elabel4">End Range 4</label>&nbsp;<input type="number" min="" max="" value="<?php echo $vehicle->temp1_range4_end; ?>" id="edr4" name="Temperature1[max_temp_end_4]" style="width:60px;" step=".10" onchange="checkwithMainMaxVal(this)"> &nbsp;<input type="color" value="<?php echo $vehicle->temp1_range4_color; ?>" id="colorpallet4" name="Temperature1[colorpallet4]" style="width:40px;"><br></div>
    <?php }?>
    </div><br/>
    <div class="input-prepend " style="width:85%;">
         <span class="f_legend"><?php if ($vehicle->n2 == 0) {?> Temperature 2 Limits - <?php
} else {
        echo getName_ByType($vehicle->n2);
    }
    ?></span>
        <span class="add-on">Min.</span>
        <input type="text" name="min_temp2_limit" placeholder="Value" maxlength="5" size="5" value="<?php echo $vehicle->temp2_min; ?>"/><span class="add-on">&deg; C</span>
        <span class="add-on">Max.</span>
        <input type="text" name="max_temp2_limit" placeholder="Value" maxlength="5" size="5" value="<?php echo $vehicle->temp2_max; ?>"/><span class="add-on">&deg; C</span>
        <?php if (isset($_SESSION['switch_to']) && $_SESSION['switch_to'] == 3) {?>
            <span class="add-on">Allowance.</span>
            <input type="text" name="allowance_temp2" placeholder="Value" maxlength="5" size="5" value="<?php echo $vehicle->temp2_allowance; ?>"/><span class="add-on">&deg; C</span>
        <?php }?>
        <span class="add-on">Nomenclature </span>
            <select id="temp2nomensid" name="n2" class="nomens">
                <option value="0">Select Nomens</option>
                <?php
if (isset($nomens)) {
        foreach ($nomens as $nomen) {
            if ($nomen["nid"] == $vehicle->n2) {
                ?>
                    <option value='<?php echo $nomen["nid"]; ?>' selected><?php echo $nomen["name"]; ?></option>
                  <?php } else {?>
                    <option value='<?php echo $nomen["nid"]; ?>'><?php echo $nomen["name"]; ?></option>
                  <?php }
        }
    }
    ?>
           </select>
            <span id="addElem_<?php echo $tempSensName2; ?>" style="cursor: pointer;"><i class="fa fa-plus-square fa-2" onclick="addElements(this,'<?php echo $tempSensName2; ?>')"></i></span>
            &nbsp;&nbsp;&nbsp;<span class="f_legend"> Static Temperature <input type="checkbox" name="chkStaticTemp2" value="1" <?php echo $staticTemp2; ?>></span>
    </div>
     <div class="MainRangeDiv_<?php echo $tempSensName2; ?>" style="display:block;">
      <?php if (($vehicle->temp1_range1_start != '' && $vehicle->temp1_range1_start != '0.00') || ($vehicle->temp1_range1_end != '' && $vehicle->temp1_range1_end != '0.00') || ($vehicle->temp1_range1_color != '' && $vehicle->temp1_range1_color != '0.00')) {?>
    <div class="ranges"><br><label class="Slabel1">Start Range 1</label> &nbsp;<input type="number" min="" max="" value="<?php echo $vehicle->temp2_range1_start; ?>" id="str1" name="Temperature2[min_temp_start_1]" style="width:60px;" step=".10" onchange="checkwithMainMinVal(this)">&nbsp;<label class="Elabel1">End Range 1</label>&nbsp;<input type="number" min="" max="" value="<?php echo $vehicle->temp2_range1_end; ?>" id="edr1" name="Temperature2[max_temp_end_1]" style="width:60px;" step=".10" onchange="checkwithMainMaxVal(this)"> &nbsp;<input type="color" value="<?php echo $vehicle->temp2_range1_color; ?>" id="colorpallet1" name="Temperature2[colorpallet1]" style="width:40px;"><br></div>
    <?php }?>
<?php if (($vehicle->temp1_range2_start != '' && $vehicle->temp1_range2_start != '0.00') || ($vehicle->temp1_range2_end != '' && $vehicle->temp1_range2_end != '0.00') || ($vehicle->temp1_range2_color != '' && $vehicle->temp1_range2_color != '0.00')) {?>
    <div class="ranges"><br><label class="Slabel2">Start Range 2</label> &nbsp;<input type="number" min="" max="" value="<?php echo $vehicle->temp2_range2_start; ?>" id="str2" name="Temperature2[min_temp_start_2]" style="width:60px;" step=".10" onchange="checkwithMainMinVal(this)">&nbsp;<label class="Elabel2">End Range 2</label>&nbsp;<input type="number" min="" max="" value="<?php echo $vehicle->temp2_range2_end; ?>" id="edr2" name="Temperature2[max_temp_end_2]" style="width:60px;" step=".10" onchange="checkwithMainMaxVal(this)"> &nbsp;<input type="color" value="<?php echo $vehicle->temp2_range2_color; ?>" id="colorpallet2" name="Temperature2[colorpallet2]" style="width:40px;"><br></div>
    <?php }?>
<?php if (($vehicle->temp1_range3_start != '' && $vehicle->temp1_range3_start != '0.00') || ($vehicle->temp1_range3_end != '' && $vehicle->temp1_range3_end != '0.00') || ($vehicle->temp1_range3_color != '' && $vehicle->temp1_range3_color != '0.00')) {?>
    <div class="ranges"><br><label class="Slabel3">Start Range 3</label> &nbsp;<input type="number" min="" max="" value="<?php echo $vehicle->temp2_range3_start; ?>" id="str3" name="Temperature2[min_temp_start_3]" style="width:60px;" step=".10" onchange="checkwithMainMinVal(this)">&nbsp;<label class="Elabel3">End Range 3</label>&nbsp;<input type="number" min="" max="" value="<?php echo $vehicle->temp2_range3_end; ?>" id="edr3" name="Temperature2[max_temp_end_3]" style="width:60px;" step=".10" onchange="checkwithMainMaxVal(this)"> &nbsp;<input type="color" value="<?php echo $vehicle->temp2_range3_color; ?>" id="colorpallet3" name="Temperature2[colorpallet3]" style="width:40px;"><br></div>
<?php }?>
<?php if (($vehicle->temp1_range4_start != '' && $vehicle->temp1_range4_start != '0.00') || ($vehicle->temp1_range4_end != '' && $vehicle->temp1_range4_end != '0.00') || ($vehicle->temp1_range4_color != '' && $vehicle->temp1_range4_color != '0.00')) {?>
    <div class="ranges"><br><label class="Slabel4">Start Range 4</label> &nbsp;<input type="number" min="" max="" value="<?php echo $vehicle->temp2_range4_start; ?>" id="str4" name="Temperature2[min_temp_start_4]" style="width:60px;" step=".10" onchange="checkwithMainMinVal(this)">&nbsp;<label class="Elabel4">End Range 4</label>&nbsp;<input type="number" min="" max="" value="<?php echo $vehicle->temp2_range4_end; ?>" id="edr4" name="Temperature2[max_temp_end_4]" style="width:60px;" step=".10" onchange="checkwithMainMaxVal(this)"> &nbsp;<input type="color" value="<?php echo $vehicle->temp2_range4_color; ?>" id="colorpallet4" name="Temperature2[colorpallet4]" style="width:40px;"><br></div>
    <?php }?>
    </div><br>
    <div class="input-prepend " style="width:85%;">
        <span class="f_legend"><?php if ($vehicle->n3 == 0) {?> Temperature 3 Limits - <?php
} else {
        echo getName_ByType($vehicle->n3);
    }
    ?></span>
        <span class="add-on">Min.</span>
        <input type="text" name="min_temp3_limit" placeholder="Value" maxlength="5" size="5" value="<?php echo $vehicle->temp3_min; ?>"/><span class="add-on">&deg; C</span>
        <span class="add-on">Max.</span>
        <input type="text" name="max_temp3_limit" placeholder="Value" maxlength="5" size="5" value="<?php echo $vehicle->temp3_max; ?>"/><span class="add-on">&deg; C</span>
        <?php if (isset($_SESSION['switch_to']) && $_SESSION['switch_to'] == 3) {?>
            <span class="add-on">Allowance.</span>
            <input type="text" name="allowance_temp3" placeholder="Value" maxlength="5" size="5" value="<?php echo $vehicle->temp3_allowance; ?>"/><span class="add-on">&deg; C</span>
        <?php }?>
        <span class="add-on">Nomenclature </span>
        <select id="temp3nomensid" name="n3" class="nomens">
            <option value="0">Select Nomens</option>
            <?php
if (isset($nomens)) {
        foreach ($nomens as $nomen) {
            if ($nomen["nid"] == $vehicle->n3) {
                ?>
                    <option value='<?php echo $nomen["nid"]; ?>' selected><?php echo $nomen["name"]; ?></option>
                  <?php } else {?>
                <option value='<?php echo $nomen["nid"]; ?>'><?php echo $nomen["name"]; ?></option>
              <?php }
        }
    }
    ?>
        </select>
            <span id="addElem_<?php echo $tempSensName3; ?>" style="cursor: pointer;"><i class="fa fa-plus-square fa-2" onclick="addElements(this,'<?php echo $tempSensName3; ?>')"></i></span>
            &nbsp;&nbsp;&nbsp;<span class="f_legend"> Static Temperature <input type="checkbox" name="chkStaticTemp3" value="1" <?php echo $staticTemp3; ?>></span>
    </div>
     <div class="MainRangeDiv_<?php echo $tempSensName3; ?>" style="display:block;">
     <?php if (($vehicle->temp1_range2_start != '' && $vehicle->temp1_range2_start != '0.00') || ($vehicle->temp1_range2_end != '' && $vehicle->temp1_range2_end != '0.00') || ($vehicle->temp1_range2_color != '' && $vehicle->temp1_range2_color != '0.00')) {?>
    <div class="ranges"><br><label class="Slabel1">Start Range 1</label> &nbsp;<input type="number" min="" max="" value="<?php echo $vehicle->temp3_range1_start; ?>" id="str1" name="Temperature3[min_temp_start_1]" style="width:60px;" step=".10" onchange="checkwithMainMinVal(this)">&nbsp;<label class="Elabel1">End Range 1</label>&nbsp;<input type="number" min="" max="" value="<?php echo $vehicle->temp3_range1_end; ?>" id="edr1" name="Temperature3[max_temp_end_1]" style="width:60px;" step=".10" onchange="checkwithMainMaxVal(this)"> &nbsp;<input type="color" value="<?php echo $vehicle->temp3_range1_color; ?>" id="colorpallet1" name="Temperature3[colorpallet1]" style="width:40px;"><br></div>
    <?php }?>
    <?php if (($vehicle->temp1_range2_start != '' && $vehicle->temp1_range2_start != '0.00') || ($vehicle->temp1_range2_end != '' && $vehicle->temp1_range2_end != '0.00') || ($vehicle->temp1_range2_color != '' && $vehicle->temp1_range2_color != '0.00')) {?>
    <div class="ranges"><br><label class="Slabel2">Start Range 2</label> &nbsp;<input type="number" min="" max="" value="<?php echo $vehicle->temp3_range2_start; ?>" id="str2" name="Temperature3[min_temp_start_2]" style="width:60px;" step=".10" onchange="checkwithMainMinVal(this)">&nbsp;<label class="Elabel2">End Range 2</label>&nbsp;<input type="number" min="" max="" value="<?php echo $vehicle->temp3_range2_end; ?>" id="edr2" name="Temperature3[max_temp_end_2]" style="width:60px;" step=".10" onchange="checkwithMainMaxVal(this)"> &nbsp;<input type="color" value="<?php echo $vehicle->temp3_range2_color; ?>" id="colorpallet2" name="Temperature3[colorpallet2]" style="width:40px;"><br></div>
    <?php }?>
    <?php if (($vehicle->temp1_range2_start != '' && $vehicle->temp1_range2_start != '0.00') || ($vehicle->temp1_range2_end != '' && $vehicle->temp1_range2_end != '0.00') || ($vehicle->temp1_range2_color != '' && $vehicle->temp1_range2_color != '0.00')) {?>
    <div class="ranges"><br><label class="Slabel3">Start Range 3</label> &nbsp;<input type="number" min="" max="" value="<?php echo $vehicle->temp3_range3_start; ?>" id="str3" name="Temperature3[min_temp_start_3]" style="width:60px;" step=".10" onchange="checkwithMainMinVal(this)">&nbsp;<label class="Elabel3">End Range 3</label>&nbsp;<input type="number" min="" max="" value="<?php echo $vehicle->temp3_range3_end; ?>" id="edr3" name="Temperature3[max_temp_end_3]" style="width:60px;" step=".10" onchange="checkwithMainMaxVal(this)"> &nbsp;<input type="color" value="<?php echo $vehicle->temp3_range3_color; ?>" id="colorpallet3" name="Temperature3[colorpallet3]" style="width:40px;"><br></div>
    <?php }?>
     <?php if (($vehicle->temp1_range2_start != '' && $vehicle->temp1_range2_start != '0.00') || ($vehicle->temp1_range2_end != '' && $vehicle->temp1_range2_end != '0.00') || ($vehicle->temp1_range2_color != '' && $vehicle->temp1_range2_color != '0.00')) {?>
    <div class="ranges"><br><label class="Slabel4">Start Range 4</label> &nbsp;<input type="number" min="" max="" value="<?php echo $vehicle->temp3_range4_start; ?>" id="str4" name="Temperature3[min_temp_start_4]" style="width:60px;" step=".10" onchange="checkwithMainMinVal(this)">&nbsp;<label class="Elabel4">End Range 4</label>&nbsp;<input type="number" min="" max="" value="<?php echo $vehicle->temp3_range4_end; ?>" id="edr4" name="Temperature3[max_temp_end_4]" style="width:60px;" step=".10" onchange="checkwithMainMaxVal(this)"> &nbsp;<input type="color" value="<?php echo $vehicle->temp3_range4_color; ?>" id="colorpallet4" name="Temperature3[colorpallet4]" style="width:40px;"><br></div>
    <?php }?>
    </div><br>
    <div class="input-prepend " style="width:85%;">
         <span class="f_legend"><?php if ($vehicle->n4 == 0) {?> Temperature 4 Limits - <?php
} else {
        echo getName_ByType($vehicle->n4);
    }
    ?></span>
        <span class="add-on">Min.</span>
        <input type="text" name="min_temp4_limit" placeholder="Value" maxlength="5" size="5" value="<?php echo $vehicle->temp4_min; ?>"/><span class="add-on">&deg; C</span>
        <span class="add-on">Max.</span>
        <input type="text" name="max_temp4_limit" placeholder="Value" maxlength="5" size="5" value="<?php echo $vehicle->temp4_max; ?>"/><span class="add-on">&deg; C</span>
        <?php if (isset($_SESSION['switch_to']) && $_SESSION['switch_to'] == 3) {?>
            <span class="add-on">Allowance.</span>
            <input type="text" name="allowance_temp4" placeholder="Value" maxlength="5" size="5" value="<?php echo $vehicle->temp4_allowance; ?>"/><span class="add-on">&deg; C</span>
        <?php }?>
        <span class="add-on">Nomenclature </span>
        <select id="temp4nomensid" name="n4" class="nomens">
            <option value="0">Select Nomens</option>
            <?php
if (isset($nomens)) {
        foreach ($nomens as $nomen) {
            if ($nomen["nid"] == $vehicle->n4) {
                ?>
                    <option value='<?php echo $nomen["nid"]; ?>' selected><?php echo $nomen["name"]; ?></option>
                  <?php } else {?>
                <option value='<?php echo $nomen["nid"]; ?>'><?php echo $nomen["name"]; ?></option>
              <?php }
        }
    }
    ?>
        </select>
            <span id="addElem_<?php echo $tempSensName4; ?>" style="cursor: pointer;"><i class="fa fa-plus-square fa-2" onclick="addElements(this,'<?php echo $tempSensName4; ?>')"></i></span>
            &nbsp;&nbsp;&nbsp;<span class="f_legend"> Static Temperature <input type="checkbox" name="chkStaticTemp4" value="1" <?php echo $staticTemp4; ?>></span>
    </div>
     <div class="MainRangeDiv_<?php echo $tempSensName4; ?>" style="display:block;">
    <?php if (($vehicle->temp4_range1_start != '' && $vehicle->temp4_range1_start != '0.00') || ($vehicle->temp4_range1_end != '' && $vehicle->temp4_range1_end != '0.00') || ($vehicle->temp4_range1_color != '' && $vehicle->temp4_range1_color != '0.00')) {?>
    <div class="ranges"><br><label class="Slabel1">Start Range 1</label> &nbsp;
    <input type="number" min="" max="" value="<?php echo $vehicle->temp4_range1_start; ?>" id="str1" name="Temperature4[min_temp_start_1]" style="width:60px;" step=".10" onchange="checkwithMainMinVal(this)">&nbsp;
  <label class="Elabel1">End Range 1</label>&nbsp;
    <input type="number" min="" max="" value="<?php echo $vehicle->temp4_range1_end; ?>" id="edr1" name="Temperature4[max_temp_end_1]" style="width:60px;" step=".10" onchange="checkwithMainMaxVal(this)"> &nbsp;
    <input type="color" value="<?php echo $vehicle->temp4_range1_color; ?>" id="colorpallet1" name="Temperature4[colorpallet1]" style="width:40px;"><br></div><?php }?>
 <?php if (($vehicle->temp4_range2_start != '' && $vehicle->temp4_range2_start != '0.00') || ($vehicle->temp4_range2_end != '' && $vehicle->temp4_range2_end != '0.00') || ($vehicle->temp4_range2_color != '' && $vehicle->temp4_range2_color != '0.00')) {?>
    <div class="ranges"><br><label class="Slabel2">Start Range 2</label> &nbsp;<input type="number" min="" max="" value="<?php echo $vehicle->temp4_range2_start; ?>" id="str2" name="Temperature4[min_temp_start_2]" style="width:60px;" step=".10" onchange="checkwithMainMinVal(this)">&nbsp;<label class="Elabel2">End Range 2</label>&nbsp;<input type="number" min="" max="" value="<?php echo $vehicle->temp4_range2_end; ?>" id="edr2" name="Temperature4[max_temp_end_2]" style="width:60px;" step=".10" onchange="checkwithMainMaxVal(this)"> &nbsp;<input type="color" value="<?php echo $vehicle->temp4_range2_color; ?>" id="colorpallet2" name="Temperature4[colorpallet2]" style="width:40px;"><br></div>
<?php }?>
 <?php if (($vehicle->temp4_range3_start != '' && $vehicle->temp4_range3_start != '0.00') || ($vehicle->temp4_range3_end != '' && $vehicle->temp4_range3_end != '0.00') || ($vehicle->temp4_range3_color != '' && $vehicle->temp4_range3_color != '0.00')) {?>
    <div class="ranges"><br><label class="Slabel3">Start Range 3</label> &nbsp;<input type="number" min="" max="" value="<?php echo $vehicle->temp4_range3_start; ?>" id="str3" name="Temperature4[min_temp_start_3]" style="width:60px;" step=".10" onchange="checkwithMainMinVal(this)">&nbsp;<label class="Elabel3">End Range 3</label>&nbsp;<input type="number" min="" max="" value="<?php echo $vehicle->temp4_range3_end; ?>" id="edr3" name="Temperature4[max_temp_end_3]" style="width:60px;" step=".10" onchange="checkwithMainMaxVal(this)"> &nbsp;<input type="color" value="<?php echo $vehicle->temp4_range3_color; ?>" id="colorpallet3" name="Temperature4[colorpallet3]" style="width:40px;"><br></div>
    <?php }?>
     <?php if (($vehicle->temp4_range4_start != '' && $vehicle->temp4_range4_start != '0.00') || ($vehicle->temp4_range4_end != '' && $vehicle->temp4_range4_end != '0.00') || ($vehicle->temp4_range4_color != '' && $vehicle->temp4_range4_color != '0.00')) {?>
    <div class="ranges"><br><label class="Slabel4">Start Range 4</label> &nbsp;<input type="number" min="" max="" value="<?php echo $vehicle->temp4_range4_start; ?>" id="str4" name="Temperature4[min_temp_start_4]" style="width:60px;" step=".10" onchange="checkwithMainMinVal(this)">&nbsp;<label class="Elabel4">End Range 4</label>&nbsp;<input type="number" min="" max="" value="<?php echo $vehicle->temp4_range4_end; ?>" id="edr4" name="Temperature4[max_temp_end_4]" style="width:60px;" step=".10" onchange="checkwithMainMaxVal(this)"> &nbsp;<input type="color" value="<?php echo $vehicle->temp4_range4_color; ?>" id="colorpallet4" name="Temperature4[colorpallet4]" style="width:40px;"><br></div>
    <?php }?>
    </div><br>
    <div class="formSep ">
    </div>
    <?php
}
if ($_SESSION['temp_sensors'] == 3) {
    ?>
    <div class="input-prepend " style="width:85%;">
        <span class="f_legend"> <?php if ($vehicle->n1 == 0) {?> Temperature 1 Limits - <?php
} else {
        echo getName_ByType($vehicle->n1);
    }
    ?></span>
        <span class="add-on">Min.</span>
        <input type="text" name="min_temp1_limit" placeholder="Value" maxlength="5" size="5" value="<?php echo $vehicle->temp1_min; ?>"/><span class="add-on">&deg; C</span>
        <span class="add-on">Max.</span>
        <input type="text" name="max_temp1_limit" placeholder="Value" maxlength="5" size="5" value="<?php echo $vehicle->temp1_max; ?>"/><span class="add-on">&deg; C</span>
        <?php if (isset($_SESSION['switch_to']) && $_SESSION['switch_to'] == 3) {?>
            <span class="add-on">Allowance.</span>
            <input type="text" name="allowance_temp1" placeholder="Value" maxlength="5" size="5" value="<?php echo $vehicle->temp1_allowance; ?>"/><span class="add-on">&deg; C</span>
        <?php }?>
            <span class="add-on">Nomenclature </span>
        <select id="temp1nomensid" name="n1" class="nomens">
            <option value="0">Select Nomens</option>
            <?php
if (isset($nomens)) {
        foreach ($nomens as $nomen) {
            if ($nomen["nid"] == $vehicle->n1) {
                ?>
                    <option value='<?php echo $nomen["nid"]; ?>' selected><?php echo $nomen["name"]; ?></option>
                  <?php } else {?>
                <option value='<?php echo $nomen["nid"]; ?>'><?php echo $nomen["name"]; ?></option>
              <?php }
        }
    }
    ?>
        </select>
        <span id="addElem_<?php echo $tempSensName1; ?>" style="cursor: pointer;"><i class="fa fa-plus-square fa-2" onclick="addElements(this,'<?php echo $tempSensName1; ?>')"></i></span>
        &nbsp;&nbsp;&nbsp;<span class="f_legend"> Static Temperature <input type="checkbox" name="chkStaticTemp1" value="1" <?php echo $staticTemp1; ?> ></span>
    </div>
     <div class="MainRangeDiv_<?php echo $tempSensName1; ?>" style="display:block;">
     <?php if (($vehicle->temp1_range1_start != '' && $vehicle->temp1_range1_start != '0.00') || ($vehicle->temp1_range1_end != '' && $vehicle->temp1_range1_end != '0.00') || ($vehicle->temp1_range1_color != '' && $vehicle->temp1_range1_color != '0.00')) {?>
      <div class="ranges"><br><label class="Slabel1">Start Range 1</label> &nbsp;<input type="number" min="" max="" value="<?php echo $vehicle->temp1_range1_start; ?>" id="str1" name="Temperature1[min_temp_start_1]" style="width:60px;" step=".10" onchange="checkwithMainMinVal(this)">&nbsp;<label class="Elabel1">End Range 1</label>&nbsp;<input type="number" min="" max="" value="<?php echo $vehicle->temp1_range1_end; ?>" id="edr1" name="Temperature1[max_temp_end_1]" style="width:60px;" step=".10" onchange="checkwithMainMaxVal(this)"> &nbsp;<input type="color" value="<?php echo $vehicle->temp1_range1_color; ?>" id="colorpallet1" name="Temperature1[colorpallet1]" style="width:40px;"><br></div>
      <?php }?>
      <?php if (($vehicle->temp1_range2_start != '' && $vehicle->temp1_range2_start != '0.00') || ($vehicle->temp1_range2_end != '' && $vehicle->temp1_range2_end != '0.00') || ($vehicle->temp1_range2_color != '' && $vehicle->temp1_range2_color != '0.00')) {?>
    <div class="ranges"><br><label class="Slabel2">Start Range 2</label> &nbsp;<input type="number" min="" max="" value="<?php echo $vehicle->temp1_range2_start; ?>" id="str2" name="Temperature1[min_temp_start_2]" style="width:60px;" step=".10" onchange="checkwithMainMinVal(this)">&nbsp;<label class="Elabel2">End Range 2</label>&nbsp;<input type="number" min="" max="" value="<?php echo $vehicle->temp1_range2_end; ?>" id="edr2" name="Temperature1[max_temp_end_2]" style="width:60px;" step=".10" onchange="checkwithMainMaxVal(this)"> &nbsp;<input type="color" value="<?php echo $vehicle->temp1_range2_color; ?>" id="colorpallet2" name="Temperature1[colorpallet2]" style="width:40px;"><br></div>
    <?php }?>
    <?php if (($vehicle->temp1_range3_start != '' && $vehicle->temp1_range3_start != '0.00') || ($vehicle->temp1_range3_end != '' && $vehicle->temp1_range3_end != '0.00') || ($vehicle->temp1_range3_color != '' && $vehicle->temp1_range3_color != '0.00')) {?>
    <div class="ranges"><br><label class="Slabel3">Start Range 3</label> &nbsp;<input type="number" min="" max="" value="<?php echo $vehicle->temp1_range3_start; ?>" id="str3" name="Temperature1[min_temp_start_3]" style="width:60px;" step=".10" onchange="checkwithMainMinVal(this)">&nbsp;<label class="Elabel3">End Range 3</label>&nbsp;<input type="number" min="" max="" value="<?php echo $vehicle->temp1_range3_end; ?>" id="edr3" name="Temperature1[max_temp_end_3]" style="width:60px;" step=".10" onchange="checkwithMainMaxVal(this)"> &nbsp;<input type="color" value="<?php echo $vehicle->temp1_range3_color; ?>" id="colorpallet3" name="Temperature1[colorpallet3]" style="width:40px;"><br></div>
    <?php }?>
    <?php if (isset($vehicle->temp1_range4_start) && isset($vehicle->temp1_range4_end) && ($vehicle->temp1_range4_start != '' && $vehicle->temp1_range4_start != '0.00') || ($vehicle->temp1_range4_end != '' && $vehicle->temp1_range4_end != '0.00') || ($vehicle->temp1_range4_color != '' && $vehicle->temp1_range4_color != '0.00')) {?>
    <div class="ranges"><br><label class="Slabel4">Start Range 4</label> &nbsp;<input type="number" min="" max="" value="<?php echo $vehicle->temp1_range4_start; ?>" id="str4" name="Temperature1[min_temp_start_4]" style="width:60px;" step=".10" onchange="checkwithMainMinVal(this)">&nbsp;<label class="Elabel4">End Range 4</label>&nbsp;<input type="number" min="" max="" value="<?php echo $vehicle->temp1_range4_end; ?>" id="edr4" name="Temperature1[max_temp_end_4]" style="width:60px;" step=".10" onchange="checkwithMainMaxVal(this)"> &nbsp;<input type="color" value="<?php echo $vehicle->temp1_range4_color; ?>" id="colorpallet4" name="Temperature1[colorpallet4]" style="width:40px;"><br></div>
    <?php }?>
    </div><br>
    <div class="input-prepend " style="width:85%;">
         <span class="f_legend"> <?php if ($vehicle->n2 == 0) {?> Temperature 2 Limits -<?php
} else {
        echo getName_ByType($vehicle->n2);
    }
    ?></span>
        <span class="add-on">Min.</span>
        <input type="text" name="min_temp2_limit" placeholder="Value" maxlength="5" size="5" value="<?php echo $vehicle->temp2_min; ?>"/><span class="add-on">&deg; C</span>
        <span class="add-on">Max.</span>
        <input type="text" name="max_temp2_limit" placeholder="Value" maxlength="5" size="5" value="<?php echo $vehicle->temp2_max; ?>"/><span class="add-on">&deg; C</span>
        <?php if (isset($_SESSION['switch_to']) && $_SESSION['switch_to'] == 3) {?>
            <span class="add-on">Allowance.</span>
            <input type="text" name="allowance_temp2" placeholder="Value" maxlength="5" size="5" value="<?php echo $vehicle->temp2_allowance; ?>"/><span class="add-on">&deg; C</span>
        <?php }?>
        <span class="add-on">Nomenclature </span>
        <select id="temp2nomensid" name="n2" class="nomens">
            <option value="0">Select Nomens</option>
            <?php
if (isset($nomens)) {
        foreach ($nomens as $nomen) {
            if ($nomen["nid"] == $vehicle->n2) {
                ?>
                    <option value='<?php echo $nomen["nid"]; ?>' selected><?php echo $nomen["name"]; ?></option>
                  <?php } else {?>
                <option value='<?php echo $nomen["nid"]; ?>'><?php echo $nomen["name"]; ?></option>
              <?php }
        }
    }
    ?>
        </select>
              <span id="addElem_<?php echo $tempSensName2; ?>" style="cursor: pointer;"><i class="fa fa-plus-square fa-2" onclick="addElements(this,'<?php echo $tempSensName2; ?>')"></i></span>
              &nbsp;&nbsp;&nbsp;<span class="f_legend"> Static Temperature <input type="checkbox" name="chkStaticTemp2" value="1" <?php echo $staticTemp2; ?> ></span>
    </div>
     <div class="MainRangeDiv_<?php echo $tempSensName2; ?>" style="display:block;">
     <?php if (($vehicle->temp2_range1_start != '' && $vehicle->temp2_range1_start != '0.00') || ($vehicle->temp2_range1_end != '' && $vehicle->temp2_range1_end != '0.00') || ($vehicle->temp2_range1_color != '' && $vehicle->temp2_range1_color != '0.00')) {?>
     <div class="ranges"><br><label class="Slabel1">Start Range 1</label> &nbsp;<input type="number" min="" max="" value="<?php echo $vehicle->temp2_range1_start; ?>" id="str1" name="Temperature2[min_temp_start_1]" style="width:60px;" step=".10" onchange="checkwithMainMinVal(this)">&nbsp;<label class="Elabel1">End Range 1</label>&nbsp;<input type="number" min="" max="" value="<?php echo $vehicle->temp2_range1_end; ?>" id="edr1" name="Temperature2[max_temp_end_1]" style="width:60px;" step=".10" onchange="checkwithMainMaxVal(this)"> &nbsp;<input type="color" value="<?php echo $vehicle->temp2_range1_color; ?>" id="colorpallet1" name="Temperature2[colorpallet1]" style="width:40px;"><br></div><?php }?>
<?php if (($vehicle->temp2_range2_start != '' && $vehicle->temp2_range2_start != '0.00') || ($vehicle->temp2_range2_end != '' && $vehicle->temp2_range2_end != '0.00') || ($vehicle->temp2_range2_color != '' && $vehicle->temp2_range2_color != '0.00')) {?>
    <div class="ranges"><br><label class="Slabel2">Start Range 2</label> &nbsp;<input type="number" min="" max="" value="<?php echo $vehicle->temp2_range2_start; ?>" id="str2" name="Temperature2[min_temp_start_2]" style="width:60px;" step=".10" onchange="checkwithMainMinVal(this)">&nbsp;<label class="Elabel2">End Range 2</label>&nbsp;<input type="number" min="" max="" value="<?php echo $vehicle->temp2_range2_end; ?>" id="edr2" name="Temperature2[max_temp_end_2]" style="width:60px;" step=".10" onchange="checkwithMainMaxVal(this)"> &nbsp;<input type="color" value="<?php echo $vehicle->temp2_range2_color; ?>" id="colorpallet2" name="Temperature2[colorpallet2]" style="width:40px;"><br></div>
    <?php }?>
<?php if (($vehicle->temp2_range3_start != '' && $vehicle->temp2_range3_start != '0.00') || ($vehicle->temp2_range3_end != '' && $vehicle->temp2_range3_end != '0.00') || ($vehicle->temp2_range3_color != '' && $vehicle->temp2_range3_color != '0.00')) {?>
    <div class="ranges"><br><label class="Slabel3">Start Range 3</label> &nbsp;<input type="number" min="" max="" value="<?php echo $vehicle->temp2_range3_start; ?>" id="str3" name="Temperature2[min_temp_start_3]" style="width:60px;" step=".10" onchange="checkwithMainMinVal(this)">&nbsp;<label class="Elabel3">End Range 3</label>&nbsp;<input type="number" min="" max="" value="<?php echo $vehicle->temp2_range3_end; ?>" id="edr3" name="Temperature2[max_temp_end_3]" style="width:60px;" step=".10" onchange="checkwithMainMaxVal(this)"> &nbsp;<input type="color" value="<?php echo $vehicle->temp2_range3_color; ?>" id="colorpallet3" name="Temperature2[colorpallet3]" style="width:40px;"><br></div>
    <?php }?>
<?php if (($vehicle->temp2_range4_start != '' && $vehicle->temp2_range4_start != '0.00') || ($vehicle->temp2_range4_end != '' && $vehicle->temp2_range4_end != '0.00') || ($vehicle->temp2_range4_color != '' && $vehicle->temp2_range4_color != '0.00')) {?>
    <div class="ranges"><br><label class="Slabel4">Start Range 4</label> &nbsp;<input type="number" min="" max="" value="<?php echo $vehicle->temp2_range4_start; ?>" id="str4" name="Temperature2[min_temp_start_4]" style="width:60px;" step=".10" onchange="checkwithMainMinVal(this)">&nbsp;<label class="Elabel4">End Range 4</label>&nbsp;<input type="number" min="" max="" value="<?php echo $vehicle->temp2_range4_end; ?>" id="edr4" name="Temperature2[max_temp_end_4]" style="width:60px;" step=".10" onchange="checkwithMainMaxVal(this)"> &nbsp;<input type="color" value="<?php echo $vehicle->temp2_range4_color; ?>" id="colorpallet4" name="Temperature2[colorpallet4]" style="width:40px;"><br></div>
    <?php }?>
    </div><br>
    <div class="input-prepend " style="width:85%;">
         <span class="f_legend"><?php if ($vehicle->n3 == 0) {?> Temperature 3 Limits -<?php
} else {
        echo getName_ByType($vehicle->n3);
    }
    ?></span>
        <span class="add-on">Min.</span>
        <input type="text" name="min_temp3_limit" placeholder="Value" maxlength="5" size="5" value="<?php echo $vehicle->temp3_min; ?>"/><span class="add-on">&deg; C</span>
        <span class="add-on">Max.</span>
        <input type="text" name="max_temp3_limit" placeholder="Value" maxlength="5" size="5" value="<?php echo $vehicle->temp3_max; ?>"/><span class="add-on">&deg; C</span>
        <?php if (isset($_SESSION['switch_to']) && $_SESSION['switch_to'] == 3) {?>
            <span class="add-on">Allowance.</span>
            <input type="text" name="allowance_temp3" placeholder="Value" maxlength="5" size="5" value="<?php echo $vehicle->temp3_allowance; ?>"/><span class="add-on">&deg; C</span>
        <?php }?>
        <span class="add-on">Nomenclature </span>
        <select id="temp3nomensid" name="n3" class="nomens">
            <option value="0">Select Nomens</option>
            <?php
if (isset($nomens)) {
        foreach ($nomens as $nomen) {
            if ($nomen["nid"] == $vehicle->n3) {
                ?>
                    <option value='<?php echo $nomen["nid"]; ?>' selected><?php echo $nomen["name"]; ?></option>
                  <?php } else {?>
                <option value='<?php echo $nomen["nid"]; ?>'><?php echo $nomen["name"]; ?></option>
              <?php }
        }
    }
    ?>
        </select>
         <span id="addElem_<?php echo $tempSensName3; ?>" style="cursor: pointer;"><i class="fa fa-plus-square fa-2" onclick="addElements(this,'<?php echo $tempSensName3; ?>')"></i></span>
         &nbsp;&nbsp;&nbsp;<span class="f_legend"> Static Temperature <input type="checkbox" name="chkStaticTemp3" value="1" <?php echo $staticTemp3; ?> ></span>
    </div>
     <div class="MainRangeDiv_<?php echo $tempSensName3; ?>" style="display:block;">
     <?php if (($vehicle->temp3_range1_start != '' && $vehicle->temp3_range1_start != '0.00') || ($vehicle->temp3_range1_end != '' && $vehicle->temp3_range1_end != '0.00') || ($vehicle->temp3_range1_color != '' && $vehicle->temp3_range1_color != '0.00')) {?>
    <div class="ranges"><br><label class="Slabel1">Start Range 1</label> &nbsp;<input type="number" min="" max="" value="<?php echo $vehicle->temp3_range1_start; ?>" id="str1" name="Temperature3[min_temp_start_1]" style="width:60px;" step=".10" onchange="checkwithMainMinVal(this)">&nbsp;<label class="Elabel1">End Range 1</label>&nbsp;<input type="number" min="" max="" value="<?php echo $vehicle->temp3_range1_end; ?>" id="edr1" name="Temperature3[max_temp_end_1]" style="width:60px;" step=".10" onchange="checkwithMainMaxVal(this)"> &nbsp;<input type="color" value="<?php echo $vehicle->temp3_range1_color; ?>" id="colorpallet1" name="Temperature3[colorpallet1]" style="width:40px;"><br></div>
    <?php }?>
 <?php if (($vehicle->temp3_range2_start != '' && $vehicle->temp3_range2_start != '0.00') || ($vehicle->temp3_range2_end != '' && $vehicle->temp3_range2_end != '0.00') || ($vehicle->temp3_range2_color != '' && $vehicle->temp3_range2_color != '0.00')) {?>
    <div class="ranges"><br><label class="Slabel2">Start Range 2</label> &nbsp;<input type="number" min="" max="" value="<?php echo $vehicle->temp3_range2_start; ?>" id="str2" name="Temperature3[min_temp_start_2]" style="width:60px;" step=".10" onchange="checkwithMainMinVal(this)">&nbsp;<label class="Elabel2">End Range 2</label>&nbsp;<input type="number" min="" max="" value="<?php echo $vehicle->temp3_range2_end; ?>" id="edr2" name="Temperature3[max_temp_end_2]" style="width:60px;" step=".10" onchange="checkwithMainMaxVal(this)"> &nbsp;<input type="color" value="<?php echo $vehicle->temp3_range2_color; ?>" id="colorpallet2" name="Temperature3[colorpallet2]" style="width:40px;"><br></div>
    <?php }?>
<?php if (($vehicle->temp3_range3_start != '' && $vehicle->temp3_range3_start != '0.00') || ($vehicle->temp3_range3_end != '' && $vehicle->temp3_range3_end != '0.00') || ($vehicle->temp3_range3_color != '' && $vehicle->temp3_range3_color != '0.00')) {?>
    <div class="ranges"><br><label class="Slabel3">Start Range 3</label> &nbsp;<input type="number" min="" max="" value="<?php echo $vehicle->temp3_range3_start; ?>" id="str3" name="Temperature3[min_temp_start_3]" style="width:60px;" step=".10" onchange="checkwithMainMinVal(this)">&nbsp;<label class="Elabel3">End Range 3</label>&nbsp;<input type="number" min="" max="" value="<?php echo $vehicle->temp3_range3_end; ?>" id="edr3" name="Temperature3[max_temp_end_3]" style="width:60px;" step=".10" onchange="checkwithMainMaxVal(this)"> &nbsp;<input type="color" value="<?php echo $vehicle->temp3_range3_color; ?>" id="colorpallet3" name="Temperature3[colorpallet3]" style="width:40px;"><br></div>
    <?php }?>
<?php if (($vehicle->temp3_range4_start != '' && $vehicle->temp3_range4_start != '0.00') || ($vehicle->temp3_range4_end != '' && $vehicle->temp3_range4_end != '0.00') || ($vehicle->temp3_range4_color != '' && $vehicle->temp3_range4_color != '0.00')) {?>
    <div class="ranges"><br><label class="Slabel4">Start Range 4</label> &nbsp;<input type="number" min="" max="" value="<?php echo $vehicle->temp3_range4_start; ?>" id="str4" name="Temperature3[min_temp_start_4]" style="width:60px;" step=".10" onchange="checkwithMainMinVal(this)">&nbsp;<label class="Elabel4">End Range 4</label>&nbsp;<input type="number" min="" max="" value="<?php echo $vehicle->temp3_range4_end; ?>" id="edr4" name="Temperature3[max_temp_end_4]" style="width:60px;" step=".10" onchange="checkwithMainMaxVal(this)"> &nbsp;<input type="color" value="<?php echo $vehicle->temp3_range4_color; ?>" id="colorpallet4" name="Temperature3[colorpallet4]" style="width:40px;"><br></div>
    <?php }?>
    </div><br>
    <div class="formSep ">
    </div>
    <?php
}
if ($_SESSION['temp_sensors'] == 2) {
    ?>
    <div class="input-prepend " style="width:85%;">
        <span class="f_legend"> <?php if ($vehicle->n1 == 0) {?> Temperature 1 Limits -<?php
} else {
        echo getName_ByType($vehicle->n1);
    }
    ?></span>
        <span class="add-on">Min.</span>
        <input type="text" name="min_temp1_limit" placeholder="Value" maxlength="5" size="5" value="<?php echo $vehicle->temp1_min; ?>"/><span class="add-on">&deg; C</span>
        <span class="add-on">Max.</span>
        <input type="text" name="max_temp1_limit" placeholder="Value" maxlength="5" size="5" value="<?php echo $vehicle->temp1_max; ?>"/><span class="add-on">&deg; C</span>
        <?php if (isset($_SESSION['switch_to']) && $_SESSION['switch_to'] == 3) {?>
            <span class="add-on">Allowance.</span>
            <input type="text" name="allowance_temp1" placeholder="Value" maxlength="5" size="5" value="<?php echo $vehicle->temp1_allowance; ?>" /><span class="add-on">&deg; C</span>
        <?php }?>
         <span class="add-on">Nomenclature </span>
        <select id="temp1nomensid" name="n1" class="nomens">
            <option value="0">Select Nomens</option>
            <?php
if (isset($nomens)) {
        foreach ($nomens as $nomen) {
            if ($nomen["nid"] == $vehicle->n1) {
                ?>
                    <option value='<?php echo $nomen["nid"]; ?>' selected><?php echo $nomen["name"]; ?></option>
                  <?php } else {?>
                <option value='<?php echo $nomen["nid"]; ?>'><?php echo $nomen["name"]; ?></option>
              <?php }
        }
    }
    ?>
        </select>
         <span id="addElem_<?php echo $tempSensName1; ?>" style="cursor: pointer;"><i class="fa fa-plus-square fa-2" onclick="addElements(this,'<?php echo $tempSensName1; ?>')"></i></span>
         &nbsp;&nbsp;&nbsp;<span class="f_legend"> Static Temperature <input type="checkbox" name="chkStaticTemp1" value="1" <?php echo $staticTemp1; ?> ></span>
    </div>
     <div class="MainRangeDiv_<?php echo $tempSensName1; ?>" style="display:block">
<?php if (($vehicle->temp1_range1_start != '' && $vehicle->temp1_range1_start != '0.00') || ($vehicle->temp1_range1_end != '' && $vehicle->temp1_range1_end != '0.00') || ($vehicle->temp1_range1_color != '' && $vehicle->temp1_range1_color != '0.00')) {?>
    <div class="ranges"><br><label class="Slabel1">Start Range 1</label> &nbsp;<input type="number" min="" max="" value="<?php echo $vehicle->temp1_range1_start; ?>" id="str1" name="Temperature1[min_temp_start_1]" style="width:60px;" step=".10" onchange="checkwithMainMinVal(this)">&nbsp;<label class="Elabel1">End Range 1</label>&nbsp;<input type="number" min="" max="" value="<?php echo $vehicle->temp1_range1_end; ?>" id="edr1" name="Temperature1[max_temp_end_1]" style="width:60px;" step=".10" onchange="checkwithMainMaxVal(this)"> &nbsp;<input type="color" value="<?php echo $vehicle->temp1_range1_color; ?>" id="colorpallet1" name="Temperature1[colorpallet1]" style="width:40px;"><br></div>
<?php }?>
<?php if (($vehicle->temp1_range2_start != '' && $vehicle->temp1_range2_start != '0.00') || ($vehicle->temp1_range2_end != '' && $vehicle->temp1_range2_end != '0.00') || ($vehicle->temp1_range1_color != '' && $vehicle->temp1_range1_color != '0.00')) {?>
    <div class="ranges"><br><label class="Slabel2">Start Range 2</label> &nbsp;<input type="number" min="" max="" value="<?php echo $vehicle->temp1_range2_start; ?>" id="str2" name="Temperature1[min_temp_start_2]" style="width:60px;" step=".10" onchange="checkwithMainMinVal(this)">&nbsp;<label class="Elabel2">End Range 2</label>&nbsp;<input type="number" min="" max="" value="<?php echo $vehicle->temp1_range2_end; ?>" id="edr2" name="Temperature1[max_temp_end_2]" style="width:60px;" step=".10" onchange="checkwithMainMaxVal(this)"> &nbsp;<input type="color" value="<?php echo $vehicle->temp1_range2_color; ?>" id="colorpallet2" name="Temperature1[colorpallet2]" style="width:40px;"><br></div>
    <?php }?>
<?php if (($vehicle->temp1_range3_start != '' && $vehicle->temp1_range3_start != '0.00') || ($vehicle->temp1_range3_end != '' && $vehicle->temp1_range3_end != '0.00') || ($vehicle->temp1_range3_color != '' && $vehicle->temp1_range3_color != '0.00')) {?>
    <div class="ranges"><br><label class="Slabel3">Start Range 3</label> &nbsp;<input type="number" min="" max="" value="<?php echo $vehicle->temp1_range3_start; ?>" id="str3" name="Temperature1[min_temp_start_3]" style="width:60px;" step=".10" onchange="checkwithMainMinVal(this)">&nbsp;<label class="Elabel3">End Range 3</label>&nbsp;<input type="number" min="" max="" value="<?php echo $vehicle->temp1_range3_end; ?>" id="edr3" name="Temperature1[max_temp_end_3]" style="width:60px;" step=".10" onchange="checkwithMainMaxVal(this)"> &nbsp;<input type="color" value="<?php echo $vehicle->temp1_range3_color; ?>" id="colorpallet3" name="Temperature1[colorpallet3]" style="width:40px;"><br></div>
    <?php }?>
<?php if (($vehicle->temp1_range4_start != '' && $vehicle->temp1_range4_start != '0.00') || ($vehicle->temp1_range4_end != '' && $vehicle->temp1_range4_end != '0.00') || ($vehicle->temp1_range4_color != '' && $vehicle->temp1_range4_color != '0.00')) {?>
    <div class="ranges"><br><label class="Slabel4">Start Range 4</label> &nbsp;<input type="number" min="" max="" value="<?php echo $vehicle->temp1_range4_start; ?>" id="str4" name="Temperature1[min_temp_start_4]" style="width:60px;" step=".10" onchange="checkwithMainMinVal(this)">&nbsp;<label class="Elabel4">End Range 4</label>&nbsp;<input type="number" min="" max="" value="<?php echo $vehicle->temp1_range4_end; ?>" id="edr4" name="Temperature1[max_temp_end_4]" style="width:60px;" step=".10" onchange="checkwithMainMaxVal(this)"> &nbsp;<input type="color" value="<?php echo $vehicle->temp1_range4_color; ?>" id="colorpallet4" name="Temperature1[colorpallet4]" style="width:40px;"><br></div>
    <?php }?>
    </div><br>
    <div class="formSep ">
    </div>
    <div class="input-prepend " style="width:85%;">
        <span class="f_legend"><?php if ($vehicle->n2 == 0) {?> Temperature 2 Limits -<?php
} else {
        echo getName_ByType($vehicle->n2);
    }
    ?></span>
        <span class="add-on">Min.</span>
        <input type="text" name="min_temp2_limit" placeholder="Value" maxlength="5" size="5" value="<?php echo $vehicle->temp2_min; ?>"  style="margin:0 0 0 0"/><span class="add-on">&deg; C</span>
        <span class="add-on">Max.</span>
        <input type="text" name="max_temp2_limit" placeholder="Value" maxlength="5" size="5" value="<?php echo $vehicle->temp2_max; ?>"  style="margin:0 0 0 0"/><span class="add-on">&deg; C</span>
        <?php if (isset($_SESSION['switch_to']) && $_SESSION['switch_to'] == 3) {?>
            <span class="add-on">Allowance.</span>
            <input type="text" name="allowance_temp2" placeholder="Value" maxlength="5" size="5" value="<?php echo $vehicle->temp2_allowance; ?>"  style="margin:0 0 0 0"/><span class="add-on">&deg; C</span>
        <?php }?>
        <span class="add-on">Nomenclature </span>
        <select id="temp2nomensid" name="n2" class="nomens">
            <option value="0">Select Nomens</option>
            <?php
if (isset($nomens)) {
        foreach ($nomens as $nomen) {
            if ($nomen["nid"] == $vehicle->n2) {
                ?>
                    <option value='<?php echo $nomen["nid"]; ?>' selected><?php echo $nomen["name"]; ?></option>
                  <?php } else {?>
                <option value='<?php echo $nomen["nid"]; ?>'><?php echo $nomen["name"]; ?></option>
              <?php }
        }
    }
    ?>
        </select>
        <span id="addElem_<?php echo $tempSensName2; ?>" style="cursor: pointer;"><i class="fa fa-plus-square fa-2" onclick="addElements(this,'<?php echo $tempSensName2; ?>')"></i></span>
        &nbsp;&nbsp;&nbsp;<span class="f_legend"> Static Temperature <input type="checkbox" name="chkStaticTemp2" value="1" <?php echo $staticTemp2; ?> ></span>
    </div>
     <div class="MainRangeDiv_<?php echo $tempSensName2; ?>" style="display:block;">
     <?php if (($vehicle->temp2_range1_start != '' && $vehicle->temp2_range1_start != '0.00') || ($vehicle->temp2_range1_end != '' && $vehicle->temp2_range1_end != '0.00') || ($vehicle->temp2_range1_color != '' && $vehicle->temp2_range1_color != '0.00')) {?>
    <div class="ranges"><br><label class="Slabel1">Start Range 1</label> &nbsp;<input type="number" min="" max="" value="<?php echo $vehicle->temp2_range1_start; ?>" id="str1" name="Temperature2[min_temp_start_1]" style="width:60px;" step=".10" onchange="checkwithMainMinVal(this)">&nbsp;<label class="Elabel1">End Range 1</label>&nbsp;<input type="number" min="" max="" value="<?php echo $vehicle->temp2_range1_end; ?>" id="edr1" name="Temperature2[max_temp_end_1]" style="width:60px;" step=".10" onchange="checkwithMainMaxVal(this)"> &nbsp;<input type="color" value="<?php echo $vehicle->temp2_range1_color; ?>" id="colorpallet1" name="Temperature2[colorpallet1]" style="width:40px;"><br></div>
    <?php }?>
<?php if (($vehicle->temp2_range2_start != '' && $vehicle->temp2_range2_start != '0.00') || ($vehicle->temp2_range2_end != '' && $vehicle->temp2_range2_end != '0.00') || ($vehicle->temp2_range2_color != '' && $vehicle->temp2_range2_color != '0.00')) {?>
    <div class="ranges"><br><label class="Slabel2">Start Range 2</label> &nbsp;<input type="number" min="" max="" value="<?php echo $vehicle->temp2_range2_start; ?>" id="str2" name="Temperature2[min_temp_start_2]" style="width:60px;" step=".10" onchange="checkwithMainMinVal(this)">&nbsp;<label class="Elabel2">End Range 2</label>&nbsp;<input type="number" min="" max="" value="<?php echo $vehicle->temp2_range2_end; ?>" id="edr2" name="Temperature2[max_temp_end_2]" style="width:60px;" step=".10" onchange="checkwithMainMaxVal(this)"> &nbsp;<input type="color" value="<?php echo $vehicle->temp2_range2_color; ?>" id="colorpallet2" name="Temperature2[colorpallet2]" style="width:40px;"><br></div>
<?php }?>
<?php if (($vehicle->temp2_range3_start != '' && $vehicle->temp2_range3_start != '0.00') || ($vehicle->temp2_range3_end != '' && $vehicle->temp2_range3_end != '0.00') || ($vehicle->temp2_range3_color != '' && $vehicle->temp2_range3_color != '0.00')) {?>
    <div class="ranges"><br><label class="Slabel3">Start Range 3</label> &nbsp;<input type="number" min="" max="" value="<?php echo $vehicle->temp2_range3_start; ?>" id="str3" name="Temperature2[min_temp_start_3]" style="width:60px;" step=".10" onchange="checkwithMainMinVal(this)">&nbsp;<label class="Elabel3">End Range 3</label>&nbsp;<input type="number" min="" max="" value="<?php echo $vehicle->temp2_range3_end; ?>" id="edr3" name="Temperature2[max_temp_end_3]" style="width:60px;" step=".10" onchange="checkwithMainMaxVal(this)"> &nbsp;<input type="color" value="<?php echo $vehicle->temp2_range3_color; ?>" id="colorpallet3" name="Temperature2[colorpallet3]" style="width:40px;"><br></div>
    <?php }?>
<?php if (($vehicle->temp2_range4_start != '' && $vehicle->temp2_range4_start != '0.00') || ($vehicle->temp2_range4_end != '' && $vehicle->temp2_range4_end != '0.00') || ($vehicle->temp2_range4_color != '' && $vehicle->temp2_range4_color != '0.00')) {?>
    <div class="ranges"><br><label class="Slabel4">Start Range 4</label> &nbsp;<input type="number" min="" max="" value="<?php echo $vehicle->temp2_range4_start; ?>" id="str4" name="Temperature2[min_temp_start_4]" style="width:60px;" step=".10" onchange="checkwithMainMinVal(this)">&nbsp;<label class="Elabel4">End Range 4</label>&nbsp;<input type="number" min="" max="" value="<?php echo $vehicle->temp2_range4_end; ?>" id="edr4" name="Temperature2[max_temp_end_4]" style="width:60px;" step=".10" onchange="checkwithMainMaxVal(this)"> &nbsp;<input type="color" value="<?php echo $vehicle->temp2_range4_color; ?>" id="colorpallet4" name="Temperature2[colorpallet4]" style="width:40px;"><br></div>
    <?php }?>
    </div><br>
    <div class="formSep ">
    </div>
    <?php
}
if ($_SESSION['temp_sensors'] == 1) {
    ?>
        <div class="input-prepend " style="width:85%;">
            <span class="f_legend"> <?php if ($vehicle->n1 == 0) {?> Temperature Limits -<?php
} else {
        echo getName_ByType($vehicle->n1);
    }
    ?></span>
        <span class="add-on">Min.</span>
        <input type="text" name="min_temp1_limit" placeholder="Value" maxlength="5" size="5" value="<?php echo $vehicle->temp1_min; ?>" style="margin:0 0 0 0"/><span class="add-on">&deg; C</span>
        <span class="add-on">Max.</span>
        <input type="text" name="max_temp1_limit" placeholder="Value" maxlength="5" size="5" value="<?php echo $vehicle->temp1_max; ?>" style="margin:0 0 0 0"/><span class="add-on">&deg; C</span>
        <?php if (isset($_SESSION['switch_to']) && $_SESSION['switch_to'] == 3) {?>
            <span class="add-on">Allowance.</span>
            <input type="text" name="allowance_temp1" placeholder="Value" maxlength="5" size="5" value="<?php echo $vehicle->temp1_allowance; ?>" style="margin:0 0 0 0"/><span class="add-on">&deg; C</span>
        <?php }?>
         <span class="add-on">Nomenclature </span>
            <select id="temp1nomensid" name="n1">
                <option value="0">Select Nomens</option>
                <?php
if (isset($nomens)) {
        foreach ($nomens as $nomen) {
            if ($nomen["nid"] == $vehicle->n1) {
                ?>
                    <option value='<?php echo $nomen["nid"]; ?>' selected><?php echo $nomen["name"]; ?></option>
                  <?php } else {?>
                    <option value='<?php echo $nomen["nid"]; ?>'><?php echo $nomen["name"]; ?></option>
                  <?php }
        }
    }
    ?>
            </select>
             <span id="addElem_<?php echo $tempSensName1; ?>" style="cursor: pointer;"><i class="fa fa-plus-square fa-2" onclick="addElements(this,'<?php echo $tempSensName1; ?>')"></i></span>
             &nbsp;&nbsp;&nbsp;<span class="f_legend"> Static Temperature <input type="checkbox" name="chkStaticTemp1" value="1" <?php echo $staticTemp1; ?> ></span>
    </div><br>
    <div class="MainRangeDiv_<?php echo $tempSensName1; ?>" style="display:block">
    <?php if (($vehicle->temp1_range1_start != '' && $vehicle->temp1_range1_start != '0.00') || ($vehicle->temp1_range1_end != '' && $vehicle->temp1_range1_end != '0.00') || ($vehicle->temp1_range1_color != '' && $vehicle->temp1_range1_color != '0.00')) {?>
    <div class="ranges"><br><label class="Slabel1">Start Range 1</label> &nbsp;<input type="number" min="" max="" value="<?php echo $vehicle->temp1_range1_start; ?>" id="str1" name="Temperature1[min_temp_start_1]" style="width:60px;" step=".10" onchange="checkwithMainMinVal(this)">&nbsp;<label class="Elabel1">End Range 1</label>&nbsp;<input type="number" min="" max="" value="<?php echo $vehicle->temp1_range1_end; ?>" id="edr1" name="Temperature1[max_temp_end_1]" style="width:60px;" step=".10" onchange="checkwithMainMaxVal(this)"> &nbsp;<input type="color" value="<?php echo $vehicle->temp1_range1_color; ?>" id="colorpallet1" name="Temperature1[colorpallet1]" style="width:40px;"><br></div>
    <?php }?>
  <?php if (($vehicle->temp1_range2_start != '' && $vehicle->temp1_range2_start != '0.00') || ($vehicle->temp1_range2_end != '' && $vehicle->temp1_range2_end != '0.00') || ($vehicle->temp1_range2_color != '' && $vehicle->temp1_range2_color != '0.00')) {?>
    <div class="ranges"><br><label class="Slabel2">Start Range 2</label> &nbsp;<input type="number" min="" max="" value="<?php echo $vehicle->temp1_range2_start; ?>" id="str2" name="Temperature1[min_temp_start_2]" style="width:60px;" step=".10" onchange="checkwithMainMinVal(this)">&nbsp;<label class="Elabel2">End Range 2</label>&nbsp;<input type="number" min="" max="" value="<?php echo $vehicle->temp1_range2_end; ?>" id="edr2" name="Temperature1[max_temp_end_2]" style="width:60px;" step=".10" onchange="checkwithMainMaxVal(this)"> &nbsp;<input type="color" value="<?php echo $vehicle->temp1_range2_color; ?>" id="colorpallet2" name="Temperature1[colorpallet2]" style="width:40px;"><br></div>
    <?php }?>
<?php if (($vehicle->temp1_range2_start != '' && $vehicle->temp1_range2_start != '0.00') || ($vehicle->temp1_range2_end != '' && $vehicle->temp1_range2_end != '0.00') || ($vehicle->temp1_range2_color != '' && $vehicle->temp1_range2_color != '0.00')) {?>
    <div class="ranges"><br><label class="Slabel3">Start Range 3</label> &nbsp;<input type="number" min="" max="" value="<?php echo $vehicle->temp1_range3_start; ?>" id="str3" name="Temperature1[min_temp_start_3]" style="width:60px;" step=".10" onchange="checkwithMainMinVal(this)">&nbsp;<label class="Elabel3">End Range 3</label>&nbsp;<input type="number" min="" max="" value="<?php echo $vehicle->temp1_range3_end; ?>" id="edr3" name="Temperature1[max_temp_end_3]" style="width:60px;" step=".10" onchange="checkwithMainMaxVal(this)"> &nbsp;<input type="color" value="<?php echo $vehicle->temp1_range3_color; ?>" id="colorpallet3" name="Temperature1[colorpallet3]" style="width:40px;"><br></div>
     <?php }?>
<?php if (($vehicle->temp1_range4_start != '' && $vehicle->temp1_range4_start != '0.00') || ($vehicle->temp1_range4_end != '' && $vehicle->temp1_range4_end != '0.00') || ($vehicle->temp1_range4_color != '' && $vehicle->temp1_range4_color != '0.00')) {?>
    <div class="ranges"><br><label class="Slabel4">Start Range 4</label> &nbsp;<input type="number" min="" max="" value="<?php echo $vehicle->temp1_range4_start; ?>" id="str4" name="Temperature1[min_temp_start_4]" style="width:60px;" step=".10" onchange="checkwithMainMinVal(this)">&nbsp;<label class="Elabel4">End Range 4</label>&nbsp;<input type="number" min="" max="" value="<?php echo $vehicle->temp1_range4_end; ?>" id="edr4" name="Temperature1[max_temp_end_4]" style="width:60px;" step=".10" onchange="checkwithMainMaxVal(this)"> &nbsp;<input type="color" value="<?php echo $vehicle->temp1_range4_color; ?>" id="colorpallet4" name="Temperature1[colorpallet4]" style="width:40px;"><br></div>
     <?php }?>
    </div>
    <br>
    <?php
}
if ($_SESSION['use_humidity'] == 1) {
    ?>
    <br><br>
    <p class="f_legend"> <h4>Humidity Limits</h4></p>
    <div class="input-prepend ">
        <span class="add-on">Min.</span>
        <input type="text" name="min_humidity_limit" placeholder="Value" maxlength="5" size="5" value="<?php echo $vehicle->hum_min; ?>"/><span class="add-on">&deg; C</span>
        <span class="add-on">Max.</span>
        <input type="text" name="max_humidity_limit" placeholder="Value" maxlength="5" size="5" value="<?php echo $vehicle->hum_max; ?>"/><span class="add-on">&deg; C</span>
    </div>
    <div class="formSep "> </div>
    <?php
}
?>
<fieldset>
    <div class="control-group pull-right">
        <input type="button" class="btn  btn-primary" value="Modify <?php echo $custom; ?>" onclick="editvehicle();">&nbsp;
    </div>
</fieldset>
</form>
<script>
    var Count1 = 0;
    var Count2 = 0;
    var Count3 = 0;
    var Count4 = 0;
    function editvehicle()
    {
        var n1 = $("#temp1nomensid").val();
        var n2 = $("#temp2nomensid").val();
        var n3 = $("#temp3nomensid").val();
        var n4 = $("#temp4nomensid").val();
        var error= "";
        var min_temp1 = $("input[name=max_temp1_limit]").val();
        var max_temp1 = $("input[name=max_temp1_limit]").val();
        var str1Val = $("#str1").val();
        var edr1Val = $("#edr1").val();
        var str2Val = $("#str2").val();
        var edr2Val = $("#edr2").val();
        var str3Val = $("#str3").val();
        var edr3Val = $("#edr3").val();
        var str4Val = $("#str4").val();
        var edr4Val = $("#edr4").val();
            if(str4Val!="0.00" && edr4Val!="0.00" && parseFloat(str4Val) >= parseFloat(edr4Val)){
                error += "Start Value should be less than End Value";
            }
            else if(str3Val!="0.00" && edr3Val!="0.00" && parseFloat(str3Val) >= parseFloat(edr3Val)){
                error += "Start Value should lesser than End Value";
            }
            else if(str2Val!="0.00" && edr2Val!="0.00" && parseFloat(str2Val) >= parseFloat(edr2Val)){
                error += "Start Value should lesser than End Value";
            }
            else if(str1Val!="0.00" && edr1Val!="0.00" && parseFloat(str1Val) >= parseFloat(edr1Val)){
                error += "Start Value should lesser than End Value";
            }
            if(error != ""){
                alert(error);
                return false;
            }
        var reportRecipientsDuplicate = [];
        if(n1 != '0'|| n2 != '0' || n3 != '0' || n4 !='0'){
        var reportRecipients = [n1,n2, n3, n4];
        var recipientsArray  = reportRecipients.sort();
            for (var i = 0; i < recipientsArray.length - 1; i++){
                if(recipientsArray[i] != undefined && recipientsArray[i] !=0){
                    if (recipientsArray[i + 1] == recipientsArray[i]) {
                        reportRecipientsDuplicate.push(recipientsArray[i]);
                    }
                }
            }
        }
        if(jQuery("#vehicleno").val() == "")
        {
            alert("Please enter a Vehicle Number");
            $("#vehicleno").focus();
            return false;
        }
        else if(reportRecipientsDuplicate .length >0){
           alert("Nomenclature selected for Temperature should be unique.");
           return false;
        }
        else
        {
            jQuery("#editvehicle").submit();
        }
    }
    function checkwithMainMinVal(thiselm){
        var minVal              = $(thiselm).val();
        var minValName          = $(thiselm).attr('name');
        var values              = minValName.split("_");
        var Count               = values[3].slice(0,-1)
        var spiltValues         = values[0].split("[");
        var MainMinValueName    = spiltValues[1]+"_"+values[1]+Count+"_limit";
        var MainMaxValueName    = "max_"+values[1]+Count+"_limit";
        var MainMinval          = $("input[name="+MainMinValueName+"]").val();
        var MainMaxval          = $("input[name="+MainMaxValueName+"]").val();
            if(Count == 1){
                if(parseFloat(MainMinval) <= parseFloat(minVal)){
                    $("#str"+Count).css("border-color","#bdc3c7");// make it red
                }
                else{
                    alert("Invalid Start Range For Range "+Count);
                    $("#str"+Count).css("border-color","#ef1c0a");// make it red
                    return false;
                }
            }
            else if(Count > 1){
                var prevCount =  parseInt(Count)- 1;
                    if(parseFloat(minVal) >= parseFloat($("#edr"+prevCount).val())){
                           $("#str"+Count).css("border-color","#bdc3c7");// make it default
                        //true value
                    }
                    else{
                        alert("Invalid Start Range for Range "+Count);
                            $("#str"+Count).css("border-color","#ef1c0a");// make it red
                            return false;
                        }
            }
    }
     function checkwithMainMaxVal(thiselm){
        var maxVal            = $(thiselm).val();
        var maxValName        = $(thiselm).attr('name');
        var values            = maxValName.split("_");
        var spiltValues       = values[0].split("[");
        var Count             = values[3].slice(0,-1);
       // var MainValueName     = spiltValues[1]+"_"+values[1]+Count+"_limit";
        var MainValueName     = $("input[name^='max_temp']" ).attr('name');;
        var MainMaxval        = $("input[name="+MainValueName+"]").val();
        var minVal            = $("#str"+values[3].slice(0,-1)).val();
        var getMinName        = spiltValues[0]+"[min_temp_start_"+Count+"]";
        var minVal            = $("input[name='"+getMinName+"']").val();
        var totalElementCount = $(".ranges").length;
       if(Count == totalElementCount){
            if(parseFloat(MainMaxval) >= parseFloat($("#edr"+Count).val()))
            {
                   $("#edr"+Count).css("border-color","#bdc3c7");
            }
            else{
                   $("#edr"+Count).css("border-color","#ef1c0a");
                    alert("End Range can't be greater than Max Temp Limit");
                    return false;
            }
       }
        if(parseFloat(minVal) >= parseFloat(maxVal)){
            alert("Invalid End Range for Range "+Count);
             $("#edr"+Count).css("border-color","#ef1c0a");
             return false;
        }
        else{
             $("#edr"+Count).css("border-color","#bdc3c7");// make it default
        }
    }
function addElements(thiselm,tempSensName){
    var splitTogetCount  = tempSensName.split("Temperature");
    var Count           = 0;
    if(splitTogetCount[1] == "1"){
        Count1 = Count1+1;
        Count = Count1;
    }
    else if(splitTogetCount[1] == "2"){
        Count2 = Count2+1;
        Count = Count2;
    }
    else if(splitTogetCount[1] == "3"){
        Count3 = Count3+1;
        Count = Count3;
    }
    else{
        Count4 = Count4+1;
        Count = Count4;
    }
    var thisElelement = $(thiselm).html();
    var element = '<div class="ranges"><br><label class="Slabel">Start Range</label> &nbsp;<input type="number" min="" max="" value="0.00" id="" name="min_temp_start" style="width:60px;" step=".10" onchange=checkwithMainMinVal(this)>&nbsp;<label class="Elabel">End Range</label>&nbsp;<input type="number"  min="" max="" value="0.00" id="" name="max_temp_end" style="width:60px;" step=".10" onchange=checkwithMainMaxVal(this)> &nbsp;<input type="color" value="" id="colorpallet" name="colorpallet" style="width:40px;"><br></div>';
    if(Count <=4){
        $(".min_temp_end").attr('id','edr'+Count);
        $(element).clone().appendTo("#addElem_"+tempSensName);
        $(".Slabel").attr("class","Slabel"+Count);
        $(".Slabel"+Count).html("Start Range "+Count);
        $(".Elabel").attr("class","Elabel"+Count);
        $(".Elabel"+Count).html("End Range "+Count);
        $("input[name = 'min_temp_start']").attr('id','str'+Count);
        $("input[name = 'min_temp_start']").attr('name',''+tempSensName+'[min_temp_start_'+Count+']');
        $("input[name = 'min_temp_end']").attr('id','edr'+Count);
        $("input[name = 'min_temp_end']").attr('name',''+tempSensName+'[min_temp_end_'+Count+']');
        $("input[name = 'max_temp_start']").attr('id','str'+Count);
        $("input[name = 'max_temp_start']").attr('name',''+tempSensName+'[max_temp_start_'+Count+']');
        $("input[name = 'max_temp_end']").attr('id','edr'+Count);
        $("input[name = 'max_temp_end']").attr('name',''+tempSensName+'[max_temp_end_'+Count+']');
        $("input[name='colorpallet']").attr('id','colorpallet'+Count);
        $("input[name='colorpallet']").attr('name',''+tempSensName+'[colorpallet'+Count+']');
         if(Count == 4){
                    $(thiselm).hide();
               }
    }
    else{
        alert("You have added 4 Start -End Range");
        return false;
    }
}
    function checkwithMainMinVal(thiselm){
        var minVal                         = $(thiselm).val();
        var minValName                     = $(thiselm).attr('name');
        var values                         = minValName.split("_");
        var Count                          = values[3].slice(0,-1)
        var spiltValues                    = values[0].split("[");
        var MainMinValueName               = spiltValues[1]+"_"+values[1]+Count+"_limit";
        var MainMaxValueName               = "max_"+values[1]+Count+"_limit";
        var MainValueName                  = $("input[name^='min_temp']" ).attr('name');;
        var splitValuesTogetCurrElement    = spiltValues[0].split("Temperature");
        var MainMinval                     = $("input[name=min_temp"+splitValuesTogetCurrElement[1]+"_limit]").val();
        if(Count == 1){
                if(parseFloat(MainMinval) <= parseFloat(minVal)){
                    $(thiselm).css("border-color","#bdc3c7");// make it default
                }
                else{
                    alert("Invalid Start Range For Range "+Count);
                    $(thiselm).css("border-color","#ef1c0a");// make it red
                    return false;
                }
            }
            else if(Count > 1){
                var prevCount =  parseInt(Count)- 1;
                var prevEndVal = $("input[name='"+spiltValues[0]+"[max_temp_end_"+prevCount+"]'").val();
                    if(parseFloat(minVal) >= parseFloat(prevEndVal)){
                           $(thiselm).css("border-color","#bdc3c7");// make it default
                        //true value
                    }
                    else{
                        alert("Invalid Start Range for Range "+Count);
                            $(thiselm).css("border-color","#ef1c0a");// make it red
                            return false;
                        }
            }
    }
     function checkwithMainMaxVal(thiselm){
        var maxVal            = $(thiselm).val();
        var maxValName        = $(thiselm).attr('name');
        var values            = maxValName.split("_");
        var spiltValues       = values[0].split("[");
        var Count             = values[3].slice(0,-1);
        var totalElementCount = $(".ranges").length;
        var endVal                          = $("input[name='"+spiltValues[0]+"[max_temp_end_"+Count+"]']").val();
        var startVal                          = $("input[name='"+spiltValues[0]+"[min_temp_start_"+Count+"]']").val();
        var splitValuesTogetCurrElement     = spiltValues[0].split("Temperature");
        var MainMaxval                      = $("input[name=max_temp"+splitValuesTogetCurrElement[1]+"_limit]").val();
        if(Count == totalElementCount){
                if(parseFloat(MainMaxval) >= parseFloat(endVal))
                {
                        $(thiselm).css("border-color","#bdc3c7");
                }
                else
                {
                        $(thiselm).css("border-color","#ef1c0a");
                        alert("End Range can't be greater than Max Temp Limit");
                        return false;
                }
           }
       if(Count == 1){
         if(parseFloat(startVal) > parseFloat(endVal)){
                alert("Invalid End Range for Range "+Count);
                  $(thiselm).css("border-color","#ef1c0a");
                 return false;
            }
            else{
                $(thiselm).css("border-color","#bdc3c7");// make it default
            }
       }
       else if(Count >1){
               var prevCount        =  parseInt(Count)- 1;
               var prevStartVal     = $("input[name='"+spiltValues[0]+"[min_temp_start_"+prevCount+"]'").val();
                if(prevStartVal > maxVal){
                    alert("Invalid End Range for Range "+Count);
                      $(thiselm).css("border-color","#ef1c0a");
                     return false;
                }
                else{
                    $(thiselm).css("border-color","#bdc3c7");// make it default
                }
        }
    }
function toggleDiv(thisElelement,elementType){
    $(".MainRangeDiv_"+elementType).slideToggle();
    if($(thisElelement).attr("title") == "Show"){
         $(thisElelement).attr("title","Hide");
    }
    else{
         $(thisElelement).attr("title","Show");
    }
}
</script>