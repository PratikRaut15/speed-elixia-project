<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
include_once "session.php";
include_once "../../lib/system/utilities.php";
include "loginorelse.php";
include_once "../../constants/constants.php";
include_once "db.php";
include_once "../../lib/autoload.php";
include_once "../../lib/system/Date.php";
include_once "../../lib/system/Sanitise.php";
include_once "../../lib/components/gui/objectdatagrid.php";
include_once "../../lib/model/VODevices.php";

$_scripts[] = "../../scripts/jquery.min.js";

class VOPush {
};
$deviceid = GetSafeValueString(isset($_GET["id"]) ? $_GET["id"] : $_GET["id"], "long");
$db = new DatabaseManager();

$SQL = sprintf('SELECT simcardid FROM devices WHERE uid = ' . $deviceid);
$db->executeQuery($SQL);
if ($db->get_rowCount() > 0) {
    while ($row = $db->get_nextRow()) {
        $simcardid = $row["simcardid"];
    }
}

$teamid = GetLoggedInUserId();

class testing {
}

$timeslot_array = Array();
$SQL = sprintf("SELECT tsid, timeslot FROM " . DB_PARENT . ".sp_timeslot");
$db->executeQuery($SQL);
if ($db->get_rowCount() > 0) {
    while ($row = $db->get_nextRow()) {
        $timeslot = new testing();
        $timeslot->tsid = $row["tsid"];
        $timeslot->timeslot = $row["timeslot"];
        $timeslot_array[] = $timeslot;
    }
}

$SQL = sprintf("SELECT team.teamid, team.name FROM " . DB_PARENT . ".team order by team.name asc");
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
date_default_timezone_set("Asia/Calcutta");
$today = date("Y-m-d H:i:s");

// ------------------------------------------------------------------  Remove ------------------------------------------------------------------------
if (isset($_POST["bothuremove"])) {
    $comments = GetSafeValueString($_POST["comments"], "string");
    $deviceid = GetSafeValueString($_POST["deviceid"], "string");
    $oldunitno = $deviceid;
    $teamid = GetSafeValueString($_POST["uteamid_remove"], "string");
    $sendmailremove = GetSafeValueString($_POST["sendmailremove"], "string");

    $SQL = sprintf('SELECT simcardid FROM devices WHERE uid = ' . $oldunitno);
    $db->executeQuery($SQL);
    if ($db->get_rowCount() > 0) {
        while ($row = $db->get_nextRow()) {
            $simcardid = $row["simcardid"];
        }
    }
    $db = new DatabaseManager();
    $SQL = sprintf("UPDATE unit SET trans_statusid= 18,teamid=" . $teamid . ", comments = '" . $comments . "' where uid=" . $oldunitno);
    $db->executeQuery($SQL);

    $SQL = sprintf("UPDATE simcard SET trans_statusid= 19,teamid=" . $teamid . ", comments = '" . $comments . "' where id=" . $simcardid);
    $db->executeQuery($SQL);

    $SQL = sprintf('SELECT simcardno FROM simcard WHERE id = ' . $simcardid);
    $db->executeQuery($SQL);
    if ($db->get_rowCount() > 0) {
        while ($row = $db->get_nextRow()) {
            $simcardno = $row["simcardno"];
        }
    }

    $customerno = GetSafeValueString($_POST["customerid"], "string");

    $SQLunit = sprintf("INSERT INTO " . DB_PARENT . ".trans_history (
    `customerno` ,`unitid`,`teamid`, `type`, `trans_time`, `statusid`, `transaction`, `simcardno`, `invoiceno`, `expirydate`, `allot_teamid`, `comments`)
    VALUES (
    '%d', '%d', '%s', 0, '%s', 6, '%s','%s','%s','%s',%d,'%s')", $customerno, $oldunitno, GetLoggedInUserId(), Sanitise::DateTime($today), "Removed", $simcardno, "", "", $teamid, $comments);
    $db->executeQuery($SQLunit);

    $SQLsim = sprintf("INSERT INTO " . DB_PARENT . ".trans_history (
    `customerno` ,`simcard_id`,`teamid`, `type`, `trans_time`, `statusid`, `transaction`, `simcardno`, `invoiceno`, `expirydate`, `allot_teamid`, `comments`)
    VALUES (
    '%d', '%d', '%s', 1, '%s', 14, '%s','%s','%s','%s',%d,'%s')", $customerno, $simcardid, GetLoggedInUserId(), Sanitise::DateTime($today), "Removed", "", "", "", $teamid, $comments);
    $db->executeQuery($SQLsim);

    //-----------------------Daily report remove row or unitno
    $SQL = sprintf("DELETE FROM dailyreport where customerno =" . $customerno . " AND uid=" . $oldunitno);
    $db->executeQuery($SQL);

    $SQL = sprintf('SELECT unitno FROM unit WHERE uid = ' . $oldunitno);
    $db->executeQuery($SQL);
    if ($db->get_rowCount() > 0) {
        while ($row = $db->get_nextRow()) {
            $unitno = $row["unitno"];
        }
    }

    $SQL = sprintf('SELECT vehicleid FROM vehicle WHERE uid = ' . $oldunitno);
    $db->executeQuery($SQL);
    if ($db->get_rowCount() > 0) {
        while ($row = $db->get_nextRow()) {
            $vehicleid = $row["vehicleid"];
        }
    }

    $SQLunit = sprintf("INSERT INTO " . DB_PARENT . ".trans_history (
    `customerno` ,`unitid`,`teamid`, `type`, `trans_time`, `statusid`, `transaction`, `simcardno`, `invoiceno`, `expirydate`, `allot_teamid`, `comments`, `vehicleid`)
    VALUES (
    '%d', 0, '%s', 2, '%s', 0, '%s', '%s', '%s', '%s',%d,'%s','%s')", $customerno, GetLoggedInUserId(), Sanitise::DateTime($today), "Removed Unit # : " . $unitno . " and Removed Sim # " . $simcardno, "", "", "", $teamid, $comments, $vehicleid);
    $db->executeQuery($SQLunit);
    $thid = $db->get_insertedId();

    // Device Allottment - Log
    $SQLunit = sprintf("INSERT INTO " . DB_PARENT . ".trans_history (
    `customerno` ,`simcard_id`,`teamid`, `type`, `trans_time`, `statusid`, `transaction`, `simcardno`, `invoiceno`, `expirydate`, `allot_teamid`, `comments`, `vehicleid`)
    VALUES (
    1, '%d', '%s', 1, '%s', %d, '%s','%s','%s','%s',%d,'%s','%s')", $simcardid, GetLoggedInUserId(), Sanitise::DateTime($today), 19, "Simcard Allotment", "", "", "", $teamid, $comments, $vehicleid);
    $db->executeQuery($SQLunit);

    $SQLunit = sprintf("INSERT INTO " . DB_PARENT . ".trans_history (
    `customerno` ,`unitid`,`teamid`, `type`, `trans_time`, `statusid`, `transaction`, `simcardno`, `invoiceno`, `expirydate`, `allot_teamid`, `comments`, `vehicleid`)
    VALUES (
    1, '%d', '%s', 0, '%s', %d, '%s','%s','%s','%s',%d,'%s','%s')", $oldunitno, GetLoggedInUserId(), Sanitise::DateTime($today), 18, "Device Allotment", "", "", "", $teamid, $comments, $vehicleid);
    $db->executeQuery($SQLunit);

    $SQL = sprintf('SELECT vehicleno,groupid FROM vehicle WHERE vehicleid = ' . $vehicleid);
    $db->executeQuery($SQL);
    if ($db->get_rowCount() > 0) {
        while ($row = $db->get_nextRow()) {
            $vehicleno = $row["vehicleno"];
            $groupid = $row["groupid"];
        }
    }

    // Service Call
    $SQLsc = sprintf("INSERT INTO " . DB_PARENT . ".servicecall (
    `uid` ,`simcardid`,`vehicleno`, `thid`, `teamid`, `type`)
    VALUES (
    %d, '%d', '%s', %d, %d, 2)", $oldunitno, $simcardid, $vehicleno, $thid, $teamid);
    $db->executeQuery($SQLsc);

    // Customerno - Make it 1
    $SQL = sprintf("UPDATE unit SET customerno=1, userid=0,  comments = '" . $comments . "' where uid=" . $oldunitno);
    $db->executeQuery($SQL);

    $SQL = sprintf("UPDATE devices SET customerno=1, expirydate='0000-00-00', device_invoiceno = '', inv_generatedate = '0000-00-00 00:00:00', po_no='', po_date='0000-00-00', invoiceno='', installdate='0000-00-00' where uid='%d'", $oldunitno);
    $db->executeQuery($SQL);

    // unset lease on old device
    $SQL = sprintf('UPDATE unit SET onlease=0 WHERE uid =' . $oldunitno);
    $db->executeQuery($SQL);

    // Populate Vehicles

    $SQL = sprintf('UPDATE vehicle SET customerno=1 where uid =' . $oldunitno);
    $db->executeQuery($SQL);

    ////////////////////////////vehiclewise alert////////////////////////////////
    $SQL = sprintf('UPDATE vehiclewise_alert SET customerno=1 where vehicleid=' . $vehicleid);
    $db->executeQuery($SQL);
    ///////////////////////////////////////////////////////////////////////////////

    $SQL = sprintf('UPDATE driver SET customerno=1 where vehicleid=' . $vehicleid);
    $db->executeQuery($SQL);

    $SQL = sprintf('UPDATE eventalerts SET customerno=1 where vehicleid=' . $vehicleid);
    $db->executeQuery($SQL);

    $SQL = sprintf('UPDATE ignitionalert SET customerno=1 where vehicleid=' . $vehicleid);
    $db->executeQuery($SQL);

    $SQL = sprintf('UPDATE acalerts SET customerno=1 where vehicleid=' . $vehicleid);
    $db->executeQuery($SQL);

    $SQL = sprintf('UPDATE checkpointmanage SET customerno=1, isdeleted=1 where vehicleid=' . $vehicleid);
    $db->executeQuery($SQL);

    $SQL = sprintf('UPDATE fenceman SET customerno=1, isdeleted=1 where vehicleid=' . $vehicleid);
    $db->executeQuery($SQL);

    $SQL = sprintf('UPDATE groupman SET customerno=1, isdeleted=1 where vehicleid=' . $vehicleid);
    $db->executeQuery($SQL);

    $SQL = sprintf('UPDATE reportman SET customerno=1 where uid=' . $oldunitno);
    $db->executeQuery($SQL);

// Create unit directory
    $relativepath = "../..";
    if (!is_dir($relativepath . '/customer/1/unitno/' . $oldunitno)) {
        // Directory doesn't exist.
        mkdir($relativepath . '/customer/1/unitno/' . $oldunitno, 0777, true) or die("Could not create directory");
    }

    if (!is_dir($relativepath . '/customer/1/unitno/' . $oldunitno . '/sqlite')) {
        // Directory doesn't exist.
        mkdir($relativepath . '/customer/1/unitno/' . $oldunitno . '/sqlite', 0777, true) or die("Could not create directory");
    }

    $SQL = sprintf("SELECT * FROM unit WHERE trans_statusid <> '10' AND customerno=" . $customerno);
    $db->executeQuery($SQL);
    if (!$db->get_rowCount() > 0) {
        $SQL = sprintf("UPDATE " . DB_PARENT . ".user SET isdeleted=1 WHERE customerno=" . $customerno);
        $db->executeQuery($SQL);
    }

    $SQL = sprintf("SELECT name FROM " . DB_PARENT . ".team WHERE teamid = " . $teamid . "");
    $db->executeQuery($SQL);
    while ($row = $db->get_nextRow()) {
        $elixir = $row['name'];
    }

    if ($sendmailremove == 1) {
        // $SQL = sprintf("select * from user inner join groupman on user.userid <> groupman.userid where user.customerno = ".$customerno." and user.email <> '' and user.groupid ='0' and (user.role = 'Administrator' OR user.role = 'Master') group by user.userid");
        $SQL = sprintf("select c.* from user c left outer join groupman p on p.groupid = " . $groupid . " left outer join groupman on c.userid <> groupman.userid where c.customerno = " . $customerno . " and c.email <> ''and c.isdeleted=0 and (c.groupid=" . $groupid . " or c.groupid ='0' ) and (c.role = 'Administrator' OR c.role = 'Master')group by c.userid");
        $db->executeQuery($SQL);
        if ($db->get_rowCount() > 0) {
            while ($row = $db->get_nextRow()) {
                $mail = new VOPush();
                $mail->username = $row['username'];
                $mail->realname = $row['realname'];
                $mail->email = $row['email'];
                $mail->vehicleno = $vehicleno;
                $mail->unit = $unitno;
                $mail->sim = $simcardno;
                $mail->elixir = $elixir;
                $mail->comments = $comments;
                $mail->subject = 'Unit & Simcard Replace Details';
                SendEmailRemove($mail);
            }
        }
    }

    header("Location: modifycustomer.php?cid=$customerno");
}

if (isset($_POST["reinstall"])) {
    $customerno = GetSafeValueString($_POST["customerid"], "string");
    $unitid = GetSafeValueString($_POST["unitid"], "string");
    $eteamid = GetSafeValueString($_POST["uteamid_reinstall"], "string");
    $newvehicleno = GetSafeValueString($_POST["newvehicleno"], "string");
    $sendmailreinstall = GetSafeValueString($_POST["sendmailreinstall"], "string");
    $todaysdate = date("Y-m-d H:i:s");

    $pdo = $db->CreatePDOConn();
    $sp_params = "'" . $todaysdate . "'"
    . ",'" . $unitid . "'"
    . ",'" . $eteamid . "'"
    . ",'" . $newvehicleno . "'"
    . ",'" . GetLoggedInUserId() . "'"
        . ",@is_executed,@newvehicleno,@oldvehicleno,@username,@realname,@email,@elixir";

    $queryCallSP = "CALL " . speedConstants::SP_REINSTALLDEV . "($sp_params)";
    $arrResult = $pdo->query($queryCallSP)->fetch(PDO::FETCH_ASSOC);
    $db->ClosePDOConn($pdo);
    $outputParamsQuery = "SELECT @is_executed AS is_executed";
    $outputResult = $pdo->query($outputParamsQuery)->fetch(PDO::FETCH_ASSOC);
    if ($outputResult["is_executed"] == 1) {
        header("Location: modifycustomer.php?cid=$customerno");
    }
}

