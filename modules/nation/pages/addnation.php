
<form method="POST" id="addnation" class="form-horizontal well "  style="width:70%;">
    <?php include 'panels/addnation.php';?>
	
	
	<fieldset>
			<div class="control-group">
			<div class="input-prepend ">
			<span class="add-on">Name <span class="mandatory">*</span></span> <input type="text" name="name" id="name" placeholder="Name" autofocus>
                
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
                        </div>
        </fieldset>
	
	<fieldset>
                  <div class="control-group pull-right">
                        <input type="button" value="Add <?php echo($_SESSION['nation']); ?>" class="btn btn-primary" onclick="addnation();">
                  </div>    
	</fieldset>
</form>