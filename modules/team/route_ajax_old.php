<?php

include_once("session.php");
include_once("loginorelse.php");
include_once("../../constants/constants.php");
include_once("db.php");
include_once("../../lib/components/gui/datagrid.php");
include_once("../../lib/system/DatabaseManager.php");
include_once("../../lib/system/Sanitise.php");

class testing {
    
}

// -------------------------------------------------------------------- Pull for Allotment ------------------------------------------------------ //


if (isset($_POST['uteamid'])) {
    $teamid = GetSafeValueString($_POST['uteamid'], "string");
    $db = new DatabaseManager();

    $status.="<select name='uallotted' id='uallotted' onChange='pullsimcard_from_unit();'><option value='0'>No Unit</option>";
    if ($teamid != '0') {
        $SQL = sprintf("SELECT unit.unitno, unit.uid,trans_status.status FROM unit INNER JOIN ".DB_PARENT.".trans_status ON trans_status.id = unit.trans_statusid WHERE trans_statusid IN (18,20) AND unit.teamid=" . $teamid);
        $db->executeQuery($SQL);
        $units = Array();
        if ($db->get_rowCount() > 0) {
            while ($row = $db->get_nextRow()) {
                $status.= "<option value='" . $row["uid"] . "'>" . $row["unitno"] . "[ " . $row["status"] . " ]</option>";
            }
        }
    }
    $status.="</select>( Allotted Device List with selected Elixir)";
    echo $status;
}

if (isset($_POST['steamid'])) {
    $teamid = GetSafeValueString($_POST['steamid'], "string");
    $db = new DatabaseManager();

    $status.="<select name='simallotted'><option value='0'>No Simcard</option>";
    if ($teamid != '0') {
        $SQL = sprintf("SELECT simcard.id as simid, simcard.simcardno,trans_status.status FROM simcard INNER JOIN ".DB_PARENT.".trans_status ON trans_status.id = simcard.trans_statusid WHERE trans_statusid IN (19,21) AND simcard.teamid=" . $teamid);
        $db->executeQuery($SQL);
        $units = Array();
        if ($db->get_rowCount() > 0) {
            while ($row = $db->get_nextRow()) {
                $status.= "<option value='" . $row["simid"] . "'>" . $row["simcardno"] . "[ " . $row["status"] . " ]</option>";
            }
        }
    }
    $status.="</select>( Allotted Simcard List with selected Elixir)";
    echo $status;
}

if (isset($_POST['uallotted']) && isset($_POST['simteamid'])) {
    $unitid = GetSafeValueString($_POST['uallotted'], "string");
    $teamid = GetSafeValueString($_POST['simteamid'], "string");
    $simcardid_compare = 0;
    $db = new DatabaseManager();

    $status = "<select name='simallotted'>";

    $SQL = sprintf("SELECT simcard.id as simid, simcard.simcardno,trans_status.status FROM devices INNER JOIN simcard ON simcard.id = devices.simcardid INNER JOIN ".DB_PARENT.".trans_status ON trans_status.id = simcard.trans_statusid WHERE devices.simcardid <> 0 AND devices.uid = " . $unitid . " LIMIT 1");
    $db->executeQuery($SQL);
    if ($db->get_rowCount() > 0) {
        while ($row = $db->get_nextRow()) {
            $simcardid_compare = $row["simid"];
            $status.= "<option value='" . $row["simid"] . "'>" . $row["simcardno"] . "[ " . $row["status"] . " ]</option>";
        }
    }

    $status.="<option value='0'>No Simcard</option>";
    $SQL = sprintf("SELECT simcard.id as simid, simcard.simcardno,trans_status.status FROM simcard INNER JOIN ".DB_PARENT.".trans_status ON trans_status.id = simcard.trans_statusid WHERE trans_statusid IN (19,21) AND simcard.teamid=" . $teamid);
    $db->executeQuery($SQL);
    $units = Array();
    if ($db->get_rowCount() > 0) {
        while ($row = $db->get_nextRow()) {
            if ($row["simid"] != $simcardid_compare) {
                $status.= "<option value='" . $row["simid"] . "'>" . $row["simcardno"] . "[ " . $row["status"] . " ]</option>";
            }
        }
    }
    $status.="</select>( Allotted Simcard List with selected Elixir)";
    echo $status;
}

// -------------------------------------------------------------------- Pull for Return ------------------------------------------------------ //