// ------------------------------------------------------------------  Remove Bad ------------------------------------------------------------------------
if (isset($_POST["bothuremovebad"])) {
    $todaysdate = date("Y-m-d H:i:s");
    $comments = GetSafeValueString($_POST["comments"], "string");
    $unitid = GetSafeValueString($_POST["deviceid"], "string");
    $teamid = GetSafeValueString($_POST["uteamid_remove_bad"], "string");
    $sendmailremovebad = GetSafeValueString($_POST["sendmailremovebad"], "string");
    $customerno = GetSafeValueString($_POST["customerid"], "string");
    $pdo = $db->CreatePDOConn();
    $todaysdate = date("Y-m-d H:i:s");
    $sp_params = "'" . $todaysdate . "'"
    . ",'" . $customerno . "'"
    . ",'" . $unitid . "'"
    . ",'" . $teamid . "'"
    . ",'" . $comments . "'"
    . ",'" . GetLoggedInUserId() . "'"
        . ",@is_executed,@username,@realname,@email,@vehicleno,@unitno,@simno,@elixir";

    $queryCallSP = "CALL " . speedConstants::SP_REMOVE_BOTH . "($sp_params)";
    $arrResult = $pdo->query($queryCallSP)->fetch(PDO::FETCH_ASSOC);
    $db->ClosePDOConn($pdo);
    $outputParamsQuery = "SELECT @is_executed AS is_executed,@username AS username,@realname AS realname,@email AS email,@vehicleno AS vehicleno,@unitno AS unitno,@simno AS simno,@elixir AS elixir";
    $outputResult = $pdo->query($outputParamsQuery)->fetch(PDO::FETCH_ASSOC);

    if ($outputResult['is_executed'] == 1) {
        // Create unit directory
        $relativepath = "../..";
        if (!is_dir($relativepath . '/customer/1/unitno/' . $oldunitno)) {
            // Directory doesn't exist.
            mkdir($relativepath . '/customer/1/unitno/' . $oldunitno, 0777, true) or die("Could not create directory");
        }

        if (!is_dir($relativepath . '/customer/1/unitno/' . $oldunitno . '/sqlite')) {
            // Directory doesn't exist.
            mkdir($relativepath . '/customer/1/unitno/' . $oldunitno . '/sqlite', 0777, true) or die("Could not create directory");
        }

        if ($sendmailremovebad == 1) {
            $mail = new stdClass();
            $mail->username = $outputResult['username'];
            $mail->realname = $outputResult['realname'];
            $mail->email = $outputResult['email'];
            $mail->vehicleno = $outputResult['vehicleno'];
            $mail->unit = $outputResult['unitno'];
            $mail->sim = $outputResult['simno'];
            $mail->elixir = $outputResult['elixir'];
            $mail->comments = $comments;
            $mail->subject = 'Unit & Simcard Replace Details';
            SendEmailRemoveBad($mail);
        }
    }

    header("Location: modifycustomer.php?cid=$customerno");
}

// ------------------------------------------------------------------  Replace Both ------------------------------------------------------------------------
if (isset($_POST["bothureplace"])) {
    $customerno = GetSafeValueString($_POST["customerid"], "string");
    $comments = GetSafeValueString($_POST["comments"], "string");
    $oldunitid = GetSafeValueString($_POST["deviceid"], "string");
    $oldunitno = $deviceid;
    $newunitid = GetSafeValueString($_POST["uallotted_replace_both"], "string");
    $newsimid = GetSafeValueString($_POST["simallotted_replace_both"], "string");
    $teamid = GetSafeValueString($_POST["uteamid_replace_both"], "string");
    $sendmailreplaceboth = GetSafeValueString($_POST["sendmailreplaceboth"], "string");
    $pdo = $db->CreatePDOConn();
    $todaysdate = date("Y-m-d H:i:s");
    $sp_params = "'" . $todaysdate . "'"
    . ",'" . $customerno . "'"
    . ",'" . $oldunitid . "'"
    . ",'" . $teamid . "'"
    . ",'" . $newunitid . "'"
    . ",'" . $newsimid . "'"
    . ",'" . $comments . "'"
    . ",'" . GetLoggedInUserId() . "'"
        . ",@is_executed,@username,@realname,@email,@vehicleno,@oldunitno,@oldsimno,@newunitno,@newsimno,@elixir,@errormsg";

    $queryCallSP = "CALL " . speedConstants::SP_REPLACE_BOTH . "($sp_params)";
    $arrResult = $pdo->query($queryCallSP)->fetch(PDO::FETCH_ASSOC);
    $db->ClosePDOConn($pdo);
    $outputParamsQuery = "SELECT @is_executed AS is_executed,@username AS username,@realname AS realname,@email AS email,@vehicleno AS vehicleno,@oldunitno AS oldunitno,@oldsimno AS oldsimno,@newunitno AS newunitno,@newsimno AS newsimno,@elixir AS elixir,@errormsg AS errormsg";
    $outputResult = $pdo->query($outputParamsQuery)->fetch(PDO::FETCH_ASSOC);

    if ($outputResult['is_executed'] == 1) {
        // Create unit directory
        $relativepath = "../..";
        if (!is_dir($relativepath . '/customer/' . $customerno . '/unitno/' . $outputResult['newunitno'])) {
            // Directory doesn't exist.
            mkdir($relativepath . '/customer/' . $customerno . '/unitno/' . $outputResult['newunitno'], 0777, true) or die("Could not create directory");
        }

        if (!is_dir($relativepath . '/customer/' . $customerno . '/unitno/' . $outputResult['newunitno'] . '/sqlite')) {
            // Directory doesn't exist.
            mkdir($relativepath . '/customer/' . $customerno . '/unitno/' . $outputResult['newunitno'] . '/sqlite', 0777, true) or die("Could not create directory");
        }

        // Create unit directory
        $relativepath = "../..";
        if (!is_dir($relativepath . '/customer/1/unitno/' . $outputResult['oldunitno'])) {
            // Directory doesn't exist.
            mkdir($relativepath . '/customer/1/unitno/' . $outputResult['oldunitno'], 0777, true) or die("Could not create directory");
        }

        if (!is_dir($relativepath . '/customer/1/unitno/' . $outputResult['oldunitno'] . '/sqlite')) {
            // Directory doesn't exist.
            mkdir($relativepath . '/customer/1/unitno/' . $outputResult['oldunitno'] . '/sqlite', 0777, true) or die("Could not create directory");
        }

        if ($sendmailreplaceboth == 1) {
            $mail = new stdClass();
            $mail->username = $outputResult['username'];
            $mail->realname = $outputResult['realname'];
            $mail->email = $outputResult['email'];
            $mail->vehicleno = $outputResult['vehicleno'];
            $mail->oldunit = $outputResult['oldunitno'];
            $mail->oldsim = $outputResult['oldsimno'];
            $mail->newunit = $outputResult['newunitno'];
            $mail->newsim = $outputResult['newsimno'];
            $mail->elixir = $outputResult['elixir'];
            $mail->comments = $comments;
            $mail->subject = 'Unit & Simcard Replace Details';
            SendEmailReplaceBoth($mail);
        }
    }

    header("Location: modifycustomer.php?cid=$customerid");
}

// ------------------------------------------------------------------  Replace Simcard ------------------------------------------------------------------------

if (isset($_POST["ureplacesimcard"])) {
    $todaysdate = date("Y-m-d H:i:s");
    $customerno = GetSafeValueString($_POST["customerid"], "string");
    $comments = GetSafeValueString($_POST["comments"], "string");
    $unitid = GetSafeValueString($_POST["deviceid"], "string");
    $sendmailreplacesimcard = GetSafeValueString($_POST["sendmailreplacesimcard"], "string");
    $newsimid = GetSafeValueString($_POST["ureplacenewsimcard"], "string");
    $teamid = GetSafeValueString($_POST["uteamid_replace_sim"], "string");

    $pdo = $db->CreatePDOConn();
    $todaysdate = date("Y-m-d H:i:s");
    $sp_params = "'" . $todaysdate . "'"
    . ",'" . $customerno . "'"
    . ",'" . $unitid . "'"
    . ",'" . $teamid . "'"
    . ",'" . $newsimid . "'"
    . ",'" . $comments . "'"
    . ",'" . GetLoggedInUserId() . "'"
        . ",@is_executed,@username,@realname,@email,@vehicleno,@oldsimcardno,@newsimcardno,@elixir";

    $queryCallSP = "CALL " . speedConstants::SP_REPLACE_SIM . "($sp_params)";

    $arrResult = $pdo->query($queryCallSP)->fetch(PDO::FETCH_ASSOC);
    $db->ClosePDOConn($pdo);
    $outputParamsQuery = "SELECT @is_executed AS is_executed,@username AS username,@realname AS realname,@email AS email,@vehicleno AS vehicleno,@oldsimcardno AS oldsimcardno,@newsimcardno AS newsimcardno,@elixir AS elixir";
    $outputResult = $pdo->query($outputParamsQuery)->fetch(PDO::FETCH_ASSOC);

    if ($outputResult['is_executed'] == 1) {
        if ($sendmailreplacesimcard == 1) {
            $mail = new stdClass();
            $mail->username = $outputResult['username'];
            $mail->realname = $outputResult['realname'];
            $mail->email = $outputResult['email'];
            $mail->vehicleno = $outputResult['vehicleno'];
            $mail->oldsim = $outputResult['oldsimcardno'];
            $mail->newsim = $outputResult['newsimcardno'];
            $mail->elixir = $outputResult['elixir'];
            $mail->comments = $comments;
            $mail->subject = 'Simcard Replace Details';
            SendEmailReplaceSimcard($mail);
        }
    }
    header("Location: modifycustomer.php?cid=$customerid");
}

// ------------------------------------------------------------------  Replace Device ------------------------------------------------------------------------
if (isset($_POST["ureplacedevice"])) {
    $todaysdate = date("Y-m-d H:i:s");
    $customerno = GetSafeValueString($_POST["customerid"], "string");
    $comments = GetSafeValueString($_POST["comments"], "string");
    $oldunitid = GetSafeValueString($_POST["deviceid"], "string");
    $newunitid = GetSafeValueString($_POST["ureplacenewunit"], "string");
    $teamid = GetSafeValueString($_POST["uteamid_replace"], "string");
    $customerid = GetSafeValueString($_POST["customerid"], "string");
    $sendmailreplace = GetSafeValueString($_POST["sendmailreplace"], "string");

    $pdo = $db->CreatePDOConn();
    $todaysdate = date("Y-m-d H:i:s");
    $sp_params = "'" . $todaysdate . "'"
    . ",'" . $customerno . "'"
    . ",'" . $oldunitid . "'"
    . ",'" . $teamid . "'"
    . ",'" . $newunitid . "'"
    . ",'" . $comments . "'"
    . ",'" . GetLoggedInUserId() . "'"
        . ",@is_executed,@username,@realname,@email,@vehicleno,@oldunitno,@newunitno,@simcardno,@elixir,@errormsgOut";

    $queryCallSP = "CALL " . speedConstants::SP_REPLACE_DEVICE . "($sp_params)";
    $arrResult = $pdo->query($queryCallSP)->fetch(PDO::FETCH_ASSOC);
    $db->ClosePDOConn($pdo);
    $outputParamsQuery = "SELECT    @is_executed AS is_executed
                                    ,@username AS username
                                    ,@realname AS realname
                                    ,@email AS email
                                    ,@vehicleno AS vehicleno
                                    ,@oldunitno AS oldunitno
                                    ,@newunitno AS newunitno
                                    ,@simcardno AS simcardno
                                    ,@elixir AS elixir
                                    ,@errormsgOut AS errormsgOut";
    $outputResult = $pdo->query($outputParamsQuery)->fetch(PDO::FETCH_ASSOC);

    if ($outputResult['is_executed'] == 1) {
        // Create unit directory
        $relativepath = "../..";
        if (!is_dir($relativepath . '/customer/' . $customerno . '/unitno/' . $outputResult['newunitno'])) {
            // Directory doesn't exist.
            mkdir($relativepath . '/customer/' . $customerno . '/unitno/' . $outputResult['newunitno'], 0777, true) or die("Could not create directory");
        }

        if (!is_dir($relativepath . '/customer/' . $customerno . '/unitno/' . $outputResult['newunitno'] . '/sqlite')) {
            // Directory doesn't exist.
            mkdir($relativepath . '/customer/' . $customerno . '/unitno/' . $outputResult['newunitno'] . '/sqlite', 0777, true) or die("Could not create directory");
        }

        // Create unit directory
        $relativepath = "../..";
        if (!is_dir($relativepath . '/customer/1/unitno/' . $outputResult['oldunitno'])) {
            // Directory doesn't exist.
            mkdir($relativepath . '/customer/1/unitno/' . $outputResult['oldunitno'], 0777, true) or die("Could not create directory");
        }

        if (!is_dir($relativepath . '/customer/1/unitno/' . $outputResult['oldunitno'] . '/sqlite')) {
            // Directory doesn't exist.
            mkdir($relativepath . '/customer/1/unitno/' . $outputResult['oldunitno'] . '/sqlite', 0777, true) or die("Could not create directory");
        }

        if ($sendmailreplace == 1) {
            $mail = new stdClass();
            $mail->username = $outputResult['username'];
            $mail->realname = $outputResult['realname'];
            $mail->email = $outputResult['email'];
            $mail->vehicleno = $outputResult['vehicleno'];
            $mail->oldunitno = $outputResult['oldunitno'];
            $mail->newunitno = $outputResult['newunitno'];
            $mail->simcard = $outputResult['simcardno'];
            $mail->elixir = $outputResult['elixir'];
            $mail->comments = $comments;
            $mail->subject = 'Unit Replace Details';
            SendEmailReplace($mail);
        }
    }

    header("Location: modifycustomer.php?cid=$customerid");
    exit;
}

// ----------------------------------------------------------- Update Unit -------------------------------------------------------------- //

