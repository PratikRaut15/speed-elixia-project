<?php $unit=  getunit($_GET['uid']);?>
<form action="route.php" method="POST" class="form-horizontal well "  style="width:50%;">
    <?php include 'panels/editunit.php';?>
	
	<fieldset>
			<div class="control-group">
			<div class="input-prepend ">
			<span class="add-on">Phone No <span class="mandatory">*</span></span><input type="text" name="phoneno" value="<?php echo $unit->phone;?>" required maxlength="20" autofocus>
                <input type="hidden" name="uid" value="<?php echo $unit->uid;?>" required>
			</div>
			
			</div>
			</fieldset>
	<fieldset>
				<div class="control-group pull-right">
				
					<input class='btn  btn-primary' type="submit" value="Modify Unit">
					
			  </div>      
	
	</fieldset>
	
        
</form>