if (isset($_POST['uteamid_returnall'])) {
    $teamid = GetSafeValueString($_POST['uteamid_returnall'], "string");
    $db = new DatabaseManager();

    $status.="<select name='uallotted_all' id='uallotted_all' onChange='pullallsimcard_from_unit();'><option value='0'>No Unit</option>";
    if ($teamid != '0') {
        $SQL = sprintf("SELECT unit.unitno, unit.uid,trans_status.status FROM unit INNER JOIN ".DB_PARENT.".trans_status ON trans_status.id = unit.trans_statusid WHERE trans_statusid IN (18,20) AND unit.teamid=" . $teamid);
        $db->executeQuery($SQL);
        $units = Array();
        if ($db->get_rowCount() > 0) {
            while ($row = $db->get_nextRow()) {
                $status.= "<option value='" . $row["uid"] . "'>" . $row["unitno"] . "[ " . $row["status"] . " ]</option>";
            }
        }
    }
    $status.="</select>( Allotted / Bad Device List with selected Elixir)";
    echo $status;
}

if (isset($_POST['steamid_all'])) {
    $teamid = GetSafeValueString($_POST['steamid_all'], "string");
    $db = new DatabaseManager();

    $status.="<select name='simallotted_all' id='simallotted_all'><option value='0'>No Simcard</option>";
    if ($teamid != '0') {
        $SQL = sprintf("SELECT simcard.id as simid, simcard.simcardno,trans_status.status FROM simcard INNER JOIN ".DB_PARENT.".trans_status ON trans_status.id = simcard.trans_statusid WHERE trans_statusid IN (19,21) AND simcard.teamid=" . $teamid);
        $db->executeQuery($SQL);
        $units = Array();
        if ($db->get_rowCount() > 0) {
            while ($row = $db->get_nextRow()) {
                $status.= "<option value='" . $row["simid"] . "'>" . $row["simcardno"] . "[ " . $row["status"] . " ]</option>";
            }
        }
    }
    $status.="</select>( Allotted / Bad Simcard List with selected Elixir)";
    echo $status;
}

if (isset($_POST['uallotted_all']) && isset($_POST['simteamid_all'])) {
    $unitid = GetSafeValueString($_POST['uallotted_all'], "string");
    $teamid = GetSafeValueString($_POST['simteamid_all'], "string");
    $simcardid_compare = 0;
    $db = new DatabaseManager();

    $status = "<select name='simallotted_all' id='simallotted_all'>";

    $SQL = sprintf("SELECT simcard.id as simid, simcard.simcardno,trans_status.status FROM devices INNER JOIN simcard ON simcard.id = devices.simcardid INNER JOIN ".DB_PARENT.".trans_status ON trans_status.id = simcard.trans_statusid WHERE devices.simcardid <> 0 AND devices.uid = " . $unitid . " LIMIT 1");
    $db->executeQuery($SQL);
    if ($db->get_rowCount() > 0) {
        while ($row = $db->get_nextRow()) {
            $simcardid_compare = $row["simid"];
            $status.= "<option value='" . $row["simid"] . "'>" . $row["simcardno"] . "[ " . $row["status"] . " ]</option>";
        }
    }

    $status.="<option value='0'>No Simcard</option>";
    $SQL = sprintf("SELECT simcard.id as simid, simcard.simcardno,trans_status.status FROM simcard INNER JOIN ".DB_PARENT.".trans_status ON trans_status.id = simcard.trans_statusid WHERE trans_statusid IN (19,21) AND simcard.teamid=" . $teamid);
    $db->executeQuery($SQL);
    $units = Array();
    if ($db->get_rowCount() > 0) {
        while ($row = $db->get_nextRow()) {
            if ($row["simid"] != $simcardid_compare) {
                $status.= "<option value='" . $row["simid"] . "'>" . $row["simcardno"] . "[ " . $row["status"] . " ]</option>";
            }
        }
    }
    $status.="</select>( Allotted / Bad Simcard List with selected Elixir)";
    echo $status;
}

// -------------------------------------------------------------------- Pull for Replace ------------------------------------------------------ //