if (isset($_POST["updateunit"])) {
    // Populate Devices
    $customerno = GetSafeValueString($_POST["customerid"], "string");
    $comments = GetSafeValueString($_POST["comments"], "string");
    $cexpiry = GetSafeValueString($_POST["ucexpiry"], "string");
    $cexpiry = date("Y-m-d", strtotime($cexpiry));
    $cinstall = GetSafeValueString($_POST["cinstall"], "string");
    $cinstall = date("Y-m-d", strtotime($cinstall));
    $cinvoiceno = GetSafeValueString($_POST["cinvoiceno"], "string");
    $dinvoiceno = GetSafeValueString($_POST["dinvoiceno"], "string");
    $cvehicleno = GetSafeValueString($_POST["cvehicleno"], "string");
    $deviceid = GetSafeValueString($_POST["deviceid"], "string");
    $cpono = GetSafeValueString($_POST["cpono"], "string");
    $cpodate = GetSafeValueString($_POST["cpodate"], "string");
    if ($cpodate != '') {
        $cpodate = date("Y-m-d", strtotime($cpodate));
    }
    $cwarranty = date("Y-m-d", strtotime($cinstall . '+365 days'));

    $cutype = GetSafeValueString($_POST["utype"], "string");

    $cstype = 13;
    if ($cutype == 23) {
        $cstype = 24;
    }
    if ($cutype == 22) {
        $cstype = 25;
    }

    if ($cutype == '-1') {
        $SQL = sprintf("UPDATE unit SET trans_statusid=" . $cstype . ", comments = '" . $comments . "'  where uid='%s'", $deviceid);
    } else {
        $SQL = sprintf("UPDATE unit SET trans_statusid = %d ,comments = '" . $comments . "' where uid='%s'", $cutype, $deviceid);
    }
    $db->executeQuery($SQL);

    $simcardno = GetSafeValueString($_POST["simcardid"], "string");

    if ($cutype == '-1') {
        $SQL = sprintf("UPDATE simcard SET comments = '" . $comments . "' where id=" . $simcardno);
    } else {
        $SQL = sprintf("UPDATE simcard SET trans_statusid = " . $cstype . ", comments = '" . $comments . "' where id=" . $simcardno);
    }
    $db->executeQuery($SQL);

    $SQL = sprintf("UPDATE vehicle SET vehicleno ='" . $cvehicleno . "' where uid =" . $deviceid);
    $db->executeQuery($SQL);

    $SQL = sprintf("UPDATE devices SET expirydate ='%s', installdate ='%s',invoiceno ='%s', device_invoiceno = '%s', po_no='%s', po_date='%s', warrantyexpiry='%s' where uid='%s'", $cexpiry, $cinstall, $cinvoiceno, $dinvoiceno, $cpono, $cpodate, $cwarranty, $deviceid);
    $db->executeQuery($SQL);

    $sql = sprintf("select * from simcard where id=" . $simcardno);
    $db->executeQuery($sql);
    while ($row = $db->get_nextRow()) {
        $simno = $row['simcardno'];
    }

    $SQLunit = sprintf("INSERT INTO " . DB_PARENT . ".trans_history (
    `customerno` ,`unitid`,`teamid`, `type`, `trans_time`, `statusid`, `transaction`, `simcardno`, `invoiceno`, `expirydate`, `comments`)
    VALUES (
    $customerno, '%d', '%s', 0, '%s', 0, '%s','%s','%s','%s','%s')", $deviceid, GetLoggedInUserId(), Sanitise::DateTime($today), "Unit Updated: Expiry Date: " . $cexpiry . "; Install Date: " . $cinstall . "; Invoice No.: " . $cinvoiceno, $simno, $cinvoiceno, $cexpiry, $comments);
    $db->executeQuery($SQLunit);

    header("Location: modifycustomer.php?cid=$customerno");
}

// ----------------------------------------------------------- Reactivate -------------------------------------------------------------- //

if (isset($_POST["ureactivate"])) {
    $comments = GetSafeValueString($_POST["comments"], "string");
    $deviceid = GetSafeValueString($_POST["deviceid"], "string");
    $simcardid = GetSafeValueString($_POST["simcardid"], "string");
    $did = GetSafeValueString($_POST["did"], "string");

    $SQL = sprintf("UPDATE unit SET trans_statusid= 5, comments = '" . $comments . "' where uid=" . $deviceid);
    $db->executeQuery($SQL);

    $cexpiry = GetSafeValueString($_POST["cexpirydate"], "string");
    $cexpiry = date("Y-m-d", strtotime($cexpiry));
    $cinvoiceno = GetSafeValueString($_POST["cinvoiceno"], "string");
    $SQL = sprintf("UPDATE devices SET simcardid=%d, expirydate ='%s', invoiceno ='%s' where deviceid='%d'", $simcardid, $cexpiry, $cinvoiceno, $did);
    $db->executeQuery($SQL);

    $SQL = sprintf("UPDATE simcard SET trans_statusid= 13, comments = '" . $comments . "' where id=" . $simcardid);
    $db->executeQuery($SQL);

    $SQL = sprintf('SELECT unitno FROM unit WHERE uid = ' . $deviceid);
    $db->executeQuery($SQL);
    if ($db->get_rowCount() > 0) {
        while ($row = $db->get_nextRow()) {
            $unitno = $row["unitno"];
        }
    }

    $SQL = sprintf('SELECT simcardno FROM simcard WHERE id = ' . $simcardid);
    $db->executeQuery($SQL);
    if ($db->get_rowCount() > 0) {
        while ($row = $db->get_nextRow()) {
            $simcardno = $row["simcardno"];
        }
    }

    $customerno = GetSafeValueString($_POST["customerid"], "string");

    $SQLunit = sprintf("INSERT INTO " . DB_PARENT . ".trans_history (
    `customerno` ,`unitid`,`teamid`, `type`, `trans_time`, `statusid`, `transaction`, `simcardno`, `invoiceno`, `expirydate`, `comments`)
    VALUES (
    $customerno, '%d', '%s', 0, '%s', 5, '%s','%s','%s','%s','%s')", $deviceid, GetLoggedInUserId(), Sanitise::DateTime($today), "Reactivated", $simcardno, $cinvoiceno, $cexpiry, $comments);
    $db->executeQuery($SQLunit);

    $SQLsim = sprintf("INSERT INTO " . DB_PARENT . ".trans_history (
    `customerno` ,`simcard_id`,`teamid`, `type`, `trans_time`, `statusid`, `transaction`, `simcardno`, `invoiceno`, `expirydate`, `comments`)
    VALUES (
    $customerno, '%d', '%s', 1, '%s', 13, '%s','%s','%s','%s','%s')", $simcardid, GetLoggedInUserId(), Sanitise::DateTime($today), "Reactivated", "", "", "", $comments);
    $db->executeQuery($SQLsim);

    $SQLunit = sprintf("INSERT INTO " . DB_PARENT . ".trans_history (
    `customerno` ,`unitid`,`teamid`, `type`, `trans_time`, `statusid`, `transaction`, `simcardno`, `invoiceno`, `expirydate`, `comments`)
    VALUES (
    $customerno, 0, '%s', 2, '%s', 0, '%s', '%s', '%s', '%s','%s')", GetLoggedInUserId(), Sanitise::DateTime($today), "Reactivated Unit # : " . $unitno . ". and Sim # " . $simcard, "", "", "", $comments);
    $db->executeQuery($SQLunit);

    //header("Location: modifycustomer.php?cid=$customerno");
}

// ----------------------------------------------------------- Renewal -------------------------------------------------------------- //

if (isset($_POST["renewal"])) {
    // Populate Devices
    $comments = GetSafeValueString($_POST["comments"], "string");
    $cexpiry = GetSafeValueString($_POST["cexpiry"], "string");
    $cexpiry = date("Y-m-d", strtotime($cexpiry));
    $cinvoiceno = GetSafeValueString($_POST["cinvoiceno"], "string");
    $deviceid = GetSafeValueString($_POST["deviceid"], "string");
    $simcardid = GetSafeValueString($_POST["simcardid"], "string");

    $SQL = sprintf("UPDATE devices SET expirydate ='%s', invoiceno ='%s' where uid=%d", Sanitise::Date($cexpiry), Sanitise::String($cinvoiceno), Sanitise::Long($deviceid));
    $db->executeQuery($SQL);

    $customerno = GetSafeValueString($_POST["customerid"], "string");

    $SQL = sprintf('SELECT simcardno FROM simcard WHERE id = ' . $simcardid);
    $db->executeQuery($SQL);
    if ($db->get_rowCount() > 0) {
        while ($row = $db->get_nextRow()) {
            $simcard = $row["simcardno"];
        }
    }

    $SQL = sprintf('SELECT unitno FROM unit WHERE uid = ' . $deviceid);
    $db->executeQuery($SQL);
    if ($db->get_rowCount() > 0) {
        while ($row = $db->get_nextRow()) {
            $unitno = $row["unitno"];
        }
    }

    $SQLunit = sprintf("INSERT INTO " . DB_PARENT . ".trans_history (
    `customerno` ,`unitid`,`teamid`, `type`, `trans_time`, `statusid`, `transaction`, `simcardno`, `invoiceno`, `expirydate`, `comments`)
    VALUES (
    $customerno, '%d', '%s', 0, '%s', 0, '%s','%s','%s','%s','%s')", $deviceid, GetLoggedInUserId(), Sanitise::DateTime($today), "Renewed", $simcard, $cinvoiceno, $cexpiry, $comments);
    $db->executeQuery($SQLunit);

    $SQLunit = sprintf("INSERT INTO " . DB_PARENT . ".trans_history (
    `customerno` ,`unitid`,`teamid`, `type`, `trans_time`, `statusid`, `transaction`, `simcardno`, `invoiceno`, `expirydate`, `comments`)
    VALUES (
    $customerno, 0, '%s', 2, '%s', 0, '%s', '%s', '%s', '%s', '%s')", GetLoggedInUserId(), Sanitise::DateTime($today), "Renewed Unit # : " . $unitno . ". with Sim # " . $simcard, "", "", "", $comments);
    $db->executeQuery($SQLunit);

    header("Location: modifycustomer.php?cid=$customerno");
}

// ----------------------------------------------------------- SMS Lock -------------------------------------------------------------- //

if (isset($_POST["switchlock"])) {
    $customerno = GetSafeValueString($_POST["customerid"], "string");
    $vehicleid = GetSafeValueString($_POST["vehicleid"], "string");
    $cm = new CustomerManager();
    if (isset($_POST["chk_val"])) {
        $cm->setlock($vehicleid, $customerno);
    } elseif (!isset($_POST["chk_val"])) {
        $cm->removelock($vehicleid, $customerno);
    }
    header("Location: modifycustomer.php?cid=$customerno");
}

// ----------------------------------------------------------- Telephonic Alert Lock -------------------------------------------------------------- //
if (isset($_POST["switchTelephoniclock"])) {
    $customerno = GetSafeValueString($_POST["customerid"], "string");
    $vehicleid = GetSafeValueString($_POST["vehicleid"], "string");
    $cm = new CustomerManager();
    if (isset($_POST["chk_telephonic_val"])) {
        $cm->setTelephonicLock($vehicleid, $customerno);
    } elseif (!isset($_POST["chk_telephonic_val"])) {
        $cm->removeTelephonicLock($vehicleid, $customerno);
    }
    header("Location: modifycustomer.php?cid=$customerno");
}

// ----------------------------------------------------------- Suspect Unit -------------------------------------------------------------- //

if (isset($_POST["ususpect"])) {
    $comments = GetSafeValueString($_POST["scomments"], "string");
    $deviceid = GetSafeValueString($_POST["deviceid"], "string");
    $simcardid = GetSafeValueString($_POST["simcardid"], "string");
    $sendmailsuspect = GetSafeValueString($_POST["sendmailsuspect"], "string");
    $customerno = GetSafeValueString($_POST["customerid"], "string");
    $apt_date = GetSafeValueString($_POST["sapt_date"], "string");
    $apt_date = date("Y-m-d", strtotime($apt_date));
    $coname = GetSafeValueString($_POST["coname"], "string");
    $cophone = GetSafeValueString($_POST["cophone"], "string");
    $priority = GetSafeValueString($_POST["spriority"], "string");
    $location = GetSafeValueString($_POST["slocation"], "string");
    $timeslot = GetSafeValueString($_POST["stimeslot"], "string");
    $purpose = GetSafeValueString($_POST["spurpose"], "string");
    $details = GetSafeValueString($_POST["sdetails"], "string");
    $coordinator = GetSafeValueString($_POST["scoordinator"], "string");
    $docketid = 0;

    if ($coname != "") {
        $sp_params =
            "'" . $coname . "'" .
            ",'" . $cophone . "'" .
            ",'" . $customerno . "'" .
            ",'" . $teamid . "'" .
            ",'" . $today . "'"
            . "," . "@lastInsertId";

        $pdo = $db->CreatePDOConn();
        $queryCallSP = "CALL " . speedConstants::SP_ADD_CORDINATOR_DETAILS . "($sp_params)";
        $result = $pdo->query($queryCallSP)->fetch(PDO::FETCH_ASSOC);
        $outputParamsQuery = "SELECT @lastInsertId";
        $outputResult = $pdo->query($outputParamsQuery)->fetch(PDO::FETCH_ASSOC);
        $coordinator = $outputResult['@lastInsertId'];
    }

    $pdo = $db->CreatePDOConn();
    $sp_params = "'" . $obj->comments . "'" .
    ",'" . $obj->deviceid . "'" .
    ",'" . $obj->simcardid . "'" .
    ",'" . $obj->bucketcust . "'" .
    ",'" . $obj->apt_date . "'" .
    ",'" . $obj->priority . "'" .
    ",'" . $obj->location . "'" .
    ",'" . $obj->timeslot . "'" .
    ",'" . $obj->operation . "'" .
    ",'" . $obj->details . "'" .
    ",'" . $obj->coordinator . "'" .
    ",'" . $obj->createby . "'" .
    ",'" . $obj->todaysdate . "'" .
    ",'" . $obj->docketId . "'" . ",@bucketid,@isexecuted,@vehicleno,@unitno,@simcardno,@username,@realname,@email,@elixir,@msg";

    $queryCallSP = "CALL " . speedConstants::SP_SUSPECT_UNIT . "($sp_params)";

    $arrResult = $pdo->query($queryCallSP);

    $outputParamsQuery = "SELECT @bucketid AS bucketid
                                    ,@isexecuted AS is_executed
                                    ,@vehicleno AS vehicleno
                                    ,@unitno AS unitno
                                    ,@simcardno AS simcardno
                                    ,@username AS username
                                    ,@realname AS realname
                                    ,@email AS email
                                    ,@elixir AS elixir
                                    ,@msg AS msg";
    $outputResult = $pdo->query($outputParamsQuery)->fetch(PDO::FETCH_ASSOC);

    if ($outputResult['is_executed'] == 1) {
        $msg = "Suspect Successfully.";
        // Send Email to Customer
        if ($obj->email == 1) {
            $mail = new stdClass();
            $mail->username = $outputResult['username'];
            $mail->realname = $outputResult['realname'];
            $mail->email = $outputResult['email'];
            $mail->vehicleno = $outputResult['vehicleno'];
            $mail->unit = $outputResult['unitno'];
            $mail->sim = $outputResult['simcardno'];
            $mail->elixir = $outputResult['elixir'];
            $mail->comments = $obj->comments;
            $mail->subject = 'Suspect Device Details';
            $mail_status = $this->SendEmailSuspect($mail);
            if ($mail_status == 1) {
                $msg = "Suspect Successfully.Mail Sent";
            } else {
                $msg = "Suspect Successfully.Mail not sent";
            }
        }
    } else {
        $msg = "Vehicle not present.";
    }
    header("Location: modifycustomer.php?cid=$customerno&msg=$msg");
}

// ----------------------------------------------------------- Resolve Unit -------------------------------------------------------------- //

