<style>
  label{  display: inline-block !important;
  }
</style>
<?php
if (isset($_SESSION['switch_to']) && $_SESSION['switch_to'] == 3) {
    if (isset($_SESSION['Warehouse'])) {
        $custom = $_SESSION['Warehouse'];
    } else {
        $custom = "Warehouse";
    }
} else {
    $custom = "Vehicle";
}
$checkpoints = getchks();
$groups = getgroup();
$masters = getworkmaster();
$fences = getfences();
?>
<form  class="form-horizontal well " name="createvehicle" id="createvehicle" action="route.php" method="POST" style="width:70%;">
    <?php include 'panels/addvehicle.php';?>
    <fieldset>
        <div class="control-group">
            <div class="input-prepend ">
                <span class="add-on"><?php echo $vehicles_ses ?><span class="mandatory">*</span></span><input type="text" name="vehicleno" id="vehicleno" placeholder="Enter <?php echo $vehicles_ses; ?> Name" autofocus maxlength="40">
            </div>
            <?php if (isset($_SESSION['Session_UserRole']) && $_SESSION['Session_UserRole'] == 'elixir') {?>
                <div class="input-prepend ">
                    <span class="add-on">Kind </span><select name="type" id="type" onchange="return setKind(value);">
                        <option value="Car">Car</option>
                        <option value="Bus">Bus</option>
                        <option value="Truck">Truck</option>
                        <option value="Warehouse">Warehouse</option>
                    </select>
                </div>
            <?php } else {
    if (isset($_SESSION['switch_to']) && $_SESSION['switch_to'] == 3) {?>
                    <input type="hidden" name="type" id="type" value="Warehouse" />
                <?php } else {?>
                    <div class="input-prepend ">
                        <span class="add-on">Kind </span><select name="type" id="type" onchange="return setKind(value);">
                            <option value="Car">Car</option>
                            <option value="Bus">Bus</option>
                            <option value="Truck">Truck</option>
                        </select>
                    </div>
                    <?php
}
}
?>
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
        echo "<option value='$group->groupid'>$group->groupname</option>";
    }
}
?>
                </select>
            </div>
            <div class="input-prepend " id="overspeed">
                <span class="add-on">Overspeed Limit</span>
                <input type="text" name="overspeed_limit" placeholder="Value" value="80" maxlength="3" size="5" /><span class="add-on">Km/Hr</span>
            </div>
        </div>
    </fieldset>
    <fieldset id="fueltank">
        <div class="control-group">
            <div class="input-prepend ">
                <span class="add-on">Fuel Tank Capacity </span><input type="text" name="fuelcapacity" id="fuelcapacity" value="0" size="5" placeholder="Fuel Tank Capacity" maxlength="3"><span class="add-on">Liters </span>
            </div>
            <div class="input-prepend ">
                <span class="add-on">Average</span>
                <input type="text" name="average" placeholder="Value" value="0" maxlength="4" size="5" /><span class="add-on">Km/Lt</span>
            </div>
        </div>
    </fieldset>
    <?php if ($_SESSION['customerno'] == 15 || $_SESSION['customerno'] == 21 || $_SESSION['customerno'] == 48) {
    ?>
        <fieldset>
            <div class="control-group">
                <div class="input-prepend ">
                    <span class="add-on">Batch </span><input type="text" name="batch" id="batch"  <?php
if ($_SESSION['customerno'] == 48) {
        echo 'value="1234"';
    } else {
        echo 'value=""';
    }
    ?> size="5" placeholder="Batch" maxlength="15">
                </div>
                <div class="input-prepend ">
                    <span class="add-on">Work Key</span>
                    <input type="text" name="work_key" id="workkey" placeholder="work Key" <?php
if ($_SESSION['customerno'] == 48) {
        echo 'value="1234"';
    } else {
        echo 'value=""';
    }
    ?> maxlength="4" size="5" />
                </div>
            </div>
            <?php if ($_SESSION['customerno'] == 15 || $_SESSION['customerno'] == 21) {
        ?>
                <div class="control-group">
                    <div class="input-prepend">
                        <span class="add-on">Start Date</span>
                        <input id="SDate" name="SDate" type="text"  value=""/>
                        <span class="add-on">Start Time</span>
                        <input id="STime" name="STime" type="text" class="input-mini" data-date="" value=""/>
                        <span class="add-on">Batch No </span><input type="text" name="dummybatch" id="dummybatch" value="<?php echo $batch->dummybatchno; ?>" size="5" placeholder="Batch" maxlength="15">
                        <br/><br/>
                        <span class="add-on">Select Master </span>
                        <select id="sel_master" name="sel_master">
                            <option value="0">Select Master</option>
                            <?php
if (isset($masters)) {
            foreach ($masters as $group) {
                echo "<option value='$group->pmid'>$group->workkey_name - ($group->workkey)</option>";
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
    <fieldset id="checkpoint">
        <div class="control-group formSep span7 ">
            <div class="input-prepend ">
                <span class="add-on">Checkpoint</span>
                <select id="chkid" name="chkid" onchange="addchk()">
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
            <div id="checkpoint_list" ></div>
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
            </div>
        </div>
    </fieldset>
    <?php
$tempSensName1 = "Temperature1";
$tempSensName2 = "Temperature2";
$tempSensName3 = "Temperature3";
$tempSensName4 = "Temperature4";
if ($_SESSION['temp_sensors'] == 4) {
    ?>
    <div class="input-prepend " style="width:80%;">
        <span class="f_legend">Temperature 1 Limits - </span>
        <span class="add-on">Min.</span>
        <input type="text" name="min_temp1_limit" placeholder="Value" maxlength="3" size="5" /><span class="add-on">&deg; C</span>
        <span class="add-on">Max.</span>
        <input type="text" name="max_temp1_limit" placeholder="Value" maxlength="3" size="5" /><span class="add-on">&deg; C</span>
        <?php if (isset($_SESSION['switch_to']) && $_SESSION['switch_to'] == 3) {?>
            <span class="add-on">Allowance.</span>
            <input type="text" name="allowance_temp1" placeholder="Value" maxlength="5" size="5" /><span class="add-on">&deg; C</span>
        <?php }?>
        <span id="addElem_<?php echo $tempSensName1; ?>" style="cursor: pointer;"><i class="fa fa-plus-square fa-2" onclick="addElements(this,'<?php echo $tempSensName1; ?>')"></i></span>
        &nbsp;&nbsp;&nbsp;<span class="f_legend"> Static Temperature <input type="checkbox" name="chkStaticTemp1" value="1"></span>
    </div>
    <div class="input-prepend " style="width:80%;">
        <span class="f_legend">Temperature 2 Limits - </span>
        <span class="add-on">Min.</span>
        <input type="text" name="min_temp2_limit" placeholder="Value" maxlength="3" size="5" /><span class="add-on">&deg; C</span>
        <span class="add-on">Max.</span>
        <input type="text" name="max_temp2_limit" placeholder="Value" maxlength="3" size="5" /><span class="add-on">&deg; C</span>
        <?php if (isset($_SESSION['switch_to']) && $_SESSION['switch_to'] == 3) {?>
            <span class="add-on">Allowance.</span>
            <input type="text" name="allowance_temp2" placeholder="Value" maxlength="5" size="5" /><span class="add-on">&deg; C</span>
        <?php }?>
        <span id="addElem_<?php echo $tempSensName2; ?>" style="cursor: pointer;"><i class="fa fa-plus-square fa-2" onclick="addElements(this,'<?php echo $tempSensName2; ?>')"></i></span>
        &nbsp;&nbsp;&nbsp;<span class="f_legend"> Static Temperature <input type="checkbox" name="chkStaticTemp2" value="1"></span>
    </div>
    <div class="input-prepend " style="width:80%;">
        <span class="f_legend">Temperature 3 Limits - </span>
        <span class="add-on">Min.</span>
        <input type="text" name="min_temp3_limit" placeholder="Value" maxlength="3" size="5" /><span class="add-on">&deg; C</span>
        <span class="add-on">Max.</span>
        <input type="text" name="max_temp3_limit" placeholder="Value" maxlength="3" size="5" /><span class="add-on">&deg; C</span>
        <?php if (isset($_SESSION['switch_to']) && $_SESSION['switch_to'] == 3) {?>
            <span class="add-on">Allowance.</span>
            <input type="text" name="allowance_temp3" placeholder="Value" maxlength="5" size="5" /><span class="add-on">&deg; C</span>
        <?php }?>
        <span id="addElem_<?php echo $tempSensName3; ?>" style="cursor: pointer;"><i class="fa fa-plus-square fa-2" onclick="addElements(this,'<?php echo $tempSensName3; ?>')"></i></span>
        &nbsp;&nbsp;&nbsp;<span class="f_legend"> Static Temperature <input type="checkbox" name="chkStaticTemp3" value="1"></span>
    </div>
    <div class="input-prepend " style="width:80%;">
        <span class="f_legend">Temperature 2 Limits - </span>
        <span class="add-on">Min.</span>
        <input type="text" name="min_temp4_limit" placeholder="Value" maxlength="3" size="5" /><span class="add-on">&deg; C</span>
        <span class="add-on">Max.</span>
        <input type="text" name="max_temp4_limit" placeholder="Value" maxlength="3" size="5" /><span class="add-on">&deg; C</span>
        <?php if (isset($_SESSION['switch_to']) && $_SESSION['switch_to'] == 3) {?>
            <span class="add-on">Allowance.</span>
            <input type="text" name="allowance_temp4" placeholder="Value" maxlength="5" size="5" /><span class="add-on">&deg; C</span>
        <?php }?>
        <span id="addElem_<?php echo $tempSensName4; ?>" style="cursor: pointer;"><i class="fa fa-plus-square fa-2" onclick="addElements(this,'<?php echo $tempSensName4; ?>')"></i></span>
        &nbsp;&nbsp;&nbsp;<span class="f_legend"> Static Temperature <input type="checkbox" name="chkStaticTemp4" value="1"></span>
    </div>
    <div class="formSep"></div>
    <?php
}
if ($_SESSION['temp_sensors'] == 3) {
    ?>
    <div class="input-prepend " style="width:80%;">
        <span class="f_legend">Temperature 1 Limits - </span>
        <span class="add-on">Min.</span>
        <input type="text" name="min_temp1_limit" placeholder="Value" maxlength="3" size="5" /><span class="add-on">&deg; C</span>
        <span class="add-on">Max.</span>
        <input type="text" name="max_temp1_limit" placeholder="Value" maxlength="3" size="5" /><span class="add-on">&deg; C</span>
        <?php if (isset($_SESSION['switch_to']) && $_SESSION['switch_to'] == 3) {?>
            <span class="add-on">Allowance.</span>
            <input type="text" name="allowance_temp1" placeholder="Value" maxlength="5" size="5" /><span class="add-on">&deg; C</span>
        <?php }?>
        <span id="addElem_<?php echo $tempSensName1; ?>" style="cursor: pointer;"><i class="fa fa-plus-square fa-2" onclick="addElements(this,'<?php echo $tempSensName1; ?>')"></i></span>
        &nbsp;&nbsp;&nbsp;<span class="f_legend"> Static Temperature <input type="checkbox" name="chkStaticTemp1" value="1"></span>
    </div>
    <div class="input-prepend " style="width:80%;">
        <span class="f_legend">Temperature 2 Limits - </span>
        <span class="add-on">Min.</span>
        <input type="text" name="min_temp2_limit" placeholder="Value" maxlength="3" size="5" /><span class="add-on">&deg; C</span>
        <span class="add-on">Max.</span>
        <input type="text" name="max_temp2_limit" placeholder="Value" maxlength="3" size="5" /><span class="add-on">&deg; C</span>
        <?php if (isset($_SESSION['switch_to']) && $_SESSION['switch_to'] == 3) {?>
            <span class="add-on">Allowance.</span>
            <input type="text" name="allowance_temp2" placeholder="Value" maxlength="5" size="5" /><span class="add-on">&deg; C</span>
        <?php }?>
        <span id="addElem_<?php echo $tempSensName2; ?>" style="cursor: pointer;"><i class="fa fa-plus-square fa-2" onclick="addElements(this,'<?php echo $tempSensName2; ?>')"></i></span>
        &nbsp;&nbsp;&nbsp;<span class="f_legend"> Static Temperature <input type="checkbox" name="chkStaticTemp2" value="1"></span>
    </div>
    <div class="input-prepend " style="width:80%;">
        <span class="f_legend">Temperature 3 Limits - </span>
        <span class="add-on">Min.</span>
        <input type="text" name="min_temp3_limit" placeholder="Value" maxlength="3" size="5" /><span class="add-on">&deg; C</span>
        <span class="add-on">Max.</span>
        <input type="text" name="max_temp3_limit" placeholder="Value" maxlength="3" size="5" /><span class="add-on">&deg; C</span>
        <?php if (isset($_SESSION['switch_to']) && $_SESSION['switch_to'] == 3) {?>
            <span class="add-on">Allowance.</span>
            <input type="text" name="allowance_temp3" placeholder="Value" maxlength="5" size="5" /><span class="add-on">&deg; C</span>
        <?php }?>
        <span id="addElem_<?php echo $tempSensName3; ?>" style="cursor: pointer;"><i class="fa fa-plus-square fa-2" onclick="addElements(this,'<?php echo $tempSensName3; ?>')"></i></span>
        &nbsp;&nbsp;&nbsp;<span class="f_legend"> Static Temperature <input type="checkbox" name="chkStaticTemp3" value="1"></span>
    </div>
    <div class="formSep ">
    </div>
    <?php
}
if ($_SESSION['temp_sensors'] == 2) {
    ?>
    <div class="input-prepend " style="width:80%;">
        <span class="f_legend">Temperature 1 Limits - </span>
        <span class="add-on">Min.</span>
        <input type="text" name="min_temp1_limit" placeholder="Value" maxlength="3" size="5" /><span class="add-on">&deg; C</span>
        <span class="add-on">Max.</span>
        <input type="text" name="max_temp1_limit" placeholder="Value" maxlength="3" size="5" /><span class="add-on">&deg; C</span>
        <?php if (isset($_SESSION['switch_to']) && $_SESSION['switch_to'] == 3) {?>
            <span class="add-on">Allowance.</span>
            <input type="text" name="allowance_temp1" placeholder="Value" maxlength="5" size="5" /><span class="add-on">&deg; C</span>
        <?php }?> <span id="addElem_<?php echo $tempSensName1; ?>" style="cursor: pointer;"><i class="fa fa-plus-square fa-2" onclick="addElements(this,'<?php echo $tempSensName1; ?>')"></i></span>
        &nbsp;&nbsp;&nbsp;<span class="f_legend"> Static Temperature <input type="checkbox" name="chkStaticTemp1" value="1"></span>
    </div>
    <div class="input-prepend " style="width:80%;">
        <span class="f_legend">Temperature 2 Limits - </span>
        <span class="add-on">Min.</span>
        <input type="text" name="min_temp2_limit" placeholder="Value" maxlength="3" size="5" /><span class="add-on">&deg; C</span>
        <span class="add-on">Max.</span>
        <input type="text" name="max_temp2_limit" placeholder="Value" maxlength="3" size="5" /><span class="add-on">&deg; C</span>
        <?php if (isset($_SESSION['switch_to']) && $_SESSION['switch_to'] == 3) {?>
            <span class="add-on">Allowance.</span>
            <input type="text" name="allowance_temp2" placeholder="Value" maxlength="5" size="5" /><span class="add-on">&deg; C</span>
        <?php }?> <span id="addElem_<?php echo $tempSensName2; ?>" style="cursor: pointer;"><i class="fa fa-plus-square fa-2" onclick="addElements(this,'<?php echo $tempSensName2; ?>')"></i></span>
        &nbsp;&nbsp;&nbsp;<span class="f_legend"> Static Temperature <input type="checkbox" name="chkStaticTemp2" value="1"></span>
    </div>
    <div class="formSep ">
    </div>
    <?php
}
if ($_SESSION['temp_sensors'] == 1) {
    ?>
    <div class="input-prepend " style="width:80%;">
        <span class="f_legend">Temperature 1 Limits - </span>
        <span class="add-on">Min.</span>
        <input type="text" name="min_temp1_limit" placeholder="Value" maxlength="3" size="5" /><span class="add-on">&deg; C</span>
        <span class="add-on">Max.</span>
        <input type="text" name="max_temp1_limit" placeholder="Value" maxlength="3" size="5" /><span class="add-on">&deg; C</span>
        <?php if (isset($_SESSION['switch_to']) && $_SESSION['switch_to'] == 3) {?>
            <span class="add-on">Allowance.</span>
            <input type="text" name="allowance_temp1" placeholder="Value" maxlength="5" size="5" /><span class="add-on">&deg; C</span>
        <?php }?>
         <span id="addElem_<?php echo $tempSensName1; ?>" style="cursor: pointer;"><i class="fa fa-plus-square fa-2" onclick="addElements(this,'<?php echo $tempSensName1; ?>')"></i></span>
         &nbsp;&nbsp;&nbsp;<span class="f_legend"> Static Temperature <input type="checkbox" name="chkStaticTemp1" value="1"></span>
    </div>
    <div class="formSep ">
    </div>
    <?php
}
if ($_SESSION['use_humidity'] == 1) {
    ?>
    <br><br>
    <div class="input-prepend ">
        <span class="f_legend">Humidity Limits - </span>
        <span class="add-on">Min.</span>
        <input type="text" name="min_humidity_limit" placeholder="Value" maxlength="3" size="5" value="0"/><span class="add-on">&deg; C</span>
        <span class="add-on">Max.</span>
        <input type="text" name="max_humidity_limit" placeholder="Value" maxlength="3" size="5" value="0"/><span class="add-on">&deg; C</span>
    </div>
    <div class="formSep "> </div>
    <?php
}
?>
<fieldset>
    <div class="control-group pull-right">
        <input type="button" value="Add New <?php echo $custom; ?>" class="btn  btn-primary" onclick="submitvehicle();">
    </div>
</fieldset>
</form>
<script>
 var Count1 = 0;
    var Count2 = 0;
    var Count3 = 0;
    var Count4 = 0;
    function submitvehicle()
    {
        var totalElementCount = $( ".ranges" ).length;
        if (jQuery("#vehicleno").val() == "")
        {
            jQuery("#vehiclecomp").show();
            jQuery("#vehiclecomp").fadeOut(3000);
        }
        else
        {
            var vehicleno = jQuery("#vehicleno").val();
            //validate all range fields
            jQuery.ajax({
                type: "POST",
                url: "route_ajax.php",
                data: {vehicleno: vehicleno},
                async: true,
                cache: false,
                success: function (statuscheck) {
                    if (statuscheck == "ok")
                    {
                        jQuery("#createvehicle").submit();
                    }
                    else
                    {
                        jQuery("#samename").show();
                        jQuery("#samename").fadeOut(3000);
                    }
                }
            });
        }
    }
    function setKind(kind) {
        if (kind == "Warehouse") {
            jQuery("#overspeed").hide();
            jQuery("#fueltank").hide();
            jQuery("#checkpoint").hide();
        } else {
            jQuery("#overspeed").show();
            jQuery("#fueltank").show();
            jQuery("#checkpoint").show();
        }
    }
    jQuery(document).ready(function () {
        var kind = jQuery("#type").val();
        if(kind == "Warehouse"){
            setKind(kind);
        }
    });
    function addElements(thiselm,tempSensName){
        var splitTogetCount     = tempSensName.split("Temperature");
        var Count               = 0;
        if(splitTogetCount[1] == "1"){
             Count1 = Count1+1;
             Count  = Count1;
        }
        else if(splitTogetCount[1] == "2"){
             Count2 = Count2+1;
             Count  = Count2;
        }
        else if(splitTogetCount[1] == "3"){
              Count3 = Count3+1;
              Count  = Count3;
        }
        else{
              Count4 = Count4+1;
              Count  = Count4;
        }
        var thisElelement = $(thiselm).html();
        var mainDiv1 = '<span style="cursor: pointer;margin-left:250px;float:left"><i class="fa fa-minus-square" onclick="toggleDiv(this,'+tempSensName+')" title="Hide"></i></span><div class="MainRangeDiv_'+tempSensName+'" style="display:block"><div>';
        var element ='<div class="ranges"><br><label class="Slabel">Start Range</label> &nbsp;<input type="number" min="" max="" value="0" id="" name="min_temp_start" style="width:60px;" step=".10" onchange=checkwithMainMinVal(this)>&nbsp;<label class="Elabel">End Range</label>&nbsp;<input type="number"  min="" max="" value="0" id="" name="max_temp_end" style="width:60px;" step=".10" onchange=checkwithMainMaxVal(this)> &nbsp;<input type="color" value="" id="colorpallet" name="colorpallet" style="width:40px;"></div>';
        var mainDiv2 = '</div>';
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
function removeElements(thiselm,tempSensName) {
    $(thiselm).parent('.ranges').remove();
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
        var splitValuesTogetCurrElement    = spiltValues[0].split("Temperature");
            var MainMaxval          = $("input[name=max_temp"+splitValuesTogetCurrElement[1]+"_limit]").val();
           if(Count == totalElementCount){
                if(MainMaxval >= endVal)
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
       if(Count >1){
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