if (isset($_POST['uteamid_replace'])) {
    $teamid = GetSafeValueString($_POST['uteamid_replace'], "string");
    $customerno = GetSafeValueString($_POST['customer_id'], "string");
    $db = new DatabaseManager();

    $status.="<select name='ureplacenewunit' id='ureplacenewunit'><option value='0'>No Unit</option>";
    if ($teamid != '0') {
        $SQL = sprintf("SELECT unit.unitno, unit.uid,trans_status.status FROM unit INNER JOIN ".DB_PARENT.".trans_status ON trans_status.id = unit.trans_statusid WHERE trans_statusid IN (18,20) AND unit.teamid=" . $teamid);
        $db->executeQuery($SQL);
        $units = Array();
        if ($db->get_rowCount() > 0) {
            while ($row = $db->get_nextRow()) {
                $status.= "<option value='" . $row["uid"] . "'>" . $row["unitno"] . "[ " . $row["status"] . " ]</option>";
            }
        }
        $SQL = sprintf("SELECT unit.unitno, unit.uid,trans_status.status FROM unit INNER JOIN ".DB_PARENT.".trans_status ON trans_status.id = unit.trans_statusid WHERE trans_statusid IN (22) AND customerno=" . $customerno);
        $db->executeQuery($SQL);
        $units = Array();
        if ($db->get_rowCount() > 0) {
            while ($row = $db->get_nextRow()) {
                $status.= "<option value='" . $row["uid"] . "'>" . $row["unitno"] . "[ " . $row["status"] . " ]</option>";
            }
        }
    }
    $status.="</select>( Allotted Device List with selected Elixir)";
    echo $status;
}

if (isset($_POST['steamid_replace'])) {
    $teamid = GetSafeValueString($_POST['steamid_replace'], "string");
    $db = new DatabaseManager();

    $status.="<select name='ureplacenewsimcard' id='ureplacesimcard'><option value='0'>No Simcard</option>";
    if ($teamid != '0') {
        $SQL = sprintf("SELECT simcard.id as simid, simcard.simcardno,trans_status.status FROM simcard INNER JOIN ".DB_PARENT.".trans_status ON trans_status.id = simcard.trans_statusid WHERE trans_statusid IN (19,21) AND simcard.teamid=" . $teamid);
        $db->executeQuery($SQL);
        $units = Array();
        if ($db->get_rowCount() > 0) {
            while ($row = $db->get_nextRow()) {
                $status.= "<option value='" . $row["simid"] . "'>" . $row["simcardno"] . "[ " . $row["status"] . " ]</option>";
            }
        }
    }
    $status.="</select>( Allotted Simcard List with selected Elixir)";
    echo $status;
}

// -------------------------------------------------------------------- Pull for Replace Both------------------------------------------------------ //

if (isset($_POST['uteamid_replace_both'])) {
    $teamid = GetSafeValueString($_POST['uteamid_replace_both'], "string");
    $db = new DatabaseManager();

    $status.="<select name='uallotted_replace_both' id='uallotted_replace_both' onChange='pullsimcard_from_unit_replace_both();'><option value='0'>No Unit</option>";
    if ($teamid != '0') {
        $SQL = sprintf("SELECT unit.unitno, unit.uid,trans_status.status FROM unit INNER JOIN ".DB_PARENT.".trans_status ON trans_status.id = unit.trans_statusid WHERE trans_statusid IN (18,20) AND unit.teamid=" . $teamid);
        $db->executeQuery($SQL);
        $units = Array();
        if ($db->get_rowCount() > 0) {
            while ($row = $db->get_nextRow()) {
                $status.= "<option value='" . $row["uid"] . "'>" . $row["unitno"] . "[ " . $row["status"] . " ]</option>";
            }
        }
    }
    $status.="</select>( Allotted Device List with selected Elixir)";
    echo $status;
}

if (isset($_POST['steamid_replace_both'])) {
    $teamid = GetSafeValueString($_POST['steamid_replace_both'], "string");
    $db = new DatabaseManager();

    $status.="<select name='simallotted_replace_both' id='simallotted_replace_both'><option value='0'>No Simcard</option>";
    if ($teamid != '0') {
        $SQL = sprintf("SELECT simcard.id as simid, simcard.simcardno,trans_status.status FROM simcard INNER JOIN ".DB_PARENT.".trans_status ON trans_status.id = simcard.trans_statusid WHERE trans_statusid IN (19,21) AND simcard.teamid=" . $teamid);
        $db->executeQuery($SQL);
        $units = Array();
        if ($db->get_rowCount() > 0) {
            while ($row = $db->get_nextRow()) {
                $status.= "<option value='" . $row["simid"] . "'>" . $row["simcardno"] . "[ " . $row["status"] . " ]</option>";
            }
        }
    }
    $status.="</select>( Allotted Simcard List with selected Elixir)";
    echo $status;
}