if (isset($_POST["uresolve"])) {
    $comments = GetSafeValueString($_POST["comments"], "string");
    $unitid = GetSafeValueString($_POST["deviceid"], "string");
    $simcardid = GetSafeValueString($_POST["simcardid"], "string");
    $teamid = GetSafeValueString($_POST["uteamid_repair"], "string");
    $sendmailrepair = GetSafeValueString($_POST["sendmailrepair"], "string");
    $customerno = GetSafeValueString($_POST["customerid"], "string");
    $today = date("Y-m-d H:i:s");

    $pdo = $db->CreatePDOConn();
    $todaysdate = date("Y-m-d H:i:s");
    $sp_params = "'" . $today . "'"
    . ",'" . $comments . "'"
    . ",'" . $unitid . "'"
    . ",'" . $simcardid . "'"
    . ",'" . $teamid . "'"
    . ",'" . GetLoggedInUserId() . "'"
        . ",'" . $customerno . "'"
        . ",@is_executed,@username,@realname,@email,@vehicleno,@unitnumber,@simcardno,@elixir";

    $queryCallSP = "CALL " . speedConstants::SP_REPAIR . "($sp_params)";
    $arrResult = $pdo->query($queryCallSP);

    $outputParamsQuery = "SELECT @is_executed AS is_executed,@username AS username,@realname AS realname,@email AS email,@vehicleno AS vehicleno,@unitnumber AS unitnumber,@simcardno AS simcardno,@elixir AS elixir";
    $outputResult = $pdo->query($outputParamsQuery)->fetch(PDO::FETCH_ASSOC);
    $db->ClosePDOConn($pdo);

    if ($outputResult['is_executed'] == 1) {
        if ($sendmailrepair == 1) {
            $mail = new VOPush();
            $mail->username = $outputResult['username'];
            $mail->realname = $outputResult['realname'];
            $mail->email = $outputResult['email'];
            $mail->vehicleno = $outputResult['vehicleno'];
            $mail->unitno = $outputResult['unitnumber'];
            $mail->simcard = $outputResult['simcardno'];
            $mail->elixir = $outputResult['elixir'];
            $mail->comments = $comments;
            $mail->subject = 'Unit Repair Details';
            SendEmail($mail);
        }
    }
    header("Location: modifycustomer.php?cid=$customerno");
}

// ----------------------------------------------------------- Terminate Unit -------------------------------------------------------------- //

if (isset($_POST["uterminate"])) {
    $comments = GetSafeValueString($_POST["comments"], "string");
    $deviceid = GetSafeValueString($_POST["deviceid"], "string");
    $simcardid = GetSafeValueString($_POST["simcardid"], "string");
    $did = GetSafeValueString($_POST["did"], "string");
    $oldunitno = $deviceid;

    $SQL = sprintf("UPDATE unit SET trans_statusid= 10, comments = '" . $comments . "' where uid=" . $deviceid);
    $db->executeQuery($SQL);

    $SQL = sprintf('UPDATE devices SET simcardid= 0 where deviceid=' . $did);
    $db->executeQuery($SQL);

    $SQL = sprintf("UPDATE simcard SET trans_statusid= 12, comments = '" . $comments . "' where id=" . $simcardid);
    $db->executeQuery($SQL);

    $customerno = GetSafeValueString($_POST["customerid"], "string");

    $SQL = sprintf('SELECT vehicleid FROM vehicle WHERE uid = ' . $oldunitno);
    $db->executeQuery($SQL);
    if ($db->get_rowCount() > 0) {
        while ($row = $db->get_nextRow()) {
            $vehicleid = $row["vehicleid"];
        }
    }

    $SQLunit = sprintf("INSERT INTO " . DB_PARENT . ".trans_history (
    `customerno` ,`unitid`,`teamid`, `type`, `trans_time`, `statusid`, `transaction`, `simcardno`, `invoiceno`, `expirydate`, `comments`, `vehicleid`)
    VALUES (
    $customerno, '%d', '%s', 0, '%s', 10, '%s','%s','%s','%s','%s','%s')", $deviceid, GetLoggedInUserId(), Sanitise::DateTime($today), "Terminated", "", "", "", $comments, $vehicleid);
    $db->executeQuery($SQLunit);

    $SQLsim = sprintf("INSERT INTO " . DB_PARENT . ".trans_history (
    `customerno` ,`simcard_id`,`teamid`, `type`, `trans_time`, `statusid`, `transaction`, `simcardno`, `invoiceno`, `expirydate`, `comments`, `vehicleid`)
    VALUES (
    $customerno, '%d', '%s', 1, '%s', 15, '%s','%s','%s','%s','%s','%s')", $simcardid, GetLoggedInUserId(), Sanitise::DateTime($today), "Applied for Disconnection", "", "", "", $comments, $vehicleid);
    $db->executeQuery($SQLsim);

    $SQL = sprintf('SELECT unitno FROM unit WHERE uid = ' . $deviceid);
    $db->executeQuery($SQL);
    if ($db->get_rowCount() > 0) {
        while ($row = $db->get_nextRow()) {
            $unitno = $row["unitno"];
        }
    }

    $SQL = sprintf('SELECT simcardno FROM simcard WHERE id = ' . $simcardid);
    $db->executeQuery($SQL);
    if ($db->get_rowCount() > 0) {
        while ($row = $db->get_nextRow()) {
            $simcardno = $row["simcardno"];
        }
    }

    $SQLunit = sprintf("INSERT INTO " . DB_PARENT . ".trans_history (
    `customerno` ,`unitid`,`teamid`, `type`, `trans_time`, `statusid`, `transaction`, `simcardno`, `invoiceno`, `expirydate`, `comments`, `vehicleid`)
    VALUES (
    $customerno, 0, '%s', 2, '%s', 0, '%s', '%s', '%s', '%s', '%s','%s')", GetLoggedInUserId(), Sanitise::DateTime($today), "Terminated Unit # : " . $unitno . " and Return Bad Sim # " . $simcardno, "", "", "", $comments, $vehicleid);
    $db->executeQuery($SQLunit);

    $SQL = sprintf('SELECT vehicleno,groupid FROM vehicle WHERE vehicleid = ' . $vehicleid);
    $db->executeQuery($SQL);
    if ($db->get_rowCount() > 0) {
        while ($row = $db->get_nextRow()) {
            $vehicleno = $row["vehicleno"];
            $groupid = $row["groupid"];
        }
    }
    // Populate Vehicles
    //    $SQL = sprintf('UPDATE vehicle SET customerno=1 where uid ='.$oldunitno);
    //    $db->executeQuery($SQL);
    ////////////////////////////vehiclewise alert////////////////////////////////
    $SQL = sprintf('UPDATE vehiclewise_alert SET customerno=1 where vehicleid=' . $vehicleid);
    $db->executeQuery($SQL);
    /////////////////////////////////////////////////////////////////////////////
    $SQL = sprintf('UPDATE driver SET customerno=1 where vehicleid=' . $vehicleid);
    $db->executeQuery($SQL);

    $SQL = sprintf('UPDATE eventalerts SET customerno=1 where vehicleid=' . $vehicleid);
    $db->executeQuery($SQL);

    $SQL = sprintf('UPDATE ignitionalert SET customerno=1 where vehicleid=' . $vehicleid);
    $db->executeQuery($SQL);

    $SQL = sprintf('UPDATE acalerts SET customerno=1 where vehicleid=' . $vehicleid);
    $db->executeQuery($SQL);

    $SQL = sprintf('UPDATE checkpointmanage SET customerno=1, isdeleted=1 where vehicleid=' . $vehicleid);
    $db->executeQuery($SQL);

    $SQL = sprintf('UPDATE fenceman SET customerno=1, isdeleted=1 where vehicleid=' . $vehicleid);
    $db->executeQuery($SQL);

    $SQL = sprintf('UPDATE groupman SET customerno=1, isdeleted=1 where vehicleid=' . $vehicleid);
    $db->executeQuery($SQL);

    $SQL = sprintf('UPDATE reportman SET customerno=1 where uid=' . $oldunitno);
    $db->executeQuery($SQL);

    $SQL = sprintf("SELECT * FROM unit WHERE trans_statusid <> '10' AND customerno=" . $customerno);
    $db->executeQuery($SQL);
    if (!$db->get_rowCount() > 0) {
        $SQL = sprintf("UPDATE " . DB_PARENT . ".user SET isdeleted=1 WHERE customerno=" . $customerno);
        $db->executeQuery($SQL);
    }
    ////////////Terminate send email to customer/////////////////////
    $SQL = sprintf("SELECT name FROM " . DB_PARENT . ".team WHERE teamid = " . $teamid . "");
    $db->executeQuery($SQL);
    while ($row = $db->get_nextRow()) {
        $elixir = $row['name'];
    }

    //sendmailterminate
    if ($sendmailterminate == 1) {
        $SQL = sprintf("select c.* from user c left outer join groupman p on p.groupid = " . $groupid . " left outer join groupman on c.userid <> groupman.userid where c.customerno = " . $customerno . " and c.email <> ''and c.isdeleted=0 and (c.groupid=" . $groupid . " or c.groupid ='0' ) and (c.role = 'Administrator' OR c.role = 'Master')group by c.userid");
        $db->executeQuery($SQL);
        if ($db->get_rowCount() > 0) {
            while ($row = $db->get_nextRow()) {
                $mail = new VOPush();
                $mail->username = $row['username'];
                $mail->realname = $row['realname'];
                $mail->email = $row['email'];
                $mail->vehicleno = $vehicleno;
                $mail->unit = $unitno;
                $mail->sim = $simcardno;
                $mail->elixir = $elixir;
                $mail->comments = $comments;
                $mail->subject = 'Terminate Unit Details';
                SendEmailTerminate($mail);
            }
        }
    }
    header("Location: modifycustomer.php?cid=$customerno");
}

