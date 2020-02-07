<script src="scripts/aeart.js " type="text/javascript"></script>
<form name="aeart" id="aeart" action="route.php" method="POST" class="form-horizontal well " style="width:70%;">
    <?php
    include 'panels/editart.php';
    $art = getarticle($_GET['aid']);?>
	<fieldset>
			<div class="control-group">
			<div class="input-prepend ">
			<span class="add-on">Article Name<span class="mandatory">*</span></span><input type="text" name="artname" id="artname" value="<?php echo $art->artname;?>">
            <input type="hidden" name="artid" id="artid" value="<?php echo $art->artid;?>">
			</div>
			<div class="input-prepend ">
			<span class="add-on">Min Temp </span><input type="number" name="mintemp" id="mintemp" step="any" value="<?php echo $art->mintemp;?>">
			</div>
			<div class="input-prepend ">
			<span class="add-on">Max Temp </span><input type="number" name="maxtemp" id="maxtemp" step="any" value="<?php echo $art->maxtemp;?>">
			</div>
			</div>
			</fieldset>
	<fieldset>
				<div class="control-group pull-right">
				
				<input type="button" value="Submit" onclick="addarticle();">
					
			  </div>      
	
	</fieldset>
	
	
</form>