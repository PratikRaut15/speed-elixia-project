<?php
//error_reporting(0);
//error_reporting(E_ALL ^ E_STRICT);
//ini_set('display_errors', 'On');
//include_once("session.php");

include_once("../../lib/system/utilities.php");
include("loginorelse.php");
include_once("../../constants/constants.php");
include_once("db.php");
include_once("../../lib/system/DatabaseManager.php");
include_once("../../lib/system/Date.php");
include_once("../../lib/system/Sanitise.php");
include_once("../../lib/components/gui/objectdatagrid.php");
include_once("../../lib/model/VODevices.php");
include_once("../user/new_alerts_func.php");

$_scripts[] = "../../scripts/jquery.min.js";
$_scripts[] = "../../scripts/team/modifycustomer.js";
$_scripts_custom[] = "../../scripts/autocomplete/jquery-ui.min.js";
$_stylesheets[] = "../../scripts/autocomplete/jquery-ui.min.css";

class customers {
    
}

function PrepareSP($sp_name, $sp_params) {
    return "call " . $sp_name . "(" . $sp_params . ");";
}

$customerid = GetSafeValueString(isset($_GET["cid"]) ? $_GET["cid"] : $_POST["customerid"], "long");
$teamid = GetLoggedInUserId();
$db = new DatabaseManager();
date_default_timezone_set("Asia/Calcutta");
$today = date("Y-m-d H:i:s");

$SQL = sprintf("SELECT team.teamid, team.name FROM " . DB_PARENT . ".team");
$db->executeQuery($SQL);
$team_allot_array = Array();
if ($db->get_rowCount() > 0) {
    while ($row = $db->get_nextRow()) {
        $team = new testing();
        $team->teamid = $row["teamid"];
        $team->name = $row["name"];
        $team_allot_array[] = $team;
    }
}

if (isset($_POST["add_notes"])) {
    $customer_notes = GetSafeValueString($_POST['customer_notes'], "string");
    $note_mail = GetSafeValueString($_POST['tags1'], "string");
    $note_mail = explode(',', $note_mail);
    $arrTo = array_unique(array_filter($note_mail));
    ////////////////Commercial Details insert -start ///////////////////////
    $today = date("Y-m-d H:i:s");
    $sql = sprintf("INSERT INTO " . DB_PARENT . ".`customer_notes` (
                `details`,
                `customerno` ,
                `entrytime`,
                `added_by`
                )VALUES ('%s','%d','%s','%s');", $customer_notes, $customerid, $today, $teamid);
    $db->executeQuery($sql);

    $strCCMailIds = "";
    $strBCCMailIds = "";
    $subject = "Note";
    $attachmentFilePath = "";
    $attachmentFileName = "";
    $isTemplatedMessage = 1;
    $isSmsSent = sendMailUtil($arrTo, $strCCMailIds, $strBCCMailIds, $subject, $customer_notes, $attachmentFilePath, $attachmentFileName, $isTemplatedMessage);

    ////////////////Commercial Details insert-end ///////////////////////
    header("Location: modifycustomer.php?cid=$customerid");
}


// ------------------------------------------------------------------ Customer Save ------------------------------------------------------------------------

