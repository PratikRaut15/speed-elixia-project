<?php
$eid = $_GET['eid'];
$objRole = new Hierarchy();
$objRole->roleid = $eid;
$objRole->parentroleid = '';
$objRole->moduleid = '2';
$objRole->customerno = $_SESSION['customerno'];
$roles = getRolesByCustomer($objRole);
//print_r($roles);

/* Get All Roles */
$objRole1 = new Hierarchy();
$objRole1->roleid = '';
$objRole1->parentroleid = '';
$objRole1->moduleid = '2';
$objRole1->customerno = $_SESSION['customerno'];
$groups = getRolesByCustomer($objRole1);
?>
<form  class="form-horizontal well " name="createvehicle" id="createvehicle" action="action.php?action=editrole" method="POST" style="width:70%;">
    <?php include 'panels/addvehicle.php'; ?>    
    <fieldset>
        <div class="control-group">
            <div class="input-prepend ">
                <span class="add-on">
                    Role    <span class="mandatory">*</span>
                </span>
                <input type="text" name="role" id="role" placeholder="Enter Role" value="<?php echo $roles[0][role]?>">
                <input type="hidden" name="rid" id="rid" value="<?php echo $roles[0][id];?>"/>
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
                            if($roles[0][pid] == $group[id]){
                            
                            echo "<option value='$group[id]' selected>$group[role]</option>";        
                            }else{
                              echo "<option value='$group[id]'>$group[role]</option>";  
                            }
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
