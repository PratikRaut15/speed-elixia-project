<?php $district = getdistrict($_REQUEST['districtid']);?>
<form method="POST" id="editdistrict" class="form-horizontal well "  style="width:70%;">
    <?php include 'panels/editdistrict.php';?>
    
	<fieldset>
			<div class="control-group">
			<div class="input-prepend ">
			<span class="add-on">Name <span class="mandatory">*</span></span> <input type="text" name="name" id="name" placeholder="Name" value="<?php echo $district->name;?>" autofocus>
                
			</div>
			<div class="input-prepend ">
			<span class="add-on">Code <span class="mandatory">*</span></span>  <input type="text" name="code" id="code" placeholder="Code" value="<?php echo $district->code;?>">
                
			</div>
			</div>
	</fieldset>
    
    
	<fieldset>
			<div class="control-group">
			<div class="input-prepend ">
			<span class="add-on">Address <span class="mandatory">*</span></span> <textarea type="text" name="address" id="address" placeholder="Address"><?php echo $district->address;?></textarea>
                
			</div>
                        <div class="input-prepend ">
			<span class="add-on"><?php echo($_SESSION['nation']); ?> </span>
                        <select id="nationid" name="nationid"  onchange="getstate()">
                            <option value="">Select <?php echo($_SESSION['nation']); ?></option>
                            <?php
                            $nations = getnations($_SESSION['userid']);
                            if(isset($nations))
                            {
                            foreach ($nations as $nation)
                            {
                                if($district->nationid == $nation->nationid){
                                echo "<option value='$nation->nationid' selected='selected'>$nation->name</option>";
                                }
                                else{
                                echo "<option value='$nation->nationid'>$nation->name</option>";
                                }
                            }
                            }
                            ?>
                        </select>
                
			</div>
			<div class="input-prepend ">
			<span class="add-on"><?php echo($_SESSION['state']); ?> </span><select id="stateid" name="stateid">
                            <option value="">Select <?php echo($_SESSION['state']); ?></option>
                <?php
                $states = getstates($_SESSION['userid']);
                if(isset($states))
                {
                foreach ($states as $state)
                {
                                if($district->stateid == $state->stateid){
                                echo "<option value='$state->stateid' selected='selected'>$state->statename</option>";
                                }
                                else{
                                echo "<option value='$state->stateid'>$state->statename</option>";
                                }
                }
                }
                ?>
                </select>
                
			</div>
                        </div>
        </fieldset>
	
	<fieldset>
                  <div class="control-group pull-right">
                      <input type="hidden" value="<?php echo $district->districtid;?>" name="districtid" id="districtid">
                        <input type="button" value="Edit <?php echo($_SESSION['district']); ?>" class="btn btn-primary" onclick="editdistrict();">
                  </div>    
	</fieldset>
    
</form>