$SQL = sprintf("SELECT devices.device_invoiceno, vehicle.vehicleno, unit.is_ac_opp, unit.acsensor,unit.tempsen1, unit.tempsen2, unit.fuelsensor, unit.is_genset_opp, unit.gensetsensor, unit.doorsensor, unit.is_door_opp, unit.tempsen1, unit.is_twowaycom, unit.is_portable, unit.tempsen2, unit.vehicleid, devices.deviceid, devices.installdate, devices.simcardid, unit.uid, unit.trans_statusid, unit.unitno, unit.acsensor, unit.is_ac_opp, unit.is_panic, unit.is_buzzer, unit.is_mobiliser, unit.type_value,unit.extra_digital, devices.expirydate, devices.invoiceno, devices.customerno, devices.po_no, devices.po_date
                from unit
                INNER JOIN devices ON unit.uid = devices.uid
                LEFT OUTER JOIN vehicle ON vehicle.vehicleid = unit.vehicleid
                WHERE unit.uid = '%d' LIMIT 1 ", $deviceid);
$db->executeQuery($SQL);
while ($row = $db->get_nextRow()) {
    $uid = $row["uid"];
    $customerno = $row["customerno"];
    $unitno = $row["unitno"];
    $transstatus = $row["trans_statusid"];
    $expirydate = $row["expirydate"];
    $invoiceno = $row["invoiceno"];
    $dev_invoiceno = $row["device_invoiceno"];
    $vehicleno = $row["vehicleno"];
    $simcardid = $row["simcardid"];
    $installdate = $row["installdate"];
    $vehicleid = $row['vehicleid'];
    $panic = $row['is_panic'];
    $buzzer = $row['is_buzzer'];
    $immobilizer = $row['is_mobiliser'];
    $twowaycom = $row['is_twowaycom'];
    $portable = $row['is_portable'];
    $type_value = $row['type_value'];
    $pno = $row['po_no'];
    $pdate = $row['po_date'];
    $fuelsensor1 = $row['fuelsensor'];
    $analog1 = $row['tempsen1'];
    $analog2 = $row['tempsen2'];
    $isGenset = $row['extra_digital'];
    $category_array = Array();

    $category = (int) $type_value;
    $binarycategory = sprintf("%08s", DecBin($category));
    for ($shifter = 1; $shifter <= 3000; $shifter = $shifter << 1) {
        $binaryshifter = sprintf("%08s", DecBin($shifter));
        if ($category & $shifter) {
            $category_array[] = $shifter;
        }
    }

    $analog = $row["tempsen1"];
    $analog2 = $row["tempsen2"];

    if (($row['is_ac_opp'] == '1') && ($row['acsensor'] == '1')) {
        $dgacopp = 1;
    } else {
        $dgacopp = 0;
    }

    if (($row['is_genset_opp'] == '1') && ($row['gensetsensor'] == '1')) {
        $dggensetopp = 1;
    } else {
        $dggensetopp = 0;
    }

    if (($row['is_door_opp'] == '1') && ($row['doorsensor'] == '1')) {
        $dgdooropp = 1;
    } else {
        $dgdooropp = 0;
    }

    $did = $row["deviceid"];
}

// ------------------------------------------------------------ Send Email ----------------------------------------------------- //

function SendEmail($mail) {
    $message .= '<html><body>';
    $message .= 'Dear ' . $mail->realname . ' ,<br>';
    $message .= '<p></p></br>';
    $message .= 'Greetings from Elixia Tech!<br/>';
    $message .= 'Please find the Unit Repair Details. <br/></br>';
    $message .= '<table style="border:1px solid #ccc;" cellspacing="0" cellpadding="0">
                                        <tr style="background-color:#ccc;height:25px;">
                                            <td colspan="5" id="formheader" style="text-align:center; border:1px solid #ccc; padding:5px;">Repair Details</td>
                                        </tr>
                                        <tr>
                                           <td style="text-align:center; border:1px solid #ccc; padding:5px;">Vehicle No</td>
                                           <td style="text-align:center; border:1px solid #ccc; padding:5px;">Unit No</td>
                                           <td style="text-align:center; border:1px solid #ccc; padding:5px;">Simcard No</td>
                                           <td style="text-align:center; border:1px solid #ccc; padding:5px;">Elixir</td>
                                           <td style="text-align:center; border:1px solid #ccc; padding:5px;">Comments</td>
                                        </tr>
                                        <tr>
                                           <td style="text-align:center; border:1px solid #ccc; padding:5px;">' . $mail->vehicleno . '</td>
                                           <td style="text-align:center; border:1px solid #ccc; padding:5px;">' . $mail->unitno . '</td>
                                           <td style="text-align:center; border:1px solid #ccc; padding:5px;">' . $mail->simcard . '</td>
                                           <td style="text-align:center; border:1px solid #ccc; padding:5px;">' . $mail->elixir . '</td>
                                           <td style="text-align:center; border:1px solid #ccc; padding:5px;">' . $mail->comments . '</td>
                                        </tr>
                                     </table></br></br></br>';
    $message .= '</body></html>';
    sendMail($mail->email, $mail->subject, $message);
    //echo $message;
}

function SendEmailReplace($mail) {
    // send email
    $arrTo = array($mail->email);
    $strCCMailIds = "";
    $strBCCMailIds = "";
    $subject = "Device replace details";
    $message = file_get_contents('../../modules/emailtemplates/replaceDevice.html');
    $message = str_replace("{{REALNAME}}", $mail->realname, $message);
    $message = str_replace("{{VEHICLENO}}", $mail->vehicleno, $message);
    $message = str_replace("{{OLDUNITNO}}", $mail->oldunitno, $message);
    $message = str_replace("{{NEWUNITNO}}", $mail->newunitno, $message);
    $message = str_replace("{{SIMCARD}}", $mail->simcard, $message);
    $message = str_replace("{{ELIXIR}}", $mail->elixir, $message);
    $message = str_replace("{{COMMENTS}}", $mail->comments, $message);
    $attachmentFilePath = "";
    $attachmentFileName = "";
    $isTemplatedMessage = 1;
    $isSmsSent = sendMailUtil($arrTo, $strCCMailIds, $strBCCMailIds, $subject, $message, $attachmentFilePath, $attachmentFileName, $isTemplatedMessage);
}

function SendEmailReplaceSimcard($mail) {
    // send email
    $arrTo = array($mail->email);
    $strCCMailIds = "";
    $strBCCMailIds = "";
    $subject = "Simcard replace details";
    $message = file_get_contents('../../modules/emailtemplates/replaceSim.html');
    $message = str_replace("{{REALNAME}}", $mail->realname, $message);
    $message = str_replace("{{VEHICLENO}}", $mail->vehicleno, $message);
    $message = str_replace("{{OLDSIMCARD}}", $mail->oldsimcardno, $message);
    $message = str_replace("{{NEWSIMCARD}}", $mail->newsimcardno, $message);
    $message = str_replace("{{ELIXIR}}", $mail->elixir, $message);
    $message = str_replace("{{COMMENTS}}", $mail->comments, $message);
    $attachmentFilePath = "";
    $attachmentFileName = "";
    $isTemplatedMessage = 1;
    $isSmsSent = sendMailUtil($arrTo, $strCCMailIds, $strBCCMailIds, $subject, $message, $attachmentFilePath, $attachmentFileName, $isTemplatedMessage);
}

function SendEmailReplaceBoth($mail) {
    // send email
    $arrTo = array($mail->email);
    $strCCMailIds = "";
    $strBCCMailIds = "";
    $subject = "Device and simcard replace details";
    $message = file_get_contents('../../modules/emailtemplates/replaceUnitSim.html');
    $message = str_replace("{{REALNAME}}", $mail->realname, $message);
    $message = str_replace("{{VEHICLENO}}", $mail->vehicleno, $message);
    $message = str_replace("{{OLDUNIT}}", $mail->oldunitno, $message);
    $message = str_replace("{{NEWUNIT}}", $mail->newunitno, $message);
    $message = str_replace("{{OLDSIM}}", $mail->oldsimno, $message);
    $message = str_replace("{{NEWSIM}}", $mail->newsimno, $message);
    $message = str_replace("{{ELIXIR}}", $mail->elixir, $message);
    $message = str_replace("{{COMMENTS}}", $mail->comments, $message);
    $attachmentFilePath = "";
    $attachmentFileName = "";
    $isTemplatedMessage = 1;
    $isSmsSent = sendMailUtil($arrTo, $strCCMailIds, $strBCCMailIds, $subject, $message, $attachmentFilePath, $attachmentFileName, $isTemplatedMessage);
}

function SendEmailRemoveBad($mail) {
    // send email
    $arrTo = array($mail->email);
    $strCCMailIds = "";
    $strBCCMailIds = "";
    $subject = "Bad Device remove Details";
    $message = file_get_contents('../../modules/emailtemplates/removeUnitSim.html');
    $message = str_replace("{{REALNAME}}", $mail->realname, $message);
    $message = str_replace("{{VEHICLENO}}", $mail->vehicleno, $message);
    $message = str_replace("{{UNITNO}}", $mail->unitno, $message);
    $message = str_replace("{{SIMNO}}", $mail->simno, $message);
    $message = str_replace("{{ELIXIR}}", $mail->elixir, $message);
    $message = str_replace("{{COMMENTS}}", $mail->comments, $message);
    $attachmentFilePath = "";
    $attachmentFileName = "";
    $isTemplatedMessage = 1;
    sendMailUtil($arrTo, $strCCMailIds, $strBCCMailIds, $subject, $message, $attachmentFilePath, $attachmentFileName, $isTemplatedMessage);
}

function SendEmailRemove($mail) {
    $message .= '<html><body>';
    $message .= 'Dear ' . $mail->realname . ' ,<br>';
    $message .= '<p></p></br>';
    $message .= 'Greetings from Elixia Tech!<br/>';
    $message .= 'Please find the Bad Unit Remove Details. <br/></br>';
    $message .= '<table style="border:1px solid #ccc;" cellspacing="0" cellpadding="0">
                                        <tr style="background-color:#ccc;height:25px;">
                                            <td colspan="6" id="formheader" style="text-align:center; border:1px solid #ccc; padding:5px;">Remove Bad Unit Details</td>
                                        </tr>
                                        <tr>
                                           <td style="text-align:center; border:1px solid #ccc; padding:5px;">Vehicle No</td>
                                           <td style="text-align:center; border:1px solid #ccc; padding:5px;"> Unit No</td>
                                           <td style="text-align:center; border:1px solid #ccc; padding:5px;">Simcard No</td>


                                           <td style="text-align:center; border:1px solid #ccc; padding:5px;">Elixir</td>
                                           <td style="text-align:center; border:1px solid #ccc; padding:5px;">Comments</td>
                                        </tr>
                                        <tr>
                                           <td style="text-align:center; border:1px solid #ccc; padding:5px;">' . $mail->vehicleno . '</td>
                                           <td style="text-align:center; border:1px solid #ccc; padding:5px;">' . $mail->unit . '</td>
                                           <td style="text-align:center; border:1px solid #ccc; padding:5px;">' . $mail->sim . '</td>

                                           <td style="text-align:center; border:1px solid #ccc; padding:5px;">' . $mail->elixir . '</td>
                                           <td style="text-align:center; border:1px solid #ccc; padding:5px;">' . $mail->comments . '</td>
                                        </tr>
                                     </table></br></br></br>';
    $message .= '</body></html>';
    sendMail($mail->email, $mail->subject, $message);
    //echo $message;
}

////////////////////////////////////////Suspect Unit mail send///////////////////////////////////////////////////////////
function SendEmailSuspect($mail) {
    // send email

    $arrTo = array($mail->email);
    $strCCMailIds = "";
    $strBCCMailIds = "";
    $subject = $mail->subject;
    $message = file_get_contents('../emailtemplates/suspectDevice.html');
    $message = str_replace("{{REALNAME}}", $mail->realname, $message);
    $message = str_replace("{{VEHICLENO}}", $mail->vehicleno, $message);
    $message = str_replace("{{UNITNO}}", $mail->unitno, $message);
    $message = str_replace("{{SIMCARDNO}}", $mail->simcardno, $message);
    $message = str_replace("{{ELIXIR}}", $mail->elixir, $message);
    $message = str_replace("{{COMMENTS}}", $mail->comment, $message);
    $attachmentFilePath = "";
    $attachmentFileName = "";
    $isTemplatedMessage = 1;
    $isSmsSent = sendMailUtil($arrTo, $strCCMailIds, $strBCCMailIds, $subject, $message, $attachmentFilePath, $attachmentFileName, $isTemplatedMessage);
    return $isSmsSent;
}

////////////////////////////////////////Terminate unit mail send/////////////////////////////////////////////////////////
function SendEmailTerminate($mail) {
    $message .= '<html><body>';
    $message .= 'Dear ' . $mail->realname . ' ,<br>';
    $message .= '<p></p></br>';
    $message .= 'Greetings from Elixia Tech!<br/>';
    $message .= 'Please find the Bad Unit Remove Details. <br/></br>';
    $message .= '<table style="border:1px solid #ccc;" cellspacing="0" cellpadding="0">
                                        <tr style="background-color:#ccc;height:25px;">
                                            <td colspan="6" id="formheader" style="text-align:center; border:1px solid #ccc; padding:5px;">Terminate Unit Details</td>
                                        </tr>
                                        <tr>
                                           <td style="text-align:center; border:1px solid #ccc; padding:5px;">Vehicle No</td>
                                           <td style="text-align:center; border:1px solid #ccc; padding:5px;"> Unit No</td>
                                           <td style="text-align:center; border:1px solid #ccc; padding:5px;">Simcard No</td>


                                           <td style="text-align:center; border:1px solid #ccc; padding:5px;">Elixir</td>
                                           <td style="text-align:center; border:1px solid #ccc; padding:5px;">Comments</td>
                                        </tr>
                                        <tr>
                                           <td style="text-align:center; border:1px solid #ccc; padding:5px;">' . $mail->vehicleno . '</td>
                                           <td style="text-align:center; border:1px solid #ccc; padding:5px;">' . $mail->unit . '</td>
                                           <td style="text-align:center; border:1px solid #ccc; padding:5px;">' . $mail->sim . '</td>

                                           <td style="text-align:center; border:1px solid #ccc; padding:5px;">' . $mail->elixir . '</td>
                                           <td style="text-align:center; border:1px solid #ccc; padding:5px;">' . $mail->comments . '</td>
                                        </tr>
                                     </table></br></br></br>';
    $message .= '</body></html>';
    sendMail($mail->email, $mail->subject, $message);
    //echo $message;
}

////////////////////////////////////////////////////////////////////////////////////////////////

function sendMail($to, $subject, $content) {
    $headers = "From: noreply@elixiatech.com\r\n";
    $headers .= "MIME-Version: 1.0\r\n";
    $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
    include_once "../../lib/system/class.phpmailer.php";
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
        echo "Error sending: " . $mail->ErrorInfo;
        $content = '';
    } else {
        echo "Mail sent";
        $content = '';
    }
}

// ----------------------------------------------------------- Page Header  ---------------------------------------------------- //
include "header.php";

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

$QUERY = sprintf('SELECT sms_lock FROM vehicle WHERE vehicleid=%d', Sanitise::Long($vehicleid));
$db->executeQuery($QUERY);
if ($db->get_rowCount() > 0) {
    while ($data = $db->get_nextRow()) {
        $smslock = $data['sms_lock'];
    }
}
$QUERY = sprintf('SELECT tel_lock FROM vehicle WHERE vehicleid=%d', Sanitise::Long($vehicleid));
$db->executeQuery($QUERY);
if ($db->get_rowCount() > 0) {
    while ($data = $db->get_nextRow()) {
        $tel_lock = $data['tel_lock'];
    }
}

