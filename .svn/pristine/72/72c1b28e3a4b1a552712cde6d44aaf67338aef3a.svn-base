<?php
if (!isset($_SESSION)) {
    session_start();
}
if (!isset($_SESSION['timezone'])) {
    $_SESSION['timezone'] = 'Asia/Kolkata';
}
date_default_timezone_set($_SESSION['timezone']);
?>
<div id="gc-topnav2"  class="ch_bar"  style="background-color:#ffffff;width:360px;height:auto; display:none;position:absolute; left:20%; z-index:100;">
    <div id="chk_box" style="width:350px; height:auto; float:left; text-align:left;">
        <a class="a" id="address"> Search </a>  <input type="text" name="chkA" id="chkA"  class="chkp_inp" style="width: 280px;">&nbsp;
    </div>
</div>
<div id="panelmap" style="margin-top: 80px;">
    <div id="color-palette"></div>
    <div>
        <input type="button"  value="refresh" class="g-button g-button-submit" id="toggler1" onclick="refreshmap();" style="background:#000000;"  >
    </div>
</div>
<div id="map" class="map" style="float:left;  height:450px"></div>
<div style="clear: both;">&nbsp;</div>
<script type="text/javascript">
    var customerrefreshfrqmap = <?php echo $_SESSION['customerno']; ?>;
</script>