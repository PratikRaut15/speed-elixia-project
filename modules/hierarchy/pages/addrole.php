<?php
$objRole = new Hierarchy();
$objRole->roleid = '';
$objRole->parentroleid = '';
$objRole->moduleid = '2';
$objRole->customerno = $_SESSION['customerno'];
$groups = getRolesByCustomer($objRole);
?>

<form  class="form-horizontal well " name="createvehicle" id="createvehicle" action="action.php?action=addrole" method="POST" style="width:70%;">
    <?php include 'panels/addvehicle.php'; ?>    
    <fieldset>
        <div class="control-group">
            <div class="input-prepend ">
                <span class="add-on">
                    Role    <span class="mandatory">*</span>
                </span>
                <input type="text" name="role" id="role" placeholder="Enter Role" autofocus maxlength="20">
            </div>
        </div>
    </fieldset>


    <fieldset>
        <div class="control-group">
            <div class="input-prepend ">
                <span class="add-on">Parent Role </span>
                <select id="roleid" name="roleid" >
                    <option value="0">Select Role</option>
                    <?php
                    if (isset($groups) && !empty($groups)) {
                        foreach ($groups as $group) {
                            echo "<option value='$group[id]'>$group[role]</option>";
                        }
                    }
                    ?>
                </select>
                <input type="hidden" name="moduleid" id="moduleid" value="2"/>
            </div>
        </div>
    </fieldset>


    <fieldset>
        <div class="control-group pull-right">
            <input type="submit" value="Add New Role" class="btn  btn-primary">
        <!--    
        <input type="button" value="Add New Vehicle" class="btn  btn-primary" onclick="submitvehicle();">
        -->
        </div>      
    </fieldset>
</form>
<script>
    function submitvehicle()
    {
        if (jQuery("#vehicleno").val() == "")
        {
            jQuery("#vehiclecomp").show();
            jQuery("#vehiclecomp").fadeOut(3000);
        }

        else
        {

            var vehicleno = jQuery("#vehicleno").val();
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
</script>