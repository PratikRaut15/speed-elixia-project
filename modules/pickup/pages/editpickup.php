<?php
$userid = $_REQUEST['uid'];
$user = getpickupboy($userid);
$pins = getpinno($userid);
$rtt = '';
if (isset($pins) && !empty($pins)) {
  $rt = Array();
  foreach ($pins as $pin) {
    $rt[] = $pin->pincode;
  }
  $rtt = implode(',', $rt);
}
?>

<form method="POST" id="edituser" name="edituser" class="form-horizontal well "  style="width:70%;" >
  <?php include 'panels/addpickup.php'; ?>
  <fieldset>
    <div class="control-group">
      <div class="input-prepend ">
        <span class="add-on">Name <span class="mandatory">*</span></span>
        <input type="text" name="pickupname" id="pickupname" placeholder="Name" autofocus value="<?php echo $user->name; ?>">
        <input type="hidden" name="pickupuser" id="pickupuser" value="<?php echo$userid; ?>">

      </div>
      <div class="input-prepend ">
        <span class="add-on">Email </span>
        <input type="text" name="email" id="email" placeholder="Émail ID " value="<?php echo $user->email; ?>" >
      </div>
    </div>
  </fieldset>

  <fieldset>
    <div class="control-group">
      <div class="input-prepend ">
        <span class="add-on">Username <span class="mandatory">*</span></span>
        <input type="text" name="username" id="username" placeholder="UserName" autofocus value="<?php echo $user->username; ?>">


      </div>
      <div class="input-prepend ">
        <span class="add-on">Password <span class="mandatory">*</span></span>
        <input type="password" name="password" id="password" placeholder="Password" value="" >
      </div>
    </div>
  </fieldset>


  <fieldset>
    <div class="control-group">
      <div class="input-prepend ">
        <span class="add-on">Phone No <span class="mandatory">*</span></span><span class="add-on">+91</span>
        <input type="text" name="phoneno" id="phoneno" placeholder="Phone No" value="<?php echo $user->phone; ?>" />

      </div>
      <div class="input-prepend ">
        <span class="add-on">Pincode </span>
        <textarea id="pins" name="pins"><?php echo $rtt; ?></textarea>

      </div>

    </div>
  </fieldset>
  <fieldset>
    <div class="control-group">
      <div class="input-prepend ">
        <span class="add-on">Photo</span>
        <?php
        if ($user->pickupboyimg != '') {
          echo '<img src="' . base64_decode($user->pickupboyimg) . '" width="200px;" height="150px;" />';
        }
        ?>
      </div>
      <div class="input-prepend">
        <span>Upload Photo</span> <input type="file" name="pickupboyphoto" id="pickupboyphoto">
        <input id="base64img" name="base64img" type="hidden"/>
      </div>

    </div>
    Note: New image uploaded would be visible after modification.
  </fieldset>

  <fieldset>
    <div class="control-group pull-right">
      <input type="submit" value="Modify User" name='create_user' class="btn btn-primary" onclick="editPickup();
          return false;" ></div>
  </fieldset>

</form>