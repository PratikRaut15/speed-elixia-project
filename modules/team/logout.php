<?php
include_once("session.php");
include_once("../../constants/constants.php");
ClearSession();
include("header.php");
?>
<link href="https://fonts.googleapis.com/css?family=Heebo:100,300,400,500,700,800,900" rel="stylesheet">
<link rel="stylesheet" href="../../css/team_login.css">
<div class="img-wrap">
<img src="../../images/elixia_team.png" alt="product-logo" style="float: left;">
<div style="padding-top: 40px"><span class="thin-font">ELIXIA</span> <span class="thick-font">TEAM</span></div>
</div>
<div class="login-page">
  <div class="form">
		<form method="post" action="index.php" class="login-form">
			<label>Click Here To Login Again.</label>
			<input type="submit" value="Log in"/>
		</form>
	</div>
</div>
</body>
<?php
include("footer.php");
?>