if (isset($_POST['uallotted_replace_both']) && isset($_POST['simteamid_replace_both'])) {
    $unitid = GetSafeValueString($_POST['uallotted_replace_both'], "string");
    $teamid = GetSafeValueString($_POST['simteamid_replace_both'], "string");
    $simcardid_compare = 0;
    $db = new DatabaseManager();

    $status = "<select name='simallotted_replace_both' id='simallotted_replace_both'>";

    $SQL = sprintf("SELECT simcard.id as simid, simcard.simcardno,trans_status.status FROM devices INNER JOIN simcard ON simcard.id = devices.simcardid INNER JOIN ".DB_PARENT.".trans_status ON trans_status.id = simcard.trans_statusid WHERE devices.simcardid <> 0 AND devices.uid = " . $unitid . " LIMIT 1");
    $db->executeQuery($SQL);
    if ($db->get_rowCount() > 0) {
        while ($row = $db->get_nextRow()) {
            $simcardid_compare = $row["simid"];
            $status.= "<option value='" . $row["simid"] . "'>" . $row["simcardno"] . "[ " . $row["status"] . " ]</option>";
        }
    }

    $status.="<option value='0'>No Simcard</option>";
    $SQL = sprintf("SELECT simcard.id as simid, simcard.simcardno,trans_status.status FROM simcard INNER JOIN ".DB_PARENT.".trans_status ON trans_status.id = simcard.trans_statusid WHERE trans_statusid IN (19,21) AND simcard.teamid=" . $teamid);
    $db->executeQuery($SQL);
    $units = Array();
    if ($db->get_rowCount() > 0) {
        while ($row = $db->get_nextRow()) {
            if ($row["simid"] != $simcardid_compare) {
                $status.= "<option value='" . $row["simid"] . "'>" . $row["simcardno"] . "[ " . $row["status"] . " ]</option>";
            }
        }
    }
    $status.="</select>( Allotted Simcard List with selected Elixir)";
    echo $status;
}

///////////////////////get bad units issue////////////////////////////////