if (!IsData()) {
    ?>

    <!-----------------------------------------------------------  Update Unit -------------------------------------------------------------->

    <div class="panel">
        <div class="paneltitle" align="center">Update Basic Information for <?php echo ($unitno); ?></div>
        <div class="panelcontents">
            <form method="post" name="myform" id="myform" onsubmit="return ValidateForm();"   enctype="multipart/form-data">
                <input type="hidden" name="customerid" value="<?php echo ($customerno); ?>"/>
                <input type="hidden" name="deviceid" value="<?php echo ($uid); ?>"/>
                <input type="hidden" name="simcardid" value="<?php echo ($simcardid); ?>"/>
                <table width="55%">

                    <?php
$expiry = date('d-m-Y', strtotime($expirydate));
    ?>
                    <tr>
                        <td>PO No. </td>
                        <td> <input  type="text" name="cpono" id="cpono" value="<?php echo $pno; ?>"/>
                        </td>
                    </tr>
                    <?php
if ($pdate == "0000-00-00") {
        $pdate = "";
    } else {
        $pdate = date('d-m-Y', strtotime($pdate));
    }
    ?>
                    <tr>
                        <td>PO Date </td>
                        <td> <input type="text" name="cpodate" id="cpodate"  value="<?php echo $pdate; ?>"/><button id="trigger8">...</button>
                        </td>
                    </tr>

                    <tr>
                        <td>Expiry Date </td>
                        <td> <input name="ucexpiry" id="ucexpiry" type="text" value="<?php echo $expiry; ?>"/><button id="trigger4">...</button>
                        </td>
                    </tr>
                    <?php
$install = date('d-m-Y', strtotime($installdate));
    ?>
                    <tr>
                        <td>Install Date </td>
                        <td> <input name="cinstall" id="cinstall" type="text" value="<?php echo $install; ?>"/><button id="trigger3">...</button>
                        </td>
                    </tr>
                    <tr>
                        <td>Rec. Invoice No. </td>
                        <td> <input name="cinvoiceno" id="cinvoiceno" type="text" value="<?php echo $invoiceno; ?>"/>
                        </td>
                    </tr>
                    <tr>
                        <td>Dev. Invoice No. </td>
                        <td> <input name="dinvoiceno" id="dinvoiceno" type="text" value="<?php echo $dev_invoiceno; ?>"/>
                        </td>
                    </tr>
                    <tr>
                        <td>Vehicle No. <span style='color:red;'>*</span></td>
                        <td> <input name="cvehicleno" id="cvehicleno" type="text" value="<?php echo $vehicleno; ?>"/>
                        </td>
                    </tr>
                    <?php if ($customerno == '1') {?>
                        <input type="hidden" name="utype" id="utype" value="-1" />
                        <?php
} else {
        ?>
                        <tr>
                            <td>Type</td>
                            <td><select name="utype" id="utype">
                                    <?php
if ($transstatus == '5') {
            ?>
                                        <option value="5" selected>Installed</option>
                                        <?php
} else {
            ?>
                                        <option value="5">Installed</option>
                                        <?php
}
        if ($transstatus == '22') {
            ?>
                                        <option value="22" selected>Not Installed</option>
                                        <?php
} else {
            ?>
                                        <option value="22">Not Installed</option>
                                        <?php
}
        if ($transstatus == '23') {
            ?>
                                        <option value="23" selected>Demo</option>
                                        <?php
} else {
            ?>
                                        <option value="23">Demo</option>
                                        <?php
}
        ?>
                                </select>
                            </td>
                        </tr>
    <?php }?>

                    <tr>

                                                                                                                                                                                <!--         <tr>
                                                                                                                                                                                     <td>Type</td>
                                                                                                                                                                                    <td> <input name="device" id="device" type="radio" value="1" <?php
if (in_array(0, $category_array) || empty($category_array)) {
        echo "checked=''";
    }
    ?> onclick="device_type();"/> &nbsp;Basic &nbsp;&nbsp;&nbsp;&nbsp;
                                                                                                                                                                                        <input name="device" id="device" type="radio" value="2" <?php
if (!in_array(0, $category_array) && !empty($category_array)) {
        echo "checked=''";
    }
    ?> onclick="device_type();" /> &nbsp; Advanced &nbsp;&nbsp;&nbsp;&nbsp;
                                                                                                                                                                                    </td>
                                                                                                                                                                                </tr>-->


                                                                                                                                                                                <!--         <tr class="adv">
                                                                                                                                                                                   <td>Sensor</td>
                                                                                                                                                                                    <td>
                                                                                                                                                                                        <input name="acsensor" id="acesensor" type="checkbox" value="1" <?php
if (in_array(1, $category_array)) {
        echo "checked";
    }
    ?> />  &nbsp;  Ac Sensor   &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                                                                                                                                                        <input name="acdigitalopp" id="acdigitalopp" type="checkbox" value='1' <?php
if ($dgacopp == '1') {
        echo "checked";
    }
    ?> />  &nbsp;  Is Opposite? <br/>


                                                                                                                                                                                        <input name="gensetsensor" id="gensetsensor" type="checkbox" value="2" <?php
if (in_array(2, $category_array)) {
        echo "checked";
    }
    ?> />  &nbsp;  Genset Sensor   &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                                                                                                                                                        <input name="gensetdigitalopp" id="gensetdigitalopp" type="checkbox" value='1' <?php
if ($dggensetopp == '1') {
        echo "checked";
    }
    ?>/>  &nbsp;  Is Genset Opposite?<br/>


                                                                                                                                                                                        <input name="doorsensor" id="doorsensor" type="checkbox" value="3" <?php
if (in_array(4, $category_array)) {
        echo "checked";
    }
    ?>/>  &nbsp;  Door Sensor   &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                                                                                                                                                        <input name="doordigitalopp" id="doordigitalopp" type="checkbox" value='1'<?php
if ($dgdooropp == '1') {
        echo "checked";
    }
    ?>/>  &nbsp;  Is Door Opposite? <br>
                                                                                                                                                                                        <input type="checkbox" name="fuelsensor" id="fuelsensor" value="4" <?php
if (in_array(1024, $category_array)) {
        echo "checked";
    }
    ?> onclick="fuelcheckbox()">&nbsp; Fuel Sensor
                                                                                                                                                                                    </td>

                                                                                                                                                                                </tr>-->
                                                                                                                                                                                <!--        <tr id= "fuelanalogtd"  <?php
if (in_array(1024, $category_array)) {
        echo "style='display:block; width:55px;'";
    } else {
        echo "style='display:none;'";
    }
    ?>>

                                                                                                                                                                                    <td style="width:50px;"><label>Fuel Analog</label></td>
                                                                                                                                                                                    <td style="width:50px;">
                                                                                                                                                                                        <select name="fuelanalog" id="fuelanalog">
                                                                                                                                                                                        <option value="0"<?php
if ($fuelsensor1 == 0) {
        echo "selected=''";
    }
    ?>>Select Output</option>
                                                                                                                                                                                        <option value="1" <?php
if ($fuelsensor1 == 1) {
        echo "selected=''";
    }
    ?> >Analog 1</option>
                                                                                                                                                                                        <option value="2" <?php
if ($fuelsensor1 == 2) {
        echo "selected=''";
    }
    ?>>Analog 2</option>
                                                                                                                                                                                        <option value="3"<?php
if ($fuelsensor1 == 3) {
        echo "selected=''";
    }
    ?>>Analog 3</option>
                                                                                                                                                                                        <option value="4"<?php
if ($fuelsensor1 == 4) {
        echo "selected=''";
    }
    ?>>Analog 4</option>
                                                                                                                                                                                    </select>
                                                                                                                                                                                    </td>
                                                                                                                                                                                </tr>-->
                                                                                                                                                                                <!--        <tr class="adv">
                                                                                                                                                                                    <td>Temperature </td>
                                                                                                                                                                                    <td>
                                                                                                                                                                                        <input name="tempsen" id="tempsen" style="float:left;" type="radio" value="0" <?php
if (empty($category_array)) {
        echo "checked";
    }
    ?> onclick="temp_show();"/> <span  style="width:32px; float:left;">None</span>
                                                                                                                                                                                        <input name="tempsen" id="tempsen1" style="float:left;" type="radio" value="1" <?php
if (in_array(8, $category_array)) {
        echo "checked";
    }
    ?> onclick="temp_show();"/><span id="tempsen4" style="width:105px; float:left;">Single Temperature </span>  &nbsp;&nbsp;&nbsp;&nbsp;
                                                                                                                                                                                        <input name="tempsen" id="tempsen2" style="float:left;" type="radio" value="2" <?php
if (in_array(16, $category_array)) {
        echo "checked";
    }
    ?> onclick="temp_show();"/> <span id="tempsen3" style="width:255px; float:left;<?php echo $fuel_show; ?>">Double Temperature </span> &nbsp;&nbsp;&nbsp;&nbsp;
                                                                                                                                                                                    </td>
                                                                                                                                                                                </tr>-->


                                                                                                                                                                                <!--        <tr class="advnone">
                                                                                                                                                                                    <td id="adv1" <?php
if ($analog1 != 0) {
        echo 'style=display:block;';
    } else {
        echo 'style=display:none;';
    }
    ?>>Analog Sensor 1 </td>
                                                                                                                                                                                <input type="hidden" name="oldanalog1" value="<?php echo ($analog1); ?>">
                                                                                                                                                                                <td><select name="canalog" id="canalog" <?php
if ($analog1 != 0) {
        echo 'style=display:block;';
    } else {
        echo 'style=display:none;';
    }
    ?>>
                                                                                                                                                                                        <option value="0" <?php
if ($analog1 == 0) {
        echo "selected=''";
    }
    ?>>Select Analog</option>
                                                                                                                                                                                        <option value="1" <?php
if ($analog1 == 1) {
        echo "selected=''";
    }
    ?>>Analog 1</option>
                                                                                                                                                                                        <option value="2" <?php
if ($analog1 == 2) {
        echo "selected=''";
    }
    ?>>Analog 2</option>
                                                                                                                                                                                        <option value="3" <?php
if ($analog1 == 3) {
        echo "selected=''";
    }
    ?>>Analog 3</option>
                                                                                                                                                                                        <option value="4" <?php
if ($analog1 == 4) {
        echo "selected=''";
    }
    ?>>Analog 4</option>
                                                                                                                                                                                    </select>
                                                                                                                                                                                </td>
                                                                                                                                                                                </tr>-->

                                                                                                                                                                                <!--        <tr id="double_temp" style="<?php
if (!in_array(16, $category_array)) {
        echo "display:none;";
    }
    ?>">
                                                                                                                                                                                        <td>Analog Sensor 2</td>
                                                                                                                                                                                        <input type="hidden" name="oldanalog2" value="<?php echo ($analog2); ?>">
                                                                                                                                                                                        <td><select name="canalog2" id="canalog2" onchange="doubleTemp()">
                                                                                                                                                                                                <option value="0" <?php
if ($analog2 == 0) {
        echo "selected=''";
    }
    ?>>Select Analog</option>
                                                                                                                                                                                                <option value="1" <?php
if ($analog2 == 1) {
        echo "selected=''";
    }
    ?>>Analog 1</option>
                                                                                                                                                                                                <option value="2" <?php
if ($analog2 == 2) {
        echo "selected=''";
    }
    ?>>Analog 2</option>
                                                                                                                                                                                                <option value="3" <?php
if ($analog2 == 3) {
        echo "selected=''";
    }
    ?>>Analog 3</option>
                                                                                                                                                                                                <option value="4" <?php
if ($analog2 == 4) {
        echo "selected=''";
    }
    ?>>Analog 4</option>
                                                                                                                                                                                            </select>
                                                                                                                                                                                        </td>
                                                                                                                                                                                        </tr>-->

                                                                                                                                                                                <!--        <tr class="adv">
                                                                                                                                                                                    <td></td>
                                                                                                                                                                                    <td>
                        <?php if (in_array(32, $category_array)) {
        ?>
                                                                                                                                                                                                                                                                                                                                                                        <input name="panic" id="panic" type="checkbox" value="1" <?php
if ($panic == 1) {
            echo "checked=''";
        }
        ?>/> Panic  &nbsp;&nbsp;&nbsp;&nbsp;
                            <?php
} else {
        ?>
                                                                                                                                                                                                                                                                                                                                                                        <input name="panic" id="panic" type="checkbox" value="1" /> Panic  &nbsp;&nbsp;&nbsp;&nbsp;
                        <?php }
    ?>
                        <?php if (in_array(64, $category_array)) {
        ?>
                                                                                                                                                                                                                                                                                                                                                                        <input name="buzzer" id="buzzer" type="checkbox" value="1" <?php
if ($buzzer == 1) {
            echo "checked=''";
        }
        ?>  /> Buzzer  &nbsp;&nbsp;&nbsp;&nbsp;
                            <?php
} else {
        ?>
                                                                                                                                                                                                                                                                                                                                                                       <input name="buzzer" id="buzzer" type="checkbox" value="1"  /> Buzzer  &nbsp;&nbsp;&nbsp;&nbsp
                        <?php }
    ?>
                        <?php if (in_array(128, $category_array)) {
        ?>
                                                                                                                                                                                                                                                                                                                                                                        <input name="immobilizer" id="immobilizer" type="checkbox" value="1" <?php
if ($immobilizer == 1) {
            echo "checked=''";
        }
        ?> /> Immobilizer  &nbsp;&nbsp;&nbsp;&nbsp;
                            <?php
} else {
        ?>
                                                                                                                                                                                                                                                                                                                                                                        <input name="immobilizer" id="immobilizer" type="checkbox" value="1" /> Immobilizer  &nbsp;&nbsp;&nbsp;&nbsp;
                            <?php
}
    ?>
                        <?php if (in_array(256, $category_array)) {
        ?>
                                                                                                                                                                                                                                                                                                                                                                        <input name="twowaycom" id="twowaycom" type="checkbox" value="1" <?php
if ($twowaycom == '1') {
            echo "checked=''";
        }
        ?> /> Two way Communication  &nbsp;&nbsp;&nbsp;&nbsp;
                            <?php
} else {
        ?>
                                                                                                                                                                                                                                                                                                                                                                        <input name="twowaycom" id="twowaycom" type="checkbox" value="1" /> Two way Communication  &nbsp;&nbsp;&nbsp;&nbsp;
                            <?php
}
    ?>
                        <?php if (in_array(512, $category_array)) {
        ?>
                                                                                                                                                                                                                                                                                                                                                                        <input name="portable" id="portable" type="checkbox" value="1" <?php
if ($portable == '1') {
            echo "checked=''";
        }
        ?> /> Portable  &nbsp;&nbsp;&nbsp;&nbsp;
                            <?php
} else {
        ?>
                                                                                                                                                                                                                                                                                                                                                                        <input name="portable" id="portable" type="checkbox" value="1" /> Portable  &nbsp;&nbsp;&nbsp;&nbsp;
                            <?php
}
    ?>
                                                                                                                                                                                    </td>
                                                                                                                                                                                </tr>-->


                    <tr>
                        <td>Comments</td><td><input name="comments" id="comments" type="text"></td>
                    </tr>

                </table>
                <input type="submit" name="updateunit" value="Update" />
            </form>
        </div>
    </div>

    <?php
}

