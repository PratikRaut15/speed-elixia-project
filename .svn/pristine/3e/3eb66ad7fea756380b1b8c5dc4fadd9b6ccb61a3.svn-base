<?php $city = getcity($_REQUEST['cityid']);?>
<form method="POST" id="editcity" class="form-horizontal well "  style="width:70%;">
    <?php include 'panels/editcity.php';?>
    
	<fieldset>
			<div class="control-group">
			<div class="input-prepend ">
			<span class="add-on">Name <span class="mandatory">*</span></span> <input type="text" name="name" id="name" placeholder="Name" value="<?php echo $city->name;?>" autofocus>
                
			</div>
			<div class="input-prepend ">
			<span class="add-on">Code <span class="mandatory">*</span></span>  <input type="text" name="code" id="code" placeholder="Code" value="<?php echo $city->code;?>">
                
			</div>
			</div>
	</fieldset>
    
    
	<fieldset>
			<div class="control-group">
			<div class="input-prepend ">
			<span class="add-on">Address <span class="mandatory">*</span></span> <textarea type="text" name="address" id="address" placeholder="Address"><?php echo $city->address;?></textarea>
                
			</div>
                        <div class="input-prepend ">
			<span class="add-on"><?php echo ($_SESSION['nation']); ?> </span>
                        <select id="nationid" name="nationid" onchange="getstate()">
                            <option value="">Select <?php echo ($_SESSION['nation']); ?></option>
                            <?php
                            $nations = getnations($_SESSION['userid']);
                            if(isset($nations))
                            {
                            foreach ($nations as $nation)
                            {
                                if($city->nationid == $nation->nationid){
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
                        </div>
        </fieldset>
    
        <fieldset>
			<div class="control-group">
			<div class="input-prepend ">
			<span class="add-on"><?php echo ($_SESSION['state']); ?> </span><select id="stateid" name="stateid" onchange="getdistrict()">
                            <option value="">Select <?php echo ($_SESSION['state']); ?></option>
                <?php
                $states = getstates($_SESSION['userid']);
                print_r($district);
                if(isset($states))
                {
                foreach ($states as $state)
                {
                                if($city->stateid == $state->stateid){
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
			<div class="input-prepend ">
			<span class="add-on"><?php echo ($_SESSION['district']); ?> </span><select id="districtid" name="districtid" onchange="getcity()">
                            <option value="">Select <?php echo ($_SESSION['district']); ?></option>
                <?php
                $districts = getdistricts($_SESSION['userid']);
                if(isset($districts))
                {
                foreach ($districts as $district)
                {
                                if($city->districtid == $district->districtid){
                                echo "<option value='$district->districtid' selected='selected'>$district->districtname</option>";
                                }
                                else{
                                echo "<option value='$district->districtid'>$district->districtname</option>";
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
                      <input type="hidden" value="<?php echo $city->cityid;?>" name="cityid" id="cityid">
                        <input type="button" value="Edit <?php echo ($_SESSION['city']); ?>" class="btn btn-primary" onclick="editcity();">
                  </div>    
	</fieldset>
    
</form>