if (isset($_POST['urepairuid_issues'])) {
    $uid = GetSafeValueString($_POST['urepairuid_issues'], "string");
    $db = new DatabaseManager();
    if ($uid != '0') {
        $SQL = sprintf("SELECT issue from unit where uid=" . $uid);
        $db->executeQuery($SQL);
        $units = Array();
        if ($db->get_rowCount() > 0) {
            while ($row = $db->get_nextRow()) {
                $issue = $row["issue"];
            }
        }
    }

    echo $issue;
}
/////////////////// get transmitter//////////////////////
if (isset($_POST['work']) && $_POST['work'] == 'alltransmitter') {
    $db = new DatabaseManager();
    $pdo = $db->CreatePDOConn();
    $sp_params1 = "''"
            . ",''"
            . ",'" . 0 . "'"
            . ",'" . 2 . "'"
            . ",'" . 0 . "'"
    ;
    $QUERY1 = $db->PrepareSP('get_transmitter', $sp_params1);
    $res1 = $pdo->query($QUERY1);
    $status = "<select name = 'alltrans' id = 'alltrans'>";
    $status.= "<option value='0'>Select Transmitter</option>";
    if ($res1) {
        while ($row = $res1->fetch(PDO::FETCH_ASSOC)) {
            $status.= "<option value='" . $row["transmitterid"] . "'>" . $row["transmitterno"] . "[" . $row["status"] . "]" . "</option>";
        }
    }
    $status.="</select>";
    $db->ClosePDOConn($pdo);
    echo $status;
} else if (isset($_POST['work']) && $_POST['work'] == 'transmitter_byteamid') {
    $db = new DatabaseManager();
    $pdo = $db->CreatePDOConn();
    $transmittertype = GetSafeValueString($_POST['transtypeid'], "int");
    $alloted_teamid = GetSafeValueString($_POST['allottedid'], "string");
    $custno = "0";
    $sp_params1 = "''"
            . ",''"
            . ",'" . $alloted_teamid . "'"
            . ",''"
            . ",'" . $custno . "'"
    ;
    $QUERY1 = $db->PrepareSP('get_transmitter', $sp_params1);
    $res1 = $pdo->query($QUERY1);
    $status = "<select name='transallotted" . $transmittertype . "' id='transallotted" . $transmittertype . "'>";
    $status.= "<option value='0'>No Transmitter</option>";
    if ($res1) {
        while ($row = $res1->fetch(PDO::FETCH_ASSOC)) {
            $status.= "<option value='" . $row["transmitterid"] . "'>" . $row["transmitterno"] . "[" . $row["status"] . "]" . "</option>";
        }
    }
    $status.="</select>";
    $db->ClosePDOConn($pdo);
    echo $status;
} else if (isset($_POST['work']) && $_POST['work'] == 'allot_transmitter') {
    $today = date('Y-m-d H:i:s');
    $loginid = GetLoggedInUserId();
    $db = new DatabaseManager();
    $pdo = $db->CreatePDOConn();
    $alloted_teamid = GetSafeValueString($_POST['allottedid'], "string");
    $trans_status = '18';
    $transid = GetSafeValueString($_POST['transid'], "string");
    $transno = GetSafeValueString($_POST['transno'], "string");
    $comments = GetSafeValueString($_POST['comments'], "string");
    $status = "Alloted Successfully";
    try {
        $sp_params1 = "'" . $transid . "'"
                . ",'" . $alloted_teamid . "'"
                . ",'" . $trans_status . "'"
                . ",'" . $comments . "'"
                . ",'" . 0 . "'"
                . ",'" . $today . "'"
                . ",'" . $loginid . "'"
        ;
        $QUERY1 = $db->PrepareSP('update_transmitter', $sp_params1);
        $pdo->query($QUERY1);
    } catch (Exception $e) {
        $status = "Caught exception: " . $e->getMessage();
    }
    echo $status;
} else if (isset($_POST['work']) && $_POST['work'] == 'reallot_transmitter') {
    $today = date('Y-m-d H:i:s');
    $loginid = GetLoggedInUserId();
    $db = new DatabaseManager();
    $pdo = $db->CreatePDOConn();
    $alloted_teamid = GetSafeValueString($_POST['allottedid'], "string");
    $trans_status = '18';
    $transid = GetSafeValueString($_POST['transid'], "string");
    $comments = GetSafeValueString($_POST['comments'], "string");
    $transno = GetSafeValueString($_POST['transno'], "string");
    $status = "Realloted Successfully";
    try {
        $sp_params1 = "'" . $transid . "'"
                . ",'" . $alloted_teamid . "'"
                . ",'" . $trans_status . "'"
                . ",'" . $comments . "'"
                . ",'" . 0 . "'"
                . ",'" . $today . "'"
                . ",'" . $loginid . "'"
        ;
        $QUERY1 = $db->PrepareSP('update_transmitter', $sp_params1);
        $pdo->query($QUERY1);
    } catch (Exception $e) {
        $status = "Caught exception: " . $e->getMessage();
    }
    echo $status;
} else if (isset($_POST['work']) && $_POST['work'] == 'inRepairtransmitter') {
    $db = new DatabaseManager();
    $pdo = $db->CreatePDOConn();
    $trans_status = "7";
    $custno = "-1";
    $sp_params1 = "''"
            . ",''"
            . ",''"
            . ",'" . $trans_status . "'"
            . ",'" . $custno . "'"
    ;
    $QUERY1 = $db->PrepareSP('get_transmitter', $sp_params1);
    $res1 = $pdo->query($QUERY1);
    $status.= "<select name='transrepair' id='transrepair'>";
    if ($res1->rowCount() > 0) {
        while ($row = $res1->fetch(PDO::FETCH_ASSOC)) {
            $status.= "<option value='" . $row["transmitterid"] . "'>" . $row["transmitterno"] . "[" . $row["status"] . "]" . "</option>";
        }
    } else {
        $status.="<option value = '0'> No Transmitters In Repair </option>";
    }
    $status.="</select>";
    $status.="(Under Repair Device List)";
    $db->ClosePDOConn($pdo);
    echo $status;
} else if (isset($_POST['work']) && $_POST['work'] == 'gettransmitterbystatus') {
    $db = new DatabaseManager();
    $pdo = $db->CreatePDOConn();
    $status = GetSafeValueString($_POST['status'], "string");
    $sp_params1 = "''"
            . ",''"
            . ",'" . 0 . "'"
            . ",'" . $status . "'"
            . ",''"
    ;
    $QUERY1 = $db->PrepareSP('get_transmitter', $sp_params1);
    $res1 = $pdo->query($QUERY1);
    $html = '';
    $html.= "<select name='trans_sendrepair' id='trans_sendrepair'>";
    if ($res1->rowCount() > 0) {
        while ($row = $res1->fetch(PDO::FETCH_ASSOC)) {
            $html.= "<option value='" . $row["transmitterid"] . "'>" . $row["transmitterno"] . "[" . $row["status"] . "]" . "</option>";
        }
    } else {
        $html.="<option value='0'>No Transmitters</option>";
    }
    $html.="</select>";
    $html.="(Fresh / Repaired / Suspected Device List)";
    $db->ClosePDOConn($pdo);
    echo $html;
} else if (isset($_POST['work']) && $_POST['work'] == 'trans_testing') {
    $db = new DatabaseManager();
    $pdo = $db->CreatePDOConn();
    $today = date('Y-m-d H:i:s');
    $loginid = GetLoggedInUserId();
    $trans_status = GetSafeValueString($_POST['trans_status'], "string");
    $transid = GetSafeValueString($_POST['transid'], "string");
    $comments = GetSafeValueString($_POST['comments'], "string");
    $transno = GetSafeValueString($_POST['transno'], "string");
    $status = "Updated Successfully";
    try {
        $sp_params1 = "'" . $transid . "'"
                . ",'" . 0 . "'"
                . ",'" . $trans_status . "'"
                . ",'" . $comments . "'"
                . ",'" . 0 . "'"
                . ",'" . $today . "'"
                . ",'" . $loginid . "'"
        ;
        $QUERY1 = $db->PrepareSP('update_transmitter', $sp_params1);
        $pdo->query($QUERY1);
        $db->ClosePDOConn($pdo);
    } catch (Exception $e) {
        $status = "Caught exception: " . $e->getMessage();
    }
    echo $status;
} else if (isset($_POST['work']) && $_POST['work'] == 'sendTransmitterTorepair') {
    $db = new DatabaseManager();
    $pdo = $db->CreatePDOConn();
    $sp_params1 = "''"
            . ",''"
            . ",''"
            . ",'" . 3 . "'"
            . ",'" . 0 . "'"
    ;
    $QUERY1 = $db->PrepareSP('get_transmitter', $sp_params1);
    $res1 = $pdo->query($QUERY1);
    $status.= "<select name='transrepairsend' id='transrepairsend'>";

    if ($res1->rowCount() > 0) {
        while ($row = $res1->fetch(PDO::FETCH_ASSOC)) {
            $status.= "<option value='" . $row["transmitterid"] . "'>" . $row["transmitterno"] . "[" . $row["status"] . "]" . "</option>";
        }
    } else {
        $status.="<option value = '0'> No Bad Transmitters </option>";
    }
    $status.="</select>";
    $status.="(Under Repair Device List)";
    $db->ClosePDOConn($pdo);
    echo $status;
} else if (isset($_POST['work']) && $_POST['work'] == 'saveSendTorepair') {
    $db = new DatabaseManager();
    $pdo = $db->CreatePDOConn();
    $today = date('Y-m-d H:i:s');
    $loginid = GetLoggedInUserId();
    $transid = GetSafeValueString($_POST['transid'], "string");
    $comments = GetSafeValueString($_POST['comments'], "string");
    $custno = "-1";
    $teamid = "0";
    $trans_status = "7";
    $status = "Updated Successfully";
    try {
        $sp_params1 = "'" . $transid . "'"
                . ",'" . $teamid . "'"
                . ",'" . $trans_status . "'"
                . ",'" . $comments . "'"
                . ",'" . $custno . "'"
                . ",'" . $today . "'"
                . ",'" . $loginid . "'"
        ;
        $QUERY1 = $db->PrepareSP('update_transmitter', $sp_params1);
        $pdo->query($QUERY1);
        $db->ClosePDOConn($pdo);
    } catch (Exception $e) {
        $status = "Caught exception: " . $e->getMessage();
    }
    $db->ClosePDOConn($pdo);
    echo $status;
} else if (isset($_POST['work']) && $_POST['work'] == 'saveReceiverepair') {
    $db = new DatabaseManager();
    $pdo = $db->CreatePDOConn();
    $today = date('Y-m-d H:i:s');
    $loginid = GetLoggedInUserId();
    $transid = GetSafeValueString($_POST['transid'], "string");
    $comments = GetSafeValueString($_POST['comments'], "string");
    $custno = "0";
    $teamid = "0";
    $trans_status = "4";
    $status = "Updated Successfully";
    try {
        $sp_params1 = "'" . $transid . "'"
                . ",'" . $teamid . "'"
                . ",'" . $trans_status . "'"
                . ",'" . $comments . "'"
                . ",'" . $custno . "'"
                . ",'" . $today . "'"
                . ",'" . $loginid . "'"
        ;
        $QUERY1 = $db->PrepareSP('update_transmitter', $sp_params1);
        $pdo->query($QUERY1);
        $db->ClosePDOConn($pdo);
    } catch (Exception $e) {
        $status = "Caught exception: " . $e->getMessage();
    }
    $db->ClosePDOConn($pdo);
    echo $status;
} else if (isset($_POST['work']) && $_POST['work'] == 'saveReplacerepairtrans') {
    $db = new DatabaseManager();
    $pdo = $db->CreatePDOConn();
    $today = date('Y-m-d H:i:s');
    $loginid = GetLoggedInUserId();
    $transid = GetSafeValueString($_POST['transid'], "string");
    $newtransno = GetSafeValueString($_POST['newtransno'], "string");
    $comments = GetSafeValueString($_POST['comments'], "string");
    $custno = "0";
    $teamid = "0";
    $trans_status = "4";
    //check if transmitterno exists
    $sp_params1 = "''"
            . ",'" . $newtransno . "'"
            . ",''"
            . ",''"
            . ",''"
    ;
    $QUERY1 = $db->PrepareSP('get_transmitter', $sp_params1);
    $transList = $pdo->query($QUERY1);
    if ($transList->rowCount() > 0) {
        $status = "Transmitter Already Exists";
    } else {

        try {
            $status = "Updated Successfully";
            $pdo1 = $db->CreatePDOConn();

            $sp_params2 = "'" . $transid . "'"
                    . ",'" . $teamid . "'"
                    . ",'" . 9 . "'"
                    . ",'" . $comments . "'"
                    . ",'" . -1 . "'"
                    . ",'" . $today . "'"
                    . ",'" . $loginid . "'"
            ;
            $QUERY2 = $db->PrepareSP('update_transmitter', $sp_params2);
            $pdo1->query($QUERY2);
            $db->ClosePDOConn($pdo1);
            // insert new transmitter
            $pdo3 = $db->CreatePDOConn();
            $sp_params3 = "'" . $newtransno . "'"
                    . ",'" . $teamid . "'"
                    . ",'" . $custno . "'"
                    . ",'" . 1 . "'"
                    . ",'" . $today . "'"
                    . ",'" . $loginid . "'"
                    . ",'" . $comments . "'"
            ;
            $QUERY3 = $db->PrepareSP('insert_transmitter', $sp_params3);
            $pdo3->query($QUERY3);
            $db->ClosePDOConn($pdo3);
        } catch (Exception $e) {
            $status = "Caught exception: " . $e->getMessage();
        }
    }
    $db->ClosePDOConn($pdo);
    echo $status;
} else if (isset($_POST['work']) && $_POST['work'] == 'allottrans') {
    //print_r($_POST);
    $status = 'Not Alloted';
    $db = new DatabaseManager();
    $pdo = $db->CreatePDOConn();
    $today = date('Y-m-d H:i:s');
    $loginid = GetLoggedInUserId();
    $unitid = GetSafeValueString($_POST['unitid'], "int");
    $vehid = GetSafeValueString($_POST['vehicleid'], "int");
    $trans1teamid = GetSafeValueString($_POST['trans1teamid'], "int");
    $trans2teamid = GetSafeValueString($_POST['trans2teamid'], "int");
    $transallotted1 = GetSafeValueString($_POST['transallotted1'], "string");
    $transallotted2 = GetSafeValueString($_POST['transallotted2'], "string");
    $trans2comments = GetSafeValueString($_POST['trans2comments'], "string");
    $trans1comments = GetSafeValueString($_POST['trans1comments'], "string");
    $custno = GetSafeValueString($_POST['customerid'], "string");
    $teamid = "0";
    $trans_status = "5";
    if ($transallotted1 != '' && $transallotted1 != '0') {
        try {
            $status = "Alloted Transmitter1 Successfully";
            $pdo1 = $db->CreatePDOConn();

            $sp_params1 = "'" . $transallotted1 . "'"
                    . ",'" . $teamid . "'"
                    . ",'" . $trans_status . "'"
                    . ",'" . $trans1comments . "'"
                    . ",'" . $custno . "'"
                    . ",'" . $today . "'"
                    . ",'" . $loginid . "'"
            ;
            $QUERY1 = $db->PrepareSP('update_transmitter', $sp_params1);
            $pdo1->query($QUERY1);
            $db->ClosePDOConn($pdo1);
        } catch (Exception $e) {
            $status = "Caught exception: " . $e->getMessage();
        }
        $SQL1 = sprintf("UPDATE vehicle SET transmitter1=%d WHERE vehicleid=%d AND customerno=%d", $transallotted1, $vehid, $custno);
        $db->executeQuery($SQL1);
    }
    if ($transallotted2 != '' && $transallotted2 != '0') {
        try {
            $status = "Alloted Transmitter 1&2 Successfully";
            $pdo2 = $db->CreatePDOConn();

            $sp_params2 = "'" . $transallotted2 . "'"
                    . ",'" . $teamid . "'"
                    . ",'" . $trans_status . "'"
                    . ",'" . $trans2comments . "'"
                    . ",'" . $custno . "'"
                    . ",'" . $today . "'"
                    . ",'" . $loginid . "'"
            ;
            $QUERY2 = $db->PrepareSP('update_transmitter', $sp_params2);
            $pdo2->query($QUERY2);
            $db->ClosePDOConn($pdo2);
        } catch (Exception $e) {
            $status = "Caught exception: " . $e->getMessage();
        }
        $SQL2 = sprintf("UPDATE vehicle SET transmitter2=%d WHERE vehicleid=%d AND customerno=%d", $transallotted2, $vehid, $custno);
        $db->executeQuery($SQL2);
    }
    $db->ClosePDOConn($pdo);
    echo $status;
}

