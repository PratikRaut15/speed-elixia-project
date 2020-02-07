<ul id="tabnav">
  <li><a class="selected" href="travelSettings.php?id=1">Add Travel Settings</a></li>
   <li><a href="travelSettings.php?id=2">View Travel Settings</a></li>
</ul>
<form action="travelSettings_ajax.php" id="travelsettingform" style="width: 43%; margin-top: 50px;" type="post">
<fieldset>
	<div class="control-group">
  <input type="hidden"  id="travelsettingid" value="<?php echo $_GET['travelsettingid'];?>" name="travelsettingid"/>
        <div class="input-prepend">
            <span class="add-on">Night Drive Start Time: <span class="mandatory">*</span></span> <input type="text" min="" max="" value="<?php echo $_GET['starttime']; ?>"  id="starttime" name="starttime" style="width:60px;">
        </div>
        <div class="input-prepend">
            <span class="add-on">Night Drive End Time: <span class="mandatory">*</span></span> <input type="text" min="" max="" value="<?php echo $_GET['endtime']; ?>"  id="endtime" name="endtime" style="width:60px;">
        </div>
         <div class="input-prepend">
            <span class="add-on">Threshold: <span class="mandatory">*</span></span> <input type="text" name="threshold" id="threshold" placeholder="Threshold" autofocus="" value="<?php echo $_GET['threshold']; ?>" readonly>
        </div>
              <button type="button" class="btn btn-default" onclick="submitTravelSettings()">Save</button>
    </div>
</fieldset>

</form>