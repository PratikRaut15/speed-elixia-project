<?php
include_once("session.php");
include_once("../constants/constants.php");
ClearSession();
include("header.php");
?>
<form method="post" action="index.php";
<div class="panel">
<div class="paneltitle" align="center">You have been logged out.</div>
<div class="panelcontents">
<p>To log in again, press here. <input type="submit" value="Log in"/>
</div>
</div>
</form>

<?php
include("footer.php");
?>