if (IsHead()) {
    ?>


    <!-----------------------------------------------------------  Renewal -------------------------------------------------------------->

    <div class="panel">
        <div class="paneltitle" align="center">Tasks for <?php echo ($unitno); ?></div>
        <div class="panelcontents">
            <form method="post" action="pushcommand.php" name='taskform' id='taskform' onsubmit="return ValidateForm1(); return false;"   enctype="multipart/form-data">
                <h3>Renewal</h3>
                <input type="hidden" name="customerid" value="<?php echo ($customerno); ?>"/>
                <input type="hidden" name="deviceid" value="<?php echo ($uid); ?>"/>
                <input type="hidden" name="simcardid" value="<?php echo ($simcardid); ?>"/>
                <table width="40%">

                    <?php
$expiry = date('d-m-Y', strtotime($expirydate));
    ?>
                    <tr>
                        <td>Expiry Date </td>
                        <td> <input name="cexpiry" id="cexpiry" type="text" value="<?php echo $expiry; ?>"/><button id="trigger">...</button>
                        </td>
                    </tr>
                    <tr>
                        <td>Invoice No. </td>
                        <td> <input name="cinvoiceno" id="cinvoiceno" type="text" value="<?php echo $invoiceno; ?>"/>
                        </td>
                    </tr>

                    <tr>
                        <td>Comments</td><td><input name = "comments" id="comments" type="text"></td>
                    </tr>
                </table>
                <input type="submit" name="renewal" value="Renewal" />
            </form>
        </div>
    </div>

    <script>
        Calendar.setup(
                {
                    inputField: "cexpiry", // ID of the input field
                    ifFormat: "%d-%m-%Y", // the date format
                    button: "trigger" // ID of the button
                });

    </script>
    <?php
}
if (!IsData()) {
    ?>

    <div class="panel">
        <div class="paneltitle" align="center">Lock SMS Alerts</div>
        <div class="panelcontents">
            <!-----------------------------------------------------------  SMS Lock -------------------------------------------------------------->
            <form method="post" action="pushcommand.php"  enctype="multipart/form-data">
                <h3>Lock SMS Alerts</h3>
                <input type="hidden" name="customerid" value="<?php echo ($customerno); ?>"/>
                <input type="hidden" name="deviceid" value="<?php echo ($uid); ?>"/>
                <input type="hidden" name="vehicleid" value="<?php echo ($vehicleid); ?>"/>

                <table width="40%">
                    <tr>
                        <td> Lock It?<input name="chk_val" id="chk_val" type="checkbox" <?php if ($smslock) {?> checked <?php }?> /> </td>
                    </tr>

                </table>
                <input type="submit" name="switchlock" value="Switch Lock" />
            </form>
        </div>
    </div>

    <!-- Telephonic Alerts -->
    <div class="panel">
        <div class="paneltitle" align="center">Lock Telephonic Alerts</div>
        <div class="panelcontents">
            <!-----------------------------------------------------------  Telephonic ALert Lock -------------------------------------------------------------->
            <form method="post" action="pushcommand.php"  enctype="multipart/form-data">
                <h3>Lock Telephonic Alerts</h3>
                <input type="hidden" name="customerid" value="<?php echo ($customerno); ?>"/>
                <input type="hidden" name="deviceid" value="<?php echo ($uid); ?>"/>
                <input type="hidden" name="vehicleid" value="<?php echo ($vehicleid); ?>"/>

                <table width="40%">
                    <tr>
                        <td> Lock It?<input name="chk_telephonic_val" id="chk_telephonic_val" type="checkbox" <?php if ($tel_lock) {?> checked <?php }?> /> </td>
                    </tr>

                </table>
                <input type="submit" name="switchTelephoniclock" value="Switch Telephonic Lock" />
            </form>
        </div>
    </div>


    <div class="panel">
        <div class="paneltitle" align="center">Suspect / Terminate</div>
        <div class="panelcontents">
            <?php
if ($transstatus == 5) {
        ?>
                <!-----------------------------------------------------------  Suspect Unit -------------------------------------------------------------->
                <form method="post" name="myform" id="myform" action="pushcommand.php" enctype="multipart/form-data">
                    <?php
$apt_date = date("Y-m-d", strtotime("+1 day"));
        $apt_date_display = date("d-m-Y", strtotime("+1 day"));

        $SQL = sprintf("SELECT count(*) as totalcount FROM bucket b
                WHERE b.apt_date = '%s' AND b.status IN (0,4)", $apt_date);
        $db->executeQuery($SQL);

        $totalcount_bucket = 0;
        if ($db->get_rowCount() > 0) {
            while ($row = $db->get_nextRow()) {
                $totalcount_bucket = $row["totalcount"];
            }
        }
        ?>

                    <h3>Suspect Unit  <span style="float: right;">Total Items in Bucket for <?php echo ($apt_date_display); ?>: <?php echo ($totalcount_bucket); ?></span></h3>
                    <input type="hidden" name="customerid" value="<?php echo ($customerno); ?>"/>
                    <input type="hidden" name="deviceid" value="<?php echo ($uid); ?>"/>
                    <input type="hidden" name="simcardid" value="<?php echo ($simcardid); ?>"/>
                    <table width="40%">

                        <tr>
                            <?php
$apt_date = date('d-m-Y', strtotime("+ 1 day"));
        ?>
                            <td>Appointment Date </td>
                            <td> <input name="sapt_date" id="sapt_date" type="text" value="<?php echo $apt_date; ?>"/><button id="trigger10">...</button>
                            </td>
                        </tr>

                        <tr>
                            <td>Priority</td>
                            <td><select name="spriority" id="spriority">
                                    <option value="1" selected>High</option>
                                    <option value="2" >Medium</option>
                                    <option value="3" >Low</option>
                                </select>
                            </td>
                        </tr>

                        <tr>
                            <td>Location</td>
                            <td><input name = "slocation" id="slocation" type="text"></td>
                        </tr>

                        <tr>
                            <td>Time Slot </td>
                            <td><select name="stimeslot" id="stimeslot">
                                    <option value="0">Select a Timeslot</option>
                                    <?php
foreach ($timeslot_array as $thistime) {
            ?>
                                        <option value="<?php echo ($thistime->tsid); ?>"><?php echo ($thistime->timeslot); ?></option>
                                        <?php
}
        ?>
                                </select>
                            </td>
                        </tr>

                        <tr>
                            <td>Purpose</td>
                            <td><select name="spurpose" id="spurpose">
                                    <option value="2" selected>Repair</option>
                                    <option value="4" >Replacement</option>
                                    <option value="5" >Reinstall</option>
                                    <option value="3" >Removal</option>
                                </select>
                            </td>
                        </tr>

                        <tr>
                            <td>Details</td>
                            <td><input name = "sdetails" id="sdetails" type="text"></td>
                        </tr>

                        <tr>
                            <?php
$cp_array = Array();
        $SQL = sprintf("SELECT cpdetailid, person_name FROM " . DB_PARENT . ".contactperson_details WHERE customerno = %d AND typeid = 3", $customerno);
        $db->executeQuery($SQL);
        if ($db->get_rowCount() > 0) {
            while ($row = $db->get_nextRow()) {
                $cp = new testing();
                $cp->cpid = $row["cpdetailid"];
                $cp->name = $row["person_name"];
                $cp_array[] = $cp;
            }
        }
        ?>
                            <td>Co-ordinator </td>
                            <td><select name="scoordinator" id="scoordinator">
                                    <option value="0">Select a Co-ordinator</option>
                                    <?php
foreach ($cp_array as $thiscp) {
            ?>
                                        <option value="<?php echo ($thiscp->cpid); ?>"><?php echo ($thiscp->name); ?></option>
                                        <?php
}
        ?>
                                </select>
                                <br/> OR <br/>Co-ordinator Name<input name = "coname" id="coname" type="text"><br/> Co-ordinator Phone<input name = "cophone" id="cophone" type="text">
                            </td>


                        </tr>
                        <tr>
                            <td>Send Mail</td>
                            <td><input id="sendmailsuspect" type="checkbox" value="1" name="sendmailsuspect"></td>
                        </tr>

                        <tr>
                            <td>Comments</td>
                            <td><input name = "scomments" id="scomments" type="text"></td>
                        </tr>

                    </table>
                    <div><input type="submit" id="ususpect" name="ususpect" value="Suspect"/></div>
                </form>
                <?php
}
    /*            if ($transstatus == 6) {
?>
<!-----------------------------------------------------------  Repair Unit -------------------------------------------------------------->
<form method="post" name="myformrepair" id="myformrepair" action="pushcommand.php" onsubmit="return Validate_RepairForm(); return false;" enctype="multipart/form-data">
<h3>Repair Unit</h3>
<input type="hidden" name="customerid" value="<?php echo( $customerno ); ?>"/>
<input type="hidden" name="deviceid" value="<?php echo( $uid ); ?>"/>
<input type="hidden" name="simcardid" value="<?php echo( $simcardid ); ?>"/>
Elixir <span style='color:red;'>*</span>: <select name="uteamid_repair" id="uteamid_repair">
<option value="0">Select an Elixir</option>
<?php
foreach ($team_allot_array as $thisteam) {
?>
<option value="<?php echo($thisteam->teamid); ?>"><?php echo($thisteam->name); ?></option>
<?php
}
?>
</select>
<font size="2">Comments</font> <input name = "comments" id="comments" type="text">
<font size="2">Send Email</font><input type="checkbox" name = "sendmailrepair" id="sendmailr" value="1">

<div><input type="submit" id="uresolve" name="uresolve" value="Repair"/></div>
</form>
<hr/>
<?php
}
 *
 */
}
if (IsHead()) {
    if ($transstatus == 10) {
        ?>

                <!-----------------------------------------------------------  Reactivate Unit -------------------------------------------------------------->
                <form method="post" name="myform" id="myform" action="pushcommand.php" enctype="multipart/form-data">
                    <h3>Reactivate Unit</h3>
                    <input type="hidden" name="customerid" value="<?php echo ($customerno); ?>"/>
                    <input type="hidden" name="deviceid" value="<?php echo ($uid); ?>"/>
                    <input type="hidden" name="did" value="<?php echo ($did); ?>"/>
                    <table width="40%">
                        <tr>
                            <td>Sim Card No. </td>
                            <td><select name="simcardid">
                                    <?php
foreach ($simcards as $thiscard) {
            ?>
                                        <option value="<?php echo ($thiscard->id); ?>"><?php echo ($thiscard->simcardno); ?></option>
                                        <?php
}
        ?>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td>Send Mail</td>
                            <td><input id="sendmailsuspect" type="checkbox" value="1" name="sendmailsuspect"></td>
                        </tr>

                        <tr>
                            <td>Comments</td><td><input name = "comments" id="comments" type="text"></td>
                        </tr>

                    </table>

                    <input type="submit" id="ureactivate" name="ureactivate" value="Reactivate"/>
                </form>
                <script>
                    Calendar.setup(
                            {
                                inputField: "cexpirydate", // ID of the input field
                                ifFormat: "%d-%m-%Y", // the date format
                                button: "trigger1" // ID of the button
                            });

                </script>

                <?php
} else {
        ?>
                <!-----------------------------------------------------------  Terminate Unit -------------------------------------------------------------->
                <form method="post" name="myform" id="myform" action="pushcommand.php" enctype="multipart/form-data">
                    <h3>Terminate Unit</h3>
                    <input type="hidden" name="customerid" value="<?php echo ($customerno); ?>"/>
                    <input type="hidden" name="deviceid" value="<?php echo ($uid); ?>"/>
                    <input type="hidden" name="simcardid" value="<?php echo ($simcardid); ?>"/>
                    <input type="hidden" name="did" value="<?php echo ($did); ?>"/>
                    <font size="2">Comments</font> <input name = "comments" id="comments" type="text"><br/>
                    <font size="2">Send Mail</font> <input id="sendmailterminate" type="checkbox" value="1" name="sendmailterminate"><br>
                    <input type="submit" id="uterminate" name="uterminate" value="Terminate"/> <font size="1">All mapped sim cards will automatically move to "Apply for Disconnection"</font>
                </form>

                <?php
}
}
?>
    </div>
</div>

<?php
/* if (IsHead() || IsService() || IsAdmin()) {
$transstatus = 6;
?>
<!------------------------------------------------------------------  Replace Unit ------------------------------------------------------------------------>
<?php if ($transstatus == 6) { ?>
<div class="panel">
<div class="paneltitle" align="center">Replace</div>
<div class="panelcontents">
<form method="post" name="myformreplace" id="myformreplace" action="pushcommand.php" onsubmit="return Validate_ReplaceForm(); return false;" enctype="multipart/form-data">
<h3>Replace Unit</h3>
<input type="hidden" name="customerid" id="customerid" value="<?php echo( $customerno ); ?>"/>
<input type="hidden" name="deviceid" value="<?php echo( $uid ); ?>"/>
<table width="60%">
<tr>
<td>Elixir <span style='color:red;'>*</span></td>
<td><select name="uteamid_replace" id="uteamid_replace" onChange="pullunit_replace();">
<option value="0">Select an Elixir</option>
<?php
foreach ($team_allot_array as $thisteam) {
?>
<option value="<?php echo($thisteam->teamid); ?>"><?php echo($thisteam->name); ?></option>
<?php
}
?>
</select>
</td>
</tr>

<tr>
<td>New Unit No.<span style='color:red;'>*</span></td>
<td id="uready_replace_td"><select id="ureplacenewunit" name="ureplacenewunit"><option value="-1">No Units</option></select>
( Allotted Device List with selected Elixir)
</td>
</tr>
<tr>
<td>Send Email</td>
<td><input type="checkbox" name = "sendmailreplace" id="sendmailreplace" value="1"></td>
</tr>

<tr>
<td>Comments</td><td><input name = "comments" id="comments" type="text"></td>
</tr>

</table>

<div><input type="submit" id="ureplacedevice" name="ureplacedevice" value="Submit"/><font size="1">Status for Old Unit will be Bad</font></div>
</form>
<hr/>

<!------------------------------------------------------------------  Replace Simcard ------------------------------------------------------------------------>

<form method="post" name="myformreplacesim" id="myformreplacesim" action="pushcommand.php" onsubmit="return Validate_ReplacesimForm(); return false;"  enctype="multipart/form-data">
<h3>Replace Simcard</h3>
<input type="hidden" name="customerid" value="<?php echo( $customerno ); ?>"/>
<input type="hidden" name="deviceid" value="<?php echo( $uid ); ?>"/>
<table width="60%">
<tr>
<td>Elixir<span style='color:red;'>*</span></td>
<td><select name="uteamid_replace_sim" id="uteamid_replace_sim" onChange="pullsim_replace();">
<option value="0">Select an Elixir</option>
<?php
foreach ($team_allot_array as $thisteam) {
?>
<option value="<?php echo($thisteam->teamid); ?>"><?php echo($thisteam->name); ?></option>
<?php
}
?>
</select>
</td>
</tr>

<tr>
<td>New Simcard No.<span style='color:red;'>*</span></td>
<td id="uready_replace_sim_td"><select name="ureplacenewsimcard" id="ureplacesimcard"><option value="-1">No Simcards</option></select>
( Allotted Simcard List with selected Elixir)
</td>
</tr>
<tr>
<td>Send Email</td>
<td><input type="checkbox" name = "sendmailreplacesimcard" id="sendmailreplacesimcard" value="1"></td>
</tr>
<tr>
<td>Comments</td><td><input name = "comments" id="comments" type="text"></td>
</tr>

</table>

<div><input type="submit" id="ureplacesimcard" name="ureplacesimcard" value="Submit"/></div>
</form>
<hr/>

<!------------------------------------------------------------------  Replace Both ------------------------------------------------------------------------>

<form method="post" name="myformreplaceboth" id="myformreplaceboth" action="pushcommand.php" onsubmit="return ValidateFormreplaceboth(); return false;"  enctype="multipart/form-data">
<h3>Replace Both Unit and Simcard</h3>
<input type="hidden" name="customerid" value="<?php echo( $customerno ); ?>"/>
<input type="hidden" name="deviceid" value="<?php echo( $uid ); ?>"/>
<table width="60%">
<tr>
<td>Elixir <span style='color:red;'>*</span></td>
<td><select name="uteamid_replace_both" id="uteamid_replace_both" onChange="pullunit_replace_both();">
<option value="0">Select an Elixir</option>
<?php
foreach ($team_allot_array as $thisteam) {
?>
<option value="<?php echo($thisteam->teamid); ?>"><?php echo($thisteam->name); ?></option>
<?php
}
?>
</select>
</td>
</tr>

<tr>
<td>Unit No. <span style='color:red;'>*</span></td>
<td id="uready_replace_both_td"><select id="uallotted_replace_both" name="uallotted_replace_both"><option value="-1">No Units</option></select>
( Allotted Device List with selected Elixir)
</td>
</tr>

<tr>
<td>Sim Card No. <span style='color:red;'>*</span></td>
<td id="simready_replace_both_td"><select name="simallotted_replace_both" id="simallotted_replace_both"><option value="-1">No Simcards</option></select>
( Allotted Simcard List with selected Elixir)
</td>
</tr>
<tr>
<td>Send Email</td>
<td><input type="checkbox" name = "sendmailreplaceboth" id="sendmailreplaceboth" value="1"></td>
</tr>
<tr>
<td>Comments</td><td><input name = "comments" id="comments" type="text"></td>
</tr>

</table>
<div><input type="submit" id="bothureplace" name="bothureplace" value="Submit"/></div>
</form>
</div>
</div>

<?php
}
?>
<!------------------------------------------------------------------  Remove Bad ------------------------------------------------------------------------>
<div class="panel">
<div class="paneltitle" align="center">Remove</div>
<div class="panelcontents">

<?php
if ($transstatus == '6') {
?>
<form method="post" name="myformbad" id="myformbad" action="pushcommand.php" onsubmit="return Validate_remove_unitsim(); return false;"  enctype="multipart/form-data">
<h3>Remove Bad Unit and Simcard</h3>
<input type="hidden" name="customerid" value="<?php echo( $customerno ); ?>"/>
<input type="hidden" name="deviceid" value="<?php echo( $uid ); ?>"/>
<table width="60%">
<tr>
<td>Elixir <span style='color:red;'>*</span></td>
<td><select name="uteamid_remove_bad" id="uteamid_remove_bad">
<option value="0">Select an Elixir</option>
<?php
foreach ($team_allot_array as $thisteam) {
?>
<option value="<?php echo($thisteam->teamid); ?>"><?php echo($thisteam->name); ?></option>
<?php
}
?>
</select>
</td>
</tr>
<tr>
<td>Send Email</td>
<td><input type="checkbox" name = "sendmailremovebad" id="sendmailremovebad" value="1"></td>
</tr>
<tr>
<td>Comments</td><td><input name = "comments" id="comments" type="text"></td>
</tr>

</table>
<div><input type="submit" id="bothuremovebad" name="bothuremovebad" value="Submit"/></div>
</form>
<?php } if ($transstatus == 5 || $transstatus == 22 || $transstatus == 23 || $transstatus == 10) { ?>

<!------------------------------------------------------------------  Remove ------------------------------------------------------------------------>

<form method="post" name="myformremove" id="myformremove" action="pushcommand.php" onsubmit="return ValidateForm2(); return false;" enctype="multipart/form-data">
<h3>Remove Unit</h3>
<input type="hidden" name="customerid" value="<?php echo( $customerno ); ?>"/>
<input type="hidden" name="deviceid" value="<?php echo( $uid ); ?>"/>
<table width="60%">
<tr>
<td>Elixir <span style='color:red;'>*</span></td>
<td><select name="uteamid_remove" id="uteamid_remove">
<option value="0">Select an Elixir</option>
<?php
foreach ($team_allot_array as $thisteam) {
?>
<option value="<?php echo($thisteam->teamid); ?>"><?php echo($thisteam->name); ?></option>
<?php
}
?>
</select>
</td>
</tr>
<tr>
<td>Send Email</td>
<td><input type="checkbox" name = "sendmailremove" id="sendmailremove" value="1"></td>
</tr>
<tr>
<td>Comments</td><td><input name = "comments" id="comments" type="text"></td>
</tr>

</table>
<div><input type="submit" id="bothuremove" name="bothuremove" value="Submit"/></div>
</form>
</div>
</div>

<?php }
?>

<hr/>
<form method="post" name="myformreinstall" id="myformreinstall" action="pushcommand.php" onsubmit="return ValidateForm5(); return false;"  enctype="multipart/form-data">
<h3>Re-Install Unit</h3>
<input type="hidden" name="unitid" value="<?php echo($deviceid); ?>"/>
<input type="hidden" name="customerid" value="<?php echo( $customerno ); ?>"/>
<table width="60%">
<tr>
<td>Elixir <span style='color:red;'>*</span></td>
<td><select name="uteamid_reinstall" id="uteamid_reinstall">
<option value="0">Select an Elixir</option>
<?php
foreach ($team_allot_array as $thisteam) {
?>
<option value="<?php echo($thisteam->teamid); ?>"><?php echo($thisteam->name); ?></option>
<?php
}
?>
</select>
</td>
</tr>
<tr>
<td>New Vehicle No<span style='color:red;'>*</span></td>
<td><input type="text" name = "newvehicleno" id="newvehicleno" required></td>
</tr>
<tr>
<td>Send Email</td>
<td><input type="checkbox" name = "sendmailreinstall" id="sendmailreinstall" value="1"></td>
</tr>

</table>
<div><input type="submit" id="reinstall" name="reinstall" value="Submit"/></div>
</form>

<?php
if ($isGenset != '0') {
?>
<div class="panel">
<div class="paneltitle" align="center">Transmitters</div>
<div class="panelcontents">
<form method="post" name="form_maptrans" id="form_maptrans" action="pushcommand.php" onsubmit="validateTransmitter();return false;">
<h3>Allot Transmitter To Vehicle No. <?php echo $vehicleno; ?></h3>
<input type="hidden" name="customerid" value="<?php echo( $customerno ); ?>"/>
<input type="hidden" name="unitid" value="<?php echo( $uid ); ?>"/>
<input type="hidden" name="vehicleid" value="<?php echo( $vehicleid ); ?>"/>
<table width ="100%">
<tr>
<td id="status" style="color: #FE2E2E;display: none;"></td>
</tr>
<tr>
<td style="width: 50%">
<table width="50%">
<tr>
<td>Elixir <span style='color:red;'>*</span></td>
<td><select name="trans1teamid" id="trans1teamid" onchange="getallotedTransmitter1();">
<option value="0">Select an Elixir</option>
<?php
foreach ($team_allot_array as $thisteam) {
?>
<option value="<?php echo($thisteam->teamid); ?>"><?php echo($thisteam->name); ?></option>
<?php
}
?>
</select>
</td>
</tr>
<tr>
<td>Allotted Transmitter List</td>
<td id="allot_trans1_td"></td>
</tr>
<tr>
<td>Comments</td><td><input name = "trans1comments" id="trans1comments" type="text"></td>
</tr>

</table>
</td>
<td style="width: 50%">
<table width="50%">
<tr>
<input type="checkbox" name="trans2_chkbox" id="trans2_chkbox" value="1">
<td>Elixir <span style='color:red;'>*</span></td>
<td><select name="trans2teamid" id="trans2teamid" onchange="getallotedTransmitter2();">
<option value="0">Select an Elixir</option>
<?php
foreach ($team_allot_array as $thisteam) {
?>
<option value="<?php echo($thisteam->teamid); ?>"><?php echo($thisteam->name); ?></option>
<?php
}
?>
</select>
</td>
</tr>
<tr>
<td>Allotted Transmitter List</td>
<td id="allot_trans2_td"></td>
</tr>
<tr>
<td>Comments</td><td><input name = "trans2comments" id="trans2comments" type="text"></td>
</tr>

</table>
</td>

</tr>
</table>
<div><input type="submit" id="allot_trans" name="allot_trans" value="Allot Transmitter"/></div>
</form>

<?php } ?>
<?php
}
 *
 */
include "footer.php";
?>

<script>

    Calendar.setup(
            {
                inputField: "cinstall", // ID of the input field
                ifFormat: "%d-%m-%Y", // the date format
                button: "trigger3" // ID of the button
            });

    Calendar.setup(
            {
                inputField: "cpodate", // ID of the input field
                ifFormat: "%d-%m-%Y", // the date format
                button: "trigger8" // ID of the button
            });

    Calendar.setup(
            {
                inputField: "ucexpiry", // ID of the input field
                ifFormat: "%d-%m-%Y", // the date format
                button: "trigger4" // ID of the button
            });

    Calendar.setup(
            {
                inputField: "sapt_date", // ID of the input field
                ifFormat: "%d-%m-%Y", // the date format
                button: "trigger10" // ID of the button
            });

    function pullunit_replace()
    {
        var uteamid = jQuery('#uteamid_replace').val();
        var customerid = jQuery('#customerid').val();
        jQuery.ajax({
            url: "route_ajax.php",
            type: 'POST',
            cache: false,
            data: {uteamid_replace: uteamid, customer_id: customerid},
            dataType: 'html',
            success: function (html) {
                jQuery("#uready_replace_td").html('');
                jQuery("#uready_replace_td").append(html);

            }
        });
        return false;
    }

    function pullsim_replace()
    {
        var steamid = jQuery('#uteamid_replace_sim').val();
        jQuery.ajax({
            url: "route_ajax.php",
            type: 'POST',
            cache: false,
            data: {steamid_replace: steamid},
            dataType: 'html',
            success: function (html) {
                jQuery("#uready_replace_sim_td").html('');
                jQuery("#uready_replace_sim_td").append(html);
            }
        });
        return false;
    }

    function pullunit_replace_both()
    {
        var uteamid = jQuery('#uteamid_replace_both').val();
        jQuery.ajax({
            url: "route_ajax.php",
            type: 'POST',
            cache: false,
            data: {uteamid_replace_both: uteamid},
            dataType: 'html',
            success: function (html) {
                jQuery("#uready_replace_both_td").html('');
                jQuery("#uready_replace_both_td").append(html);

                // Pull Simcards
                pullsimcards_replace_both();
            }
        });
        return false;
    }

    function pullsimcards_replace_both()
    {
        var steamid = jQuery('#uteamid_replace_both').val();
        jQuery.ajax({
            url: "route_ajax.php",
            type: 'POST',
            cache: false,
            data: {steamid_replace_both: steamid},
            dataType: 'html',
            success: function (html) {
                jQuery("#simready_replace_both_td").html('');
                jQuery("#simready_replace_both_td").append(html);
            }
        });
        return false;
    }

    function pullsimcard_from_unit_replace_both()
    {
        var uallotted = jQuery('#uallotted_replace_both').val();
        var simteamid = jQuery('#uteamid_replace_both').val();

        jQuery.ajax({
            url: "route_ajax.php",
            type: 'POST',
            cache: false,
            data: {uallotted_replace_both: uallotted, simteamid_replace_both: simteamid},
            dataType: 'html',
            success: function (html) {
                jQuery("#simready_replace_both_td").html('');
                jQuery("#simready_replace_both_td").append(html);
            }
        });
        return false;
    }


    // ---------------------  Update Unit


    $(document).ready(function () {
        var device = $('input:radio[name=device]:checked').val();
        if (device == 1) {
            $(".adv").hide();
            $("#ac_sensor").hide();
            $("#double_temp").hide();
        } else {
            $(".adv").show();
            if ($("#sensor").val() != '0' && device == 2)
            {
                $("#ac_sensor").show();
            }
            if ($('input:radio[name=tempsen]:checked').val() == 2 && device == 2) {
                $("#double_temp").show();
            }
        }

    });

    function device_type() {
        var device = $('input:radio[name=device]:checked').val();
        if (device == 1) {
            $(".adv").hide();
            $("#ac_sensor").hide();
            $("#double_temp").hide();
        } else {
            $(".adv").show();
            if ($("#sensor").val() != '0' && device == 2)
            {
                $("#ac_sensor").show();
            }
            if ($('input:radio[name=tempsen]:checked').val() == 2 && device == 2) {
                $("#double_temp").show();
            }
        }
    }
    function sensor_show()
    {
        if ($("#sensor").val() == '1' || $("#sensor").val() == '2' || $("#sensor").val() == '3')
        {
            $("#ac_sensor").show();
        } else {
            $("#ac_sensor").hide();
        }
    }


    function temp_show() {
        if ($('input:radio[name=tempsen]:checked').val() == 0) {
            $("#double_temp").hide();
            $("#canalog").hide();
            $("#advnone").hide();
            $("#adv1").hide();

        } else if ($('input:radio[name=tempsen]:checked').val() == 1) {
            $("#adv1").show();
            $("#advnone").show();
            $("#canalog").show();
            $("#double_temp").hide();
        } else if ($('input:radio[name=tempsen]:checked').val() == 2) {
            $("#adv1").show();
            $("#advnone").show();
            $("#canalog").show();
            $("#double_temp").show();
        } else {
            $("#double_temp").hide();
            $("#canalog").hide();
            $("#adv1").hide();
        }
    }

    function fuelcheckbox() {

        if ($('input:checkbox[name=fuelsensor]:checked').val() == 4) {
            $("#fuelanalogtd").show();
        } else {
            $("#fuelanalogtd").hide();
        }
    }

    function doubleTemp() {

        var temp1 = $("#canalog").val();
        var temp2 = $("#canalog2").val();

        if (temp1 == temp2) {
            alert("Please Select Different Analog Output For Double Temperature");
            $('#canalog2').val(0);
        }
    }

    function ValidateForm1() {
        var cexpiry = $("#cexpiry").val();
        var deviceid = $("#deviceid").val();
        if (cexpiry == "") {
            alert("Expiry Date not be blank.");
            return false;
        } else if (deviceid == "") {
            alert("Device id not be blank");
            return false;
        } else {
            $("#taskform").submit();
        }

    }
    function ValidateForm2() {
        var uteamid_remove = $("#uteamid_remove").val();
        if (uteamid_remove == '0') {
            alert("Please select Elixir for remove unit");
            return false;
        } else {
            $("#uteamid_remove").submit();
        }

    }
    function ValidateForm5() {
        var uteamid_reinstall = $("#uteamid_reinstall").val();
        if (uteamid_reinstall == '0') {
            alert("Please select Elixir for reinstall unit");
            return false;
        } else {
            $("#myformreinstall").submit();
        }
    }
    function Validate_RepairForm() {
        var uteamid_repair = $("#uteamid_repair").val();
        if (uteamid_repair == "0") {
            alert("Please select Elixir.");
            return false;
        } else {
            $("#myformrepair").submit();
        }
    }

    function Validate_ReplacesimForm() {
        var uteamid_replace_sim = $("#uteamid_replace_sim").val();
        var ureplacenewsimcard = $("#ureplacenewsimcard").val();
        if (uteamid_replace_sim == '0') {
            alert("Please select Elixir");
        } else if (ureplacenewsimcard == '0' || ureplacenewsimcard == '-1') {
            alert("Please select new simcard no.");
        } else {
            $('#myformreplacesim').submit();
        }
    }
    function Validate_ReplaceForm() {
        var uteamid_replace = $("#uteamid_replace").val();
        var ureplacenewunit = $("#ureplacenewunit").val();
        if (uteamid_replace == '0') {
            alert("Select elixir for replace unit");
            return false;
        } else if (ureplacenewunit == '-1' || ureplacenewunit == '0') {
            alert("select New unit No.");
            return false;
        }
        $("#myformreplace").submit();
    }

    function ValidateFormreplaceboth() {
        var uteamid_replace_both = $("#uteamid_replace_both").val();
        var uallotted_replace_both = $("#uallotted_replace_both").val();
        var simallotted_replace_both = $("#simallotted_replace_both").val();
        if (uteamid_replace_both == '0') {
            alert("Please select Elixir");
            return false;
        } else if (uallotted_replace_both == '0' || uallotted_replace_both == '-1') {
            alert("Please select unit no.");
            return false;
        } else if (simallotted_replace_both == '0' || simallotted_replace_both == '-1') {
            alert("Please select sim card no.");
        } else {
            $("#myformreplaceboth").submit();
        }
    }

    function Validate_remove_unitsim() {
        var uteamid_remove_bad = $("#uteamid_remove_bad").val();
        if (uteamid_remove_bad == "0") {
            alert("Please select Elixir");
        } else {
            $("#myformbad").submit();
        }
    }


    function ValidateForm() {
        var punitno = $("#punitno").val();

        var cvehicleno = $("#cvehicleno").val();

        if (cvehicleno == "") {
            alert("Vehicle Number should not be blank");
            return false;
        } else if (punitno == '') {
            alert("Please Enter Unit No");
            return false;
        } else {
            $("#myform").submit();
        }

    }

    function getallotedTransmitter1() {
        var trans1teamid = $("#trans1teamid").val();
        if (trans1teamid == '0') {
            alert("Please Select Elixir for Transmitter 1");
        } else {
            jQuery.ajax({
                url: "route_ajax.php",
                type: 'POST',
                cache: false,
                data: {
                    work: 'transmitter_byteamid',
                    allottedid: trans1teamid,
                    transtypeid: 1
                },
                dataType: 'html',
                success: function (html) {
                    if (jQuery("#trans2_chkbox").prop("checked") == true) {
                        jQuery("#allot_trans2_td").html('');
                        jQuery("#allot_trans2_td").append(html);
                    } else {
                        jQuery("#allot_trans1_td").html('');
                        jQuery("#allot_trans1_td").append(html);
                    }
                }
            });
        }
    }
    function getallotedTransmitter2() {
        var trans2teamid = $("#trans2teamid").val();
        if (jQuery("#trans2_chkbox").prop("checked") == false) {
            alert("Please Tick Check Box");
        } else if (jQuery("#trans2_chkbox").prop("checked") && trans2teamid == '0') {
            alert("Please Select Elixir for Transmitter 2");
        } else {
            jQuery.ajax({
                url: "route_ajax.php",
                type: 'POST',
                cache: false,
                data: {
                    work: 'transmitter_byteamid',
                    allottedid: trans2teamid,
                    transtypeid: 2
                },
                dataType: 'html',
                success: function (html) {
                    jQuery("#allot_trans2_td").html('');
                    jQuery("#allot_trans2_td").append(html);
                }
            });
        }
    }
    function validateTransmitter() {
        var trans1teamid = jQuery("#trans1teamid").val();
        var trans2teamid = jQuery("#trans2teamid").val();
        var data = jQuery("#form_maptrans").serialize();
        var dataString = "work=allottrans&" + data;
        if (trans1teamid == '0') {
            alert("Select an Elixir for Transmitter 1");
        } else if (jQuery('input[name=trans2_chkbox]:checked').val() != '1') {
            alert("Please Tick Check Box");
        } else if (jQuery('input[name=trans2_chkbox]:checked').val() != '1' && trans2teamid == '0') {
            alert("Select an Elixir for Transmitter 2");
        } else {
            jQuery.ajax({
                url: "route_ajax.php",
                type: 'POST',
                cache: false,
                data: dataString,
                //dataType: 'html',
                success: function (html) {
                    jQuery("#status").show();
                    jQuery("#status").html(html);

                }
            });
        }
    }

</script>