<?php $nation = getnation($_REQUEST['nationid']);?>
<form method="POST" id="editnation" class="form-horizontal well "  style="width:70%;">
    <?php include 'panels/editnation.php';?>
    
	<fieldset>
			<div class="control-group">
			<div class="input-prepend ">
			<span class="add-on">Name <span class="mandatory">*</span></span> <input type="text" name="name" id="name" placeholder="Name" value="<?php echo $nation->name;?>" autofocus>
                
			</div>
			<div class="input-prepend ">
			<span class="add-on">Code <span class="mandatory">*</span></span>  <input type="text" name="code" id="code" placeholder="Code" value="<?php echo $nation->code;?>">
                
			</div>
			</div>
	</fieldset>
    
    
	<fieldset>
			<div class="control-group">
			<div class="input-prepend ">
			<span class="add-on">Address <span class="mandatory">*</span></span> <textarea type="text" name="address" id="address" placeholder="Address"><?php echo $nation->address;?></textarea>
                
			</div>
                        </div>
        </fieldset>
	
	<fieldset>
                  <div class="control-group pull-right">
                      <input type="hidden" value="<?php echo $nation->nationid;?>" name="nationid" id="nationid">
                        <input type="button" value="Edit <?php echo($_SESSION['nation']); ?>" class="btn btn-primary" onclick="editnation();">
                  </div>    
	</fieldset>
    
</form>