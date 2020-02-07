
<form method="POST" id="addstate" class="form-horizontal well "  style="width:70%;">
    <?php include 'panels/addstate.php';?>
	
	<fieldset>
			<div class="control-group">
			<div class="input-prepend ">
			<span class="add-on">Name <span class="mandatory">*</span></span> <input type="text" name="name" id="sname" placeholder="Name" autofocus>
                
			</div>
			<div class="input-prepend ">
			<span class="add-on">Code <span class="mandatory">*</span></span>  <input type="text" name="code" id="code" placeholder="Code">
                
			</div>
			</div>
	</fieldset>
    
    
	<fieldset>
			<div class="control-group">
			<div class="input-prepend ">
			<span class="add-on">Address <span class="mandatory">*</span></span> <textarea type="text" name="address" id="address" placeholder="Address"></textarea>
                
			</div>
			<div class="input-prepend ">
			<span class="add-on"><?php echo($_SESSION["nation"]); ?> </span><select id="nationid" name="nationid">
                            <option value="">Select <?php echo($_SESSION["nation"]); ?></option>
                            <?php
                            $nations = getnations($_SESSION['userid']);
                            if(isset($nations))
                            {
                            foreach ($nations as $nation)
                            {
                            echo "<option value='$nation->nationid'>$nation->name</option>";
                            }
                            }
                            ?>
                        </select>
                
			</div>
                        </div>
        </fieldset>
	
	<fieldset>
                  <div class="control-group pull-right">
                        <input type="button" value="Add <?php echo($_SESSION["state"]); ?>" class="btn btn-primary" onclick="addstate();">
                  </div>    
	</fieldset>
</form>