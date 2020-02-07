<?php
include_once "session.php";
include_once "db.php";
include_once "../../constants/speedConstants.php";
$message = "";

if (isset($_POST["username"]) && isset($_POST["password"])) {
	// Attempting to Log in.
	include_once "../../lib/system/DatabaseManager.php";
	$db = new DatabaseManager();
	$username = GetSafeValueString($_POST["username"], "string");
	$password = GetSafeValueString($_POST["password"], "string");
	$SQL = sprintf("SELECT st.* FROM " . DB_ELIXIATECH . ".team st
                    where BINARY st.username = '%s' and BINARY st.password = '%s'
                    limit 1 ", $username, $password);
	$db->executeQuery($SQL);
	while ($row = $db->get_nextRow()) {
		setDepartment($row["department_id"]);
		setCompRoleId($row["company_roleId"]);
		$arrAllowedIps = explode(",", speedConstants::ALLOWED_IPS);
		if (checkUserType(speedConstants::TEAM_DEPARTMENT_SALES)) {
			SetUsername($row["username"]);
			SetLoggedInUserId($row["teamid"]);
			SetLoggedInrid($row["rid"]);
			SetLoginUser($row["name"]);
			SetRole($row["role"]);
			setDepartment($row["department_id"]);
			setRoleId($row["role_id"]);
			header("Location: sales_pipeline.php");
		}
		if (checkUserType(speedConstants::TEAM_DEPARTMENT_OTHERS, 0, speedConstants::DISTRIBUTOR_COMP_ROLE)) {
			SetUsername($row["username"]);
			SetLoggedInUserId($row["teamid"]);
			SetLoggedInrid($row["rid"]);
			SetLoginUser($row["name"]);
			SetRole($row["role"]);
			setDepartment($row["department_id"]);
			setRoleId($row["role_id"]);
			header("Location: distCustDetails.php");
		} else if (in_array($_SERVER['REMOTE_ADDR'], $arrAllowedIps) || $row["teamid"] == 6) {
			SetUsername($row["username"]);
			SetLoggedInUserId($row["teamid"]);
			SetLoggedInrid($row["rid"]);
			SetLoginUser($row["name"]);
			SetRole($row["role"]);
			setDepartment($row["department_id"]);
			setRoleId($row["role_id"]);
			if (IsDealer()) {
				$_SESSION["sessiondistributorid"] = $row["distributor_id"];
			} else if (checkUserType(speedConstants::TEAM_DEPARTMENT_SALES)) {
				header("Location: updateTimesheet.php");
			} else if (IsData()) {
				header("Location: updateTimesheet.php");
			} else if (IsDistributor()) {
				header("Location: dealers.php");
			} else if (IsDealer()) {
				header("Location: retailers.php");
			} else if (IsRepair()) {
				header("Location: repairview.php");
			} else {
				header("Location: updateTimesheet.php");
			}
			exit;
		} else {
			$message = "Invalid Login";
		}
	}
	$message = "Invalid Login";
}

include "header.php";
?>
<link href="https://fonts.googleapis.com/css?family=Heebo:100,300,400,500,700,800,900" rel="stylesheet">
<link rel="stylesheet" href="../../css/team_login.css">
<div class="img-wrap">
<img src="../../images/elixia_team.png" alt="product-logo" style="float: left;">
<div style="padding-top: 40px"><span class="thin-font">ELIXIA</span> <span class="thick-font">TEAM</span></div>
</div>
<div class="col-md-10 col-md-offset-1" style="margin-top:150px; height: 100px; line-height: 75px;">
    <marquee scrollamount="12" behavior="scroll" height="100" class="thin-font">We have migrated to new server, Please <a href="http://speed.elixiatech.com/modules/team/login.php" target="_blank" style="color: red;">click here</a> for Team.</marquee>
</div>
<div class="login-page">
  <div class="form">
        <div class="invalid_login"><?php echo ($message); ?></div>
        <br>
        <form method="post" action="login.php" class="login-form">
            <div><label for="username">Username</label><input id="username" type="text" name="username" placeholder="Enter Username" /></div>
            <div><label for="password">Password</label><input id="password" type="password" name="password" placeholder="Enter Password"/></div>
            <div><input type="submit" value="Login" /></div>
        </form>
  </div>
</div>
</body>
<?php
include "footer.php";
?>