if (isset($_REQUEST['action'])) {
    if ($_REQUEST['action'] == "genaratecode") {
        $i = 0;
        $tmp = mt_rand(1, 9);
        do {
            $tmp .= mt_rand(0, 9);
        } while (++$i < 14);
        echo '0' . $tmp;
    }

    if ($_REQUEST['action'] == "purchasetrace") {
        $arr_p = array();
        $message = "";
        $today = date("Y-m-d H:i:s");
        $punitno = $_REQUEST["unitnumber"];
        $devicetype = $_REQUEST["unittypeid"];
        $comments = $_REQUEST["comments"];
        $activation_code = $_REQUEST["activationcode"];

        if ($devicetype == 3) {
            $punitno = $_REQUEST["unitnumber"];
        } else {
            $punitno = '0' . $_REQUEST["unitnumber"];
        }

        $db = new DatabaseManager();
        $sql = sprintf("SELECT `unitno` FROM " . DB_Trace . ".`unit` WHERE unitno='" . $punitno . "'");
        $db->executeQuery($sql);
        if ($db->get_rowCount() > 0) {
            $message = "Unit Number Already exists.";
        } else if ($message == "") {
            $SQLUnit = sprintf("INSERT INTO " . DB_Trace . ".`unit`(`customerno`,`unitno`,`trans_statusid`,`comments`,`unittypeid`,created_on) VALUES (1,'%s',1,'%s','%d','%s')", $punitno, $comments, $devicetype, $today);
            $db->executeQuery($SQLUnit);
            $unitid = $db->get_insertedId();
            // Populate Devices
            $devicekey = mt_rand();
            $expiry = date('Y-m-d', strtotime('+1 year'));
            $SQL = sprintf("INSERT INTO " . DB_Trace . ".devices (`customerno` ,`devicekey`,`registeredon`,`uid`,`expirydate`)VALUES (1, '%s', '%s', '%d', '%s')", $devicekey, Sanitise::DateTime($today), $unitid, $expiry);
            $db->executeQuery($SQL);

            // Populate Vehicles
            $Query = "INSERT INTO " . DB_Trace . ".vehicle (vehicleno,customerno, uid) VALUES ('%s',%d, %d)";
            $SQL = sprintf($Query, 'Not Allocated', 1, $unitid);
            $db->executeQuery($SQL);
            $vehicleid = $db->get_insertedId();

            //$activation_code = rand(1000,9000);
            // Update Unit
            $SQL = sprintf("UPDATE " . DB_Trace . ".unit SET activationcode ='%s', vehicleid=%d where uid=%d", $activation_code, $vehicleid, $unitid);
            $db->executeQuery($SQL);

            //Insert into trace unitcommand details

            $SQL = sprintf("INSERT INTO " . DB_Trace . ".unitCommandDetails (`uid` ,`customerno`,`created_on`)VALUES ('%d', '%d', '%s')", $unitid, 1, Sanitise::DateTime($today));
            $db->executeQuery($SQL);

            // Create unit directory
            $relativepath = "../../../trace";
            if (!is_dir($relativepath . '/customer/1/unitno/' . $punitno)) {
                // Directory doesn't exist.
                mkdir($relativepath . '/customer/1/unitno/' . $punitno, 0777, true) or die("Could not create directory");
            }

            if (!is_dir($relativepath . '/customer/1/unitno/' . $punitno . '/sqlite')) {
                // Directory doesn't exist.
                mkdir($relativepath . '/customer/1/unitno/' . $punitno . '/sqlite', 0777, true) or die("Could not create directory");
            }
        }

        if (!empty($message)) {
            $arr_p['status'] = "failure";
            $arr_p['message'] = $message;
        } else {
            $arr_p['status'] = "sucess";
            $arr_p['message'] = "Unit added sucessfully";
        }
        echo json_encode($arr_p);
    }
}
?>