if (isset($_POST["save"])) {

// Attempting to save changes now.
    $custsms = GetSafeValueString($_POST["customersms"], "string");
    $custtelephonicalert = GetSafeValueString($_POST["customertelephonicalert"], "string");
    $custname = GetSafeValueString($_POST["customername"], "string");
    $custcompany = GetSafeValueString($_POST["customercompany"], "string");
    $cunitprice = GetSafeValueString($_POST["cunitprice"], "string");
    $cmsp = GetSafeValueString($_POST["cmsp"], "string");
    $custrenewal = GetSafeValueString($_POST['duration'], "string");
    $timezone = GetSafeValueString($_POST['timezone'], "string");
    $comdetails = GetSafeValueString($_POST['comdetails'], "string");
    $leaseduration = isset($_POST['leaseduration']) ? GetSafeValueString($_POST['leaseduration'], "int") : 0;
    $leaseprice = isset($_POST['leaseprice']) ? GetSafeValueString($_POST['leaseprice'], "int") : 0;
    
    $industrytype = isset($_POST['industrytype'])?$_POST['industrytype']:0;
    $salesid = isset($_POST['sales'])?$_POST['sales']:0;
    
    
    if ($leaseduration == '-1') {
        $leaseduration = GetSafeValueString($_POST['customlease'], "int");
    }
// Use AC

    $cac = 1;
    if (!isset($_POST["cac"])) {
        $cac = 0;
    }

// Use Genset
    $cgenset = 1;
    if (!isset($_POST["cgenset"])) {
        $cgenset = 0;
    }

// Use Fuel
    $cfuel = 1;
    if (!isset($_POST["cfuel"])) {
        $cfuel = 0;
    }

// Use Door
    $cdoor = 1;
    if (!isset($_POST["cdoor"])) {
        $cdoor = 0;
    }

// Use Panic
    $cpanic = 1;
    if (!isset($_POST["cpanic"])) {
        $cpanic = 0;
    }

// Use Buzzer
    $cbuzzer = 1;
    if (!isset($_POST["cbuzzer"])) {
        $cbuzzer = 0;
    }

// Use Immobilizer
    $cimmo = 1;
    if (!isset($_POST["cimmobilizer"])) {
        $cimmo = 0;
    }

// Use Mobility
    $mob = 1;
    if (!isset($_POST["cimobility"])) {
        $mob = 0;
    }

// Use Sales Engage
    $csalesengage = 1;
    if (!isset($_POST["csalesengage"])) {
        $csalesengage = 0;
    }

// Use Delivery
    $cdelivery = 1;
    if (!isset($_POST["cdelivery"])) {
        $cdelivery = 0;
    }

// Use Routing
    $crouting = 1;
    if (!isset($_POST["crouting"])) {
        $crouting = 0;
    } else if ($cdelivery == 0) {
        $crouting = 0;
    }


    // Use Loading
    $cloading = 1;
    if (!isset($_POST["cloading"])) {
        $cloading = 0;
    }

    // Use GeoLocation
    $cgeolocation = 1;
    if (!isset($_POST["cgeolocation"])) {
        $cgeolocation = 0;
    }

    // Use Maintenance Module
    $cmaintenance = 1;
    if (!isset($_POST["cmaintenance"])) {
        $cmaintenance = 0;
    }

    // Use Traking
    $ctraking = 0;
    if (isset($_POST["ctracking"])) {
        $ctraking = 1;
    }

    // Use Portable Module
    $cportable = 1;
    if (!isset($_POST["cportable"])) {
        $cportable = 0;
    }

    // Use Heirarchy
    $cheirarchy = 1;
    if (!isset($_POST["cheirarchy"])) {
        $cheirarchy = 0;
    } else if ($cmaintenance == 0) {
        $cheirarchy = 0;
    }

    // Temperature Sensors
    $ctempsensor = GetSafeValueString($_POST["ctempsensor"], "long");
    $advanced_alert = (int) $_POST['advancedAlerts'];

    if (isset($custsms) && $custsms > 0) {
        $SMS_SQL = sprintf("UPDATE " . DB_PARENT . ".customer SET
            `totalsms`=`totalsms`+'%d',
            `smsleft`=`smsleft`+'%d',
            `sms_balance_alert`='0'
            WHERE `customerno`=%d", Sanitise::Long($custsms), Sanitise::Long($custsms), $customerid);
        $db->executeQuery($SMS_SQL);
    }

    $SQL = sprintf("UPDATE " . DB_PARENT . ".customer SET
            `customername`='%s',
            `industryid`='%d',
            `salesid`='%d',
            `customercompany`='%s',
            `total_tel_alert`=`total_tel_alert`+'%d',
            `tel_alertleft`=`tel_alertleft`+'%d',
            `use_msgkey`='%d',
            `use_geolocation`='%d',
            `use_tracking`='%d',
            `use_maintenance`='%d',
            `use_portable`='%d',
            `temp_sensors`='%d',
            `use_hierarchy`='%d',
            `use_advanced_alert`= '%d',
            `use_ac_sensor`= '%d',
            `use_genset_sensor`= '%d',
            `use_fuel_sensor`= '%d',
            `use_panic`= '%d',
            `use_buzzer`= '%d',
            `use_immobiliser`= '%d',
            `use_mobility`= '%d',
            `use_sales` = '%d',
            `use_door_sensor`= '%d',
            `use_delivery`= '%d',
            `use_routing`= '%d',
            `unitprice`= '%s',
            `unit_msp`= '%s',
            `renewal`= '%d',
            `timezone`='%d',
            `lease_duration`=%d,
            `lease_price`=%d,
            `commercial_details`='%s'
            WHERE customerno = %d ", $custname,$industrytype,$salesid,$custcompany, Sanitise::Long($custtelephonicalert), Sanitise::Long($custtelephonicalert), Sanitise::Long($cloading), Sanitise::Long($cgeolocation), Sanitise::Long($ctraking), Sanitise::Long($cmaintenance), Sanitise::Long($cportable), Sanitise::Long($ctempsensor), Sanitise::Long($cheirarchy), $advanced_alert, $cac, $cgenset, $cfuel, $cpanic, $cbuzzer, $cimmo, $mob, $csalesengage, $cdoor, $cdelivery, $crouting, $cunitprice, $cmsp, $custrenewal, $timezone, $leaseduration, $leaseprice, $comdetails, $customerid);
    $db->executeQuery($SQL);

    $SQL = sprintf('SELECT totalsms FROM ' . DB_PARENT . '.customer WHERE customerno = ' . $customerid);
    $db->executeQuery($SQL);
    if ($db->get_rowCount() > 0) {
        while ($row = $db->get_nextRow()) {
            $totalsms = $row["totalsms"];
        }
    }

    if ($cmaintenance == 1 && $cheirarchy == 1) {
        $SQL = sprintf("select userid, username, role from user where customerno=" . $customerid . " and role = 'Administrator'");
        $db->executeQuery($SQL);
        if ($db->get_rowCount() > 0) {
            $userid_admin = array();
            while ($row = $db->get_nextRow()) {
                $userid_admin[] = $row['userid'];
            }
            $users = implode(',', $userid_admin);
            $SQLT = sprintf("update user SET role ='Master', roleid=1 where userid in (" . $users . ") and customerno=" . $customerid . " ");
            $db->executeQuery($SQLT);
        }
    } else {
        $SQL = sprintf("select userid, username, role from user where customerno=" . $customerid . " and role = 'Master'");
        $db->executeQuery($SQL);
        if ($db->get_rowCount() > 0) {
            $userid_admin = array();
            while ($row = $db->get_nextRow()) {
                $userid_admin[] = $row['userid'];
            }
            $users = implode(',', $userid_admin);
            $SQLT = sprintf("update user SET role ='Administrator', roleid=5 where userid in (" . $users . ") and customerno=" . $customerid . " ");
            $db->executeQuery($SQLT);
        }
    }


    $SQLunit = sprintf("INSERT INTO " . DB_PARENT . ".trans_history (
    `customerno` ,`unitid`,`teamid`, `type`, `trans_time`, `statusid`, `transaction`, `simcardno`, `invoiceno`, `expirydate`)
    VALUES (
    $customerid, 0, '%s', 2, '%s', 0, '%s', '%s', '%s', '%s')", GetLoggedInUserId(), Sanitise::DateTime($today), "SMS Added : " . $custsms . ". Total SMS : " . $totalsms, "", "", "");
    $db->executeQuery($SQLunit);

    //header("Location: customers.php");
    header("Location: modifycustomer.php?cid=$customerid");
}

$timezones = Array();
$SQL = sprintf("SELECT * from " . DB_PARENT . ".timezone");
$db->executeQuery($SQL);
if ($db->get_rowCount() > 0) {
    while ($row = $db->get_nextRow()) {
        $time = new customers();
        $time->tid = $row['tid'];
        $time->zone = $row['timezone'];
        $timezones[] = $time;
    }
}

$industrytypes = Array();
$SQL = sprintf("SELECT 	industryid,industry_type from sales_industry_type where isdeleted=0");
$db->executeQuery($SQL);
if ($db->get_rowCount() > 0) {
    while ($row = $db->get_nextRow()) {
        $type = new stdClass();
        $type->industryid = $row['industryid'];
        $type->industry_type = $row['industry_type'];
        $industrytypes[] = $type;
    }
}


$saleslist = Array();
$SQL = sprintf("SELECT 	teamid,name from team where role='Sales'");
$db->executeQuery($SQL);
if ($db->get_rowCount() > 0) {
    while ($row = $db->get_nextRow()) {
        $sales = new stdClass();
        $sales->teamid = $row['teamid'];
        $sales->name = $row['name'];
        $saleslist[] = $sales;
    }
}



$SQL = sprintf("SELECT c.* from " . DB_PARENT . ".customer c where c.customerno = '%d' LIMIT 1 ", $customerid);
$db->executeQuery($SQL);

while ($row = $db->get_nextRow()) {

    $custname = $row["customername"];
    $custcompany = $row["customercompany"];
    $custphone = $row["customerphone"];
    $custcell = $row["customercell"];
    $custemail = $row["customeremail"];
    $custsms = $row["totalsms"];
    $custtelephonicalert = $row["total_tel_alert"];
    $cloading = $row["use_msgkey"];
    $cgeolocation = $row["use_geolocation"];
    $cmaintenance = $row["use_maintenance"];
    $cportable = $row["use_portable"];
    $ctempsensor = $row["temp_sensors"];
    $cheirarchy = $row["use_hierarchy"];
    $ctraking = $row['use_tracking'];
    $advanced_alert = $row["use_advanced_alert"];
    $ac_sensor = $row["use_ac_sensor"];
    $genset_sensor = $row["use_genset_sensor"];
    $fuel_sensor = $row["use_fuel_sensor"];
    $door_sensor = $row["use_door_sensor"];
    $routing = $row["use_routing"];
    $panic = $row["use_panic"];
    $buzzer = $row["use_buzzer"];
    $immobilizer = $row["use_immobiliser"];
    $mobility = $row["use_mobility"];
    $salesengage = $row["use_sales"];
    $delivery = $row["use_delivery"];
    $unitprice = $row["unitprice"];
    $unit_msp = $row["unit_msp"];
    $cust_renewal = $row["renewal"];
    $t_zone = $row['timezone'];
    $comdetails = $row['commercial_details'];
    $lease_period = $row['lease_duration'];
    $lease_price = $row['lease_price'];
    $salesid = $row['salesid'];
    $industryid = $row['industryid'];
}

function SendEmail($mail) {
    // send email
    $arrTo = array($mail->email);
    $strCCMailIds = "";
    $strBCCMailIds = "";
    $subject = "Unit Installation Details";
    $message = file_get_contents('../../modules/emailtemplates/registerDevice.html');
    $message = str_replace("{{REALNAME}}", $mail->realname, $message);
    $message = str_replace("{{VEHICLENO}}", $mail->vehicleno, $message);
    $message = str_replace("{{UNITNO}}", $mail->unitnumber, $message);
    $message = str_replace("{{SIMCARD}}", $mail->simcardno, $message);
    $message = str_replace("{{INSTALLDATE}}", $mail->installdate, $message);
    $message = str_replace("{{EXPIRYDATE}}", $mail->expirydate, $message);
    $message = str_replace("{{ELIXIR}}", $mail->elixir, $message);
    $message = str_replace("{{COMMENTS}}", $mail->comment, $message);
    $attachmentFilePath = "";
    $attachmentFileName = "";
    $isTemplatedMessage = 1;
    $isSmsSent = sendMailUtil($arrTo, $strCCMailIds, $strBCCMailIds, $subject, $message, $attachmentFilePath, $attachmentFileName, $isTemplatedMessage);
    return $isSmsSent;
}

function SendEmailNewUser($mail, $username, $password) {
    // send email
    $arrTo = array($mail);
    $strCCMailIds = "";
    $strBCCMailIds = "";
    $subject = "New Registration Details";
    $message = file_get_contents('../../modules/emailtemplates/NewUserRegister.html');
    $message = str_replace("{{USERNAME}}", $username, $message);
    $message = str_replace("{{PASSWORD}}", $password, $message);
    $attachmentFilePath = "";
    $attachmentFileName = "";
    $isTemplatedMessage = 1;
    $isSmsSent = sendMailUtil($arrTo, $strCCMailIds, $strBCCMailIds, $subject, $message, $attachmentFilePath, $attachmentFileName, $isTemplatedMessage);
    return $isSmsSent;
}

function getelixirid($customerno) {
    $db = new DatabaseManager();
    $SQL = sprintf("SELECT userid FROM " . DB_PARENT . ".user where role='elixir' AND customerno=" . $customerno);
    $db->executeQuery($SQL);
    if ($db->get_rowCount() > 0) {
        while ($row = $db->get_nextRow()) {
            $userid = $row["userid"];
        }
    }
    return $userid;
}

function sendMail($to, $subject, $content) {
    $headers = "From: noreply@elixiatech.com\r\n";
    $headers .= "MIME-Version: 1.0\r\n";
    $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
    include_once("../../lib/system/class.phpmailer.php");
    $mail = new PHPMailer();
    $mail->IsMail();
    $mail->AddAddress($to);
    $mail->From = "noreply@elixiatech.com\r\n";
    $mail->FromName = "Elixia Speed";
    $mail->Sender = "noreply@elixiatech.com\r\n";
    $mail->Subject = $subject;
    $mail->Body = $content;
    $mail->IsHTML(true);
    $mail->AddReplyTo("From: noreply@elixiatech.com\r\n", "Elixia Speed");
    if (!$mail->Send()) {
        //echo "Error sending: " . $mail->ErrorInfo;
        $content = '';
    } else {
        //echo "Mail sent";
        $content = '';
    }
}

include("header.php");
?>
<?php
if (IsHead() || IsAdmin()) {
    ?>
    <!-- ------------------------------------------------------------------ Customer Save ------------------------------------------------------------------------ -->

    <div class="panel">
        <div class="paneltitle" align="center">Update Customer - Credentials</div>
        <div class="panelcontents">

            <p>Please adjust any inaccurate details. Understand that changing these values will have a very real and immediate impact on the customer.</p>
            <form method="post" action="modifycustomer.php" name="updatecustomerform" id="updatecustomerform" onsubmit="return validform_update_cust();
                        return false;"  enctype="multipart/form-data">
                <input type="hidden" name="customerid" value="<?php echo( $customerid ); ?>"/>
                <table width="100%">
                    <tr>
                        <td>Real Name <span style="color:red;">*</span></td><td><input name="customername" id='customername' type="text" value="<?php echo($custname); ?>" /> </td>
                        <td>Company <span style="color:red;">*</span></td><td><input name="customercompany" id='customercompany' type="text"  value="<?php echo($custcompany); ?>" /></td>
                </table>

                <br/>
                <div class="paneltitlemid" align="center">Modules</div><br/>
                <table width="60%" align="center">
                    <tr>
                        <td>SMS Package: <b><?php echo($custsms); ?></b> + </td><td><input name="customersms" type="text" /></td>
                    </tr>
                    <tr>
                        <td>Telephonic Alerts Package: <b><?php echo($custtelephonicalert); ?></b> + </td><td><input name="customertelephonicalert" type="text" /></td>
                    </tr>
                    <tr>
                        <td>Tracking Module</td>
                        <td><input name="ctracking" id="ctracking" type="checkbox" <?php if ($ctraking) { ?> checked <?php } ?> onclick="show_features();"/></td>
                    </tr>
                    <tr id="load_sensor" <?php if (!$ctraking) { ?> style="display:none" <?php } ?>>
                        <td>Load Sensor</td>
                        <td><input name="cloading" id="cloading" type="checkbox" <?php if ($cloading) { ?> checked <?php } ?>/><br/>(Note: Point all the devices using this feature to 9990)</td>
                    </tr>
                    <tr id="reverse_geo" <?php if (!$ctraking) { ?> style="display:none" <?php } ?>>
                        <td>Reverse Geo-Location</td>
                        <td><input name="cgeolocation" id="cgeolocation" type="checkbox" <?php if ($cgeolocation) { ?> checked <?php } ?>/><br/>(Note: Location will be pulled from Google Maps API)</td>
                    </tr>
                    <tr id="ac_tr"  <?php if (!$ctraking) { ?> style="display:none" <?php } ?>>
                        <td></td>
                        <td><input name="cac" id="cac" type="checkbox" <?php if ($ac_sensor) { ?> checked <?php } ?>/>Use AC Sensor</td>
                    </tr>
                    <tr id="genset_tr"  <?php if (!$ctraking) { ?> style="display:none" <?php } ?>>
                        <td></td>
                        <td><input name="cgenset" id="cgenset" type="checkbox" <?php if ($genset_sensor == '1') { ?> checked <?php } ?>/>Use Genset Sensor</td>
                    </tr>
                    <tr id="fuel_tr"  <?php if (!$ctraking) { ?> style="display:none" <?php } ?>>
                        <td></td>
                        <td><input name="cfuel" id="cfuel" type="checkbox" <?php if ($fuel_sensor == '1') { ?> checked <?php } ?>/>Use Fuel Sensor</td>
                    </tr>
                    <tr id="door_tr"  <?php if (!$ctraking) { ?> style="display:none" <?php } ?>>
                        <td></td>
                        <td><input name="cdoor" id="cdoor" type="checkbox" <?php if ($door_sensor == 1) { ?> checked <?php } ?>/>Use Door Sensor</td>
                    </tr>

                    <tr id="portable_tr" <?php if (!$ctraking) { ?> style="display:none" <?php } ?>>
                        <td>Portable Module</td>
                        <td><input name="cportable" id="cportable" type="checkbox" <?php if ($cportable == 1) { ?> checked <?php } ?>/></td>
                    </tr>
                    <tr id="temp_tr" <?php if (!$ctraking) { ?> style="display:none" <?php } ?>>
                        <td>Temperature Sensors</td>
                        <td><input type="radio" name="ctempsensor" value="0" <?php if ($ctempsensor == 0) { ?> checked <?php } ?> >0 <input type="radio" name="ctempsensor" value="1" <?php if ($ctempsensor == 1) { ?> checked <?php } ?>>1 <input type="radio" name="ctempsensor" value="2" <?php if ($ctempsensor == 2) { ?> checked <?php } ?>>2
                            <input type="radio" name="ctempsensor" value="3" <?php if ($ctempsensor == 3) { ?> checked <?php } ?>>3 <input type="radio" name="ctempsensor" value="4" <?php if ($ctempsensor == 4) { ?> checked <?php } ?>>4</td>
                    </tr>
                    <tr id="advanced_tr" <?php if (!$ctraking) { ?> style="display:none" <?php } ?>>
                        <td>Advanced Alerts</td>
                        <td><input type="radio" name="advancedAlerts" value="0" <?php
                            if ($advanced_alert == 0) {
                                echo "checked";
                            }
                            ?> >No <input type="radio" name="advancedAlerts" value="1" <?php
                                   if ($advanced_alert == 1) {
                                       echo "checked";
                                   }
                                   ?>>Yes</td>
                    </tr>

                    <tr id="panic_tr" <?php if (!$ctraking) { ?> style="display:none" <?php } ?>>
                        <td></td>
                        <td><input name="cpanic" id="cpanic" type="checkbox" <?php
                            if ($panic == 1) {
                                echo "checked";
                            }
                            ?>/>Use Panic</td>
                    </tr>
                    <tr id="buzzer_tr" <?php if (!$ctraking) { ?> style="display:none" <?php } ?>>
                        <td></td>
                        <td><input name="cbuzzer" id="cbuzzer" type="checkbox" <?php
                            if ($buzzer == 1) {
                                echo "checked";
                            }
                            ?>/>Use Buzzer</td>
                    </tr>
                    <tr id="immobilizer_tr" <?php if (!$ctraking) { ?> style="display:none" <?php } ?>>
                        <td></td>
                        <td><input name="cimmobilizer" id="cimmobilizer" type="checkbox" <?php
                            if ($immobilizer == 1) {
                                echo "checked";
                            }
                            ?>/>Use Immobilizer</td>
                    </tr>

                    <tr id="mobility_tr" <?php if (!$ctraking) { ?> style="display:none" <?php } ?>>
                        <td></td>
                        <td><input name="cimobility" id="cimobility" type="checkbox" <?php
                            if ($mobility == 1) {
                                echo "checked";
                            }
                            ?>/>Use Mobility</td>
                    </tr>

                    <tr id="salesengage_tr" <?php if (!$ctraking) { ?> style="display:none" <?php } ?>>
                        <td></td>
                        <td><input name="csalesengage" id="csalesengage" type="checkbox" <?php
                            if ($salesengage == 1) {
                                echo "checked";
                            }
                            ?>/>Use Sales Engage</td>
                    </tr>

                    <tr>
                        <td>Maintenance Module</td>
                        <td><input name="cmaintenance" id="cmaintenance" type="checkbox" <?php if ($cmaintenance == 1) { ?> checked <?php } ?>  onclick="show_heirarchy();"/></td>
                    </tr>
                    <tr id="heir_tr" <?php if (!$cmaintenance) { ?> style="display:none" <?php } ?>>
                        <td></td>
                        <td><input name="cheirarchy" id="cheirarchy" type="checkbox" <?php if ($cheirarchy == 1) { ?> checked <?php } ?>/>Use Hierarchy</td>
                    </tr>

                    <tr>
                        <td>Delivery Module</td>
                        <td><input name="cdelivery" id="cdelivery" type="checkbox" <?php if ($delivery == 1) { ?> checked <?php } ?> onclick="show_routing();"/></td>
                    </tr>
                    <tr id="routing_tr" <?php if (!$delivery) { ?> style="display:none" <?php } ?>>
                        <td></td>
                        <td><input name="crouting" id="crouting" type="checkbox" <?php if ($routing == 1) { ?> checked <?php } ?>/>Use Routing</td>
                    </tr>
                    <tr>
                        <td>Unit Price</td>
                        <td><input type="text" value="<?php echo $unitprice; ?>" name="cunitprice" id="cunitprice"/></td>
                    </tr>
                    <tr>
                        <td>Unit Monthly Subscription Price</td>
                        <td><input type="text" value="<?php echo $unit_msp; ?>" name="cmsp" id="cmsp"/></td>
                    </tr>
                    <tr>
                        <td>Subscription Renewal Period</td>
                        <td><input type="radio" name="duration" id="duration" value="1" <?php if ($cust_renewal == 1) { ?> checked <?php } ?>/>1 Month
                            <input type="radio" name="duration" id="duration" value="3" <?php if ($cust_renewal == 3) { ?> checked <?php } ?>/>3 Months
                            <input type="radio" name="duration" id="duration" value="6" <?php if ($cust_renewal == 6) { ?> checked <?php } ?>/>6 Months
                            <input type="radio" name="duration" id="duration" value="12"<?php if ($cust_renewal == 12) { ?> checked <?php } ?>/>1 Year
                            <input type="radio" name="duration" id="duration" value="-3"<?php if ($cust_renewal == -3) { ?> checked <?php } ?>/>Lease
                            <input type="radio" name="duration" id="duration" value="-1"<?php if ($cust_renewal == -1) { ?> checked <?php } ?>/>Demo
                            <input type="radio" name="duration" id="duration" value="-2"<?php if ($cust_renewal == -2) { ?> checked <?php } ?>/>Closed</td>
                    </tr>
                    <tr class="tr_lease" style="display: none;">
                        <td>Lease Period</td>
                        <td>
                            <input type="radio" name="leaseduration" id="leaseduration" value="3" <?php if ($lease_period == 3) { ?> checked <?php } ?>/>3 Months
                            <input type="radio" name="leaseduration" id="leaseduration" value="6" <?php if ($lease_period == 6) { ?> checked <?php } ?>/>6 Months
                            <input type="radio" name="leaseduration" id="leaseduration" value="12"<?php if ($lease_period == 12) { ?> checked <?php } ?>/>1 Year
                            <input type="radio" name="leaseduration" id="leaseduration" value="-1"<?php if ($lease_period != 12 && $lease_period != 6 && $lease_period != 3 && $lease_period != 0) { ?> checked <?php } ?> onclick="showLeaseCustom();"/>Custom
                            <input type="number" name="customlease" id="customlease" size="3" style="display: none;width:30px;" value="<?php
                            if ($lease_period == 12 || $lease_period == 6 || $lease_period == 3) {
                                echo "0";
                            } else {
                                echo $lease_period;
                            }
                            ?>"/>
                        </td>
                    </tr>
                    <tr class="tr_lease" style="display: none;">
                        <td>Lease Price</td>
                        <td><input type="text" name="leaseprice" id="leaseprice" value="<?php echo $lease_price; ?>"/></td>
                    </tr>
                    <tr><td>Commercial Details</td>
                        <td><textarea name="comdetails" style='width:300px;' id ='comdetails'><?php echo $comdetails; ?></textarea></td>
                    </tr>
                    <tr>
                        <td>Timezone </td>
                        <td>
                            <select name="timezone" id="timezone">
                                <option value="0">Select Timezone</option>
                                <?php
                                if (isset($timezones)) {
                                    foreach ($timezones as $time) {
                                        ?>
                                        <option value="<?php echo $time->tid; ?>" <?php
                                        if ($time->tid == $t_zone) {
                                            echo 'selected';
                                        }
                                        ?> ><?php echo $time->zone; ?></option>
                                                <?php
                                            }
                                        }
                                        ?>
                            </select>
                        </td>
                    </tr>
                    
                    <tr>
                        <td>Industry type </td>
                        <td>
                            <select name="industrytype" id="industrytype">
                                <option value="0">Select Industry</option>
                                <?php
                                if (isset($industrytypes)) {
                                    foreach ($industrytypes as $ind) {
                                        ?>
                                        <option value="<?php echo $ind->industryid; ?>" <?php
                                        if ($ind->industryid == $industryid){
                                            echo 'selected';
                                        }
                                        ?> ><?php echo $ind->industry_type; ?></option>
                                                <?php
                                            }
                                        }
                                        ?>
                            </select>
                        </td>
                    </tr>
                    
                    <tr>
                        <td>Sales Manager </td>
                        <td>
                            <select name="sales" id="sales">
                                <option value="0">Select Sales Person</option>
                                <?php
                                if (isset($saleslist)) {
                                    foreach ($saleslist as $sales) {
                                        ?>
                                        <option value="<?php echo $sales->teamid; ?>" <?php
                                        if ($sales->teamid == $salesid){
                                            echo 'selected';
                                        }
                                        ?> ><?php echo $sales->name; ?></option>
                                                <?php
                                            }
                                        }
                                        ?>
                            </select>
                        </td>
                    </tr>
                    
                    
                </table>
                <hr/>
                <div align="center"><input type="submit" name="save" value="Save Changes" /></div>
            </form>
        </div>

        <?php
    }

    class testing {
        
    }

    $db = new DatabaseManager();
    $SQL = sprintf("SELECT * FROM unit INNER JOIN " . DB_PARENT . ".trans_status ON trans_status.id = unit.trans_statusid WHERE trans_statusid IN (2)");
    $db->executeQuery($SQL);
    $units = Array();
    if ($db->get_rowCount() > 0) {
        while ($row = $db->get_nextRow()) {
            $unit = new testing();
            $unit->unitno = $row["unitno"] . "[ " . $row["status"] . " ]";
            $unit->uid = $row["uid"];
            $units[] = $unit;
        }
    }

    $db = new DatabaseManager();
    $SQL = sprintf("SELECT * FROM unit INNER JOIN " . DB_PARENT . ".trans_status ON trans_status.id = unit.trans_statusid WHERE trans_statusid IN (6) AND customerno = " . $customerid);
    $db->executeQuery($SQL);
    $resolveunits = Array();
    if ($db->get_rowCount() > 0) {
        while ($row = $db->get_nextRow()) {
            $unit = new testing();
            $unit->unitno = $row["unitno"] . "[ " . $row["status"] . " ]";
            $unit->uid = $row["uid"];
            $resolveunits[] = $unit;
        }
    }

    $db = new DatabaseManager();
    $SQL = sprintf("SELECT simcard.id as simid, simcard.simcardno, trans_status.status FROM simcard INNER JOIN " . DB_PARENT . ".trans_status ON trans_status.id = simcard.trans_statusid WHERE trans_statusid IN (11)");
    $db->executeQuery($SQL);
    $simcards = Array();
    if ($db->get_rowCount() > 0) {
        while ($row = $db->get_nextRow()) {
            $simcard = new testing();
            $simcard->simcardno = $row["simcardno"] . "[ " . $row["status"] . " ]";
            $simcard->id = $row["simid"];
            $simcards[] = $simcard;
        }
    }

    $db = new DatabaseManager();
    $SQL = sprintf("SELECT simcard.id as simid, simcard.simcardno, trans_status.status FROM simcard INNER JOIN " . DB_PARENT . ".trans_status ON trans_status.id = simcard.trans_statusid WHERE trans_statusid IN (14) AND simcard.customerno=%d", $customerid);
    $db->executeQuery($SQL);
    $sussimcards = Array();
    if ($db->get_rowCount() > 0) {
        while ($row = $db->get_nextRow()) {
            $simcard = new testing();
            $simcard->simcardno = $row["simcardno"] . "[ " . $row["status"] . " ]";
            $simcard->id = $row["simid"];
            $sussimcards[] = $simcard;
        }
    }

    if (IsHead()) {

        ?>
        <script>
            function getUnit() {
                var data = $('#uteamid').val();

                jQuery("#unitno").autocomplete({
                    source: "route_ajax.php?uteamid_returnall=" + data,
                    select: function (event, ui) {

                        /*clear selected value */
                        jQuery(this).val(ui.item.value);
                        jQuery('#unitid').val(ui.item.uid)
                        return false;
                    }
                });
            }
            function getSim() {
                var data = $('#uteamid').val();
                jQuery("#simno").autocomplete({
                    source: "route_ajax.php?steamid_all=" + data,
                    select: function (event, ui) {
                        //                    insertUnit(ui.item.value, ui.item.uid);
                        /*clear selected value */
                        jQuery(this).val(ui.item.value);
                        jQuery('#simid').val(ui.item.sid)
                        return false;
                    }
                });
            }
            jQuery(document).ready(function () {
                jQuery('#registermsg').fadeIn();
                jQuery('#registermsg').fadeOut(7000);

                jQuery("#tags").autocomplete({
                    source: "route_ajax.php?action=teamdata",
                    minLength: 1,
                    select: function (event, ui) {
                        jQuery('#tags1').val(jQuery('#tags1').val() + ',' + ui.item.value);
                        AssignVehicle(ui.item.value, ui.item.teamid);
                        /*clear selected value */
                        jQuery(this).val("");
                        return false;
                    }

                });

            });

            function AssignVehicle(selected_name, vehicleid) {

                if (vehicleid != "" && jQuery('#em_vehicle_div_' + vehicleid).val() == null) {

                    var div = document.createElement('div');
                    var remove_image = document.createElement('img');
                    remove_image.src = "../../images/boxdelete.png";
                    remove_image.className = 'clickimage';
                    remove_image.onclick = function () {
                        removeVehicle1(vehicleid, selected_name);
                    };
                    div.className = 'recipientbox';
                    div.id = 'em_vehicle_div_' + vehicleid;
                    div.innerHTML = '<span>' + selected_name + '</span><input type="hidden" class="v_list_element" name="em_vehicle_' + vehicleid + '" value="' + vehicleid + '"/>';
                    jQuery("#listvehicle1").append(div);
                    jQuery(div).append(remove_image);
                }
            }

            function removeVehicle1(vehicleid, mail) {
                var mail1 = ',' + mail;
                $('#em_vehicle_div_' + vehicleid).remove();
                $('#tags1').val(function (index, value) {
                    return value.replace(mail1, '');
                });
            }

        </script>

        <?php
    }
    if (IsHead() || IsAdmin() || IsCRM()) {
        $db = new DatabaseManager();
        $customerno = $_GET['cid'];
        $SQL = "SELECT * FROM " . DB_PARENT . ".contactperson_type_master";
        $db->executeQuery($SQL);
        $cpdatas = Array();
        if ($db->get_rowCount() > 0) {
            while ($row = $db->get_nextRow()) {
                $cpdata = new customers();
                $cpdata->typeid = $row['person_typeid'];
                $cpdata->persontype = $row['person_type'];
                $cpdatas[] = $cpdata;
            }
        }
        $SQL1 = sprintf("SELECT customerno,customername,customercompany FROM " . DB_PARENT . ".customer WHERE customerno = %d", $customerno);
        $db->executeQuery($SQL1);
        if ($db->get_rowCount() > 0) {
            $row1 = $db->get_nextRow();
            $cno = $row1['customerno'];
            $customercompany = $row1['customercompany'];
        }

        /* Add Account Customer */
        ?>
        <br/>


        <div class="panel">
            <div class="paneltitle" align="center">Allot Ledger</div>
            <div class="panelcontents">
                <form method="post" name="form_ledgermap" id="form_ledgermap">
                    <table>
                        <tr>
                            <td id="msg_mapledger" style="display: none;color: #FF0000;text-align: center"></td>
                        </tr>
                        <tr>
                            <td>Ledger Name</td>
                            <td><input type="text" name="ledgername" id="ledgername" size="50"/></td>
                        <input type="hidden" name="ledgerid" id="ledgerid"/>
                        <input type="hidden" name="cust_ledger" id="cust_ledger" value="<?php echo $customerno; ?>"/>
                        </tr>
                        <tr>
                            <td></td>
                        </tr>
                    </table>
                    <input type="button" id="map_ledger" name="map_ledger" class="btn btn-default" value="Allot Ledger" onclick="Mapledger();">
                </form>
            </div>
        </div>
        <br>
        <div class="panel">
            <div class="paneltitle" align="center">List Of Ledger For Customer No <?php echo $customerid; ?></div>
            <div class="panelcontents">
                <table border="1" width="100%">
                    <tr>
                        <th>Sr No.</th>
                        <th>Ledger Id</th>
                        <th>Ledger Name</th>
                        <th>Address Line1</th>
                        <th>Address Line2</th>
                        <th>Address Line3</th>
                        <th>PAN No.</th>
                        <th>CST No.</th>
                        <th>VAT No.</th>
                        <th>ST No.</th>
                        <th>Phone</th>
                        <th>Email</th>
                        <th>Delete</th>
                        <th>Generate Invoice</th>

                    </tr>
                    <tbody id="demo_mappedledger" style="text-align: center;">

                    </tbody>
                </table>
            </div>
        </div>
        <br/>
        <div class="panel">
            <div class="paneltitle" align="center">Map Invoice Vehicles</div>
            <div class="panelcontents">
                <a href="ledger_mapvehicle.php?cno=<?php echo $customerno; ?>"><button class="btn btn-info">Add/View Map Vehicle</button></a>
            </div>
        </div>
        <br/>
        <!-- ------------------ add Po details--------------------------------- -->
        <?php
        if (IsSales() || IsHead()) {
            $pdo = $db->CreatePDOConn();
            $sp_params = "'" . $customerid . "'"
                    . ",''"
                    . ",''"
            ;
            $QUERY = PrepareSP('get_invoice_customer_address', $sp_params);
            $res = $pdo->query($QUERY);
            $podetails = Array();
            if ($res) {
                while ($row = $res->fetch(PDO::FETCH_ASSOC)) {
                    $DATA = new stdClass();
                    $DATA->invid = $row['invcustid'];
                    $DATA->invname = $row['invoicename'];
                    $DATA->acc_cust = $row['customer'];
                    $DATA->custno = $row['customergroup'];
                    $podetails[] = $DATA;
                }
            }
            $db->ClosePDOConn($pdo);
            ?>
            <div class="panel">
                <div class="paneltitle" align="center">Add PO Details</div>
                <div class="panelcontents">
                    <span id="error_cust" style="display: none;color: #FF0000;text-align: center">Please Select Customer</span>
                    <span id="add_po_succ" style="display: none;color: #00493a;text-align: center">Add Successfully</span>
                    <span id="fail_add_po" style="display: none;color: #FF0000;text-align: center">Some Error Has Occurred Try Again</span>
                    <form method="post" name="add_po" id="add_po">
                        <table>
                            <tr><td>Customer No</td>
                                <td>
                                    <input type ="text" name ="cust_grp" id="cust_grp" value="<?php echo $cno; ?>-<?php echo $customercompany; ?>" size="30" readonly/>
                                </td>
                            <input type ="hidden" name ="cid" id="cid" value="<?php echo $cno; ?>"/>
                            </tr>
                            <tr>
                                <td>PO Number</td>
                                <td>
                                    <input type ="text" name ="po_no" id="po_no">
                                </td>
                            </tr>
                            <tr>
                                <td>PO Date</td>
                                <td>
                                    <input type ="text" name ="podate" id="podate"/><button id="trigger10">...</button>
                                </td>
                            </tr>
                            <tr>
                                <td>PO Expiry</td>
                                <td>
                                    <input type ="text" name ="poexp" id="poexp"/><button id="trigger11">...</button>
                                </td>
                            </tr>
                            <tr>
                                <td>PO Amount</td>
                                <td>
                                    <input type ="text" name ="poamt" id="poamt"/>
                                </td>
                            </tr>
                            <tr>
                                <td>Description</td>
                                <td>
                                    <textarea name ="podesc" id="podesc" cols="30" rows="5"></textarea>
                                </td>
                            </tr>
                        </table>
                        <input type="button" id="add_po" name="add_po" class="btn btn-default" value="Add PO Details" onclick="addAccountpo();">
                    </form>
                </div>
            </div>
            <br>
            <div class="panel">
                <div class="paneltitle" align="center">View PO Details</div>
                <div class="panelcontents">
                    <table border="1" width="100%">
                        <tr>
                            <th>Sr No.</th>
                            <th>PO Number</th>
                            <th>PO Date</th>
                            <th>PO Expiry</th>
                            <th>PO Amount</th>
                            <th>Description</th>
                            <th>Edit</th>
                            <th>Delete</th>

                        </tr>
                        <tbody id="demo_po" style="text-align: center;">

                        </tbody>
                    </table>
                </div>
            </div>
            <br/>
            <script>
                Calendar.setup(
                        {
                            inputField: "podate", // ID of the input field
                            ifFormat: "%d-%m-%Y", // the date format
                            button: "trigger10" // ID of the button
                        });

                Calendar.setup(
                        {
                            inputField: "poexp", // ID of the input field
                            ifFormat: "%d-%m-%Y", // the date format
                            button: "trigger11" // ID of the button
                        });
            </script>
            <?php
        }
        ?>
        <?php
    }
//Datagtrid
    $devices = array();
    $db = new DatabaseManager();
    $isGenset = 0;
    $sms_lock_url = "";
    $SQL = sprintf("SELECT devices.device_invoiceno, devices.invoiceno, unit.uid, devices.customerno, devices.expirydate, unit.unitno, trans_status.status, devices.lastupdated, devices.registeredon,
                vehicle.vehicleno,vehicle.vehicleid, devices.simcardid, unit.sellingprice, vehicle.subscription_cost, vehicle.subscription_period, unit.onlease
                FROM devices
                INNER JOIN unit ON unit.uid = devices.uid
                LEFT OUTER JOIN simcard ON simcard.id = devices.simcardid
                LEFT OUTER JOIN vehicle ON vehicle.vehicleid = unit.vehicleid
                INNER JOIN " . DB_PARENT . ".trans_status ON trans_status.id = unit.trans_statusid WHERE unit.customerno = %d ORDER BY unit.trans_statusid ASC, simcard.trans_statusid DESC, devices.expirydate DESC", $customerid);
    $db->executeQuery($SQL);
    if ($db->get_rowCount() > 0) {
        $x = 1;
        while ($row = $db->get_nextRow()) {
            $device = new VODevices();
            $device->deviceid = $row["uid"];
            $device->sellingprice = $row["sellingprice"];
            $onlease = $row["onlease"]; 
            if($onlease == 1)
            {
                $device->onlease = "Yes";
            }
            else
            {
                $device->onlease = "No";                
            }
            $device->subscription_cost = $row["subscription_cost"];            
            $p = $row["subscription_period"];
            if($p == 1)
            {
                $device->subscription_period = "Monthly";
            }
            elseif($p == 3)
            {
                $device->subscription_period = "Quarterly";                
            }
            elseif($p == 6)
            {
                $device->subscription_period = "Six Monthly";                
            }            
            elseif($p == 12)
            {
                $device->subscription_period = "Yearly";                
            }                     
            else
            {
                $device->subscription_period = $p;                
            }                       
            $device->customerno = $row["customerno"];
            $customerno = $row["customerno"];
            $device->invoiceno = $row["invoiceno"];
            $device->expirydate = date("d-m-Y", strtotime($row["expirydate"]));
            if ($row["uid"] == 0) {
                $device->unitassigned = "Not yet";
            } else {
                $device->unitassigned = $row["unitno"] . " [ " . $row["status"] . " ]";
            }
            $device->phone = $row["phone"];
            $device->lastupdated = date("d-m-Y h:i", strtotime($row["lastupdated"]));
            $device->registeredon = $row["registeredon"];
            $device->vehicleno = $row["vehicleno"];
            $vehicleid = $row['vehicleid'];
            $teamid = $_SESSION['sessionteamid'];
            $device->simcardid = $row["simcardid"];

            $device->x = $x;
            //echo  $device->geneset1;
            //$device->geneset = $device->geneset1. "-" .$device->geneset2;
            $devices[] = $device;
            $x++;
        }
    }
    if (isset($devices)) {
        foreach ($devices as $thisdevice) {
            $db = new DatabaseManager();
            $SQL = sprintf("SELECT * FROM simcard INNER JOIN " . DB_PARENT . ".trans_status ON trans_status.id = simcard.trans_statusid WHERE simcard.id = %d", $thisdevice->simcardid);
            $db->executeQuery($SQL);
            if ($db->get_rowCount() > 0) {
                while ($row = $db->get_nextRow()) {
                    $thisdevice->simcardno = $row["simcardno"] . " [ " . $row["status"] . " ]";
                }
            } else {
                $thisdevice->simcardno = "Demapped";
            }
        }
    }
    ?>

    <hr/>

    <div class="panel">
        <div class="paneltitle" align="center">Device List</div>
        <div class="panelcontents">
            <?php
            $dg = new objectdatagrid($devices);
            $dg->AddAction("View/Edit", "../../images/edit.png", "pushcommand.php?id=%d");
            $dg->AddColumn("Sr.No", "x");
            $dg->AddColumn("Unit #", "unitassigned");
            $dg->AddColumn("Simcard #", "simcardno");
            $dg->AddColumn("Vehicle #", "vehicleno");
            $dg->AddColumn("Proforma Invoice #", "invoiceno");            
            $dg->AddColumn("Device Invoice #", "device_invoiceno");
            $dg->AddColumn("Device Price", "sellingprice");                        
            $dg->AddColumn("Subscription Invoice #", "invoiceno");
            $dg->AddColumn("Subscription Price", "subscription_cost");                        
            $dg->AddColumn("Subscription Period", "subscription_period");                                    
            $dg->AddColumn("Lease", "onlease");                        
            $dg->AddColumn("Last Updated", "lastupdated");
            $dg->AddColumn("Expiry Date", "expirydate");
            $dg->SetNoDataMessage("No Devices");
            $dg->AddIdColumn("deviceid");
            $dg->Render();
            ?>
        </div>
    </div>

    <br/>

    <div class="panel">
        <div class="paneltitle" align="center">Notes</div>
        <div class="panelcontents">

            <form method="post" action="modifycustomer.php"  enctype="multipart/form-data">
                <input type="hidden" name="customerid" value="<?php echo( $customerid ); ?>"/>
                <table width="40%">
                    <tr>
                        <td>Notes</td><td><textarea name="customer_notes" style='width:300px;' id ='customer_notes'></textarea></td>
                    </tr>
                    <tr>
                        <td>Send Email to</td>
                        <td>
                            <input  type="text" name="tags" id="tags" size="20" value="" autocomplete="off" placeholder="Enter email id"  />
                            <input  type="hidden" name="tags1"  id="tags1">
                        </td>
                    </tr>
                    <tr>
                        <td></td>
                        <td>
                            <div id="listvehicle1" ></div>
                        </td>
                    </tr>
                </table>
                <input type="submit" name="add_notes" value="Add Notes" />
            </form>
        </div>
    </div>



    <?php
    $db = new DatabaseManager();
    $SQL = sprintf("select * from " . DB_PARENT . ".customer_notes WHERE customerno = %d AND isdeleted=0 order by entrytime desc", $customerid);
    $db->executeQuery($SQL);
    $details = array();
    if ($db->get_rowCount() > 0) {
        $x = 1;
        while ($row = $db->get_nextRow()) {
            $comdetail = new stdClass();
            $comdetail->cdid = $row["cdid"];
            $comdetail->srno = $x;
            $comdetail->details = $row["details"];
            $comdetail->entrytime = date("d-m-Y H:i", strtotime($row["entrytime"]));
            $details[] = $comdetail;
            $x++;
        }
    }
    ?>
    <div class="panel">
        <div class="panelcontents">
            <?php
            $dg = new objectdatagrid($details);
            $dg->AddColumn("Sr. No", "srno");
            $dg->AddColumn("Notes", "details");
            $dg->AddColumn("Timestamp", "entrytime");
            $dg->SetNoDataMessage("No Notes");
            $dg->AddIdColumn("cdid");
            $dg->Render();
            ?>
        </div>
    </div>

    <br/>
    <?php
    include("footer.php");
    ?>

    <style>
        .recipientbox {
            border: 1px solid #999999;
            float: left;
            font-weight: 700;
            padding: 4px 27px;
            /*    width: 100px;*/

            float:left;
            -webkit-transition:all 0.218s;
            -webkit-user-select:none;
            background-color:#000;
            /*background-image:-webkit-linear-gradient(top, #4D90FE, #4787ED);*/
            border:1px solid #3079ED;
            color:#FFFFFF;
            text-shadow:rgba(0, 0, 0, 0.0980392) 0 1px;
            border:1px solid #DCDCDC;
            border-bottom-left-radius:2px;
            border-bottom-right-radius:2px;
            border-top-left-radius:2px;
            border-top-right-radius:2px;

            cursor:default;
            display:inline-block;
            font-size:11px;
            font-weight:bold;
            height:27px;
            line-height:27px;
            min-width:46px;
            padding:0 8px;
            text-align:center;

            border: 1px solid rgba(0, 0, 0, 0.1);
            color:#fff !important;
            font-size: 11px;
            font: bold 11px/27px Arial,sans-serif !important;
            vertical-align: top;
            margin-left:5px;
            margin-top:5px;
            text-align:left;
        }
        .recipientbox img {
            float:right;
            padding-top:5px;
        }
        .labelwidth{
            width:200px;
        }
    </style>
