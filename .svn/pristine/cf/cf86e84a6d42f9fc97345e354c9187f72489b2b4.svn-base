<script type="text/javascript">
  jQuery(function () {
    jQuery("#genset1").autocomplete({
      source: "route_ajax.php?action=genset",
      minLength: 1,
      select: function (event, ui) {
        jQuery('#gensetid1').val(ui.item.id);
      }
    });
    jQuery("#genset2").autocomplete({
      source: "route_ajax.php?action=genset",
      minLength: 1,
      select: function (event, ui) {
        jQuery('#gensetid2').val(ui.item.id);
      }
    });
    jQuery("#transmitter1").autocomplete({
      source: "route_ajax.php?action=transmitter",
      minLength: 1,
      select: function (event, ui) {
        jQuery('#transmitterid1').val(ui.item.id);
      }
    });
    jQuery("#transmitter2").autocomplete({
      source: "route_ajax.php?action=transmitter",
      minLength: 1,
      select: function (event, ui) {
        jQuery('#transmitterid2').val(ui.item.id);
      }
    });
  });
</script>
<?php
$vehicle = getvehicle($_GET['vid']);
?>
<form  class="form-horizontal well " id="editvehicle"  action="route.php" method="POST" style="width:70%;">
  <input  type="hidden" name="vehicleid" value="<?php echo $_GET['vid']; ?>"  />
  <input  type="hidden" name="uid" value="<?php echo $vehicle->uid; ?>"  />
  <?php include 'panels/editvehicle.php'; ?>
  <fieldset>
    <div class="control-group">
      <div class="input-prepend ">
        <span class="add-on"><?php echo $vehicles_ses; ?><span class="mandatory">*</span></span><input type="text" name="vehicleno"  value="<?php echo $vehicle->vehicleno; ?>" id="vehicleno" placeholder="Enter <?php echo $vehicles_ses; ?>" readonly="" maxlength="20">
      </div>
    </div>
  </fieldset>

  <fieldset>
    <div class="control-group">
      <div class="input-prepend ">
        <span class="add-on">Genset 1</span><input type="text" name="genset1"  value="<?php echo $vehicle->genset1; ?>" id="genset1" placeholder="Enter Genset No" >
        <input type="hidden" name="gensetid1" value="" id="gensetid1"/>
      </div>
    </div>
  </fieldset>

  <fieldset>
    <div class="control-group">
      <div class="input-prepend ">
        <span class="add-on">Genset 2</span><input type="text" name="genset2"  value="<?php echo $vehicle->genset2; ?>" id="genset2" placeholder="Enter Genset No" >
        <input type="hidden" name="gensetid2" value="" id="gensetid2"/>
      </div>
    </div>
  </fieldset>

  <fieldset>
    <div class="control-group">
      <div class="input-prepend ">
        <span class="add-on">Transmitter 1</span><input type="text" name="transmitter1"  value="<?php echo $vehicle->transmitter1; ?>" id="transmitter1" placeholder="Enter Transmitter No">
        <input type="hidden" name="transmitterid1" value="" id="transmitterid1"/>
      </div>
    </div>
  </fieldset>

  <fieldset>
    <div class="control-group">
      <div class="input-prepend ">
        <span class="add-on">Transmitter 2</span><input type="text" name="transmitter2"  value="<?php echo $vehicle->transmitter2; ?>" id="transmitter2" placeholder="Enter Transmitter No">
        <input type="hidden" name="transmitterid2" value="" id="transmitterid2"/>
      </div>
    </div>
  </fieldset>

  <fieldset>
    <div class="control-group">
      <input type="button" class="btn  btn-primary" value="Save" onclick="editvehicle();">
      <input type="reset" class="btn  btn-danger" value="Cancel">
    </div>

  </fieldset>


</form>

<script>
  function editvehicle()
  {
    if (jQuery("#vehicleno").val() == "")
    {
      jQuery("#vehiclecomp").show();
      jQuery("#vehiclecomp").fadeOut(3000);
    }

    else
    {
      jQuery("#editvehicle").submit();
    }
  }
</script>