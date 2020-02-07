<?php

include 'user_functions.php';

if (isset($_POST['username']) && isset($_POST['password'])) {
	checklogin(GetSafeValueString($_POST['username'], "string"), GetSafeValueString($_POST['password'], "string"));
} elseif (isset($_POST['kmTracked']) && isset($_POST['data1'])) {
	$km_tracked = get_km_tracked($_POST['data1']);
	echo json_encode($km_tracked);
	exit;
} elseif (isset($_POST["oldpwd"]) && isset($_POST["newpwd"])) {
	chkandchangepasswd(GetSafeValueString($_POST["oldpwd"], "string"), GetSafeValueString($_POST["newpwd"], "string"));
} elseif (isset($_REQUEST["newpwd"])) {
	chkandchangepasswd_modal(GetSafeValueString($_REQUEST["newpwd"], "string"));
} elseif (isset($_POST['uname'])) {
	if ($_POST['uname'] != '') {
		generatepass(GetSafeValueString($_POST['uname'], "string"));
	} else {
		echo 'notok';
	}
} elseif (isset($_POST['name']) && isset($_POST['email']) && isset($_POST['phoneno']) && isset($_POST['role'])) {
	$name = GetSafeValueString($_POST['name'], "string");
	$email = GetSafeValueString($_POST['email'], "string");
	$phoneno = GetSafeValueString($_POST['phoneno'], "string");
	$role = GetSafeValueString($_POST['role'], "string");
	modifyuser($name, $email, $phoneno, $role);
} elseif (isset($_POST['email']) && isset($_POST['phoneno']) && isset($_POST['alerts']) == 'true') {
	//$name = GetSafeValueString($_POST['name'],"string");
	$email = GetSafeValueString($_POST['email'], "string");
	$phoneno = GetSafeValueString($_POST['phoneno'], "string");
	//$role = GetSafeValueString($_POST['role'],"string");
	modifyuser_modal($email, $phoneno);
	if ($_POST['geosms']) {
		$geosms = GetSafeValueString($_POST['geosms'], "string");
	}
	if ($_POST['geoemail']) {
		$geoemail = GetSafeValueString($_POST['geoemail'], "string");
	}

	if ($_POST['ospeedsms']) {
		$ospeedsms = GetSafeValueString($_POST['ospeedsms'], "string");
	}
	if ($_POST['ospeedemail']) {
		$ospeedemail = GetSafeValueString($_POST['ospeedemail'], "string");
	}
	if ($_POST['powercsms']) {
		$powercsms = GetSafeValueString($_POST['powercsms'], "string");
	}
	if ($_POST['powercemail']) {
		$powercemail = GetSafeValueString($_POST['powercemail'], "string");
	}
	if ($_POST['tampersms']) {
		$tampersms = GetSafeValueString($_POST['tampersms'], "string");
	}
	if ($_POST['tamperemail']) {
		$tamperemail = GetSafeValueString($_POST['tamperemail'], "string");
	}
	if ($_POST['chksms']) {
		$chksms = GetSafeValueString($_POST['chksms'], "string");
	}

	if ($_POST['chkemail']) {
		$chkemail = GetSafeValueString($_POST['chkemail'], "string");
	}
	if ($_POST['acsms']) {
		$acsms = GetSafeValueString($_POST['acsms'], "string");
	}

	if ($_POST['acemail']) {
		$acemail = GetSafeValueString($_POST['acemail'], "string");
	}

	if ($_POST['igsms']) {
		$igsms = GetSafeValueString($_POST['igsms'], "string");
	}
	if ($_POST['igemail']) {
		$igemail = GetSafeValueString($_POST['igemail'], "string");
	}
	if ($_POST['tempsms']) {
		$tempsms = GetSafeValueString($_POST['tempsms'], "string");
	}
	if ($_POST['tempemail']) {
		$tempemail = GetSafeValueString($_POST['tempemail'], "string");
	}
	if ($_POST['dailyemail']) {
		$dailyemail = GetSafeValueString($_POST['dailyemail'], "string");
	}
	if ($_POST['dailyemail_csv']) {
		$dailyemail_csv = GetSafeValueString($_POST['dailyemail_csv'], "string");
	}
	$user = new VOUser();
	$usermanager = new UserManager();
	$user->mess_sms = 1;
	if (!isset($geosms)) {
		$user->mess_sms = 0;
	}
	$user->mess_email = 1;
	if (!isset($geoemail)) {
		$user->mess_email = 0;
	}
	$user->speed_sms = 1;
	if (!isset($ospeedsms)) {
		$user->speed_sms = 0;
	}
	$user->speed_email = 1;
	if (!isset($ospeedemail)) {
		$user->speed_email = 0;
	}
	$user->power_sms = 1;
	if (!isset($powercsms)) {
		$user->power_sms = 0;
	}
	$user->power_email = 1;
	if (!isset($powercemail)) {
		$user->power_email = 0;
	}
	$user->tamper_sms = 1;
	if (!isset($tampersms)) {
		$user->tamper_sms = 0;
	}
	$user->tamper_email = 1;
	if (!isset($tamperemail)) {
		$user->tamper_email = 0;
	}

	$user->chk_sms = 1;
	if (!isset($chksms)) {
		$user->chk_sms = 0;
	}
	$user->chk_email = 1;
	if (!isset($chkemail)) {
		$user->chk_email = 0;
	}

	$user->ac_sms = 1;
	if (!isset($acsms)) {
		$user->ac_sms = 0;
	}
	$user->ac_email = 1;
	if (!isset($acemail)) {
		$user->ac_email = 0;
	}
	$user->ignition_sms = 1;
	if (!isset($igsms)) {
		$user->ignition_sms = 0;
	}
	$user->ignition_email = 1;
	if (!isset($igemail)) {
		$user->ignition_email = 0;
	}
	$user->temp_sms = 1;
	if (!isset($tempsms)) {
		$user->temp_sms = 0;
	}
	$user->temp_email = 1;
	if (!isset($tempemail)) {
		$user->temp_email = 0;
	}
	$user->dailyemail = 1;
	if (!isset($dailyemail)) {
		$user->dailyemail = 0;
	}
	$user->dailyemail_csv = 1;
	if (!isset($dailyemail_csv)) {
		$user->dailyemail_csv = 0;
	}

	$user_data = getuser();
	if ($user_data->use_advanced_alert == 1) {
		$user->harsh_break_sms = isset($_POST['harsh_break_sms']) ? 1 : 0;
		$user->harsh_break_mail = isset($_POST['harsh_break_mail']) ? 1 : 0;
		$user->high_acce_sms = isset($_POST['high_acce_sms']) ? 1 : 0;
		$user->high_acce_mail = isset($_POST['high_acce_mail']) ? 1 : 0;
		$user->sharp_turn_sms = isset($_POST['sharp_turn_sms']) ? 1 : 0;
		$user->sharp_turn_mail = isset($_POST['sharp_turn_mail']) ? 1 : 0;
		$user->towing_sms = isset($_POST['towing_sms']) ? 1 : 0;
		$user->towing_mail = isset($_POST['towing_mail']) ? 1 : 0;
	} else {
		$user->harsh_break_sms = $user->harsh_break_mail = $user->high_acce_sms = $user->high_acce_mail = $user->sharp_turn_sms = $user->sharp_turn_mail = $user->towing_sms = $user->towing_mail = 0;
	}

	$user->door_sms = isset($_POST['doorsms']) ? 1 : 0;
	$user->door_email = isset($_POST['dooremail']) ? 1 : 0;
	$user->start_alert_time = GetSafeValueString($_POST['STime'], "string");
	$user->stop_alert_time = GetSafeValueString($_POST['ETime'], "string");
	$user->userid = $_SESSION['userid'];
	$user->customerno = $_SESSION['customerno'];

	$usermanager->modifyalerts_all($user, $_SESSION['userid']);

	$safcsms = GetSafeValueString($_POST['safcsms'], "string");
	$safcemail = GetSafeValueString($_POST['safcemail'], "string");
	$saftsms = GetSafeValueString($_POST['saftsms'], "string");
	$saftemail = GetSafeValueString($_POST['saftemail'], "string");
	$safcmin = GetSafeValueString($_POST['safcmin'], "string");
	$saftmin = GetSafeValueString($_POST['saftmin'], "string");

	$user = new VOUser();
	$user->safcsms = 1;
	if (!isset($safcsms) || $safcsms == "") {
		$user->safcsms = 0;
	}
	$user->saftsms = 1;
	if (!isset($saftsms) || $saftsms == "") {
		$user->saftsms = 0;
	}
	$user->safcemail = 1;
	if (!isset($safcemail) || $safcemail == "") {
		$user->safcemail = 0;
	}
	$user->saftemail = 1;
	if (!isset($saftemail) || $saftemail == "") {
		$user->safcemail = 0;
	}
	if ($safcmin != 0 || $saftmin != 0) {
		// Insert or Update
		$user->safcmin = $safcmin;
		$user->saftmin = $saftmin;
		$user->userid = $_SESSION['userid'];
		$user->customerno = $_SESSION['customerno'];
		$usermanager->modify_stoppage_alerts($user, $_SESSION['userid']);
	} else {
		// Delete
		$usermanager->delete_stoppage_alerts($_SESSION['customerno'], $_SESSION['userid'], $user);
	}
} elseif (isset($_POST['alerts'])) {

	$user = new VOUser();
	$usermanager = new UserManager();

	if (isset($_POST['geosms'])) {
		$geosms = GetSafeValueString($_POST['geosms'], "string");
	}
	if (isset($_POST['geoemail'])) {
		$geoemail = GetSafeValueString($_POST['geoemail'], "string");
	}
	if (isset($_POST['geotelephone'])) {
		$geotelephone = GetSafeValueString($_POST['geotelephone'], "string");
	}
	if (isset($_POST['geomobile'])) {
		$geomobile = GetSafeValueString($_POST['geomobile'], "string");
	}
	if (isset($_POST['chksms'])) {
		$chksms = GetSafeValueString($_POST['chksms'], "string");
	}
	if (isset($_POST['chkemail'])) {
		$chkemail = GetSafeValueString($_POST['chkemail'], "string");
	}
	if (isset($_POST['chktelephone'])) {
		$chktelephone = GetSafeValueString($_POST['chktelephone'], "string");
	}
	if (isset($_POST['chkmobile'])) {
		$chkmobile = GetSafeValueString($_POST['chkmobile'], "string");
	}

	$user->mess_sms = 1;
	if (!isset($geosms)) {
		$user->mess_sms = 0;
	}
	$user->mess_email = 1;
	if (!isset($geoemail)) {
		$user->mess_email = 0;
	}
	$user->mess_telephone = 1;
	if (!isset($geotelephone)) {
		$user->mess_telephone = 0;
	}
	$user->mess_mobile = 1;
	if (!isset($geomobile)) {
		$user->mess_mobile = 0;
	}

	$user->chk_sms = 1;
	if (!isset($chksms)) {
		$user->chk_sms = 0;
	}
	$user->chk_email = 1;
	if (!isset($chkemail)) {
		$user->chk_email = 0;
	}
	$user->chk_telephone = 1;
	if (!isset($chktelephone)) {
		$user->chk_telephone = 0;
	}

	$user->chk_mobile = 1;
	if (!isset($chkmobile)) {
		$user->chk_mobile = 0;
	}

	$user->userid = isset($_POST['uid']) ? (int) $_POST['uid'] : $_SESSION['userid'];
	$user->customerno = $_SESSION['customerno'];
	$usermanager->modifyalerts($user, $_SESSION['userid']);
} elseif (isset($_POST['advanced_alerts'])) {

	$usermanager = new UserManager();
	$user_data = $usermanager->get_user($_SESSION['customerno'], $_SESSION["userid"]);

	if ($user_data->use_advanced_alert == 1) {
		$user = new VOUser();
		$user->harsh_break_sms = (isset($_POST['harsh_break_sms']) && $_POST['harsh_break_sms'] == 'on') ? 1 : 0;
		$user->harsh_break_mail = (isset($_POST['harsh_break_mail']) && $_POST['harsh_break_mail'] == 'on') ? 1 : 0;
		$user->high_acce_sms = (isset($_POST['high_acce_sms']) && $_POST['high_acce_sms'] == 'on') ? 1 : 0;
		$user->high_acce_mail = (isset($_POST['high_acce_mail']) && $_POST['high_acce_mail'] == 'on') ? 1 : 0;
		$user->sharp_turn_sms = (isset($_POST['sharp_turn_sms']) && $_POST['sharp_turn_sms'] == 'on') ? 1 : 0;
		$user->sharp_turn_mail = (isset($_POST['sharp_turn_mail']) && $_POST['sharp_turn_mail'] == 'on') ? 1 : 0;
		$user->towing_sms = (isset($_POST['towing_sms']) && $_POST['towing_sms'] == 'on') ? 1 : 0;
		$user->towing_mail = (isset($_POST['towing_mail']) && $_POST['towing_mail'] == 'on') ? 1 : 0;
		$user->userid = $_SESSION['userid'];
		$user->customerno = $_SESSION['customerno'];
		$usermanager->modify_advanced_alerts($user, $_SESSION['userid']);
		echo "Changes saved";
	} else {
		echo "No access";
	}
	exit;
} elseif (isset($_POST['time_alerts'])) {
	//print_r($_POST);
	if ($_POST['acisms']) {
		$acisms = GetSafeValueString($_POST['acisms'], "string");
	}
	if ($_POST['aciemail']) {
		$aciemail = GetSafeValueString($_POST['aciemail'], "string");
	}
	if ($_POST['aciselect']) {
		$aciselect = GetSafeValueString($_POST['aciselect'], "string");
	}

	$user = new VOUser();
	$usermanager = new UserManager();

	$user->aci_sms = 1;
	if (!isset($acisms)) {
		$user->aci_sms = 0;
	}

	$user->aci_email = 1;
	if (!isset($aciemail)) {
		$user->aci_email = 0;
	}

	$user->aci_time = $aciselect;
	if (!isset($aciselect)) {
		$user->aci_time = 0;
	}

	$user->userid = $_SESSION['userid'];
	$user->customerno = $_SESSION['customerno'];
	$usermanager->modifyTalerts($user, $_SESSION['userid']);
} elseif (isset($_POST['email_alerts'])) {
	if (isset($_POST['dailyemail'])) {
		$dailyemail = GetSafeValueString($_POST['dailyemail'], "string");
	} else {
		$dailyemail = 0;
	}
	if (isset($_POST['dailyemail_csv'])) {
		$dailyemail_csv = GetSafeValueString($_POST['dailyemail_csv'], "string");
	} else {
		$dailyemail_csv = 0;
	}

	$user = new VOUser();
	$usermanager = new UserManager();

	$user->userid = isset($_POST['uid']) ? (int) $_POST['uid'] : $_SESSION['userid'];
	$user->customerno = $_SESSION['customerno'];
	$user->dailyemail = $dailyemail;
	$user->dailyemail_csv = $dailyemail_csv;
	$usermanager->new_modifyCronalerts($user);
} elseif (isset($_POST['vehicle_alerts_status'])) {
	if (isset($_POST['vmastatus'])) {
		$vmastatus = GetSafeValueString($_POST['vmastatus'], "string");
	} else {
		$vmastatus = 0;
	}
	$user = new VOUser();
	$usermanager = new UserManager();

	$user->userid = isset($_POST['uid']) ? (int) $_POST['uid'] : $_SESSION['userid'];
	$user->customerno = $_SESSION['customerno'];
	$user->vmastatus = $vmastatus;
	$usermanager->new_modifyVehicleMovementalerts($user);
} elseif (isset($_POST['alert_timebased'])) {
	$user = new VOUser();
	$usermanager = new UserManager();

	$user->start_alert_time = GetSafeValueString($_POST['STime'], "string");
	$user->stop_alert_time = GetSafeValueString($_POST['ETime'], "string");
	$user->userid = isset($_POST['uid']) ? (int) $_POST['uid'] : $_SESSION['userid'];
	$user->customerno = $_SESSION['customerno'];
	$usermanager->modifyAlertsTimebased($user, $_SESSION['userid']);
} elseif (isset($_POST['custom']) && $_POST['custom'] == 'true') {
	$user = new VOUser();
	$user->usecustom1 = GetSafeValueString($_POST['usecustom_1'], "string");
	$user->customname1 = GetSafeValueString($_POST['customname_1'], "string");
	$user->usecustom2 = GetSafeValueString($_POST['usecustom_2'], "string");
	$user->customname2 = GetSafeValueString($_POST['customname_2'], "string");
	$user->usecustom3 = GetSafeValueString($_POST['usecustom_3'], "string");
	$user->customname3 = GetSafeValueString($_POST['customname_3'], "string");
	$user->usecustom4 = GetSafeValueString($_POST['usecustom_4'], "string");
	$user->customname4 = GetSafeValueString($_POST['customname_4'], "string");
	$user->usecustom5 = GetSafeValueString($_POST['usecustom_5'], "string");
	$user->customname5 = GetSafeValueString($_POST['customname_5'], "string");
	$user->usecustom6 = GetSafeValueString($_POST['usecustom_6'], "string");
	$user->customname6 = GetSafeValueString($_POST['customname_6'], "string");
	$user->usecustom7 = GetSafeValueString($_POST['usecustom_7'], "string");
	$user->customname7 = GetSafeValueString($_POST['customname_7'], "string");
	$user->usecustom8 = GetSafeValueString($_POST['usecustom_8'], "string");
	$user->customname8 = GetSafeValueString($_POST['customname_8'], "string");
	$user->usecustom9 = GetSafeValueString($_POST['usecustom_9'], "string");
	$user->customname9 = GetSafeValueString($_POST['customname_9'], "string");
	$user->usecustom10 = GetSafeValueString($_POST['usecustom_10'], "string");
	$user->customname10 = GetSafeValueString($_POST['customname_10'], "string");
	$user->usecustom11 = GetSafeValueString($_POST['usecustom_11'], "string");
	$user->customname11 = GetSafeValueString($_POST['customname_11'], "string");
	$user->usecustom12 = GetSafeValueString($_POST['usecustom_12'], "string");
	$user->customname12 = GetSafeValueString($_POST['customname_12'], "string");
	$user->usecustom13 = GetSafeValueString($_POST['usecustom_13'], "string");
	$user->customname13 = GetSafeValueString($_POST['customname_13'], "string");
	$user->usecustom14 = GetSafeValueString($_POST['usecustom_14'], "string"); //for license number
	$user->customname14 = GetSafeValueString($_POST['customname_14'], "string");
	$user->usecustom15 = GetSafeValueString($_POST['usecustom_15'], "string");
	$user->customname15 = GetSafeValueString($_POST['customname_15'], "string");
	$user->usecustom16 = GetSafeValueString($_POST['usecustom_16'], "string");
	$user->customname16 = GetSafeValueString($_POST['customname_16'], "string");
	$user->usecustom17 = GetSafeValueString($_POST['usecustom_17'], "string");
	$user->customname17 = GetSafeValueString($_POST['customname_17'], "string");
	$user->usecustom18 = GetSafeValueString($_POST['usecustom_18'], "string");
	$user->customname18 = GetSafeValueString($_POST['customname_18'], "string");
	$user->usecustom19 = GetSafeValueString($_POST['usecustom_19'], "string");
	$user->customname19 = GetSafeValueString($_POST['customname_19'], "string");
	$user->usecustom20 = GetSafeValueString($_POST['usecustom_20'], "string");
	$user->customname20 = GetSafeValueString($_POST['customname_20'], "string");
	$user->usecustom21 = GetSafeValueString($_POST['usecustom_21'], "string");
	$user->customname21 = GetSafeValueString($_POST['customname_21'], "string");
	$user->usecustom22 = GetSafeValueString($_POST['usecustom_22'], "string");
	$user->customname22 = GetSafeValueString($_POST['customname_22'], "string");
	$user->usecustom23 = GetSafeValueString($_POST['usecustom_23'], "string");
	$user->customname23 = GetSafeValueString($_POST['customname_23'], "string");
	$user->usecustom24 = GetSafeValueString($_POST['usecustom_24'], "string");
	$user->customname24 = GetSafeValueString($_POST['customname_24'], "string");
	$user->usecustom25 = GetSafeValueString($_POST['usecustom_25'], "string");
	$user->customname25 = GetSafeValueString($_POST['customname_25'], "string");
	$customerno = $_SESSION['customerno'];
	$usermanager = new UserManager();
	$usermanager->modifycustomfield($user, $customerno);
} elseif (isset($_POST['stoppagealerts']) && $_POST['stoppagealerts'] = "true") {
	$safcsms = GetSafeValueString($_POST['safcsms'], "string");
	$safcemail = GetSafeValueString($_POST['safcemail'], "string");
	$safctelephone = GetSafeValueString($_POST['safctelephone'], "string");
	$safcmobile = GetSafeValueString($_POST['safcmobile'], "string");
	$saftsms = GetSafeValueString($_POST['saftsms'], "string");
	$saftemail = GetSafeValueString($_POST['saftemail'], "string");
	$safttelephone = GetSafeValueString($_POST['safttelephone'], "string");
	$saftmobile = GetSafeValueString($_POST['saftmobile'], "string");
	$safcmin = GetSafeValueString($_POST['safcmin'], "string");
	$saftmin = GetSafeValueString($_POST['saftmin'], "string");

	$usermanager = new UserManager();
	$user = new VOUser();
	$user->safcsms = (isset($safcsms) && ($safcsms != '')) ? $safcsms : 0;
	$user->safcemail = (isset($safcemail) && ($safcemail != '')) ? $safcemail : 0;
	$user->safctelephone = (isset($safctelephone) && ($safctelephone != '')) ? $safctelephone : 0;
	$user->safcmobile = (isset($safcmobile) && ($safcmobile != '')) ? $safcmobile : 0;

	$user->saftsms = (isset($saftsms) && ($saftsms != '')) ? $saftsms : 0;
	$user->saftemail = (isset($saftemail) && ($saftemail != '')) ? $saftemail : 0;
	$user->safttelephone = (isset($safttelephone) && ($safttelephone != '')) ? $safttelephone : 0;
	$user->saftmobile = (isset($saftmobile) && ($saftmobile != '')) ? $saftmobile : 0;

	/* No point of minutes if both the checkboxes are unchecked */
	if ($safcsms == 0 && $safcemail == 0 && $safctelephone == 0 && $safcmobile == 0) {
		$safcmin = 0;
	}
	if ($saftsms == 0 && $saftemail == 0 && $safttelephone == 0 && $saftmobile == 0) {
		$saftmin = 0;
	}

	$user->safcmin = (isset($safcmin) && ($safcmin != '')) ? $safcmin : 0;
	$user->saftmin = (isset($saftmin) && ($saftmin != '')) ? $saftmin : 0;

	$user->userid = isset($_POST['uid']) ? (int) $_POST['uid'] : 0;
	$user->customerno = $_SESSION['customerno'];

	if ($safcmin != 0 || $saftmin != 0) {
		// Insert or Update
		$vehicles = $usermanager->pullvehicles($user->customerno);
		foreach ($vehicles as $thisvehicle) {
			$usermanager->modify_stoppage_alerts($user, $user->userid, $thisvehicle);
		}
	} else {
		// Delete
		$usermanager->delete_stoppage_alerts($user->customerno, $user->userid, $user);
	}
} elseif (isset($_POST['fuelalert']) && $_POST['fuelalert'] = "true") {
	$usermanager = new UserManager();
	$fuelsms = GetSafeValueString($_POST['fuelsms'], "string");
	$fuelemail = GetSafeValueString($_POST['fuelemail'], "string");
	$fuelrange = GetSafeValueString($_POST['fuelrange'], "string");
	$fueltelephone = GetSafeValueString($_POST['fueltelephone'], "string");
	$fuelmobile = GetSafeValueString($_POST['fuelmobile'], "string");

	$user = new VOUser();
	$user->fuel_alert_sms = 1;
	if ($fuelsms == '0') {
		$user->fuel_alert_sms = 0;
	}
	$user->fuel_alert_email = 1;
	if ($fuelemail == '0') {
		$user->fuel_alert_email = 0;
	}
	$user->fuel_alert_percentage = $fuelrange;
	if ($fuelrange == '0') {
		$user->fuel_alert_percentage = 0;
	}
	$user->fuel_alert_telephone = $fueltelephone;
	if ($fueltelephone == '0') {
		$user->fuel_alert_telephone = 0;
	}
	$user->fuel_alert_mobile = $fuelmobile;
	if ($fuelmobile == '0') {
		$user->fuel_alert_mobile = 0;
	}

	$userid = isset($_POST['uid']) ? (int) $_POST['uid'] : $_SESSION['userid'];
	$usermanager->modify_fuel_alerts($user, $userid);
} elseif (isset($_GET['username']) && isset($_GET['password']) && isset($_GET['dealer']) && isset($_GET['checksum'])) {
	$dealerDomain = "";
	switch ($_GET['dealer']) {
	case "navyogtracking":
		$dealerDomain = "navyogtracking";
		break;
	}
	if (!empty($dealerDomain)) {
		$strReqParams = "elixiaspeed" . $dealerDomain . GetSafeValueString($_GET['username'], "string") . GetSafeValueString($_GET['password'], "string");
		$checksum = crc32($strReqParams);
		if ($checksum == $_GET['checksum']) {
			$isDealer = 1;
			$arrResult = checklogin(GetSafeValueString($_GET['username'], "string"), GetSafeValueString($_GET['password'], "string"), $isDealer);
			echo $_GET['callback'] . '(' . json_encode($arrResult) . ')';
		}
	}
} elseif (isset($_POST['del_adv_temp'])) {
	$vehicleid = GetSafeValueString($_POST['del_adv_temp'], "string");
	$result = delete_adv_temp_range($vehicleid);
	echo $result;
} elseif (isset($_POST['method']) && $_POST['method'] == 'add_adv_temp') {
	$result = add_adv_temp($_POST);
	echo $result;
} elseif (isset($_POST['edit_advance_temp'])) {
	$vehicleid = GetSafeValueString($_POST['edit_advance_temp'], "string");
	$userid = GetSafeValueString($_POST['edit_advance_temp_userid'], "string");
	$result = get_edit_advance_temp($vehicleid, $userid);
	echo $result;
} elseif (isset($_POST['method']) && $_POST['method'] == 'check_temp_range') {
	$result = check_temp_range($_POST);
	echo $result;
}
?>
