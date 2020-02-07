<script type="text/javascript">

</script>

<style type="text/css">
body {
font-family:verdana;
font-size:15px;
}

a {color:#333; text-decoration:none}
a:hover {color:#ccc; text-decoration:none}

#mask{
  position:absolute;
  left:0;
  top:0;
  z-index:9000;
  background-color:#000;
  display:none;
}
#mask1 {
  position:absolute;
  left:0;
  top:0;
  z-index:9000;
  display:none;
  background-color:#000;
}
#boxes .window, .window1 {
  position:absolute;
  left:0;
  top:0;
  width:440px;
  height:200px;
  display:none;
  z-index:9999;
  padding:20px;
}
#boxes #dialog {
  width:375px; 
  height:170px;
  padding:10px;
  background-color:#ffffff;
}
#boxes #dialog1 {
  width:412px; 
  height:236px;
  padding:10px;
  background-color:#ffffff;
}
</style>
<div id="boxes">
<div style="top: 54%; left: 551.5px; display: none;" id="dialog" class="window">

<table id="floatingpanel1">
    <thead>
    <tr>
        <th id="formheader" colspan="2">Change Password</th>
    </tr>
    </thead>
    <tr>
        <td colspan="2" id="incorrect" style="display: none">Password Does Not Match</td>
        <td colspan="2" id="perfect" style="display: none">Password Changed</td>        
        <td colspan="2" id="newempty" style="display: none">Please Enter New Password</td>                
        <td colspan="2" id="confirmempty" style="display: none">Please Enter Confirm Password</td>                        
    </tr>
    <tr>
        <td>New Password <?php echo $user; ?></td>
        <td><input type="password" name="newpasswd" id="newpasswd" maxlength="10" size="15" placeholder="New Password"></td>
    </tr>
    <tr>
        <td>Confirm Password</td>
        <td>
            <input type="password" name="confirm_newpasswd" id="confirm_newpasswd" maxlength="10" size="15" placeholder="Confirm New Password">
        </td>
    </tr>
    <tr>
    <td align="center"><input type="button" class="g-button g-button-submit" name="password" value="Modify" onclick="dosave_modal();"></td>
	<td align="center"><input type="button" id="close_popup" class="g-button g-button-submit" name="password" value="Skip"></td>
    </tr>
</table>
</div>
<div style="top: 36%; left: 583px; display: none;" id="dialog1" class="window1">
<?php include 'modal_myaccount.php'; ?>
</div>
<!-- Mask to cover the whole screen -->
<div style="width: 1478px; height: 602px; display: none; opacity: 0.8;" onclick="updategroupid();" id="mask"></div>
</div>
<div style="width: 1478px; height: 602px; display: none; opacity: 0.8;" id="mask1"></div>
</div>