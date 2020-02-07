<?php
include_once ('busrouteFunctions.php');
?>

<div id="panelmap" style="margin-top: 80px;">
    <div id="color-palette"></div>
</div>
<div id="map" class="map" style="float:left;  height:450px"></div>
<div style="clear: both;">&nbsp;</div>
<script type="text/javascript">
    var customerrefreshfrqmap = <?php echo $_SESSION['customerno']; ?>;
</script>